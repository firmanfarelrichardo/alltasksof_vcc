\
# Chat Consultation Specification — Polling MVP

## Purpose

Dokumen ini mengunci mekanisme chat konsultasi sederhana berbasis polling.

Untuk versi awal, jangan gunakan WebSocket.

---

# Main Decision

Gunakan:

```text
Polling HTTP setiap 5 detik
```

Tujuannya:

1. Sederhana.
2. Mudah dibuat dengan PHP native.
3. Mudah diuji.
4. Tidak memerlukan server tambahan.
5. Cukup untuk MVP.

---

# Chat Activation Rule

Chat hanya dapat digunakan jika:

```text
consultations.status = active
```

dan terdapat payment valid:

```text
payments.internal_status = paid
```

---

# Chat Flow

```text
User membuka halaman chat
        |
        v
Frontend request pesan awal
        |
        v
GET /api/user/consultations/{id}/messages
        |
        v
Render pesan
        |
        v
Polling setiap 5 detik
        |
        v
Ambil pesan terbaru
```

Admin menggunakan flow serupa.

---

# Routes

## User Chat

```text
GET  /user/consultations/{consultationId}/chat
GET  /api/user/consultations/{consultationId}/messages
POST /api/user/consultations/{consultationId}/messages
```

## Admin Chat

```text
GET  /admin/consultations/{consultationId}/chat
GET  /api/admin/consultations/{consultationId}/messages
POST /api/admin/consultations/{consultationId}/messages
```

---

# Fetch New Messages

Gunakan parameter:

```text
after_id
```

Contoh:

```text
GET /api/user/consultations/10/messages?after_id=225
```

Backend hanya mengembalikan pesan baru.

---

# JSON Response

## Success

```json
{
  "success": true,
  "data": {
    "messages": [
      {
        "id": 226,
        "sender_id": 8,
        "sender_name": "Admin Network",
        "sender_role": "admin",
        "message": "Silakan kirim topologi jaringan Anda.",
        "created_at": "2026-06-10 10:30:00"
      }
    ],
    "last_message_id": 226
  }
}
```

## Empty Result

```json
{
  "success": true,
  "data": {
    "messages": [],
    "last_message_id": 225
  }
}
```

## Error

```json
{
  "success": false,
  "message": "Anda tidak memiliki akses ke konsultasi ini."
}
```

---

# Send Message Request

```json
{
  "message": "Saya ingin konsultasi desain jaringan kantor."
}
```

---

# Message Validation

1. Message wajib diisi.
2. Trim whitespace.
3. Message tidak boleh hanya spasi.
4. Maksimal 3000 karakter.
5. Simpan sebagai plain text.
6. Escape saat render HTML.
7. Jangan menerima HTML mentah.
8. Jangan menerima script.

---

# Ownership Rules

## User

User hanya boleh mengakses chat jika:

```text
consultations.user_id = current_user.id
```

## Admin

Admin hanya boleh mengakses chat jika:

```text
consultation sub service
→ service category
→ admin_service_assignments
→ current admin
```

## Superadmin

Superadmin boleh melihat riwayat chat jika diperlukan, tetapi tidak perlu membalas kecuali diputuskan kemudian.

---

# Closed Consultation Rule

Untuk MVP:

```text
consultations.status = closed
```

berarti:

1. Chat tetap dapat dibaca.
2. Chat menjadi read-only.
3. User tidak dapat mengirim pesan baru.
4. Admin tidak dapat mengirim pesan baru.

---

# Cancelled Consultation Rule

Jika:

```text
consultations.status = cancelled
```

maka chat tidak dapat digunakan.

---

# Frontend Polling Rule

Gunakan:

```javascript
setInterval(fetchNewMessages, 5000);
```

Jangan polling terlalu cepat.

Minimum:

```text
3 detik
```

Rekomendasi:

```text
5 detik
```

---

# Polling Stop Rule

Hentikan polling jika:

1. User keluar dari halaman chat.
2. Consultation menjadi `closed`.
3. Consultation menjadi `cancelled`.
4. Session habis.
5. Terjadi error auth.

---

# UI Rules

Halaman chat menampilkan:

1. Nama sub layanan.
2. Status konsultasi.
3. Nama lawan bicara.
4. List pesan.
5. Timestamp.
6. Input pesan.
7. Tombol kirim.
8. Locked state jika belum paid.
9. Read-only state jika closed.

---

# Performance Rules

1. Gunakan `after_id`.
2. Gunakan index:
   ```text
   messages(consultation_id, created_at)
   ```
3. Jangan load seluruh riwayat setiap polling.
4. Load riwayat awal dengan limit.
5. Gunakan pagination jika pesan sangat banyak.

---

# Security Rules

1. Validasi auth.
2. Validasi ownership.
3. Validasi consultation status.
4. Escape output.
5. Gunakan CSRF token untuk POST.
6. Gunakan prepared statement.
7. Jangan log isi pesan sensitif kecuali diperlukan.

---

# Final Rule

Gunakan polling sederhana untuk MVP.

Jangan menambahkan WebSocket, Redis, queue, atau real-time server tambahan tanpa instruksi eksplisit.
