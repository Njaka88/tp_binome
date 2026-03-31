<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Services\AuthService;

final class AdminController
{
    private AuthService $auth;
    private ArticleRepository $articles;
    private CategoryRepository $categories;

    public function __construct()
    {
        $this->auth = new AuthService();
        $this->articles = new ArticleRepository();
        $this->categories = new CategoryRepository();
    }

    public function dashboard(Request $request, array $params): void
    {
        unset($request, $params);
        $this->auth->requireAuth();

        render('back/dashboard', [
            'user' => $this->auth->user(),
            'articlesCount' => count($this->articles->adminList()),
            'categoriesCount' => count($this->categories->all()),
            'metaTitle' => 'Dashboard BackOffice',
            'metaDescription' => 'Administration des contenus.',
        ]);
    }
}
