@if ($paginator->hasPages())
    <div class="pagination-container">
        {{-- Info jumlah data --}}
        <div class="pagination-info">
            Menampilkan
            <strong>{{ $paginator->firstItem() }}</strong>
            –
            <strong>{{ $paginator->lastItem() }}</strong>
            dari
            <strong>{{ $paginator->total() }}</strong>
            data
        </div>

        {{-- Navigasi halaman --}}
        <nav class="pagination-wrapper" role="navigation" aria-label="Pagination Navigation">
            <ul class="pagination">
                {{-- Previous --}}
                @if ($paginator->onFirstPage())
                    <li class="disabled" aria-disabled="true">
                        <span>&laquo;</span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
                    </li>
                @endif

                {{-- Page Number --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                            @else
                                <li><a href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <li>
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
                    </li>
                @else
                    <li class="disabled" aria-disabled="true">
                        <span>&raquo;</span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif
