/* 

    <script>
        const noSubItemMessage = "Aucun équipement disponible pour cette salle.";
        var mainSubs = <?= json_encode($mainSubs) ?>;
        var mains = <?= json_encode($mains) ?>;
        function generateSubListContent(item) {
            return `
                <span class="sub-quantity">${item.role}</span>
                <span class="sub-name">${item.lastname} ${item.firstname}</span>
                <span class="sub-email">${item.email}</span>
            `;
        }
    </script>
    <script src="../../assets/script/twoContainerList.js"></script>
*/


document.addEventListener('DOMContentLoaded', function () {
    const mainButtonsContainer = document.querySelector('.main-buttons');
    const subList = document.getElementById('sub-list');
    const searchInput = document.querySelector('.search-input');
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');

    const mainsPerPage = 5;
    const subsPerPage = 5;

    let currentPage = 1;
    let filteredMains = mains;

    let sub = '';
    let currentSub = [];

    let filteredSubs = sub;


    function updateSubList(mainName) {
        currentSub = mainSubs[mainName];
        filterSubs();
    }

    function renderMainButtons() {
        mainButtonsContainer.innerHTML = '';
        filteredMains.forEach(main => {
            const button = document.createElement('button');
            button.className = 'main-button';
            button.textContent = main.name;
            button.dataset.mainName = main.name;
            button.addEventListener('click', function () {
                document.querySelectorAll('.main-button').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                updateSubList(this.dataset.mainName);
            });
            mainButtonsContainer.appendChild(button);
        });
    }

    function filterMains() {
        const searchTerm = searchInput.value.toLowerCase();
        filteredMains = mains.filter(main => main.name.toLowerCase().includes(searchTerm));
        currentPage = 1;
        renderMainButtons();
    }

    function filterSubs() {
        const searchTerm = searchInput.value.toLowerCase();

        function searchInSub(sub, searchTerm) {
            const term = searchTerm.toLowerCase();
            return (
                sub.role.toLowerCase().includes(term) ||
                sub.lastname.toLowerCase().includes(term) ||
                sub.firstname.toLowerCase().includes(term) ||
                sub.email.toLowerCase().includes(term)
            );
        }
        filteredSubs = currentSub.filter(sub => searchInSub(sub, searchTerm));
        currentPage = 1;

        renderSubList();
    }

    function renderSubList() {
        subList.innerHTML = '';
        const startIndex = (currentPage - 1) * subsPerPage;
        const endIndex = startIndex + subsPerPage;
        const subsToShow = filteredSubs.slice(startIndex, endIndex);

        if (subsToShow.length > 0) {
            subsToShow.forEach(item => {
                const div = document.createElement('div');
                div.className = 'sub-item';
                div.innerHTML = generateSubListContent(item);
                subList.appendChild(div);
            });
        } else {
            const message = document.createElement('div');
            message.textContent = noSubItemMessage;
            subList.appendChild(message);
        }

        prevPageBtn.disabled = currentPage === 1;
        nextPageBtn.disabled = endIndex >= filteredSubs.length;
    }

    searchInput.addEventListener('input', filterSubs);

    prevPageBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            renderSubList();
        }
    });

    nextPageBtn.addEventListener('click', () => {
        if (currentPage * subsPerPage < filteredSubs.length) {
            currentPage++;
            renderSubList();
        }
    });

    // Initial render
    renderMainButtons();
    if (filteredMains.length > 0) {
        document.querySelector('.main-button').classList.add('active');
        currentSub = mainSubs[filteredMains[0].name];
        filterSubs();
    }
});