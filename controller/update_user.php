<?php

require 'init.php'; // Include your database connection setup

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate and sanitize inputs
        if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'All fields (username, email, password) are required']);
            exit;
        }

        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $password = $_POST['password']; // Do not hash until validation is complete

        if (!$email) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
            exit;
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Update user details in the database
        $sql = "UPDATE user SET email = :email, password = :password WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([
            ':email' => $email,
            ':password' => $hashedPassword,
            ':username' => $username,
        ]);

        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'User details updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update user details']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
} catch (PDOException $e) {
    // Log PDOException details for debugging (avoid exposing sensitive data in production)
    error_log("Database Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'A database error occurred. Please try again later.']);
} catch (Exception $e) {
    // Log generic exceptions for debugging
    error_log("Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred: ' . $e->getMessage()]);
}
?>