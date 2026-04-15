@extends('produk.layout')

@section('title', 'Tambah Produk')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Produk
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('produk.barang.index') }}">Produk</a></li>
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
                            <i class="fas fa-edit mr-1"></i> Form Tambah Produk Baru
                        </h3>
                    </div>

                    <form action="{{ route('produk.barang.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nama Produk</label>
                                <input type="text" name="nama"
                                    class="form-control @error('nama') is-invalid @enderror"
                                    placeholder="Masukkan nama produk" value="{{ old('nama') }}" required>
                                @error('nama')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Kategori</label>
                                        <input type="text" name="kategori"
                                            class="form-control @error('kategori') is-invalid @enderror"
                                            placeholder="Masukkan kategori" value="{{ old('kategori') }}">
                                        @error('kategori')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{-- <label>Barcode</label>
                                        <input type="text" name="barcode"
                                            class="form-control @error('barcode') is-invalid @enderror"
                                            placeholder="Masukkan barcode (opsional)" value="{{ old('barcode') }}">
                                        @error('barcode')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror --}}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Harga Beli</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number" name="harga_pokok"
                                                class="form-control @error('harga_pokok') is-invalid @enderror"
                                                placeholder="0" value="{{ old('harga_pokok') }}" required>
                                            @error('harga_pokok')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Harga Jual</label>
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
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Stok</label>
                                        <input type="number" name="stok"
                                            class="form-control @error('stok') is-invalid @enderror"
                                            value="{{ old('stok', 0) }}" required>
                                        @error('stok')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Minimum Stok</label>
                                        <input type="number" name="min_stok"
                                            class="form-control @error('min_stok') is-invalid @enderror"
                                            value="{{ old('min_stok', 0) }}" required>
                                        @error('min_stok')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <a href="{{ route('produk.barang.index') }}" class="btn btn-default mr-1">
                                <i class="fas fa-times mr-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-1"></i> Simpan Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
