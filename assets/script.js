document.addEventListener("DOMContentLoaded", function(_) {
    // Back to top button
    let back_to_top_button = document.getElementById("back-to-top");
    window.onscroll = function() { scrollFunction() };
    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            back_to_top_button.style.display = "block";
        } else {
            back_to_top_button.style.display = "none";
        }
    }
})

function toggleView(name = "password") {
    let elements = document.getElementsByName(name);
    for (let i = 0; i < elements.length; i++) {
        if (elements[i].type === "password") {
            elements[i].type = "text";
        } else {
            elements[i].type = "password";
        }
    }
}

function insertErrorMessage(element, msg) {
    let error_div = document.createElement('div');
    error_div.className = 'errore';

    let error_message = document.createElement('p');
    error_message.innerHTML = msg;

    error_div.appendChild(error_message);

    element.parentNode.insertBefore(error_div, element.nextSibling);
}

function removeErrorDivs() {
    let error_divs = document.getElementsByClassName("errore");
    for (let i = 0; i < error_divs.length; i++) {
        error_divs[i].remove();
    }
}

function validateString(element, str, min=Number.MIN_SAFE_INTEGER, max=Number.MAX_SAFE_INTEGER, show_char_number=false) {
    const specialCharPattern = /[{}\[\]|`¬¦!"£$%^&*<>:;#~_\-+=,@]/;
    if(str === '') {
        insertErrorMessage(element, "Il campo non può essere vuoto.");
        return false;
    }

    if(str.length < min) {
        if (show_char_number) {
            insertErrorMessage(element,"Il campo deve contenere almeno " + min + " caratteri.");
        }
        return false;
    }
    if(str.length > max) {
        if (show_char_number) {
            insertErrorMessage(element,"Il campo deve contenere al massimo " + max + " caratteri.");
        }
        return false;
    }
    // se la stringa contiene uno spazio
    if (str.includes(' ')) {
        if(show_char_number) {
            insertErrorMessage(element,"Il campo non può contenere spazi.");
        }
        return false;
    }

    if (specialCharPattern.test(str)) {
        if(show_char_number) {
            insertErrorMessage(element,"Il campo non può contenere caratteri speciali.");
        }
        return false;
    }

    return true;
}

function validateLogin() {
    let username = document.getElementsByName("username")[0].value;
    let password = document.getElementsByName("password")[0].value;

    let username_element = document.getElementsByName("username")[0];
    let password_element = document.getElementsByName("password")[0];

    let fieldset_element = document.getElementsByTagName("fieldset")[0];

    let isUsernameValid = validateString(fieldset_element, username, 4, 50);
    let isPasswordValid = validateString( fieldset_element, password,  4, 100);

    removeErrorDivs();
    if (!isUsernameValid || !isPasswordValid) {
        insertErrorMessage(fieldset_element,"Username o password non validi");
    }

    return isUsernameValid && isPasswordValid;
}