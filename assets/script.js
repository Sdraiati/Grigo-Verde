let imgCount = 0;
let availabilityCount = 0;
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
    // Sincronizza la variabile availabilityCount con il valore dal campo nascosto
    if(document.getElementById("availabilityCount")) {
        availabilityCount = parseInt(document.getElementById("availabilityCount").value, 10) || 0;
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

    let position_element = document.getElementsByName("posizione")[0];
    let tipo_element = document.getElementsByName("tipo")[0];
    let n_tavoli_element = document.getElementsByName("n_tavoli")[0];
    let nome_element = document.getElementsByName("nome")[0];
    let description_element = document.getElementsByName("descrizione")[0];

    removeErrorDivs();

    if (posizione < 0 || !Number.isInteger(parseInt(posizione))) {
        insertErrorMessage(position_element, "La posizione deve essere un numero intero positivo.");
        return false;
    }

    if (tipo !== "Aula verde" && tipo !== "Spazio ricreativo") {
        insertErrorMessage(tipo_element, "Il tipo deve essere Amministratore o Docente.");
        return false;
    }

    if (n_tavoli < 0 || !Number.isInteger(parseInt(n_tavoli))) {
        if (n_tavoli === "") {
            n_tavoli = 0;
        }
        else {
            insertErrorMessage(n_tavoli_element, "Il numero di tavoli deve essere un numero intero positivo.");
            return false;
        }
    }

    let limit = 65534;
    if (descrizione.length > limit) {
        insertErrorMessage(description_element, "La descrizione non può contenere più di " + limit + " caratteri.");
        return false;
    }

    let isNomeValid = validateString(nome_element, nome, 2, 25, true, true);
    let isImageValid = true;
    if (imgCount > 0) {
        isImageValid = validateImage();
    }
    let isAvailabilityValid = validateAvailability();
    return isNomeValid && isImageValid && isAvailabilityValid;
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

    let isUsernameValid = validateString(username_element, username, 4, 12, true, true);

    let isNomeValid = validateString(nome_element, nome, 2, 70, true, false);
    let isCognomeValid = validateString(cognome_element, cognome, 2, 70, true, false);

    let isPasswordValid = validatePassword();

    return isUsernameValid && isPasswordValid && isNomeValid && isCognomeValid;
}

function showEditPassword() {
    let fieldset = document.getElementById("password-fields");
    fieldset.classList.remove("hidden");
    fieldset.disabled = false;
    // Hide the button
    let button = document.getElementById("edit_password_button");
    button.classList.add("hidden");
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
    if (error != "" && error_div) error_div.innerText = error;
    return valid;
}

