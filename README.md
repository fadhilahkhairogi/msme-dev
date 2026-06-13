Aplikasi pencatatan inventori UMKM tanpa invoice adalah aplikasi yang berfungsi untuk mencatat transaksi penjualan secara langsung (direct sales recording) tanpa menghasilkan dokumen tagihan (invoice), tetapi tetap melakukan pencatatan data transaksi, pengurangan stok, dan penyusunan laporan penjualan.


# Cara menjalankan projek

Cara untuk menyalin repositori ke lokal, instalasi dependensi, dan konfigurasi database awal agar aplikasi dapat berjalan. Dilakukan sebelum pertama kali menjalankan aplikasi

a. Buka command prompt pada folder project

b. Jalankan perintah:

```bash
git clone https://github.com/fadhilahkhairogi/msme-dev umkm-app

cd umkm-app

copy .env.example .env

composer install

php artisan key:generate
```

c. Buka file .env dan ubah baris berikut:

```properties
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

menjadi

```properties
# DB_CONNECTION=sqlite
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_umkm
DB_USERNAME=root
DB_PASSWORD=
```

d. Buka xampp, lalu klik tombol “Start” pada opsi MySQL, Tunggu hingga jalan

e. klik tombol “Admin” disamping tombol “Start”

f. buka “http://localhost/phpmyadmin/”

g. jalankan SQL berikut di phpMyAdmin:

```sql
CREATE DATABASE db_umkm;
```

h. jalankan aplikasi dengan membuka command prompt di folder umkm-app, jalankan:

```bash
php artisan migrate:refresh

php artisan db:seed

php artisan serve --host 0.0.0.0
```

i. buka aplikasi web pada link “http://localhost:8000/produk”

j. buka Aplikasi mobile yang terletak di "./Mobile" pada Android Atudio
