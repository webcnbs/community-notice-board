<?php
// controllers/AuthController.php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_verify($_POST['csrf'] ?? '')) die('Invalid CSRF token');
            $email = sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $remember = isset($_POST['remember']);

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user && $user['status'] === 'active' && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'user_id'   => $user['user_id'],
                    'username'  => $user['username'],
                    'role'      => $user['role']
                ];

                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    $pdo = Database::getInstance()->pdo();
                    $pdo->prepare("UPDATE users SET remember_token=? WHERE user_id=?")
                        ->execute([$token, $user['user_id']]);
                    setcookie(REMEMBER_COOKIE, $token, time() + REMEMBER_LIFETIME, '/', '', false, true);
                }

                // ✅ Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: admin/dashboard.php'); exit;
                } elseif ($user['role'] === 'manager') {
                    header('Location: index2.php'); exit; // manager dashboard
                } else {
                    header('Location: index.php'); exit; // resident home
                }
            }

            $error = 'Invalid credentials or inactive account';
            include __DIR__ . '/../login.php';
        } else {
            include __DIR__ . '/../login.php';
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_verify($_POST['csrf'] ?? '')) die('Invalid CSRF token');
            $username = sanitize($_POST['username'] ?? '');
            $email    = sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $userModel = new User();

            // ✅ Check for duplicate username
            if ($userModel->usernameExists($username)) {
                $error = "Username already taken.";
                include __DIR__ . '/../register.php';
                return;
            }

            // ✅ Check for duplicate email
            if ($userModel->exists($email)) {
                $error = "Email already registered.";
                include __DIR__ . '/../register.php';
                return;
            }

            // Create new user (default status = pending)
            $userModel->create($username, $email, $password);

            $message = 'Registration submitted. Await manager/admin approval.';
            include __DIR__ . '/../login.php';
        } else {
            include __DIR__ . '/../register.php';
        }
    }

    public function logout() {
        setcookie(REMEMBER_COOKIE, '', time() - 3600, '/');
        session_destroy();
        header('Location: login.php'); exit;
    }
}