# Branch Strategy

## Tujuan

Dokumen ini menjelaskan strategi penggunaan branch pada repository **SIP Sistem Absensi** agar proses pengembangan lebih terstruktur, meminimalkan konflik, dan memudahkan kolaborasi antar anggota tim.

---

## Struktur Branch

Repository ini menggunakan beberapa jenis branch sebagai berikut:

| Branch | Fungsi |
|---------|--------|
| `main` | Menyimpan versi aplikasi yang stabil dan siap digunakan. |
| `develop` | Branch utama yang digunakan selama proses pengembangan. |
| `feature/*` | Digunakan untuk mengembangkan fitur baru. |
| `hotfix/*` | Digunakan untuk memperbaiki bug yang bersifat mendesak (jika diperlukan). |

---

## Aturan Penggunaan Branch

### 1. Branch `main`

- Digunakan untuk menyimpan versi aplikasi yang sudah stabil.
- Tidak digunakan untuk pengembangan fitur baru.
- Perubahan pada branch ini dilakukan setelah fitur pada branch `develop` dinyatakan siap.

---

### 2. Branch `develop`

- Menjadi branch utama selama proses pengembangan.
- Seluruh fitur baru akan dikembangkan dari branch ini.
- Branch ini akan terus diperbarui selama proses development berlangsung.

---

### 3. Branch `feature/*`

Digunakan untuk mengembangkan satu fitur tertentu.

Format penamaan:

```
feature/nama-fitur
```

Contoh:

```
feature/backend-auth
feature/mobile-attendance
feature/web-admin-approval
feature/tv-dashboard-popup
```

Setelah fitur selesai dikembangkan, perubahan akan digabungkan kembali ke branch `develop`.

---

### 4. Branch `hotfix/*`

Digunakan apabila ditemukan bug yang perlu segera diperbaiki.

Contoh:

```
hotfix/login-error
hotfix/nfc-validation
```

Branch ini hanya digunakan jika diperlukan.

---

## Diagram Branch

```
main
│
└── develop
      │
      ├── feature/backend-auth
      ├── feature/mobile-attendance
      ├── feature/web-admin-approval
      └── feature/tv-dashboard-popup
```

---

## Catatan

Strategi branch ini dapat disesuaikan dengan kebutuhan proyek seiring perkembangan proses pengembangan.
