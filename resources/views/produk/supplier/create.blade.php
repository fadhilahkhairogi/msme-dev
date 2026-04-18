@extends('produk.layout')

@section('title', 'Tambah Supplier')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Supplier
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('produk.supplier.index') }}">Supplier</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline shadow-sm">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-edit mr-1"></i> Form Tambah Supplier Baru
                        </h3>
                    </div>

                    <form action="{{ route('produk.supplier.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label>Kode Supplier</label>
                                        <input type="text" name="kode"
                                            class="form-control @error('kode') is-invalid @enderror"
                                            placeholder="Masukkan kode" value="{{ old('kode') }}">
                                        @error('kategori')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-7">
                                    <div class="form-group">

                                        <label>Nama Supplier</label>
                                        <input type="text" name="nama"
                                            class="form-control @error('nama') is-invalid @enderror"
                                            placeholder="Masukkan nama Supplier" value="{{ old('nama') }}" required>
                                        @error('nama')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>





                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label>Nomor Telepon</label>
                                        <div class="input-group">
                                            <input type="text" name="telepon"
                                                class="form-control @error('telepon') is-invalid @enderror"
                                                placeholder="Masukkan Nomor Telepon" value="{{ old('telepon') }}" required>
                                            @error('harga_pokok')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number" name="harga_jual"
                                                class="form-control @error('harga_jual') is-invalid @enderror"
                                                placeholder="0" value="{{ old('harga_jual') }}" required>
                                            @error('harga_jual')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div> --}}
                            </div>

                            <div class="row">
                                <div class="col-md-11">
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <input type="text" name="alamat" placeholder="Masukkan alamat"
                                            class="form-control @error('alamat') is-invalid @enderror"
                                            value="{{ old('alamat') }}" required>
                                        @error('stok')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <a href="{{ route('produk.supplier.index') }}" class="btn btn-default mr-1">
                                <i class="fas fa-times mr-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-1"></i> Simpan Supplier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
