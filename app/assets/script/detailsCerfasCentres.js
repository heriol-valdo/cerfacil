document.addEventListener('DOMContentLoaded', function() {
    const roomButtonsContainer = document.querySelector('.room-buttons');
    const equipmentList = document.getElementById('equipment-list');
    const searchInput = document.querySelector('.search-input');
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');

    const roomsPerPage = 5;
    let currentPage = 1;
    let filteredRooms = rooms;

    function updateEquipmentList(roomId) {
        const equipment = roomEquipment[roomId];  
        equipmentList.innerHTML = ''; // Réinitialiser la liste
    
        if (equipment && equipment.length > 0) {
            equipment.forEach(item => {
                const div = document.createElement('div');
                div.className = 'equipment-item';
                div.innerHTML = `
                <div class="equipment-name">
                <h3 class="underline">Informations Etudiants</h3>
                <span >Nom  : ${item.name}</span></br>
                <span >Email  : ${item.email}</span></br>
                <span>Telephone  : ${item.telephone}</span></br>
                <div style="margin-bottom: 30px;"></div>

                <h3 class="underline">Informations Entreprise</h3>
                <span>Nom : ${item.nameEntreprise}</span></br>
                <span>Email : ${item.emailEntreprise}</span></br>
                <span>Telephone : ${item.telephoneEntreprise}</span></br>



                </div>
                
                
                `;
                equipmentList.appendChild(div);
            });
        } else {
            const message = document.createElement('div');
            message.textContent = 'Aucun détail disponible pour ce cerfa.';
            equipmentList.appendChild(message);
        }
    }
    
    function renderRoomButtons() {
        const startIndex = (currentPage - 1) * roomsPerPage;
        const endIndex = startIndex + roomsPerPage;
        const roomsToShow = filteredRooms.slice(startIndex, endIndex); // Utilisation de 'rooms' défini en PHP
    
        roomButtonsContainer.innerHTML = ''; // Réinitialise le conteneur de boutons de salle
    
        roomsToShow.forEach((room, index) => {
            const button = document.createElement('button');
            button.className = 'room-button';
            button.textContent = room.name;
            button.dataset.roomId = room.id; // Utilisation de l'ID de salle comme identifiant de dataset
    
            // Ajouter un gestionnaire d'événement pour chaque bouton
            button.addEventListener('click', function() {
                document.querySelectorAll('.room-button').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                updateEquipmentList(this.dataset.roomId); // Passer l'ID de salle
            });
    
            // Ajouter le bouton au conteneur
            roomButtonsContainer.appendChild(button);
    
            // Sélectionner le premier bouton par défaut
            if (index === 0) {
                button.classList.add('active'); // Marque le premier bouton comme actif
                updateEquipmentList(room.id); // Charge la liste d'équipements pour le premier élément
            }
        });
    
        // Si aucune salle n'est disponible, affiche un message par défaut
        if (rooms.length === 0) {
            const message = document.createElement('div');
            message.textContent = 'Aucun cerfa disponible.';
            roomButtonsContainer.appendChild(message);
        }
    
        // Gérer l'état des boutons de pagination
        prevPageBtn.disabled = currentPage === 1;
        nextPageBtn.disabled = endIndex >= rooms.length;
    }
    

    function filterRooms() {
        const searchTerm = searchInput.value.toLowerCase();
        filteredRooms = rooms.filter(room => room.name.toLowerCase().includes(searchTerm));
        currentPage = 1;
        renderRoomButtons();
    }

    searchInput.addEventListener('input', filterRooms);

    prevPageBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            renderRoomButtons();
        }
    });

    nextPageBtn.addEventListener('click', () => {
        if (currentPage * roomsPerPage < filteredRooms.length) {
            currentPage++;
            renderRoomButtons();
        }
    });

    // Initial render
    renderRoomButtons();
    if (filteredRooms.length > 0) {
        updateEquipmentList(filteredRooms[0].id);
        document.querySelector('.room-button').classList.add('active');
    }
});