<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/BookImage.php';

class Book {

    /* ------------------------- CREATE ------------------------- */
    public static function create(int $userId, string $title, string $author, ?string $genre, ?string $condition, array $images = []): int {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO books (user_id, title, author, genre, book_condition) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$userId, $title, $author, $genre, $condition]);
        $bookId = (int)$pdo->lastInsertId();

        // Save images
        foreach ($images as $img) {
            BookImage::create($bookId, $img);
        }

        return $bookId;
    }

    /* ------------------------- UPDATE ------------------------- */
    public static function update(int $id, int $userId, string $title, string $author, ?string $genre, ?string $condition, string $status, array $newImages = []): void {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE books SET title=?, author=?, genre=?, book_condition=?, status=? WHERE id=? AND user_id=?');
        $stmt->execute([$title, $author, $genre, $condition, $status, $id, $userId]);

        // Add new images if any
        foreach ($newImages as $img) {
            BookImage::create($id, $img);
        }
    }

    /* ------------------------- DELETE ------------------------- */
    public static function delete(int $id, int $userId): void {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM books WHERE id=? AND user_id=?');
        $stmt->execute([$id, $userId]);
    }

    /* Delete book + all images */
    public static function deleteWithImages(int $id, int $userId): void {
        $pdo = Database::getConnection();

        // Fetch images
        $imgs = BookImage::findByBook($id);

        // Delete image files
        foreach ($imgs as $img) {
            $path = __DIR__ . '/../../uploads/books/' . $img['image_path'];
            if (file_exists($path)) {
                unlink($path);
            }
            BookImage::delete((int)$img['id']);
        }

        // Delete book
        self::delete($id, $userId);
    }

    /* ------------------------- FIND ------------------------- */
    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM books WHERE id=?');
        $stmt->execute([$id]);
        $b = $stmt->fetch();
        return $b ?: null;
    }

    /* Find book + images together */
    public static function findWithImages(int $id): ?array {
        $book = self::findById($id);
        if (!$book) return null;

        $book['images'] = BookImage::findByBook($id);
        return $book;
    }

    /* ------------------------- FIND ALL ------------------------- */
    public static function findAll(?string $q = null): array {
        $pdo = Database::getConnection();
        if ($q) {
            $like = '%' . $q . '%';
            $stmt = $pdo->prepare('
                SELECT b.*, u.name as owner_name
                FROM books b
                JOIN users u ON u.id=b.user_id
                WHERE b.title LIKE ? OR b.author LIKE ?
                ORDER BY b.created_at DESC
            ');
            $stmt->execute([$like, $like]);
        } else {
            $stmt = $pdo->query('
                SELECT b.*, u.name as owner_name
                FROM books b
                JOIN users u ON u.id=b.user_id
                ORDER BY b.created_at DESC
            ');
        }
        return $stmt->fetchAll();
    }

    /* ------------------------- FIND BY USER ------------------------- */
    public static function findByUser(int $userId): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM books WHERE user_id=? ORDER BY created_at DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}
