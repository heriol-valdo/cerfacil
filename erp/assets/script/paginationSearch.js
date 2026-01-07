/* CSS de la pagination à importer

    <link rel="stylesheet" href="../../assets/style/pagination.css" />

*/

/* Barre de recherche à importer (juste au début de <main> en général)

    <div class="d-flex justify-content-between mt-3 mb-3">
        <input type="text" id="searchInput" class="form-control w-25" placeholder="Rechercher...">
    <div>

*/

/* Partie à importer sous le contenu à paginer

    <!-- Pagination Nav -->
    <div class="pagination-container">
        <nav aria-label="Pagination" id="paginationNav" style="display: none;">
        <ul class="pagination justify-content-center" id="paginationList">
            <li class="page-item"><a class="page-link" href="#" id="prevPage">Précédent</a></li>
            <!-- Page numbers will be inserted here dynamically -->
            <li class="page-item"><a class="page-link" href="#" id="nextPage">Suivant</a></li>
        </ul>
        </nav>
    </div>

*/


/* Partie script à importer dans la vue

    <!-- Pagination + Barre de recherche -->
    <script> // Variables pour paginationSearch.js
        const targetTable = document.getElementById('centreTable');
    </script>
    <script src="../../assets/script/paginationSearch.js"></script>

*/


// Pagination and search scripts

const searchInput = document.getElementById('searchInput');
const noResultsMessage = document.getElementById('noResultsMessage');
const tableHead = document.getElementById('tableHead');
const paginationList = document.getElementById('paginationList');
const rowsPerPage = 10;  // Nombre d'éléments par page
const maxPagesVisible = 9; // Limite de pages visibles
let currentPage = 1;

function updatePagination(totalPages) {
    paginationList.innerHTML = '';
    const prevPageItem = document.createElement('li');
    prevPageItem.className = 'page-item';
    const prevPageLink = document.createElement('a');
    prevPageLink.className = 'page-link';
    prevPageLink.href = '#';
    prevPageLink.id = 'prevPage';
    prevPageLink.textContent = 'Précédent';
    prevPageItem.appendChild(prevPageLink);
    paginationList.appendChild(prevPageItem);

    const startPage = Math.max(1, currentPage - Math.floor(maxPagesVisible / 2));
    const endPage = Math.min(totalPages, startPage + maxPagesVisible - 1);

    for (let i = startPage; i <= endPage; i++) {
        const pageItem = document.createElement('li');
        pageItem.className = 'page-item' + (i === currentPage ? ' active' : '');
        const pageLink = document.createElement('a');
        pageLink.className = 'page-link';
        pageLink.href = '#';
        pageLink.textContent = i;
        pageLink.addEventListener('click', function (e) {
            e.preventDefault();
            currentPage = i;
            displayPage(currentPage);
        });
        pageItem.appendChild(pageLink);
        paginationList.appendChild(pageItem);
    }

    const nextPageItem = document.createElement('li');
    nextPageItem.className = 'page-item';
    const nextPageLink = document.createElement('a');
    nextPageLink.className = 'page-link';
    nextPageLink.href = '#';
    nextPageLink.id = 'nextPage';
    nextPageLink.textContent = 'Suivant';
    nextPageItem.appendChild(nextPageLink);
    paginationList.appendChild(nextPageItem);

    prevPageItem.classList.toggle('disabled', currentPage === 1);
    nextPageItem.classList.toggle('disabled', currentPage === totalPages);

    // Event handlers for the Prev and Next buttons
    prevPageLink.addEventListener('click', function (e) {
        e.preventDefault();
        if (currentPage > 1) {
            currentPage--;
            displayPage(currentPage);
        }
    });

    nextPageLink.addEventListener('click', function (e) {
        e.preventDefault();
        if (currentPage < totalPages) {
            currentPage++;
            displayPage(currentPage);
        }
    });
}

function displayPage(page) {
    const rows = targetTable.querySelectorAll('tbody tr');
    const totalRows = rows.length;
    const totalPages = Math.ceil(totalRows / rowsPerPage);
    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;

    rows.forEach((row, index) => {
        row.style.display = (index >= start && index < end) ? '' : 'none';
    });

    document.getElementById('paginationNav').style.display = totalRows > rowsPerPage ? 'block' : 'none';
    currentPage = page;
    updatePagination(totalPages);
}

function searchAndPaginate() {
    const filter = searchInput.value.toLowerCase();
    const rows = targetTable.querySelectorAll('tbody tr');
    let visibleRows = [];

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        let found = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(filter));

        row.style.display = 'none';
        if (found) visibleRows.push(row);
    });

    visibleRows.forEach(row => row.style.display = 'none');
    for (let i = 0; i < visibleRows.length; i++) {
        visibleRows[i].style.display = (i < rowsPerPage) ? '' : 'none';
    }

    document.getElementById('paginationNav').style.display = visibleRows.length > rowsPerPage ? 'block' : 'none';
    document.getElementById('noResultsMessage').style.display = visibleRows.length ? 'none' : 'block';
    tableHead.style.display = visibleRows.length ? 'table-header-group' : 'none';

    currentPage = 1;
    updatePagination(Math.ceil(visibleRows.length / rowsPerPage));
}

// Initial call to display the first page
displayPage(currentPage);

searchInput.addEventListener('input', searchAndPaginate);

// Set table height
document.addEventListener("DOMContentLoaded", function () {
    const rowsPerPage = 10; // Nombre d'éléments par page
    const tableContainer = document.querySelector('.table-container');
    const rowHeight = 50; // Approximate row height, adjust based on your styling
    const tableHeadHeight = document.querySelector('thead').offsetHeight;
    const minHeight = (rowsPerPage * rowHeight) + tableHeadHeight;

    function setTableHeight() {
        const totalRows = targetTable.querySelectorAll('tbody tr').length;
        const visibleRows = Math.min(rowsPerPage, totalRows);
        const newHeight = (visibleRows * rowHeight) + tableHeadHeight;
        tableContainer.style.height = `${Math.max(newHeight, minHeight)}px`;
    }

    setTableHeight();
    searchInput.addEventListener('input', setTableHeight);
    document.getElementById('paginationList').addEventListener('click', function (e) {
        if (e.target.tagName === 'A') {
            setTimeout(setTableHeight, 100); // Adjust this timeout as needed
        }
    });
});
