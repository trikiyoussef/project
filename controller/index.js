
    document.addEventListener("DOMContentLoaded", () => {
    const storedUsername = localStorage.getItem("username");
    const userInfo = document.getElementById("userInfo");
    const usernameDisplay = document.getElementById("usernameDisplay");
    const loginButton = document.getElementById("loginButton");
    console.log(storedUsername);
    if (storedUsername) {
      // User is logged in
      usernameDisplay.textContent = storedUsername;
      userInfo.style.display = "flex"; // Show avatar and sign-out button
      loginButton.style.display = "none"; // Hide login button
    } else {
      // User is not logged in
      userInfo.style.display = "none";
      loginButton.style.display = "block";
    }
  
    // Handle sign-out
    document.getElementById("signOutButton").addEventListener("click", () => {
      localStorage.removeItem("username"); // Remove username from localStorage
      window.location.reload(); // Refresh the page to reset UI
    });
  });
  document.getElementById("avatar").addEventListener("click", () => {
  window.location.href = "./view/user.html";
});
  