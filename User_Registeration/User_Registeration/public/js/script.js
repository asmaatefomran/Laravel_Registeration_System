document.addEventListener('DOMContentLoaded', function() {
    // Password toggle functions
    window.togglePassword = function() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    };

    window.toggleConfirmPassword = function() {
        var x = document.getElementById("password_confirmation");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    };

    // Password validation
    var myInput = document.getElementById("password");
    var letter = document.getElementById("letter");
    var capital = document.getElementById("capital");
    var number = document.getElementById("number");
    var length = document.getElementById("length");

    if (myInput) {
        myInput.onfocus = function() {
            document.getElementById("message").style.display = "block";
        };

        myInput.onblur = function() {
            document.getElementById("message").style.display = "none";
        };

        myInput.onkeyup = function() {
            // Validate lowercase letters
            var lowerCaseLetters = /[a-z]/g;
            if (myInput.value.match(lowerCaseLetters)) {
                letter.classList.remove("invalid");
                letter.classList.add("valid");
            } else {
                letter.classList.remove("valid");
                letter.classList.add("invalid");
            }

            // Validate capital letters
            var upperCaseLetters = /[A-Z]/g;
            if (myInput.value.match(upperCaseLetters)) {
                capital.classList.remove("invalid");
                capital.classList.add("valid");
            } else {
                capital.classList.remove("valid");
                capital.classList.add("invalid");
            }

            // Validate numbers
            var numbers = /[0-9]/g;
            if (myInput.value.match(numbers)) {
                number.classList.remove("invalid");
                number.classList.add("valid");
            } else {
                number.classList.remove("valid");
                number.classList.add("invalid");
            }

            // Validate length
            if (myInput.value.length >= 8) {
                length.classList.remove("invalid");
                length.classList.add("valid");
            } else {
                length.classList.remove("valid");
                length.classList.add("invalid");
            }
        };
    }

    // Form validation
    window.validateForm = async function() {
        let isValid = true;
        const inputs = document.querySelectorAll("input[type='text'], input[type='password'], input[type='file']");
        
        inputs.forEach(function(input) {
            if (input.value.trim() === "" && input.name !== "whatsapp_number") {
                input.classList.add("error-field");
                isValid = false;
            } else {
                input.classList.remove("error-field");
            }
        });

        const whatsappInput = document.querySelector("input[name='whatsapp_number']");
        if (whatsappInput.value.trim() === "") {
            whatsappInput.classList.add("error-field");
            isValid = false;
        } //else if (isValid) {
           // const isWhatsAppValid = await validateWhatsApp();
            //if (!isWhatsAppValid) {
                //whatsappInput.classList.add("error-field");
               // isValid = false;
           // }
        //}

        const errorElement = document.getElementById("global-error");
        errorElement.style.display = isValid ? "none" : "block";
        errorElement.textContent = isValid ? "" : "Please correct the errors in the form.";

        return isValid;
    };

    // Form submission
    window.submitForm = async function(event) {
        event.preventDefault(); 
        const isValid = await validateForm();
        
        if (isValid) {
            event.target.submit();
        } else {
            document.getElementById("global-error").style.display = "block";
            document.getElementById("global-error").textContent = "Please correct the errors in the form.";
        }
    };

    // AJAX validation with jQuery
    $(document).ready(function() {
        // Username validation
        $("#username").keyup(function() {
            let username = $(this).val().trim();
            if (username.length > 2) {
                $.ajax({
                    url: 'DB_Ops.php',
                    type: 'POST',
                    data: {validate: 'username', username: username},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $("#usernameValidation").html(response);
                    }
                });
            }
        });

        // Email validation
        $("#email").keyup(function() {
            let email = $(this).val().trim();
            if (email.length > 5) {
                $.ajax({
                    url: 'DB_Ops.php',
                    type: 'POST',
                    data: {validate: 'email', email: email},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $("#emailValidation").html(response);
                    }
                });
            }
        });
    });
});