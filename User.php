<?php

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Register a new user
    public function registerUser($username, $email, $password, $role = 'user')
    {
        try {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Insert user into the database
            $sql = "INSERT INTO user (username, email, password, role) VALUES (:username, :email, :password, :role)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':role' => $role,
            ]);

            return true;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new Exception('Username or email already exists');
            }
            throw new Exception('Database error during registration: ' . $e->getMessage());
        }
    }

    // Update user details
    public function updateUser($username, $email, $password)
    {
        try {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Update user details in the database
            $sql = "UPDATE user SET email = :email, password = :password WHERE username = :username";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':email' => $email,
                ':password' => $hashedPassword,
                ':username' => $username,
            ]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception('Database error during update: ' . $e->getMessage());
        }
    }
    // Fetch user details by username
    public function getUserByUsername($username)
    {
        try {
            $sql = "SELECT username, email, password, role FROM user WHERE username = :username";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':username' => $username]);
            return $stmt->fetch(PDO::FETCH_ASSOC); // Returns user details or false if not found
        } catch (PDOException $e) {
            throw new Exception('Database error during fetching user: ' . $e->getMessage());
        }
    }
}