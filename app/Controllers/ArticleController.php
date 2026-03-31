<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Services\AuthService;

final class ArticleController
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

    public function index(Request $request, array $params): void
    {
        unset($request, $params);
        $this->auth->requireAuth();

        render('back/articles/index', [
            'user' => $this->auth->user(),
            'articles' => $this->articles->adminList(),
            'metaTitle' => 'Articles',
            'metaDescription' => 'Gestion des articles.',
        ]);
    }

    public function create(Request $request, array $params): void
    {
        unset($request, $params);
        $this->auth->requireAuth();

        render('back/articles/form', [
            'user' => $this->auth->user(),
            'article' => null,
            'categories' => $this->categories->all(),
            'action' => '/admin/articles/create',
            'metaTitle' => 'Nouvel article',
            'metaDescription' => 'Creation d\'article.',
        ]);
    }

    public function store(Request $request, array $params): void
    {
        unset($params);
        $this->auth->requireAuth();
        require_csrf();

        $payload = $this->validatedPayload($request);
        if ($payload === null) {
            redirect('/admin/articles/create');
        }

        $imagePath = $this->handleUpload($request->file('image'));
        if ($imagePath === '') {
            store_old_input($_POST);
            redirect('/admin/articles/create');
        }
        $payload['image_path'] = $imagePath;

        $baseSlug = slugify($payload['slug'] ?: $payload['title']);
        $payload['slug'] = ensure_slug_unique(
            fn (string $candidate): bool => $this->articles->slugExists($candidate),
            $baseSlug
        );

        $this->articles->create($payload);
        clear_old_input();
        set_flash('success', 'Article cree avec succes.');
        redirect('/admin/articles');
    }

    public function edit(Request $request, array $params): void
    {
        unset($request);
        $this->auth->requireAuth();

        $id = (int) ($params['id'] ?? 0);
        $article = $this->articles->findById($id);
        if ($article === null) {
            set_flash('error', 'Article introuvable.');
            redirect('/admin/articles');
        }

        render('back/articles/form', [
            'user' => $this->auth->user(),
            'article' => $article,
            'categories' => $this->categories->all(),
            'action' => '/admin/articles/' . $id . '/edit',
            'metaTitle' => 'Modifier article',
            'metaDescription' => 'Edition d\'article.',
        ]);
    }

    public function update(Request $request, array $params): void
    {
        $this->auth->requireAuth();
        require_csrf();

        $id = (int) ($params['id'] ?? 0);
        $article = $this->articles->findById($id);
        if ($article === null) {
            set_flash('error', 'Article introuvable.');
            redirect('/admin/articles');
        }

        $payload = $this->validatedPayload($request);
        if ($payload === null) {
            redirect('/admin/articles/' . $id . '/edit');
        }

        $imagePath = $article['image_path'];
        $newImage = $this->handleUpload($request->file('image'));
        if ($newImage === '') {
            store_old_input($_POST);
            redirect('/admin/articles/' . $id . '/edit');
        }
        if ($newImage !== null) {
            $imagePath = $newImage;
        }
        $payload['image_path'] = $imagePath;

        $baseSlug = slugify($payload['slug'] ?: $payload['title']);
        $payload['slug'] = ensure_slug_unique(
            fn (string $candidate): bool => $this->articles->slugExists($candidate, $id),
            $baseSlug
        );

        $this->articles->update($id, $payload);
        clear_old_input();
        set_flash('success', 'Article mis a jour.');
        redirect('/admin/articles');
    }

    public function delete(Request $request, array $params): void
    {
        unset($request);
        $this->auth->requireAuth();
        require_csrf();

        $id = (int) ($params['id'] ?? 0);
        $article = $this->articles->findById($id);
        if ($article === null) {
            set_flash('error', 'Article introuvable.');
            redirect('/admin/articles');
        }

        $this->articles->delete($id);
        set_flash('success', 'Article supprime.');
        redirect('/admin/articles');
    }

    private function validatedPayload(Request $request): ?array
    {
        $title = trim((string) $request->input('title', ''));
        $slug = trim((string) $request->input('slug', ''));
        $summary = trim((string) $request->input('summary', ''));
        $content = trim((string) $request->input('content', ''));
        $metaTitle = trim((string) $request->input('meta_title', ''));
        $metaDescription = trim((string) $request->input('meta_description', ''));
        $status = (string) $request->input('status', 'draft');
        $categoryId = (int) $request->input('category_id', 0);

        if (!in_array($status, ['draft', 'published'], true)) {
            $status = 'draft';
        }

        if ($title === '' || $summary === '' || $content === '') {
            set_flash('error', 'Titre, resume et contenu sont obligatoires.');
            store_old_input($_POST);

            return null;
        }

        if ($categoryId < 1) {
            set_flash('error', 'Veuillez selectionner une categorie.');
            store_old_input($_POST);

            return null;
        }

        $publishedAt = null;
        if ($status === 'published') {
            $publishedAt = date('Y-m-d H:i:s');
        }

        return [
            'category_id' => $categoryId,
            'title' => $title,
            'slug' => $slug,
            'summary' => $summary,
            'content' => $content,
            'meta_title' => $metaTitle !== '' ? $metaTitle : mb_substr($title, 0, 60),
            'meta_description' => $metaDescription !== '' ? $metaDescription : mb_substr($summary, 0, 160),
            'status' => $status,
            'published_at' => $publishedAt,
        ];
    }

    private function handleUpload(?array $file): ?string
    {
        if ($file === null || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            set_flash('error', 'Erreur pendant l\'upload image.');

            return '';
        }

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        $tmpName = (string) ($file['tmp_name'] ?? '');
        $mime = mime_content_type($tmpName) ?: '';
        if (!isset($allowed[$mime])) {
            set_flash('error', 'Image invalide. Formats autorises: jpg, png, webp.');

            return '';
        }

        if (($file['size'] ?? 0) > 2 * 1024 * 1024) {
            set_flash('error', 'Image trop lourde (max 2MB).');

            return '';
        }

        $ext = $allowed[$mime];
        $filename = date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destinationDir = BASE_PATH . '/public/assets/uploads';
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0775, true);
        }

        $destination = $destinationDir . '/' . $filename;
        if (!move_uploaded_file($tmpName, $destination)) {
            set_flash('error', 'Impossible de sauvegarder l\'image upload.');

            return '';
        }

        return '/assets/uploads/' . $filename;
    }
}
