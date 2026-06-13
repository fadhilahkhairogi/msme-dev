import 'package:flutter/material.dart';
import '../core/constants/app_constants.dart';
import '../models/histori_stok.dart';
import '../services/api_service.dart';
import '../widgets/histori_card.dart';
import '../widgets/search_bar_widget.dart';
import 'detail_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({Key? key}) : super(key: key);

  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final ApiService _apiService = ApiService();
  final TextEditingController _searchController = TextEditingController();
  
  List<HistoriStok> _dataHistori = [];
  bool _isLoading = true;
  DateTimeRange? _selectedDateRange;
  String? _selectedJenis;

  @override
  void initState() {
    super.initState();
    _fetchData();
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  Future<void> _fetchData([String query = '']) async {
    setState(() {
      _isLoading = true;
    });

    String? startStr;
    String? endStr;
    if (_selectedDateRange != null) {
      startStr = "${_selectedDateRange!.start.year}-${_selectedDateRange!.start.month.toString().padLeft(2, '0')}-${_selectedDateRange!.start.day.toString().padLeft(2, '0')}";
      endStr = "${_selectedDateRange!.end.year}-${_selectedDateRange!.end.month.toString().padLeft(2, '0')}-${_selectedDateRange!.end.day.toString().padLeft(2, '0')}";
    }

    try {
      final data = await _apiService.getAllHistoriStok(
        query: query,
        startDate: startStr,
        endDate: endStr,
        jenis: _selectedJenis,
      );
      setState(() {
        _dataHistori = data;
        _isLoading = false;
      });
    } catch (e) {
      print("HomeScreen._fetchData Error: $e");
      setState(() {
        _isLoading = false;
      });
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Terjadi kesalahan: $e')),
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
      _fetchData(_searchController.text);
    }
  }

  void _resetFilterTanggal() {
    setState(() {
      _selectedDateRange = null;
    });
    _fetchData(_searchController.text);
  }

  void _tampilkanDialogPilihJenis() {
    showDialog<String>(
      context: context,
      builder: (BuildContext context) {
        return SimpleDialog(
          title: const Text('Pilih Jenis Aktivitas'),
          children: <Widget>[
            SimpleDialogOption(
              onPressed: () {
                Navigator.pop(context, 'Barang masuk');
              },
              child: const Row(
                children: [
                  Icon(Icons.arrow_downward, color: Colors.green),
                  SizedBox(width: 8),
                  Text('Barang Masuk'),
                ],
              ),
            ),
            SimpleDialogOption(
              onPressed: () {
                Navigator.pop(context, 'Barang keluar');
              },
              child: const Row(
                children: [
                  Icon(Icons.arrow_upward, color: Colors.red),
                  SizedBox(width: 8),
                  Text('Barang Keluar'),
                ],
              ),
            ),
            SimpleDialogOption(
              onPressed: () {
                Navigator.pop(context, 'Semua');
              },
              child: const Row(
                children: [
                  Icon(Icons.clear_all, color: Colors.grey),
                  SizedBox(width: 8),
                  Text('Semua'),
                ],
              ),
            ),
          ],
        );
      },
    ).then((value) {
      if (value != null) {
        setState(() {
          if (value == 'Semua') {
            _selectedJenis = null;
          } else {
            _selectedJenis = value;
          }
        });
        _fetchData(_searchController.text);
      }
    });
  }


  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppConstants.backgroundColor,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 1,
        title: Row(
          children: [
            // Logo UMKM di Header
            Image.asset(
              'assets/logo_UMKM.png',
              height: 32,
              errorBuilder: (context, error, stackTrace) =>
                  const Icon(Icons.inventory, color: AppConstants.primaryColor),
            ),
            const SizedBox(width: 12),
            const Text(
              'Logistik Gudang',
              style: TextStyle(
                color: AppConstants.textColorDark,
                fontWeight: FontWeight.bold,
                fontSize: 18,
              ),
            ),
          ],
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.person, color: AppConstants.textColorLight),
            onPressed: () {
              // Nanti untuk menu Profil / Logout
            },
          ),
        ],
      ),
      body: Column(
        children: [
          // Bagian Pencarian (Search Bar)
          SearchBarWidget(
            controller: _searchController,
            onChanged: (value) {
              _fetchData(value);
            },
          ),

          // Filter tanggal dan tipe aktivitas
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text(
                  "Filter:",
                  style: TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.bold,
                    color: AppConstants.textColorDark,
                  ),
                ),
                Row(
                  children: [
                    // Filter tanggal
                    if (_selectedDateRange != null) ...[
                      IconButton(
                        icon: const Icon(Icons.clear, color: AppConstants.dangerColor, size: 20),
                        onPressed: _resetFilterTanggal,
                        tooltip: "Reset Filter Tanggal",
                        constraints: const BoxConstraints(),
                        padding: const EdgeInsets.only(right: 4),
                      ),
                    ],
                    OutlinedButton.icon(
                      onPressed: () => _pilihRentangTanggal(context),
                      icon: const Icon(
                        Icons.calendar_month,
                        size: 14,
                        color: AppConstants.primaryColor,
                      ),
                      label: Text(
                        _selectedDateRange == null
                            ? "Pilih Tanggal"
                            : "${_selectedDateRange!.start.day}/${_selectedDateRange!.start.month} - ${_selectedDateRange!.end.day}/${_selectedDateRange!.end.month}",
                        style: const TextStyle(color: AppConstants.primaryColor, fontSize: 11),
                      ),
                      style: OutlinedButton.styleFrom(
                        side: BorderSide(color: AppConstants.primaryColor.withOpacity(0.4)),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                        minimumSize: Size.zero,
                        tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                      ),
                    ),
                    const SizedBox(width: 8),
                    // Filter tipe aktivitas
                    OutlinedButton(
                      onPressed: _tampilkanDialogPilihJenis,
                      style: OutlinedButton.styleFrom(
                        side: BorderSide(color: AppConstants.primaryColor.withOpacity(0.4)),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                        minimumSize: Size.zero,
                        tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                      ),
                      child: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Text(
                            _selectedJenis == null
                                ? "Aktivitas"
                                : _selectedJenis == "Barang masuk"
                                    ? "Masuk"
                                    : "Keluar",
                            style: const TextStyle(color: AppConstants.primaryColor, fontSize: 11),
                          ),
                          const SizedBox(width: 2),
                          const Icon(
                            Icons.arrow_drop_down,
                            size: 16,
                            color: AppConstants.primaryColor,
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),

          const SizedBox(height: 10),

          // Daftar Barang (ListView) dengan Pull-to-Refresh
          Expanded(
            child: _isLoading
                ? const Center(
                    child: CircularProgressIndicator(color: AppConstants.primaryColor),
                  )
                : _dataHistori.isEmpty
                    ? const Center(
                        child: Text(
                          "Histori stok tidak ditemukan",
                          style: TextStyle(color: AppConstants.textColorLight),
                        ),
                      )
                    : RefreshIndicator(
                        onRefresh: () => _fetchData(_searchController.text),
                        color: AppConstants.primaryColor,
                        backgroundColor: Colors.white,
                        child: ListView.builder(
                          physics: const AlwaysScrollableScrollPhysics(),
                          itemCount: _dataHistori.length,
                          padding: const EdgeInsets.symmetric(horizontal: 12),
                          itemBuilder: (context, index) {
                            final histori = _dataHistori[index];
                            return HistoriCard(
                              histori: histori,
                              showProductName: true,
                              onTap: () {
                                if (histori.barang != null) {
                                  Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                      builder: (context) => DetailScreen(barang: histori.barang!),
                                    ),
                                  );
                                }
                              },
                            );
                          },
                        ),
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
                Icon(Icons.inventory, color: AppConstants.primaryColor),
                const Text('Barang', style: TextStyle(fontSize: 12)),
              ],
            ),
            const SizedBox(width: 48), // Ruang untuk tombol apung
            Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(Icons.group, color: AppConstants.textColorLight),
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
}

