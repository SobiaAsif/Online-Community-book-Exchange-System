
<?php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/classes/User.php';
require_login();
$u = current_user();
$err = $ok = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';
    try {
        if (!$name || !$email) throw new Exception('Name and email are required.');
        User::updateProfile($u['id'], $name, $email, $newPassword ? $newPassword : null);
        $fresh = User::findById($u['id']);
        set_user_session($fresh);
        $ok = 'Profile updated.';
    } catch (Exception $e) {
        $err = $e->getMessage();
    }
}

include __DIR__ . '/_layout/header.php';
?>
<h2>My Profile</h2>
<?php if ($err): ?><div class="flash"><?= htmlspecialchars($err) ?></div><?php endif; ?>
<?php if ($ok): ?><div class="flash" style="background:#ecfdf5; border-color:#10b981; color:#065f46;"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
<form method="post" class="row">
  <div>
    <label>Name</label>
    <input name="name" value="<?= htmlspecialchars($u['name']) ?>" required>
  </div>
  <div>
    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($u['email']) ?>" required>
  </div>
  <div>
    <label>New Password (optional)</label>
    <input type="password" name="new_password" placeholder="Leave blank to keep current password">
  </div>
  <div style="grid-column:1 / span 2">
    <button class="btn">Save</button>
  </div>
</form>
<?php include __DIR__ . '/_layout/footer.php'; ?>
