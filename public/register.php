<?php

require_once __DIR__ . '/../config/config.php';

$db = new Database();
$repo = new UserRepository($db);
$auth = new Auth($repo);
$authController = new AuthController($repo, $auth);


$existingUsers = $repo->all(1, 0); 
$allowPublic = (count($existingUsers) === 0);


if (!$allowPublic && (!$auth->check() || !$auth->isAdmin())) {
    flash_set('error','Access denied');
    header('Location: index.php'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
        flash_set('error','Invalid CSRF token');
    } else {
       
        if ($allowPublic) {
            $_POST['role'] = 'admin';
        } else {
            
            if (!in_array($_POST['role'] ?? 'user', ['admin','user'])) {
                $_POST['role'] = 'user';
            }
        }
        $authController->register($_POST);
        
        header('Location: register.php'); exit;
    }
}

include __DIR__ . '/templates/header.php';
?>
<h2><?= $allowPublic ? 'Create First Admin Account' : 'Add User (Admin Only)' ?></h2>
<form method="post">
    <?= Csrf::inputField() ?>
    <div class="mb-3"><label>Name</label><input class="form-control" name="name" required></div>
    <div class="mb-3"><label>Email</label><input class="form-control" name="email" type="email" required></div>
    <div class="mb-3"><label>Phone</label><input class="form-control" name="phone"></div>
    <div class="mb-3"><label>Password</label><input class="form-control" name="password" type="password" required></div>

    <?php if (!$allowPublic): ?>
    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
    </div>
    <?php endif; ?>

    <button class="btn btn-success"><?= $allowPublic ? 'Create Admin' : 'Create User' ?></button>
</form>
<?php include __DIR__ . '/templates/footer.php'; ?>
