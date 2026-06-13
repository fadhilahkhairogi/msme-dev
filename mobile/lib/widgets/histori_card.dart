import 'package:flutter/material.dart';
import '../core/constants/app_constants.dart';
import '../models/histori_stok.dart';

class HistoriCard extends StatelessWidget {
  final HistoriStok histori;
  final bool showProductName;
  final VoidCallback? onTap;

  const HistoriCard({
    Key? key,
    required this.histori,
    this.showProductName = false,
    this.onTap,
  }) : super(key: key);

  String _formatTanggal(String tanggal) {
    if (tanggal.length >= 10) {
      return tanggal.substring(0, 10);
    }
    return tanggal.isEmpty ? '-' : tanggal;
  }

  @override
  Widget build(BuildContext context) {
    final tgl = _formatTanggal(histori.editedAt);
    final isMasuk = histori.jenis.toLowerCase() == 'barang masuk' || histori.selisihStok >= 0;
    final String sign = isMasuk ? '+' : '';
    final Color itemColor = isMasuk ? AppConstants.successColor : AppConstants.dangerColor;

    return Card(
      color: Colors.white,
      elevation: 0,
      margin: const EdgeInsets.only(bottom: 8),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(8),
        side: BorderSide(
          color: Colors.grey.shade300,
        ),
      ),
      child: ListTile(
        onTap: onTap,
        contentPadding: const EdgeInsets.symmetric(
          horizontal: 16,
          vertical: 4,
        ),
        leading: Container(
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(
            color: itemColor.withOpacity(0.1),
            borderRadius: BorderRadius.circular(8),
          ),
          child: Icon(
            isMasuk ? Icons.download : Icons.upload,
            color: itemColor,
            size: 24,
          ),
        ),
        title: Text(
          showProductName ? (histori.barang?.nama ?? 'Barang Terhapus') : histori.jenis,
          style: const TextStyle(
            fontWeight: FontWeight.bold,
            fontSize: 14,
          ),
        ),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          mainAxisSize: MainAxisSize.min,
          children: [
            Text(
              showProductName ? "${histori.jenis} : $tgl" : "Tanggal: $tgl",
              style: const TextStyle(fontSize: 12),
            ),
            if (showProductName && histori.barang != null) ...[
              const SizedBox(height: 2),
              Text(
                "Kode: ${histori.barang!.kode}",
                style: const TextStyle(fontSize: 12, color: AppConstants.textColorLight),
              ),
            ],
          ],
        ),
        trailing: Text(
          "$sign${histori.selisihStok}",
          style: TextStyle(
            fontWeight: FontWeight.bold,
            fontSize: 18,
            color: itemColor,
          ),
        ),
      ),
    );
  }
}


