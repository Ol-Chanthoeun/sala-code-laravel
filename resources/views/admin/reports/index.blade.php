@extends('layouts.admin')
@section('title', 'Reports & Export')
@section('page-title', 'Reports & Export')
@section('breadcrumb', 'Reports')

@section('content')
<section class="reports-card">
    <form class="reports-filter-form" method="GET" action="{{ route('admin.reports.index') }}">
        <label>Report Type
            <select name="report" id="reportType">@foreach($types as $value=>$label)<option value="{{ $value }}" @selected($type===$value)>{{ $label }}</option>@endforeach</select>
        </label>
        <label class="report-filter" data-reports="users admins courses videos video-playlists quizzes quiz-results contact-messages activity-logs">Date From<input type="date" name="date_from" value="{{ request('date_from') }}"></label>
        <label class="report-filter" data-reports="users admins courses videos video-playlists quizzes quiz-results contact-messages activity-logs">Date To<input type="date" name="date_to" value="{{ request('date_to') }}"></label>
        <label class="report-filter" data-reports="users activity-logs">Role<select name="role"><option value="">All roles</option>@foreach(['user'=>'User','admin'=>'Admin','super_admin'=>'Super Admin'] as $v=>$l)<option value="{{ $v }}" @selected(request('role')===$v)>{{ $l }}</option>@endforeach</select></label>
        <label class="report-filter" data-reports="users admins">Auth Provider<select name="provider"><option value="">All providers</option><option value="email" @selected(request('provider')==='email')>Email</option><option value="google" @selected(request('provider')==='google')>Google</option></select></label>
        <label class="report-filter" data-reports="users admins courses lessons videos video-playlists quizzes">Status<input name="status" value="{{ request('status') }}" placeholder="e.g. active or published"></label>
        <label class="report-filter" data-reports="courses">Course Language<input name="language" value="{{ request('language') }}" placeholder="e.g. C++"></label>
        <label class="report-filter" data-reports="courses quizzes">Difficulty<input name="difficulty" value="{{ request('difficulty') }}"></label>
        <label class="report-filter" data-reports="course-sections lessons code-examples">Course<select name="course_id"><option value="">All courses</option>@foreach($options['courses'] as $id=>$name)<option value="{{ $id }}" @selected((string)request('course_id')===(string)$id)>{{ $name }}</option>@endforeach</select></label>
        <label class="report-filter" data-reports="lessons">Section<select name="section_id"><option value="">All sections</option>@foreach($options['sections'] as $id=>$name)<option value="{{ $id }}" @selected((string)request('section_id')===(string)$id)>{{ $name }}</option>@endforeach</select></label>
        <label class="report-filter" data-reports="videos">Playlist<select name="playlist_id"><option value="">All playlists</option>@foreach($options['playlists'] as $id=>$name)<option value="{{ $id }}" @selected((string)request('playlist_id')===(string)$id)>{{ $name }}</option>@endforeach</select></label>
        <label class="report-filter" data-reports="quizzes">Language<select name="language_id"><option value="">All languages</option>@foreach($options['languages'] as $id=>$name)<option value="{{ $id }}" @selected((string)request('language_id')===(string)$id)>{{ $name }}</option>@endforeach</select></label>
        <label class="report-filter" data-reports="quizzes">Category<select name="category_id"><option value="">All categories</option>@foreach($options['categories'] as $id=>$name)<option value="{{ $id }}" @selected((string)request('category_id')===(string)$id)>{{ $name }}</option>@endforeach</select></label>
        <label class="report-filter" data-reports="quiz-results">User<select name="user_id"><option value="">All users</option>@foreach($options['users'] as $id=>$name)<option value="{{ $id }}" @selected((string)request('user_id')===(string)$id)>{{ $name }}</option>@endforeach</select></label>
        <label class="report-filter" data-reports="quiz-results">Quiz<select name="quiz_id"><option value="">All quizzes</option>@foreach($options['quizzes'] as $id=>$name)<option value="{{ $id }}" @selected((string)request('quiz_id')===(string)$id)>{{ $name }}</option>@endforeach</select></label>
        <label class="report-filter" data-reports="quiz-results">Min Score<input type="number" min="0" name="score_min" value="{{ request('score_min') }}"></label>
        <label class="report-filter" data-reports="quiz-results">Max Score<input type="number" min="0" name="score_max" value="{{ request('score_max') }}"></label>
        <label class="report-filter" data-reports="activity-logs">Module<select name="module"><option value="">All modules</option>@foreach($options['modules'] as $value)<option @selected(request('module')===$value)>{{ $value }}</option>@endforeach</select></label>
        <label class="report-filter" data-reports="activity-logs">Action<select name="action"><option value="">All actions</option>@foreach($options['actions'] as $value)<option @selected(request('action')===$value)>{{ $value }}</option>@endforeach</select></label>
        <div class="reports-filter-actions">
            <button class="reports-reset-btn" id="resetReportFilters" type="button"><i class="fas fa-rotate-left"></i> Reset Filters</button>
            <button class="action-btn" name="preview" value="1" type="submit"><i class="fas fa-eye"></i> Preview</button>
        </div>
    </form>
