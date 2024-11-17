<?php

require 'init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Assign default role
        $role = 'user';

        // Insert user into the database
        $sql = "INSERT INTO user (username, email, password, role) VALUES (:username, :email, :password, :role)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':role' => $role,
        ]);

        // Return success response
        echo json_encode(['status' => 'success', 'username' => $username]);
    } catch (PDOException $e) {
        // Handle database errors
        if ($e->getCode() === '23000') { // Duplicate entry (unique constraint violation)
            echo json_encode(['status' => 'error', 'message' => 'Username or email already exists']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } catch (Exception $e) {
        // Handle other errors
        echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred: ' . $e->getMessage()]);
    }
} else {
    // Handle invalid request method
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>