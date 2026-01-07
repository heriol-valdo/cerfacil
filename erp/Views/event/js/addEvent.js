
// Barre de recherche
function removeAccents(str) { // Retire accents
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

function filter(searchBarId, itemClass) {
    const searchInput = document.getElementById(searchBarId).value.toLowerCase();
    const items = document.getElementsByClassName(itemClass);

    for (let i = 0; i < items.length; i++) {
        const itemText = items[i].textContent || items[i].innerText;
        if (itemText.toLowerCase().indexOf(searchInput) > -1) {
            items[i].style.display = "";
        } else {
            items[i].style.display = "none";
        }
    }
}

// Affichage / Masquage options
// - En fonction de : Type event
document.getElementById('event_type').addEventListener('change', function () {
    const toggleButton = document.getElementById('toggleUserList');
    const usersSelectorContainer = document.getElementById('user-selector-container');
    const sessionsSelectorContainer = document.getElementById('sessions-selector-container');

    if (this.value === '1') {
        toggleButton.classList.remove('hidden');
        sessionsSelectorContainer.classList.remove('hidden');

        usersSelectorContainer.classList.add('hidden');
    } else if (this.value === '3') {
        toggleButton.classList.add('hidden');
        sessionsSelectorContainer.classList.add('hidden');

        usersSelectorContainer.classList.remove('hidden');
    }
});
// - En fonction de : ID Modalités
document.getElementById('event_id_modalites').addEventListener('change', function () {
    const salleContainer = document.getElementById('salle-selector-container');
    if (this.value === '2') {
        salleContainer.classList.add('hidden');
    } else {
        salleContainer.classList.remove('hidden');
    }
});



// Bouton : Toggle selector Users
document.getElementById('toggleUserList').addEventListener('click', function () {
    const userListContainer = document.getElementById('user-selector-container');
    userListContainer.classList.toggle('hidden');
});

// Bouton : Submit
document.getElementById('event_type').addEventListener('change', function () {
    const submitButton = document.getElementById('submitButton');
    if (this.value === "1" || this.value === "2" || this.value === "3") {
        submitButton.disabled = false;
    } else {
        submitButton.disabled = true;
    }
});


function validatePeriod(debutId, finId, errorId, radioButtonId) {
    const debutElement = document.getElementById(debutId);
    const finElement = document.getElementById(finId);
    const errorElement = document.getElementById(errorId);
    const submitButton = document.getElementById('submitButton');
    const radioButton = radioButtonId ? document.getElementById(radioButtonId) : null;
    const otherRadioButton = radioButtonId === 'eventOption1' ? document.getElementById('eventOption2') : document.getElementById('eventOption1');
    const otherInput = radioButtonId === 'eventOption1' ? document.getElementById('event_nbOccurences') : document.getElementById('event_dateFin');

    function addOneHourToTime(inputValue) {
        console.log("Input value:", inputValue);
    
        let time;
        if (inputValue.includes("T")) {
            time = new Date(inputValue);
        } else {
            time = new Date(`1970-01-01T${inputValue}:00`);
        }
    
        time.setHours(time.getHours() + 1);
        console.log("Time after increment:", time);
    
        if (inputValue.includes("T")) {
            const year = time.getFullYear();
            const month = (time.getMonth() + 1).toString().padStart(2, '0');
            const day = time.getDate().toString().padStart(2, '0');
            const hours = time.getHours().toString().padStart(2, '0');
            const minutes = time.getMinutes().toString().padStart(2, '0');
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        } else {
            const hours = time.getHours().toString().padStart(2, '0');
            const minutes = time.getMinutes().toString().padStart(2, '0');
            return `${hours}:${minutes}`;
        }
    }

    function checkValidity() {
        const debut = debutElement.value;
        const fin = finElement.value;

        if (radioButton && !radioButton.checked) {
            submitButton.disabled = false;
            errorElement.style.display = 'none';
            return;
        }

        if (debut && fin) {
            if (fin >= debut) {
                submitButton.disabled = false;
                errorElement.style.display = 'none';
            } else {
                submitButton.disabled = true;
                errorElement.style.display = 'block';
            }
        } else {
            submitButton.disabled = true;
            errorElement.style.display = 'none';
        }
    }

    // Attach event listeners
    debutElement.addEventListener('change', function () {
        if (!finElement.value) {
            finElement.value = addOneHourToTime(this.value);
        }
        checkValidity();
    });

    finElement.addEventListener('change', checkValidity);

    if (radioButton) {
        radioButton.addEventListener('change', function () {
            if (this.checked) {
                finElement.disabled = false;
                otherInput.disabled = true;
                otherInput.value = ''; // Clear the other input
                errorElement.style.display = 'none'; // Hide error when switching
            } else {
                finElement.disabled = true;
                finElement.value = ''; // Clear the value if not checked
            }
            checkValidity();
        });

        // Handle initial state of the radio button (e.g., page load)
        if (!radioButton.checked) {
            finElement.disabled = true;
            finElement.value = ''; // Clear the value if not checked
            errorElement.style.display = 'none';
        }
    }

    // Initial validation check
    checkValidity();
}

// Initialisation des checks de période
validatePeriod('event_debut', 'event_fin', 'dateError', '');
validatePeriod('event_heureDebut', 'event_heureFin', 'recurrentHeureError', '');
validatePeriod('event_dateDebut', 'event_dateFin', 'recurrentDateError', 'eventOption1');

// Add this to handle the second option
document.getElementById('eventOption2').addEventListener('change', function() {
    if (this.checked) {
        document.getElementById('event_nbOccurences').disabled = false;
        document.getElementById('event_dateFin').disabled = true;
        document.getElementById('event_dateFin').value = ''; // Clear date input
        document.getElementById('recurrentDateError').style.display = 'none'; // Hide error
    } else {
        document.getElementById('event_nbOccurences').disabled = true;
        document.getElementById('event_nbOccurences').value = ''; // Clear occurrences input
    }
});


// Script pour le jours selector
const dayButtons = document.querySelectorAll('.add-event-jour-button');
const hiddenInputs = document.querySelectorAll('.add-event-hidden-input');

dayButtons.forEach((button, index) => {
    button.addEventListener('click', () => {
        // Toggle active class to show selection
        button.classList.toggle('active');

        // Update the corresponding hidden input value
        const input = hiddenInputs[index];
        if (button.classList.contains('active')) {
            input.value = button.getAttribute('data-day');
        } else {
            input.value = "";
        }
    });
});

const getSelectedDays = () => {
    return Array.from(hiddenInputs)
        .map(input => input.value)
        .filter(value => value !== "");
};

// Active / Désactive : Choix NbOccurence ou dateFin
function toggleInputs() {
    const dateRadio = document.getElementById('eventOption1');
    const occurrencesRadio = document.getElementById('eventOption2');
    const dateInput = document.getElementById('event_dateFin');
    const occurrencesInput = document.getElementById('event_nbOccurences');

    dateInput.disabled = !dateRadio.checked;
    occurrencesInput.disabled = !occurrencesRadio.checked;
}

document.getElementById('eventOption1').addEventListener('change', toggleInputs);
document.getElementById('eventOption2').addEventListener('change', toggleInputs);

// Active / Désactive : Choix période (récurrent / non récurrent)
function toggleSections() {
    const isChecked = document.getElementById('recurrent-switch').checked;

    const recurrentSection = document.getElementById('recurrent-period-selector');
    const nonRecurrentSection = document.getElementById('no-recurrent-period-selector');

    if (isChecked) { // Si Récurrent
        recurrentSection.classList.remove('hidden');
        document.getElementById('event_heureDebut').setAttribute('required', 'required');
        document.getElementById('event_heureFin').setAttribute('required', 'required');
        document.getElementById('event_dateDebut').setAttribute('required', 'required');

        document.getElementById('event_debut').removeAttribute('required');
        document.getElementById('event_fin').removeAttribute('required');
        nonRecurrentSection.classList.add('hidden');
    } else { // Si non-récurrent
        nonRecurrentSection.classList.remove('hidden');
        document.getElementById('event_debut').setAttribute('required', 'required');
        document.getElementById('event_fin').setAttribute('required', 'required');

        document.getElementById('event_heureDebut').removeAttribute('required');
        document.getElementById('event_heureFin').removeAttribute('required');
        document.getElementById('event_dateDebut').removeAttribute('required');
        recurrentSection.classList.add('hidden');
    }
}

const addEventForm = document.getElementById("addEventForm");
const addEventFormController = "../../controller/Event/eventActionController.php";

addEventForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    var formdata = (window.FormData) ? new FormData(this) : null;
    var formData = (formdata !== null) ? formdata : formdata.serialize();

    try {
        const response = await fetch(addEventFormController , {
            method: "POST",
            body: formData,
        });

        const result = await response.json();
        if (result.valid) {
            showToast(true, result.valid, "Ajout réussi");
            setTimeout(() => {
                let queryParams = [];
            if (selectedUser) {
                queryParams.push(`selectedUser=${selectedUser}`);
            }
            if (selectedSession) {
                queryParams.push(`selectedSession=${selectedSession}`);
            }
            if (centreId) {
                queryParams.push(`centreId=${centreId}`);
            }

            const queryString = queryParams.length ? `?${queryParams.join('&')}` : '';

            window.location.href = window.location.pathname + queryString;
            }, 500);
        } else {
            showToast(false, result.erreur, "Erreur lors de l'ajout");
        }
    } catch (error) {
        showToast(false, error, "Erreur au try view");
    }
});

document.getElementById('recurrent-switch').addEventListener('change', toggleSections);
window.addEventListener('load', toggleSections);