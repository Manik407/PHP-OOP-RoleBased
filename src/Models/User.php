<?php

declare(strict_types=1);

class User {
    public ?int $id;
    public string $name;
    public string $email;
    public ?string $phone;
    public string $password; 
    public string $role;
    public ?string $created_at;
    public ?string $updated_at;

    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->phone = $data['phone'] ?? null;
        $this->password = $data['password'] ?? '';
        $this->role = $data['role'] ?? 'user';
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }
}
