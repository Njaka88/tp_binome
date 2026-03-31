<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class ArticleRepository
{
    public function latestPublished(int $limit = 6): array
    {
        $sql = 'SELECT a.id, a.title, a.slug, a.summary, a.image_path, a.published_at,
                       c.name AS category_name, c.slug AS category_slug
                FROM articles a
                LEFT JOIN categories c ON c.id = a.category_id
                WHERE a.status = "published"
                ORDER BY a.published_at DESC, a.id DESC
                LIMIT :limit';

        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countPublished(?int $categoryId = null): int
    {
        $sql = 'SELECT COUNT(*) FROM articles WHERE status = "published"';
        $params = [];

        if ($categoryId !== null) {
            $sql .= ' AND category_id = :category_id';
            $params['category_id'] = $categoryId;
        }

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    public function paginatePublished(int $page, int $perPage): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $sql = 'SELECT a.id, a.title, a.slug, a.summary, a.image_path, a.published_at,
                       c.name AS category_name, c.slug AS category_slug
                FROM articles a
                LEFT JOIN categories c ON c.id = a.category_id
                WHERE a.status = "published"
                ORDER BY a.published_at DESC, a.id DESC
                LIMIT :limit OFFSET :offset';
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function paginatePublishedByCategory(int $categoryId, int $page, int $perPage): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $sql = 'SELECT a.id, a.title, a.slug, a.summary, a.image_path, a.published_at,
                       c.name AS category_name, c.slug AS category_slug
                FROM articles a
                LEFT JOIN categories c ON c.id = a.category_id
                WHERE a.status = "published" AND a.category_id = :category_id
                ORDER BY a.published_at DESC, a.id DESC
                LIMIT :limit OFFSET :offset';
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findPublishedBySlug(string $slug): ?array
    {
        $sql = 'SELECT a.id, a.title, a.slug, a.summary, a.content, a.image_path,
                       a.meta_title, a.meta_description, a.published_at,
                       c.name AS category_name, c.slug AS category_slug
                FROM articles a
                LEFT JOIN categories c ON c.id = a.category_id
                WHERE a.slug = :slug AND a.status = "published"
                LIMIT 1';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        $article = $stmt->fetch();

        return $article ?: null;
    }

    public function adminList(): array
    {
        $sql = 'SELECT a.id, a.title, a.slug, a.status, a.published_at, a.updated_at,
                       c.name AS category_name
                FROM articles a
                LEFT JOIN categories c ON c.id = a.category_id
                ORDER BY a.updated_at DESC, a.id DESC';

        return Database::connection()->query($sql)->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT id, category_id, title, slug, summary, content, image_path,
                       meta_title, meta_description, status, published_at
                FROM articles
                WHERE id = :id
                LIMIT 1';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $article = $stmt->fetch();

        return $article ?: null;
    }

    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM articles WHERE slug = :slug';
        $params = ['slug' => $slug];

        if ($excludeId !== null) {
            $sql .= ' AND id != :exclude_id';
            $params['exclude_id'] = $excludeId;
        }

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(array $payload): int
    {
        $sql = 'INSERT INTO articles (
                    category_id, title, slug, summary, content, image_path,
                    meta_title, meta_description, status, published_at
                ) VALUES (
                    :category_id, :title, :slug, :summary, :content, :image_path,
                    :meta_title, :meta_description, :status, :published_at
                )';

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute([
            'category_id' => $payload['category_id'],
            'title' => $payload['title'],
            'slug' => $payload['slug'],
            'summary' => $payload['summary'],
            'content' => $payload['content'],
            'image_path' => $payload['image_path'],
            'meta_title' => $payload['meta_title'],
            'meta_description' => $payload['meta_description'],
            'status' => $payload['status'],
            'published_at' => $payload['published_at'],
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    public function update(int $id, array $payload): void
    {
        $sql = 'UPDATE articles
                SET category_id = :category_id,
                    title = :title,
                    slug = :slug,
                    summary = :summary,
                    content = :content,
                    image_path = :image_path,
                    meta_title = :meta_title,
                    meta_description = :meta_description,
                    status = :status,
                    published_at = :published_at,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id';

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'category_id' => $payload['category_id'],
            'title' => $payload['title'],
            'slug' => $payload['slug'],
            'summary' => $payload['summary'],
            'content' => $payload['content'],
            'image_path' => $payload['image_path'],
            'meta_title' => $payload['meta_title'],
            'meta_description' => $payload['meta_description'],
            'status' => $payload['status'],
            'published_at' => $payload['published_at'],
        ]);
    }

    public function delete(int $id): void
    {
        $sql = 'DELETE FROM articles WHERE id = :id';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    public function sitemapItems(): array
    {
        $sql = 'SELECT slug, updated_at
                FROM articles
                WHERE status = "published"
                ORDER BY updated_at DESC';

        return Database::connection()->query($sql)->fetchAll();
    }
}
