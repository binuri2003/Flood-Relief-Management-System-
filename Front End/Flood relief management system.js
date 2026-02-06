window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.get('status') === 'success') {
        const notify = document.getElementById('notification-box');
        if (notify) {
            notify.style.display = 'block';
            setTimeout(() => { 
                notify.style.transition = "opacity 0.5s ease";
                notify.style.opacity = "0";
                setTimeout(() => { notify.style.display = 'none'; }, 500);
            }, 5000);
        }
    }

    if (urlParams.get('status') === 'error') {
        const errorNotify = document.getElementById('error-box');
        if (errorNotify) { errorNotify.style.display = 'block'; }
    }

    if (urlParams.get('status') === 'exists') {
        const duplicateBox = document.getElementById('duplicate-error');
        if (duplicateBox) { 
            duplicateBox.style.display = 'block'; 
        }
    }
};

function validateAuth() {
    const pass1 = document.getElementById("pass1").value;
    const pass2 = document.getElementById("pass2").value;
    if (pass1 !== pass2) {
        alert("Passwords do not match.");
        return false; 
    }
    return true; 
}