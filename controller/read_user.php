<?php
header('Content-Type: application/json; charset=utf-8');

require 'init.php'; // Include your database connection setup

try {
    if (isset($_GET['username'])) {
        $username = $_GET['username'];

        // Fetch user details from the database
        $sql = "SELECT username, email, password, role FROM user WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo json_encode(['status' => 'success', 'user' => $user]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Username is required']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
}
?>