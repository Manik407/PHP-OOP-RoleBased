<?php

declare(strict_types=1);

class AuthController {
    private UserRepository $repo;
    private Auth $auth;

    public function __construct(UserRepository $repo, Auth $auth) {
        $this->repo = $repo;
        $this->auth = $auth;
    }

    public function login(array $post): void {
        $email = trim($post['email'] ?? '');
        $password = $post['password'] ?? '';
        if (!$email || !$password) {
            flash_set('error', 'Email and password required');
            return;
        }
        if ($this->auth->attempt($email, $password)) {
            flash_set('success', 'Logged in successfully');
            header('Location: index.php'); exit;
        } else {
            flash_set('error', 'Invalid credentials');
        }
    }

    public function register(array $post): void {
        $name = trim($post['name'] ?? '');
        $email = trim($post['email'] ?? '');
        $phone = trim($post['phone'] ?? '');
        $password = $post['password'] ?? '';
        $role = in_array($post['role'] ?? 'user', ['admin','user']) ? $post['role'] : 'user';

        $errors = [];
        if ($name === '') $errors[] = 'Name required';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';
        if (strlen($password) < 6) $errors[] = 'Password must be at least 6 chars';
        if ($this->repo->findByEmail($email)) $errors[] = 'Email already exists';

        if ($errors) {
            flash_set('error', implode('<br>', $errors));
            return;
        }

        $user = new User([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role
        ]);
        $this->repo->create($user);
        flash_set('success', 'User registered');
    }
}
