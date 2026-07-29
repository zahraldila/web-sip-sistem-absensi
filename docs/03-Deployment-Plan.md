# Deployment Plan

## Tujuan

Dokumen ini menjelaskan rencana proses deployment pada proyek SIP Sistem Absensi. Deployment akan dilakukan secara bertahap mengikuti perkembangan proyek.

---

## Tahapan Deployment

Proses deployment direncanakan melalui beberapa tahapan sebagai berikut:

1. Development
2. Deployment Manual
3. Implementasi Docker
4. Implementasi CI/CD
5. Production Deployment

---

## Tahap Development

Pada tahap awal, seluruh proses pengembangan dilakukan pada lingkungan lokal (local development) masing-masing anggota tim.

Seluruh fitur dikembangkan pada branch `feature/*` kemudian digabungkan ke branch `develop`.

Belum dilakukan deployment ke server.

---

## Deployment Manual

Setelah aplikasi dapat dijalankan dengan baik, deployment akan dilakukan secara manual.

Proses deployment dapat meliputi:

- Mengambil perubahan terbaru dari repository GitHub.
- Menjalankan aplikasi pada server.
- Memastikan aplikasi berjalan dengan baik setelah proses deployment.

Tahap ini digunakan sebelum proses deployment diotomatisasi menggunakan CI/CD.

---

## Implementasi Docker

Docker direncanakan digunakan untuk mempermudah proses deployment dan memastikan lingkungan aplikasi tetap konsisten.

Konfigurasi Docker akan disesuaikan setelah teknologi yang digunakan pada backend dan frontend telah ditentukan.

---

## Implementasi CI/CD

Setelah proses deployment manual berjalan dengan baik, proyek akan mulai menerapkan CI/CD menggunakan GitHub Actions.

Tujuan implementasi CI/CD antara lain:

- Mengotomatiskan proses build.
- Mengurangi kesalahan saat deployment.
- Mempermudah proses update aplikasi.

---

## Production Deployment

Setelah aplikasi selesai dikembangkan dan lolos proses pengujian, deployment dilakukan ke server production.

Konfigurasi server, domain, dan proses deployment akan disesuaikan dengan infrastruktur yang digunakan oleh PT Selada Indonesia Produktif.

---

## Catatan

Dokumen ini merupakan rencana awal deployment dan dapat diperbarui sesuai kebutuhan proyek maupun perubahan infrastruktur selama proses pengembangan.
