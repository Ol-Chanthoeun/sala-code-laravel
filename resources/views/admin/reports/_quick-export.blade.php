<details class="quick-export">
    <summary><i class="fas fa-file-export"></i> Export <i class="fas fa-chevron-down"></i></summary>
    <div>
        @php($quickExportQuery = collect(request()->query())->except(['page', ...($reportExcept ?? [])])->merge($reportQuery ?? [])->all())
        @foreach(['xlsx'=>['Excel','fa-file-excel'],'csv'=>['CSV','fa-file-csv'],'pdf'=>['PDF','fa-file-pdf']] as $format=>$item)
            <a href="{{ route('admin.reports.export', [...$quickExportQuery, 'report'=>$reportType, 'format'=>$format]) }}"><i class="fas {{ $item[1] }}"></i>{{ $item[0] }}</a>
        @endforeach
    </div>
</details>
