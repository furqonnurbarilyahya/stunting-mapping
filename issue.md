## 🎯 Objective
Menghadirkan Panel Detail interaktif (seperti *Sidebar* atau *Pop-up Card* ekstensif) yang seketika mengekspos deretan lengkap informasi riset wilayah sewaktu pengguna berinteraksi memilih suatu lokasi di dalam Peta.

## 🧩 Background
Aplikasi saat ini hanya memancarkan kode klaster spasialnya saja. Namun, mengingat basis dari perangkat lunak ini adalah visualisasi ekstensif hasil dokumentasi medis stunting, sistem dituntut untuk bisa memaparkan isi perut angkanya ke hadapan pengguna secara transparan. Tidak dibenarkan menyembunyikan hasil penelitian lapangan yang vital tersebut dari *viewport* (*layar*) audiens utama.

## ✅ Scope of Work
- **Konstruksi *Layout* Komponen Panel/Sidebar**: Menyuntikkan konstruksi ruang elemen (bisa membelah layar *layout* peta atau menggunakan antarmuka *Overlay* bayangan melayang) yang bereaksi mendengarkan *(listening)* pemicu *(trigger)* interaktif pilihan dari *Marker Leaflet* maupun menu *dropdown* wilayah.
- **Integrasi Pemasokan Data Mentah (*Data Binding*)**: Menyalurkan seluruh properti muatan hasil respons JSON dari server dan melampirkannya atau membekalkannya *(looping list)* ke baris-baris tampilan teks UI secara dinamis pada *front-end*.
- **Logika Eksklusi Filter Atribut**: Memetakan *seluruh kolom murni* yang ditarik dari *database* (misalnya: Imunisasi, Sanitasi, dan indikator malnutrisi lainnya), sembari menyaring dengan keras **agar khusus kolom/variabel `lisa_cluster` tidak ikut tampil (disembunyikan operasionalnya/dihiraukan)**.

## ❌ Out of Scope
- Anda tidak diwajibkan menyertakan konversi visual bagan grafik (seperti *Bar/Pie Chart*) interaktif per indikator pada komponen ini. Daftar urutan nilai dalam desain tabular mendatar atau bersusun ke bawah saja sudah sempurna, asalkan estetis dan tertata.

## 📦 Expected Output
Setiap pemicu pemilihan kabupaten/wilayah meletupkan sebuah daftar rinci (*Card List*) di sisi peta, menyampaikan seluruh komponen pembangun parameter dengan jelas bagi mata riset analis pengguna.

## ✅ Acceptance Criteria
- [ ] Wadah (*container*) komponen Detail Data terbuka responsif dan sinkron mengikuti klik wilayah terbaru
- [ ] Berisikan semua data indikator secara meluas (inklusif *all columns*)
- [ ] Secara mutlak, label khusus metrik `lisa_cluster` *tidak tembus* ke daftar panel tampilan
- [ ] Memiliki detektor *fallback* sehingga seandainya sebuah kolom tidak senonoh (*null/empty*), antarmuka menampilkan strip (`-`) yang elegan agar visualisasi sistem terhindar dari disabilitas struktural
