<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\ActivityLog;
use App\Services\ActivityLogger;
use App\Services\ActivityLogService;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class LogUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $userBefore */
        $userBefore = Auth::user();
        $startedAt = now();
        $lastLogId = Schema::hasTable('activity_logs') ? (int) ActivityLog::max('id') : 0;
        $target = collect($request->route()?->parameters() ?? [])->first(fn($value)=>$value instanceof Model);
        $before = $target instanceof Model ? $target->getAttributes() : [];

        try {
            $response = $next($request);
        } catch (Throwable $exception) {
            $status = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : null;
            if ($status === 403 && Schema::hasTable('activity_logs')) {
                try {
                    ActivityLogService::log($request,'Permission Denied','Security',$target,'Unauthorized access attempt was denied.',[],[],$userBefore);
                } catch (Throwable $loggingException) {
                    Log::warning('Activity logging failed.', ['error'=>$loggingException->getMessage()]);
                }
            }
            throw $exception;
        }

        $isLogMaintenance = str_starts_with($request->route()?->getName() ?? '', 'admin.activity-logs.');

        $isReportPreview = $request->route()?->getName() === 'admin.reports.index' && $request->boolean('preview');
        $shouldLog = ! $request->isMethod('get') || $request->route()?->getName() === 'google.callback' || $isReportPreview;

        $alreadyLogged = Schema::hasTable('activity_logs') && ActivityLog::where('id','>',$lastLogId)->where('route_name',$request->route()?->getName())->where('ip_address',$request->ip())->exists();
        if ($response->getStatusCode() === 403 && !$alreadyLogged && Schema::hasTable('activity_logs')) {
            try {
                ActivityLogService::log($request,'Permission Denied','Security',$target,'Unauthorized access attempt was denied.',[],[],$userBefore);
                $alreadyLogged=true;
            } catch (Throwable $exception) {
                Log::warning('Activity logging failed.', ['error'=>$exception->getMessage()]);
            }
        }
        if ($shouldLog && ! $isLogMaintenance && ! $alreadyLogged && $response->getStatusCode() < 400 && Schema::hasTable('activity_logs')) {
            try {
                /** @var User|null $actor */
                $actor = $userBefore ?? Auth::user();
                if ($isReportPreview) {
                    ActivityLogService::log($request,'Report Preview','Reports',$request->string('report')->headline()->toString(),$actor?->name.' previewed a filtered report.',[],[],$actor);
                } else {
                    $target ??= $request->isMethod('post') ? $this->createdTarget($request,$startedAt) : null;
                    $after = $target instanceof Model ? $target->getAttributes() : [];
                    $changed = collect($after)->filter(fn($value,$key)=>array_key_exists($key,$before) && $before[$key] !== $value)->keys();
                    ActivityLogger::record($request,$actor,$target,collect($before)->only($changed)->all(),collect($after)->only($changed)->all());
                }
            } catch (Throwable $exception) {
                Log::warning('Activity logging failed.', ['error' => $exception->getMessage()]);
            }
        }

        return $response;
    }

    private function createdTarget(Request $request, $startedAt): ?Model
    {
        $route=$request->route()?->getName() ?? '';
        $class=match(true){str_starts_with($route,'admin.courses.')=>\App\Models\Course::class,str_starts_with($route,'admin.sections.')=>\App\Models\CourseSection::class,str_starts_with($route,'admin.lessons.')=>\App\Models\Lesson::class,str_starts_with($route,'admin.examples.')=>\App\Models\LessonExample::class,str_starts_with($route,'admin.videos.')=>\App\Models\Video::class,str_starts_with($route,'admin.video-playlists.')=>\App\Models\VideoPlaylist::class,str_starts_with($route,'admin.quiz-categories.')=>\App\Models\QuizCategory::class,str_starts_with($route,'admin.programming-languages.')=>\App\Models\ProgrammingLanguage::class,str_starts_with($route,'admin.quiz-questions.')=>\App\Models\QuizQuestion::class,str_starts_with($route,'admin.quizzes.')=>\App\Models\Quiz::class,str_starts_with($route,'contact.')=>\App\Models\Contact::class,default=>null};
        return $class ? $class::where('created_at','>=',$startedAt)->latest('id')->first() : null;
    }
}
