import 'barang.dart';

class HistoriStok {
  final int id;
  final int barangId;
  final int selisihStok;
  final String jenis;
  final String editedAt;
  final Barang? barang;

  HistoriStok({
    required this.id,
    required this.barangId,
    required this.selisihStok,
    required this.jenis,
    required this.editedAt,
    this.barang,
  });

  factory HistoriStok.fromJson(Map<String, dynamic> json) {
    return HistoriStok(
      id: json['id'] is int ? json['id'] : (int.tryParse(json['id']?.toString() ?? '') ?? 0),
      barangId: json['barang_id'] is int ? json['barang_id'] : (int.tryParse(json['barang_id']?.toString() ?? '') ?? 0),
      selisihStok: json['selisih_stok'] is int ? json['selisih_stok'] : (int.tryParse(json['selisih_stok']?.toString() ?? '') ?? 0),
      jenis: json['jenis']?.toString() ?? 'Barang masuk',
      editedAt: json['edited_at']?.toString() ?? json['created_at']?.toString() ?? '',
      barang: json['barang'] != null ? Barang.fromJson(json['barang']) : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'barang_id': barangId,
      'selisih_stok': selisihStok,
      'jenis': jenis,
      'edited_at': editedAt,
      'barang': barang?.toJson(),
    };
  }
}


