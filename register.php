<?php
session_start();
require_once 'config.php';
require_once 'csrf.php';

$errors = [];
$username = '';
$email = '';
$password = '';
$confirm_password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
        die("Geçersiz CSRF token!");
    }

    // Form verilerini al ve temizle
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Validasyon
    if ($username === '') {
        $errors[] = "Kullanıcı adı boş bırakılamaz.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Geçerli bir email adresi giriniz.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Şifre en az 6 karakter olmalıdır.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Şifreler eşleşmiyor.";
    }

    // Kullanıcı adı ve email benzersiz mi?
    if (empty($errors)) {
        // Kullanıcı adı kontrolü
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        if ($stmt->fetch()) {
            $errors[] = "Bu kullanıcı adı zaten alınmış.";
        }

        // Email kontrolü
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            $errors[] = "Bu email adresi zaten kayıtlı.";
        }
    }

    // Kayıt işlemi
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashed_password
        ]);

        // Başarılı kayıt sonrası giriş sayfasına yönlendir
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Kullanıcı Kayıt</h2>
    <a href="login.php" class="btn btn-secondary mb-3">Giriş Yap</a>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="mb-3">
            <label for="username" class="form-label">Kullanıcı Adı</label>
            <input type="text" id="username" name="username" class="form-control" required value="<?= htmlspecialchars($username) ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email Adresi</label>
            <input type="email" id="email" name="email" class="form-control" required value="<?= htmlspecialchars($email) ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Şifre</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Şifre Tekrar</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
        </div>

        <!-- CSRF token -->
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

        <button type="submit" class="btn btn-primary">Kayıt Ol</button>
    </form>
</body>
</html>







