# 🐾 Hayvan Sahiplendirme İlan Sistemi

Bu proje, kullanıcıların evcil hayvan sahiplendirme ilanları ekleyebileceği, düzenleyebileceği ve görüntüleyebileceği basit bir PHP tabanlı web uygulamasıdır.

## 🔗 Proje Adresi

📍 [Canlı Demo](http://95.130.171.20/~st22360859087)  
📺 [Tanıtım Videosu (YouTube)](https://www.youtube.com/watch?v=1S9QAGh4p8M)

---

## 📌 Özellikler

- 👤 Kullanıcı Kayıt ve Giriş Sistemi (PHP Session + CSRF güvenliği)
- 🐶 Hayvan ilanı ekleme, düzenleme ve silme (yalnızca ilan sahibine ait)
- 📄 Tüm ilanların listelendiği ana sayfa
- 🔒 Güvenli şifre saklama (`password_hash`)
- ⚠️ CSRF koruması
- ✨ Bootstrap ile sade ve responsive tasarım

---

## 🗂️ Dosya Yapısı

```
hayvan_sahiplendirme/
│
├── config.php           → Veritabanı bağlantı ayarları
├── csrf.php             → CSRF token üretimi ve doğrulama
├── index.php            → Anasayfa, ilan listeleme
├── login.php            → Giriş sayfası
├── logout.php           → Oturumu kapatma
├── register.php         → Yeni kullanıcı kaydı
├── add_pet.php          → Yeni ilan ekleme
├── edit_pet.php         → İlan düzenleme
├── delete_pet.php       → İlan silme
└── uploads/             → (Varsa) görseller için klasör
```

---

## 🧑‍💻 Kurulum ve Çalıştırma

### 1. Proje Dosyalarının Yüklenmesi

FTP kullanarak `hayvan_sahiplendirme/` klasörünü `public_html/` dizinine yükleyin.  
FTP bilgileri hosting sağlayıcınız tarafından verilmiştir.

### 2. Veritabanı Kurulumu

- Tarayıcıdan phpMyAdmin'e gidin:  
  👉 [http://95.130.171.20/phpmyadmin](http://95.130.171.20/phpmyadmin)

- `dbstorage22360859087` adında bir veritabanı oluşturun (veya bu adla verilmiş olanı kullanın).

- Aşağıdaki SQL komutlarını çalıştırarak tabloları oluşturun:

```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  pet_type VARCHAR(100) NOT NULL,
  age INT NOT NULL,
  description TEXT,
  contact_info VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### 3. config.php Ayarları

`config.php` dosyanız aşağıdaki gibi olmalıdır:

```php
<?php
$host = 'localhost';
$db   = 'dbstorage22360859087';
$user = 'dbusr22360859087';
$pass = 'Kietcnxnfnfc';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
```

---

## 🔐 Güvenlik Özellikleri

- **Şifreleme**: Kullanıcı şifreleri `password_hash()` ile güvenli şekilde saklanır.
- **CSRF Koruması**: Tüm formlarda CSRF token kontrolü yapılır.
- **PDO & Prepared Statements**: SQL enjeksiyonlarına karşı koruma sağlanır.

---

## 🧑‍🎓 Öğrenci Bilgileri

- 👨‍🎓 Öğrenci No: `22360859087`
- 📁 Hosting Dizin: `~st22360859087`
- 🌐 Site Adresi: [http://95.130.171.20/~st22360859087](http://95.130.171.20/~st22360859087)

