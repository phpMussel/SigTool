### Bagaimana cara menginstal:

Sebelum menginstal, mohon periksa persyaratannya. Jika ini tidak terpenuhi, SigTool tidak akan beroperasi dengan benar.

#### Persyaratan:
- __Untuk SigTool &lt;=1.0.2:__ PHP &gt;=7.0.3 dengan dukungan phar (PHP &gt;=7.2.0 direkomendasikan).
- __Untuk SigTool 1.0.3:__ PHP &gt;=7.0.3 (PHP &gt;=7.2.0 direkomendasikan).
- __Untuk SigTool v2:__ PHP &gt;=7.2.0.
- __Semua versi:__ *Setidaknya* &gt;=681 MB RAM yang tersedia (tapi, minimal &gt;=1 GB sangat direkomendasikan).
- __Semua versi:__ Sekitar ~300 MB ruang disk yang tersedia (jumlah ini dapat bervariasi antara iterasi database tanda tangan).
- __Semua versi:__ Kemampuan untuk mengoperasikan PHP dalam modus CLI (misalnya, command prompt, terminal, shell, bash, dll).

The recommended way to install SigTool is through Composer.

`composer require phpmussel/sigtool`

Sebaliknya, Anda dapat mengkloning repositori, atau mengunduh ZIP, langsung dari GitHub.

---


### Bagaimana cara menggunakan:

Perhatikan bahwa SigTool BUKAN aplikasi web berbasis PHP (atau web-app)! SigTool adalah aplikasi CLI berbasis PHP (atau CLI-app) dimaksudkan untuk digunakan dengan terminal, shell, dll. Hal ini dapat dipanggil dengan memanggil biner PHP dengan file `SigTool.php` sebagai argumen pertamanya:

`$ php SigTool.php`

Informasi bantuan akan ditampilkan saat SigTool dipanggil, mencantumkan flag yang mungkin (argumen kedua) yang bisa digunakan saat mengoperasikan SigTool.

Bendera yang mungkin:
- Tidak ada argumen: Tampilkan informasi bantuan ini.
- `x`: Ekstrak file tanda tangan dari `daily.cvd` dan `main.cvd`.
- `p`: Proses file tanda tangan untuk digunakan dengan phpMussel.
- `m`: Download `main.cvd` sebelum diproses.
- `d`: Download `daily.cvd` sebelum diproses.
- `u`: Perbarui SigTool (download `SigTool.php` lagi dan die; tidak ada pemeriksaan yang dilakukan).

Output yang dihasilkan adalah berbagai file tanda tangan phpMussel yang dihasilkan langsung dari database tanda tangan ClamAV, dalam dua bentuk:
- File tanda tangan yang bisa dimasukkan langsung ke direktori `/vault/signatures/`.
- Dikompresi GZ salinan dari file tanda tangan yang dapat digunakan untuk memperbarui repositori `phpMussel/Signatures`.

Output diproduksi langsung ke direktori yang sama dengan `SigTool.php`. File sumber dan semua file kerja sementara akan dihapus selama operasi berlangsung (jadi, jika Anda ingin menyimpan salinan `daily.cvd` dan `main.cvd`, Anda harus membuat salinan sebelum memproses file tanda tangan).

Saat menggunakan SigTool untuk membuat file tanda tangan baru, mungkin pemindai anti-virus komputer Anda dapat mencoba untuk menghapus atau mengkarantina file tanda tangan yang baru dibuat. Ini terjadi karena kadang-kadang, file tanda tangan mungkin berisi data yang sangat mirip dengan data yang dicari oleh anti-virus Anda saat memindai. Namun, file tanda tangan yang dibuatkan oleh SigTool tidak mengandung kode yang dapat dieksekusi, dan sepenuhnya jinak. Jika Anda mengalami masalah ini, Anda dapat mencoba untuk menonaktifkan sementara pemindai anti-virus Anda, atau mengkonfigurasi pemindai anti-virus Anda untuk dimasukkan ke dalam daftar putih direktori tempat Anda membuat file tanda tangan baru.

Jika file YAML `signatures.dat` disertakan dalam direktori yang sama saat memproses, informasi versi dan checksum akan diperbarui sesuai dengan itu (jadi, saat menggunakan SigTool untuk memperbarui repositori `phpMussel/Signatures`, ini harus disertakan).

