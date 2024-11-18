<?php
require '../config.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // Decode the JSON input
    $input = json_decode(file_get_contents("php://input"), true);

    // Check if 'username' is provided
    if (!isset($input['username']) || empty($input['username'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input: username is required']);
        exit;
    }

    $username = $input['username'];

    // Prepare and execute the delete query
    $sql = "DELETE FROM user WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'User deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }
} catch (PDOException $e) {
    // Log the exception for debugging
    error_log('Database Error: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'A database error occurred. Please try again later.']);
} catch (Exception $e) {
    // Log the exception for debugging
    error_log('General Error: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred.']);
}
?>