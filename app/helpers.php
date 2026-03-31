<?php

declare(strict_types=1);

function app_config(string $key, mixed $default = null): mixed
{
    global $appConfig;

    $segments = explode('.', $key);
    $value = $appConfig;

    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }

    return $value;
}

function base_url(string $path = ''): string
{
    $base = rtrim((string) app_config('app.base_url', ''), '/');
    $normalized = '/' . ltrim($path, '/');

    return $base . ($path === '' ? '' : $normalized);
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function render(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require BASE_PATH . '/views/' . $view . '.php';
}

function redirect(string $path): void
{
    header('Location: ' . base_url($path));
    exit;
}

function set_flash(string $key, string $message): void
{
    $_SESSION['_flash'][$key] = $message;
}

function flash(string $key): ?string
{
    if (!isset($_SESSION['_flash'][$key])) {
        return null;
    }

    $message = (string) $_SESSION['_flash'][$key];
    unset($_SESSION['_flash'][$key]);

    return $message;
}

function old_input(string $key, mixed $default = ''): mixed
{
    return $_SESSION['_old'][$key] ?? $default;
}

function store_old_input(array $input): void
{
    $_SESSION['_old'] = $input;
}

function clear_old_input(): void
{
    unset($_SESSION['_old']);
}

function csrf_token(): string
{
    if (!isset($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(16));
    }

    return (string) $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function verify_csrf_token(?string $token): bool
{
    return isset($_SESSION['_csrf']) && hash_equals((string) $_SESSION['_csrf'], (string) $token);
}

function require_csrf(): void
{
    if (!verify_csrf_token($_POST['_csrf'] ?? null)) {
        http_response_code(419);
        exit('CSRF token invalide.');
    }
}

function slugify(string $text): string
{
    $lower = function_exists('mb_strtolower') ? mb_strtolower($text) : strtolower($text);
    $text = trim($lower);
    $text = preg_replace('/[^a-z0-9]+/u', '-', $text) ?? '';
    $text = trim($text, '-');

    return $text !== '' ? $text : 'article';
}

function ensure_slug_unique(callable $existsCallback, string $baseSlug): string
{
    $slug = $baseSlug;
    $counter = 1;

    while ($existsCallback($slug)) {
        $slug = $baseSlug . '-' . $counter;
        $counter++;
    }

    return $slug;
}
