<?php

require_once __DIR__ . '/../config/config.php';
$db = new Database();
$repo = new UserRepository($db);
$auth = new Auth($repo);
$userController = new UserController($repo, $auth);


if (!$auth->check()) {
    header('Location: login.php'); exit;
}

$users = $userController->list();

include __DIR__ . '/templates/header.php';
?>
<h2>Manage Users</h2>

<?php if ($auth->isAdmin()): ?>
    <a href="register.php" class="btn btn-success mb-2">Add User</a>
<?php endif; ?>

<table class="table table-striped">
<thead>
<tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Actions</th></tr>
</thead>
<tbody>
<?php foreach ($users as $i => $u): ?>
<tr>
    <td><?= $i+1 ?></td>
    <td><?= htmlspecialchars($u->name, ENT_QUOTES) ?></td>
    <td><?= htmlspecialchars($u->email, ENT_QUOTES) ?></td>
    <td><?= htmlspecialchars($u->phone ?? '-', ENT_QUOTES) ?></td>
    <td><?= htmlspecialchars($u->role, ENT_QUOTES) ?></td>
    <td>
        <?php if ($auth->isAdmin() || $auth->id() === $u->id): ?>
            <a href="editUser.php?id=<?= $u->id ?>" class="btn btn-primary btn-sm">Edit</a>
        <?php endif; ?>
        <?php if ($auth->isAdmin() && $auth->id() !== $u->id): ?>
            <form method="post" action="deleteUser.php" style="display:inline-block" onsubmit="return confirm('Delete user?')">
                <?= Csrf::inputField() ?>
                <input type="hidden" name="id" value="<?= $u->id ?>">
                <button class="btn btn-danger btn-sm">Delete</button>
            </form>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php include __DIR__ . '/templates/footer.php'; ?>
