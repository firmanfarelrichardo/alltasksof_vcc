\
# Current Progress Update — Admin Consultation Pipeline

## Added Requirement

Dashboard admin harus menampilkan perjalanan pengguna sejak registrasi hingga konsultasi selesai.

## Added Pipeline Stages

```text
registered
payment_pending
payment_cancelled
payment_success
consultation_active
consultation_closed
```

## Added Admin Tabs

```text
Semua
Terdaftar
Menunggu Pembayaran
Pembayaran Dibatalkan
Pembayaran Berhasil
Konsultasi Aktif
Riwayat Selesai
```

## Added Backend Components

```text
AdminPipelineController.php
AdminPipelineService.php
AdminPipelineRepository.php
```

## Added Route

```text
GET /admin/pipeline
```

## Important Rules

1. Registered user list bersifat read-only pada admin.
2. Approval tetap hanya dilakukan superadmin.
3. Payment dan consultation tetap dibatasi berdasarkan layanan admin.
4. Raw status Midtrans disimpan terpisah dari internal status.
5. Label UI `success` berasal dari internal status `paid`.
6. Label UI `cancel` berasal dari raw Midtrans status `cancel`.
7. Chat hanya dibuka setelah payment valid dan consultation aktif.
