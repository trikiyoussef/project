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

// Default section
$activeSection = isset($_GET['section']) ? $_GET['section'] : 'dashboard';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['delete']) && $isAdmin) {
    // Delete a user (admin only)
    $userIdToDelete = $_POST['user_id'];
    $controller->deleteUser($userIdToDelete);
    $message = "User deleted successfully!";
    $activeSection = 'community';
  } elseif (isset($_POST['make_admin']) && $isAdmin) {
    // Promote the user to admin
    $userIdToUpdate = $_POST['user_id'];
    $userToUpdate = $controller->getUser($userIdToUpdate);
    $userToUpdate['role'] = 'admin'; // Change role to admin

    // Update the user in the database
    $updatedUser = new User(
      $userToUpdate['id'],
      $userToUpdate['username'],
      $userToUpdate['email'],
      $userToUpdate['password'], // Keep the existing password
      $userToUpdate['role'] // New role
    );
    $controller->updateUser($updatedUser, $userToUpdate['id']);
    $message = "User promoted to admin!";
    $activeSection = 'community';
  } elseif (isset($_POST['add_user']) && $isAdmin) {
    // Add a new user
    $newUsername = $_POST['new_username'];
    $newEmail = $_POST['new_email'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $newRole = $_POST['new_role'];

    $newUser = new User(null, $newUsername, $newEmail, $newPassword, $newRole);
    $controller->addUser($newUser);
    $message = "New user added successfully!";

  } elseif (isset($_POST['edit_user']) && $isAdmin) {
    // Edit user details (admin only)
    $userIdToEdit = $_POST['user_id'];
    $newUsername = $_POST['edit_username'];
    $newEmail = $_POST['edit_email'];

    // Fetch the user to edit
    $userToEdit = $controller->getUser($userIdToEdit);
    $updatedUser = new User(
      $userToEdit['id'],
      $newUsername,
      $newEmail,
      $userToEdit['password'], // Keep the existing password
      $userToEdit['role'] // Keep the existing role
    );
    $controller->updateUser($updatedUser, $userIdToEdit);
    $message = "User updated successfully!";
    $activeSection = 'community';
  } elseif (isset($_POST['update_profile'])) {
    // Collect new data
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];
    $newPassword = $_POST['password']; // This can be empty

    // Validate input
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
      $message = "Invalid email format.";
    } else {
      // Fetch current user
      $currentUser = $controller->getUser($id);

      // Update details
      $updatedUser = new User(
        $currentUser['id'],
        $newUsername,
        $newEmail,
        $newPassword ? password_hash($newPassword, PASSWORD_BCRYPT) : $currentUser['password'], // Hash new password if provided
        $currentUser['role'] // Keep role unchanged
      );

      // Save updated user
      $controller->updateUser($updatedUser, $id);
      $message = "Profile updated successfully!";
    }

    // Reload dashboard section
    $activeSection = 'dashboard';

  }


  // Redirect to the active section after form submission
  header("Location: index.php?section=$activeSection");
  exit();
}

