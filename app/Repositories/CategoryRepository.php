<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class CategoryRepository
{
    public function all(): array
    {
        $sql = 'SELECT id, name, slug, description FROM categories ORDER BY name ASC';

        return Database::connection()->query($sql)->fetchAll();
    }

    public function allWithPublishedCount(): array
    {
        $sql = 'SELECT c.id, c.name, c.slug, c.description,
                       COUNT(a.id) AS published_count
                FROM categories c
                LEFT JOIN articles a
                    ON a.category_id = c.id AND a.status = "published"
                GROUP BY c.id, c.name, c.slug, c.description
                ORDER BY c.name ASC';

        return Database::connection()->query($sql)->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT id, name, slug, description FROM categories WHERE id = :id LIMIT 1';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $category = $stmt->fetch();

        return $category ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $sql = 'SELECT id, name, slug, description FROM categories WHERE slug = :slug LIMIT 1';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        $category = $stmt->fetch();

        return $category ?: null;
    }

    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM categories WHERE slug = :slug';
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
        $sql = 'INSERT INTO categories (name, slug, description) VALUES (:name, :slug, :description)';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute([
            'name' => $payload['name'],
            'slug' => $payload['slug'],
            'description' => $payload['description'],
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    public function update(int $id, array $payload): void
    {
        $sql = 'UPDATE categories
                SET name = :name,
                    slug = :slug,
                    description = :description,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'name' => $payload['name'],
            'slug' => $payload['slug'],
            'description' => $payload['description'],
        ]);
    }

    public function delete(int $id): void
    {
        $sql = 'DELETE FROM categories WHERE id = :id';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}
