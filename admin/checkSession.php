<?php

require_once '../api/Database.php';

session_start();

function CheckSession(): bool
{
    if (!isset($_SESSION['sid'])) {
        //echo json_encode(['authenticated' => false]);
        //exit;
        return false;
    }

    $pdo = (new Database())->connect();
    $stmt = $pdo->prepare("SELECT * FROM login WHERE sid = ?");
    $stmt->execute([$_SESSION['sid']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        //echo json_encode(['authenticated' => true, 'login' => $user['login']]);
        return true;
    } else {
        //echo json_encode(['authenticated' => false]);
        return false;
    }
}

