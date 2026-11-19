# Manajemen Keanggotaan

Modul Keanggotaan digunakan oleh Anggota dan Pengurus Cabang untuk mengelola data identitas, status keanggotaan, kartu tanda anggota, permohonan mutasi, serta riwayat iuran kas.

---

## 1. Pendaftaran & Data Anggota Baru

* Calon anggota dapat didaftarkan oleh admin cabang melalui menu **Anggota** $\rightarrow$ **Tambah Anggota Baru**.
* Data yang perlu dilengkapi meliputi:
  * Biodata pribadi (Nama lengkap, NIK, tempat/tanggal lahir, jenis kelamin).
  * Kontak & alamat domisili.
  * Cabang asal dan tingkatan sabuk awal.
  * Foto profil resmi.

---

## 2. Kartu Tanda Anggota (KTA) & Sertifikat

* Setelah data diverifikasi, setiap anggota akan memiliki Nomor Tanda Anggota (NTA) unik.
* KTA digital dapat dilihat dan diunduh melalui menu **ID & Sertifikat**.
* KTA dapat dicetak sebagai bukti keanggotaan resmi perguruan Cipta Sejati.

---

## 3. Alur Pengajuan Mutasi Anggota

Apabila seorang anggota berpindah tempat tinggal atau ingin melanjutkan latihan di cabang lain, anggota/pengurus dapat mengajukan mutasi:

```
[ Anggota / Admin Cabang Asal Mengajukan Mutasi ]
                       │
                       ▼
         [ Status: Menunggu Persetujuan ]
                       │
                       ▼
       [ Verifikasi Koordinator Cabang Tujuan ]
          ├── Disetujui  ──> Anggota resmi berpindah ke cabang tujuan
          └── Ditolak    ──> Anggota tetap tercatat di cabang asal
```

1. Buka menu **Mutasi Anggota**, klik **Ajukan Mutasi**.
2. Pilih cabang baru yang dituju dan tuliskan keterangan/alasan kepindahan.
3. Koordinator cabang tujuan akan memeriksa pengajuan melalui menu **Persetujuan Mutasi**.
4. Setelah disetujui, data anggota otomatis berpindah ke daftar cabang baru.

---

## 4. Kas & Iuran Anggota (Sidebar)

Menu **Kas Anggota** pada sidebar navigasi digunakan untuk mengelola pencatatan keuangan internal cabang dan anggota perguruan:

* **Pencatatan Iuran:** Digunakan oleh bendahara cabang untuk mencatat setoran kas wajib bulanan, uang latihan, maupun kas sukarela.
* **Status Setoran:** Anggota dapat memantau riwayat setoran yang telah dibayarkan maupun daftar tagihan kas internal yang belum lunas.
* **Laporan Kas:** Koordinator atau bendahara cabang dapat mengekspor rekapitulasi kas ke dalam format Excel atau PDF melalui menu **Laporan Kas Anggota**.

> [!NOTE]
> Menu **Kas Anggota** di sidebar ini murni untuk pembukuan internal organisasi. Hal ini berbeda dengan menu **Pembayaran Sewa** pada profil navbar yang berfungsi khusus untuk membayar biaya langganan/sewa website Cipta Sejati.
