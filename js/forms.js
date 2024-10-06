function validateSignUp(e) {
    e.preventDefault();
    let name = document.forms["signup"]["nameof"].value;
    let dob = document.forms["signup"]["dob"].value;
    let uname = document.forms["signup"]["username"].value;
    let pass = document.forms["signup"]["password"].value;
    let errField = document.querySelector(".error-field.password");
    errField.innerText = ""; 

    if (name === "" || dob === "" || uname === "" || pass === "") {
        errField.innerText = "Fields should not be empty!";
        return false; 
    }

    let passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (!passRegex.test(pass)) {
        errField.innerHTML = "Password should be of 8 characters. <br/> uppercase,lowercase,digit,and special character";
        return false; 
    }
    e.target.submit();
}

function validateLogin(e) {
    e.preventDefault();
    let uname = document.forms["login"]["username"].value;
    let pass = document.forms["login"]["password"].value;
    let errField = document.querySelector(".error-field.password");
    errField.innerText = ""; 

    if (uname === "" || pass === "") {
        errField.innerText = "Fields should not be empty!";
        return false; 
    }

    e.target.submit();
}

function showPass() {
    let passField = document.getElementById("pass");
    if (passField.type === "password") {
        passField.type = "text"
    } else {
        passField.type = "password"
    }
}

function showPass2() {
    let passField = document.getElementById("pass2");
    if (passField.type === "password") {
        passField.type = "text"
    } else {
        passField.type = "password"
    }
}


function validateChangePassword(e) {
    e.preventDefault();
    let oldpass = document.forms["changepass"]["oldpassword"].value;
    let newpass = document.forms["changepass"]["newpassword"].value;
    let errField = document.querySelector(".error-field.password");
    errField.innerText = ""; 

    if (newpass === "" || oldpass === "") {
        errField.innerText = "Fields should not be empty!";
        return false; 
    }

    if (newpass === oldpass) {
        errField.innerText = "Same Password Can't Be Updated!";
        return false; 
    }

    let passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (!passRegex.test(newpass)) {
        errField.innerHTML = "Password should be of 8 characters. <br/> uppercase,lowercase,digit,and special character";
        return false; 
    }
    e.target.submit();   
}