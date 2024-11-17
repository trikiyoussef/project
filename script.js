function toggleForms() {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    loginForm.style.display = loginForm.style.display === 'none' ? 'block' : 'none';
    registerForm.style.display = registerForm.style.display === 'none' ? 'block' : 'none';
  }
  
  document.getElementById('loginForm').addEventListener('submit', async function (event) {
    event.preventDefault(); // Prevent default form submission
  
    const formData = new FormData(this);
  
    try {
      // Send the form data to login.php
      const response = await fetch('login.php', {
        method: 'POST',
        body: formData
      });
      console.log(response);
  
      const result = await response.json();
  
      if (result.status === 'success') {
        // Save username in local storage
        localStorage.setItem('username', result.username);
  
        // Redirect to the homepage
        window.location.href = "index.html";
      } else {
        // Show an error message
        alert(result.message || 'Login failed. Please try again.');
      }
    } catch (error) {
      alert('An unexpected error occurred. Please try again.' + error);
    }
  });
  
  document.getElementById("registerForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
  
    // Clear previous error messages
    document.getElementById("email-error").textContent = "";
    document.getElementById("password-error").textContent = "";
    document.getElementById("confirm-error").textContent = "";
  
    // Get values from the form
    const email = formData.get("email");
    const password = formData.get("password");
    const confirmPassword = formData.get("confirm-password");
  
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
  
    if (isValid) {
  
      try {
        const response = await fetch("register.php", {
          method: "POST",
          body: formData,
        });
        console.log(response);
        const data = await response.json();
        console.log(data);
        if (data.status === "success") {
          localStorage.setItem("username", data.username);
          window.location.href = "index.html";
        } else {
          alert("Registration failed. Please try again.");
        }
      } catch (error) {
        alert('An unexpected error occurred. Please try again.' + error);
      }
    }
  });
 