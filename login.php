<?php
session_start();
require_once 'config.php';   // Veritabanı bağlantısı
require_once 'csrf.php';     // CSRF fonksiyonları

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF kontrolü
    if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
        $errors[] = "Geçersiz CSRF token!";
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $errors[] = "Email ve şifre boş bırakılamaz!";
        } else {
            // Kullanıcıyı email ile veritabanından çek
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Giriş başarılı, oturum bilgilerini ayarla
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['username'];
                
                // Ana sayfaya yönlendir
                header("Location: index.php");
                exit;
            } else {
                $errors[] = "Geçersiz email veya şifre.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h2>Giriş Yap</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error) echo "<p>" . htmlspecialchars($error) . "</p>"; ?>
        </div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">

        <div class="mb-3">
            <label for="email" class="form-label">Email adresi</label>
            <input type="email" id="email" class="form-control" name="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Şifre</label>
            <input type="password" id="password" class="form-control" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary">Giriş Yap</button>
    </form>

    <p class="mt-3">Hesabınız yok mu? <a href="register.php">Kayıt olun</a></p>
</body>
</html>






