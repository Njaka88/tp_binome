<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;

final class FrontController
{
    private ArticleRepository $articles;
    private CategoryRepository $categories;

    public function __construct()
    {
        $this->articles = new ArticleRepository();
        $this->categories = new CategoryRepository();
    }

    public function home(Request $request, array $params): void
    {
        unset($request, $params);

        render('front/home', [
            'articles' => $this->articles->latestPublished(6),
            'categories' => $this->categories->allWithPublishedCount(),
            'metaTitle' => app_config('seo.default_title'),
            'metaDescription' => app_config('seo.default_description'),
            'canonicalUrl' => base_url('/'),
        ]);
    }

    public function articles(Request $request, array $params): void
    {
        unset($params);

        $page = (int) ($request->query('page', 1));
        if ($page < 1) {
            $page = 1;
        }

        $perPage = 6;
        $total = $this->articles->countPublished();
        $items = $this->articles->paginatePublished($page, $perPage);
        $totalPages = max(1, (int) ceil($total / $perPage));

        render('front/articles', [
            'articles' => $items,
            'page' => $page,
            'totalPages' => $totalPages,
            'metaTitle' => 'Articles - Guerre en Iran',
            'metaDescription' => 'Tous les articles recents sur la guerre en Iran.',
            'canonicalUrl' => base_url('/articles?page=' . $page),
        ]);
    }

    public function article(Request $request, array $params): void
    {
        unset($request);

        $slug = (string) ($params['slug'] ?? '');
        $article = $this->articles->findPublishedBySlug($slug);

        if ($article === null) {
            http_response_code(404);
            render('front/404', [
                'metaTitle' => 'Article introuvable',
                'metaDescription' => 'Cet article n\'existe pas ou n\'est plus disponible.',
            ]);

            return;
        }

        render('front/article', [
            'article' => $article,
            'metaTitle' => $article['meta_title'] ?: $article['title'],
            'metaDescription' => $article['meta_description'] ?: $article['summary'],
            'canonicalUrl' => base_url('/article/' . $article['slug']),
        ]);
    }

    public function category(Request $request, array $params): void
    {
        $slug = (string) ($params['slug'] ?? '');
        $category = $this->categories->findBySlug($slug);
        if ($category === null) {
            http_response_code(404);
            render('front/404', [
                'metaTitle' => 'Categorie introuvable',
                'metaDescription' => 'La categorie demandee est introuvable.',
            ]);

            return;
        }

        $page = (int) ($request->query('page', 1));
        if ($page < 1) {
            $page = 1;
        }

        $perPage = 6;
        $total = $this->articles->countPublished((int) $category['id']);
        $items = $this->articles->paginatePublishedByCategory((int) $category['id'], $page, $perPage);
        $totalPages = max(1, (int) ceil($total / $perPage));

        render('front/category', [
            'category' => $category,
            'articles' => $items,
            'page' => $page,
            'totalPages' => $totalPages,
            'metaTitle' => $category['name'] . ' - Articles',
            'metaDescription' => 'Articles de la categorie ' . $category['name'] . '.',
            'canonicalUrl' => base_url('/categorie/' . $category['slug'] . '?page=' . $page),
        ]);
    }

    public function robots(Request $request, array $params): void
    {
        unset($request, $params);

        header('Content-Type: text/plain; charset=UTF-8');
        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo 'Sitemap: ' . base_url('/sitemap.xml') . "\n";
    }

    public function sitemap(Request $request, array $params): void
    {
        unset($request, $params);

        $items = $this->articles->sitemapItems();
        $categories = $this->categories->all();

        header('Content-Type: application/xml; charset=UTF-8');

        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $baseUrls = [
            ['loc' => base_url('/'), 'lastmod' => date('c')],
            ['loc' => base_url('/articles'), 'lastmod' => date('c')],
        ];

        foreach ($baseUrls as $url) {
            echo '<url>';
            echo '<loc>' . e($url['loc']) . '</loc>';
            echo '<lastmod>' . e($url['lastmod']) . '</lastmod>';
            echo '<changefreq>daily</changefreq>';
            echo '<priority>0.9</priority>';
            echo '</url>';
        }

        foreach ($categories as $category) {
            echo '<url>';
            echo '<loc>' . e(base_url('/categorie/' . $category['slug'])) . '</loc>';
            echo '<lastmod>' . e(date('c')) . '</lastmod>';
            echo '<changefreq>daily</changefreq>';
            echo '<priority>0.7</priority>';
            echo '</url>';
        }

        foreach ($items as $item) {
            echo '<url>';
            echo '<loc>' . e(base_url('/article/' . $item['slug'])) . '</loc>';
            echo '<lastmod>' . e(date('c', strtotime((string) $item['updated_at']))) . '</lastmod>';
            echo '<changefreq>weekly</changefreq>';
            echo '<priority>0.8</priority>';
            echo '</url>';
        }

        echo '</urlset>';
    }
}
