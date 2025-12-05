<?php

declare(strict_types=1);

class Csrf {
    public static function generate(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    public static function validate(?string $token): bool {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
    }
    public static function inputField(): string {
        return '<input type="hidden" name="csrf_token" value="'.htmlspecialchars(self::generate(), ENT_QUOTES).'">';
    }
}
