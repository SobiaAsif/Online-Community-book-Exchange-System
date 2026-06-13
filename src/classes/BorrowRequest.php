
<?php
require_once __DIR__ . '/../db.php';

class BorrowRequest {
    public static function create(int $bookId, int $requesterId, int $ownerId): int {
        $pdo = Database::getConnection();
        if ($requesterId === $ownerId) {
            throw new Exception('You cannot request your own book.');
        }
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('SELECT status FROM books WHERE id=? AND user_id=?');
            $stmt->execute([$bookId, $ownerId]);
            $book = $stmt->fetch();
            if (!$book) throw new Exception('Book not found.');
            if ($book['status'] !== 'available') throw new Exception('Book is not available.');

            $stmt = $pdo->prepare('INSERT INTO borrow_requests (book_id, requester_id, owner_id, status) VALUES (?, ?, ?, "pending")');
            $stmt->execute([$bookId, $requesterId, $ownerId]);
            $pdo->commit();
            return (int)$pdo->lastInsertId();
        } catch (PDOException $e) {
            $pdo->rollBack();
            throw $e;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public static function listIncoming(int $userId): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT r.*, b.title, b.author, u.name as requester_name
                               FROM borrow_requests r
                               JOIN books b ON b.id = r.book_id
                               JOIN users u ON u.id = r.requester_id
                               WHERE r.owner_id=?
                               ORDER BY r.created_at DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function listOutgoing(int $userId): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT r.*, b.title, b.author, u.name as owner_name
                               FROM borrow_requests r
                               JOIN books b ON b.id = r.book_id
                               JOIN users u ON u.id = r.owner_id
                               WHERE r.requester_id=?
                               ORDER BY r.created_at DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function act(int $id, int $userId, string $action): void {
        $pdo = Database::getConnection();
        if ($action === 'cancel') {
            $stmt = $pdo->prepare('UPDATE borrow_requests SET status="cancelled" WHERE id=? AND requester_id=? AND status="pending"');
            $stmt->execute([$id, $userId]);
        } elseif (in_array($action, ['accept','reject'])) {
            $pdo->beginTransaction();
            try {
                $stmt = $pdo->prepare('SELECT * FROM borrow_requests WHERE id=? AND owner_id=? AND status="pending"');
                $stmt->execute([$id, $userId]);
                $r = $stmt->fetch();
                if (!$r) { $pdo->rollBack(); return; }
                $newStatus = $action === 'accept' ? 'accepted' : 'rejected';
                $stmt = $pdo->prepare('UPDATE borrow_requests SET status=? WHERE id=?');
                $stmt->execute([$newStatus, $id]);
                if ($newStatus === 'accepted') {
                    $stmt = $pdo->prepare('UPDATE books SET status="lent" WHERE id=?');
                    $stmt->execute([$r['book_id']]);
                }
                $pdo->commit();
            } catch (Exception $e) {
                $pdo->rollBack();
                throw $e;
            }
        }
    }
}
