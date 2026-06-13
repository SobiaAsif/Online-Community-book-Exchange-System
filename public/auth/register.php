<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/classes/User.php';

$err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $middle_name = trim($_POST['middle_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    try {
        if (!$first_name || !$last_name || !$gender || !$state || !$email || !$username || !$password) {
            throw new Exception('All fields except Middle Name are required.');
        }

        $id = User::createExtended($first_name, $middle_name, $last_name, $gender, $state, $email, $username, $password);
        $user = User::findById($id);
        set_user_session($user);
        header('Location: ' . BASE_URL);
        exit;
    } catch (Exception $e) {
        $err = $e->getMessage();
    }
}

include __DIR__ . '/../_layout/header.php';
?>

<style>
.register-container {
    max-width: 500px;
    margin: 40px auto;
    background: #e6a7a3ff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.register-container h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #222;
}

.register-container form div {
    margin-bottom: 15px;
}

.register-container label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #444;
}

.register-container input, 
.register-container select {
    width: 100%;
    padding: 10px;
    border: 1px solid #2c2222ff;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s;
}

.register-container input:focus,
.register-container select:focus {
    border-color: #007BFF;
    outline: none;
}

.register-container .btn {
    width: 100%;
    padding: 10px;
    background: #007BFF;
    border: none;
    border-radius: 8px;
    color: #fff;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.register-container .btn:hover {
    background: #0056b3;
}

.flash {
    background: #ffe0e0;
    border: 1px solid #ffcccc;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 8px;
    color: #d8000c;
    text-align: middle;
}
</style>

<div class="register-container">
  <h2>Create Account</h2>

  <?php if ($err): ?>
      <div class="flash"><?= htmlspecialchars($err) ?></div>
  <?php endif; ?>

  <form method="post">
    <div>
      <label for="first_name">First Name</label>
      <input id="first_name" name="first_name" required>
    </div>
    <div>
      <label for="middle_name">Middle Name (optional)</label>
      <input id="middle_name" name="middle_name">
    </div>
    <div>
      <label for="last_name">Last Name</label>
      <input id="last_name" name="last_name" required>
    </div>
    <div>
      <label for="gender">Gender</label>
      <select id="gender" name="gender" required>
        <option value="">-- Select Gender --</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
      </select>
    </div>
    <div>
      <label for="state">State</label>
      <input id="state" name="state" required>
    </div>
    <div>
      <label for="email">Email</label>
      <input id="email" type="email" name="email" required>
    </div>
    <div>
      <label for="username">Username</label>
      <input id="username" name="username" required>
    </div>
    <div>
      <label for="password">Password</label>
      <input id="password" type="password" name="password" required>
    </div>
    <div>
      <button class="btn" type="submit">Register</button>
    </div>
  </form>
</div>

<?php include __DIR__ . '/../_layout/footer.php'; ?>