function validateFiltriUtente() {
  let error_div = document.getElementById("error_msg");
  const values = [
    document.getElementById("username").value,
    document.getElementById("nome").value,
    document.getElementById("cognome").value,
  ];
  const specialCharPattern = /[{}\[\]|`¬¦!"£$%^&*<>:;#~_\-+=,@]/;

  for (let i = 0; i < values.length; i++) {
    if (specialCharPattern.test(values[i])) {
        error_div.innerText = "Nessun campo testuale può contenere caratteri speciali";
        return false;
    }
  }
  return true;
}

function addMonth(div) {
    let month_label = document.createElement("label");
    month_label.htmlFor = `availability_month_${availabilityCount}`;
    month_label.innerHTML = "Seleziona i mesi";

    let month_div = document.createElement("div");
    month_div.id = `availability_month_${availabilityCount}`;

    let months = ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto",
        "Settembre", "Ottobre", "Novembre", "Dicembre"];
    for (let i = 0; i < months.length; i++) {
        let option = document.createElement("input");
        option.type = "checkbox";
        option.name = months[i] + '_' + availabilityCount;
        option.name = "availability_month_" + availabilityCount + "[]";
        option.value = months[i];

        let label = document.createElement("label");
        label.htmlFor = months[i];
        label.innerHTML = months[i];

        month_div.appendChild(option);
        month_div.appendChild(label);
    }

    div.appendChild(month_label);
    div.appendChild(month_div);
}

function addWeekDay(div) {
    let day_label = document.createElement("label");
    day_label.htmlFor = `availability_day_${availabilityCount}`;
    day_label.innerHTML = "Seleziona i giorni";

    let day_div = document.createElement("div");
    day_div.id = `availability_day_${availabilityCount}`;

    let week_days = ["Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato", "Domenica"];
    for (let i = 0; i < week_days.length; i++) {
        let option = document.createElement("input");
        option.type = "checkbox";
        option.name = "availability_day_" + availabilityCount + "[]";
        option.value = week_days[i];

        let label = document.createElement("label");
        label.htmlFor = week_days[i];
        label.innerHTML = week_days[i];

        day_div.appendChild(option);
        day_div.appendChild(label);
    }

    div.appendChild(day_label);
    div.appendChild(day_div);
}

function addHour(div) {
    let start_hour_label = document.createElement("label");
    start_hour_label.htmlFor = `availability_start_hour_${availabilityCount}`;
    start_hour_label.innerHTML = "Inserisci l'ora di inizio";
    let start_hour_input = document.createElement("input");
    start_hour_input.type = "time";
    start_hour_input.name = `availability_start_hour_${availabilityCount}`;
    start_hour_input.id = `availability_start_hour_${availabilityCount}`;
    start_hour_input.required = true;

    let end_hour_label = document.createElement("label");
    end_hour_label.htmlFor = `availability_end_hour_${availabilityCount}`;
    end_hour_label.innerHTML = "Inserisci l'ora di fine";
    let end_hour_input = document.createElement("input");
    end_hour_input.type = "time";
    end_hour_input.name = `availability_end_hour_${availabilityCount}`;
    end_hour_input.id = `availability_end_hour_${availabilityCount}`;
    end_hour_input.required = true;

    div.appendChild(start_hour_label);
    div.appendChild(start_hour_input);
    div.appendChild(end_hour_label);
    div.appendChild(end_hour_input);
}

function validateAvailability() {
    let availability_divs = document.getElementsByClassName("availability");
    if (availability_divs.length === 0) {
        return true;
    }

    let availabilityTimes = [];

    for (let i = 0; i < availability_divs.length; i++) {
        let month_div = document.getElementById(`availability_month_${i}`);
        let day_div = document.getElementById(`availability_day_${i}`);
        let start_hour = document.getElementById(`availability_start_hour_${i}`);
        let end_hour = document.getElementById(`availability_end_hour_${i}`);

        if (!month_div || !day_div || !start_hour || !end_hour) {
            insertErrorMessage(availability_divs[i], "Compila tutti i campi.");
            return false;
        }

        let months_checked = month_div.querySelectorAll('input[type="checkbox"]:checked');
        if (months_checked.length === 0) {
            insertErrorMessage(month_div, "Selezionare almeno un mese.");
            return false;
        }
        let days_checked = day_div.querySelectorAll('input[type="checkbox"]:checked');
        if (days_checked.length === 0) {
            insertErrorMessage(day_div, "Selezionare almeno un giorno.");
            return false;
        }
        if (start_hour.value === "") {
            insertErrorMessage(start_hour, "Selezionare un'ora di inizio.");
            return false;
        }
        if (end_hour.value === "") {
            insertErrorMessage(end_hour, "Selezionare un'ora di fine.");
            return false;
        }
        if (start_hour.value >= end_hour.value) {
            insertErrorMessage(end_hour, "L'orario di fine deve essere successivo a quello di inizio.");
            return false;
        }
        availabilityTimes.push({
            months: Array.from(months_checked).map(e => e.value),
            days: Array.from(days_checked).map(e => e.value),
            start: start_hour.value,
            end: end_hour.value
        });
    }
    for (let i = 0; i < availabilityTimes.length; i++) {
        for (let j = i + 1; j < availabilityTimes.length; j++) {
            if (checkOverlap(availabilityTimes[i], availabilityTimes[j])) {
                insertErrorMessage(availability_divs[i], "Le disponibilità non possono essere sovrapposte.");
                insertErrorMessage(availability_divs[j], "Le disponibilità non possono essere sovrapposte.");
                return false;
            }
        }
    }
    return true;
}

function checkOverlap(time1, time2) {
    // Controlla se i mesi e i giorni si sovrappongono
    let monthsOverlap = time1.months.some(month => time2.months.includes(month));
    let daysOverlap = time1.days.some(day => time2.days.includes(day));

    if (monthsOverlap && daysOverlap) {
        // Controlla se gli orari si sovrappongono
        return (time1.start < time2.end && time1.end > time2.start);
    }
    return false;
}

function removeAvailability(divId) {
    removeErrorDivs();
    let availability_div = document.getElementById(divId);
    if (availability_div) {
        availability_div.remove();
    }

    let availability_divs = document.getElementsByClassName("availability");
    availabilityCount = availability_divs.length;

    for (let i = 0; i < availability_divs.length; i++) {
        availability_divs[i].id = `availability_div_${i}`;
        let month_div = availability_divs[i].querySelector('[id^="availability_month_"]');
        let day_div = availability_divs[i].querySelector('[id^="availability_day_"]');
        let start_hour = availability_divs[i].querySelector('[id^="availability_start_hour_"]');
        let end_hour = availability_divs[i].querySelector('[id^="availability_end_hour_"]');
        let remove_button = availability_divs[i].querySelector('input[type="button"]');

        if (month_div) month_div.id = `availability_month_${i}`;
        if (day_div) day_div.id = `availability_day_${i}`;
        if (start_hour) start_hour.id = `availability_start_hour_${i}`;
        if (end_hour) end_hour.id = `availability_end_hour_${i}`;

        if (remove_button) {
            remove_button.setAttribute('onclick', `removeAvailability('availability_div_${i}')`);
        }
    }
}

function addAvailability() {
    if(!validateAvailability()) {
        return;
    }

    let availability_div = document.createElement("div");

    let availability_title = document.createElement("h3");
    availability_title.innerHTML = "Disponibilità " + (availabilityCount + 1);
    availability_div.appendChild(availability_title);

    addMonth(availability_div);
    addWeekDay(availability_div);
    addHour(availability_div);

    let remove_button = document.createElement("input");
    remove_button.type = "button";
    remove_button.value = "Rimuovi";
    remove_button.onclick = function() {
        removeAvailability(availability_div.id);
    };
    availability_div.appendChild(remove_button);

    availability_div.id = `availability_div_${availabilityCount}`;
    availability_div.className = "availability";
    availabilityCount++;

    let add_availability_button = document.getElementById("add_availability_button");

    add_availability_button.parentNode.insertBefore(availability_div, add_availability_button);
}