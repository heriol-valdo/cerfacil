document.addEventListener('DOMContentLoaded', function() {
    const sessionButtonsContainer = document.querySelector('.session-buttons');
    const absencesList = document.getElementById('absences-list');
    const searchInput = document.querySelector('.search-input');
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');
    const periodSelect = document.getElementById('period-select');

    const sessionsPerPage = 5;
    let currentPage = 1;
    let filteredSessions = allSessionsAbsences;
    let currentSessionId = null;

    function formatDateToFrench(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', options);
    }

    function isDateInPeriod(date, period) {
        const now = new Date();
        const absenceDate = new Date(date);
        switch (period) {
            case 'thisWeek':
                const weekStart = new Date(now.setDate(now.getDate() - now.getDay()));
                const weekEnd = new Date(now.setDate(now.getDate() - now.getDay() + 6));
                return absenceDate >= weekStart && absenceDate <= weekEnd;
            case 'thisMonth':
                return absenceDate.getMonth() === now.getMonth() && absenceDate.getFullYear() === now.getFullYear();
            case 'lastMonth':
                const lastMonth = new Date(now.getFullYear(), now.getMonth() - 1);
                return absenceDate.getMonth() === lastMonth.getMonth() && absenceDate.getFullYear() === lastMonth.getFullYear();
            case 'thisYear':
                return absenceDate.getFullYear() === now.getFullYear();
            default:
                return true; // 'all' or any other value
        }
    }

    function filterAbsencesByPeriod(absences, period) {
        if (period === 'all') return absences;
        return absences.filter(absence => isDateInPeriod(absence.dateDebut, period) || isDateInPeriod(absence.dateFin, period));
    }


    function updateAbsencesList(sessionId) {
        absencesList.innerHTML = '';
        currentSessionId = sessionId;
        const session = filteredSessions.find(session => session.id === sessionId);
        if (!session) return;

        const sessionName = session.name;
        let sessionAbsences = absences[sessionName] || [];
        
        const selectedPeriod = periodSelect.value;
        sessionAbsences = filterAbsencesByPeriod(sessionAbsences, selectedPeriod);

        if (sessionAbsences.length > 0) {
            sessionAbsences.forEach(absence => {
                const div = document.createElement('div');
                div.className = 'absence-item';
                div.innerHTML = `
                    <div class="absence-header">
                        <span class="absence-name">${absence.etudiant_prenom} ${absence.etudiant_nom}</span>
                        <div class="absence-justification-container">
                            <span class="absence-justification ${absence.justificatif ? 'justified' : 'not-justified'}">
                                ${absence.justificatif ? 'Justifié' : 'Non justifié'}
                            </span>
                            ${absence.justificatif ? `
                                <button class="justificatif-btn" data-justificatif="${absence.justificatif}">
                                    <i class="fas fa-eye"></i> Voir le justificatif
                                </button>
                            ` : ''}
                        </div>
                    </div>
                    <div class="absence-details">
                        <div class="absence-dates">
                            <span>Du: ${formatDateToFrench(absence.dateDebut)}</span>
                            <span class="date-separator">Au: ${formatDateToFrench(absence.dateFin)}</span>
                        </div>
                        <div class="absence-reason">Raison: ${absence.raison}</div>
                    </div>
                `;
                absencesList.appendChild(div);
            });

            // Ajouter les écouteurs d'événements pour les boutons de justificatif
            document.querySelectorAll('.justificatif-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    showJustificatif(this.dataset.justificatif);
                });
            });
        } else {
            const message = document.createElement('p');
            message.textContent = 'Aucune absence pour cette session dans la période sélectionnée.';
            absencesList.appendChild(message);
        }
    }

    function showJustificatif(justificatifUrl) {
        const modal = document.getElementById('justificatifModal');
        const modalContent = document.getElementById('justificatifContent');
        const fileExtension = justificatifUrl.split('.').pop().toLowerCase();

        if (['pdf', 'jpg', 'jpeg', 'png'].includes(fileExtension)) {
            if (fileExtension === 'pdf') {
                modalContent.innerHTML = `<embed src="${justificatifUrl}" type="application/pdf" width="100%" height="600px" />`;
            } else {
                modalContent.innerHTML = `<img src="${justificatifUrl}" alt="Justificatif" style="max-width: 100%; max-height: 600px;" />`;
            }
            modal.style.display = 'block';
        } else {
            alert('Format de fichier non supporté');
        }
    }

    window.onclick = function(event) {
        const modal = document.getElementById('justificatifModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    function renderSessionButtons() {
        const startIndex = (currentPage - 1) * sessionsPerPage;
        const endIndex = startIndex + sessionsPerPage;
        const sessionsToShow = filteredSessions.slice(startIndex, endIndex);

        sessionButtonsContainer.innerHTML = '';
        sessionsToShow.forEach(session => {
            const button = document.createElement('button');
            button.className = 'session-button';
            button.textContent = session.name;
            button.dataset.sessionId = session.id;
            button.addEventListener('click', function() {
                document.querySelectorAll('.session-button').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                updateAbsencesList(this.dataset.sessionId);
            });
            sessionButtonsContainer.appendChild(button);
        });

        prevPageBtn.disabled = currentPage === 1;
        nextPageBtn.disabled = endIndex >= filteredSessions.length;
    }

    function filterSessions() {
        const searchTerm = searchInput.value.toLowerCase();
        filteredSessions = allSessionsAbsences.filter(session => session.name.toLowerCase().includes(searchTerm));
        currentPage = 1;
        renderSessionButtons();
    }

    searchInput.addEventListener('input', filterSessions);

    prevPageBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            renderSessionButtons();
        }
    });

    nextPageBtn.addEventListener('click', () => {
        if (currentPage * sessionsPerPage < filteredSessions.length) {
            currentPage++;
            renderSessionButtons();
        }
    });

    // Add event listener for period select
    periodSelect.addEventListener('change', () => {
        if (currentSessionId) {
            updateAbsencesList(currentSessionId);
        }
    });

    // Initial render
    renderSessionButtons();
    if (filteredSessions.length > 0) {
        updateAbsencesList(filteredSessions[0].id);
        document.querySelector('.session-button').classList.add('active');
    }
});