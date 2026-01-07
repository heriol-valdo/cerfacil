<?php
if (isset($_SESSION['user'])):
  header('Location: /app/home');
  exit;
?>
<?php else: ?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Se connecter à l'administration | CerFacil</title>
  <link rel="icon" type="image/png" href="../../app/assets/image/favicon.png" sizes="16x16">
  <link rel="icon" type="image/png" href="../../app/assets/image/favicon.png" sizes="32x32">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>

    .button {
    transition: all 0.3s ease;
    position: relative;
    }

    .button:disabled {
      opacity: 0.7;
      cursor: not-allowed;
    }

    .button.loading {
      background: linear-gradient(45deg, #007bff, #0056b3);
    }

    .fa-spinner {
      margin-right: 8px;
    }

    .fa-check {
      margin-right: 8px;
    }


    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    /* Animated background elements */
    body::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
      animation: rotate 20s linear infinite;
      pointer-events: none;
    }

    @keyframes rotate {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Floating particles */
    .particles {
      position: absolute;
      width: 100%;
      height: 100%;
      overflow: hidden;
      pointer-events: none;
    }

    .particle {
      position: absolute;
      width: 4px;
      height: 4px;
      background: rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      animation: float 6s ease-in-out infinite;
    }

    .particle:nth-child(1) { left: 10%; animation-delay: 0s; }
    .particle:nth-child(2) { left: 20%; animation-delay: 1s; }
    .particle:nth-child(3) { left: 30%; animation-delay: 2s; }
    .particle:nth-child(4) { left: 40%; animation-delay: 3s; }
    .particle:nth-child(5) { left: 50%; animation-delay: 4s; }
    .particle:nth-child(6) { left: 60%; animation-delay: 5s; }

    @keyframes float {
      0%, 100% { transform: translateY(100vh) scale(0); }
      10% { transform: translateY(90vh) scale(1); }
      90% { transform: translateY(-10vh) scale(1); }
      100% { transform: translateY(-10vh) scale(0); }
    }

    .wrapper {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 24px;
      padding: 3rem;
      width: 100%;
      max-width: 440px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
      position: relative;
      z-index: 10;
      animation: slideUp 0.8s ease-out;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(50px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .logo {
      width: 80px;
      height: 80px;
      display: block;
      margin: 0 auto 1rem;
      border-radius: 16px;
      box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
      transition: transform 0.3s ease;
    }

    .logo:hover {
      transform: scale(1.05) rotate(5deg);
    }

    h2 {
      text-align: center;
      color: #2d3748;
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 2rem;
      background: linear-gradient(135deg, #667eea, #764ba2);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .input-field {
      margin-bottom: 1.5rem;
      position: relative;
    }

    .text {
      display: block;
      color: #4a5568;
      font-weight: 500;
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 1rem 1.25rem;
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(10px);
    }

    input[type="text"]:focus,
    input[type="password"]:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
      transform: translateY(-2px);
    }

    .forget {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .forget label {
      display: flex;
      align-items: center;
      cursor: pointer;
      color: #4a5568;
      font-size: 0.9rem;
    }

    .forget input[type="checkbox"] {
      margin-right: 0.5rem;
      width: 18px;
      height: 18px;
      accent-color: #667eea;
    }

    .forget a {
      color: #667eea;
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .forget a:hover {
      color: #764ba2;
      text-decoration: underline;
    }

    .button {
      width: 100%;
      padding: 1rem;
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .button::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }

    .button:hover::before {
      left: 100%;
    }

    .button:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }

    .button:active {
      transform: translateY(0);
    }

    /* Loading state */
    .button.loading {
      pointer-events: none;
      opacity: 0.7;
    }

    .button.loading::after {
      content: '';
      position: absolute;
      width: 20px;
      height: 20px;
      margin: auto;
      border: 2px solid transparent;
      border-top-color: #ffffff;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Responsive design */
    @media (max-width: 480px) {
      .wrapper {
        margin: 1rem;
        padding: 2rem;
        border-radius: 16px;
      }
      
      h2 {
        font-size: 1.75rem;
      }
      
      .forget {
        flex-direction: column;
        align-items: flex-start;
      }
    }

    /* Success animation */
    @keyframes success {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }

    .success-animation {
      animation: success 0.6s ease-in-out;
    }
  </style>
</head>

<body>
  <div class="particles">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
  </div>

  <div class="wrapper">
    <form id="loginForm">
      <img class="logo" src="../../app/assets/image/Fichier 1.png" alt="logo Cerfacil">
      <h2>CerFacil</h2>
      
      <div class="input-field">
        <label class="text">Email</label>
        <input name="email" type="text" required placeholder="Entrez votre email">
      </div>
      
      <div class="input-field">
        <label class="text">Mot de passe</label>
        <input name="password" type="password" required placeholder="Entrez votre mot de passe">
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
  <script src="../../app/assets/script/toast.js"></script>

 <script>
document.getElementById("loginForm").addEventListener("submit", async function (e) {
  e.preventDefault();

  const submitButton = this.querySelector('.button');
  const originalText = submitButton.innerHTML; // Utiliser innerHTML au lieu de value
  
  // Add loading state avec icône FontAwesome
  submitButton.classList.add('loading');
  submitButton.disabled = true;
  submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connexion...';

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

    // Remove loading state
    submitButton.classList.remove('loading');
    submitButton.disabled = false;
    submitButton.innerHTML = originalText;

    if (result.erreur) {
      showToast(false, result.erreur, '');
    } else if(result.redirect) {
      window.location.href = result.redirect;
    } else if (result.valid) {
      // Animation de succès avec icône
      submitButton.innerHTML = '<i class="fas fa-check"></i> Connecté !';
      submitButton.style.backgroundColor = '#28a745';
      
      // Add success animation
      document.querySelector('.wrapper').classList.add('success-animation');
      
      showToast(true, result.valid, "Chargement en cours...");

      setTimeout(() => {
        window.location.href = "home";
      }, 1000);
    }
  } catch (error) {
    // Remove loading state on error
    submitButton.classList.remove('loading');
    submitButton.disabled = false;
    submitButton.innerHTML = originalText;
    
    Toastify({
      text: "Une erreur est survenue",
      duration: 2000,
      backgroundColor: "red",
    }).showToast();
  }
});

// Add focus animations
document.querySelectorAll('input[type="text"], input[type="password"]').forEach(input => {
  input.addEventListener('focus', function() {
    this.parentElement.style.transform = 'translateY(-2px)';
    this.parentElement.style.transition = 'transform 0.3s ease';
  });
  
  input.addEventListener('blur', function() {
    this.parentElement.style.transform = 'translateY(0)';
  });
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