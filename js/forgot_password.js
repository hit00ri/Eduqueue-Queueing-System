document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".auth-form");
    const emailInput = document.getElementById("email");
    const newPasswordInput = document.getElementById("new_password");
    const confirmPasswordInput = document.getElementById("confirm_password");

    // Only add event listener if form exists
    if (form) {
        form.addEventListener("submit", (event) => {
            let errorMessage = "";
            let isValid = true;

            // Email validation
            if (!emailInput.value.trim()) {
                errorMessage = "Email is required.";
                isValid = false;
            } else if (!validateEmail(emailInput.value.trim())) {
                errorMessage = "Please enter a valid email address.";
                isValid = false;
            }

            // Password validation (minimum 3 characters as per profile-b.php)
            if (isValid) {
                if (!newPasswordInput.value.trim()) {
                    errorMessage = "New password is required.";
                    isValid = false;
                } else if (newPasswordInput.value.length < 3) {
                    errorMessage = "Password must be at least 3 characters long.";
                    isValid = false;
                } else if (!confirmPasswordInput.value.trim()) {
                    errorMessage = "Confirm password is required.";
                    isValid = false;
                } else if (newPasswordInput.value !== confirmPasswordInput.value) {
                    errorMessage = "Passwords do not match.";
                    isValid = false;
                }
            }

            if (!isValid) {
                event.preventDefault();
                
                // Show error message in a nice way
                const existingError = document.querySelector('.auth-error');
                if (existingError) {
                    existingError.textContent = errorMessage;
                } else {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'auth-error';
                    errorDiv.textContent = errorMessage;
                    const authTitle = document.querySelector('.auth-title');
                    if (authTitle && authTitle.nextElementSibling) {
                        authTitle.nextElementSibling.after(errorDiv);
                    } else {
                        authTitle.after(errorDiv);
                    }
                }
                
                // Scroll to error
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });

        // Real-time password match validation
        const checkPasswordMatch = () => {
            if (newPasswordInput.value && confirmPasswordInput.value) {
                if (newPasswordInput.value !== confirmPasswordInput.value) {
                    confirmPasswordInput.style.borderColor = '#dc3545';
                } else {
                    confirmPasswordInput.style.borderColor = '#28a745';
                }
            } else {
                confirmPasswordInput.style.borderColor = '';
            }
        };

        newPasswordInput.addEventListener('input', checkPasswordMatch);
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    }

    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
});