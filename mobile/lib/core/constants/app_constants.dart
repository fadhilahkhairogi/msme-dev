import 'package:flutter/material.dart';

class AppConstants {
  // Base URL API (Mengarah ke backend Laravel umkm-app di port 8000)
  static const String baseUrl = 'http://10.0.2.2:8000/api';


  // Desain & Warna Tema (Khas AdminLTE)
  static const Color backgroundColor = Color(0xFFF4F6F9);
  static const Color primaryColor = Colors.blue;
  static const Color textColorDark = Colors.black87;
  static const Color textColorLight = Colors.grey;
  
  // Status/Badge Colors
  static const Color successColor = Colors.green;
  static const Color dangerColor = Colors.red;
}
