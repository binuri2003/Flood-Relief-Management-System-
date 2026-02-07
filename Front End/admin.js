window.onload = function() {
    loadUsers();
};

function showSection(sectionId) {
    document.getElementById("users").style.display = "none";
    document.getElementById("reports").style.display = "none";
    document.getElementById(sectionId).style.display = "block";

    if (sectionId === 'users') loadUsers();
    if (sectionId === 'reports') loadSummary();
}

function loadUsers() {
    fetch('admin_backenddev.php')
    .then(response => response.json())
    .then(users => {
        let tableBody = document.querySelector("#users table");
        tableBody.innerHTML = `<tr><th>ID</th><th>Name</th><th>User Name</th><th>Role</th><th>Action</th></tr>`;
        
        users.forEach(user => {
            tableBody.innerHTML += `<tr>
                <td>${user.UserID}</td>
                <td style="cursor:pointer; color:blue;" onclick="viewUserDetails(${user.UserID})">
                    ${user.FirstName} ${user.LastName}
                </td>
                <td>${user.UserName}</td>
                <td>${user.Role}</td>
                <td><button onclick="confirmDelete(${user.UserID})">Delete</button></td>
            </tr>`;
        });
    });
}

function viewUserDetails(id) {
    fetch(`admin_backenddev.php?userId=${id}`)
    .then(response => response.json())
    .then(user => {
        alert("USER DETAILED REPORT\n" + 
              "----------------------\n" +
              "Full Name: " + user.FirstName + " " + user.LastName + "\n" +
              "Address: " + user.Address + "\n" +
              "Username: " + user.UserName + "\n" +
              "User Role: " + user.Role);
    });
}

function loadSummary() {
    let areaValue = document.getElementById("areaFilter").value;
    let reliefValue = document.getElementById("reliefFilter").value;

    fetch(`admin_backenddev.php?action=summary&area=${areaValue}&reliefType=${reliefValue}`)
    .then(response => response.json())
    .then(data => {
        document.getElementById("summaryValues").innerHTML = `
            <li>Total Registered Users: <strong>${data.totalUsers}</strong></li>
            <li>High Severity Households: <strong>${data.highSeverity}</strong></li>
            <li>${data.typeName} Requests: <strong>${data.reliefCount}</strong></li>
        `;
    });
}
function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this user?")) {
        fetch(`admin_backenddev.php?deleteId=${id}`, {
            method: 'DELETE'
        })
        .then(response => {
            alert("User removed from database");
            loadUsers();
        })
        .catch(err => console.log("Error deleting", err));
    }
}

function logout() {
    alert("Logged out successfully");
    window.location.href = "login.html";
}
