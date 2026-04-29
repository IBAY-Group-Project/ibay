document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("signup-form");

    form.addEventListener("submit", function (e) {

        const password = document.getElementById("password").value;
        const confirm = document.getElementById("confirmPassword").value;

        let errorMessage = "";

        if (password !== confirm) {
            errorMessage = "Passwords do not match";
        } 
        else if (password.length < 8) {
            errorMessage = "Password must be at least 8 characters long";
        } 
        else if (!/[A-Z]/.test(password)) {
            errorMessage = "Password must include at least one uppercase letter";
        } 
        else if (!/[a-z]/.test(password)) {
            errorMessage = "Password must include at least one lowercase letter";
        } 
        else if (!/[0-9]/.test(password)) {
            errorMessage = "Password must include at least one number";
        } 
        else if (!/[\W]/.test(password)) {
            errorMessage = "Password must include at least one special character";
        }

        if (errorMessage !== "") {
            e.preventDefault(); // stop form submission
            alert(errorMessage); // popup
        }
    });

});