<?php
require '../config.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate that required fields are present
        if (empty($_POST['username']) || empty($_POST['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Username and password are required']);
            exit;
        }

        $username = $_POST['username'];
        $password = $_POST['password'];

        // Check if user exists
        $sql = "SELECT * FROM user WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Success response
            echo json_encode(['status' => 'success', 'username' => $username]);
        } else {
            // Invalid credentials
            echo json_encode(['status' => 'error', 'message' => 'Invalid username or password']);
        }
    } else {
        // Handle invalid request method
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
} catch (PDOException $e) {
    // Handle database connection/query errors
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    // Handle general errors
    echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred: ' . $e->getMessage()]);
}
?>