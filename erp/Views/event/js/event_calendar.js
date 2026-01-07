// Format de la date
function formatDate(dateStr) {
  const options = {
    //weekday: 'short',
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
    //timeZoneName: 'short'
  };
  const date = new Date(dateStr);
  return date.toLocaleDateString('fr-FR', options).replace(',', '');
}

// 1ere lettre en màj
function firstToUpper(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

// Formate la date pour l'event
function formatEventTimes(start, end) {
  const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
  const startDate = start.toLocaleDateString('fr-FR', options);
  const endDate = end.toLocaleDateString('fr-FR', options);

  if (startDate === endDate) {
    const startTime = start.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    const endTime = end.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    return `${firstToUpper(startDate)}, de ${startTime} à ${endTime}`;
  } else {
    const startTime = start.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    const endTime = end.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    return `${firstToUpper(startDate)}, ${startTime} au ${firstToUpper(endDate)}, ${endTime}`;
  }
}


document.addEventListener('DOMContentLoaded', function () {
  var calendarEl = document.getElementById('calendar');

  var modal = document.querySelector('.modal');
  var modalTrigger = document.querySelector('.modal-trigger');
  var modalOverlay = document.querySelector('.modal-overlay');

  var customButtons = {};
  var headerToolbar = {
    left: 'today',
    center: 'prev title next',
    right: 'dayGridMonth,timeGridWeek'
  };

  // Conditions pour boutons
  if (userRole == 1 || userRole == 3 || userRole == 4) {
    customButtons = {
      addButton: {
        text: 'Ajouter un évènement',
        click: function () {
          $('#eventAddModal').modal('show');
        }
      },
      addCours: {
        text: 'Ajouter un cours',
        click: function () {
          $('#coursAddModal').modal('show');
        }
      }
    };

    headerToolbar = {
      left: 'today',
      center: 'prev title next',
      right: 'addButton addCours dayGridMonth,timeGridWeek'
    };
  }

  var calendar = new FullCalendar.Calendar(calendarEl, {
    themeSystem: 'bootstrap5',
    locale: 'fr',
    buttonText: { // Traductions des boutons
      today: 'Aujourd\'hui',
      month: 'Mois',
      week: 'Semaine',
      day: 'Jour'
    },
    allDaySlot: false, // Désactive champ All-day
    customButtons: customButtons,
    headerToolbar: headerToolbar,
    eventTimeFormat: {
      hour12: false,
      hour: "2-digit",
      minute: "2-digit",
      meridiem: false,
    },
    initialDate: new Date(), // Date initiale
    initialView: 'timeGridWeek', // Vue initiale
    navLinks: true, // can click day/week names to navigate views
    selectable: false,
    selectMirror: true,
    select: arg => eventModal.open(arg.start, arg.end),
    eventClick: openEventInfoModal,
    editable: false,
    dayMaxEvents: true, // allow "more" link when too many events
    events: calendarInfos
  });
  calendar.render();
});

var previousModal = null;

function openEventInfoModal(info) {
  const event = info.event;

  // Titre
  document.getElementById('eventTitle').innerText = event.title;

  // Date
  const formattedEventTimes = event.end ? formatEventTimes(event.start, event.end) : `Le ${capitalizeFirstLetter(formatDate(event.start))}`;
  document.getElementById('eventStart').innerText = formattedEventTimes;

  // Cours
  
  const coursDetailsElement = document.getElementById('cours_details');
  if (event.extendedProps.id_types_event != 2) {
    coursDetailsElement.style.display = 'none';
  } else {
    coursDetailsElement.style.display = 'block';
  }

  // Modalités + Salle apparition
  document.getElementById('modalites_nom').innerText = event.extendedProps.modalites_nom;
  const sallesNomElement = document.getElementById('salles_nom');
  if (event.extendedProps.modalites_nom !== 'Présentiel') {
    sallesNomElement.parentElement.style.display = 'none';
  } else {
    sallesNomElement.parentElement.style.display = 'block';
    sallesNomElement.innerText = event.extendedProps.salles_nom || 'N/A';
  }

  // Lien
  const urlContainer = document.getElementById('urlContainer');
  const urlElement = document.getElementById('url');
  const url = event.extendedProps.url || '';
  const maxCharacters = 30;
  if (url) {
    urlElement.href = url;

    // Si lien trop long
    if (url.length > maxCharacters) {
      const shortUrl = url.substring(0, maxCharacters) + '...';
      urlElement.innerText = shortUrl;
    } else {
      urlElement.innerText = url;
    }
  } else {
    urlContainer.style.display = 'none';
  }

  // Description
  const descriptionElement = document.getElementById('description');
  descriptionElement.value = event.extendedProps.description || '';

  // Cours
  if (event.extendedProps.cours_details) {
    ['formateur_firstname', 'formateur_lastname', 'matiere_nom'].forEach(prop => {
      const element = document.getElementById(prop);
      if (element) {
        element.innerText = event.extendedProps.cours_details[prop] || 'N/A';
      }
    });
  }

  // Set Author details
  document.getElementById('author_firstname').innerText = event.extendedProps.author_firstname || 'N/A';
  document.getElementById('author_lastname').innerText = event.extendedProps.author_lastname || 'N/A';
  document.getElementById('author_role').innerText = event.extendedProps.author_role || 'N/A';

  $('#deleteButton').data('recurrenceId', info.event.extendedProps.id_recurrence);
  $('#deleteButton').data('eventId', info.event.extendedProps.id_event);
  $('#editButton').data('recurrenceId', info.event.extendedProps.id_recurrence);
  $('#editButton').data('eventId', info.event.extendedProps.id_event);

  // Show the modal
  $('#eventInfoModal').modal('show');
}


// Fonction pour rafraîchir les événements du calendrier
function refreshCalendar() {
  if (calendar) {
    calendar.refetchEvents();
  }
}
