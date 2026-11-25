<?php

declare(strict_types=1);

function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function money($n): string
{
    return number_format((float) $n, 2, '.', ' ') . ' $';
}

function days(string $s, string $e): int
{
    return (new DateTime($s))->diff(new DateTime($e))->days + 1;
}

function flash(?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'] = $message;
        return null;
    }
    $msg = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $msg;
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_role(array|string $roles): void
{
    $user = current_user();
    $roles = (array) $roles;
    if (!$user || !in_array($user['role'], $roles, true)) {
        flash('Аввал ворид шавед.');
        redirect('/login');
    }
}

function calc(array $car, int $days, int $km): array
{
    $limit   = (int) $car['km_per_day'] * $days;
    $extraKm = max(0, $km - $limit);
    $base    = $days * (float) $car['price_day'];
    $extra   = $extraKm * (float) $car['extra_km_price'];
    return [
        'limit'    => $limit,
        'extra_km' => $extraKm,
        'base'     => $base,
        'extra'    => $extra,
        'total'    => $base + $extra,
    ];
}
