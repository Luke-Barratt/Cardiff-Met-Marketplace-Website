function validate(form) {
    var fail = "";
    fail = validateForename(form.forename.value);
    fail += validateSurname(form.surname.value);
    fail += validateUsername(form.username.value);
    fail += validatePassword(form.password.value);
    fail += validateRepeatPassword(form.repeatpassword.value)
    fail += validateEmail(form.email.value);
    fail += validateRepeatEmail(form.repeatemail.value)

    if(fail == "") {
        return true;
    }
    else {
        alert(fail);
        return false;
    }
}

function validateForename(field) {
    if (field == "") {
        return "No Forename was entered. \n";
    }
    else if (/[^a-zA-Z ]/.test(field)) {
        return "Only letters and white space allowed. \n";
    }
    else {
        return "";
    }
}

function validateSurname(field) {
    if (field == "") {
        return "No Surname was entered. \n";
    }
    else if (/[^a-zA-Z ]/.test(field)) {
        return "Only letters and white space allowed. \n";
    }
    else {
        return "";
    }   
}

function validateUsername(field) {
    if (field == "") {
        return "No Username was entered. \n";
    }
    else if (field.length <= 5) {
        return "Username must be at least 6 characters. \n";
    }
    else if (/[^a-zA-Z0-9_-]/.test(field)) {
        return "Only a-z, A-Z, 0-9, - and _ allowed in Usernames. \n";
    }
    else {
        return "";
    }
}

function validatePassword(field) {
    if (field == "") {
        return "No Password was entered.\n";
    }
    else if (field.length <= 7) {
        return "Passwords must be at least 8 characters.\n";
    }
    else if (!(/[a-z]/.test(field)) || !(/[A-Z]/.test(field)) || !(/[0-9]/.test(field))) {
        return "Passwords require one each of " + " a-z, A-Z and 0-9.\n"
    }
    else {
        return "";
    }
}

function validateRepeatPassword(field) {
    if (field == "") {
        return "Password was not repeated. \n";
    }
    else if (document.getElementById("Password").value != document.getElementById("repeatPassword").value) {
       return "Passwords do not match. \n";
    }
    else {
        return "";
    }
}

function validateEmail(field) {
    if (field == "") {
        return "No Email was entered. \n";
    }
    else if (!((field.indexOf(".") > 0) && (field.indexOf("@") > 0)) || (/[^a-zA-Z0-9.@-_]/.test(field))) {
        return "This Email address is invalid.\n";
    }
    else {
        return "";
    }
}

function validateRepeatEmail(field) {
    if (field == "") {
        return "Email was not repeated. \n";
    }
    else if (document.getElementById("Email").value != document.getElementById("repeatEmail").value) {
        return "Emails do not match. \n";
    }
    else {
        return "";
    }
}

