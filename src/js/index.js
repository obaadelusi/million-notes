document.addEventListener("DOMContentLoaded", load);

function load() {
    // Validate form
    hideErrors();

    const signupForm = document.getElementById("signup-form");
    signupForm &&
        signupForm.addEventListener("submit", (e) => {
            e.preventDefault();
            hideErrors();

            if (!signupFormHasErrors()) {
                signupForm.submit();
            }
        });

    const loginForm = document.getElementById("login-form");
    loginForm &&
        loginForm.addEventListener("submit", (e) => {
            e.preventDefault();
            hideErrors();

            if (!loginFormHasErrors()) {
                loginForm.submit();
            }
        });
}

function hideErrors() {
    const errors = document.getElementsByClassName("input-error");

    for (const inputError of errors) {
        inputError.style.display = "none";
    }
}

function signupFormHasErrors() {
    let hasErrors = false;

    const userValue = document.getElementById("username").value;
    const emailValue = document.getElementById("email").value;
    const passValue = document.getElementById("password").value;
    const confirmPassValue = document.getElementById("confirmPassword").value;

    if (signupFormIsEmpty()) {
        hasErrors = true;
    }

    // Username validation
    const userRegex = new RegExp(/^[A-Za-z][A-Za-z0-9_]{2,15}$/);
    const userIsValid = userRegex.test(trim(userValue));

    if (trim(userValue).length > 0 && !userIsValid) {
        document.getElementById(`usernameformat_error`).style.display = "block";
        hasErrors = true;
    }

    // Email validation
    const emailRegex = new RegExp(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,5}$/);
    const isValidEmail = emailRegex.test(emailValue);

    if (trim(emailValue).length > 0 && !isValidEmail) {
        document.getElementById("email_error").style = "block";
        hasErrors = true;
    }

    // Password validation
    const passRegex = new RegExp(/[A-Za-z0-9_]{6,16}/);
    const passIsValid = passRegex.test(trim(passValue));

    if (trim(passValue).length > 0 && !passIsValid) {
        document.getElementById(`password_error`).style.display = "block";
        hasErrors = true;
    }

    // Confirm Password validation
    if (trim(passValue) != confirmPassValue) {
        document.getElementById(`confirmPassword_error`).style.display = "block";
        hasErrors = true;
    }

    return hasErrors;
}

function loginFormHasErrors() {
    let hasErrors = false;

    const userValue = document.getElementById("username").value;
    const passValue = document.getElementById("password").value;

    if (loginFormIsEmpty()) {
        hasErrors = true;
    }

    // Username validation
    const userRegex = new RegExp(/^[A-Za-z][A-Za-z0-9_]{2,15}$/);
    const userIsValid = userRegex.test(trim(userValue));

    if (trim(userValue).length > 0 && !userIsValid) {
        document.getElementById(`nameformat_error`).style.display = "block";
        hasErrors = true;
    }

    // Password validation
    const passRegex = new RegExp(/[A-Za-z0-9_]{6,16}/);
    const passIsValid = passRegex.test(trim(passValue));

    if (trim(passValue).length > 0 && !passIsValid) {
        document.getElementById(`passformat_error`).style.display = "block";
        hasErrors = true;
    }

    return hasErrors;
}

function signupFormIsEmpty() {
    let inputIsEmpty = false;

    const username = document.getElementById("username"),
        email = document.getElementById("email"),
        password = document.getElementById("password"),
        confirmPassword = document.getElementById("confirmPassword");

    const signupInputs = [confirmPassword, password, email, username];

    for (const input of signupInputs) {
        if (trim(input.value).length == 0) {
            document.getElementById(`${input.id}_error`).style.display = "block";
            inputIsEmpty = true;
            input.focus();
        }
    }

    return inputIsEmpty;
}

function loginFormIsEmpty() {
    let inputIsEmpty = false;

    const username = document.getElementById("username"),
        password = document.getElementById("password");

    const signupInputs = [password, username];

    for (const input of signupInputs) {
        if (trim(input.value).length == 0) {
            document.getElementById(`${input.id}_error`).style.display = "block";
            inputIsEmpty = true;
            input.focus();
        }
    }

    return inputIsEmpty;
}

function clearFields() {
    const inputs = document.getElementsByTagName("input");

    for (const input of inputs) {
        input.value = "";
    }
}

/**
 * Removes whitespace from a string value.
 * @param str The string to trim.
 * @returns A string with leading and trailing white-space removed.
 */
function trim(str) {
    // Uses a regex to remove spaces from a string.
    return str.replace(/^\s+|\s+$/g, "");
}
