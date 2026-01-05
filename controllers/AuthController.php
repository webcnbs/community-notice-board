<?php
// controllers/AuthController.php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/AuditLog.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/database.php';

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_verify($_POST['csrf'] ?? '')) {
                $error = 'Session expired. Please try again.';
                include __DIR__ . '/../login.php';
                return;
            }

            $email    = sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $remember = isset($_POST['remember']);

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            // User not found OR password incorrect
            if (!$user || !password_verify($password, $user['password'])) {
                $error = 'Invalid email or password.';
                include __DIR__ . '/../login.php';
                return;
            }

            // Account exists but not active
            if ($user['status'] !== 'active') {
                $error = 'Your account is not active. Please wait for approval.';
                include __DIR__ . '/../login.php';
                return;
            }

            // ✅ Login successful
            $_SESSION['user'] = [
                'user_id'  => $user['user_id'],
                'username' => $user['username'],
                'role'     => $user['role']
            ];

            // Record login action
            (new AuditLog())->record(
                $user['user_id'],
                'Login',
                'User logged into the system'
            );

            // Remember me
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                Database::getInstance()->pdo()
                    ->prepare("UPDATE users SET remember_token=? WHERE user_id=?")
                    ->execute([$token, $user['user_id']]);

                setcookie(
                    REMEMBER_COOKIE,
                    $token,
                    time() + REMEMBER_LIFETIME,
                    '/',
                    '',
                    false,
                    true
                );
            }

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header('Location: route.php?action=admin-dashboard'); exit;
            } elseif ($user['role'] === 'manager') {
                header('Location: route.php?action=index2'); exit;
            } else {
                header('Location: index.php'); exit;
            }
        }

        include __DIR__ . '/../login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_verify($_POST['csrf'] ?? '')) die('Invalid CSRF token');
            $username = sanitize($_POST['username'] ?? '');
            $email    = sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $userModel = new User();

            if ($userModel->exists($email)) {
                $error = "Email already registered.";
                include __DIR__ . '/../register.php'; return;
            }

            if ($userModel->usernameExists($username)) {
                $error = "Username already taken. Please choose another.";
                include __DIR__ . '/../register.php'; return;
            }

            // Create new user (default status = pending)
            $userModel->create($username, $email, $password);

            (new AuditLog())->record(null, 'Registration', "New user registered: $username ($email)");

            $message = 'Registration submitted. Await manager/admin approval.';
            include __DIR__ . '/../login.php';
        } else {
            include __DIR__ . '/../register.php';
        }
    }

    public function logout() {
        if (isset($_SESSION['user']['user_id'])) {
            (new AuditLog())->record($_SESSION['user']['user_id'], 'Logout', 'User logged out');
        }
        setcookie(REMEMBER_COOKIE, '', time() - 3600, '/');
        session_destroy();
        header('Location: route.php?action=login'); exit;
    }

    // ✅ Forgot Password
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            include __DIR__ . '/../forgot-password.php';
            return;
        }
        if (!csrf_verify($_POST['csrf'] ?? '')) die('Invalid CSRF token');

        $email = sanitize($_POST['email'] ?? '');
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user) {
            $error = "Email not found.";
            include __DIR__ . '/../forgot-password.php'; return;
        }

        $token = bin2hex(random_bytes(32));
        $pdo = Database::getInstance()->pdo();
        $pdo->prepare("UPDATE users SET remember_token=? WHERE user_id=?")
            ->execute([$token, $user['user_id']]);

        // TODO: send email with reset link
        // For now, you can display the reset link for testing:
        $message = "Password reset link: reset-password.php?token=$token";
        include __DIR__ . '/../forgot-password.php';
    }

    // ✅ Reset Password
    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            include __DIR__ . '/../reset-password.php';
            return;
        }
        if (!csrf_verify($_POST['csrf'] ?? '')) die('Invalid CSRF token');

        $token = $_POST['token'] ?? '';
        $newPassword = $_POST['password'] ?? '';

        if (strlen($newPassword) < 8) {
            $error = "Password must be at least 8 characters.";
            include __DIR__ . '/../reset-password.php'; return;
        }

        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE remember_token=?");
        $stmt->execute([$token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $error = "Invalid or expired token.";
            include __DIR__ . '/../reset-password.php'; return;
        }

        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE users SET password=?, remember_token=NULL WHERE user_id=?")
            ->execute([$hash, $user['user_id']]);

        (new AuditLog())->record($user['user_id'], 'Password Reset', 'User reset their password');

        $message = "Password reset successful. Please login.";
        include __DIR__ . '/../login.php';
    }
}