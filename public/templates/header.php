<?php

require_once __DIR__ . '/../../config/config.php';
$auth = new Auth(new UserRepository(new Database()));
$user = $auth->user();
?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>OOP Role Based App</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">

<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
  <div class="container">
    <a class="navbar-brand" href="index.php">OOP RBAC</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <?php if ($user): ?>
          <li class="nav-item"><a class="nav-link" href="#">Hello, <?=htmlspecialchars($user['name'], ENT_QUOTES)?></a></li>
          <?php if ($user['role'] === 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="register.php">Add User</a></li>
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
<?php include __DIR__ . '/flash.php'; ?>
