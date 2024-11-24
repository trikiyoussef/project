function toggleForms() {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    loginForm.style.display = loginForm.style.display === 'none' ? 'block' : 'none';
    registerForm.style.display = registerForm.style.display === 'none' ? 'block' : 'none';
  }
  document.addEventListener('DOMContentLoaded', function () {
    const registerForm = document.getElementById('registerForm');
  
    // Email validation pattern
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const passwordPattern = /^(?=.*[A-Z])(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/;
  
    // Registration form validation
    if (registerForm) {
      registerForm.addEventListener('submit', function (event) {
        const usernameField = registerForm.querySelector('input[name="username"]');
        const emailField = registerForm.querySelector('#email');
        const passwordField = registerForm.querySelector('#password');
        const confirmPasswordField = registerForm.querySelector('#confirm-password');
  
        // Clear previous errors
        const usernameError = document.getElementById('username-error');
        const emailError = document.getElementById('email-error');
        const passwordError = document.getElementById('password-error');
        const confirmError = document.getElementById('confirm-error');
  
        usernameError.textContent = '';
        emailError.textContent = '';
        passwordError.textContent = '';
        confirmError.textContent = '';
  
        // Validate username
        if (!usernameField.value.trim()) {
          usernameError.textContent = 'Username is required.';
          usernameField.focus();
          event.preventDefault();
          return;
        }
  
        // Ensure username is at least 3 characters long
        if (usernameField.value.trim().length < 3) {
          usernameError.textContent = 'Username must be at least 3 characters long.';
          usernameField.focus();
          event.preventDefault();
          return;
        }
  
        // Validate email
        if (!emailPattern.test(emailField.value.trim())) {
          emailError.textContent = 'Invalid email address.';
          emailField.focus();
          event.preventDefault();
          return;
        }
  
          // Validate password strength
      const password = passwordField.value.trim();
      if (!passwordPattern.test(password)) {
        passwordError.textContent = 'Password must be at least 8 characters long, include at least one uppercase letter, and one special symbol.';
        passwordField.focus();
        event.preventDefault();
        return;
      }
        // Validate password confirmation
        if (passwordField.value.trim() !== confirmPasswordField.value.trim()) {
          confirmError.textContent = 'Passwords do not match.';
          confirmPasswordField.focus();
          event.preventDefault();
          return;
        }
      });
    }
  });
  