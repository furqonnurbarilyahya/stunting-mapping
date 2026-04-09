## 🎯 Objective
Menampilkan peta Jawa Timur dengan klaster stunting.

## 🧩 Background
Visualisasi peta adalah fitur utama aplikasi.

## ✅ Scope of Work
- Integrasi pustaka pemetaan Leaflet.js ke dalam *frontend*.
- Load data koordinat dan informasi wilayah melalui GeoJSON atau dari *endpoint* API JSON yang ada.
- Menerapkan fungsi pewarnaan wilayah (*choropleth / color-coding*) berdasarkan kategori klaster stunting secara dinamis.

## 📦 Expected Output
Sebuah komponen peta interaktif yang tertanam di UI aplikasi dan dapat menampilkan gradasi/warna berbeda di setiap area berdasarkan tingkatan klasternya.

## ✅ Acceptance Criteria
- [ ] Peta tampil dengan benar saat halaman dimuat
- [ ] Masing-masing wilayah memiliki warna berbeda yang berkorespondensi dengan status klasternya
- [ ] Tidak ada error Javascript terkait Leaflet yang muncul di halaman console browser
