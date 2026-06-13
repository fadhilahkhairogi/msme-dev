import 'dart:convert';
import 'package:http/http.dart' as http;
import '../core/constants/app_constants.dart';
import '../models/barang.dart';
import '../models/histori_stok.dart';

class ApiService {
  final http.Client _client;

  // Constructor, menerima http.Client opsional untuk unit testing
  ApiService({http.Client? client}) : _client = client ?? http.Client();

  // Ambil data barang dengan pencarian dan rentang tanggal
  Future<List<Barang>> getBarang({String query = '', String? startDate, String? endDate}) async {
    String urlString = '${AppConstants.baseUrl}/barang?search=$query';
    if (startDate != null && endDate != null && startDate.isNotEmpty && endDate.isNotEmpty) {
      urlString += '&start_date=$startDate&end_date=$endDate';
    }
    final url = Uri.parse(urlString);

    try {
      final response = await _client.get(url);
      if (response.statusCode == 200) {
        final Map<String, dynamic> responseData = json.decode(response.body);
        final List<dynamic> data = responseData['data'] ?? [];
        return data.map((item) => Barang.fromJson(item)).toList();
      } else {
        throw Exception('Gagal memuat data barang (Status: ${response.statusCode})');
      }
    } catch (e) {
      print('ApiService.getBarang Error: $e');
      rethrow;
    }
  }

  // Ambil riwayat stok per barang
  Future<List<HistoriStok>> getHistoriStok(int barangId, {String? startDate, String? endDate, String? jenis}) async {
    String urlString = '${AppConstants.baseUrl}/histori-stok/$barangId';
    List<String> params = [];
    if (startDate != null && endDate != null && startDate.isNotEmpty && endDate.isNotEmpty) {
      params.add('start_date=$startDate');
      params.add('end_date=$endDate');
    }
    if (jenis != null && jenis.isNotEmpty) {
      params.add('jenis=${Uri.encodeComponent(jenis)}');
    }
    if (params.isNotEmpty) {
      urlString += '?${params.join('&')}';
    }

    final url = Uri.parse(urlString);

    try {
      final response = await _client.get(url);
      if (response.statusCode == 200) {
        final Map<String, dynamic> responseData = json.decode(response.body);
        final List<dynamic> data = responseData['data'] ?? [];
        return data.map((item) => HistoriStok.fromJson(item)).toList();
      } else {
        throw Exception('Gagal memuat histori stok (Status: ${response.statusCode})');
      }
    } catch (e) {
      print('ApiService.getHistoriStok Error: $e');
      rethrow;
    }
  }

  // Ambil semua data riwayat stok
  Future<List<HistoriStok>> getAllHistoriStok({String query = '', String? startDate, String? endDate, String? jenis}) async {
    String urlString = '${AppConstants.baseUrl}/all-histori-stok?search=$query';
    if (startDate != null && endDate != null && startDate.isNotEmpty && endDate.isNotEmpty) {
      urlString += '&start_date=$startDate&end_date=$endDate';
    }
    if (jenis != null && jenis.isNotEmpty) {
      urlString += '&jenis=${Uri.encodeComponent(jenis)}';
    }

    final url = Uri.parse(urlString);

    try {
      final response = await _client.get(url);
      if (response.statusCode == 200) {
        final Map<String, dynamic> responseData = json.decode(response.body);
        final List<dynamic> data = responseData['data'] ?? [];
        return data.map((item) => HistoriStok.fromJson(item)).toList();
      } else {
        throw Exception('Gagal memuat semua histori stok (Status: ${response.statusCode})');
      }
    } catch (e) {
      print('ApiService.getAllHistoriStok Error: $e');
      rethrow;
    }
  }
}

