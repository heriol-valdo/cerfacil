document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const addCoursForm = document.getElementById('addCoursForm');
    const isRecurrentCheckbox = document.getElementById('is_recurrent');
    const nonRecurrentFields = document.getElementById('non-recurrent-fields');
    const recurrentFields = document.getElementById('recurrent-fields');
    const modalitesSelect = document.getElementById('id_modalites');
    const salleGroup = document.getElementById('salleGroup');
    const urlGroup = document.getElementById('urlGroup');
    const formateurGroup = document.getElementById('formateurGroup');
    const additionalSessionsContainer = document.getElementById('sessions-container');
    const additionalSessionCheckboxes = additionalSessionsContainer.querySelectorAll('input[type="checkbox"]');
    const jourButtons = document.querySelectorAll('.jour-button');
    const userToken = JSON.parse(document.getElementById('userToken').value);
    const finDateRadio = document.getElementById('finDate');
    const finOccurencesRadio = document.getElementById('finOccurences');
    const dateFin = document.getElementById('dateFin');
    const nbOccurences = document.getElementById('nbOccurences');

    // Configuration utilisateur
    const userInfo = getUserInfo();

    // Initialisation
    initializeForm();
    setupEventListeners();

    // Fonctions principales
    function initializeForm() {
        if (userInfo.role == 4) { // Si l'utilisateur est un formateur
            const formateurSelect = document.getElementById('id_formateurs');
            
            if (formateurGroup && formateurSelect) {

                formateurGroup.style.display = 'none';
                
                // Créer un champ caché pour l'ID du formateur
                const hiddenFormateurInput = document.createElement('input');
                hiddenFormateurInput.type = 'hidden';
                hiddenFormateurInput.name = 'id_formateurs';
                hiddenFormateurInput.value = userInfo.id;
                
                // Remplacer le select par le champ caché
                formateurGroup.appendChild(hiddenFormateurInput);
                formateurSelect.remove();
            }
        }
        
        toggleRecurrentFields();
        toggleModaliteFields();
        toggleFinFields();
        updateSessionsDisplay();
        sendSelectedSessions();
    }

    function setupEventListeners() {
        isRecurrentCheckbox.addEventListener('change', toggleRecurrentFields);
        modalitesSelect.addEventListener('change', toggleModaliteFields);
        addCoursForm.addEventListener('submit', handleFormSubmit);
    
        const debouncedSendSelectedSessions = debounce(sendSelectedSessions, 500);
        additionalSessionCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', debouncedSendSelectedSessions);
        });

        jourButtons.forEach(button => {
            button.addEventListener('click', function() {
                this.classList.toggle('active');
            });
        });

        finDateRadio.addEventListener('change', toggleFinFields);
        finOccurencesRadio.addEventListener('change', toggleFinFields);
    }

    function handleFormSubmit(e) {
        e.preventDefault();
        try {
            if (!validateForm()) {
                return;
            }
            const formData = new FormData(addCoursForm);
            console.log('Valeur de fréquence avant prepareFormData:', document.getElementById('frequence').value);
            const data = prepareFormData(formData);
            
            console.log('Données à envoyer:', data);
    
            if (data.is_recurrent) {
                validateFrequencyAndDays(data);
            }
            sendFormData(data);
        } catch (error) {
            console.error('Erreur capturée:', error);
            showToast(false, error.message, "Erreur de validation");
        }
    }

    function validateForm() {
        let isValid = true;
        const errorMessages = [];

        const selectedSessions = getSelectedSessions();
        if (selectedSessions.length === 0) {
            isValid = false;
            errorMessages.push("Veuillez sélectionner au moins une session.");
        }

        if (!isRecurrentCheckbox.checked) {
            const debut = new Date(document.getElementById('debut').value);
            const fin = new Date(document.getElementById('fin').value);
            if (fin <= debut) {
                isValid = false;
                errorMessages.push("La date de fin doit être postérieure à la date de début.");
            }
        } else {
            const heureDebut = document.getElementById('heureDebut').value;
            const heureFin = document.getElementById('heureFin').value;
            if (heureFin <= heureDebut) {
                isValid = false;
                errorMessages.push("L'heure de fin doit être postérieure à l'heure de début.");
            }

            if (finDateRadio.checked && dateFin.value === "") {
                isValid = false;
                errorMessages.push("Veuillez spécifier une date de fin pour l'événement récurrent.");
            } else if (finOccurencesRadio.checked && (nbOccurences.value === "" || parseInt(nbOccurences.value) < 1)) {
                isValid = false;
                errorMessages.push("Veuillez spécifier un nombre valide d'occurrences (minimum 1).");
            }

            if (getSelectedDays().length === 0) {
                isValid = false;
                errorMessages.push("Veuillez sélectionner au moins un jour pour l'événement récurrent.");
            }
        }

        const modalite = modalitesSelect.value;
        if (modalite === '1' && document.getElementById('id_salles').value === "") {
            isValid = false;
            errorMessages.push("Veuillez sélectionner une salle pour le cours en présentiel.");
        }
        if (modalite === '2' && document.getElementById('addCoursUrl').value === "") {
            isValid = false;
            errorMessages.push("Veuillez fournir une URL pour le cours à distance.");
        }

        if (!isValid) {
            showErrors(errorMessages);
        }

        return isValid;
    }

    function validateFrequencyAndDays(data) {
        const frequencyValue = parseInt(data.frequence[0]);
        const frequencyUnit = data.frequence[1];
        const selectedDays = data.jours;

        if (frequencyValue <= 0) {
            throw new Error("La valeur de fréquence doit être supérieure à 0");
        }

        if (frequencyUnit === 'semaine') {
            if (selectedDays.length === 0) {
                throw new Error("Pour une fréquence hebdomadaire, vous devez sélectionner au moins un jour de la semaine");
            }
        } else if (frequencyUnit === 'mois') {
            if (selectedDays.length === 0 || selectedDays.length > 7) {
                throw new Error("Pour une fréquence mensuelle, vous devez sélectionner entre 1 et 7 jours de la semaine");
            }
        }
    }

    function showErrors(messages) {
        const errorContainer = document.getElementById('error-container') || createErrorContainer();
        errorContainer.innerHTML = messages.map(msg => `<p>${msg}</p>`).join('');
    }

    function createErrorContainer() {
        const container = document.createElement('div');
        container.id = 'error-container';
        container.className = 'alert alert-danger mt-3';
        addCoursForm.prepend(container);
        return container;
    }

    function getUserInfo() {
        return {
            role: userToken.role,
            id: userToken.id,
            centre: userToken.centre
        };
    }

    function toggleRecurrentFields() {
        const isRecurrent = isRecurrentCheckbox.checked;
        nonRecurrentFields.style.display = isRecurrent ? 'none' : 'block';
        recurrentFields.style.display = isRecurrent ? 'block' : 'none';
        
        toggleRequiredAttributes(nonRecurrentFields, !isRecurrent);
        toggleRequiredAttributes(recurrentFields, isRecurrent);
    }

    function toggleFinFields() {
        dateFin.disabled = !finDateRadio.checked;
        nbOccurences.disabled = !finOccurencesRadio.checked;

        if (finDateRadio.checked) {
            dateFin.setAttribute('required', 'required');
            nbOccurences.removeAttribute('required');
        } else {
            nbOccurences.setAttribute('required', 'required');
            dateFin.removeAttribute('required');
        }
    }

    function toggleRequiredAttributes(container, isRequired) {
        const inputs = container.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (isRequired) {
                input.setAttribute('required', 'required');
            } else {
                input.removeAttribute('required');
            }
        });
    }

    function toggleModaliteFields() {
        const modalite = modalitesSelect.value;
        salleGroup.style.display = (modalite === '1' || modalite === '3') ? 'block' : 'none';
        urlGroup.style.display = (modalite === '2' || modalite === '3') ? 'block' : 'none';
        
        document.getElementById('id_salles').required = (modalite === '1' || modalite === '3');
        document.getElementById('url').required = (modalite === '2' || modalite === '3');
    }

    function prepareFormData(formData) {
        // Convertit FormData en objet JavaScript standard
        const data = Object.fromEntries(formData.entries());
        console.log("data" + data)
        
        // Conversions de types et traitements essentiels
        data.is_recurrent = data.is_recurrent === 'on'; // Convertit en booléen
        data.event_sessions = getSelectedSessions(); // Récupère les sessions sélectionnées
        data.id_modalites = Number(data.id_modalites);
        data.id_matieres = Number(data.id_matieres);
        data.id_formateurs = Number(data.id_formateurs);
        data.id_salles = data.id_salles ? Number(data.id_salles) : null;
    
        if (data.is_recurrent) {
            // Traitement spécifique pour les événements récurrents
            data.jours = getSelectedDays(); // Récupère les jours sélectionnés
            data.frequence = [
                parseInt(document.getElementById('numberFrequence[]').value, 10) || 1, // Valeur de fréquence (par défaut 1)
                document.getElementById('frequenceUnit').value // Unité de fréquence
            ];
            data.dateDebut = data.dateDebut || null;
            // Gère la fin de récurrence (date spécifique ou nombre d'occurrences)
            data.dateFin = data.finType === 'date' ? (data.dateFin || null) : null;
            data.nbOccurences = data.finType === 'occurences' ? (parseInt(data.nbOccurences, 10) || null) : null;
        } else {
            // Pour les événements non récurrents, on garde seulement debut et fin
            data.debut = data.debut || null;
            data.fin = data.fin || null;
        }
    
        // Supprime les champs inutiles ou temporaires
        ['userToken', 'preselected_session', 'frequenceUnit', 'additional_sessions[]', 'finType'].forEach(key => delete data[key]);
    
        // Ajuste les champs en fonction de la modalité
        if (data.id_modalites === 2) delete data.id_salles; // Supprime id_salles pour le distanciel
        if (data.id_modalites === 1 || data.id_modalites === 3) delete data.url; // Supprime url pour le présentiel ou hybride
    
        console.log('Données préparées:', data);
        return data;
    }

    function sendFormData(data) {
        fetch('../../controller/Cours/postCoursController.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),  // Utilisation de JSON.stringify ici
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(result => {
            if (result.erreur) {
                showToast(false, result.erreur, "Erreur lors de l'ajout du cours");
            } else if (result.success) {
                showToast(true, result.success, "Ajout du cours réussi");
                setTimeout(() => window.location.reload(), 3000);
            }
        })
        .catch(error => {
            console.error("Erreur : ", error);
            showToast(false, "Une erreur est survenue lors de la communication avec le serveur", "Erreur");
        });
    }

    function showToast(isSuccess, message, title) {
        Toastify({
            text: message,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: isSuccess ? "#4CAF50" : "#F44336",
            stopOnFocus: true,
        }).showToast();
    }

    function updateSessionsDisplay() {
        const preselectedSession = document.querySelector('input[name="preselected_session"]').value;
        const sessionsLabel = document.getElementById('sessions-label');
        const sessionsHelp = document.getElementById('sessions-help');
        const sessionCheckboxes = document.querySelectorAll('input[name="additional_sessions[]"]');
        
        if (preselectedSession) {
            sessionsLabel.textContent = 'Sessions supplémentaires à ajouter';
            sessionsHelp.textContent = 'Cochez les sessions supplémentaires auxquelles ce cours s\'applique.';
            sessionCheckboxes.forEach(checkbox => {
                if (checkbox.value === preselectedSession) {
                    checkbox.checked = true;
                    checkbox.disabled = true;
                } else {
                    checkbox.disabled = false;
                    checkbox.checked = false;
                }
            });
        } else {
            sessionsLabel.textContent = 'Sessions à ajouter';
            sessionsHelp.textContent = 'Cochez les sessions auxquelles ce cours s\'applique.';
            sessionCheckboxes.forEach(checkbox => {
                checkbox.disabled = false;
                checkbox.checked = false;
            });
        }
    }

    function getSelectedSessions() {
        const preselectedSession = document.querySelector('input[name="preselected_session"]').value;
        const additionalSessions = Array.from(document.querySelectorAll('input[name="additional_sessions[]"]:checked')).map(cb => cb.value);
        
        const allSessions = new Set(additionalSessions);
        if (preselectedSession) {
            allSessions.add(preselectedSession);
        }
        
        return Array.from(allSessions).map(Number);
    }

    function getSelectedDays() {
        return Array.from(document.querySelectorAll('.jour-button.active'))
            .map(button => parseInt(button.getAttribute('data-day')));
    }

    function sendSelectedSessions() {
        const selectedSessions = getSelectedSessions();
        
        if (selectedSessions.length > 0) {
            fetchData('../../controller/Matieres/getMatieresController.php', selectedSessions, updateMatiereSelect, 'matiere');
            fetchData('../../controller/Formateur/getFormateursSessionsController.php', selectedSessions, updateFormateurSelect, 'formateur');
            fetchData('../../controller/Salle/getSallesSessionsController.php', selectedSessions, updateSalleSelect, 'salle');
        } else {
            updateMatiereSelect([]);
            updateFormateurSelect([]);
            updateSalleSelect([]);
        }
    }

    function fetchData(url, selectedSessions, updateFunction, type) {
        const loadingElement = document.getElementById(`${type}-loading`);
        const emptyElement = document.getElementById(`${type}-empty`);
        const selectElement = document.getElementById(`id_${type}s`);
    
        if (loadingElement) loadingElement.style.display = 'block';
        if (emptyElement) emptyElement.style.display = 'none';
        if (selectElement) selectElement.disabled = true;
    
        $.ajax({
            url: url,
            method: 'POST',
            data: { event_sessions: selectedSessions },
            dataType: 'json',
            success: function(response) {
                if (response.success && response[`${type}s`]) {
                    updateFunction(response[`${type}s`]);
                } else {
                    console.error(`Erreur ou aucun ${type} trouvé`);
                    updateFunction([]);
                }
            },
            error: function(xhr, status, error) {
                console.error(`Erreur AJAX (${type}):`, status, error);
                updateFunction([]);
            },
            complete: function() {
                if (loadingElement) loadingElement.style.display = 'none';
            }
        });
    }

    function updateMatiereSelect(matieres) {
        updateSelect('id_matieres', matieres, 'id', 'matiere_nom');
    }

    function updateFormateurSelect(formateurs) {
        updateSelect('id_formateurs', formateurs, 'id', item => `${item.lastname} ${item.firstname}`);
    }

    function updateSalleSelect(salles) {
        updateSelect('id_salles', salles, 'id', 'nom');
    }

    function updateSelect(selectId, items, valueKey, textKey) {
        const select = document.getElementById(selectId);
        const emptyMessage = document.getElementById(`${selectId.split('_')[1]}-empty`);
        
        if (!select) {
            console.error(`L'élément select ${selectId} n'a pas été trouvé`);
            return;
        }
        
        const defaultOption = select.querySelector('option[value=""]');
        select.innerHTML = '';
        if (defaultOption) select.appendChild(defaultOption);
        
        if (items.length === 0) {
            select.disabled = true;
            if (emptyMessage) emptyMessage.style.display = 'block';
        } else {
            items.forEach(item => {
                const option = document.createElement('option');
                option.value = item[valueKey];
                option.textContent = typeof textKey === 'function' ? textKey(item) : item[textKey];
                select.appendChild(option);
            });
            select.disabled = false;
            if (emptyMessage) emptyMessage.style.display = 'none';
        }
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
});