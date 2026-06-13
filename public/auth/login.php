
<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/classes/User.php';

$err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    try {
        $user = User::findByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            throw new Exception('Invalid credentials.');
        }
        set_user_session($user);
        header('Location: ' . BASE_URL);
        exit;
    } catch (Exception $e) {
        $err = $e->getMessage();
    }
}

include __DIR__ . '/../_layout/header.php';
?>
<h2>Login</h2>
<?php if ($err): ?><div class="flash"><?= htmlspecialchars($err) ?></div><?php endif; ?>
<form method="post" class="row">
  <div>
    <label>Email</label>
    <input type="email" name="email" required>
  </div>
  <div>
    <label>Password</label>
    <input type="password" name="password" required>
  </div>
  <div style="grid-column:1 / span 2">
    <button class="btn">Login</button>
  </div>
</form>
<?php include __DIR__ . '/../_layout/footer.php'; ?>
