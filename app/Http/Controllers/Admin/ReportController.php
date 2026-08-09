<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Reports\ReportCatalog;
use App\Services\Reports\SimpleXlsxWriter;
use App\Services\ActivityLogService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request, ReportCatalog $catalog): View
    {
        $this->normalizeFilters($request);
        $this->validateInput($request);
        $types = $catalog->types($request->user());
        $type = $request->string('report')->toString() ?: array_key_first($types);
        $report = $catalog->make($type, $request, $request->user());
        $preview = null;

        if ($request->boolean('preview')) {
            $paginator = (clone $report['query'])->paginate(25)->withQueryString();
            $paginator->setCollection($paginator->getCollection()->map($report['row']));
            $preview = $paginator;
        }

        return view('admin.reports.index', compact('types', 'type', 'report', 'preview') + ['options' => $catalog->options()]);
    }

    public function export(Request $request, ReportCatalog $catalog, SimpleXlsxWriter $xlsx): Response
    {
        $this->normalizeFilters($request, true);
        $this->validateInput($request, true);
        $type = $request->string('report')->toString();
        $format = $request->string('format')->toString();
        $report = $catalog->make($type, $request, $request->user());
        $count = (clone $report['query'])->count();
        $file = Str::slug(Str::before($report['title'], ' Report')).'-report-'.now()->format('Y-m-d').'.'.$format;

        if ($format === 'csv') {
            return $this->csv($report, $file, fn () => $this->logExport($request, $report['title'], $format, $count));
        }

        if ($format === 'xlsx') {
            abort_if($count > 25000, 422, 'Excel exports are limited to 25,000 records. Use CSV for larger reports.');
            $rows = (clone $report['query'])->lazy(500)->map($report['row']);
            $content = $xlsx->create($report['headings'], $rows);
            $this->logExport($request, $report['title'], $format, $count);
            return response($content, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="'.$file.'"',
            ]);
        }

        abort_if($count > 2000, 422, 'PDF exports are limited to 2,000 records. Narrow the filters or use CSV.');
        $rows = (clone $report['query'])->limit(2000)->get()->map($report['row'])->all();
        $pdf = Pdf::loadView('admin.reports.pdf', compact('report', 'rows', 'count'))
            ->setPaper('a4', count($report['headings']) > 6 ? 'landscape' : 'portrait')
            ->download($file);
        $this->logExport($request, $report['title'], $format, $count);
        return $pdf;
    }

    private function csv(array $report, string $file, \Closure $afterExport): StreamedResponse
    {
        return response()->streamDownload(function () use ($report, $afterExport): void {
            $out = fopen('php://output', 'wb');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, $report['headings']);
            foreach ((clone $report['query'])->lazy(500) as $record) fputcsv($out, ($report['row'])($record));
            fclose($out);
            $afterExport();
        }, $file, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function validateInput(Request $request, bool $export=false): void
    {
        $rules = [
            'report'=>['nullable','string','max:40'],'status'=>['nullable',Rule::in(['active','inactive','published','draft','archived','unpublished','in_progress','completed'])],'role'=>['nullable',Rule::in(['user','admin','super_admin'])],
            'provider'=>['nullable','in:email,google'],'difficulty'=>['nullable','string','max:30'],
            'language'=>['nullable','string','max:100'],'search'=>['nullable','string','max:255'],
            'course_id'=>['nullable','integer','exists:courses,id'],'section_id'=>['nullable','integer','exists:course_sections,id'],'playlist_id'=>['nullable','integer','exists:video_playlists,id'],
            'language_id'=>['nullable','integer','exists:programming_languages,id'],'category_id'=>['nullable','integer','exists:quiz_categories,id'],'quiz_id'=>['nullable','integer','exists:quizzes,id'],'user_id'=>['nullable','integer','exists:users,id'],
            'score_min'=>['nullable','integer','min:0'],'score_max'=>['nullable','integer','min:0','gte:score_min'],
            'module'=>['nullable','string','max:100'],'action'=>['nullable','string','max:100'],'severity'=>['nullable','in:normal,warning,suspicious'],
            'date_from'=>['nullable','date_format:Y-m-d'],'date_to'=>['nullable','date_format:Y-m-d','after_or_equal:date_from'],
        ];
        if ($export) $rules = array_merge($rules, ['report'=>['required','string','max:40'],'format'=>['required','in:xlsx,csv,pdf']]);
        $request->validate($rules);
    }

    private function normalizeFilters(Request $request, bool $export=false): void
    {
        $type = $request->string('report')->toString() ?: 'users';
        $filters = match ($type) {
            'users' => ['date_from','date_to','role','provider','status','search'],
            'admins' => ['date_from','date_to','provider','status','search'],
            'courses' => ['date_from','date_to','language','difficulty','status'],
            'course-sections' => ['course_id'],
            'lessons' => ['course_id','section_id','status'],
            'code-examples' => ['course_id'],
            'videos' => ['playlist_id','status','date_from','date_to','search'],
            'video-playlists' => ['status','date_from','date_to'],
            'quizzes' => ['language_id','category_id','difficulty','status','date_from','date_to'],
            'quiz-results' => ['user_id','quiz_id','score_min','score_max','date_from','date_to'],
            'contact-messages' => ['date_from','date_to'],
            'activity-logs' => ['date_from','date_to','user_id','role','module','action','severity','search'],
            default => [],
        };
        $base = ['report', 'preview', 'page', ...($export ? ['format'] : [])];
        $request->query->replace(collect($request->query())->only([...$base, ...$filters])->all());
    }

    private function logExport(Request $request, string $title, string $format, int $count): void
    {
        $actor=$request->user();
        $action = match ($format) {'xlsx' => 'Export Excel', 'csv' => 'Export CSV', 'pdf' => 'Export PDF'};
        ActivityLogService::log($request,$action,'Reports',$title,"{$actor->name} exported {$title} as ".strtoupper($format).'.',[],['format'=>$format,'record_count'=>$count],$actor);
    }
}
