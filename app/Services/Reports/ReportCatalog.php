<?php

namespace App\Services\Reports;

use App\Models\ActivityLog;
use App\Models\Contact;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Lesson;
use App\Models\LessonExample;
use App\Models\ProgrammingLanguage;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizCategory;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoPlaylist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReportCatalog
{
    public function types(User $actor): array
    {
        $types = [
            'users' => 'Users', 'courses' => 'Courses', 'course-sections' => 'Course Sections',
            'lessons' => 'Lessons', 'code-examples' => 'Code Examples', 'videos' => 'Videos',
            'video-playlists' => 'Video Playlists', 'quizzes' => 'Quizzes', 'quiz-results' => 'Quiz Results',
            'contact-messages' => 'Contact Messages',
        ];

        if ($actor->isSuperAdmin()) {
            $types = ['users' => 'Users', 'admins' => 'Admins', ...array_slice($types, 1, null, true), 'activity-logs' => 'Activity Logs'];
        }

        return $types;
    }

    public function options(): array
    {
        return [
            'courses' => Course::orderBy('title')->pluck('title', 'id'),
            'sections' => CourseSection::orderBy('title')->pluck('title', 'id'),
            'playlists' => VideoPlaylist::orderBy('name')->pluck('name', 'id'),
            'languages' => ProgrammingLanguage::orderBy('name')->pluck('name', 'id'),
            'categories' => QuizCategory::orderBy('title')->pluck('title', 'id'),
            'quizzes' => Quiz::orderBy('title')->pluck('title', 'id'),
            'users' => User::orderBy('name')->pluck('name', 'id'),
            'modules' => ActivityLog::distinct()->orderBy('module')->pluck('module'),
            'actions' => ActivityLog::distinct()->orderBy('action')->pluck('action'),
        ];
    }

    public function make(string $type, Request $request, User $actor): array
    {
        abort_unless(array_key_exists($type, $this->types($actor)), 403);

        $definition = match ($type) {
            'users', 'admins' => $this->users($type, $request, $actor),
            'courses' => $this->courses($request),
            'course-sections' => $this->sections($request),
            'lessons' => $this->lessons($request),
            'code-examples' => $this->examples($request),
            'videos' => $this->videos($request),
            'video-playlists' => $this->playlists($request),
            'quizzes' => $this->quizzes($request),
            'quiz-results' => $this->quizResults($request),
            'contact-messages' => $this->contacts($request),
            'activity-logs' => $this->activityLogs($request),
        };

        $mapper = $definition['row'];
        $definition['row'] = fn ($record) => array_map(fn ($value) => blank($value) ? '-' : $value, $mapper($record));

        return [...$definition, 'type' => $type, 'filters' => $this->appliedFilters($request)];
    }

    private function users(string $type, Request $r, User $actor): array
    {
        $query = User::query()->when($type === 'admins', fn (Builder $q) => $q->whereIn('role', [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN]))
            ->when($type === 'users', fn (Builder $q) => $actor->isSuperAdmin() ? $q : $q->where('role', User::ROLE_USER))
            ->when($r->filled('role'), fn (Builder $q) => $q->where('role', $r->string('role')))
            ->when($r->filled('status'), fn (Builder $q) => $q->where('status', $r->string('status')))
            ->when($r->filled('provider'), fn (Builder $q) => $r->string('provider')->toString() === 'google' ? $q->whereNotNull('google_id') : $q->whereNull('google_id'));
        $query->when($r->filled('search'), fn (Builder $q) => $q->where(fn (Builder $nested) => $nested->where('name','like','%'.$r->string('search').'%')->orWhere('email','like','%'.$r->string('search').'%')));
        $this->dates($query, $r);
        return ['title' => $type === 'admins' ? 'Admins Report' : 'Users Report', 'headings' => ['Name','Email','Auth Provider','Role','Status','Registered Date'], 'query' => $query->oldest(),
            'row' => fn (User $u) => [$u->name,$u->email,$u->authProviderLabel(),str($u->role)->headline()->toString(),str($u->status)->headline()->toString(),$this->date($u->created_at)]];
    }

    private function courses(Request $r): array
    {
        $q = Course::query()->withCount(['sections','lessons'])->when($r->filled('language'), fn (Builder $q) => $q->where('programming_language', $r->string('language')))->when($r->filled('difficulty'), fn (Builder $q) => $q->where('difficulty_level', $r->string('difficulty')))->when($r->filled('status'), fn (Builder $q) => $q->where('status', $r->string('status')));
        $this->dates($q,$r);
        return ['title'=>'Courses Report','headings'=>['Course','Language','Difficulty','Status','Sections','Lessons','Created Date'],'query'=>$q->oldest(),'row'=>fn(Course $m)=>[$m->title,$m->programming_language,$m->difficulty_level,$m->status,$m->sections_count,$m->lessons_count,$this->date($m->created_at)]];
    }

    private function sections(Request $r): array
    {
        $q=CourseSection::with('course')->withCount('lessons')->when($r->filled('course_id'),fn(Builder $q)=>$q->where('course_id',$r->integer('course_id')));
        return ['title'=>'Course Sections Report','headings'=>['Course','Section','Order','Lessons','Created Date'],'query'=>$q->orderBy('course_id')->orderBy('order_number'),'row'=>fn(CourseSection $m)=>[$m->course?->title,$m->title,$m->order_number,$m->lessons_count,$this->date($m->created_at)]];
    }

    private function lessons(Request $r): array
    {
        $q=Lesson::with(['course','section'])->when($r->filled('course_id'),fn(Builder $q)=>$q->where('course_id',$r->integer('course_id')))->when($r->filled('section_id'),fn(Builder $q)=>$q->where('section_id',$r->integer('section_id')))->when($r->filled('status'),fn(Builder $q)=>$q->where('status',$r->string('status')));
        return ['title'=>'Lessons Report','headings'=>['Course','Section','Lesson','Difficulty','Status','Order','Learning Time','Created Date'],'query'=>$q->orderBy('course_id')->orderBy('order_number'),'row'=>fn(Lesson $m)=>[$m->course?->title,$m->section?->title ?: 'Unsectioned',$m->title,$m->difficulty_level,$m->status,$m->order_number,$m->estimated_learning_time ? $m->estimated_learning_time.' min' : '',$this->date($m->created_at)]];
    }

    private function examples(Request $r): array
    {
        $q=LessonExample::with('lesson.course')->when($r->filled('course_id'),fn(Builder $q)=>$q->whereHas('lesson',fn(Builder $l)=>$l->where('course_id',$r->integer('course_id'))));
        return ['title'=>'Code Examples Report','headings'=>['Course','Lesson','Example','Expected Output','Created Date'],'query'=>$q->oldest(),'row'=>fn(LessonExample $m)=>[$m->lesson?->course?->title,$m->lesson?->title,$m->title,$this->plain($m->expected_output),$this->date($m->created_at)]];
    }

    private function videos(Request $r): array
    {
        $q=Video::with('playlist')->when($r->filled('search'),fn(Builder $q)=>$q->where(fn(Builder $nested)=>$nested->where('title','like','%'.$r->string('search').'%')->orWhere('description','like','%'.$r->string('search').'%')))->when($r->filled('playlist_id'),fn(Builder $q)=>$q->where('playlist_id',$r->integer('playlist_id')))->when($r->filled('status'),fn(Builder $q)=>$q->where('status',$r->string('status')));
        $this->dates($q,$r);
        return ['title'=>'Videos Report','headings'=>['Playlist','Video','Duration','Order','Status','YouTube Video ID','Created Date'],'query'=>$q->orderBy('playlist_id')->orderBy('order_number'),'row'=>fn(Video $m)=>[$m->playlist?->name,$m->title,$m->duration,$m->order_number,$m->status,$m->youtube_video_id,$this->date($m->created_at)]];
    }

    private function playlists(Request $r): array
    {
        $q=VideoPlaylist::withCount('videos')->when($r->filled('status'),fn(Builder $q)=>$q->where('status',$r->string('status')));
        $this->dates($q,$r);
        return ['title'=>'Video Playlists Report','headings'=>['Playlist','Description','Videos','Order','Status','Created Date'],'query'=>$q->orderBy('order_number'),'row'=>fn(VideoPlaylist $m)=>[$m->name,$this->plain($m->description),$m->videos_count,$m->order_number,$m->status,$this->date($m->created_at)]];
    }

    private function quizzes(Request $r): array
    {
        $q=Quiz::with(['programmingLanguage','category'])->withCount('questions')->when($r->filled('language_id'),fn(Builder $q)=>$q->where('programming_language_id',$r->integer('language_id')))->when($r->filled('category_id'),fn(Builder $q)=>$q->where('quiz_category_id',$r->integer('category_id')))->when($r->filled('difficulty'),fn(Builder $q)=>$q->where('difficulty',$r->string('difficulty')))->when($r->filled('status'),fn(Builder $q)=>$q->where('status',$r->string('status')));
        $this->dates($q,$r);
        return ['title'=>'Quizzes Report','headings'=>['Quiz','Language','Category','Difficulty','Questions','Passing Score','Status','Created Date'],'query'=>$q->oldest(),'row'=>fn(Quiz $m)=>[$m->title,$m->programmingLanguage?->name,$m->category?->title,$m->difficulty,$m->questions_count,$m->passing_score.'%',$m->status,$this->date($m->created_at)]];
    }

    private function quizResults(Request $r): array
    {
        $q=QuizAttempt::with(['user','quiz.category'])->when($r->filled('user_id'),fn(Builder $q)=>$q->where('user_id',$r->integer('user_id')))->when($r->filled('quiz_id'),fn(Builder $q)=>$q->where('quiz_id',$r->integer('quiz_id')))->when($r->filled('score_min'),fn(Builder $q)=>$q->where('score','>=',$r->integer('score_min')))->when($r->filled('score_max'),fn(Builder $q)=>$q->where('score','<=',$r->integer('score_max')));
        $this->dates($q,$r,'completed_at');
        return ['title'=>'Quiz Results Report','headings'=>['Student','Email','Quiz','Category','Correct','Incorrect','Score','Percentage','Result','Attempt Date'],'query'=>$q->latest('completed_at'),'row'=>function(QuizAttempt $m){$pct=$m->total_points ? round($m->score/$m->total_points*100,1):0; return [$m->user?->name ?: 'Guest',$m->user?->email ?: '',$m->quiz?->title,$m->quiz?->category?->title,$m->correct_count,$m->incorrect_count,$m->score,$pct.'%',ucfirst($m->status),$this->date($m->completed_at ?: $m->created_at,true)];}];
    }

    private function contacts(Request $r): array
    {
        $q=Contact::query(); $this->dates($q,$r);
        return ['title'=>'Contact Messages Report','headings'=>['Name','Email','Message','Received Date'],'query'=>$q->latest(),'row'=>fn(Contact $m)=>[$m->name,$m->email,$this->plain($m->message),$this->date($m->created_at,true)]];
    }

    private function activityLogs(Request $r): array
    {
        $q=ActivityLog::with('user')->when($r->filled('search'),fn(Builder $q)=>$q->where(fn(Builder $nested)=>$nested->where('user_name','like','%'.$r->string('search').'%')->orWhere('user_email','like','%'.$r->string('search').'%')->orWhere('description','like','%'.$r->string('search').'%')->orWhere('target_name','like','%'.$r->string('search').'%')->orWhere('ip_address','like','%'.$r->string('search').'%')))->when($r->filled('user_id'),fn(Builder $q)=>$q->where('user_id',$r->integer('user_id')))->when($r->filled('role'),fn(Builder $q)=>$q->where('role',$r->string('role')))->when($r->filled('module'),fn(Builder $q)=>$q->where('module',$r->string('module')))->when($r->filled('action'),fn(Builder $q)=>$q->where('action',$r->string('action')))->when($r->filled('severity'),fn(Builder $q)=>$q->where('severity',$r->string('severity'))); $this->dates($q,$r);
        return ['title'=>'Activity Logs Report','headings'=>['User','Email','Role','Action','Module','Target','Description','IP Address','Browser','Device','Risk','Date & Time'],'query'=>$q->latest(),'row'=>fn(ActivityLog $m)=>[$m->user_name,$m->actor_email,$m->role,$m->action,$m->module,$m->target_label,$this->plain($m->description),$m->ip_address,$m->browser,$m->device,$m->severity,$this->date($m->created_at,true)]];
    }

    private function dates(Builder $query, Request $r, string $column='created_at'): void
    {
        $query->when($r->filled('date_from'),fn(Builder $q)=>$q->whereDate($column,'>=',$r->date('date_from')))->when($r->filled('date_to'),fn(Builder $q)=>$q->whereDate($column,'<=',$r->date('date_to')));
    }

    private function appliedFilters(Request $r): array
    {
        return collect($r->except(['page','preview','format','report']))->filter(fn($v)=>$v!==null&&$v!=='')->mapWithKeys(fn($v,$k)=>[str($k)->replace('_',' ')->headline()->toString()=>(string)$v])->all();
    }

    private function date($date, bool $time=false): string { return $date?->format($time?'Y-m-d H:i':'Y-m-d') ?? ''; }
    private function plain(?string $value): string { return str(strip_tags((string)$value))->squish()->limit(180)->toString(); }
}
