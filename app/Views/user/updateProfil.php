<?php 

include __DIR__ . '/../elements/header.php' ;
require_once __DIR__ . '/../../Controller/HomeController.php';

$userProfil = HomeController::getprofil();
$role=$_SESSION['user']['role']; 

?>




    <!DOCTYPE html>
    <html lang="en" dir="ltr">

    <head>
        <meta charset="UTF-8">
        <title> Mon Profil |CerFacil</title>
        <link rel="stylesheet" href="../../app/assets/style/profilstyle.css">
        <link rel="stylesheet" href="../../app/assets/style/contentContainer.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

                <div class="container">
                    <div class="input-role">
                        <span>Modifier mes informations</span>
                    </div>
                    <div class="content">
                        <form id="<?= $role ?>">
                            <div class="user-details">
                                <div class="input-box">
                                    <span for="lastname" class="details">Nom</span>
                                    <p name="lastname" ><?= $userProfil->data->lastname ?></p>
                                </div>
                                <div class="input-box">
                                    <span for="firstname" class="details">Prénom</span>
                                    <p name="firstname"><?= $userProfil->data->firstname ?></p>
                                </div>
                                <div class="input-box">
                                    <span for="email" class="details">Email</span>
                                    <p name="email"><?= $userProfil->data->email ?></p>
                                </div>
                                <?php /* Admin */ if ($role == 1): ?>
                                    <div class="input-box">
                                        <span for="telephone" class="details">Téléphone</span>
                                        <input type="text" name="telephone" value="<?= $userProfil->data->telephone ?>">
                                    </div>
                                    <div class="input-box">
                                        <span for="lieu_travail" class="details">Lieu de travail</span>
                                        <input type="text" name="lieu_travail"
                                            value="<?= $userProfil->data->lieu_travail ?>">
                                    </div>
                                <?php /* GEntreprise */ elseif ($role == 2): ?>
                                    <div class="input-box">
                                        <span for="telephone" class="details">Téléphone</span>
                                        <input type="text" name="telephone" value="<?= $userProfil->data->telephone ?>">
                                    </div>

                                    <div class="input-box">
                                        <span for="lieu_travail" class="details">Lieu de travail</span>
                                        <input type="text" name="lieu_travail"
                                            value="<?= $userProfil->data->lieu_travail ?>">
                                    </div>
                                <?php /* GCentre */ elseif ($role == 3): ?>
                                    <div class="input-box">
                                        <span for="telephone" class="details">Téléphone</span>
                                        <input type="text" name="telephone" value="<?= $userProfil->data->telephone ?>">
                                    </div>
                                <?php /* Formateurs */ elseif ($role == 4): ?>
                                    <div class="input-box">
                                        <span for="telephone" class="details">Téléphone</span>
                                        <input type="text" name="telephone" value="<?= $userProfil->data->telephone ?>">
                                    </div>
                                    <!-- Si le Siret n'est pas vide, affiche la ligne -->
                                    <?php if (!empty($userProfil->data->siret)): ?>
                                    <?php endif; ?>
                                    <div class="input-box">

                                        <span class="details" for="adressePostale">Adresse postale</span>
                                        <input type="text" name="adressePostale"
                                            value="<?= $userProfil->data->adressePostale ?>">
                                    </div>
                                    <div class="input-box">
                                        <span class="details" for="codePostal">Code postal</span>
                                        <input type="text" name="codePostal" value="<?= $userProfil->data->codePostal ?>">
                                    </div>
                                    <div class="input-box">
                                        <span class="details" for="ville">Ville</span>
                                        <input type="text" name="ville" value="<?= $userProfil->data->ville ?>">
                                    </div>
                                <?php elseif /* Conseillers financeurs */ ($role == 6): ?>
                                <?php endif; ?>
                            </div>
                            <div class="button-centre">
                                <button type="submit" class="custom-button">Enregistrer les informations</button>
                                <a class="custom-button" href="profil">Retour</a>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Script showToast -->
                <script src="../../app/assets/script/toast.js"></script>
                <script>
                    const formId = "<?php echo $role; ?>";
                    console.log(formId);
                    const updateForm = document.getElementById(formId);
                    if (updateForm) {
                        const fields = updateForm.querySelectorAll("input[type='text']");
                        updateForm.addEventListener("submit", async function (e) {

                            e.preventDefault();
                            const formData = new FormData(this);
                            const data = {};
                            fields.forEach((field) => {
                                data[field.name] = formData.get(field.name);
                            });

                            try {
                                const response = await fetch("updateProfilSend", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                    },
                                    body: JSON.stringify(data),
                                });

                                const result = await response.json();

                                if (result.erreur) {
                                    showToast(false, result.erreur, "Erreur lors de la mise à jour");
                                } else if (result.valid) {
                                    showToast(true, result.valid, "Mise à jour réussie");

                                    setTimeout(() => {
                                        window.location.href = "profil";
                                    }, 1000);
                                }
                            } catch (error) {
                                console.error("Erreur réseau : ", error);
                            }
                        });
                    }
                </script>
            </main>
            <footer>
                <?php
                include __DIR__ . '/../elements/footer.php';
                ?>
            </footer>

        </div>
    </body>