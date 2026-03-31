<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Repositories\CategoryRepository;
use App\Services\AuthService;

final class CategoryController
{
    private AuthService $auth;
    private CategoryRepository $categories;

    public function __construct()
    {
        $this->auth = new AuthService();
        $this->categories = new CategoryRepository();
    }

    public function index(Request $request, array $params): void
    {
        unset($request, $params);
        $this->auth->requireAuth();

        render('back/categories/index', [
            'user' => $this->auth->user(),
            'categories' => $this->categories->all(),
            'metaTitle' => 'Categories',
            'metaDescription' => 'Gestion des categories.',
        ]);
    }

    public function create(Request $request, array $params): void
    {
        unset($request, $params);
        $this->auth->requireAuth();

        render('back/categories/form', [
            'user' => $this->auth->user(),
            'category' => null,
            'action' => '/admin/categories/create',
            'metaTitle' => 'Nouvelle categorie',
            'metaDescription' => 'Creation d\'une categorie.',
        ]);
    }

    public function store(Request $request, array $params): void
    {
        unset($params);
        $this->auth->requireAuth();
        require_csrf();

        $name = trim((string) $request->input('name', ''));
        $description = trim((string) $request->input('description', ''));
        $slugInput = trim((string) $request->input('slug', ''));
        $baseSlug = slugify($slugInput !== '' ? $slugInput : $name);

        if ($name === '') {
            set_flash('error', 'Le nom de categorie est obligatoire.');
            store_old_input($_POST);
            redirect('/admin/categories/create');
        }

        $slug = ensure_slug_unique(fn (string $candidate): bool => $this->categories->slugExists($candidate), $baseSlug);

        $this->categories->create([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
        ]);

        clear_old_input();
        set_flash('success', 'Categorie creee avec succes.');
        redirect('/admin/categories');
    }

    public function edit(Request $request, array $params): void
    {
        unset($request);
        $this->auth->requireAuth();

        $id = (int) ($params['id'] ?? 0);
        $category = $this->categories->findById($id);

        if ($category === null) {
            set_flash('error', 'Categorie introuvable.');
            redirect('/admin/categories');
        }

        render('back/categories/form', [
            'user' => $this->auth->user(),
            'category' => $category,
            'action' => '/admin/categories/' . $id . '/edit',
            'metaTitle' => 'Modifier categorie',
            'metaDescription' => 'Edition de categorie.',
        ]);
    }

    public function update(Request $request, array $params): void
    {
        $this->auth->requireAuth();
        require_csrf();

        $id = (int) ($params['id'] ?? 0);
        $category = $this->categories->findById($id);
        if ($category === null) {
            set_flash('error', 'Categorie introuvable.');
            redirect('/admin/categories');
        }

        $name = trim((string) $request->input('name', ''));
        $description = trim((string) $request->input('description', ''));
        $slugInput = trim((string) $request->input('slug', ''));
        $baseSlug = slugify($slugInput !== '' ? $slugInput : $name);

        if ($name === '') {
            set_flash('error', 'Le nom de categorie est obligatoire.');
            store_old_input($_POST);
            redirect('/admin/categories/' . $id . '/edit');
        }

        $slug = ensure_slug_unique(
            fn (string $candidate): bool => $this->categories->slugExists($candidate, $id),
            $baseSlug
        );

        $this->categories->update($id, [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
        ]);

        clear_old_input();
        set_flash('success', 'Categorie mise a jour.');
        redirect('/admin/categories');
    }

    public function delete(Request $request, array $params): void
    {
        unset($request);
        $this->auth->requireAuth();
        require_csrf();

        $id = (int) ($params['id'] ?? 0);
        $category = $this->categories->findById($id);
        if ($category === null) {
            set_flash('error', 'Categorie introuvable.');
            redirect('/admin/categories');
        }

        $this->categories->delete($id);
        set_flash('success', 'Categorie supprimee.');
        redirect('/admin/categories');
    }
}
