<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Validator;
use App\Repositories\UserRepository;

final class AuthController extends BaseController
{
    private UserRepository $users;

    public function __construct()
    {
        parent::__construct();
        $this->users = new UserRepository();
    }

    public function loginForm(): void
    {
        if (current_user()) {
            $this->redirect('/');
        }
        $this->render('auth/login', ['title' => 'Вуруд']);
    }

    public function login(): void
    {
        $email = trim((string) ($_POST['email'] ?? ''));
        $pass  = (string) ($_POST['password'] ?? '');

        $user = $this->users->findByEmail($email);
        if (!$user || !password_verify($pass, $user['password'])) {
            flash('E-mail ё рамз нодуруст аст.');
            $this->redirect('/login');
        }

        unset($user['password']);
        $_SESSION['user'] = $user;
        flash('Хуш омадед, ' . $user['full_name'] . '!');
        $this->redirect('/');
    }

    public function registerForm(): void
    {
        if (current_user()) {
            $this->redirect('/');
        }
        $this->render('auth/register', ['title' => 'Бақайдгирӣ']);
    }

    public function register(): void
    {
        $v = (new Validator($_POST))
            ->required('full_name', 'Ному насаб ҳатмист')
            ->maxLength('full_name', 120)
            ->required('email')
            ->email('email')
            ->required('password', 'Рамз ҳатмист')
            ->maxLength('password', 72);

        if ($v->fails()) {
            $this->render('auth/register', [
                'title'  => 'Бақайдгирӣ',
                'errors' => $v->errors(),
                'old'    => $_POST,
            ], 422);
            return;
        }

        if ($this->users->emailExists($_POST['email'])) {
            flash('Ин e-mail аллакай ба қайд гирифта шудааст.');
            $this->redirect('/register');
        }

        $id = $this->users->create([
            'full_name' => trim($_POST['full_name']),
            'email'     => trim($_POST['email']),
            'phone'     => trim($_POST['phone'] ?? ''),
            'password'  => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'role'      => 'client',
        ]);

        $user = $this->users->find($id);
        $_SESSION['user'] = $user;
        flash('Бақайдгирӣ бомуваффақият анҷом ёфт!');
        $this->redirect('/');
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        flash('Шумо баромадед.');
        $this->redirect('/login');
    }
}
