<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class UserRepository
{
    public function findByUsername(string $username): ?array
    {
        $sql = 'SELECT id, username, password_hash, full_name FROM users WHERE username = :username LIMIT 1';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        return $user ?: null;
    }
}
