@if ($paginator->total() > 0)
    <nav class="admin-pagination" role="navigation" aria-label="Pagination Navigation">
        <p class="admin-pagination__summary">
            Showing <strong>{{ $paginator->firstItem() }}</strong> to <strong>{{ $paginator->lastItem() }}</strong>
            of <strong>{{ $paginator->total() }}</strong> results
        </p>

        @if ($paginator->hasPages())
            <div class="admin-pagination__links">
                @if ($paginator->onFirstPage())
                    <span class="admin-pagination__item admin-pagination__item--disabled" aria-disabled="true" aria-label="Previous page">&lsaquo;</span>
                @else
                    <a class="admin-pagination__item" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page">&lsaquo;</a>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="admin-pagination__ellipsis">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="admin-pagination__item admin-pagination__item--active" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="admin-pagination__item" href="{{ $url }}" aria-label="Go to page {{ $page }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a class="admin-pagination__item" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page">&rsaquo;</a>
                @else
                    <span class="admin-pagination__item admin-pagination__item--disabled" aria-disabled="true" aria-label="Next page">&rsaquo;</span>
                @endif
            </div>
        @endif
    </nav>
@endif
