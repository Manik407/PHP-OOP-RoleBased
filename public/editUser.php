<?php

require_once __DIR__ . '/../config/config.php';

$db = new Database();
$repo = new UserRepository($db);
$auth = new Auth($repo);
$controller = new UserController($repo, $auth);


if (!$auth->check()) {
    header('Location: login.php'); exit;
}


$id = isset($_GET['id']) ? (int)$_GET['id'] : $auth->id();


if (!$auth->isAdmin() && $auth->id() !== $id) {
    flash_set('error','Access denied');
    header('Location: index.php'); exit;
}


$rawUser = $repo->findById($id);


if (!$rawUser) {
    flash_set('error', 'User not found');
    header('Location: index.php');
    exit;
}


if (is_array($rawUser)) {
    
    $userObj = (object)$rawUser;
} else {
    $userObj = $rawUser;
}


function safe_val($obj, string $prop): string {
    if (is_array($obj)) {
        return isset($obj[$prop]) ? (string)$obj[$prop] : '';
    }
    if (is_object($obj)) {
        return isset($obj->{$prop}) ? (string)$obj->{$prop} : '';
    }
    return '';
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
        flash_set('error','Invalid CSRF token');
    } else {
        $controller->edit($id, $_POST);
        
        header('Location: editUser.php?id=' . $id);
        exit;
    }
}

include __DIR__ . '/templates/header.php';
?>
<h2>Edit User</h2>
<form method="post">
    <?= Csrf::inputField() ?>
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input id="name" name="name" class="form-control" required
               value="<?= htmlspecialchars(safe_val($userObj, 'name'), ENT_QUOTES) ?>">
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" name="email" type="email" class="form-control" required
               value="<?= htmlspecialchars(safe_val($userObj, 'email'), ENT_QUOTES) ?>">
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input id="phone" name="phone" class="form-control"
               value="<?= htmlspecialchars(safe_val($userObj, 'phone'), ENT_QUOTES) ?>">
    </div>

    <?php if ($auth->isAdmin()): 
        $currentRole = safe_val($userObj, 'role') ?: 'user';
    ?>
    <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select id="role" name="role" class="form-control">
            <option value="user" <?= $currentRole === 'user' ? 'selected' : '' ?>>User</option>
            <option value="admin" <?= $currentRole === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
    </div>
    <?php endif; ?>

    <div class="mb-3">
        <label for="password" class="form-label">Change Password (leave blank to keep)</label>
        <input id="password" name="password" type="password" class="form-control" autocomplete="new-password">
        <div class="form-text">Enter at least 6 characters to change password.</div>
    </div>

    <button class="btn btn-primary" type="submit">Save</button>
    <a class="btn btn-secondary" href="index.php">Back</a>
</form>

<?php include __DIR__ . '/templates/footer.php'; ?>
