<?php
require_once __DIR__ . '/../../src/auth.php';
$u = current_user();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Book Exchange</title>
  <style>
    :root {
        --primary: #2563eb;
        --primary-dark: #1d4ed8;
        --secondary: #6b7280;
        --danger: #dc2696ff;
        --success: #059669;
        --background: #0b3763ff;
        --card: #fa83c5ff;
        --text: #1f2937;
        --border: #1860f1ff;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background: var(--background);
        color: var(--text);
        line-height: 1.6;
    }

    /* Modern Header */
    header {
        background: linear(135deg, #1f2937 0%, #374151 100%);
        color: white;
        padding: 1rem 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .header-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo {
        font-size: 1.5rem;
        font-weight: bold;
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .logo:hover {
        color: #60a5fa;
    }

    nav {
        display: flex;
        gap: 1.5rem;
        align-items: center;
    }

    nav a {
        color: #d1d5db;
        text-decoration: none;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    nav a:hover {
        color: white;
        background: rgba(255, 255, 255, 0.1);
    }

    /* Modern Container */
    .container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .card {
        background: var(--card);
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid var(--border);
    }

    /* Modern Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.875rem;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-secondary {
        background: var(--secondary);
        color: white;
    }

    .btn-danger {
        background: var(--danger);
        color: white;
    }

    .btn-success {
        background: var(--success);
        color: white;
    }

    /* Modern Forms */
    .form-group {
        margin-bottom: 1.5rem;
    }

    label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--text);
    }

    input, select, textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border);
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    /* Modern Table */
    .table-container {
        background: var(--card);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table th {
        background: #278cf1ff;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid var(--border);
    }

    .modern-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border);
    }

    .modern-table tr:hover {
        background: #1ce677ff;
    }

    /* Flash Messages */
    .flash {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    .flash.success {
        background: #e6a721ff;
        border: 1px solid #1d754cff;
        color: #065f46;
    }

    .flash.error {
        background: #f87a7aff;
        border: 1px solid #723fd1ff;
        color: #991b1b;
    }

    /* Grid System */
    .grid {
        display: grid;
        gap: 1.5rem;
    }

    .grid-2 {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }

    /* Hero Section */
    .hero {
        text-align: center;
        padding: 4rem 2rem;
        background: linear(135deg, #1f2937 0%, #374151 100%);
        color: white;
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    .hero h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .hero p {
        font-size: 1.25rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Status badges for tables */
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    
    .status-available {
        background: #19f365ff;
        color: #166534;
    }
    
    .status-lent {
        background: #f71414ff;
        color: #991b1b;
    }
    
    .status-pending {
        background: #d8bf5aff;
        color: #92400e;
    }
  </style>
</head>
<body>
<header>
  <div class="header-content">
    <div>
      <a href="<?= BASE_URL ?>" class="logo">📚 Book Exchange</a>
    </div>
    <nav>
      <a href="<?= BASE_URL ?>">🔍 Browse</a>
      <?php if ($u): ?>
        <!-- Logged-in User Menu -->
        <a href="<?= BASE_URL ?>books/my.php">📚 My Books</a>
        <a href="<?= BASE_URL ?>requests/index.php">🔄 Requests</a>
        <a href="<?= BASE_URL ?>profile.php">👤 Profile</a>
        <a href="<?= BASE_URL ?>auth/logout.php">🚪 Logout</a>
      <?php else: ?>
        <!-- Guest Menu -->
        <a href="<?= BASE_URL ?>auth/login.php">🔐 Login</a>
        <a href="<?= BASE_URL ?>auth/register.php">📝 Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">