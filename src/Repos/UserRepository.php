<?php

declare(strict_types=1);

class UserRepository {
    private PDO $pdo;

    public function __construct(Database $db) {
        $this->pdo = $db->getConnection();
    }

    public function create(User $user): int {
        $sql = "INSERT INTO users (name, email, phone, password, role) VALUES (:name, :email, :phone, :password, :role)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':name' => $user->name,
            ':email' => $user->email,
            ':phone' => $user->phone,
            ':password' => $user->password,
            ':role' => $user->role
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(User $user): bool {
        $sql = "UPDATE users SET name = :name, email = :email, phone = :phone, role = :role WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $user->name,
            ':email' => $user->email,
            ':phone' => $user->phone,
            ':role' => $user->role,
            ':id' => $user->id
        ]);
    }

    public function updatePassword(int $id, string $hashedPassword): bool {
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':password' => $hashedPassword, ':id' => $id]);
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function findById(int $id): ?User {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? new User($row) : null;
    }

    public function findByEmail(string $email): ?User {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();
        return $row ? new User($row) : null;
    }

    public function all(int $limit = 100, int $offset = 0): array {
        $sql = "SELECT * FROM users ORDER BY id DESC LIMIT :offset, :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $users = [];
        foreach ($rows as $r) $users[] = new User($r);
        return $users;
    }
}
