<?php
session_start();
include(__DIR__ . '/../../Controller/UserController.php');

$controller = new UserController();

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
  header('Location: login.php'); // Redirect to login if not authenticated
  exit();
}

// Fetch user information
$id = $_SESSION['id'];
$user = $controller->getUser($id);

// Check if the logged-in user is an admin
$isAdmin = $user['role'] === 'admin';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['update'])) {
    // Update user information
    $updatedUsername = $_POST['username'];
    $updatedEmail = $_POST['email'];
    $updatedPassword = $_POST['password'];

    // Update the user object
    $userObj = new User(
      $user['id'],
      $updatedUsername,
      $updatedEmail,
      $updatedPassword ? password_hash($updatedPassword, PASSWORD_BCRYPT) : $user['password'],
      $user['role']
    );

    // Update in the database
    $controller->updateUser($userObj, $user['id']);
    $message = "Profile updated successfully!";
    $user = $controller->getUser($id); // Fetch updated info
  } elseif (isset($_POST['delete']) && $isAdmin) {
    // Delete a user (admin only)
    $userIdToDelete = $_POST['user_id'];
    $controller->deleteUser($userIdToDelete);
    $message = "User deleted successfully!";
  }
}

// Fetch all users if admin
$allUsers = $isAdmin ? $controller->getUsers() : [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
  <style>
    /* General Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #f3f4f6, #e8eaf6);
      color: #333;
      line-height: 1.6;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }

    .profile-container,
    .dashboard {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      padding: 30px;
      width: 100%;
      max-width: 500px;
      margin: 20px auto;
    }

    .dashboard {
      max-width: 800px;
    }

    h1, h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #444;
      font-weight: 600;
    }

    label {
      display: block;
      font-weight: 500;
      margin-bottom: 8px;
      color: #555;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px 15px;
      margin-bottom: 20px;
      border: 1px solid #ddd;
      border-radius: 8px;
      background: #f9f9f9;
      transition: border-color 0.3s ease, background-color 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
      border-color: #6c63ff;
      background: #fff;
      outline: none;
    }

    button[type="submit"],
    .btn-danger {
      display: block;
      width: 100%;
      background: linear-gradient(135deg, #6c63ff, #845ec2);
      color: #fff;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.2s ease;
      margin-top: 10px;
      text-align: center;
    }

    button[type="submit"]:hover,
    .btn-danger:hover {
      background: linear-gradient(135deg, #5a54d8, #6c63ff);
      transform: translateY(-2px);
    }

    .btn-danger {
      background: linear-gradient(135deg, #ff6f6f, #d9534f);
      width: auto;
      padding: 8px 12px;
      display: inline-block;
    }

    .btn-danger:hover {
      background: linear-gradient(135deg, #ff4c4c, #d32f2f);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table thead {
      background: linear-gradient(135deg, #6c63ff, #845ec2);
      color: white;
      border-radius: 8px;
    }

    table thead th {
      padding: 15px;
      text-align: left;
      font-weight: 500;
    }

    table tbody tr {
      background: #f9f9f9;
      transition: background 0.3s ease;
    }

    table tbody tr:nth-child(even) {
      background: #f4f4f9;
    }

    table tbody tr:hover {
      background: #ecebff;
    }

    table td {
      padding: 12px;
      text-align: left;
      color: #555;
    }

    .alert {
      padding: 10px 15px;
      background: #e8f5e9;
      border-left: 5px solid #66bb6a;
      border-radius: 8px;
      margin: 15px 0;
      color: #2e7d32;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .profile-container,
      .dashboard {
        width: 90%;
      }

      table th,
      table td {
        font-size: 14px;
      }
    }
  </style>
</head>

<body>
  <div class="profile-container">
    <h1>User Profile</h1>
    <?php if (isset($message)): ?>
      <div class="alert">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>
    <form id="userForm" method="POST" action="user.php">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" placeholder="Enter a new password (optional)">

      <button type="submit" name="update">Save</button>
    </form>
  </div>

  <?php if ($isAdmin): ?>
    <div class="dashboard" id="dashboard">
      <h2>Admin Dashboard</h2>
      <table id="userTable">
        <thead>
          <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($allUsers as $user): ?>
            <tr>
              <td>
                <?= htmlspecialchars($user['username']) ?>
              </td>
              <td>
                <?= htmlspecialchars($user['email']) ?>
              </td>
              <td>
                <?= htmlspecialchars($user['role']) ?>
              </td>
              <td>
                <form method="POST" action="user.php" style="display:inline;">
                  <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                  <button type="submit" name="delete" class="btn-danger">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</body>

</html>
