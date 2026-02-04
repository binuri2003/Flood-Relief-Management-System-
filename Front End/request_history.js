function loadRequestHistory() {
    const tableBody = document.getElementById("requestHistoryBody");
    const userID = document.getElementById("userIDInput").value.trim();

    if (!userID) {
        alert("Please enter your User ID.");
        return;
    }

    fetch(`getUserRequestHistory.php?userID=${userID}`)
        .then(response => response.json())
        .then(data => {

            tableBody.innerHTML = "";

            if (!data || data.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center">
                            No relief requests found for User ID ${userID}
                        </td>
                    </tr>
                `;
                return;
            }

            data.forEach(req => {
                tableBody.innerHTML += `
                    <tr>
                        <td>${req.request_id}</td>
                        <td>${req.relief_type}</td>
                        <td>${req.contact_name}</td>
                        <td>${req.contact_number}</td>
                        <td>${req.severity_level}</td>
                        <td>${req.family_members}</td>
                        <td>${req.description || 'None'}</td>
                    </tr>
                `;
            });
        })
        .catch(() => {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-danger">
                        Failed to load request history
                    </td>
                </tr>
            `;
        });
}
