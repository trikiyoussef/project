function toggleForms() {
  const loginForm = document.getElementById('login-form');
  const registerForm = document.getElementById('register-form');
  loginForm.style.display = loginForm.style.display === 'none' ? 'block' : 'none';
  registerForm.style.display = registerForm.style.display === 'none' ? 'block' : 'none';
}

function validateForm() {
  // Clear previous error messages
  document.getElementById("email-error").textContent = "";
  document.getElementById("password-error").textContent = "";
  document.getElementById("confirm-error").textContent = "";

  // Get values from the form
  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;
  const confirmPassword = document.getElementById("confirm-password").value;

  let isValid = true;

  // Validate email
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email)) {
    document.getElementById("email-error").textContent = "Invalid email format";
    isValid = false;
  }

  // Validate password length
  if (password.length < 8) {
    document.getElementById("password-error").textContent = "Password must be at least 8 characters";
    isValid = false;
  }

  // Validate password match
  if (password !== confirmPassword) {
    document.getElementById("confirm-error").textContent = "Passwords do not match";
    isValid = false;
  }

  // If form is valid, switch to the login page
  if (isValid) {
    alert("Registration successful!");
    toggleForms();  // Automatically go to the login page
  }
}
