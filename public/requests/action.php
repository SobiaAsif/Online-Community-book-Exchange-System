
<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/classes/BorrowRequest.php';
require_login();
$u = current_user();

$id = (int)($_POST['id'] ?? 0);
$action = $_POST['action'] ?? '';
if ($id && in_array($action, ['accept','reject','cancel'])) {
    BorrowRequest::act($id, $u['id'], $action);
}
header('Location: ' . BASE_URL . 'requests/index.php');
