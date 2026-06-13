
<?php
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function current_user() {
    return $_SESSION['user'] ?? null;
}

function require_login() {
    if (!current_user()) {
        header('Location: ' . BASE_URL . 'auth/login.php');
        exit;
    }
}

function require_admin() {
    if (!current_user() || !current_user()['is_admin']) {
        http_response_code(403);
        echo "Forbidden";
        exit;
    }
}

function set_user_session(array $user) {
    $_SESSION['user'] = [
        'id' => (int)$user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'is_admin' => (int)$user['is_admin'],
    ];
}

function logout() {
    $_SESSION = [];
    session_destroy();
}
