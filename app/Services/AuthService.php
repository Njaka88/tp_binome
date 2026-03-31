<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;

final class AuthService
{
    private UserRepository $users;

    public function __construct()
    {
        $this->users = new UserRepository();
    }

    public function login(string $username, string $password): bool
    {
        $user = $this->users->findByUsername($username);
        if ($user === null) {
            return false;
        }

        if (!password_verify($password, (string) $user['password_hash'])) {
            return false;
        }

        $_SESSION['auth_user'] = [
            'id' => (int) $user['id'],
            'username' => $user['username'],
            'full_name' => $user['full_name'] ?? $user['username'],
        ];

        return true;
    }

    public function logout(): void
    {
        unset($_SESSION['auth_user']);
    }

    public function check(): bool
    {
        return isset($_SESSION['auth_user']) && is_array($_SESSION['auth_user']);
    }

    public function user(): ?array
    {
        return $this->check() ? $_SESSION['auth_user'] : null;
    }

    public function requireAuth(): void
    {
        if (!$this->check()) {
            set_flash('error', 'Veuillez vous connecter pour acceder au backoffice.');
            redirect('/admin/login');
        }
    }
}
