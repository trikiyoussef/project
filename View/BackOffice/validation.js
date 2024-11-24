document.addEventListener("DOMContentLoaded", () => {
    // Validation functions
    const validateUsername = (username) => {
      return username.length >= 3;
    };
  
    const validatePassword = (password) => {
      const hasUppercase = /[A-Z]/.test(password);
      const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
      return password.length >= 8 && hasUppercase && hasSpecialChar;
    };
  
    const validateEmail = (email) => {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
    };
  
    // Validate form on submit
    document.querySelectorAll("form").forEach((form) => {
      form.addEventListener("submit", (e) => {
        const usernameInput = form.querySelector('input[name="new_username"], input[name="edit_username"], input[name="username"]');
        const passwordInput = form.querySelector('input[name="new_password"], input[name="password"]');
        const emailInput = form.querySelector('input[name="new_email"], input[name="edit_email"], input[name="email"]');
  
        let valid = true;
        let errorMessage = "";
  
        // Validate username
        if (usernameInput && !validateUsername(usernameInput.value)) {
          valid = false;
          errorMessage += "Username must be at least 3 characters long.\n";
        }
  
        // Validate email
        if (emailInput && !validateEmail(emailInput.value)) {
          valid = false;
          errorMessage += "Please provide a valid email address.\n";
        }
  
        // Validate password (only when provided)
        if (passwordInput && passwordInput.value && !validatePassword(passwordInput.value)) {
          valid = false;
          errorMessage += "Password must be at least 8 characters long, contain an uppercase letter, and a special character.\n";
        }
  
        // Stop form submission if invalid
        if (!valid) {
          e.preventDefault();
          alert(errorMessage.trim());
        }
      });
    });
  });
  