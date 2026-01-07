<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../elements/header.php';
require_once __DIR__ . '/../../requestFile/authRequet.php';
require_once __DIR__ . '/../../controller/User/profilController.php';
if ($_SESSION['user']['role'] == 1) {
    require_once __DIR__ . '/../../controller/Admin/homeContentController.php';
} else {
    require_once __DIR__ . '/../../controller/Assistance/assistanceController.php';
}
require_once __DIR__ . '/../../controller/User/validTokenController.php';
?>

<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>Demande d'assistance | ErpFacil</title>
    <link rel="stylesheet" href="../../erp/assets/style/assistanceStyle.css" />
    <link rel="stylesheet" href="../../erp/assets/style/contentContainer.css" />
    <link rel="stylesheet" href="../../erp/assets/style/cardStyle.css" />
    <link rel="stylesheet" href="../../erp/assets/style/tableStyle.css" />
    <link rel="stylesheet" href="../../erp/assets/style/profilstyle.css" />
    <link rel="stylesheet" href="../../erp/assets/style/pagination.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../erp/assets/style/modals.css" />
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Google Font Link for Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
</head>

<body>
    <div class="content-container">
        <header>
            <div class="header-container">
                <i class="fa-solid fa-question header-icon"></i>
                <div class="title">
                    <h1>Demande d'assistance</h1>
                </div>
            </div>
            <div class="back-icon-container">
                <a href="home"><i class="fa-solid fa-arrow-left-long fa-2xl"
                        style="color: #263b4a;"></i></a>
            </div>
        </header>
        <main>
            <?php
            if (isset($_SESSION['error-msg'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error-msg'];
                    unset($_SESSION['error-msg']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success-msg'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success-msg'];
                    unset($_SESSION['success-msg']); ?>
                </div>
            <?php endif; ?>
            <div class="container">
                <div>
                    <table class="table table-striped mt-3" id="tickets-table">
                        <h3>Historique des ticketss</h3>
                        <div class="d-flex justify-content-between mt-3 mb-3">
                            <input type="text" id="searchInput" class="form-control w-25" placeholder="Rechercher...">
                        </div>
                        <thead id="tableHead">
                            <tr>
                                <th scope="col">Statuts</th>
                                <th scope="col">Date</th>
                                <th scope="col">Objet</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>

                        <?php if ($ticketList->data != null): ?>

                            <?php 
                                    $tickets = $ticketList->data;
                                     
                                    usort( $tickets, function($a, $b) {
                                        // Gestion des dates vides
                                        if (empty($a->dateCreation)) return 1;
                                        if (empty($b->dateCreation)) return -1;

                                        return strtotime($b->dateCreation) - strtotime($a->dateCreation);
                                    });
                                   
                                
                                foreach ($tickets as $ticket) { ?>
                                <?php $date = new DateTime($ticket->dateCreation);
                                // Format the date in DD-MM-YYYY HH:MM format
                                $formattedDate = $date->format('d-m-Y H:i'); 
                                
                                
                                ?>

                                <tr class="table-row">
                                    <td><?= $ticket->etat ?></td>
                                    <td><?= $formattedDate ?></td>
                                    <td><?= $ticket->objet ?></td>
                                    <td>
                                        <form action="voir-demande.php" method="POST">
                                            <input type="hidden" name="ticketId" value="<?= $ticket->id ?>" />
                                            <div>
                                                <button type="submit" name="voirTicket" class="submit-button">
                                                    <i class="fa-solid fa-arrow-right fa-xl" style="color: #263b4a;"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php endif; ?>


                    </table>
                    <?php if ($ticketList->data == null): ?>
                        <p>Aucun ticket n'a été créé</p>
                    <?php endif; ?>

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
                </div>
                <?php if ($_SESSION['user']['role'] != 1): ?>
                    <div class="button-centre">
                        <a class="custom-button " href="demande-assistance.php">Faire une demande</a>
                        <!--a class="custom-button " href="../../view/user/home.php">Retour</a-->
                        <span></span>
                    </div>
                <?php endif; ?>
            </div>
            <!-- Pagination + Barre de recherche -->
            <script> // Variables pour paginationSearch.js
                const targetTable = document.getElementById('tickets-table');
            </script>
            <script src="../../erp/assets/script/paginationSearch.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
        </main>
        <footer>
            <?php
            include __DIR__ . '/../elements/footer.php';
            ?>
        </footer>
    </div>

</body>

</html>