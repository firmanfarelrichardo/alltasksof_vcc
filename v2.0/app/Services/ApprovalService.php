<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

final class ApprovalService
{
    public function __construct(private readonly User $users = new User())
    {
    }

    public function pendingUsers(): array
    {
        return $this->users->pendingUsers();
    }

    public function approveUser(int $userId): bool
    {
        return $this->users->updateStatus($userId, 'approved');
    }

    public function rejectUser(int $userId): bool
    {
        return $this->users->updateStatus($userId, 'rejected');
    }
}
