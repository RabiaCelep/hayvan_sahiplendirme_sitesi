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

try {
    $stmt = $pdo->prepare("SELECT id FROM pets WHERE id = :id AND user_id = :user_id");
    $stmt->execute([':id' => $pet_id, ':user_id' => $_SESSION['user_id']]);
    $pet = $stmt->fetch();

    if (!$pet) {
        header('Location: index.php');
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM pets WHERE id = :id AND user_id = :user_id");
    $stmt->execute([':id' => $pet_id, ':user_id' => $_SESSION['user_id']]);

    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}



