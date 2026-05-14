# Panduan Login Multi-Field - PRO-MENTOR

## Fitur Login yang Tersedia

Sistem PRO-MENTOR kini mendukung **3 metode login** yang fleksibel:

### 1. Login dengan Email
- **Format**: `user@example.com`
- **Berlaku untuk**: Semua role (Admin, Dosen, Mahasiswa)
- **Contoh**: `john.doe@unm.ac.id`

### 2. Login dengan NIM (Nomor Induk Mahasiswa)
- **Format**: Angka (bukan 10 digit)
- **Berlaku untuk**: Mahasiswa
- **Contoh**: `1234567890123` atau `123456789`

### 3. Login dengan NIDN (Nomor Induk Dosen Nasional)
- **Format**: Angka 10 digit
- **Berlaku untuk**: Dosen
- **Contoh**: `0123456789`

## Cara Kerja Sistem

Sistem secara **otomatis mendeteksi** jenis input yang Anda masukkan:

```
Input mengandung "@"          → Login dengan EMAIL
Input angka 10 digit          → Login dengan NIDN (Dosen)
Input angka selain 10 digit   → Login dengan NIM (Mahasiswa)
```

## Contoh Penggunaan

### Mahasiswa Login
```
Username: 1234567890123
Password: ********
✓ Sistem mendeteksi sebagai NIM
```

### Dosen Login
```
Username: 0123456789
Password: ********
✓ Sistem mendeteksi sebagai NIDN (10 digit)
```

### Login dengan Email (Semua Role)
```
Username: admin@unm.ac.id
Password: ********
✓ Sistem mendeteksi sebagai EMAIL
```

## Keamanan

- Semua metode login menggunakan enkripsi password yang sama
- Rate limiting tetap aktif (maksimal 5 percobaan login)
- Session management tetap aman dengan remember token

## Troubleshooting

### Tidak bisa login dengan NIM/NIDN?
1. Pastikan NIM/NIDN sudah terdaftar di database
2. Pastikan kolom `nim` atau `nidn` di tabel `users` sudah terisi
3. Periksa apakah password yang dimasukkan benar

### NIDN terdeteksi sebagai NIM?
- Pastikan NIDN Anda tepat **10 digit**
- Jika kurang dari 10 digit, tambahkan angka 0 di depan

### Masih ingin login dengan email?
- Anda tetap bisa login dengan email seperti biasa
- Sistem akan otomatis mendeteksi format email (mengandung @)

## File yang Dimodifikasi

1. **app/Http/Requests/Auth/LoginRequest.php**
   - Menambahkan method `getLoginField()` untuk deteksi otomatis
   - Mengubah validasi dan logika autentikasi

2. **resources/views/auth/login.blade.php**
   - Mengubah label dan placeholder input

3. **changelog.md**
   - Dokumentasi perubahan fitur

## Catatan Teknis

### Database Requirements
Pastikan tabel `users` memiliki kolom:
- `email` (string, unique)
- `nim` (string, nullable, unique)
- `nidn` (string, nullable, unique)
- `password` (hashed)

### Logika Deteksi (LoginRequest.php)
```php
protected function getLoginField(): string
{
    $login = $this->input('email');
    
    // Email detection
    if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
        return 'email';
    }
    
    // NIDN detection (10 digits)
    if (is_numeric($login) && strlen($login) == 10) {
        return 'nidn';
    }
    
    // Default to NIM
    return 'nim';
}
```

## Update Selanjutnya

Fitur yang bisa ditambahkan di masa depan:
- [ ] Validasi format NIM berdasarkan aturan kampus
- [ ] Validasi format NIDN sesuai standar Dikti
- [ ] Login dengan username custom
- [ ] Two-factor authentication (2FA)
