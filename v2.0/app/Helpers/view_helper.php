<?php

declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function status_badge(?string $status): string
{
    $status = $status ?: '-';
    $class = match ($status) {
        'approved', 'paid', 'active' => 'badge-success',
        'rejected', 'inactive', 'cancelled', 'failed', 'expired' => 'badge-danger',
        'closed', 'refunded' => 'badge-info',
        default => 'badge-warning',
    };

    return '<span class="badge ' . $class . '">' . e($status) . '</span>';
}
