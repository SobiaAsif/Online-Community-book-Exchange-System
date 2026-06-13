<?php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/classes/Book.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : null;
$books = Book::findAll($q);
$u = current_user();

include __DIR__ . '/_layout/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>📚 Community Book Exchange</h1>
        <p>Share, borrow, and discover books with your community. Join thousands of readers today!</p>
        <?php if (!$u): ?>
            <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: center;">
                <a href="<?= BASE_URL ?>auth/register.php" class="btn btn-primary">Get Started</a>
                <a href="<?= BASE_URL ?>auth/login.php" class="btn btn-secondary">Login</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<div class="container">
    <!-- Search Section -->
    <div class="card" style="margin-bottom: 2rem;">
        <h2 style="margin-bottom: 1rem;">Discover Books</h2>
        <form method="get" style="display: flex; gap: 1rem; align-items: end;">
            <div style="flex: 1;">
                <label>Search Books</label>
                <input type="text" name="q" placeholder="Search by title, author, or genre..." 
                       value="<?= htmlspecialchars($q ?? '') ?>">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
            <?php if ($q): ?>
                <a href="<?= BASE_URL ?>" class="btn btn-secondary">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Books Grid -->
    <div class="card">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 1.5rem;">
            <h2>Available Books</h2>
            <span style="color: var(--secondary);"><?= count($books) ?> books found</span>
        </div>

        <?php if (empty($books)): ?>
            <div style="text-align: center; padding: 3rem; color: var(--secondary);">
                <h3>No books found</h3>
                <p>Try adjusting your search terms or be the first to add a book!</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Cover</th>
                            <th>Book Details</th>
                            <th>Owner</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($books as $b): ?>
                        <tr>
                            <td style="width: 80px;">
                                <!-- You can add book cover images here later -->
                                <div style="width: 60px; height: 80px; background: #e5e7eb; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                                    📖
                                </div>
                            </td>
                            <td>
                                <strong style="display: block; margin-bottom: 0.25rem;"><?= htmlspecialchars($b['title']) ?></strong>
                                <span style="color: var(--secondary);">by <?= htmlspecialchars($b['author']) ?></span>
                                <?php if ($b['genre']): ?>
                                    <div style="margin-top: 0.5rem;">
                                        <span style="background: #e0e7ff; color: #3730a3; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.75rem;">
                                            <?= htmlspecialchars($b['genre']) ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($b['owner_name']) ?></td>
                            <td>
                                <span class="status-badge status-<?= htmlspecialchars($b['status']) ?>">
                                    <?= htmlspecialchars($b['status']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($u && $u['id'] !== (int)$b['user_id'] && $b['status']==='available'): ?>
                                    <form method="post" action="<?= BASE_URL ?>requests/create.php" style="display:inline;">
                                        <input type="hidden" name="book_id" value="<?= (int)$b['id'] ?>">
                                        <input type="hidden" name="owner_id" value="<?= (int)$b['user_id'] ?>">
                                        <button class="btn btn-primary" style="padding: 0.5rem 1rem;">Request Book</button>
                                    </form>
                                <?php elseif (!$u): ?>
                                    <a href="<?= BASE_URL ?>auth/login.php" class="btn btn-secondary">Login to Request</a>
                                <?php else: ?>
                                    <span style="color: var(--secondary);">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/_layout/footer.php'; ?>