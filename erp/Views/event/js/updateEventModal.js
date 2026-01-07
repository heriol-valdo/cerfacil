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

// Apparition/disparition modal en fonction clic update
$('#editButton').click(function () {
    var eventId = $(this).data('eventId');
    if ($(this).data('recurrenceId')) {
        var recurrenceId = $(this).data('recurrenceId');
    }
    $('#eventInfoModal').modal('hide');
    previousModal = $('#eventInfoModal');

    // Fetch event details
    fetchEventDetails(eventId);
});

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

async function fetchEventDetails(eventId) {
    try {
        const response = await fetch("../../controller/Event/eventActionController.php", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'getEventDetails',
                event_id: eventId
            }),
        });
        const result = await response.json();
        if (result.data) {
            $('#eventUpdateModal').modal('show');
            $('#eventUpdateModal').on('shown.bs.modal', function () {
                populateUpdateForm(result.data);
            });

        } else {
            showToast(false, result.message, "Erreur lors de la récupération des détails de l'événement");
        }
    } catch (error) {
        console.error("Erreur réseau : ", error);
        showToast(false, 'Une erreur réseau s\'est produite. ', "Erreur");
    }
}

function validatePeriod_edit(debutId, finId, errorId, radioButtonId) {
    const debutElement = document.getElementById(debutId);
    const finElement = document.getElementById(finId);
    const errorElement = document.getElementById(errorId);
    const submitButton = document.getElementById('edit-submitButton');
    const radioButton = radioButtonId ? document.getElementById(radioButtonId) : null;
    const otherRadioButton = radioButtonId === 'edit-eventOption1' ? document.getElementById('edit-eventOption2') : document.getElementById('edit-eventOption1');
    const otherInput = radioButtonId === 'edit-eventOption1' ? document.getElementById('edit-event_nbOccurences') : document.getElementById('edit-event_dateFin');

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
validatePeriod_edit('edit-event_jourDebut', 'edit-event_jourFin', 'edit-dateError', '');
validatePeriod_edit('edit-event_heureDebut', 'edit-event_heureFin', 'edit-recurrentHeureError', '');
validatePeriod_edit('edit-event_dateDebut', 'edit-event_dateFin', 'edit-recurrentDateError', 'edit-eventOption1');


// Apparition participants selector
function toggleParticipantSelector() {
    const toggleButton = document.getElementById('edit-toggleUserList');
    const eventTypeValue = document.getElementById('edit-event_type').value;
    const usersSelectorContainer = document.getElementById('edit-user-selector-container');
    const sessionsSelectorContainer = document.getElementById('edit-sessions-selector-container');

    if (eventTypeValue == '1') {
        toggleButton.classList.remove('hidden');
        sessionsSelectorContainer.classList.remove('hidden');

        usersSelectorContainer.classList.add('hidden');
    } else if (eventTypeValue == '3') {
        toggleButton.classList.add('hidden');
        sessionsSelectorContainer.classList.add('hidden');

        usersSelectorContainer.classList.remove('hidden');
    }
}

// Apparition salle selector
function toggleSalleSelector() {
    const salleContainer = document.getElementById('edit-salle-selector-container');
    const modalitesValue = document.getElementById('edit-event_id_modalites').value;

    if (modalitesValue == '2') {
        salleContainer.classList.add('hidden');
    } else {
        salleContainer.classList.remove('hidden');
    }
}

// Active / Désactive : Choix NbOccurence ou dateFin
function toggleInputs() {
    const dateRadio = document.getElementById('edit-eventOption1');
    const occurrencesRadio = document.getElementById('edit-eventOption2');
    const dateInput = document.getElementById('edit-event_dateFin');
    const occurrencesInput = document.getElementById('edit-event_nbOccurences');

    dateInput.disabled = !dateRadio.checked;
    occurrencesInput.disabled = !occurrencesRadio.checked;
}

function toggleSections() {
    const isChecked = document.getElementById('edit-recurrent-switch').checked;

    const recurrentSection = document.getElementById('edit-recurrent-period-selector');
    const nonRecurrentSection = document.getElementById('edit-no-recurrent-period-selector');

    const heureDebutContainer = document.getElementById('edit-heureDebut-container');
    const heureDebut = document.getElementById('edit-event_heureDebut');
    const heureFinContainer = document.getElementById('edit-heureFin-container');
    const heureFin = document.getElementById('edit-event_heureFin');

    const jourDebutContainer = document.getElementById('edit-jourDebut-container');
    const jourDebut = document.getElementById('edit-event_jourDebut');
    const jourFinContainer = document.getElementById('edit-jourFin-container');
    const jourFin = document.getElementById('edit-event_jourFin');

    const dateDebut = document.getElementById('edit-event_dateDebut');
    const dateFin = document.getElementById('edit-event_dateFin');

    if (isChecked) { // Si Récurrent
        recurrentSection.classList.remove('hidden');
        dateDebut.setAttribute('required', 'required');

        heureDebutContainer.classList.remove('col-md-3');
        heureFinContainer.classList.remove('col-md-3');
        heureDebutContainer.classList.add('col-md-6');
        heureFinContainer.classList.add('col-md-6');

        jourDebut.removeAttribute('required');
        jourFin.removeAttribute('required');
        jourDebutContainer.classList.add('hidden');
        jourFinContainer.classList.add('hidden');
    } else { // Si non-récurrent
        nonRecurrentSection.classList.remove('hidden');
        jourDebut.setAttribute('required', 'required');
        jourFin.setAttribute('required', 'required');

        heureFinContainer.classList.remove('col-md-6');
        heureDebutContainer.classList.remove('col-md-6');
        heureDebutContainer.classList.add('col-md-3');
        heureFinContainer.classList.add('col-md-3');

        dateDebut.removeAttribute('required');
        recurrentSection.classList.add('hidden');
        jourDebutContainer.classList.remove('hidden');
        jourFinContainer.classList.remove('hidden');
    }
}

function populateUpdateForm(eventDetails) {
    $('#edit-event_nom').val(eventDetails.nom);
    $('#edit-event_id_modalites').val(eventDetails.id_modalites);
    $('#edit-event_type').val(eventDetails.id_types_event);
    $('#edit-event_url').val(eventDetails.url);
    $('#edit-event_description').val(eventDetails.description);

    const eventTypeDropdown = document.getElementById('edit-event_type');;
    eventTypeDropdown.value = eventDetails.id_types_event;
    eventTypeDropdown.classList.add('grayed-out-select');

    /*if (eventDetails.id_recurrence) {
        $('#recurrent-switch').prop('checked', true);
    }*/

    if (eventDetails.id_salles) {
        
        const salleRadioButton = document.getElementById('edit-salle-' + eventDetails.id_salles);
        if (salleRadioButton) {
            salleRadioButton.checked = true;
        } else {
            console.error("Salle radio button not found ID:", eventDetails.id_salles);
        }
    }

    if (eventDetails.sessions && Array.isArray(eventDetails.sessions)) {
        eventDetails.sessions.forEach(function (session) {
            const checkboxId = 'edit-session-' + session.id.trim();
            const checkbox = document.getElementById(checkboxId);
            if (checkbox) {
                checkbox.checked = true;
            } else {
                console.error("Checkbox non trouvée pour :", session.id);
            }
        });

    }

    if (eventDetails.users && Array.isArray(eventDetails.users)) {
        eventDetails.users.forEach(function (user) {
            const checkbox = document.getElementById('edit-user-' + user.id_users.trim());
            if (checkbox) {
                checkbox.checked = true;
            } else {
                console.log("Checkbox non trouvée pour :", user.id_users);
            }
        });
    }

    toggleSalleSelector();
    toggleParticipantSelector()

    // Il faut que eventDetails.debut/fin soit au format "YYYY-MM-DD HH:MM"
    let dateTimePattern = /(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}):\d{2}/;

    let debutMatch = eventDetails.debut.match(dateTimePattern);
    let finMatch = eventDetails.fin.match(dateTimePattern);

    if (debutMatch) {
        let debutDate = debutMatch[1].split(' ').reverse().join('-');
        let debutTime = debutMatch[2];

        $('input[name="event_jourDebut"]').val(debutDate);
        $('input[name="event_heureDebut"]').val(debutTime);
    }

    if (finMatch) {
        let finDate = finMatch[1].split(' ').reverse().join('-');
        let finTime = finMatch[2];

        $('input[name="event_jourFin"]').val(finDate);
        $('input[name="event_heureFin"]').val(finTime);
    }
}

