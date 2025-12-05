<?php

declare(strict_types=1);

class UserController {
    private UserRepository $repo;
    private Auth $auth;

    public function __construct(UserRepository $repo, Auth $auth) {
        $this->repo = $repo;
        $this->auth = $auth;
    }

    public function list(): array {
        return $this->repo->all(100,0);
    }

    public function add(array $post): void {
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
        flash_set('success', 'User created');
    }

    public function edit(int $id, array $post): void {
        $user = $this->repo->findById($id);
        if (!$user) { flash_set('error','User not found'); return; }

        $user->name = trim($post['name'] ?? $user->name);
        $user->email = trim($post['email'] ?? $user->email);
        $user->phone = trim($post['phone'] ?? $user->phone);
        $user->role = in_array($post['role'] ?? $user->role, ['admin','user']) ? $post['role'] : $user->role;

        
        $existing = $this->repo->findByEmail($user->email);
        if ($existing && $existing->id !== $user->id) {
            flash_set('error','Email already in use by another user');
            return;
        }

        $this->repo->update($user);
        if (!empty($post['password'])) {
            if (strlen($post['password']) >= 6) {
                $this->repo->updatePassword($user->id, password_hash($post['password'], PASSWORD_DEFAULT));
            } else {
                flash_set('error','Password must be at least 6 chars');
                return;
            }
        }
        flash_set('success','User updated');
    }

    public function delete(int $id): void {
        if ($this->auth->id() === $id) {
            flash_set('error', 'You cannot delete yourself');
            return;
        }
        $this->repo->delete($id);
        flash_set('success', 'User deleted');
    }
}
