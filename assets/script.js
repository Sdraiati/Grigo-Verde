document.addEventListener("DOMContentLoaded", function(_) {
    const baseUrl = String(window.location.origin);
    document.documentElement.style.setProperty('--base-url', `"${baseUrl}"`);
    // Back to top button
    let back_to_top_button = document.getElementById("back-to-top");
    window.onscroll = function() { scrollFunction() };
    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            back_to_top_button.classList.remove("hidden");
        } else {
            back_to_top_button.classList.add("hidden");
        }
    }

    let messageDiv = document.getElementById('message');
    if (messageDiv) {
        setTimeout(function() {
            messageDiv.classList.add('fade-out');

            setTimeout(function() {
                messageDiv.removeAttribute('id');
                messageDiv.classList.remove('fade-out');
                messageDiv.classList.add('helper');
            }, 1000);

        }, 3000);
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

function validateString(element, str, min = Number.MIN_SAFE_INTEGER, max = Number.MAX_SAFE_INTEGER, show_char_number = false, spaces = false) {
    const specialCharPattern = /[{}\[\]|`¬¦!"£$%^&*<>:;#~_\-+=,@]/;
    if (str === '') {
        console.log("metto errore");
        insertErrorMessage(element, "Il campo non può essere vuoto.");
        return false;
    }

    if (str.length < min) {
        if (show_char_number) {
            insertErrorMessage(element, "Il campo deve contenere almeno " + min + " caratteri.");
        }
        return false;
    }
    if (str.length > max) {
        if (show_char_number) {
            insertErrorMessage(element, "Il campo può contenere al massimo " + max + " caratteri.");
        }
        return false;
    }
    // se la stringa contiene uno spazio
    if (!spaces && str.includes(' ')) {
        if (show_char_number) {
            insertErrorMessage(element, "Il campo non può contenere spazi.");
        }
        return false;
    }

    if (specialCharPattern.test(str)) {
        if (show_char_number) {
            insertErrorMessage(element, "Il campo non può contenere caratteri speciali.");
        }
        return false;
    }

    return true;
}

function validateLogin() {
    let username = document.getElementsByName("username")[0].value;
    let password = document.getElementsByName("password")[0].value;

    let fieldset_element = document.getElementsByTagName("fieldset")[0];

    let isUsernameValid = validateString(fieldset_element, username, 4, 50);
    let isPasswordValid = validateString(fieldset_element, password, 4, 100);

    removeErrorDivs();
    if (!isUsernameValid || !isPasswordValid) {
        insertErrorMessage(fieldset_element, "Username o password non validi");
    }

    return isUsernameValid && isPasswordValid;
}

function validateImage() {
    //check if the uploaded image is larger than 1MB
    let images = document.getElementsByClassName("image");
    for (let i = 0; i < images.length; i++) {
        if (images[i].files[0].size > 1048576) {
            removeErrorDivs();
            insertErrorMessage(images[i], "L'immagine non può superare 1MB.");
            return false;
        }
    }
    //for each image n with id image_n check there is a corresponding description with id img_description_n completed
    let descriptions = document.getElementsByClassName("img_description");
    for (let i = 0; i < descriptions.length; i++) {
        if (descriptions[i].value === "") {
            removeErrorDivs();
            insertErrorMessage(descriptions[i], "Inserire una descrizione per l'immagine.");
            return false;
        }
    }
    return true;
}

function validateNewSpace() {
    let posizione = document.getElementsByName("posizione")[0].value;
    let nome = document.getElementsByName("nome")[0].value;
    let descrizione = document.getElementsByName("descrizione")[0].value;
    let tipo = document.getElementsByName("tipo")[0].value;
    let n_tavoli = document.getElementsByName("n_tavoli")[0].value;

    let fieldset_element = document.getElementsByTagName("fieldset")[0];

    removeErrorDivs();

    if (posizione < 0 || !Number.isInteger(parseInt(posizione))) {
        insertErrorMessage(fieldset_element, "La posizione deve essere un numero intero positivo.");
        return false;
    }

    if (tipo !== "Aula verde" && tipo !== "Spazio ricreativo") {
        insertErrorMessage(fieldset_element, "Il tipo deve essere Amministratore o Docente.");
        return false;
    }

    if (n_tavoli < 0 || !Number.isInteger(parseInt(n_tavoli))) {
        if (n_tavoli === "") {
            n_tavoli = 0;
        }
        else {
            insertErrorMessage(fieldset_element, "Il numero di tavoli deve essere un numero intero positivo.");
            return false;
        }
    }

    let limit = 65534;
    if (descrizione.length > limit) {
        insertErrorMessage(fieldset_element, "La descrizione non può contenere più di " + limit + " caratteri.");
        return false;
    }

    let isNomeValid = validateString(fieldset_element, nome, 2, 70, true, true);
    let isImageValid = validateImage();
    return isNomeValid && isImageValid;
}

function removeImage(num) {
    let image_div = document.getElementById(`image_div_${num}`);
    image_div.remove();
    //if count is 1 we can add the button again
    if (imgCount === 1) {
        let add_image_button = document.createElement("input");
        add_image_button.type = "button";
        add_image_button.value = "Aggiungi immagine";
        add_image_button.id = "add_img_button";
        add_image_button.onclick = addImage;

        let fieldset = document.getElementsByTagName("fieldset")[1];
        fieldset.appendChild(add_image_button);
    }
}

let imgCount = 0;
function addImage() {
    //check if the image is already present
    let images = document.getElementsByClassName("image");
    if (images.length > 0) {
        //check if the image is empty
        if (images[0].value === "") {
            removeErrorDivs();
            insertErrorMessage(images[0], "Inserire un'immagine.");
            return;
        }
    }
    //check if the description is already present
    let description = document.getElementsByClassName("img_description");
    if (description.length > 0) {
        //check if the description is empty
        if (description[0].value === "") {
            removeErrorDivs();
            insertErrorMessage(description[0], "Inserire una descrizione per l'immagine.");
            return;
        }
    }

    let image_label = document.createElement("label");
    image_label.htmlFor = `image_${imgCount}`;
    image_label.innerHTML = "Carica un'immagine";
    let image_input = document.createElement("input");
    image_input.type = "file";
    image_input.className = "image";
    image_input.name = `image_${imgCount}`;
    image_input.id = `image_${imgCount}`;
    image_input.accept = "image/png, image/jpg, image/jpeg";

    // Crea l'elemento img per l'anteprima
    let image_preview = document.createElement("img");
    image_preview.id = `image_preview_${imgCount}`;
    image_preview.style.maxWidth = "10vw";
    image_preview.style.display = "none";

    image_input.addEventListener("change", function(event) {
        if (event.target.files && event.target.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                image_preview.src = e.target.result;
                image_preview.style.display = "block";
            };
            reader.readAsDataURL(event.target.files[0]);

            // Rimuovi gli errori associati all'immagine
            let error_divs = document.getElementsByClassName("errore");
            for (let i = 0; i < error_divs.length; i++) {
                if (error_divs[i].previousSibling === image_input) {
                    error_divs[i].remove();
                }
            }
        }
    });

    let description_label = document.createElement("label");
    description_label.htmlFor = `img_description_${imgCount}`;
    description_label.innerHTML = "Descrizione dell'immagine";
    let description_input = document.createElement("input");
    description_input.type = "text";
    description_input.className = "img_description";
    description_input.name = `img_description_${imgCount}`;
    description_input.id = `img_description_${imgCount}`;
    description_input.placeholder = "Descrizione dell'immagine";

    description_input.addEventListener("input", function() {
        // Rimuovi gli errori associati alla descrizione
        let error_divs = document.getElementsByClassName("errore");
        for (let i = 0; i < error_divs.length; i++) {
            if (error_divs[i].previousSibling === description_input) {
                error_divs[i].remove();
            }
        }
    });

    let remove_button = document.createElement("input");
    remove_button.type = "button";
    remove_button.value = "Rimuovi";
    let num = imgCount;
    remove_button.onclick = function() { removeImage(num) };

    let image_div = document.createElement("div");
    image_div.appendChild(image_label);
    image_div.appendChild(image_input);
    image_div.appendChild(image_preview);
    image_div.appendChild(description_label);
    image_div.appendChild(description_input);
    image_div.appendChild(remove_button);

    image_div.id = `image_div_${imgCount}`;
    imgCount++;

    let add_image_button = document.getElementById("add_img_button");

    add_image_button.parentNode.insertBefore(image_div, add_image_button);
    //TODO: teniamo una sola immagine quindi dopo l'aggiunta di una nuova immagine rimuoviamo il pulsante
    if (imgCount === 1)
        add_image_button.remove();
}

function refreshPreview() {
    //remove all elements with id starting with image_preview_
    let images = document.getElementsByClassName("image");
    for (let i = 0; i < images.length; i++) {
        let image_preview = document.getElementById(`image_preview_${i}`);
        image_preview.remove();
    }
}
function validatePrenotazione() {
    // Get the form elements
    var giorno = document.getElementById('giorno');
    var dalleOre = document.getElementById('dalle-ore');
    var alleOre = document.getElementById('alle-ore');
    var spazio = document.getElementById('spazio');
    let fieldset_element = document.getElementsByTagName("fieldset")[0];

    removeErrorDivs();

    // Check if any required field is empty
    if (!giorno.value || !dalleOre.value || !alleOre.value || !spazio.value || spazio.value === '-1') {
        insertErrorMessage(fieldset_element, "Compilare tutti i campi obbligatori.");
        return false;
    }

    // Check if the date is not sooner than today
    var today = new Date();
    today.setHours(0, 0, 0, 0); // Set to midnight to compare only the date part
    var selectedDate = new Date(giorno.value);
    if (selectedDate < today) {
        insertErrorMessage(giorno, "La data non può essere precedente a oggi.");
        return false;
    }

    if (dalleOre.value >= alleOre.value) {
        insertErrorMessage(alleOre, "L'orario di fine deve essere successivo a quello di inizio.");
        return false;
    }

    return true;
}

function validateNewUser() {
    let username = document.getElementsByName("username")[0].value;
    let nome = document.getElementsByName("nome")[0].value;
    let cognome = document.getElementsByName("cognome")[0].value;
    let ruolo = document.getElementsByName("ruolo")[0].value;

    let ruolo_element = document.getElementsByName("ruolo")[0];
    removeErrorDivs();

    if (ruolo !== "Amministratore" && ruolo !== "Docente") {
        insertErrorMessage(ruolo_element, "Il ruolo deve essere Amministratore o Docente.");
        return false;
    }

    let username_element = document.getElementsByName("username")[0];
    let nome_element = document.getElementsByName("nome")[0];
    let cognome_element = document.getElementsByName("cognome")[0];

    let isUsernameValid = validateString(username_element, username, 4, 50, true, true);

    let isNomeValid = validateString(nome_element, nome, 2, 70, true, false);
    let isCognomeValid = validateString(cognome_element, cognome, 2, 70, true, false);

    isPasswordValid = validatePassword();

    return isUsernameValid && isPasswordValid && isNomeValid && isCognomeValid;
}

function showEditPassword() {
    let fieldset = document.getElementById("password-fields");
    fieldset.classList.remove("hidden");
    fieldset.disabled = false;
}

function validatePassword() {
    let password = document.getElementsByName("password")[0].value;
    let password_element = document.getElementsByName("password")[0];
    // check if fieldset with id password-fields is enabled
    let password_fieldset = document.getElementById("password-fields");
    if (password_fieldset && !password_fieldset.disabled)
        isPasswordValid = validateString(password_element, password, 4, 100, true, true);
    //check if the password corresponds to the value in conferma password
    let conferma_password = document.getElementsByName("conferma_password")[0].value;
    let conferma_password_element = document.getElementsByName("conferma_password")[0];
    if (password !== conferma_password) {
        insertErrorMessage(conferma_password_element, "Le password non corrispondono.");
        return false;
    }
    return isPasswordValid;
}

function validateDate() {
    // validare l'input dei form all'interno dei date e time picker.
    let data = document.getElementById("data").value;
    let start = document.getElementById("orario_inizio").value;
    let end = document.getElementById("orario_fine").value;
    let error_div = document.getElementById("error_msg");
    let error = "";
    let valid = false;


    if (data != "" || start != "" || end != "") { // se almeno uno di questi campi è selezionato.
        if (data == "" || start == "" || end == "") { // se solo un campo dovesse essere vuoto
            error = "Nessun campo data o ora può essere lasciato vuoto.";
        } else if (start >= end) {
            error = "Orario di inizio e di fine devono essere rispettivamente l'uno minore o uguale dell'altro bitch.";
        } else {
            valid = true;
        }
    } else {
        valid = true;
    }
    console.log(error);
    if (error != "" && error_div) error_div.innerText = error;
    return valid;
}

function validateFiltriUtente() {
  let error_div = document.getElementById("error_msg");
  let valid = true;
  const values = [
    document.getElementById("username").value,
    document.getElementById("nome").value,
    document.getElementById("cognome").value,
  ];
  const specialCharPattern = /[{}\[\]|`¬¦!"£$%^&*<>:;#~_\-+=,@]/;

  values.forEach((item) => {
    valid = specialCharPattern.test(item);
  });

  if (!valid) {
    error_div.innerText = "Nessun campo testuale può contenere caratteri speciali";
  }
  return valid;
}
