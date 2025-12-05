<?php

declare(strict_types=1);

class Auth
{
    private UserRepository $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    
    public function attempt(string $email, string $password): bool
    {
        $email = trim($email);
        if ($email === '' || $password === '') {
            return false;
        }

        $user = $this->repo->findByEmail($email);
        if (!$user) {
            return false;
        }

       
        if (password_verify($password, $user->password)) {
          
            session_regenerate_id(true);

            
            $_SESSION['user'] = [
                'id'    => (int)$user->id,
                'email' => $user->email,
                'name'  => $user->name,
                'role'  => $user->role,
            ];

            return true;
        }

        return false;
    }

    
    public function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

   
    public function id(): ?int
    {
        return isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;
    }

   
    public function check(): bool
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
    }

   
    public function isAdmin(): bool
    {
        return $this->check() && (($_SESSION['user']['role'] ?? '') === 'admin');
    }

   
    public function logout(): void
    {
        unset($_SESSION['user']);
      
        session_regenerate_id(true);
    }

    
    public function requireAuth(string $redirect = 'login.php'): void
    {
        if (!$this->check()) {
            flash_set('error', 'Please login to continue.');
            header('Location: ' . $redirect);
            exit;
        }
    }

   
    public function requireAdmin(string $redirect = 'index.php'): void
    {
        if (!$this->check() || !$this->isAdmin()) {
            flash_set('error', 'Access denied.');
            header('Location: ' . $redirect);
            exit;
        }
    }

   
    public function userModel(): ?User
    {
        $id = $this->id();
        if ($id === null) return null;
        return $this->repo->findById($id);
    }
}
