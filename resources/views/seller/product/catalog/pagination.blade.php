@if ($paginator->onFirstPage())
    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
        <span class="page-link" aria-hidden="true">&lsaquo; Previous</span>
    </li>
@else
    <li class="page-item">
        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo; Previous</a>
    </li>
@endif

@if ($paginator->hasMorePages())
    <li class="page-item">
        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next &rsaquo;</a>
    </li>
@else
    <li class="page-item disabled" aria-disabled="true">
        <span class="page-link" aria-hidden="true">Next &rsaquo;</span>
    </li>
@endif
