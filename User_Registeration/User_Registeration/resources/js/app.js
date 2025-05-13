import './bootstrap';
// Password toggle functions
    // Function to toggle the visibility of the password field
    function togglePassword() {
        var passwordField = document.getElementById("password");
        var checkbox = document.getElementById("password_checkbox");

        // Toggle the type of the password field based on checkbox
        if (checkbox.checked) {
            passwordField.type = "text"; // Show the password
        } else {
            passwordField.type = "password"; // Hide the password
        }
    }

    // Function to toggle the visibility of the confirm password field
    function toggleConfirmPassword() {
        var confirmPasswordField = document.getElementById("password_confirmation");
        var checkbox = document.getElementById("password_confirmation_checkbox");

        // Toggle the type of the confirm password field based on checkbox
        if (checkbox.checked) {
            confirmPasswordField.type = "text"; // Show the password
        } else {
            confirmPasswordField.type = "password"; // Hide the password
        }
    }


// Initialize password strength indicator
function initPasswordStrengthIndicator() {
    const passwordInput = document.getElementById("password");
    const letter = document.getElementById("letter");
    const capital = document.getElementById("capital");
    const number = document.getElementById("number");
    const length = document.getElementById("length");

    if (!passwordInput) return;

    passwordInput.onfocus = function() {
        document.getElementById("message").style.display = "block";
    };

    passwordInput.onblur = function() {
        document.getElementById("message").style.display = "none";
    };

    passwordInput.onkeyup = function() {
        // Validate lowercase letters
        const lowerCaseLetters = /[a-z]/g;
        if (passwordInput.value.match(lowerCaseLetters)) {
            letter.classList.remove("invalid");
            letter.classList.add("valid");
        } else {
            letter.classList.remove("valid");
            letter.classList.add("invalid");
        }

        // Validate capital letters
        const upperCaseLetters = /[A-Z]/g;
        if (passwordInput.value.match(upperCaseLetters)) {
            capital.classList.remove("invalid");
            capital.classList.add("valid");
        } else {
            capital.classList.remove("valid");
            capital.classList.add("invalid");
        }

        // Validate numbers
        const numbers = /[0-9]/g;
        if (passwordInput.value.match(numbers)) {
            number.classList.remove("invalid");
            number.classList.add("valid");
        } else {
            number.classList.remove("valid");
            number.classList.add("invalid");
        }

        // Validate length
        if (passwordInput.value.length >= 8) {
            length.classList.remove("invalid");
            length.classList.add("valid");
        } else {
            length.classList.remove("valid");
            length.classList.add("invalid");
        }
    };
}

// WhatsApp validation
async function validateWhatsApp() {
    const whatsappInput = document.getElementById("whatsappInput");
    const whatsappErr = document.getElementById("whatsappErr");
    
    if (!whatsappInput.value.trim()) {
        whatsappErr.textContent = "WhatsApp number is required";
        return false;
    }

    if (!/^[0-9]{11}$/.test(whatsappInput.value)) {
        whatsappErr.textContent = "WhatsApp number must be exactly 11 digits";
        return false;
    }

    whatsappErr.textContent = "";
    return true;
}

// Form validation
function setupFormValidation() {
    const validateWhatsappBtn = document.getElementById("validateWhatsappBtn");
    if (validateWhatsappBtn) {
        validateWhatsappBtn.addEventListener("click", validateWhatsApp);
    }

    const registrationForm = document.getElementById("registrationForm");
    if (registrationForm) {
        registrationForm.addEventListener("submit", async function(event) {
            event.preventDefault();
            let isValid = true;
            const errorElement = document.getElementById("global-error");
            errorElement.style.display = "none";

            // Validate all required fields
            const requiredInputs = document.querySelectorAll("input[required]");
            requiredInputs.forEach(function(input) {
                if (!input.value.trim()) {
                    input.classList.add("error-field");
                    isValid = false;
                } else {
                    input.classList.remove("error-field");
                }
            });

            // Special WhatsApp validation
            const whatsappValid = await validateWhatsApp();
            if (!whatsappValid) {
                isValid = false;
            }

            if (!isValid) {
                errorElement.style.display = "block";
                errorElement.textContent = "Please correct the errors in the form.";
            } else {
                this.submit();
            }
        });
    }
}

// Real-time username validation (AJAX)
function setupUsernameValidation() {
    const usernameInput = document.getElementById("username");
    if (usernameInput) {
        usernameInput.addEventListener("keyup", function() {
            const username = this.value.trim();
            if (username.length > 2) {
                fetch(validationRoutes.username, {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({username: username})
                })
                .then(response => response.json())
                .then(data => {
                    let validationSpan = document.getElementById("usernameValidation");
                    if (!validationSpan) {
                        validationSpan = document.createElement("span");
                        validationSpan.id = "usernameValidation";
                        this.parentNode.appendChild(validationSpan);
                    }
                    validationSpan.innerHTML = data.message;
                    validationSpan.style.color = data.valid ? 'green' : 'red';
                });
            }
        });
    }
}

// Real-time email validation (AJAX)
function setupEmailValidation() {
    const emailInput = document.getElementById("email");
    if (emailInput) {
        emailInput.addEventListener("keyup", function() {
            const email = this.value.trim();
            if (email.length > 5) {
                fetch(validationRoutes.email, {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({email: email})
                })
                .then(response => response.json())
                .then(data => {
                    let validationSpan = document.getElementById("emailValidation");
                    if (!validationSpan) {
                        validationSpan = document.createElement("span");
                        validationSpan.id = "emailValidation";
                        this.parentNode.appendChild(validationSpan);
                    }
                    validationSpan.innerHTML = data.message;
                    validationSpan.style.color = data.valid ? 'green' : 'red';
                });
            }
        });
    }
}

// Initialize all functionality when DOM is loaded
document.addEventListener("DOMContentLoaded", function() {
    initPasswordStrengthIndicator();
    setupFormValidation();
    setupUsernameValidation();
    setupEmailValidation();

    // Pass Laravel routes to JavaScript
    window.validationRoutes = {
        username: "{{ route('validate.username') }}",
        email: "{{ route('validate.email') }}"
    };
});