document.addEventListener('DOMContentLoaded', function() {
    const formationButtons = document.querySelector('.formation-buttons');
    const sessionList = document.getElementById('session-list');
    const searchInput = document.querySelector('.search-input');
    const prevButton = document.getElementById('prevPage');
    const nextButton = document.getElementById('nextPage');

    sessionFilter.addEventListener('change', function() {
        sessionFilterValue = this.value;
        console.log(sessionFilterValue);
        if (activeFormation) {
            displaySessions(activeFormation);
        }
    });

    console.log(sessionFilterValue)


    const itemsPerPage = 5;
    let currentPage = 1;
    let formationsData = [...formations]; // Store original formations data
    let activeFormation = null; // Keep track of the active formation

    function displayFormations(page) {
        formationButtons.innerHTML = '';
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedFormations = formations.slice(start, end);

        paginatedFormations.forEach(formation => {
            const button = document.createElement('button');
            button.textContent = formation.name;
            button.addEventListener('click', () => {
                setActiveFormation(button, formation.name);
                displaySessions(formation.name);
            });
            if (formation.name === activeFormation) {
                button.classList.add('active');
            }
            formationButtons.appendChild(button);
        });

        prevButton.disabled = page === 1;
        nextButton.disabled = end >= formations.length;

        // Display sessions of the first formation by default if no active formation
        if (paginatedFormations.length > 0 && !activeFormation) {
            setActiveFormation(formationButtons.firstChild, paginatedFormations[0].name);
            displaySessions(paginatedFormations[0].name);
        } else if (paginatedFormations.length === 0) {
            sessionList.innerHTML = '<p>Aucune formation trouvée.</p>';
        }
    }

    function setActiveFormation(button, formationName) {
        // Remove 'active' class from previous active button
        const activeButton = formationButtons.querySelector('.active');
        if (activeButton) {
            activeButton.classList.remove('active');
        }
        // Add 'active' class to clicked button
        button.classList.add('active');
        activeFormation = formationName;
        displaySessions(formationName);
    }

    function displaySessions(formationName) {
        sessionList.innerHTML = '';
        if (sessions[formationName] && sessions[formationName].length > 0) {
            const currentDate = new Date();
            let visibleSessions = 0;
            sessions[formationName].forEach(session => {
                const sessionStartDate = new Date(session.dateDebut);
                const sessionEndDate = new Date(session.dateFin);
                let shouldDisplay = false;
    
                switch(sessionFilterValue) {
                    case 'termine':
                        shouldDisplay = sessionEndDate < currentDate;
                        break;
                    case 'en-cours':
                        shouldDisplay = sessionStartDate <= currentDate && currentDate <= sessionEndDate;
                        break;
                    case 'a-venir':
                        shouldDisplay = sessionStartDate > currentDate;
                        break;
                    default:
                        shouldDisplay = true; // Show all sessions if no filter is selected
                }
    
                if (shouldDisplay) {
                    const sessionDiv = document.createElement('div');
                    sessionDiv.classList.add('session-item');
                    sessionDiv.innerHTML = `
                        <h3>${session.nomSession}</h3>
                        <p>Date de début: ${session.dateDebut}</p>
                        <p>Date de fin: ${session.dateFin}</p>
                        <p>Nombre de places: ${session.nbPlace}</p>
                    `;
                    sessionList.appendChild(sessionDiv);
                    visibleSessions++;
                }
            });
            if (visibleSessions === 0) {
                sessionList.innerHTML = '<p>Aucune session ne correspond aux critères de filtrage pour cette formation.</p>';
            }
        } else {
            sessionList.innerHTML = '<p>Aucune session disponible pour cette formation.</p>';
        }
    }

    function filterFormations() {
        const searchTerm = searchInput.value.toLowerCase();
        if (searchTerm === '') {
            formations = [...formationsData]; // Revert to original formations when search is empty
        } else {
            formations = formationsData.filter(formation => 
                formation.name.toLowerCase().includes(searchTerm)
            );
        }
        currentPage = 1; // Reset to first page after each search
        displayFormations(currentPage);
    }

    searchInput.addEventListener('input', filterFormations);

    prevButton.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            displayFormations(currentPage);
        }
    });

    nextButton.addEventListener('click', () => {
        if (currentPage < Math.ceil(formations.length / itemsPerPage)) {
            currentPage++;
            displayFormations(currentPage);
        }
    });

    // Initial display
    displayFormations(currentPage);
});