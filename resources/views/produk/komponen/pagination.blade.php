@if ($paginator->hasPages())
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            Menampilkan
            <span class="font-weight-bold">{{ $paginator->firstItem() }}</span>
            <b>-</b>
            <span class="font-weight-bold">{{ $paginator->lastItem() }}</span>
            dari
            <span class="font-weight-bold">{{ $paginator->total() }}</span>
        </div>

        <ul class="pagination pagination-sm m-0">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">Sebelumnya</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Sebelumnya</a>
                </li>
            @endif

            @php
                $sekarang = $paginator->currentPage();
                $terakhir = $paginator->lastPage();
                $mulai = max(1, $sekarang - 2);
                $akhir = min($terakhir, $mulai + 4);
                if ($akhir - $mulai < 4) {
                    $mulai = max(1, $akhir - 4);
                }
            @endphp

            @for ($halaman = $mulai; $halaman <= $akhir; $halaman++)
                @if ($halaman == $sekarang)
                    <li class="page-item active">
                        <span class="page-link">{{ $halaman }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->url($halaman) }}">{{ $halaman }}</a>
                    </li>
                @endif
            @endfor

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">Berikutnya</span>
                </li>
            @endif
        </ul>
    </div>
@endif
