<?php

if (isset($_SESSION['user'])):
 
  header('Location: /erp/home');
  exit;
?>
<?php else: ?>
  <!DOCTYPE html>
  <html lang="fr">

  <head>
    <meta charset="UTF-8">
    <title>Se connecter à l'administration | ErpFacil </title>
    <link rel="icon" type="image/png" href="../../erp/assets/image/favicon.png" sizes="16x16">
    <link rel="icon" type="image/png" href="../../erp/assets/image/favicon.png" sizes="32x32">
    <link rel="stylesheet" href="../../erp/assets/style/loginstyle.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  </head>

  <body>
    <div class="wrapper">
      <form id="loginForm">
        <img class="logo" src="../../erp/assets/image/Fichier 1.png" alt="logo Cerfacil">
        <h2>ErpFacil</h2>
        <div class="input-field">
          <label class="text">Email </label>
          <input name="email" type="text" required>
        </div>
        <div class="input-field">
          <label class="text">Mot de passe</label>
          <input name="password" type="password" required>
        </div>
        <div class="forget">
          <label for="remember">
            <input type="checkbox" id="remember">
            <p>Se souvenir de moi</p>
          </label>
          <a href="askPassword">Mot de passe oublié ?</a>
        </div>
        <input class="button" type="submit" value="Se connecter">
      </form>
    </div>


    <!-- Script showToast -->
    <script src="../../erp/assets/script/toast.js"></script>

    <script>
      document.getElementById("loginForm").addEventListener("submit", async function (e) {
        e.preventDefault();


        const formData = new FormData(this);
        const data = {
          email: formData.get("email"),
          password: formData.get("password"),
        };

        try {

          const response = await fetch("login", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
          });

          const result = await response.json();

          if (result.erreur) {

            showToast(false, result.erreur, '');

          } else if(result.redirect){
               window.location.href = result.redirect;
            
          }else if (result.valid) {


            showToast(true, result.valid, "chargement en cours...");

            setTimeout(() => {
              window.location.href = "home";
            }, 1000);
          }
        } catch (error) {
          Toastify({
            text: error,
            duration: 2000,
            backgroundColor: "red",
          }).showToast();
        }
      });
    </script>


    <?php
    if (isset($_COOKIE["logout"])) {
      $logoutMessage = htmlspecialchars($_COOKIE["logout"], ENT_QUOTES, 'UTF-8');

      echo "
        <script>
        showToast(true, '{$logoutMessage}', 'Au revoir !'); 
        </script>
      ";

      setcookie("logout", "", time() - 3600, "/");
    }
    ?>

  </body>

  </html>


<?php endif; ?>