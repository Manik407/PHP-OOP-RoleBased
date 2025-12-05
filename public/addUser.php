<?php

require_once __DIR__ . '/../config/config.php';

$db = new Database();
$repo = new UserRepository($db);
$auth = new Auth($repo);
$userController = new UserController($repo, $auth);


if (!$auth->check() || !$auth->isAdmin()) {
    flash_set('error', 'Access denied');
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
        flash_set('error', 'Invalid CSRF token');
    } else {
        $userController->add($_POST);
        header('Location: addUser.php');
        exit;
    }
}

include __DIR__ . '/templates/header.php';
?>
<h2>Add User</h2>

<form method="post" novalidate>
    <?= Csrf::inputField() ?>
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input id="name" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" name="email" type="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input id="phone" name="phone" class="form-control">
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input id="password" name="password" type="password" class="form-control" required>
        <div class="form-text">Password must be at least 6 characters.</div>
    </div>

    <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select id="role" name="role" class="form-control">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
    </div>

    <button class="btn btn-success" type="submit">Create</button>
    <a class="btn btn-secondary" href="index.php">Back</a>
</form>

<?php include __DIR__ . '/templates/footer.php'; ?>
