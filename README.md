# Hayvan Sahiplendirme İlan Sistemi 🐾

Bu proje, kullanıcıların evcil hayvan sahiplendirme ilanları ekleyebileceği, düzenleyebileceği ve görüntüleyebileceği basit bir PHP tabanlı web uygulamasıdır.

## 🔗 Proje Adresi

📍 [Canlı Demo](http://95.130.171.20/~st22360859087)

---

## 📌 Özellikler

- 👤 Kullanıcı Kayıt ve Giriş Sistemi (PHP Session + CSRF güvenliği)
- 🐶 Hayvan ilanı ekleme, düzenleme ve silme (sadece ilan sahibine ait)
- 📄 Tüm ilanların listelendiği ana sayfa
- 🔒 Güvenli şifre saklama (Password Hashing)
- ⚠️ CSRF koruması
- ✨ Bootstrap ile sade ve responsive tasarım

---

## 🗂️ Dosya Yapısı

hayvan_sahiplendirme/
│
├── config.php → Veritabanı bağlantı ayarları
├── csrf.php → CSRF token üretimi ve doğrulama
├── index.php → Anasayfa, ilan listeleme
├── login.php → Giriş sayfası
├── logout.php → Oturumu kapatma
├── register.php → Yeni kullanıcı kaydı
├── add_pet.php → Yeni ilan ekleme
├── edit_pet.php → İlan düzenleme
├── delete_pet.php → İlan silme


---

## 🧑‍💻 Kurulum ve Çalıştırma

1. **Proje Dosyaları**: FTP ile `public_html/` dizinine `hayvan_sahiplendirme/` klasörünü yükleyin.
2. **Veritabanı Kurulumu**:
   - phpMyAdmin'e gidin: [http://95.130.171.20/phpmyadmin](http://95.130.171.20/phpmyadmin)
   - `dbstorage22360859087` adında bir veritabanı kullanın.
   - Aşağıdaki SQL komutlarıyla tabloları oluşturun:

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
🔐 Güvenlik Özellikleri
Şifreleme: Kullanıcı şifreleri password_hash() ile güvenli şekilde saklanır.

CSRF Koruması: Tüm formlarda CSRF token kontrolü yapılır.

PDO & Prepared Statements: SQL enjeksiyonlarına karşı koruma sağlanır.

🧑‍🎓 Öğrenci Bilgileri
👨‍🎓 Öğrenci No: 22360859087

📁 Hosting Dizin: ~st22360859087

🌐 Site: http://95.130.171.20/~st22360859087

Youtube linki: https://www.youtube.com/watch?v=1S9QAGh4p8M
