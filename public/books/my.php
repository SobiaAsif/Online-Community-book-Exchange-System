<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/classes/Book.php';
require_once __DIR__ . '/../../src/classes/BookImage.php';

require_login();
$u = current_user();
$books = Book::findByUser($u['id']);

include __DIR__ . '/../_layout/header.php';
?>

<h1>My Books</h1>
<p><a class="btn" href="<?= BASE_URL ?>books/edit.php">➕ Add Book</a></p>

<style>
.books-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 12px;
  table-layout: fixed;
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.books-table th {
  background: #f8fafc;
  padding: 12px 8px;
  text-align: left;
  font-weight: 600;
  color: #374151;
  border-bottom: 2px solid #e5e7eb;
}

.books-table td {
  padding: 12px 8px;
  border-bottom: 1px solid #e5e7eb;
  vertical-align: top;
}

/* Column widths */
.books-table th:nth-child(1), .books-table td:nth-child(1) { width: 12%; }  /* Images */
.books-table th:nth-child(2), .books-table td:nth-child(2) { width: 20%; }  /* Title */
.books-table th:nth-child(3), .books-table td:nth-child(3) { width: 15%; }  /* Author */
.books-table th:nth-child(4), .books-table td:nth-child(4) { width: 12%; }  /* Genre */
.books-table th:nth-child(5), .books-table td:nth-child(5) { width: 12%; }  /* Condition */
.books-table th:nth-child(6), .books-table td:nth-child(6) { width: 10%; }  /* Status */
.books-table th:nth-child(7), .books-table td:nth-child(7) { width: 19%; }  /* Actions */

.status-badge {
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 500;
  display: inline-block;
  text-transform: capitalize;
}

.status-available {
  background: #dcfce7;
  color: #166534;
  border: 1px solid #bbf7d0;
}

.status-lent {
  background: #fef2f2;
  color: #991b1b;
  border: 1px solid #fecaca;
}

.action-buttons {
  display: flex;
  gap: 6px;
  flex-wrap: wrap;
}

.btn-small {
  padding: 6px 10px !important;
  font-size: 12px !important;
  text-decoration: none;
  display: inline-block;
}

.image-gallery {
  display: flex;
  gap: 4px;
  flex-wrap: wrap;
}

.book-image {
  width: 40px;
  height: 40px;
  object-fit: cover;
  border-radius: 4px;
  border: 1px solid #ddd;
}

.no-image {
  color: #9ca3af;
  font-size: 12px;
  font-style: italic;
}

.empty-state {
  text-align: center;
  padding: 40px;
  color: #6b7280;
}
</style>

<?php if (empty($books)): ?>
  <div class="empty-state">
    <h3>No books yet</h3>
    <p>Start building your collection by adding your first book!</p>
  </div>
<?php else: ?>
  <table class="books-table">
    <thead>
      <tr>
        <th>Cover</th>
        <th>Title</th>
        <th>Author</th>
        <th>Genre</th>
        <th>Condition</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($books as $b): ?>
      <?php $images = BookImage::findByBook($b['id']); ?>
      <tr>
        <td>
          <?php if ($images): ?>
            <div class="image-gallery">
              <?php foreach (array_slice($images, 0, 2) as $img): ?>
                <img src="<?= BASE_URL ?>uploads/books/<?= htmlspecialchars($img['image_path']) ?>"
                     class="book-image"
                     alt="Book cover">
              <?php endforeach; ?>
              <?php if (count($images) > 2): ?>
                <span style="color:#6b7280; font-size:11px;">+<?= count($images) - 2 ?></span>
              <?php endif; ?>
            </div>
          <?php else: ?>
            <span class="no-image">No Image</span>
          <?php endif; ?>
        </td>
        <td><strong><?= htmlspecialchars($b['title']) ?></strong></td>
        <td><?= htmlspecialchars($b['author']) ?></td>
        <td><?= htmlspecialchars($b['genre'] ?: '-') ?></td>
        <td><?= htmlspecialchars($b['book_condition'] ?: '-') ?></td>
        <td>
          <span class="status-badge status-<?= htmlspecialchars($b['status']) ?>">
            <?= htmlspecialchars($b['status']) ?>
          </span>
        </td>
        <td>
          <div class="action-buttons">
            <a class="btn secondary btn-small" 
               href="<?= BASE_URL ?>books/edit.php?id=<?= (int)$b['id'] ?>">
               Edit
            </a>
            <a class="btn danger btn-small" 
               href="<?= BASE_URL ?>books/delete.php?id=<?= (int)$b['id'] ?>"
               onclick="return confirm('Are you sure you want to delete \"<?= addslashes($b['title']) ?>\"?')">
               Delete
            </a>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<?php include __DIR__ . '/../_layout/footer.php'; ?>