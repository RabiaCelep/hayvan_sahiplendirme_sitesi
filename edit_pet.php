<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pet_id = $_GET['id'] ?? null;
if (!$pet_id || !ctype_digit($pet_id)) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM pets WHERE id = :id AND user_id = :user_id");
$stmt->execute([':id' => $pet_id, ':user_id' => $_SESSION['user_id']]);
$pet = $stmt->fetch();

if (!$pet) {
    header('Location: index.php');
    exit;
}

$errors = [];
$tur = $pet['pet_type'];
$yas = $pet['age'];
$aciklama = $pet['description'];
$iletisim = $pet['contact_info'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tur = trim($_POST['tur'] ?? '');
    $yas = trim($_POST['yas'] ?? '');
    $aciklama = trim($_POST['aciklama'] ?? '');
    $iletisim = trim($_POST['iletisim'] ?? '');

    if ($tur === '') {
        $errors[] = "Tür bilgisi boş bırakılamaz.";
    }
    if ($yas === '' || !ctype_digit($yas) || (int)$yas < 0) {
        $errors[] = "Yaş pozitif bir tam sayı olmalıdır.";
    }
    if ($iletisim === '') {
        $errors[] = "İletişim bilgisi boş bırakılamaz.";
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE pets SET pet_type = :pet_type, age = :age, description = :description, contact_info = :contact_info WHERE id = :id AND user_id = :user_id");
            $stmt->execute([
                ':pet_type' => $tur,
                ':age' => (int)$yas,
                ':description' => $aciklama,
                ':contact_info' => $iletisim,
                ':id' => $pet_id,
                ':user_id' => $_SESSION['user_id'],
            ]);
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $errors[] = "Veritabanı hatası: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>İlan Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="container mt-5">
    <h2>İlan Düzenle</h2>
    <a href="index.php" class="btn btn-secondary mb-3">Geri Dön</a>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="mb-3">
            <label for="tur" class="form-label">Tür</label>
            <input type="text" id="tur" name="tur" class="form-control" value="<?= htmlspecialchars($tur) ?>" required />
        </div>
        <div class="mb-3">
            <label for="yas" class="form-label">Yaş</label>
            <input type="number" id="yas" name="yas" min="0" class="form-control" value="<?= htmlspecialchars($yas) ?>" required />
        </div>
        <div class="mb-3">
            <label for="aciklama" class="form-label">Açıklama</label>
            <textarea id="aciklama" name="aciklama" class="form-control"><?= htmlspecialchars($aciklama) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="iletisim" class="form-label">İletişim Bilgisi</label>
            <input type="text" id="iletisim" name="iletisim" class="form-control" value="<?= htmlspecialchars($iletisim) ?>" required />
        </div>
        <button type="submit" class="btn btn-primary">Güncelle</button>
    </form>
</body>
</html>


