<?php
// require_once __DIR__ . '/../../requestFile/authRequet.php';
// require_once __DIR__ . '/../../controller/User/resetPasswordController.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Réinitialisation du mot de passe | CerFacil</title>
  <link rel="icon" type="image/png" href="../../app/assets/image/favicon.png" sizes="16x16">
  <link rel="icon" type="image/png" href="../../app/assets/image/favicon.png" sizes="32x32">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
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
      margin-bottom: 1rem;
      background: linear-gradient(135deg, #667eea, #764ba2);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .subtitle {
      text-align: center;
      color: #718096;
      font-size: 1rem;
      margin-bottom: 2rem;
      line-height: 1.5;
    }

    .input-field {
      margin-bottom: 1.5rem;
      position: relative;
    }

    .input-field i {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #a0aec0;
      font-size: 1.1rem;
      transition: color 0.3s ease;
    }

    input[type="password"],
    input[type="hidden"] {
      width: 100%;
      padding: 1rem 1.25rem 1rem 3rem;
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(10px);
    }

    input[type="hidden"] {
      display: none;
    }

    input[type="password"]:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
      transform: translateY(-2px);
    }

    input[type="password"]:focus + i,
    .input-field:focus-within i {
      color: #667eea;
    }

    .password-strength {
      margin-top: 0.5rem;
      font-size: 0.875rem;
    }

    .strength-bar {
      width: 100%;
      height: 4px;
      background: #e2e8f0;
      border-radius: 2px;
      margin-top: 0.5rem;
      overflow: hidden;
    }

    .strength-fill {
      height: 100%;
      transition: all 0.3s ease;
      border-radius: 2px;
    }

    .strength-weak .strength-fill {
      width: 33%;
      background: #f56565;
    }

    .strength-medium .strength-fill {
      width: 66%;
      background: #ed8936;
    }

    .strength-strong .strength-fill {
      width: 100%;
      background: #48bb78;
    }

    .strength-text {
      margin-top: 0.25rem;
      font-size: 0.8rem;
    }

    .strength-weak .strength-text {
      color: #f56565;
    }

    .strength-medium .strength-text {
      color: #ed8936;
    }

    .strength-strong .strength-text {
      color: #48bb78;
    }

    .requirements {
      background: rgba(102, 126, 234, 0.05);
      border: 1px solid rgba(102, 126, 234, 0.2);
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1.5rem;
    }

    .requirements h4 {
      color: #4a5568;
      font-size: 0.9rem;
      margin-bottom: 0.5rem;
      font-weight: 600;
    }

    .requirement {
      display: flex;
      align-items: center;
      font-size: 0.8rem;
      color: #718096;
      margin-bottom: 0.25rem;
    }

    .requirement i {
      margin-right: 0.5rem;
      font-size: 0.7rem;
    }

    .requirement.valid {
      color: #48bb78;
    }

    .requirement.valid i {
      color: #48bb78;
    }

    .btn {
      margin-top: 1rem;
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

    .button:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
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

    .back-link {
      text-align: center;
      margin-top: 1.5rem;
    }

    .back-link a {
      color: #667eea;
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 500;
      transition: color 0.3s ease;
      display: inline-flex;
      align-items: center;
    }

    .back-link a:hover {
      color: #764ba2;
    }

    .back-link i {
      margin-right: 0.5rem;
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
    <form id="resetForm">
      <img class="logo" src="../../app/assets/image/Fichier 1.png" alt="logo CerFacil" />
      <h2>Nouveau mot de passe</h2>
      <p class="subtitle">Créez un mot de passe sécurisé pour votre compte CerFacil</p>
      
      <div class="requirements">
        <h4>Exigences du mot de passe :</h4>
        <div class="requirement" id="length">
          <i class="fas fa-circle"></i>
          Au moins 12 caractères
        </div>
        <div class="requirement" id="uppercase">
          <i class="fas fa-circle"></i>
          Une lettre majuscule
        </div>
        <div class="requirement" id="lowercase">
          <i class="fas fa-circle"></i>
          Une lettre minuscule
        </div>
        <div class="requirement" id="number">
          <i class="fas fa-circle"></i>
          Un chiffre
        </div>
        <div class="requirement" id="special">
          <i class="fas fa-circle"></i>
          Un caractère spécial
        </div>
      </div>

      <div class="input-field">
        <input type="password" required placeholder="Nouveau mot de passe" name="newPassword" id="newPassword" />
        <i class="fas fa-lock"></i>
        <div class="password-strength" id="passwordStrength">
          <div class="strength-bar">
            <div class="strength-fill"></div>
          </div>
          <div class="strength-text"></div>
        </div>
      </div>

      <div class="input-field">
        <input type="password" required placeholder="Confirmer votre mot de passe" name="confirmPassword" id="confirmPassword" />
        <i class="fas fa-lock"></i>
        <input type="hidden" name="token" value="<?= $_GET['reset_token'] ?>" />
      </div>

      <div class="btn">
        <input class="button" type="submit" value="Réinitialiser le mot de passe" id="submitBtn">
      </div>

      <div class="back-link">
        <a href="/app/">
          <i class="fas fa-arrow-left"></i>
          Retour à la connexion
        </a>
      </div>
    </form>
  </div>

  <!-- Script showToast -->
  <script src="../../app/assets/script/toast.js"></script>

  <script>
    // Password strength checker
    function checkPasswordStrength(password) {
      let score = 0;
      const requirements = {
        length: password.length >= 12,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /\d/.test(password),
        special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
      };

      // Update requirement indicators
      Object.keys(requirements).forEach(req => {
        const element = document.getElementById(req);
        if (requirements[req]) {
          element.classList.add('valid');
          element.querySelector('i').className = 'fas fa-check-circle';
          score++;
        } else {
          element.classList.remove('valid');
          element.querySelector('i').className = 'fas fa-circle';
        }
      });

      // Update strength bar
      const strengthElement = document.getElementById('passwordStrength');
      const strengthText = strengthElement.querySelector('.strength-text');
      
      strengthElement.className = 'password-strength';
      
      if (score < 3) {
        strengthElement.classList.add('strength-weak');
        strengthText.textContent = 'Faible';
      } else if (score < 5) {
        strengthElement.classList.add('strength-medium');
        strengthText.textContent = 'Moyen';
      } else {
        strengthElement.classList.add('strength-strong');
        strengthText.textContent = 'Fort';
      }

      return score >= 5;
    }

    // Password validation
    function validatePasswords() {
      const newPassword = document.getElementById('newPassword').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      const submitBtn = document.getElementById('submitBtn');
      
      const isStrong = checkPasswordStrength(newPassword);
      const isMatching = newPassword === confirmPassword && confirmPassword !== '';
      
      submitBtn.disabled = !isStrong || !isMatching;
      
      return isStrong && isMatching;
    }

    // Event listeners
    document.getElementById('newPassword').addEventListener('input', validatePasswords);
    document.getElementById('confirmPassword').addEventListener('input', validatePasswords);

    // Form submission
    document.getElementById("resetForm").addEventListener("submit", async function (e) {
      e.preventDefault();

      if (!validatePasswords()) {
        showToast(false, 'Veuillez respecter tous les critères du mot de passe', '');
        return;
      }

      const submitButton = this.querySelector('.button');
      const originalText = submitButton.value;
      
      // Add loading state
      submitButton.classList.add('loading');
      submitButton.value = '';

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

        // Remove loading state
        submitButton.classList.remove('loading');
        submitButton.value = originalText;

        if (result.erreur) {
          showToast(false, result.erreur, 'Il y a eu une erreur, retour à l\'accueil');
        } else if (result.valid) {
          // Add success animation
          document.querySelector('.wrapper').classList.add('success-animation');
          
          showToast(true, result.valid, "Redirection en cours...");
          setTimeout(() => {
            window.location.href = "/app/";
          }, 1500);
        } else if (result.erreur_token) {
          showToast(false, result.erreur_token, "Il y a eu une erreur, retour à l'accueil");
          setTimeout(() => {
            window.location.href = "/app/";
          }, 1500);
        }
      } catch (error) {
        // Remove loading state on error
        submitButton.classList.remove('loading');
        submitButton.value = originalText;
        
        showToast(false, "Une erreur est survenue", "");
      }
    });

    // Add focus animations
    document.querySelectorAll('input[type="password"]').forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'translateY(-2px)';
      });
      
      input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'translateY(0)';
      });
    });

    // Initial validation
    validatePasswords();
  </script>
</body>

</html>