// Toggle password visibility functions
function togglePasswordVisibility(passwordField, toggleIcon) {
    const type =
        passwordField.getAttribute("type") === "password" ? "text" : "password";
    passwordField.setAttribute("type", type);
    toggleIcon.textContent = type === "password" ? "👁️" : "👁️‍🗨️";
}

// Password field and toggle
const password = document.getElementById("password");
const togglePassword = document.getElementById("togglePassword");

togglePassword.addEventListener("click", function () {
    togglePasswordVisibility(password, this);
});

// Confirm password field and toggle
const confirmPassword = document.getElementById("confirmPassword");
const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");

toggleConfirmPassword.addEventListener("click", function () {
    togglePasswordVisibility(confirmPassword, this);
});

// Show all passwords checkbox
const showPasswordsCheckbox = document.getElementById("showPasswords");

showPasswordsCheckbox.addEventListener("change", function () {
    const type = this.checked ? "text" : "password";

    // Update password field
    password.setAttribute("type", type);
    togglePassword.textContent = type === "password" ? "👁️" : "👁️‍🗨️";

    // Update confirm password field
    confirmPassword.setAttribute("type", type);
    toggleConfirmPassword.textContent = type === "password" ? "👁️" : "👁️‍🗨️";
});

// Check if passwords match
const passwordMatch = document.getElementById("passwordMatch");

function checkPasswordsMatch() {
    if (confirmPassword.value === "") {
        passwordMatch.textContent = "";
        passwordMatch.className = "password-match";
    } else if (password.value === confirmPassword.value) {
        passwordMatch.textContent = "Password cocok";
        passwordMatch.className = "password-match match";
    } else {
        passwordMatch.textContent = "Password tidak cocok";
        passwordMatch.className = "password-match no-match";
    }
}

password.addEventListener("input", checkPasswordsMatch);
confirmPassword.addEventListener("input", checkPasswordsMatch);

// Check password strength
const passwordStrength = document.getElementById("passwordStrength");

password.addEventListener("input", function () {
    const value = this.value;
    let strength = "";
    let color = "";

    if (value.length === 0) {
        strength = "";
    } else if (value.length < 6) {
        strength = "Lemah";
        color = "red";
    } else if (value.length < 10) {
        strength = "Sedang";
        color = "orange";
    } else {
        strength = "Kuat";
        color = "green";
    }

    passwordStrength.textContent = strength ? `Kekuatan: ${strength}` : "";
    passwordStrength.style.color = color;
});

// Form submission
const registerForm = document.getElementById("registerForm");

registerForm.addEventListener("submit", function (e) {
    e.preventDefault();

    // Add your form submission logic here
    if (password.value !== confirmPassword.value) {
        alert("Password tidak cocok. Silakan periksa kembali.");
        return;
    }

    alert("Pendaftaran berhasil!");
    // In a real application, you would send this data to your server
});
