<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;
use App\Services\AuthService;

final class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService = new AuthService())
    {
    }

    public function showRegister(): void
    {
        $this->view('auth.register');
    }

    public function register(): void
    {
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $passwordConfirmation = (string) ($_POST['password_confirmation'] ?? '');

        if (!$this->validRegistration($name, $email, $password, $passwordConfirmation)) {
            Response::redirect('/register');
        }

        $result = $this->authService->register($name, $email, $password);
        Session::flash($result['success'] ? 'success' : 'error', $result['message']);

        Response::redirect($result['success'] ? '/login' : '/register');
    }

    public function showLogin(): void
    {
        $this->view('auth.login');
    }

    public function login(): void
    {
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if (!Validator::email($email) || !Validator::required($password)) {
            Session::flash('error', 'Email atau password tidak valid.');
            Response::redirect('/login');
        }

        $result = $this->authService->login($email, $password);

        if (!$result['success']) {
            Session::flash('error', $result['message']);
            Response::redirect('/login');
        }

        Response::redirect($result['redirect']);
    }

    public function logout(): void
    {
        $this->authService->logout();
        Response::redirect('/login');
    }

    private function validRegistration(string $name, string $email, string $password, string $confirmation): bool
    {
        if (!Validator::required($name) || !Validator::email($email)) {
            Session::flash('error', 'Nama dan email wajib valid.');
            return false;
        }

        if (!Validator::minLength($password, 8)) {
            Session::flash('error', 'Password minimal 8 karakter.');
            return false;
        }

        if ($password !== $confirmation) {
            Session::flash('error', 'Konfirmasi password tidak sesuai.');
            return false;
        }

        return true;
    }
}