async function updateElement(eventId, range) {
    let formData = {};
    const form = document.querySelector('#updateEventForm');
    new FormData(form).forEach((value, key) => {
        if (formData[key]) {
            if (!Array.isArray(formData[key])) {
                formData[key] = [formData[key]];
            }
            formData[key].push(value);
        } else {
            formData[key] = value;
        }
    });

    try {
        const response = await fetch("../../controller/Event/eventActionController.php", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: "updateEvent",
                range: range,
                event_id: eventId,
                data: formData
            }),
        });
        const result = await response.json();
        if (result.erreur) {
            return { success: false, message: result.erreur };
        } else if (result.valid) {
            return { success: true, message: result.valid };
        } else {
            return { success: false, message: 'An unknown error occurred.' };
        }
    } catch (error) {
        console.error("Network error: ", error);
        return { success: false, message: 'A network error occurred.' };
    }
}

// Script pour le jours selector
const daysButtons = document.querySelectorAll('.edit-jour-button');
const hiddensInputs = document.querySelectorAll('.edit-hidden-input');

daysButtons.forEach((button, index) => {
    button.addEventListener('click', () => {
        button.classList.toggle('active');

        const input = hiddensInputs[index];
        if (button.classList.contains('active')) {
            input.value = button.getAttribute('data-day');
        } else {
            input.value = "";
        }
    });
});

