document.addEventListener("DOMContentLoaded", () => {
    const username = localStorage.getItem("username");
  
    if (!username) {
      alert("No user logged in. Redirecting to login page.");
      window.location.href = "../view/login.html";
      return;
    }
  
    // Fetch user details, including the role
    fetch(`../controller/read_user.php?username=${username}`)
      .then(response => response.json())
      .then(data => {
        if (data.status === "success") {
          const user = data.user;
          document.getElementById("username").value = user.username;
          document.getElementById("email").value = user.email;
          console.log(user);
          // Check if the user is an admin
          if (user.role === "admin") {
            document.getElementById("dashboard").style.display = "block";
  
            // Fetch all users for the admin dashboard
            fetch("../controller/get_users.php")
              .then(response => response.json())
              .then(data => {
                if (data.status === "success") {
                  const userTable = document.getElementById("userTable").querySelector("tbody");
                  userTable.innerHTML = "";
  
                  data.users.forEach(user => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                      <td>${user.username}</td>
                      <td>${user.email}</td>
                      <td>${user.role}</td>
                      <td><button data-username="${user.username}" class="deleteButton">Delete</button></td>
                    `;
                    userTable.appendChild(row);
                  });
  
                  document.querySelectorAll(".deleteButton").forEach(button => {
                    button.addEventListener("click", () => {
                      const usernameToDelete = button.getAttribute("data-username");
                      if (confirm(`Are you sure you want to delete ${usernameToDelete}?`)) {
                        fetch("../controller/delete_user.php", {
                          method: "POST",
                          headers: { "Content-Type": "application/json" },
                          body: JSON.stringify({ username: usernameToDelete }),
                        })
                          .then(response => response.json())
                          .then(data => {
                            if (data.status === "success") {
                              alert("User deleted successfully.");
                              button.closest("tr").remove();
                            } else {
                              alert(data.message || "Failed to delete user.");
                            }
                          })
                          .catch(error => alert("An error occurred: " + error));
                      }
                    });
                  });
                } else {
                  alert(data.message || "Failed to fetch users.");
                }
              })
              .catch(error => alert("An error occurred: " + error));
          }
        } else {
          alert(data.message || "Failed to fetch user details.");
        }
      })
      .catch(error => alert("An error occurred: " + error));
  });
  // Handle form submission for updating user details
  document.getElementById("userForm").addEventListener("submit", (event) => {
      event.preventDefault();
  
      const formData = new FormData(event.target);
  
      // Include the username in the request (read-only field in the form)
      formData.append("username", document.getElementById("username").value);
  
      fetch("../controller/update_user.php", {
        method: "POST",
        body: formData,
      })
        .then(response => response.json())
        .then(data => {
          const message = document.getElementById("message");
          if (data.status === "success") {
            message.textContent = "User details updated successfully";
            message.style.color = "green";
          } else {
            message.textContent = data.message || "Failed to update user details.";
            message.style.color = "#f44336"; // Red for error
          }
        })
        .catch(error => {
          const message = document.getElementById("message");
          message.textContent = "An error occurred: " + error;
          message.style.color = "#f44336"; // Red for error
        });
    });