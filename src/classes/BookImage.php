<?php
require_once __DIR__ . '/../db.php';

class BookImage {
    // Save new image record
    public static function create(int $bookId, string $filename): void {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO book_images (book_id, image_path) VALUES (?, ?)");
        $stmt->execute([$bookId, $filename]);
    }

    // Get all images of a book
    public static function findByBook(int $bookId): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM book_images WHERE book_id = ?");
        $stmt->execute([$bookId]);
        return $stmt->fetchAll();
    }

    // Delete a single image by its ID
    public static function delete(int $id): void {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM book_images WHERE id = ?");
        $stmt->execute([$id]);
    }

    // Delete all images of a book
    public static function deleteByBook(int $bookId): void {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM book_images WHERE book_id = ?");
        $stmt->execute([$bookId]);
    }
}
