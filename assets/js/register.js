document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("registrationForm");
    const fullName = document.getElementById("full_name");
    const username = document.getElementById("username");
    const email = document.getElementById("email");
    const phone = document.getElementById("phone");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirm_password");
    const address = document.getElementById("address");
    const role = document.getElementById("role");
    const doctorFields = document.getElementById("doctorFields");
    const deliveryFields = document.getElementById("deliveryFields");
    const company = document.getElementById("company_name");
    const usernameNote = document.getElementById("usernameNote");
    const isEditing = form.dataset.editing === "1";
    const photoSelect = document.getElementById("profile_image");
    const photoPreview = document.getElementById("doctorPhotoPreview");
    let usernameTimer;

    function updateRoleFields() {
        const isDoctor = role.value === "doctor";
        const isDelivery = role.value === "delivery";
        doctorFields.hidden = !isDoctor;
        deliveryFields.hidden = !isDelivery;
        address.required = role.value === "customer";
        company.required = isDelivery;
        ["specialization", "qualification", "experience_years", "consultation_fee", "available_days", "available_time"].forEach(function (id) {
            document.getElementById(id).required = isDoctor;
        });
    }

    role.addEventListener("change", updateRoleFields);
    updateRoleFields();

    if (photoSelect && photoPreview) {
        photoSelect.addEventListener("change", function () {
            const selected = photoSelect.value === "default.png" ? "default.svg" : photoSelect.value;
            photoPreview.src = "../assets/uploads/profiles/" + encodeURIComponent(selected);
        });
    }

    username.addEventListener("input", function () {
        clearTimeout(usernameTimer);
        const value = username.value.trim();
        if (value === "") {
            usernameNote.textContent = "";
            usernameNote.className = "field-note";
            return;
        }
        if (isEditing && value === username.dataset.currentUsername) {
            usernameNote.textContent = "";
            usernameNote.className = "field-note";
            return;
        }
        usernameTimer = setTimeout(function () {
            fetch(username.dataset.checkUrl + "&username=" + encodeURIComponent(value), { credentials: "same-origin" })
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    if (username.value.trim() !== value) return;
                    usernameNote.textContent = data.message;
                    usernameNote.className = "field-note " + (data.ok ? "note-ok" : "note-bad");
                })
                .catch(function () {
                    if (username.value.trim() !== value) return;
                    usernameNote.textContent = "Availability check unavailable. The form will still verify your username.";
                    usernameNote.className = "field-note note-bad";
                });
        }, 250);
    });

    form.addEventListener("submit", function (event) {
        clearErrors();
        let isValid = true;

        if (fullName.value.trim() === "") {
            showError(fullName, "Full name is required.");
            isValid = false;
        }

        const usernamePattern = /^[a-zA-Z0-9_]+$/;

        if (username.value.trim() === "") {
            showError(username, "Username is required.");
            isValid = false;
        }
        else if (username.value.trim().length < 4) {
            showError(username, "Username must be at least 4 characters.");
            isValid = false;
        }
        else if (username.value.trim().length > 50) {
            showError(username, "Username must be at most 50 characters.");
            isValid = false;
        }
        else if (!usernamePattern.test(username.value.trim())) {
            showError(username, "Only letters, numbers and underscore are allowed.");
            isValid = false;
        }

        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email.value.trim() === "") {
            showError(email, "Email address is required.");
            isValid = false;
        }
        else if (!emailPattern.test(email.value.trim())) {
            showError(email, "Enter a valid email address.");
            isValid = false;
        }

        const phonePattern = /^01[0-9]{9}$/;

        if (phone.value.trim() === "") {
            showError(phone, "Phone number is required.");
            isValid = false;
        }
        else if (!phonePattern.test(phone.value.trim())) {
            showError(phone, "Enter a valid 11-digit Bangladeshi phone number.");
            isValid = false;
        }

        if (password.value === "" && !isEditing) {
            showError(password, "Password is required.");
            isValid = false;
        }
        else if (password.value.length < 6) {
            showError(password, "Password must be at least 6 characters.");
            isValid = false;
        }

        if (confirmPassword && password.value !== confirmPassword.value) {
            showError(confirmPassword, "The two passwords do not match.");
            isValid = false;
        }

        if (role.value === "customer" && address.value.trim() === "") {
            showError(address, "Home address is required.");
            isValid = false;
        }

        if (role.value === "doctor") {
            ["specialization", "qualification", "available_days", "available_time"].forEach(function (id) {
                const input = document.getElementById(id);
                if (input.value.trim() === "") {
                    showError(input, "This doctor detail is required.");
                    isValid = false;
                }
            });
        }

        if (role.value === "delivery" && company.value === "") {
            showError(company, "Choose a delivery company.");
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
    });

    function showError(input, message) {
        const formGroup = input.parentElement;
        const error = document.createElement("small");
        error.className = "field-error";
        error.innerText = message;
        formGroup.appendChild(error);
        input.classList.add("input-error");
    }

    function clearErrors() {
        const errors = document.querySelectorAll(".field-error");

        errors.forEach(function (error) {
            error.remove();
        });

        const inputs = document.querySelectorAll(".input-error");

        inputs.forEach(function (input) {
            input.classList.remove("input-error");
        });
    }
});
