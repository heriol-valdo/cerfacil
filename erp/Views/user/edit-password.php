<?php
include __DIR__ . '/../elements/header.php';
require_once __DIR__ . '/../../Controller/HomeController.php';

$userProfil = HomeController::getprofil();

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title> Mon Profil | ErpFacil</title>
    <link rel="stylesheet" href="../../erp/assets/style/profilstyle.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="../../erp/assets/script/toast.js"></script>
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
                <div class="content">
                    <form id="passwordForm">
                        <div class="user-details">
                            <div class="input-box">
                                <span class="details" for="oldPassword">Mot de passe actuel </span>
                                <input type="password" id="oldPassword" name="oldPassword" required/>
                            </div>
                            <div class="input-box">
                                <span class="details" for="newPassword">Nouveau mot de passe</span>
                                <input type="password" id="newPassword" name="newPassword" required/>
                            </div>
                            <div class="input-box">
                                <span class="details" for="confirmPassword">Confirmation du mot de passe</span>
                                <input type="password" id="confirmPassword" name="confirmPassword" required/>
                            </div>
                        </div>
                        <div class="button-centre">
                            <button class="custom-button" type="submit" id="editPasswordBtn">Modifier mon mot de passe</button>
                            <a class="custom-button" href="profil">Annuler</a>
                        </div>
                    </form>


                </div>
            </div>

        </main>
        <footer>
            <?php
            include __DIR__ . '/../elements/footer.php';
            ?>
        </footer>

    </div>

    <script>

     document.getElementById('passwordForm').addEventListener('submit',async function(e) {
        e.preventDefault();

        const oldPassword = document.getElementById('oldPassword').value;
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
            
        // Validation côté client
        if (newPassword !== confirmPassword) {
            showToast(false, "Le mot de passe de confirmation n'est pas identique", '');
            return;
        }
        
        const data = {
            oldPassword: oldPassword,
            newPassword: newPassword
        };

        try {

                const response = await fetch("editPasswordSend", {
                    method: "POST",
                    headers: {
                    "Content-Type": "application/json",
                    },
                    body: JSON.stringify(data),
                });

                const result = await response.json();

                if (result.erreur) {
                    console.log(result.erreur);

                    showToast(false, result.erreur, '');

                }else if(result.valid) {

                    showToast(true, result.valid, "");

                    setTimeout(() => {
                    window.location.href = "profil";
                    }, 1000);
                }
        } catch (error) {
                showToast(false, error, "");
                console.log(error);
        }
      });
    </script>
</body>

</html>