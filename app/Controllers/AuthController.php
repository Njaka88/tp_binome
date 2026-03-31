<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Services\AuthService;

final class AuthController
{
    private AuthService $auth;

    public function __construct()
    {
        $this->auth = new AuthService();
    }

    public function showLogin(Request $request, array $params): void
    {
        unset($request, $params);

        if ($this->auth->check()) {
            redirect('/admin');
        }

        render('back/login', [
            'metaTitle' => 'Connexion BackOffice',
            'metaDescription' => 'Connexion administrateur.',
        ]);
    }

    public function login(Request $request, array $params): void
    {
        unset($params);
        require_csrf();

        $username = trim((string) $request->input('username', ''));
        $password = (string) $request->input('password', '');

        if ($username === '' || $password === '') {
            set_flash('error', 'Veuillez remplir username et password.');
            store_old_input(['username' => $username]);
            redirect('/admin/login');
        }

        if (!$this->auth->login($username, $password)) {
            set_flash('error', 'Identifiants invalides.');
            store_old_input(['username' => $username]);
            redirect('/admin/login');
        }

        clear_old_input();
        set_flash('success', 'Connexion reussie.');
        redirect('/admin');
    }

    public function logout(Request $request, array $params): void
    {
        unset($request, $params);
        require_csrf();

        $this->auth->logout();
        set_flash('success', 'Vous etes deconnecte.');
        redirect('/admin/login');
    }
}