*Catatan: Jika Anda pengguna phpMussel, harap diingat bahwa file tanda tangan harus AKTIF agar mereka dapat bekerja dengan benar! Jika Anda menggunakan SigTool untuk menghasilkan file tanda tangan baru, Anda dapat "mengaktifkan" mereka dengan mencantumkannya di direktif konfigurasi "Active" dalam phpMussel. Jika Anda menggunakan halaman pembaruan bagian depan untuk menginstal dan memperbarui file tanda tangan, Anda bisa "mengaktifkan" mereka langsung dari halaman pembaruan bagian depan. Namun, menggunakan kedua metode tersebut tidaklah perlu. Juga, untuk kinerja optimal phpMussel, sebaiknya Anda hanya menggunakan file tanda tangan yang Anda butuhkan untuk instalasi Anda (misalnya, jika beberapa jenis file tertentu dimasukkan dalam daftar hitam, Anda mungkin tidak memerlukan file tanda tangan yang sesuai dengan jenis file tersebut; menganalisis file yang akan diblokir tetap adalah pekerjaan yang berlebihan dan secara signifikan dapat memperlambat proses pemindaian).*

Demonstrasi video untuk menggunakan SigTool tersedia di YouTube: __[youtu.be/f2LfjY1HzRI](https://youtu.be/f2LfjY1HzRI)__

---


### SigTool menghasilkan daftar file tanda tangan:
File tanda tangan | Deskripsi
---|---
clamav.hdb | Menargetkan semua jenis file; Bekerja dengan file hash.
clamav.htdb | Menargetkan file HTML; Bekerja dengan data yang dinormalisasi dengan HTML.
clamav_regex.htdb | Menargetkan file HTML; Bekerja dengan data yang dinormalisasi dengan HTML; Tanda tangan dapat berisi ekspresi reguler.
clamav.mdb | Menargetkan file PE; Bekerja dengan metadata seksi PE.
clamav.ndb | Menargetkan semua jenis file; Bekerja dengan data yang dinormalisasi dengan ANSI.
clamav_regex.ndb | Menargetkan semua jenis file; Bekerja dengan data yang dinormalisasi dengan ANSI; Tanda tangan dapat berisi ekspresi reguler.
clamav.db | Menargetkan semua jenis file; Bekerja dengan data mentah.
clamav_regex.db | Menargetkan semua jenis file; Bekerja dengan data mentah; Tanda tangan dapat berisi ekspresi reguler.
clamav_elf.db | Menargetkan file ELF; Bekerja dengan data mentah.
clamav_elf_regex.db | Menargetkan file ELF; Bekerja dengan data mentah; Tanda tangan dapat berisi ekspresi reguler.
clamav_email.db | Menargetkan file EML; Bekerja dengan data mentah.
clamav_email_regex.db | Menargetkan file EML; Bekerja dengan data mentah; Tanda tangan dapat berisi ekspresi reguler.
clamav_exe.db | Menargetkan file PE; Bekerja dengan data mentah.
clamav_exe_regex.db | Menargetkan file PE; Bekerja dengan data mentah; Tanda tangan dapat berisi ekspresi reguler.
clamav_graphics.db | Targets image files; Bekerja dengan data mentah.
clamav_graphics_regex.db | Targets image files; Bekerja dengan data mentah; Tanda tangan dapat berisi ekspresi reguler.
clamav_java.db | Menargetkan file Java; Bekerja dengan data mentah.
clamav_java_regex.db | Menargetkan file Java; Bekerja dengan data mentah; Tanda tangan dapat berisi ekspresi reguler.
clamav_macho.db | Menargetkan file Mach-O; Bekerja dengan data mentah.
clamav_macho_regex.db | Menargetkan file Mach-O; Bekerja dengan data mentah; Tanda tangan dapat berisi ekspresi reguler.
clamav_ole.db | Menargetkan obyek OLE; Bekerja dengan data mentah.
clamav_ole_regex.db | Menargetkan obyek OLE; Bekerja dengan data mentah; Tanda tangan dapat berisi ekspresi reguler.
clamav_pdf.db | Menargetkan file PDF; Bekerja dengan data mentah.
clamav_pdf_regex.db | Menargetkan file PDF; Bekerja dengan data mentah; Tanda tangan dapat berisi ekspresi reguler.
clamav_swf.db | Menargetkan file SWF; Bekerja dengan data mentah.
clamav_swf_regex.db | Menargetkan file SWF; Bekerja dengan data mentah; Tanda tangan dapat berisi ekspresi reguler.

---


### Catatan mengenai ekstensi file tanda tangan:
*Informasi ini akan diperluas ke depan.*

- __cedb__: File tanda tangan kompleks diperpanjang (ini adalah format yang dibuat untuk phpMussel, dan tidak ada hubungannya dengan database tanda tangan ClamAV; SigTool tidak menghasilkan file tanda tangan apapun dengan menggunakan ekstensi ini; ini ditulis secara manual untuk repositori `phpMussel/Signatures`; `clamav.cedb` x berisi adaptasi dari beberapa tanda tangan usang/tua dari versi sebelumnya dari database tanda tangan ClamAV yang dianggap masih memiliki kegunaan lanjutan untuk phpMussel). File tanda tangan yang bekerja dengan berbagai aturan berdasarkan metadata diperluas yang dihasilkan oleh phpMussel menggunakan ekstensi ini.
- __db__: File tanda tangan standar (ini diekstraksi dari file tanda tangan `.ndb` yang terkandung dalam `daily.cvd` dan `main.cvd`). File tanda tangan yang bekerja secara langsung dengan isi file menggunakan ekstensi ini.
- __fdb__: File tanda tangan nama file (database tanda tangan ClamAV sebelumnya mendukung tanda tangan nama file, tapi tidak lagi; SigTool tidak menghasilkan file tanda tangan apapun dengan menggunakan ekstensi ini; dipertahankan karena kegunaan lanjutan untuk phpMussel). File tanda tangan yang bekerja dengan nama file menggunakan ekstensi ini.
- __hdb__: File tanda tangan hash (ini diekstraksi dari file tanda tangan `.hdb` yang terkandung dalam `daily.cvd` dan `main.cvd`). File tanda tangan yang bekerja dengan file hash menggunakan ekstensi ini.
- __htdb__: File tanda tangan HTML (ini diekstraksi dari file tanda tangan `.ndb` yang terkandung dalam `daily.cvd` dan `main.cvd`). File tanda tangan yang bekerja dengan konten yang dinormalisasi HTML menggunakan ekstensi ini.
- __mdb__: File tanda tangan seksi PE (ini diekstraksi dari file tanda tangan `.mdb` yang terkandung dalam `daily.cvd` dan `main.cvd`). File tanda tangan yang bekerja dengan metadata seksi PE menggunakan ekstensi ini.
- __medb__: File tanda tangan PE diperpanjang (ini adalah format yang dibuat untuk phpMussel, and tidak ada hubungannya dengan database tanda tangan ClamAV; SigTool tidak menghasilkan file tanda tangan apapun dengan menggunakan ekstensi ini; ini ditulis secara manual untuk repositori `phpMussel/Signatures`). File tanda tangan yang bekerja dengan metadata PE (selain metadata seksi PE) menggunakan ekstensi ini.
- __ndb__: File tanda tangan yang dinormalisasi (ini diekstraksi dari file tanda tangan `.ndb` yang terkandung dalam `daily.cvd` dan `main.cvd`). File tanda tangan yang bekerja dengan konten yang dinormalisasi ANSI menggunakan ekstensi ini.
- __udb__: File tanda tangan URL (ini adalah format yang dibuat untuk phpMussel, and tidak ada hubungannya dengan database tanda tangan ClamAV; SigTool *saat ini* tidak menghasilkan file tanda tangan apapun dengan menggunakan ekstensi ini, meskipun hal ini bisa berubah di masa depan; saat ini, ini ditulis secara manual untuk repositori `phpMussel/Signatures`). File tanda tangan yang bekerja dengan URL menggunakan ekstensi ini.
- __ldb__: File tanda tangan logis (ini *akhirnya* akan, untuk versi SigTool masa depan, diekstraksi dari file tanda tangan `.ldb` yang terkandung dalam `daily.cvd` dan `main.cvd`, namun belum didukung oleh SigTool atau phpMussel). File tanda tangan yang bekerja dengan berbagai aturan logis menggunakan ekstensi ini.


---


Terakhir Diperbarui: 22 Juli 2021 (2021.07.22).
