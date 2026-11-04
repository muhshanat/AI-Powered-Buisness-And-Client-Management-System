
@if ($paginator->hasPages())
    <nav class="admin-pagination">

        <div class="pagination-summary">
            Showing
            <strong>{{ $paginator->firstItem() }}</strong>
            to
            <strong>{{ $paginator->lastItem() }}</strong>
            of
            <strong>{{ $paginator->total() }}</strong>
            results
        </div>

       
<div class="pagination-controls">

    {{-- Previous Arrow --}}
    @if ($paginator->onFirstPage())
        <span class="page-arrow disabled" aria-label="Previous page">
            <svg viewBox="0 0 24 24">
                <path d="m15 18-6-6 6-6"/>
            </svg>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}"
           class="page-arrow"
           aria-label="Previous page"
           title="Previous page">
            <svg viewBox="0 0 24 24">
                <path d="m15 18-6-6 6-6"/>
            </svg>
        </a>
    @endif

    {{-- Page Numbers --}}
    @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
        <a href="{{ $url }}"
           class="page-number {{ $paginator->currentPage() == $page ? 'current' : '' }}">
            {{ $page }}
        </a>
    @endforeach

    {{-- Next Arrow --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
           class="page-arrow"
           aria-label="Next page"
           title="Next page">
            <svg viewBox="0 0 24 24">
                <path d="m9 18 6-6-6-6"/>
            </svg>
        </a>
    @else
        <span class="page-arrow disabled" aria-label="Next page">
            <svg viewBox="0 0 24 24">
                <path d="m9 18 6-6-6-6"/>
            </svg>
        </span>
    @endif

</div>


    </nav>
@endif
