<?php

require_once __DIR__ . '/../config/config.php';
$db = new Database();
$repo = new UserRepository($db);
$auth = new Auth($repo);
$controller = new UserController($repo, $auth);

if (!$auth->check() || !$auth->isAdmin()) {
    flash_set('error','Access denied');
    header('Location: index.php'); exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php'); exit;
}

if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
    flash_set('error','Invalid CSRF token');
    header('Location: index.php'); exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$controller->delete($id);
header('Location: index.php'); exit;
