# Git Workflow

## Tujuan

Dokumen ini menjelaskan alur penggunaan Git yang digunakan pada proyek SIP Sistem Absensi agar proses pengembangan berjalan lebih teratur dan memudahkan kolaborasi antar anggota tim.

---

## Branch yang Digunakan

Repository ini menggunakan branch berikut:

| Branch | Fungsi |
|---------|--------|
| `main` | Menyimpan versi aplikasi yang stabil dan siap digunakan. |
| `develop` | Branch utama untuk proses pengembangan. |
| `feature/*` | Digunakan untuk mengembangkan fitur baru. |
| `hotfix/*` | Digunakan untuk memperbaiki bug yang bersifat mendesak (jika diperlukan). |

---

## Alur Pengembangan

Alur pengembangan yang digunakan pada proyek ini adalah sebagai berikut:

```
main
│
└── develop
      │
      ├── feature/backend-auth
      ├── feature/mobile-login
      ├── feature/web-admin-approval
      └── feature/tv-dashboard-popup
```

Setiap pengembangan fitur dilakukan pada branch `feature/*` yang dibuat dari branch `develop`.

---

## Langkah Pengembangan

1. Pastikan branch `develop` sudah terbaru.
2. Buat branch baru sesuai fitur yang akan dikerjakan.
3. Kerjakan fitur pada branch tersebut.
4. Commit perubahan secara berkala dengan pesan yang jelas.
5. Push branch ke repository GitHub.
6. Gabungkan perubahan ke branch `develop` sesuai mekanisme yang disepakati oleh tim.

---

## Catatan

Pada tahap awal proyek, proses deployment masih dilakukan secara manual. Penggunaan Pull Request, Branch Protection, dan CI/CD akan diterapkan sesuai kebutuhan pada tahap pengembangan berikutnya.
