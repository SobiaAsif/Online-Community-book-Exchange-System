
<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/classes/Book.php';
require_login();
$u = current_user();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$book = $id ? Book::findById($id) : null;
if (!$book || (int)$book['user_id'] !== $u['id']) {
    http_response_code(404); die('Not found');
}
Book::delete($id, $u['id']);
header('Location: ' . BASE_URL . 'books/my.php');
