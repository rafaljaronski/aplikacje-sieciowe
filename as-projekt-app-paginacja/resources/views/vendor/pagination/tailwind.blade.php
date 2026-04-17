@if ($paginator->hasPages())
    <nav role="navigation">
        <ul class="pagination-list">

            @if ($paginator->onFirstPage())
                <li class="pagination-item disabled"><span>« Poprzednia</span></li>
            @else
                <li class="pagination-item"><a href="{{ $paginator->previousPageUrl() }}" rel="prev">« Poprzednia</a></li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="pagination-item disabled"><span>{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="pagination-item active"><span>{{ $page }}</span></li>
                        @else
                            <li class="pagination-item"><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="pagination-item"><a href="{{ $paginator->nextPageUrl() }}" rel="next">Następna »</a></li>
            @else
                <li class="pagination-item disabled"><span>Następna »</span></li>
            @endif

        </ul>
    </nav>
@endif