const getSelectedDay = () => {
    return Array.from(hiddensInputs)
        .map(input => input.value)
        .filter(value => value !== "");
};

// Affichage / Masquage options
document.getElementById('edit-event_type').addEventListener('change', toggleParticipantSelector);
document.getElementById('edit-event_id_modalites').addEventListener('change', toggleSalleSelector);
document.getElementById('edit-recurrent-switch').addEventListener('change', toggleSections);

document.getElementById('edit-eventOption2').addEventListener('change', function () {
    if (this.checked) {
        document.getElementById('edit-event_nbOccurences').disabled = false;
        document.getElementById('edit-event_dateFin').disabled = true;
        document.getElementById('edit-event_dateFin').value = '';
        document.getElementById('edit-recurrentDateError').style.display = 'none';
    } else {
        document.getElementById('edit-event_nbOccurences').disabled = true;
        document.getElementById('edit-event_nbOccurences').value = '';
    }
});

document.getElementById('edit-eventOption1').addEventListener('change', toggleInputs);
document.getElementById('edit-eventOption2').addEventListener('change', toggleInputs);

// Bouton : Toggle selector Users
document.getElementById('edit-toggleUserList').addEventListener('click', function () {
    const userListContainer = document.getElementById('edit-user-selector-container');
    userListContainer.classList.toggle('hidden');
});

document.getElementById('edit-all-switch').addEventListener('change', function () {
    const infoMessage = document.getElementById('edit-edit-all-info');

    if (this.checked) {
        infoMessage.style.display = 'block';
    } else {
        infoMessage.style.display = 'none';
    }
});

// Rouvre modal précédente 
$('#updateEventModal .btn-secondary').click(function () {
    $('#updateEventModal').modal('hide');

    if (previousModal) {
        previousModal.modal('show');
    }
});

// Modification event
$('#edit-submitButton').click(async function (e) {
    e.preventDefault();

    var eventId = $('#editButton').data('eventId');
    let range = "";

    const editRecurrentChecked = document.getElementById('edit-recurrent-switch').checked;
    const editAllChecked = document.getElementById('edit-all-switch').checked;
    // Sélectionne range en fonction des options sélectionnées
    if (editRecurrentChecked) {
        if (editAllChecked) {
            range = "all";
        } else {
            range = "after";
        }
    } else {
        range = "default";
    }

    const result = await updateElement(eventId, range);
    showToast(result.success, result.message, result.success ? "Modification réussie" : "Erreur lors de la modification");
    if (result.success) {
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
    }
    $('#eventUpdateModal').modal('hide');
});