// LOAD REQUEST HISTORY
function loadRequestHistory() {
    const tableBody = document.getElementById("requestHistoryBody");
    const userID = document.getElementById("userIDInput").value.trim();

    if (!userID) {
        alert("Please enter your User ID.");
        return;
    }

    fetch(`request_history.php?userID=${userID}`)
        .then(response => response.json())
        .then(data => {
            tableBody.innerHTML = "";

            if (!data || data.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="10" class="text-center">No relief requests found for User ID ${userID}</td>
                    </tr>
                `;
                return;
            }

            data.forEach(req => {
                const row = document.createElement("tr");

                row.innerHTML = `
                    <td>${req.user_id}</td>
                    <td>${req.request_id}</td>
                    <td>${req.relief_type}</td>
                    <td>${req.contact_name}</td>
                    <td>${req.contact_number || 'N/A'}</td>
                    <td>${req.severity_level}</td>
                    <td>${req.family_members}</td>
                    <td>${req.address}</td>
                    <td>${req.description || 'None'}</td>
                    <td>
                        <button class="btn btn-sm btn-primary me-1" onclick="editRequest(${req.request_id})">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteRequest(${req.request_id}, this)">Delete</button>
                    </td>
                `;

                tableBody.appendChild(row);
            });
        })
        .catch(() => {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="10" class="text-center text-danger">Failed to load request history</td>
                </tr>
            `;
        });
}

// DELETE REQUEST
function deleteRequest(requestID, btn) {
    if (!confirm("Do you really want to delete this request?")) return;

    fetch("request_history.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ requestID })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const row = btn.closest("tr");
            row.remove();
            alert("Request successfully deleted.");
        } else {
            alert("Failed to delete request: " + (data.message || ""));
        }
    })
    .catch(() => alert("Error deleting request."));
}

// EDIT REQUEST WITH CONFIRMATION
function editRequest(requestID) {
    const confirmEdit = confirm("Are you really want to edit this request?");
    if (!confirmEdit) return; // stay on the page if user clicks "No"

    // Redirect to request_form.html with requestID and userID so you can pre-fill the form
    const userID = document.getElementById("userIDInput").value.trim();
    window.location.href = `request_form.html?requestID=${requestID}&userID=${userID}`;
}
