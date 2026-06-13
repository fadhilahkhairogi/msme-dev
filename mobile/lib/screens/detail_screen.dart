import 'package:flutter/material.dart';
import '../core/constants/app_constants.dart';
import '../models/barang.dart';
import '../models/histori_stok.dart';
import '../services/api_service.dart';
import '../widgets/histori_card.dart';

class DetailScreen extends StatefulWidget {
  final Barang barang;

  const DetailScreen({Key? key, required this.barang}) : super(key: key);

  @override
  _DetailScreenState createState() => _DetailScreenState();
}

class _DetailScreenState extends State<DetailScreen> {
  final ApiService _apiService = ApiService();
  DateTimeRange? _selectedDateRange;
  List<HistoriStok> _historiStok = [];
  bool _isLoadingHistori = true;

  @override
  void initState() {
    super.initState();
    _fetchHistori();
  }

  Future<void> _fetchHistori() async {
    setState(() {
      _isLoadingHistori = true;
    });

    String? startStr;
    String? endStr;
    if (_selectedDateRange != null) {
      startStr = "${_selectedDateRange!.start.year}-${_selectedDateRange!.start.month.toString().padLeft(2, '0')}-${_selectedDateRange!.start.day.toString().padLeft(2, '0')}";
      endStr = "${_selectedDateRange!.end.year}-${_selectedDateRange!.end.month.toString().padLeft(2, '0')}-${_selectedDateRange!.end.day.toString().padLeft(2, '0')}";
    }

    try {
      final data = await _apiService.getHistoriStok(
        widget.barang.id,
        startDate: startStr,
        endDate: endStr,
      );
      setState(() {
        _historiStok = data;
        _isLoadingHistori = false;
      });
    } catch (e) {
      print("DetailScreen._fetchHistori Error: $e");
      setState(() {
        _isLoadingHistori = false;
      });
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Gagal mengambil histori: $e')),
        );
      }
    }
  }

  Future<void> _pilihRentangTanggal(BuildContext context) async {
    final DateTimeRange? picked = await showDateRangePicker(
      context: context,
      firstDate: DateTime(2020),
      lastDate: DateTime(2030),
      initialDateRange: _selectedDateRange,
    );
    if (picked != null && picked != _selectedDateRange) {
      setState(() {
        _selectedDateRange = picked;
      });
      _fetchHistori();
    }
  }

  void _resetFilterTanggal() {
    setState(() {
      _selectedDateRange = null;
    });
    _fetchHistori();
  }


  @override
  Widget build(BuildContext context) {
    final hasStock = widget.barang.stok > 0;

    return Scaffold(
      backgroundColor: AppConstants.backgroundColor,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 1,
        iconTheme: const IconThemeData(
          color: AppConstants.textColorDark,
        ),
        title: Row(
          children: [
            // Logo UMKM di AppBar
            Image.asset(
              'assets/logo_UMKM.png',
              height: 28,
              errorBuilder: (context, error, stackTrace) =>
                  const Icon(Icons.inventory, color: AppConstants.primaryColor),
            ),
            const SizedBox(width: 12),
            const Text(
              'Detail Barang',
              style: TextStyle(
                color: AppConstants.textColorDark,
                fontWeight: FontWeight.bold,
                fontSize: 18,
              ),
            ),
          ],
        ),
      ),
      body: Column(
        children: [
          // Kartu Identitas Item
          Container(
            width: double.infinity,
            margin: const EdgeInsets.all(16),
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(8),
              border: Border.all(
                color: Colors.grey.shade300,
              ),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    // Kotak Ikon Biru
                    Container(
                      padding: const EdgeInsets.all(10),
                      decoration: BoxDecoration(
                        color: AppConstants.primaryColor.withOpacity(0.1),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: const Icon(
                        Icons.inventory_2,
                        color: AppConstants.primaryColor,
                        size: 28,
                      ),
                    ),
                    const SizedBox(width: 16),
                    // Info Nama & Kode
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            widget.barang.nama,
                            style: const TextStyle(
                              fontSize: 20,
                              fontWeight: FontWeight.bold,
                              color: AppConstants.textColorDark,
                            ),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            "Kode: ${widget.barang.kode} • ${widget.barang.kategori}",
                            style: const TextStyle(
                              fontSize: 14,
                              color: AppConstants.textColorLight,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
                const Padding(
                  padding: EdgeInsets.symmetric(vertical: 16),
                  child: Divider(),
                ),
                // Informasi detail tambahan
                Row(
                  children: [
                    Expanded(
                      child: _buildDetailItem("Satuan", widget.barang.satuan),
                    ),
                    Expanded(
                      child: _buildDetailItem("Min. Stok", "${widget.barang.minStok} ${widget.barang.satuan}"),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                Row(
                  children: [
                    Expanded(
                      child: _buildDetailItem("Harga Pokok", "Rp ${_formatRupiah(widget.barang.hargaPokok)}"),
                    ),
                    Expanded(
                      child: _buildDetailItem("Harga Jual", "Rp ${_formatRupiah(widget.barang.hargaJual)}"),
                    ),
                  ],
                ),

                const Padding(
                  padding: EdgeInsets.symmetric(vertical: 16),
                  child: Divider(),
                ),
                // Bagian Stok
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text(
                      "Sisa Stok Gudang:",
                      style: TextStyle(fontSize: 14, color: AppConstants.textColorLight),
                    ),
                    Container(
                      padding: const EdgeInsets.symmetric(
                        horizontal: 12,
                        vertical: 4,
                      ),
                      decoration: BoxDecoration(
                        color: hasStock
                            ? AppConstants.successColor.withOpacity(0.1)
                            : AppConstants.dangerColor.withOpacity(0.1),
                        borderRadius: BorderRadius.circular(4),
                      ),
                      child: Text(
                        "${widget.barang.stok}",
                        style: TextStyle(
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                          color: hasStock
                              ? AppConstants.successColor
                              : AppConstants.dangerColor,
                        ),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),

          // Riwayat stok dan filter tanggal
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text(
                  "Histori Perubahan Stok",
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    color: AppConstants.textColorDark,
                  ),
                ),
                Row(
                  children: [
                    if (_selectedDateRange != null) ...[
                      IconButton(
                        icon: const Icon(Icons.clear, color: AppConstants.dangerColor, size: 20),
                        onPressed: _resetFilterTanggal,
                        tooltip: "Reset Filter Tanggal",
                        constraints: const BoxConstraints(),
                        padding: const EdgeInsets.only(right: 8),
                      ),
                    ],
                    OutlinedButton.icon(
                      onPressed: () => _pilihRentangTanggal(context),
                      icon: const Icon(
                        Icons.calendar_month,
                        size: 16,
                        color: AppConstants.primaryColor,
                      ),
                      label: Text(
                        _selectedDateRange == null
                            ? "Filter Tanggal"
                            : "${_selectedDateRange!.start.day}/${_selectedDateRange!.start.month} - ${_selectedDateRange!.end.day}/${_selectedDateRange!.end.month}",
                        style: const TextStyle(color: AppConstants.primaryColor),
                      ),
                      style: OutlinedButton.styleFrom(
                        side: BorderSide(color: AppConstants.primaryColor.withOpacity(0.4)),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),

          const SizedBox(height: 8),

          // Daftar riwayat perubahan stok
          Expanded(
            child: _isLoadingHistori
                ? const Center(
                    child: CircularProgressIndicator(color: AppConstants.primaryColor),
                  )
                : _historiStok.isEmpty
                    ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(Icons.history, size: 60, color: Colors.grey[300]),
                            const SizedBox(height: 10),
                            Text(
                              "Tidak ada riwayat pada tanggal ini.",
                              style: TextStyle(color: Colors.grey[500]),
                            ),
                            if (_selectedDateRange != null)
                              TextButton(
                                onPressed: _resetFilterTanggal,
                                child: const Text(
                                  "Tampilkan Semua",
                                  style: TextStyle(color: AppConstants.primaryColor),
                                ),
                              ),
                          ],
                        ),
                      )
                    : ListView.builder(
                        padding: const EdgeInsets.symmetric(
                          horizontal: 16,
                          vertical: 8,
                        ),
                        itemCount: _historiStok.length,
                        itemBuilder: (context, index) {
                          final trx = _historiStok[index];
                          return HistoriCard(histori: trx);
                        },
                      ),
          ),
        ],
      ),
      bottomNavigationBar: _bangunNavigasiBawah(),
      floatingActionButton: _bangunTombolScan(),
      floatingActionButtonLocation: FloatingActionButtonLocation.centerDocked,
    );
  }

  Widget _bangunNavigasiBawah() {
    return BottomAppBar(
      shape: const CircularNotchedRectangle(),
      notchMargin: 8,
      child: SizedBox(
        height: 60,
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceAround,
          children: [
            Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(Icons.inventory, color: AppConstants.textColorLight),
                const Text('Barang', style: TextStyle(fontSize: 12)),
              ],
            ),
            const SizedBox(width: 48), // Ruang untuk tombol apung
            Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(Icons.group, color: AppConstants.primaryColor),
                const Text('Notifikasi', style: TextStyle(fontSize: 12)),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _bangunTombolScan() {
    return Container(
      margin: const EdgeInsets.only(top: 30),
      height: 80,
      width: 80,
      decoration: BoxDecoration(
        color: AppConstants.primaryColor,
        shape: BoxShape.circle,
        border: Border.all(color: Colors.white, width: 4),
      ),
      child: const Center(
        child: Text(
          'Scan',
          style: TextStyle(
            color: Colors.white,
            fontWeight: FontWeight.bold,
            fontSize: 18,
          ),
        ),
      ),
    );
  }

  Widget _buildDetailItem(String label, String value, {Color? textColor}) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: const TextStyle(fontSize: 12, color: AppConstants.textColorLight),
        ),
        const SizedBox(height: 4),
        Text(
          value,
          style: TextStyle(
            fontSize: 14,
            fontWeight: FontWeight.bold,
            color: textColor ?? AppConstants.textColorDark,
          ),
        ),
      ],
    );
  }

  String _formatRupiah(double value) {
    return value.toStringAsFixed(0).replaceAllMapped(
      RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'),
      (Match m) => '${m[1]}.',
    );
  }

}


