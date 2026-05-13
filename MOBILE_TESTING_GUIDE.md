# 📱 Panduan Testing Tampilan Mobile PRO-MENTOR

## 🖥️ Cara 1: Testing di Browser Laptop (Paling Mudah)

### Google Chrome / Microsoft Edge
1. Buka website: `http://localhost:8000` atau `http://127.0.0.1:8000`
2. Tekan **F12** atau **Ctrl + Shift + I** untuk membuka DevTools
3. Tekan **Ctrl + Shift + M** atau klik icon 📱 (Toggle device toolbar)
4. Pilih device dari dropdown:
   - **iPhone 12 Pro** (390 x 844)
   - **iPhone SE** (375 x 667)
   - **Samsung Galaxy S20** (360 x 800)
   - **iPad** (768 x 1024)
   - **Responsive** (custom size)
5. Refresh halaman (**Ctrl + R**) untuk melihat tampilan mobile
6. Test dengan scroll, klik tombol, dan navigasi

### Mozilla Firefox
1. Buka website
2. Tekan **F12** untuk membuka DevTools
3. Tekan **Ctrl + Shift + M** untuk Responsive Design Mode
4. Pilih device atau atur custom size
5. Refresh dan test

### Safari (Mac)
1. Buka website
2. Tekan **Cmd + Option + I** untuk Web Inspector
3. Klik icon 📱 atau pilih **Develop > Enter Responsive Design Mode**
4. Pilih device dan test

---

## 📱 Cara 2: Testing di HP Langsung (Lebih Akurat)

### Syarat:
- Laptop dan HP harus dalam **satu jaringan WiFi yang sama**

### Langkah-langkah:

#### 1. Cek IP Address Laptop
**Windows:**
```bash
ipconfig
```
Cari bagian **IPv4 Address**, contoh: `192.168.1.100`

**Mac/Linux:**
```bash
ifconfig
```
atau
```bash
ip addr show
```

#### 2. Jalankan Laravel Server
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

#### 3. Akses dari HP
Buka browser di HP (Chrome/Safari), ketik:
```
http://[IP-LAPTOP]:8000
```
Contoh:
```
http://192.168.1.100:8000
```

#### 4. Login dan Test
- Login sebagai admin, dosen, atau mahasiswa
- Test semua fitur
- Cek responsiveness saat rotate HP (portrait/landscape)

---

## 🎯 Halaman yang Sudah Dioptimasi untuk Mobile

### ✅ Admin
- [x] Dashboard
- [x] Data Pendaftar (List)
- [x] Data Pendaftar (Detail)
- [x] Kelola User
- [x] Pasangan Mentor
- [x] Feedback
- [x] Laporan

### ✅ Dosen
- [x] Beranda
- [x] Daftar Pendaftar
- [x] Seleksi & Penilaian
- [x] Notifikasi

### ✅ Mahasiswa
- [x] Beranda
- [x] Form Pendaftaran
- [x] Status Seleksi
- [x] Self Assessment
- [x] Feedback

---

## 📐 Breakpoints yang Digunakan

```css
/* Mobile */
@media(max-width: 768px) {
    /* Styles untuk HP */
}

/* Tablet */
@media(min-width: 769px) and (max-width: 1024px) {
    /* Styles untuk tablet */
}

/* Desktop */
@media(min-width: 1025px) {
    /* Styles untuk desktop */
}
```

---

## 🔍 Checklist Testing Mobile

### Layout & Navigation
- [ ] Sidebar/menu bisa dibuka dan ditutup dengan smooth
- [ ] Hamburger menu berfungsi
- [ ] Navigation links mudah diklik (tidak terlalu kecil)
- [ ] Scroll smooth tanpa horizontal scroll

### Form & Input
- [ ] Input field cukup besar untuk diketik
- [ ] Dropdown mudah dipilih
- [ ] Button cukup besar untuk diklik (min 44x44px)
- [ ] File upload berfungsi
- [ ] Validation error terlihat jelas

### Table & Data
- [ ] Table bisa di-scroll horizontal jika perlu
- [ ] Data tetap terbaca dengan jelas
- [ ] Action buttons mudah diklik

### Modal & Popup
- [ ] Modal muncul dengan benar
- [ ] PDF preview berfungsi
- [ ] Modal bisa ditutup dengan mudah
- [ ] Tidak ada element yang terpotong

### Performance
- [ ] Loading cepat
- [ ] Tidak ada lag saat scroll
- [ ] Image/PDF loading dengan baik

---

## 🐛 Troubleshooting

### Masalah: Tidak bisa akses dari HP
**Solusi:**
1. Pastikan firewall Windows tidak memblokir port 8000
2. Jalankan sebagai administrator:
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```
3. Cek apakah laptop dan HP di WiFi yang sama

### Masalah: Tampilan tidak responsive
**Solusi:**
1. Clear cache browser: **Ctrl + Shift + Delete**
2. Hard refresh: **Ctrl + Shift + R**
3. Pastikan viewport meta tag ada:
   ```html
   <meta name="viewport" content="width=device-width, initial-scale=1">
   ```

### Masalah: Element terlalu kecil di mobile
**Solusi:**
- Minimum touch target: **44x44px**
- Font size minimum: **14px** untuk body text
- Button padding minimum: **12px 16px**

---

## 📊 Device Testing Priority

### High Priority (Test Wajib)
1. **iPhone 12 Pro** (390x844) - iOS Safari
2. **Samsung Galaxy S20** (360x800) - Android Chrome
3. **iPhone SE** (375x667) - Small screen iOS

### Medium Priority
4. **iPad** (768x1024) - Tablet view
5. **Samsung Galaxy Tab** (800x1280)

### Low Priority
6. **Desktop** (1920x1080) - Already optimized
7. **Large Desktop** (2560x1440)

---

## 🎨 Mobile UI Improvements Applied

### 1. **Stats Cards**
- Grid: 5 columns → 2 columns on mobile
- Last card spans 2 columns for balance

### 2. **Forms**
- 2-column grid → 1 column on mobile
- Larger input fields
- Full-width buttons

### 3. **Tables**
- Horizontal scroll enabled
- Sticky header
- Compact padding

### 4. **File Cards**
- Flex-wrap enabled
- Buttons stack vertically
- Larger touch targets

### 5. **Modals**
- Full screen on mobile
- Easier to close
- Better PDF viewing

### 6. **Navigation**
- Hamburger menu
- Overlay sidebar
- Touch-friendly links

---

## 💡 Tips Testing

1. **Test dengan jari, bukan mouse** - Simulasi real user
2. **Test di berbagai orientasi** - Portrait & Landscape
3. **Test dengan koneksi lambat** - Throttle network di DevTools
4. **Test dengan berbagai ukuran font** - Accessibility
5. **Test dengan zoom** - 100%, 125%, 150%

---

## 📝 Notes

- Semua halaman sudah menggunakan responsive design
- Admin layout sudah include mobile optimization
- PDF preview sudah responsive
- Form validation tetap berfungsi di mobile
- File upload sudah mobile-friendly

---

**Last Updated:** 14 Mei 2026
**Version:** 1.0
**Developer:** PRO-MENTOR Team