// Fetch all users if admin
$allUsers = $isAdmin ? $controller->getUsers() : [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Heritage Tunisie Admin Dashboard</title>
  <style>
  body {
    display: flex;
    min-height: 100vh;
    background-color: #000;
    color: #fff;
    font-family: Arial, sans-serif;
  }

  .sidebar {
    width: 250px;
    background-color: #121212;
    padding: 20px 0;
    display: flex;
    flex-direction: column;
  }

  .sidebar a {
    color: #fff;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 5px;
    margin: 5px 10px;
    cursor: pointer;
  }

  .sidebar a.active {
    background-color: #16a085;
  }

  .main-content {
    flex: 1;
    padding: 20px;
  }

  .section {
    display: none;
  }

  .section.active {
    display: block;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #222;
    color: #fff;
    border-radius: 8px;
    overflow: hidden;
  }

  table th,
  table td {
    padding: 10px;
    border: 1px solid #333;
    text-align: left;
  }

  table th {
    background-color: #16a085;
    color: #fff;
  }

  table input[type="text"],
  table input[type="email"] {
    width: calc(100% - 20px); /* Ensure it fits within cell padding */
    padding: 8px 10px;
    background-color: #121212;
    color: #fff;
    border: 1px solid #444;
    border-radius: 4px;
    max-width: 300px; /* Limit width */
  }

  table input[type="text"]:focus,
  table input[type="email"]:focus {
    border-color: #16a085;
    outline: none;
  }

  button {
    padding: 5px 10px;
    background-color: #e74c3c;
    color: #fff;
    border: none;
    cursor: pointer;
    border-radius: 5px;
  }

  button:hover {
    background-color: #c0392b;
  }

  form.add-user {
    margin-top: 20px;
    background-color: #121212;
    padding: 20px;
    border-radius: 8px;
  }

  form.add-user input,
  form.add-user select,
  form.add-user button {
    margin: 10px 0;
    padding: 10px;
    width: calc(100% - 20px); /* Fit the container with padding */
    max-width: 500px; /* Prevent excessive stretching */
    border-radius: 5px;
    border: 1px solid #333;
    background-color: #222;
    color: #fff;
  }

  form.add-user button {
    background-color: #16a085;
  }

  form.add-user button:hover {
    background-color: #13a57b;
  }

  .profile-container {
    background: #121212;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
    padding: 30px;
    width: 100%;
    max-width: 500px;
    margin: 20px auto;
  }

  .profile-container h1 {
    color: #16a085;
    margin-bottom: 20px;
  }

  label {
    display: block;
    font-weight: 500;
    margin-bottom: 8px;
    color: #fff; /* Make labels white */
  }

  input[type="text"],
  input[type="email"],
  input[type="password"] {
    width: calc(100% - 20px); /* Fit within the container with padding */
    max-width: 500px; /* Prevent excessive stretching */
    padding: 10px 15px;
    margin-bottom: 20px;
    border: 1px solid #333;
    border-radius: 8px;
    background: #1e1e1e;
    color: #fff;
    transition: border-color 0.3s ease, background-color 0.3s ease;
  }

  input[type="text"]:focus,
  input[type="email"]:focus,
  input[type="password"]:focus {
    border-color: #16a085;
    background: #2c2c2c;
    outline: none;
  }

  button[type="submit"] {
    display: block;
    width: 100%;
    background: linear-gradient(135deg, #16a085, #13a57b);
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

  button[type="submit"]:hover {
    background: linear-gradient(135deg, #13a57b, #16a085);
    transform: translateY(-2px);
  }

  .alert {
    background-color: #e74c3c;
    color: #fff;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
    text-align: center;
}
</style>

</head>

<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Heritage Tunisie</h2>
    <a href="index.php?section=dashboard" class="<?= $activeSection === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
    <a href="index.php?section=community" class="<?= $activeSection === 'community' ? 'active' : '' ?>">Community
      Management</a>
    <a href="index.php?section=sites" class="<?= $activeSection === 'sites' ? 'active' : '' ?>">Heritage Sites</a>
    <a href="index.php?section=events" class="<?= $activeSection === 'events' ? 'active' : '' ?>">Cultural Events</a>
    <a href="index.php?section=articles" class="<?= $activeSection === 'articles' ? 'active' : '' ?>">Articles &
      Resources</a>
    <a href="index.php?section=purchases" class="<?= $activeSection === 'purchases' ? 'active' : '' ?>">Purchases</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Sections -->
    <div id="dashboard" class="section <?= $activeSection === 'dashboard' ? 'active' : '' ?>">
      <h2>Dashboard Overview</h2>
      <p>Welcome to the Heritage Tunisie admin dashboard. Select a section to begin managing data.</p>

      <!-- User Profile Section -->
      <div class="profile-container">
        <h1>User Profile</h1>
        <?php if (isset($message)): ?>
          <div class="alert">
            <?= htmlspecialchars($message) ?>
          </div>
        <?php endif; ?>
        <form id="userForm" method="POST" action="index.php?section=dashboard">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

          <label for="email">Email:</label>
          <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

          <label for="password">Password:</label>
          <input type="password" id="password" name="password" placeholder="Enter a new password (optional)">

          <button type="submit" name="update_profile">Save</button>
        </form>
      </div>
    </div>
    <div id="community" class="section <?= $activeSection === 'community' ? 'active' : '' ?>">
      <h2>Community Management</h2>
      <?php if ($isAdmin): ?>
        <table>
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
              <?php if ($user['id'] == $id)
                continue; // Skip the logged-in user ?>
              <tr>
                <form method="POST" action="index.php">
                  <td>
                    <input type="text" name="edit_username" value="<?= htmlspecialchars($user['username']) ?>" required>
                  </td>
                  <td>
                    <input type="email" name="edit_email" value="<?= htmlspecialchars($user['email']) ?>" required>
                  </td>
                  <td><?= htmlspecialchars($user['role']) ?></td>
                  <td>
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <button type="submit" name="edit_user">Save</button>
                    <?php if ($user['role'] !== 'admin'): ?>
                      <button type="submit" name="make_admin">Make Admin</button>
                    <?php endif; ?>
                    <button type="submit" name="delete">Delete</button>
                  </td>
                </form>
              </tr>
            <?php endforeach;
            ?>
          </tbody>

        </table>

        <!-- Add New User Form -->
        <form method="POST" action="index.php?section=community" class="add-user">
          <h3>Add New User</h3>
          <input type="text" name="new_username" placeholder="Username" required>
          <input type="email" name="new_email" placeholder="Email" required>
          <input type="password" name="new_password" placeholder="Password" required>
          <select name="new_role" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
          </select>
          <button type="submit" name="add_user">Add User</button>
        </form>
      <?php else: ?>
        <p>You do not have access to manage community members.</p>
      <?php endif; ?>
    </div>
    <div id="sites" class="section <?= $activeSection === 'sites' ? 'active' : '' ?>">
      <h2>Heritage Sites</h2>
      <p>Maintain and update information about Tunisian historical sites and landmarks.</p>
    </div>
    <div id="events" class="section <?= $activeSection === 'events' ? 'active' : '' ?>">
      <h2>Cultural Events</h2>
      <p>Manage cultural event schedules and details to engage the community.</p>
    </div>
    <div id="articles" class="section <?= $activeSection === 'articles' ? 'active' : '' ?>">
      <h2>Articles & Resources</h2>
      <p>Create, edit, or manage articles, blogs, and other informational content.</p>
    </div>
    <div id="purchases" class="section <?= $activeSection === 'purchases' ? 'active' : '' ?>">
      <h2>Purchases</h2>
      <p>Track and manage user purchases or orders related to cultural products.</p>
    </div>
  </div>

  <script>
    // Sidebar navigation
    const navLinks = document.querySelectorAll('.sidebar a');
    const sections = document.querySelectorAll('.section');

    navLinks.forEach(link => {
      link.addEventListener('click', function () {
        // Remove active class from all links and hide all sections
        navLinks.forEach(nav => nav.classList.remove('active'));
        sections.forEach(section => section.classList.remove('active'));

        // Add active class to the clicked link and show the corresponding section
        this.classList.add('active');
        const target = this.getAttribute('href').split('=')[1];
        document.getElementById(target).classList.add('active');
      });
    });
  </script>
</body>

</html>