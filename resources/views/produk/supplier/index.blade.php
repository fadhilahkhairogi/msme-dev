@extends('produk.layout')

@section('title', 'Manajemen Supplier')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-box-open mr-2"></i> Manajemen Supplier
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Manajemen Supplier</li>
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

            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header">
                    <h3 class="card-title mt-1"><i class="fas fa-list mr-1"></i> Daftar Supplier</h3>

                    <div class="card-tools d-flex align-items-center">
                        <a href="{{ route('produk.supplier.create') }}" class="btn btn-sm btn-primary mr-2">
                            <i class="fas fa-plus mr-1"></i> Tambah supplier
                        </a>


                        <form method="GET" action="{{ route('produk.supplier.index') }}" class="m-0">
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
                    {{-- untuk tabel supplier --}}
                    <table class="table table-hover table-striped text-nowrap">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 100px;">No.</th>
                                <th>Kode</th>
                                <th>Nama supplier</th>
                                <th>Alamat</th>
                                <th>Telepon</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($daftarSupplier as $index => $supplier)
                                <tr>
                                    <td class="text-center align-middle">{{ $daftarSupplier->firstItem() + $index }}.</td>
                                    <td class="align-middle text-success font-weight-bold">
                                        {{ $supplier->kode }}</td>
                                    <td class="align-middle font-weight-bold">{{ $supplier->nama }}</td>
                                    <td class="align-middle">{{ $supplier->alamat ?? '-' }}</td>
                                    {{-- <td class="align-middle">{{ $supplier->barcode ?? '-' }}</td> --}}
                                    <td class="align-middle text-muted">
                                        {{ $supplier->telepon }}
                                    </td>

                                    <td class="text-center align-middle">
                                        <div class="btn-group">
                                            <a href="{{ route('produk.supplier.edit', $supplier->id) }}"
                                                class="btn btn-sm btn-success" title="Edit Data">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                data-url="{{ route('produk.supplier.destroy', $supplier->id) }}"
                                                data-name="{{ addslashes($supplier->nama) }}" title="Hapus Data">
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
                        {{-- Tes --}}
                        @include('produk.komponen.pagination', ['paginator' => $daftarSupplier])
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
                            <h4 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i> Konfirmasi Hapus
                            </h4>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-0">Apakah Anda yakin ingin menghapus supplier <strong
                                    id="delete-item-name"></strong>? Data yang sudah dihapus tidak dapat dikembalikan.
                            </p>
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
    @stop

    @push('js')
        <script>
            $(function() {

                $('[title]').tooltip();


                $('.btn-delete').on('click', function() {
                    var url = $(this).data('url');
                    var name = $(this).data('name');

                    $('#form-delete').attr('action', url);
                    $('#delete-item-name').text(name);

                    $('#modal-delete').modal('show');
                });


            });
        </script>
    @endpush
