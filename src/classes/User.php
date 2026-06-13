<?php
require_once __DIR__ . '/../db.php';

class User
{
    private static function handleDuplicateException(PDOException $e): void {
        if ($e->errorInfo[1] == 1062) {
            if (str_contains($e->getMessage(), 'email')) {
                throw new Exception('Email already registered.');
            }
            if (str_contains($e->getMessage(), 'username')) {
                throw new Exception('Username already taken.');
            }
            throw new Exception('Duplicate entry detected.');
        }
        throw $e;
    }

    private static function insertUser(array $fields): int {
        $pdo = Database::getConnection();

        $columns = implode(", ", array_keys($fields));
        $placeholders = rtrim(str_repeat("?, ", count($fields)), ", ");

        $sql = "INSERT INTO users ($columns) VALUES ($placeholders)";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute(array_values($fields));
            return (int)$pdo->lastInsertId();
        } catch (PDOException $e) {
            self::handleDuplicateException($e);
        }
    }

    public static function create(string $name, string $email, string $password): int {
        return self::insertUser([
            'name'          => $name,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    public static function createExtended(
        string $first_name,
        ?string $middle_name,
        string $last_name,
        string $gender,
        string $state,
        string $email,
        string $username,
        string $password
    ): int {
        return self::insertUser([
            'first_name'    => $first_name,
            'middle_name'   => $middle_name ?: null,
            'last_name'     => $last_name,
            'gender'        => $gender,
            'state'         => $state,
            'email'         => $email,
            'username'      => $username,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    public static function findByEmail(string $email): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('
            SELECT id, first_name, middle_name, last_name, gender, state, email, username, is_admin 
            FROM users WHERE id = ?
        ');
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function updateProfile(
        int $id,
        string $first_name,
        ?string $middle_name,
        string $last_name,
        string $gender,
        string $state,
        string $email,
        string $username,
        ?string $newPassword = null
    ): void {
        $pdo = Database::getConnection();

        $fields = [
            'first_name'  => $first_name,
            'middle_name' => $middle_name ?: null,
            'last_name'   => $last_name,
            'gender'      => $gender,
            'state'       => $state,
            'email'       => $email,
            'username'    => $username,
        ];

        if ($newPassword) {
            $fields['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $setClause = implode(', ', array_map(fn($col) => "$col=?", array_keys($fields)));

        $sql = "UPDATE users SET $setClause WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([...array_values($fields), $id]);
    }
}
