<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/classes/BorrowRequest.php';
require_login();
$u = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = (int)($_POST['book_id'] ?? 0);
    $ownerId = (int)($_POST['owner_id'] ?? 0);

    try {
        BorrowRequest::create($bookId, $u['id'], $ownerId); // static call
        header('Location: ' . BASE_URL . 'requests/index.php?msg=Request+sent');
        exit;
    } catch (Exception $e) {
        $msg = urlencode($e->getMessage());
        header('Location: ' . BASE_URL . '?error=' . $msg);
        exit;
    }
}

http_response_code(405);
