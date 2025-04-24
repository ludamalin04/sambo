<?php

require_once '../api/Database.php';

header('Content-Type: application/json');
session_start();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['login'], $data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Missing credentials']);
    exit;
}

$pdo = (new Database())->connect();
$stmt = $pdo->prepare("SELECT * FROM login WHERE login = ?");
$stmt->execute([$data['login']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($data['password'], $user['password'])) {
    // Генеруємо новий SID
    $sid = bin2hex(random_bytes(32));
    $_SESSION['sid'] = $sid;

    // Зберігаємо SID в БД
    $update = $pdo->prepare("UPDATE login SET sid = ? WHERE id_login = ?");
    $update->execute([$sid, $user['id_login']]);

    echo json_encode(['success' => true, 'message' => 'Login successful']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
}
