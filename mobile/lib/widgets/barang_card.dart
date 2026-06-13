import 'package:flutter/material.dart';
import '../core/constants/app_constants.dart';
import '../models/barang.dart';

class BarangCard extends StatelessWidget {
  final Barang barang;
  final VoidCallback onTap;

  const BarangCard({
    Key? key,
    required this.barang,
    required this.onTap,
  }) : super(key: key);

  String _formatTanggal(String tanggal) {
    if (tanggal.length >= 10) {
      return tanggal.substring(0, 10);
    }
    return tanggal;
  }

  @override
  Widget build(BuildContext context) {
    final isMasuk = barang.lastSelisihStok > 0;
    final isKeluar = barang.lastSelisihStok < 0;
    final String sign = isMasuk ? '+' : '';
    final Color itemColor = isMasuk 
        ? AppConstants.successColor 
        : (isKeluar ? AppConstants.dangerColor : Colors.grey);

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
      child: InkWell(
        borderRadius: BorderRadius.circular(8),
        onTap: onTap,
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Row(
            children: [
              // Ikon visual produk
              Container(
                padding: const EdgeInsets.all(10),
                decoration: BoxDecoration(
                  color: AppConstants.primaryColor.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(8),
                ),
                child: const Icon(
                  Icons.inventory_2,
                  color: AppConstants.primaryColor,
                  size: 24,
                ),
              ),
              const SizedBox(width: 16),

              // Informasi nama dan kode produk
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      barang.nama,
                      style: const TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 16,
                        color: AppConstants.textColorDark,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      "Kode: ${barang.kode} • ${barang.kategori}",
                      style: const TextStyle(
                        fontSize: 12,
                        color: AppConstants.textColorLight,
                      ),
                    ),
                  ],
                ),
              ),

              // Informasi riwayat stok terakhir
              Column(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  Text(
                    _formatTanggal(barang.lastEditedAt),
                    style: const TextStyle(
                      fontSize: 11,
                      color: AppConstants.textColorLight,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Container(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 8,
                      vertical: 2,
                    ),
                    decoration: BoxDecoration(
                      color: itemColor.withOpacity(0.1),
                      borderRadius: BorderRadius.circular(4),
                    ),
                    child: Text(
                      "$sign${barang.lastSelisihStok}",
                      style: TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 15,
                        color: itemColor,
                      ),
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}

