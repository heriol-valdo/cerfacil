<?php

include __DIR__ . '/../elements/header.php';

require_once __DIR__ . '/../../Controller/EntrepriseController.php';
require_once __DIR__ . '/../../Controller/CentreFormationController.php';

$allcentres = CentreFormationController::ListCentreFormation();

$allentreprises = EntrepriseController::ListEntreprise();


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>Liste des Entreprises | ErpFacil</title>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="../../erp/assets/style/listeEtudiants.css" />
    <link rel="stylesheet" href="../../erp/assets/style/contentContainer.css" />
    <link rel="stylesheet" href="../../erp/assets/style/profilstyle.css" />
    <link rel="stylesheet" href="../../erp/assets/style/pagination.css" />
    <link rel="stylesheet" href="../../erp/assets/style/modals.css" />

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
</head>

<body>
    <div class="content-container">
        <header>
            <div class="header-container">
                <i class="fa-solid fa-users header-icon"></i>
                <div class="title">
                    <h1>Liste des entreprises</h1>
                </div>
            </div>
        </header>
        <main>
            <div class="d-flex justify-content-between mt-3 mb-3">
                <input type="text" id="searchInput" class="form-control w-25" placeholder="Rechercher...">
                <div>
                    <button class="btn btn-secondary" data-bs-toggle="modal"
                        data-bs-target="#addEntrepriseModal">Ajouter une
                        Entreprise</button>
                </div>
            </div>
            <?php if (!empty($allentreprises)): ?>

                <table class="table table-striped mt-3" id="entrepriseTable">
                    <thead>
                        <tr>
                            <th>Nom Entreprise</th>
                            <th>Nom directeur</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Ville</th>
                            <th colspan="3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allentreprises as $allentreprise): ?>
                            <tr>
                                <td><?php echo $allentreprise->nomEntreprise !== null ? htmlspecialchars($allentreprise->nomEntreprise) : ''; ?>
                                </td>
                                <td><?php echo $allentreprise->nomDirecteur !== null ? htmlspecialchars($allentreprise->nomDirecteur) : ''; ?>
                                </td>
                                <td><?php echo $allentreprise->telephone !== null ? htmlspecialchars($allentreprise->telephone) : ''; ?>
                                </td>
                                <td><?php echo $allentreprise->email !== null ? htmlspecialchars($allentreprise->email) : ''; ?>
                                </td>
                                <td><?php echo $allentreprise->ville !== null ? htmlspecialchars($allentreprise->ville) : ''; ?>
                                </td>
                                <td>
                                <button 
                                    class="btn btn-warning btn-sm" 
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal"
                                    data-field-id="<?= $allentreprise->id ?>"
                                    data-field-siret="<?= $allentreprise->siret ?>"
                                    data-field-nomEntreprise="<?= $allentreprise->nomEntreprise ?>"
                                    data-field-nomDirecteur="<?= $allentreprise->nomDirecteur ?>"
                                    data-field-adressePostale="<?= $allentreprise->adressePostale ?>"
                                    data-field-codePostal="<?= $allentreprise->codePostal ?>"
                                    data-field-ville="<?=$allentreprise->ville ?>"
                                    data-field-telephone="<?= $allentreprise->telephone ?>"
                                    data-field-ape="<?= $allentreprise->ape ?>"
                                    data-field-intracommunautaire="<?= $allentreprise->intracommunautaire ?>"
                                    data-field-isActif="<?= $allentreprise->isActif ?>"
                                    data-field-soumis_tva="<?= $allentreprise->soumis_tva ?>"
                                    data-field-domaineActivite="<?=$allentreprise->domaineActivite ?>"
                                    data-field-formeJuridique="<?= $allentreprise->formeJuridique ?>"
                                    data-field-siteWeb="<?= $allentreprise->siteWeb ?>"
                                    data-field-fax="<?= $allentreprise->fax ?>"
                                    data-field-logo="<?=$allentreprise->logo ?>"
                                    data-field-dateCreation="<?= $allentreprise->dateCreation ?>"
                                    data-field-email="<?= $allentreprise->email ?>"
                                    onclick="populateEnterpriseModal(this)"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>

                                <td>
                                    <button class="btn btn-danger btn-sm"
                                        onclick="confirmDeleteElement(<?php echo $allentreprise->id; ?>)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p id="noResultsMessage" style="display:none;">Aucun résultat trouvé</p>
            <?php else: ?>
                <p><?php echo "Aucune entreprise" ?></p>
            <?php endif; ?>

            <?php include "modalAddEntreprise.php"; ?>
            <?php include "modalEditEntreprise.php"; ?>
            <?php include "modalDeleteEntreprise.php"; ?>

           
         

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

            <!-- Script showToast -->
            <script src="../../erp/assets/script/toast.js"></script>

            <!-- Pagination + Barre de recherche -->
            <script> // Variables pour paginationSearch.js
                const targetTable = document.getElementById('entrepriseTable');
            </script>
            <script src="../../erp/assets/script/paginationSearch.js"></script>

            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
        </main>
        <footer>
            <?php include __DIR__ . '/../elements/footer.php'; ?>
        </footer>

    </div>

</body>