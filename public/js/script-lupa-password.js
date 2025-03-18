// Toggle password visibility functions
function togglePasswordVisibility(passwordField, toggleIcon) {
    const type =
        passwordField.getAttribute("type") === "password" ? "text" : "password";
    passwordField.setAttribute("type", type);
    toggleIcon.textContent = type === "password" ? "👁️" : "👁️‍🗨️";
}

// Password visibility toggles
const newPassword = document.getElementById("newPassword");
const toggleNewPassword = document.getElementById("toggleNewPassword");

toggleNewPassword.addEventListener("click", function () {
    togglePasswordVisibility(newPassword, this);
});

const confirmNewPassword = document.getElementById("confirmNewPassword");
const toggleConfirmNewPassword = document.getElementById(
    "toggleConfirmNewPassword"
);

toggleConfirmNewPassword.addEventListener("click", function () {
    togglePasswordVisibility(confirmNewPassword, this);
});

// Multi-step form navigation
const sendCodeBtn = document.getElementById("sendCodeBtn");
const resetPasswordBtn = document.getElementById("resetPasswordBtn");
const emailStep = document.getElementById("emailStep");
const verificationStep = document.getElementById("verificationStep");
const successMessage = document.getElementById("successMessage");

sendCodeBtn.addEventListener("click", function () {
    const email = document.getElementById("email").value;
    if (!email) {
        alert("Mohon masukkan email atau username Anda");
        return;
    }

    // In a real application, you would send a request to your server here
    // For this demo, we'll just proceed to the next step
    emailStep.style.display = "none";
    verificationStep.classList.add("active");

    // Simulate sending a verification code
    alert(
        `Kode verifikasi telah dikirim ke ${email}. Silakan periksa email Anda.`
    );
});

resetPasswordBtn.addEventListener("click", function () {
    const verificationCode = document.getElementById("verificationCode").value;
    const newPasswordValue = newPassword.value;
    const confirmNewPasswordValue = confirmNewPassword.value;

    if (!verificationCode || !newPasswordValue || !confirmNewPasswordValue) {
        alert("Mohon lengkapi semua field");
        return;
    }

    if (verificationCode.length !== 6) {
        alert("Kode verifikasi harus 6 digit");
        return;
    }

    if (newPasswordValue !== confirmNewPasswordValue) {
        alert("Password tidak cocok");
        return;
    }

    // In a real application, you would validate the code and update the password on your server
    // For this demo, we'll just show the success message
    verificationStep.classList.remove("active");
    successMessage.style.display = "block";

    // In a production app, you might redirect to login after a delay
    setTimeout(function () {
        // window.location.href = 'login.html'; // Uncomment in a real application
    }, 3000);
});
