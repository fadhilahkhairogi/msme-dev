class Barang {
  final int id;
  final String nama;
  final String kode;
  final int stok;
  final String kategori;
  final String satuan;
  final double hargaPokok;
  final double hargaJual;
  final int minStok;
  final int lastSelisihStok;
  final String lastEditedAt;

  Barang({
    required this.id,
    required this.nama,
    required this.kode,
    required this.stok,
    required this.kategori,
    required this.satuan,
    required this.hargaPokok,
    required this.hargaJual,
    required this.minStok,
    required this.lastSelisihStok,
    required this.lastEditedAt,
  });

  factory Barang.fromJson(Map<String, dynamic> json) {
    return Barang(
      id: json['id'] is int ? json['id'] : (int.tryParse(json['id']?.toString() ?? '') ?? 0),
      nama: json['nama']?.toString() ?? '',
      kode: json['kode']?.toString() ?? '',
      stok: json['stok'] is int ? json['stok'] : (int.tryParse(json['stok']?.toString() ?? '') ?? 0),
      kategori: json['kategori']?.toString() ?? 'Umum',
      satuan: json['satuan']?.toString() ?? 'pcs',
      hargaPokok: json['harga_pokok'] is num ? (json['harga_pokok'] as num).toDouble() : (double.tryParse(json['harga_pokok']?.toString() ?? '') ?? 0.0),
      hargaJual: json['harga_jual'] is num ? (json['harga_jual'] as num).toDouble() : (double.tryParse(json['harga_jual']?.toString() ?? '') ?? 0.0),
      minStok: json['min_stok'] is int ? json['min_stok'] : (int.tryParse(json['min_stok']?.toString() ?? '') ?? 0),
      lastSelisihStok: json['last_selisih_stok'] is int ? json['last_selisih_stok'] : (int.tryParse(json['last_selisih_stok']?.toString() ?? '') ?? 0),
      lastEditedAt: json['last_edited_at']?.toString() ?? '-',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'nama': nama,
      'kode': kode,
      'stok': stok,
      'kategori': kategori,
      'satuan': satuan,
      'harga_pokok': hargaPokok,
      'harga_jual': hargaJual,
      'min_stok': minStok,
      'last_selisih_stok': lastSelisihStok,
      'last_edited_at': lastEditedAt,
    };
  }
}


