<?php


//require_once __DIR__ . '/../../controller/User/askResetPasswordController.php';
?>



<!DOCTYPE html>

<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mot de passe oublié | ErpFacil</title>
  <link rel="stylesheet" href="../../erp/assets/style/loginstyle.css">
  <link rel="stylesheet" href="../../erp/assets/style/contentContainer.css" />
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="icon" type="image/png" href="../../erp/assets/image/favicon.png" sizes="16x16">
    <link rel="icon" type="image/png" href="../../erp/assets/image/favicon.png" sizes="32x32">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
  <div class="content-container">
    <main>
      <div class="body-container">
        <form id="askForm" class="reset-password-form">
          <img class="logo" src="../../erp/assets/image/Fichier 1.png" alt="logo LGX France">
          <h2>ErpFacil</h2>
          <h2>Mot de passe oublié ?</h2>
          <p style="text-align: center;">Nous vous enverrons un lien de récupération par mail. 
            
          <br/>Attention, il n'est valable que pendant 15 minutes.</p>
          <div class="input-field">
            <input type="text" required placeholder="Veuillez rentrer votre adresse e-mail" name="email" />
          </div>
          <div class="bottom-btn">
            <div class="btn">
              <a href="/erp/"><button class="button-base" type="button">Retour à la connexion</button></a>
            </div>
            <div class="btn">
              <input class="button-base" type="submit" value="Envoyer le lien de récupération">
            </div>
          </div>
        </form>
      </div>
    </main>
    <footer>
      <?php
      include __DIR__ . '/../elements/footer.php';
      ?>
    </footer>
  </div>


  <!-- Script showToast -->
  <script src="../../erp/assets/script/toast.js"></script>
  <script>
    document.getElementById("askForm").addEventListener("submit", async function (e) {
      e.preventDefault();

      const formData = new FormData(this);
      const data = {
        email: formData.get("email"),
      };

      try {
        const response = await fetch("askPasswordSend", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        });

        const result = await response.json();

        if (result.erreur) {
          showToast(false, result.erreur, '');
        } else if (result.valid) {
          showToast(true, result.valid, "");
        }
      } catch (error) {
        showToast(false, error, "");
      }
    });
  </script>