<?php


// require_once __DIR__ . '/../../requestFile/authRequet.php';
// require_once __DIR__ . '/../../controller/User/resetPasswordController.php';


?>

<!DOCTYPE html>

<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Réinitialisation du mot de passe | ErpFacil</title>
  <link rel="stylesheet" href="../../erp/assets/style/loginstyle.css">
  <link rel="icon" type="image/png" href="../../erp/assets/image/favicon.png" sizes="16x16">
  <link rel="icon" type="image/png" href="../../erp/assets/image/favicon.png" sizes="32x32">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    .recuperation {
      display: flex;
      justify-content: center;
      align-items: center;
     
    }

  </style>
</head>

<body>

  <div class="wrapper">
    <form id="resetForm">
      <img class="logo" src="../../erp/assets/image/Fichier 1.png" alt="logo LGX France" />
      <h2>Mot de passe</h2>
      <div class="input-field">
        <input type="password" required placeholder="Nouveau mot de passe" name="newPassword" />
        <input type="hidden" name="token" value="<?= $_GET['reset_token'] ?>" />
      </div>
      <div class="input-field">
        <input type="password" required placeholder="Confirmer votre mot de passe" name="confirmPassword" />
      </div>
      <div class="btn">
        <input class="button recuperation" type="submit" value="Récupération du compte">
      </div>
    </form>
  </div>

  <!-- Script showToast -->
  <script src="../../erp/assets/script/toast.js"></script>


  <script>
    document.getElementById("resetForm").addEventListener("submit", async function (e) {
      e.preventDefault();

      const formData = new FormData(this);
      const data = {
        newPassword: formData.get("newPassword"),
        confirmPassword: formData.get("confirmPassword"),
        reset_token: formData.get("token"),
      };

      try {
        const response = await fetch("resetPasswordSend", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        });

        const result = await response.json();

        if (result.erreur) {
          showToast(false, result.erreur, 'Il y a eu une erreur, retour à l\'accueil');
        } else if (result.valid) {
          showToast(true, result.valid, "");
          setTimeout(() => {
            window.location.href = "home";
          }, 1000);
        } else if (result.erreur_token) {
          showToast(false, result.erreur_token, "Il y a eu une erreur, retour à l\'accueil");
          setTimeout(() => {
            window.location.href = "home";
          }, 1000);
        }
      } catch (error) {
        showToast(false, error, "");
      }
    });
  </script>
</body>

</html>