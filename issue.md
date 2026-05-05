# 🗺️ Epic: Implementasi Choropleth Map Batas Wilayah Jawa Timur

## 🎯 Objective
Mengintegrasikan batas wilayah kabupaten/kota Jawa Timur ke dalam peta berbasis Leaflet sehingga visualisasi data berbentuk choropleth dapat ditampilkan secara akurat per wilayah.

## 🧩 Background
Saat ini peta hanya menggunakan titik centroid (latitude & longitude) dari database, sehingga visualisasi data tidak mengikuti batas administratif wilayah. Hal ini menyebabkan warna atau cluster antar kabupaten/kota dapat tercampur dan tidak representatif. 

Dengan menggunakan data GeoJSON hasil olahan dari QGIS, setiap kabupaten/kota dapat divisualisasikan sebagai polygon yang terpisah, memungkinkan penerapan choropleth map yang lebih akurat.

## ✅ Scope of Work
Berikut adalah tahapan implementasi level tinggi (high-level) yang harus diselesaikan:

1. **Persiapan Data GeoJSON**
   - Export data batas wilayah kabupaten/kota Jawa Timur dari software QGIS ke format GeoJSON.
   - Pastikan file output menggunakan *Coordinate Reference System* (CRS) WGS84 / EPSG:4326.
   - Simpan file GeoJSON ke dalam direktori publik (public directory) pada project Laravel agar dapat diakses oleh frontend.

2. **Rendering Peta Dasar & Polygon**
   - Load file GeoJSON tersebut di halaman frontend menggunakan `fetch` atau AJAX.
   - Tambahkan data tersebut ke dalam instance Leaflet menggunakan fitur `L.geoJSON()`.
   - Pastikan setiap kabupaten/kota muncul sebagai polygon mandiri (tidak saling terikat).

3. **Mapping Data Backend ke Spasial**
   - Ambil data klastering (stunting) yang ada di database melalui API / Controller yang sudah tersedia.
   - Cocokkan (mapping) data dari database dengan setiap feature di GeoJSON berdasarkan key nama wilayah (kabupaten/kota).

4. **Implementasi Gaya Peta Choropleth**
   - Buat fungsi *styling* dinamis untuk Leaflet yang akan mengubah `fillColor` masing-masing polygon sesuai dengan kelompok/nilai datanya.
   - Atur `color` (garis batas/stroke), `weight`, dan `fillOpacity` secara proporsional. Pastikan tiap wilayah punya batas garis yang tegas untuk mencegah percampuran warna (bleeding).

5. **Penambahan Label & Interaksi Map**
   - Tampilkan nama masing-masing wilayah sebagai teks/label yang secara permanen menempel di bagian tengah tiap polygon (bisa menggunakan `bindTooltip` dengan parameter *permanent* atau metode sejenis).
   - Buat interaksi *popup* menggunakan fungsi `onEachFeature` sehingga saat salah satu polygon diklik, informasi spesifik dari daerah tersebut akan ditampilkan.

## 📦 Expected Output
- Peta menampilkan batas wilayah kabupaten/kota Jawa Timur secara jelas dan presisi.
- Setiap wilayah memiliki warna sesuai nilai data klaster pada database untuk dijadikan peta choropleth.
- Tidak ada overlap warna antar wilayah.
- Nama kabupaten/kota tampil langsung di peta (tidak perlu dihover baru muncul).
- Informasi detail dapat diakses melalui interaksi (popup).

## ✅ Acceptance Criteria
- [ ] GeoJSON berhasil di-load dan ditampilkan di Leaflet secara utuh.
- [ ] Setiap kabupaten/kota ditampilkan sebagai polygon terpisah.
- [ ] Data dari database berhasil terhubung ke masing-masing wilayah pada file GeoJSON.
- [ ] Warna choropleth map sudah sesuai dengan nilai data dari backend.
- [ ] Label nama kabupaten/kota tampil statis di atas peta.
- [ ] Tidak terjadi “bleeding” warna antar batas wilayah.
- [ ] Popup muncul dan menampilkan informasi yang sesuai/spesifik saat wilayah diklik.
