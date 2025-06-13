<?php
session_start();
require 'config.php';

// Veritabanından ilanları çek
$stmt = $pdo->query("SELECT pets.*, users.username FROM pets JOIN users ON pets.user_id = users.id ORDER BY pets.id DESC");
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Hayvan Sahiplendirme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <?php if (isset($_SESSION['user_id'])): ?>
        <p class="text-end">Merhaba, <?= htmlspecialchars($_SESSION['user_name']) ?>! <a href="logout.php">Çıkış</a></p>

        <a href="add_pet.php" class="btn btn-success mb-3">Yeni İlan Ekle</a>
    <?php else: ?>
        <p><a href="login.php">Giriş Yap</a> veya <a href="register.php">Kayıt Ol</a></p>
    <?php endif; ?>

    <h2>Hayvan Sahiplendirme İlanları</h2>

    <?php if (count($pets) === 0): ?>
        <p>Henüz hiçbir ilan bulunmamaktadır.</p>
    <?php endif; ?>

    <?php foreach ($pets as $pet): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($pet['pet_type']) ?> (<?= htmlspecialchars($pet['age']) ?> yaşında)</h5>
                <?php if (!empty($pet['description'])): ?>
                    <p class="card-text"><?= nl2br(htmlspecialchars($pet['description'])) ?></p>
                <?php endif; ?>
                <p><strong>İletişim:</strong> <?= htmlspecialchars($pet['contact_info']) ?></p>
                <p><small><strong>İlan sahibi:</strong> <?= htmlspecialchars($pet['username']) ?></small></p>

                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $pet['user_id']): ?>
                    <a href="edit_pet.php?id=<?= $pet['id'] ?>" class="btn btn-primary btn-sm">Düzenle</a>
                    <a href="delete_pet.php?id=<?= $pet['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bu ilanı silmek istediğinize emin misiniz?')">Sil</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>

</body>
</html>


