<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
        }

        .table-bot {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div>
        <center>
            <h4>Laporan Stok Barang</h4>
        </center>
        {{-- logika isi tabel laporan stok --}}
        <table>
            <thead>
                <th>No</th>
                <th>ID</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>HPP</th>
                <th>Harga Jual</th>
                <th>Stok</th>
                <th>Min. Stok</th>
            </thead>
            @php $nomor_barang =0; @endphp
            @foreach ($daftar_Barang as $produk)
                @php $nomor_barang++; @endphp
                <tr>
                    <td><b>{{ $nomor_barang }}</b></td>
                    <td>{{ $produk->id }}</td>
                    <td>{{ $produk->kode }}</td>
                    <td>{{ $produk->nama }}</td>
                    <td>{{ $produk->kategori }}</td>
                    <td>{{ $produk->harga_pokok }}</td>
                    <td>{{ $produk->harga_jual }}</td>
                    <td>{{ $produk->stok }}</td>
                    <td>{{ $produk->min_stok }}</td>
                </tr>
            @endforeach
            <tr class="table-bot">
                <td style="text-align:center;" colspan ="5"><b>TOTAL</b></td>
                <td><b>{{ $totalHargaBeli }}</b></td>
                <td><b>{{ $totalHargaJual }}</b></td>
                <td><b>{{ $totalStok }}</b></td>
                <td><b>-</b></td>
            </tr>
        </table>
    </div>
</body>
