import 'dart:convert';
import 'package:flutter_test/flutter_test.dart';
import 'package:http/http.dart' as http;
import 'package:http/testing.dart';
import 'package:mobile_logistik/services/api_service.dart';
import 'package:mobile_logistik/models/histori_stok.dart';

void main() {
  group('WB-06: _fetchHistori() via getHistoriStok()', () {
    // WB-06 _fetchHistori() V(G)=4 P1: tanpa filter — 2 data histori
    test('P1: tanpa filter', () async {
      final mockResponse = {
        'data': [
          {
            'id': 1,
            'barang_id': 1,
            'selisih_stok': 20,
            'jenis': 'Barang masuk',
            'edited_at': '2026-05-15 10:00:00',
          },
          {
            'id': 2,
            'barang_id': 1,
            'selisih_stok': 10,
            'jenis': 'Barang masuk',
            'edited_at': '2026-06-27 16:00:00',
          },
        ]
      };

      final client = MockClient((request) async {
        expect(request.url.path, '/api/histori-stok/1');
        return http.Response(jsonEncode(mockResponse), 200);
      });

      final api = ApiService(client: client);
      final result = await api.getHistoriStok(1);

      expect(result.length, 2);
      expect(result[0].selisihStok, 20);
    });

    // WB-06 _fetchHistori() V(G)=4 P2: date filter — 1 data terfilter
    test('P2: date filter', () async {
      final mockResponse = {
        'data': [
          {
            'id': 2,
            'barang_id': 1,
            'selisih_stok': 10,
            'jenis': 'Barang masuk',
            'edited_at': '2026-06-27 16:00:00',
          },
        ]
      };

      final client = MockClient((request) async {
        final uri = request.url;
        expect(uri.queryParameters['start_date'], '2026-06-01');
        expect(uri.queryParameters['end_date'], '2026-06-30');
        return http.Response(jsonEncode(mockResponse), 200);
      });

      final api = ApiService(client: client);
      final result = await api.getHistoriStok(
        1,
        startDate: '2026-06-01',
        endDate: '2026-06-30',
      );

      expect(result.length, 1);
    });

    // WB-06 _fetchHistori() V(G)=4 P3: server 500 — throw Exception
    test('P3: server error 500', () async {
      final client = MockClient((request) async {
        return http.Response('Server Error', 500);
      });

      final api = ApiService(client: client);
      expect(
        () => api.getHistoriStok(1),
        throwsA(isA<Exception>()),
      );
    });

    // WB-06 _fetchHistori() V(G)=4 P4: network error — throw Exception
    test('P4: network error', () async {
      final client = MockClient((request) async {
        throw Exception('Network timeout');
      });

      final api = ApiService(client: client);
      expect(
        () => api.getHistoriStok(1),
        throwsA(isA<Exception>()),
      );
    });
  });
}
