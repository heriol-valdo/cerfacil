<?php
include __DIR__ . '/../elements/header.php';
require_once __DIR__ . '/../../Controller/HomeController.php';

$userProfil = HomeController::getprofil();

?>




<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <link rel="stylesheet" href="../../erp/assets/style/profilstyle.css">
    <link rel="stylesheet" href="../../erp/assets/style/contentContainer.css">
    <meta charset="UTF-8">
    <title> Mon Profil | ErpFacil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>


<body>
    <div class="content-container">
        <header>
            <div class="header-container">
                <i class="fa-regular fa-user header-icon"></i>
                <div class="title">
                    <h1>Mon Profil <?= $userProfil->data->firstname ?></h1>
                </div>
            </div>
        </header>
        <main>
            <!-- Admin content -->
            <?php if ($_SESSION['user']['role'] == 1): ?>
                <div class="container">
                    <div class="input-role">
                        <span>Role</span>
                        <input type="text" value="<?= $userProfil->data->role ?>" disabled>
                    </div>

                    <div class="content">

                        <form action="">

                            <div class="user-details">
                                <div class="input-box">
                                    <span class="details">Nom</span>
                                    <input type="text" value="<?= $userProfil->data->lastname ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Prénom</span>
                                    <input type="text" value="<?= $userProfil->data->firstname ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Email</span>
                                    <input type="text" value="<?= $userProfil->data->email ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Téléphone</span>
                                    <input type="text" value="<?= $userProfil->data->telephone ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Lieu de travail</span>
                                    <input type="text" value="<?= $userProfil->data->lieu_travail ?>" disabled>
                                </div>


                            </div>
                            <div class="button-centre">
                                <a class="custom-button" href="editPassword"> Modifier mon mot de passe</a>
                                <a class="custom-button" href="updateProfil">Modifier mon compte</a>
                            </div>
                        </form>

                    </div>
                </div>

                <!--------------------------------------------------------- 
    Gestionnaire Entreprise Content -->
            <?php elseif ($_SESSION['user']['role'] == 2): ?>
                <div class="container">

                    <div class="input-role">
                        <span>Role</span>
                        <input type="text" value="<?= $userProfil->data->role ?>" disabled>
                    </div>

                    <div class="content">

                        <form action="">

                            <div class="user-details">
                                <div class="input-box">
                                    <span class="details">Nom</span>
                                    <input type="text" value="<?= $userProfil->data->lastname ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Prénom</span>
                                    <input type="text" value="<?= $userProfil->data->firstname ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Email</span>
                                    <input type="text" value="<?= $userProfil->data->email ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Téléphone</span>
                                    <input type="text" value="<?= $userProfil->data->telephone ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Lieu de Travail</span>
                                    <input type="text" value="<?= $userProfil->data->lieu_travail ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Entreprise</span>
                                    <input type="text" value="<?= $userProfil->data->nomEntreprise ?>" disabled>
                                </div>
                            </div>

                            <div class="button-centre">
                                <a class="custom-button" href="edit-password.php"> Modifier mon mot de passe</a>
                                <a class="custom-button" href="updateProfil.php">Modifier mon compte</a>
                            </div>

                        </form>

                    </div>
                </div>

                <!------------------------------------------------ 
    Gestionnaire Centre Content -->
            <?php elseif ($_SESSION['user']['role'] == 3): ?>

                <div class="container">

                    <div class="input-role">
                        <span>Role</span>
                        <input type="text" value="<?= $userProfil->data->role ?>" disabled>
                    </div>

                    <div class="content">

                        <form action="">

                            <div class="user-details">
                                <div class="input-box">
                                    <span class="details">Nom</span>
                                    <input type="text" value="<?= $userProfil->data->lastname ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Prénom</span>
                                    <input type="text" value="<?= $userProfil->data->firstname ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Email</span>
                                    <input type="text" value="<?= $userProfil->data->email ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Téléphone</span>
                                    <input type="text" value="<?= $userProfil->data->telephone ?>" disabled>
                                </div>

                            </div>
                            <div class="input-centre">
                                <span class="details">Centre de formation</span>
                                <input type="text" value="<?= $userProfil->data->nomCentre ?>" disabled>
                            </div>
                            <div class="button-centre">
                                <a class="custom-button" href="edit-password.php"> Modifier mon mot de passe</a>
                                <a class="custom-button" href="updateProfil.php">Modifier mon compte</a>
                            </div>
                        </form>

                    </div>
                </div>
                <!--------------------------------------------------------------- 
    Formateur Content -->
            <?php elseif ($_SESSION['user']['role'] == 4): ?>
                <div class="container">

                    <div class="input-role">
                        <span>Role</span>
                        <input type="text" value="<?= $userProfil->data->role ?>" disabled>
                    </div>

                    <div class="content">

                        <form action="">

                            <div class="user-details">
                                <div class="input-box">
                                    <span class="details">Nom</span>
                                    <input type="text" value="<?= $userProfil->data->lastname ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Prénom</span>
                                    <input type="text" value="<?= $userProfil->data->firstname ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Email</span>
                                    <input type="text" value="<?= $userProfil->data->email ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Téléphone</span>
                                    <input type="text" value="<?= $userProfil->data->telephone ?>" disabled>
                                </div>
                                <!-- Si le Siret n'est pas vide, affiche la ligne -->
                                <?php if (!empty($userProfil->data->siret)): ?>
                                    <div class="input-box">
                                        <span class="details">Siret</span>
                                        <input type="text" value="<?= $userProfil->data->siret ?>" disabled>
                                    </div>
                                <?php endif; ?>
                                <div class="input-box">
                                    <span class="details">Adresse postale</span>
                                    <input type="text" value="<?= $userProfil->data->adressePostale ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Code postal</span>
                                    <input type="text" value="<?= $userProfil->data->codePostal ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Ville</span>
                                    <input type="text" value="<?= $userProfil->data->ville ?>" disabled>
                                </div>
                            </div>
                            <!-- Récupérer Nom centre de formation -->
                            <div class="input-centre">
                                <span class="details">Centre de formation</span>
                                <input type="text" value="<?= $userProfil->data->nomCentre ?>" disabled>
                            </div>
                            <div class="button-centre">
                                <a class="custom-button" href="edit-password.php"> Modifier mon mot de passe</a>
                                <a class="custom-button" href="updateProfil.php">Modifier mon compte</a>
                            </div>
                        </form>

                    </div>
                </div>


                <!----------------------------------------------------------- 
    Etudiant Content -->
            <?php elseif ($_SESSION['user']['role'] == 5): ?>
                <div class="container">

                    <div class="input-role">
                        <span>Role</span>
                        <input type="text" value="<?= $userProfil->data->role ?>" disabled>
                    </div>

                    <div class="content">

                        <form action="">

                            <div class="user-details">
                                <div class="input-box">
                                    <span class="details">Nom</span>
                                    <input type="text" value="<?= $userProfil->data->lastname ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Prénom</span>
                                    <input type="text" value="<?= $userProfil->data->firstname ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">date de naissance</span>
                                    <input type="text" value="<?= $userProfil->data->date_naissance ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Email</span>
                                    <input type="text" value="<?= $userProfil->data->email ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Adresse postale</span>
                                    <input type="text" value="<?= $userProfil->data->adressePostale ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Code postale</span>
                                    <input type="text" value="<?= $userProfil->data->codePostal ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Ville</span>
                                    <input type="text" value="<?= $userProfil->data->ville ?>" disabled>
                                </div>
                                <!-- Si l'entreprise n'est pas vide, affiche la ligne -->
                                <!-- Récupérer nom entreprise -->
                                <?php if (!empty($userProfil->data->nomEntreprise)): ?>
                                    <div class="input-box">
                                        <span class="details">Entreprise </span>
                                        <input type="text" value="<?= $userProfil->data->nomEntreprise ?>" disabled>
                                    </div>
                                <?php endif; ?>
                                <!-- Récupérer Nom centre de formation -->
                                <div class="input-box">
                                    <span class="details">Centre de formation</span>
                                    <input type="text" value="<?= $userProfil->data->nomCentre ?>" disabled>
                                </div>
                            </div>
                            <div class="button-centre">
                                <a class="custom-button" href="edit-password.php"> Modifier mon mot de passe</a>
                                <a class="custom-button" href="updateProfil.php">Modifier mon compte</a>
                            </div>
                        </form>

                    </div>
                </div>

                <!-----------------------------------------------------------
    Financeur Content -->
            <?php elseif ($_SESSION['user']['role'] == 6): ?>

                <div class="container">

                    <div class="input-role">
                        <span>Role</span>
                        <input type="text" value="<?= $userProfil->data->role ?>" disabled>
                    </div>

                    <div class="content">

                        <form action="">

                            <div class="user-details">
                                <div class="input-box">
                                    <span class="details">Nom</span>
                                    <input type="text" value="<?= $userProfil->data->lastname ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Prénom</span>
                                    <input type="text" value="<?= $userProfil->data->firstname ?>" disabled>
                                </div>
                                <div class="input-box">
                                    <span class="details">Email</span>
                                    <input type="text" value="<?= $userProfil->data->email ?>" disabled>
                                </div>
                                <?php if (!empty($userProfil->data->telephone)): ?>
                                    <div class="input-box">
                                        <span class="details">Téléphone</span>
                                        <input type="text" value="<?= $userProfil->data->telephone ?>" disabled>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($userProfil->data->telephone)): ?>
                                <div class="input-centre">
                                    <span class="details">Lieu de travail</span>
                                    <input type="text" value="<?= $userProfil->data->lieu_travail ?>" disabled>
                                </div>
                            <?php endif; ?>
                            <div class="button-centre">
                                <a class="custom-button" href="edit-password.php"> Modifier mon mot de passe</a>
                                <?php if ($_SESSION['user']['role'] != 5): ?>
                                    <a class="custom-button" href="updateProfil.php">Modifier mon compte</a>
                                <?php endif; ?>
                            </div>
                        </form>

                    </div>
                </div>



            <?php endif; ?>


        </main>
        <footer>
            <?php
            include __DIR__ . '/../elements/footer.php';
            ?>
        </footer>

    </div>
</body>