import 'dart:convert';
import 'package:flutter_test/flutter_test.dart';
import 'package:http/http.dart' as http;
import 'package:http/testing.dart';
import 'package:mobile_logistik/services/api_service.dart';
import 'package:mobile_logistik/models/histori_stok.dart';

void main() {
  group('WB-05: ApiService.getAllHistoriStok()', () {
    // WB-05 getAllHistoriStok() V(G)=5 P1: tanpa filter — return semua
    test('P1: tanpa filter', () async {
      final mockResponse = {
        'data': [
          {
            'id': 1,
            'barang_id': 1,
            'selisih_stok': 10,
            'jenis': 'Barang masuk',
            'edited_at': '2026-06-27 16:00:00',
            'barang': {'id': 1, 'nama': 'Kopi', 'kode': 'K001'},
          }
        ]
      };

      final client = MockClient((request) async {
        expect(request.url.path, '/api/all-histori-stok');
        return http.Response(jsonEncode(mockResponse), 200);
      });

      final api = ApiService(client: client);
      final result = await api.getAllHistoriStok();

      expect(result.length, 1);
      expect(result[0].jenis, 'Barang masuk');
      expect(result[0].barang!.nama, 'Kopi');
    });

    // WB-05 getAllHistoriStok() V(G)=5 P2: date filter — request dengan param tanggal
    test('P2: date filter', () async {
      final mockResponse = {'data': <Map<String, dynamic>>[]};

      final client = MockClient((request) async {
        final uri = request.url;
        expect(uri.queryParameters['start_date'], '2026-01-01');
        expect(uri.queryParameters['end_date'], '2026-12-31');
        return http.Response(jsonEncode(mockResponse), 200);
      });

      final api = ApiService(client: client);
      final result = await api.getAllHistoriStok(
        startDate: '2026-01-01',
        endDate: '2026-12-31',
      );

      expect(result, isEmpty);
    });

    // WB-05 getAllHistoriStok() V(G)=5 P3: date + jenis filter — param lengkap
    test('P3: date + jenis filter', () async {
      final mockResponse = {
        'data': [
          {
            'id': 2,
            'barang_id': 2,
            'selisih_stok': -5,
            'jenis': 'Barang keluar',
            'edited_at': '2026-06-20 14:00:00',
            'barang': null,
          }
        ]
      };

      final client = MockClient((request) async {
        final uri = request.url;
        expect(uri.queryParameters['start_date'], '2026-06-01');
        expect(uri.queryParameters['end_date'], '2026-06-30');
        expect(uri.queryParameters['jenis'], 'Barang keluar');
        return http.Response(jsonEncode(mockResponse), 200);
      });

      final api = ApiService(client: client);
      final result = await api.getAllHistoriStok(
        startDate: '2026-06-01',
        endDate: '2026-06-30',
        jenis: 'Barang keluar',
      );

      expect(result.length, 1);
      expect(result[0].jenis, 'Barang keluar');
      expect(result[0].selisihStok, -5);
    });

    // WB-05 getAllHistoriStok() V(G)=5 P4: response 500 — throw Exception
    test('P4: server error 500', () async {
      final client = MockClient((request) async {
        return http.Response('Internal Server Error', 500);
      });

      final api = ApiService(client: client);

      expect(
        () => api.getAllHistoriStok(),
        throwsA(isA<Exception>()),
      );
    });

    // WB-05 getAllHistoriStok() V(G)=5 P5: network error — throw Exception
    test('P5: network error', () async {
      final client = MockClient((request) async {
        throw Exception('Connection refused');
      });

      final api = ApiService(client: client);

      expect(
        () => api.getAllHistoriStok(),
        throwsA(isA<Exception>()),
      );
    });
  });
}
