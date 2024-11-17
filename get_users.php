<?php
require 'init.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // Fetch all users from the database
    $sql = "SELECT username, email, role FROM user";
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($users) {
        echo json_encode(['status' => 'success', 'users' => $users]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No users found']);
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