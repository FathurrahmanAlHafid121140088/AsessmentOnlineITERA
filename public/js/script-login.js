// Toggle password visibility with icon
const togglePassword = document.getElementById("togglePassword");
const password = document.getElementById("password");

togglePassword.addEventListener("click", function () {
    // Toggle the type attribute
    const type =
        password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);

    // Toggle the eye icon
    this.textContent = type === "password" ? "👁️" : "👁️‍🗨️";
});

// Toggle password visibility with checkbox
const showPasswordCheckbox = document.getElementById("showPassword");

showPasswordCheckbox.addEventListener("change", function () {
    // Toggle the type attribute based on checkbox
    const type = this.checked ? "text" : "password";
    password.setAttribute("type", type);

    // Also update the eye icon
    togglePassword.textContent = type === "password" ? "👁️" : "👁️‍🗨️";
});
