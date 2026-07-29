# Branch Strategy

## Tujuan

Dokumen ini menjelaskan aturan penggunaan branch pada repository **SIP Sistem Absensi** agar proses pengembangan lebih terstruktur, konsisten, dan memudahkan kolaborasi antar anggota tim.

---

## Struktur Branch

Repository ini menggunakan branch sebagai berikut:

| Branch | Fungsi |
|---------|--------|
| `main` | Menyimpan versi aplikasi yang sudah stabil dan siap digunakan. |
| `develop` | Branch utama yang digunakan selama proses pengembangan. |
| `feature/*` | Digunakan untuk mengembangkan fitur baru. |
| `hotfix/*` | Digunakan untuk memperbaiki bug yang bersifat mendesak (jika diperlukan). |

---

## Aturan Penggunaan Branch

### Branch `main`

- Digunakan untuk menyimpan versi aplikasi yang stabil.
- Tidak digunakan untuk pengembangan fitur secara langsung.

### Branch `develop`

- Menjadi branch utama selama proses pengembangan.
- Menjadi dasar dalam pembuatan branch `feature/*`.

### Branch `feature/*`

- Digunakan untuk mengembangkan satu fitur tertentu.
- Dibuat dari branch `develop`.
- Setelah fitur selesai, perubahan digabungkan kembali ke branch `develop`.
- Branch dapat dihapus setelah proses penggabungan selesai.

### Branch `hotfix/*`

- Digunakan apabila ditemukan bug yang perlu segera diperbaiki.
- Digunakan sesuai kebutuhan proyek.

---

## Penamaan Branch

Gunakan format berikut:

```
feature/<modul>-<fitur>
```

Contoh:

| Modul | Contoh Branch |
|--------|---------------|
| Backend | `feature/backend-auth` |
| Backend | `feature/backend-attendance` |
| Backend | `feature/backend-report` |
| Mobile | `feature/mobile-login` |
| Mobile | `feature/mobile-attendance` |
| Mobile | `feature/mobile-profile` |
| Web Admin | `feature/web-admin-dashboard` |
| Web Admin | `feature/web-admin-approval` |
| Web Admin | `feature/web-admin-employee` |
| TV Dashboard | `feature/tv-dashboard-monitoring` |
| TV Dashboard | `feature/tv-dashboard-popup` |
| TV Dashboard | `feature/tv-dashboard-summary` |

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

- Setiap developer hanya bekerja pada branch yang menjadi tanggung jawabnya.
- Hindari melakukan pengembangan langsung pada branch `main`.
- Penamaan branch harus mengikuti format yang telah ditentukan agar mudah dikenali oleh seluruh anggota tim.
- Strategi branch ini dapat diperbarui sesuai kebutuhan dan perkembangan proyek.
