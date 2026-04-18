@extends('produk.layout')

@section('title', 'Manajemen Barang')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-box-open mr-2"></i> Manajemen Barang
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Manajemen Barang</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">

        @if (session('Sukses') || session('sukses'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                {{ session('Sukses') ?? session('sukses') }}
            </div>
        @endif

        <div class="row">
            {{-- Info Box: Kesehatan Stok --}}
            <div class="col-12 col-sm-6 col-md-4">
                @php $warnaKesehatanStok = $dashboard['persentaseKesehatanStok'] > 50 ? 'success' : 'danger'; @endphp
                <div class="info-box shadow-sm">
                    <span class="info-box-icon bg-{{ $warnaKesehatanStok }} elevation-1"><i
                            class="fas fa-heartbeat"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Kesehatan Stok</span>
                        <span class="info-box-number">{{ $dashboard['persentaseKesehatanStok'] }}%
                            <small>Terpenuhi</small></span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-{{ $warnaKesehatanStok }}"
                                style="width: {{ $dashboard['persentaseKesehatanStok'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box shadow-sm">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-wallet"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total HPP (Modal)</span>
                        <span class="info-box-number">Rp
                            {{ number_format($dashboard['totalHargaBeli'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-4">
                <div class="info-box shadow-sm">
                    <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-hand-holding-usd"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Estimasi Nilai Jual</span>
                        <span class="info-box-number">
                            Rp {{ number_format($dashboard['totalHargaJual'], 0, ',', '.') }}
                            @if ($dashboard['prakiraanKeuntungan'] > 0)
                                <small class="text-success ml-1"><i class="fas fa-arrow-up"></i>
                                    {{ number_format($dashboard['prakiraanKeuntungan'], 1) }}%</small>
                            @elseif($dashboard['prakiraanKeuntungan'] < 0)
                                <small class="text-danger ml-1"><i class="fas fa-arrow-down"></i>
                                    {{ number_format($dashboard['prakiraanKeuntungan'], 1) }}%</small>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title mt-1"><i class="fas fa-list mr-1"></i> Daftar Barang</h3>

                <div class="card-tools d-flex align-items-center">
                    <a href="{{ route('produk.barang.create') }}" class="btn btn-sm btn-primary mr-2">
                        <i class="fas fa-plus mr-1"></i> Tambah Barang
                    </a>
                    <a href="{{ route('produk.barang.laporan') }}" target="_blank"
                        class="btn btn-sm btn-outline-info mr-3">
                        <i class="fas fa-print mr-1"></i> Cetak Stok
                    </a>

                    <form method="GET" action="{{ route('produk.barang.index') }}" class="m-0">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" class="form-control float-right"
                                placeholder="Cari produk..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped text-nowrap">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">No.</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            {{-- <th>Barcode</th> --}}
                            <th>HPP</th>
                            <th>Harga Jual</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Min Stok</th>
                            <th class="text-center" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daftarBarang as $index => $Barang)
                            <tr>
                                <td class="text-center align-middle">{{ $daftarBarang->firstItem() + $index }}.</td>
                                <td class="align-middle font-weight-bold">{{ $Barang->kode }}</td>
                                <td class="align-middle font-weight-bold">{{ $Barang->nama }}</td>
                                <td class="align-middle">{{ $Barang->kategori ?? '-' }}</td>
                                {{-- <td class="align-middle">{{ $Barang->barcode ?? '-' }}</td> --}}
                                <td class="align-middle text-muted">Rp
                                    {{ number_format($Barang->harga_pokok, 0, ',', '.') }}
                                </td>
                                <td class="align-middle text-success font-weight-bold">Rp
                                    {{ number_format($Barang->harga_jual, 0, ',', '.') }}</td>
                                <td class="text-center align-middle">
                                    @if ($Barang->stok <= $Barang->min_stok)
                                        <span class="badge badge-danger px-2 py-1">{{ $Barang->stok }}</span>
                                    @else
                                        <span class="badge badge-success px-2 py-1">{{ $Barang->stok }}</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle text-muted">{{ $Barang->min_stok }}</td>
                                <td class="text-center align-middle">
                                    <div class="btn-group">
                                        <a href="{{ route('produk.barang.edit', $Barang->id) }}"
                                            class="btn btn-sm btn-success" title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete"
                                            data-url="{{ route('produk.barang.destroy', $Barang->id) }}"
                                            data-name="{{ addslashes($Barang->nama) }}" title="Hapus Data">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="fas fa-folder-open fa-3x mb-3 text-gray-300"></i><br>
                                    Belum ada data produk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer clearfix">
                <div class="float-right">
                    @include('produk.komponen.pagination', ['paginator' => $daftarBarang])
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Tabel   restok --}}
            <div class="col-md-6">
                <div class="card card-danger shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-exclamation-triangle mr-1"></i> Peringatan Restok</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool">
                                {{-- <i class="fas fa-minus"></i> --}}
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0" style="height: 300px;">
                        <table class="table table-head-fixed text-nowrap table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th class="text-center">Sisa Stok</th>
                                    <th class="text-center">Min. Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dashboard['daftarBarangStokKurang'] as $stokKurang)
                                    <tr>
                                        <td>{{ $stokKurang->nama }}</td>
                                        <td class="text-center"><span
                                                class="badge badge-danger">{{ $stokKurang->stok }}</span></td>
                                        <td class="text-center text-muted">{{ $stokKurang->min_stok }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-5">
                                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i><br>
                                            Stok dalam kondisi aman.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-primary shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Distribusi Kategori</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool">
                            </button>
                        </div>
                    </div>
                    <div class="card-body d-flex justify-content-center align-items-center" style="height: 300px;">
                        @if (!empty($dashboard['labelGrafik']) && count($dashboard['labelGrafik']) > 0)
                            <canvas id="diagramLingkaranKategori" style=""></canvas>
                        @else
                            {{-- kalau datanya kosong --}}
                            <div id="dataDiagramKosong" class="text-muted font-italic text-center d-none">
                                <i class="fas fa-chart-bar fa-2x mb-2 text-gray-300"></i><br>
                                Data grafik belum tersedia.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="modal-delete" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="form-delete" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h4 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i> Konfirmasi Hapus</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Apakah Anda yakin ingin menghapus Barang <strong
                                id="delete-item-name"></strong>? Data yang sudah dihapus tidak dapat dikembalikan.</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash mr-1"></i> Ya, Hapus
                            Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modal-view" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title"><i class="fas fa-circle-info mr-2"></i> Detail Barang</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash mr-1"></i> Ya, Hapus
                        Data</button>
                </div>
            </div>
        </div>
    </div>
@stop

@push('js')
    <script>
        $(function() {

            $('[title]').tooltip();


            $('.btn-delete').on('click', function() {
                var url = $(this).data('url');
                var name = $(this).data('name');

                // Ubah action form dan nama item di dalam modal
                $('#form-delete').attr('action', url);
                $('#delete-item-name').text(name);

                // Tampilkan modal
                $('#modal-delete').modal('show');
            });
            var labelGrafik = @json($dashboard['labelGrafik'] ?? []);
            var dataDiagram = @json($dashboard['dataDiagram'] ?? []);

            // Logika jika data kosong
            if (labelGrafik.length === 0 || dataDiagram.length === 0) {
                $('#diagramLingkaranKategori').addClass('d-none'); // Sembunyikan canvas
                $('#dataDiagramKosong').removeClass('d-none'); // Tampilkan teks "Belum tersedia"
            } else {
                var canvas = $('#diagramLingkaranKategori').get(0).getContext('2d');
                var diagram = {
                    labels: labelGrafik,
                    datasets: [{
                        data: dataDiagram,
                        backgroundColor: [
                            'royalblue',
                            'purple',
                            'skyblue',
                            'gray',
                            'green',
                            'orange',
                            'red'

                        ],
                    }]
                };
                var opsiDiagram = {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'right'
                        }
                    }
                };

                // ini grafik
                new Chart(canvas, {
                    type: 'pie',
                    data: diagram,
                    options: opsiDiagram
                });
            }

        });
    </script>
@endpush
