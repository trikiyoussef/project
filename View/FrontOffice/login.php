<?php

include(__DIR__ . '/../Controller/UserController.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'];

  if ($action === 'login') {
    login();
  } elseif ($action === 'register') {
    register(); 
  }
}

function login()
{
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  $controller = new UserController();
  $users = $controller->getUsers(); // Fetch all users
  foreach ($users as $user) {
    if ($user['username'] === $username && password_verify($password, $user['password'])) {
      // Successful login
      session_start();
      $_SESSION['id'] = $user['id'];
      //header('Location: ./index.php');
      echo '<script>
      alert("You are logged in successfully.");
      window.location.href = "./index.php";
    </script>';
      exit();
    }
  }

  // Login failed
  echo '<script>alert("Invalid username or password.");</script>';
}

function register()
{
  $username = $_POST['username'] ?? '';
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';
  $confirmPassword = $_POST['confirm-password'] ?? '';

  if ($password !== $confirmPassword) {
    echo '<script>alert("Passwords do not match."); window.history.back();</script>';
    return;
  }

  // Hash the password for security
  $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

  $user = new User(NULL,$username, $email, $hashedPassword, 'user');

  $controller = new UserController();
  $controller->addUser($user); // Add user using provided function

  echo '<script>alert("Registration successful! You can now log in."); window.location.href="./login.php";</script>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Heritage Tunisie - Login & Registration</title>
  <style>
    :root {
      --background-color: #000000;
      --default-color: #fafafa;
      --heading-color: #ffffff;
      --accent-color: #27a776;
      --surface-color: #1a1a1a;
      --contrast-color: #ffffff;
    }

    /* General styles */
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: var(--background-color);
      color: var(--default-color);
      flex-direction: column;
    }

    /* Website title */
    .site-title {
      font-size: 2.5rem;
      color: var(--heading-color);
      margin-bottom: 1rem;
    }

    /* Container for forms */
    .form-container {
      background-color: var(--surface-color);
      padding: 2rem;
      border-radius: 10px;
      width: 300px;
      text-align: center;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Headings */
    h2 {
      color: var(--heading-color);
      margin-bottom: 1rem;
    }

    /* Input fields */
    .form-container input[type="text"],
    .form-container input[type="password"],
    .form-container input[type="email"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: none;
      border-radius: 5px;
      background-color: var(--background-color);
      color: var(--default-color);
    }

    /* Error message */
    .error {
      color: red;
      font-size: 0.8rem;
      margin-top: -10px;
      margin-bottom: 10px;
    }

    /* Button */
    .form-container button {
      width: 100%;
      padding: 10px;
      background-color: var(--accent-color);
      border: none;
      border-radius: 5px;
      color: var(--contrast-color);
      font-weight: bold;
      cursor: pointer;
      margin-top: 1rem;
    }

    .form-container button:hover {
      background-color: #239366;
      /* Darken on hover */
    }

    /* Link to toggle forms */
    .form-container .toggle-link {
      display: block;
      color: var(--contrast-color);
      margin-top: 1rem;
      text-decoration: none;
    }

    .form-container .toggle-link:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <!-- Website Title -->
  <div class="site-title">Heritage Tunisie</div>

  <!-- Login Form -->
  <div class="form-container" id="login-form">
    <h2>Login</h2>
    <form id="loginForm" method="POST" action="login.php">
      <input type="hidden" name="action" value="login">
      <input type="text" id="username" name="username" placeholder="Username" required>
      <input type="password" id="password" name="password" placeholder="Password" required>

      <button type="submit">Login</button>
    </form>
    <a href="#" class="toggle-link" onclick="toggleForms()">Don't have an account? Register</a>
  </div>

  <!-- Registration Form -->
  <div class="form-container" id="register-form" style="display:none;">
    <h2>Register</h2>
    <form id="registerForm" method="POST" action="login.php">
      <!-- Username input -->
      <input type="hidden" name="action" value="register">
      <input type="text" name="username" placeholder="Username" required>

      <!-- Email input -->
      <input type="text" name="email" id="email" placeholder="Email" required>
      <span class="error" id="email-error"></span>

      <!-- Password input -->
      <input type="password" name="password" id="password" placeholder="Password (min 8 characters)" required>
      <span class="error" id="password-error"></span>

      <!-- Confirm password input -->
      <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm Password" required>
      <span class="error" id="confirm-error"></span>

      <!-- Submit button -->
      <button type="submit" name="register">Register</button>
    </form>
    <a href="#" class="toggle-link" onclick="toggleForms()">Already have an account? Login</a>
  </div>
  <script src="login.js"></script>

</body>

</html>