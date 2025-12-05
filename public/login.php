<?php

require_once __DIR__ . '/../config/config.php';
$db = new Database();
$repo = new UserRepository($db);
$auth = new Auth($repo);
$controller = new AuthController($repo, $auth);

if ($auth->check()) {
    header('Location: index.php'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
        flash_set('error','Invalid CSRF token');
    } else {
        $controller->login($_POST);
        if ($auth->check()) { header('Location: index.php'); exit; }
    }
}
include __DIR__ . '/templates/header.php';
?>
<h2>Login</h2>
<form method="post" class="mb-3">
    <?= Csrf::inputField() ?>
    <div class="mb-3"><label>Email</label><input class="form-control" type="email" name="email" required></div>
    <div class="mb-3"><label>Password</label><input class="form-control" type="password" name="password" required></div>
    <button class="btn btn-primary">Login</button>
</form>
<?php include __DIR__ . '/templates/footer.php'; ?>