</section>

@if($preview)
<section class="reports-summary">
    <div><span>Report Summary</span><strong>Report: {{ Str::before($report['title'], ' Report') }}</strong></div>
    <div><span>Total Records</span><strong>{{ number_format($preview->total()) }}</strong></div>
    @if($report['filters'])
        <div class="reports-summary__filters"><span>Applied Filters</span><strong>
            @foreach($report['filters'] as $key=>$value)
                @php
                    $displayValue = match ($key) {
                        'Course Id' => $options['courses'][$value] ?? $value,
                        'Section Id' => $options['sections'][$value] ?? $value,
                        'Playlist Id' => $options['playlists'][$value] ?? $value,
                        'Language Id' => $options['languages'][$value] ?? $value,
                        'Category Id' => $options['categories'][$value] ?? $value,
                        'Quiz Id' => $options['quizzes'][$value] ?? $value,
                        'User Id' => $options['users'][$value] ?? $value,
                        'Role', 'Status', 'Provider' => Str::headline($value),
                        default => $value,
                    };
                @endphp
                {{ Str::replace(' Id', '', $key) }}: {{ $displayValue }}@if(!$loop->last) · @endif
            @endforeach
        </strong></div>
    @endif
</section>
<section class="reports-card">
    <div class="reports-export-bar"><h3>Preview</h3><div>
        @foreach(['xlsx'=>['Excel','fa-file-excel'],'csv'=>['CSV','fa-file-csv'],'pdf'=>['PDF','fa-file-pdf']] as $format=>$button)
            <a class="report-export-btn report-export-btn--{{ $format }}" href="{{ route('admin.reports.export', [...request()->query(), 'report'=>$type, 'format'=>$format]) }}"><i class="fas {{ $button[1] }}"></i>{{ $button[0] }}</a>
        @endforeach
    </div></div>
    <div class="table-responsive reports-preview-scroll"><table class="reports-preview-table"><thead><tr>@foreach($report['headings'] as $heading)<th>{{ $heading }}</th>@endforeach</tr></thead><tbody>@forelse($preview as $row)<tr>@foreach($row as $cell)<td>{{ $cell }}</td>@endforeach</tr>@empty<tr><td colspan="{{ count($report['headings']) }}">No matching records.</td></tr>@endforelse</tbody></table></div>
    {{ $preview->links('admin.partials.pagination') }}
</section>
@endif

<button class="reports-back-to-top" id="reportsBackToTop" type="button" aria-label="Back to top" title="Back to top" hidden>
    <i class="fas fa-arrow-up" aria-hidden="true"></i>
</button>
@endsection

@push('scripts')
<script>(()=>{
    const type=document.getElementById('reportType');
    const form=type.form;
    const filters=[...document.querySelectorAll('.report-filter')];
    const update=(clearHidden=false)=>filters.forEach(el=>{
        const hidden=!el.dataset.reports.split(' ').includes(type.value);
        el.hidden=hidden;
        el.querySelectorAll('input,select').forEach(control=>{
            if(hidden&&clearHidden) control.value='';
            control.disabled=hidden;
        });
    });
    type.addEventListener('change',()=>update(true));
    document.getElementById('resetReportFilters').addEventListener('click',()=>{
        form.querySelectorAll('input,select').forEach(control=>{if(control!==type) control.value='';});
        update(true);
    });
    update(true);
})();</script>
<script>(()=>{
    const button=document.getElementById('reportsBackToTop');
    const update=()=>button.hidden=window.scrollY<360;
    window.addEventListener('scroll',update,{passive:true});
    button.addEventListener('click',()=>window.scrollTo({top:0,behavior:'smooth'}));
    update();
})();</script>
@endpush
