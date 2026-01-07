document.addEventListener('DOMContentLoaded', function() {
    const roomButtonsContainer = document.querySelector('.room-buttons');
    const equipmentList = document.getElementById('equipment-list');
    const searchInput = document.querySelector('.search-input');
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');

    const roomsPerPage = 5;
    let currentPage = 1;
    let filteredRooms = rooms;

    function updateEquipmentList(roomName) {
        const equipment = roomEquipment[roomName];
        equipmentList.innerHTML = '';
        if (equipment && equipment.length > 0) {
            equipment.forEach(item => {
                const div = document.createElement('div');
                div.className = 'equipment-item';
                div.innerHTML = `
                    <span class="equipment-name">${item.name}</span>
                    <span class="equipment-quantity">${item.quantity}</span>
                `;
                equipmentList.appendChild(div);
            });
        } else {
            const message = document.createElement('div');
            message.textContent = 'Aucun équipement disponible pour cette salle.';
            equipmentList.appendChild(message);
        }
    }

    function renderRoomButtons() {
        const startIndex = (currentPage - 1) * roomsPerPage;
        const endIndex = startIndex + roomsPerPage;
        const roomsToShow = filteredRooms.slice(startIndex, endIndex);

        roomButtonsContainer.innerHTML = '';
        roomsToShow.forEach(room => {
            const button = document.createElement('button');
            button.className = 'room-button';
            button.textContent = room.name;
            button.dataset.roomName = room.name;
            button.addEventListener('click', function() {
                document.querySelectorAll('.room-button').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                updateEquipmentList(this.dataset.roomName);
            });
            roomButtonsContainer.appendChild(button);
        });

        prevPageBtn.disabled = currentPage === 1;
        nextPageBtn.disabled = endIndex >= filteredRooms.length;
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
        updateEquipmentList(filteredRooms[0].name);
        document.querySelector('.room-button').classList.add('active');
    }
});