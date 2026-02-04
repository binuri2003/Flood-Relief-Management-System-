document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('reliefForm');
    const container = document.querySelector('.container');

    const urlParams = new URLSearchParams(window.location.search);
    const requestID = urlParams.get('requestID'); 
    const userIDFromURL = urlParams.get('userID');

    if (requestID) {
        fetch(`request_history.php?userID=${userIDFromURL}`)
            .then(res => res.json())
            .then(data => {
                const request = data.find(r => r.request_id == requestID);
                if (request) {
                    document.getElementById('userID').value = request.user_id;
                    document.getElementById('userName').value = request.contact_name;
                    document.getElementById('contactPerson').value = request.contact_name;
                    document.getElementById('address').value = request.address;
                    document.getElementById('familyMembers').value = request.family_members;
                    document.getElementById('reliefType').value = request.relief_type;
                    document.getElementById('description').value = request.description || '';

                    const numbers = request.contact_number ? request.contact_number.split(',') : [];
                    document.getElementById('contactNumber1').value = numbers[0] || '';
                    document.getElementById('contactNumber2').value = numbers[1] || '';
                }
            });
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const userID = document.getElementById('userID').value.trim();
        const userName = document.getElementById('userName').value.trim();
        const contactPerson = document.getElementById('contactPerson').value.trim();
        const contactNumber1 = document.getElementById('contactNumber1').value.trim();
        const contactNumber2 = document.getElementById('contactNumber2').value.trim();
        const address = document.getElementById('address').value.trim();
        const familyMembers = document.getElementById('familyMembers').value.trim();
        const reliefType = document.getElementById('reliefType').value;
        const district = document.getElementById('district').value.trim();
        const divSec = document.getElementById('divSec').value.trim();
        const gnDivision = document.getElementById('gnDivision').value.trim();
        const floodLevel = document.querySelector('input[name="floodLevel"]:checked')?.value;
        const description = document.getElementById('description').value.trim();

        if (!userID || !userName || !contactPerson || !address || !familyMembers || !reliefType || !district || !divSec || !gnDivision || !floodLevel) {
            showAlert('Please fill all required fields!', 'danger');
            return;
        }

        if (!contactNumber1 && !contactNumber2) {
            showAlert('Please provide at least one contact number!', 'danger');
            return;
        }

        const formData = new FormData();
        if (requestID) formData.append('requestID', requestID); 
        formData.append('userID', userID);
        formData.append('userName', userName);
        formData.append('contactPerson', contactPerson);
        formData.append('contactNumber1', contactNumber1);
        formData.append('contactNumber2', contactNumber2);
        formData.append('address', address);
        formData.append('familyMembers', familyMembers);
        formData.append('reliefType', reliefType);
        formData.append('district', district);
        formData.append('divSec', divSec);
        formData.append('gnDivision', gnDivision);
        formData.append('floodLevel', floodLevel);
        formData.append('description', description);

        try {
            const response = await fetch('request_form.php', { method: 'POST', body: formData });
            const result = await response.json();

            if (result.status === 'success') {
                showAlert(`Your relief request has been ${requestID ? 'updated' : 'created'} successfully!`, 'success');
                form.reset();
            } else {
                showAlert(result.message || 'Something went wrong!', 'danger');
            }
        } catch (err) {
            console.error(err);
            showAlert('Error submitting form!', 'danger');
        }
    });

    function showAlert(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show mt-3`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `${message} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        container.prepend(alertDiv);
    }
});
