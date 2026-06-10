<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Session;
use App\Models\User;

final class AuthService
{
    public function __construct(private readonly User $users = new User())
    {
    }

    public function register(string $name, string $email, string $password): array
    {
        if ($this->users->findByEmail($email) !== null) {
            return ['success' => false, 'message' => 'Email sudah terdaftar.'];
        }

        $this->users->createPendingUser($name, $email, password_hash($password, PASSWORD_DEFAULT));

        return ['success' => true, 'message' => 'Registrasi berhasil. Akun Anda menunggu persetujuan superadmin.'];
    }

    public function login(string $email, string $password): array
    {
        $user = $this->users->findByEmail($email);

        if ($user === null || !password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Email atau password tidak valid.'];
        }

        if ($user['status'] !== 'approved') {
            return ['success' => false, 'message' => 'Akun belum aktif atau tidak dapat digunakan.'];
        }

        Session::regenerate();
        Session::put('user_id', (int) $user['id']);
        Session::put('user_name', $user['name']);
        Session::put('role', $user['role']);
        Session::put('status', $user['status']);

        return ['success' => true, 'redirect' => self::redirectPathForRole($user['role'])];
    }

    public function logout(): void
    {
        Session::destroy();
    }

    public static function redirectPathForRole(?string $role): string
    {
        return match ($role) {
            'admin' => '/admin/dashboard',
            'superadmin' => '/superadmin/dashboard',
            default => '/user/dashboard',
        };
    }
}
