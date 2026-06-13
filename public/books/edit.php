<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/classes/Book.php';
require_once __DIR__ . '/../../src/classes/BookImage.php';

require_login();
$u = current_user();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$book = $id ? Book::findById($id) : null;

if ($id && (!$book || (int)$book['user_id'] !== $u['id'])) {
    http_response_code(404);
    die('Not found');
}

$err = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title  = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $genre  = trim($_POST['genre'] ?? '');
    $cond   = trim($_POST['book_condition'] ?? '');
    $status = $_POST['status'] ?? 'available';

    try {
        if (!$title || !$author) throw new Exception('Title and author are required.');

        // Create / Update book
        if ($id) {
            Book::update($id, $u['id'], $title, $author, $genre ?: null, $cond ?: null, $status);
        } else {
            $id = Book::create($u['id'], $title, $author, $genre ?: null, $cond ?: null);
        }

        // ---------------- UPLOAD IMAGES ----------------
        if (!empty($_FILES['images']['name'][0])) {
            // FIXED: Correct upload path to public/uploads/books/
            $uploadDir = __DIR__ . '/../uploads/books/';

            // Debug: Show the upload path
            error_log("Upload directory: " . $uploadDir);

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
                if (!is_uploaded_file($tmp)) continue;

                $filename = time() . "_" . basename($_FILES['images']['name'][$i]);
                $destPath = $uploadDir . $filename;

                if (move_uploaded_file($tmp, $destPath)) {
                    BookImage::create($id, $filename);
                    error_log("Image saved to: " . $destPath);
                } else {
                    error_log("Failed to move uploaded file to: " . $destPath);
                }
            }
        }

        // ---------------- DELETE IMAGES ----------------
        if (!empty($_POST['delete_images'])) {
            foreach ($_POST['delete_images'] as $imgId) {
                $imgId = (int)$imgId;
                $images = BookImage::findByBook($id);
                foreach ($images as $img) {
                    if ($img['id'] === $imgId) {
                        $path = __DIR__ . '/../uploads/books/' . $img['image_path'];
                        if (file_exists($path)) unlink($path);
                    }
                }
                BookImage::delete($imgId);
            }
        }

        header('Location: ' . BASE_URL . 'books/my.php');
        exit;

    } catch (Exception $e) {
        $err = $e->getMessage();
    }
}

include __DIR__ . '/../_layout/header.php';
?>

<h2><?= $id ? 'Edit Book' : 'Add Book' ?></h2>
<?php if ($err): ?><div class="flash"><?= htmlspecialchars($err) ?></div><?php endif; ?>

<form method="post" enctype="multipart/form-data" class="row">

  <div>
    <label>Title</label>
    <input name="title" value="<?= htmlspecialchars($book['title'] ?? '') ?>" required>
  </div>

  <div>
    <label>Author</label>
    <input name="author" value="<?= htmlspecialchars($book['author'] ?? '') ?>" required>
  </div>

  <div>
    <label>Genre</label>
    <input name="genre" value="<?= htmlspecialchars($book['genre'] ?? '') ?>">
  </div>

  <div>
    <label>Condition</label>
    <input name="book_condition" value="<?= htmlspecialchars($book['book_condition'] ?? '') ?>">
  </div>

  <?php if ($id): ?>
  <div>
    <label>Status</label>
    <select name="status">
      <option value="available" <?= ($book['status'] ?? '')==='available' ? 'selected' : '' ?>>Available</option>
      <option value="lent" <?= ($book['status'] ?? '')==='lent' ? 'selected' : '' ?>>Lent Out</option>
    </select>
  </div>
  <?php endif; ?>

  <!-- IMAGE UPLOAD -->
  <div>
      <label>Upload Images (you can select multiple)</label>
      <input type="file" name="images[]" multiple accept="image/*">
  </div>

  <!-- SHOW EXISTING IMAGES -->
  <?php if ($id): ?>
      <?php $images = BookImage::findByBook($id); ?>
      <?php if ($images): ?>
          <div style="margin-top:15px;">
              <label>Existing Images</label><br>
              <?php foreach ($images as $img): ?>
                  <div style="display:inline-block; margin:5px; text-align:center;">
                      <img src="<?= BASE_URL ?>uploads/books/<?= htmlspecialchars($img['image_path']) ?>"
                           style="width:80px; height:80px; object-fit:cover; border-radius:4px;">
                      <br>
                      <label>
                          <input type="checkbox" name="delete_images[]" value="<?= $img['id'] ?>">
                          Delete
                      </label>
                  </div>
              <?php endforeach; ?>
          </div>
      <?php endif; ?>
  <?php endif; ?>

  <div style="grid-column:1 / span 2; margin-top:20px;">
    <button class="btn"><?= $id ? 'Save' : 'Create' ?></button>
    <a class="btn secondary" href="<?= BASE_URL ?>books/my.php">Cancel</a>
  </div>

</form>

<?php include __DIR__ . '/../_layout/footer.php'; ?>