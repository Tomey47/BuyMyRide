@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                <span aria-hidden="true">&lsaquo;</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="hover:bg-gray-100" aria-label="{{ __('pagination.previous') }}">
                &lsaquo;
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="disabled" aria-disabled="true">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="active" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="hover:bg-gray-100">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="hover:bg-gray-100" aria-label="{{ __('pagination.next') }}">
                &rsaquo;
            </a>
        @else
            <span class="disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                <span aria-hidden="true">&rsaquo;</span>
            </span>
        @endif
    </nav>
@endif 