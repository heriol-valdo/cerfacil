<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mot de passe oublié | CerFacil</title>
  <link rel="icon" type="image/png" href="../../app/assets/image/favicon.png" sizes="16x16">
  <link rel="icon" type="image/png" href="../../app/assets/image/favicon.png" sizes="32x32">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
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

    .content-container {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 10;
      position: relative;
    }

    .body-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 24px;
      padding: 3rem;
      width: 100%;
      max-width: 480px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
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

    h2:first-of-type {
      margin-bottom: 0.5rem;
    }

    h2:last-of-type {
      font-size: 1.5rem;
      margin-bottom: 1.5rem;
    }

    p {
      text-align: center;
      color: #4a5568;
      font-size: 0.95rem;
      line-height: 1.6;
      margin-bottom: 2rem;
      padding: 1rem;
      background: rgba(102, 126, 234, 0.05);
      border-radius: 12px;
      border-left: 4px solid #667eea;
    }

    .input-field {
      margin-bottom: 2rem;
      position: relative;
    }

    input[type="text"] {
      width: 100%;
      padding: 1rem 1.25rem;
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(10px);
    }

    input[type="text"]:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
      transform: translateY(-2px);
    }

    .bottom-btn {
      display: flex;
      gap: 1rem;
      flex-direction: column;
    }

    .btn {
      width: 100%;
    }

    .button-base {
      width: 100%;
      padding: 1rem;
      border: none;
      border-radius: 12px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      text-decoration: none;
      display: inline-block;
      text-align: center;
    }

    .btn:first-child .button-base {
      background: rgba(255, 255, 255, 0.9);
      color: #667eea;
      border: 2px solid #667eea;
    }

    .btn:first-child .button-base:hover {
      background: #667eea;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .btn:last-child .button-base {
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
    }

    .btn:last-child .button-base::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }

    .btn:last-child .button-base:hover::before {
      left: 100%;
    }

    .btn:last-child .button-base:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }

    .button-base:active {
      transform: translateY(0);
    }

    /* Loading state */
    .button-base.loading {
      pointer-events: none;
      opacity: 0.7;
    }

    .button-base.loading::after {
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

    /* Link styling */
    a {
      text-decoration: none;
    }

    /* Responsive design */
    @media (max-width: 480px) {
      .body-container {
        margin: 1rem;
        padding: 2rem;
        border-radius: 16px;
        max-width: calc(100% - 2rem);
      }
      
      h2 {
        font-size: 1.75rem;
      }
      
      h2:last-of-type {
        font-size: 1.25rem;
      }

      .bottom-btn {
        gap: 0.75rem;
      }

      p {
        font-size: 0.9rem;
        padding: 0.75rem;
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

  <div class="content-container">
    <main>
      <div class="body-container">
        <form id="askForm" class="reset-password-form">
          <img class="logo" src="../../app/assets/image/Fichier 1.png" alt="logo CerFacil">
          <h2>CerFacil</h2>
          <h2>Mot de passe oublié ?</h2>
          <p>Nous vous enverrons un lien de récupération par mail.<br/>
          <strong>Attention, il n'est valable que pendant 15 minutes.</strong></p>
          
          <div class="input-field">
            <input type="text" required placeholder="Veuillez rentrer votre adresse e-mail" name="email" />
          </div>
          
          <div class="bottom-btn">
            <div class="btn">
              <a href="/app/">
                <button class="button-base" type="button">
                  <i class="fas fa-arrow-left" style="margin-right: 0.5rem;"></i>
                  Retour à la connexion
                </button>
              </a>
            </div>
            <div class="btn">
              <input class="button-base" type="submit" value="Envoyer le lien de récupération">
            </div>
          </div>
        </form>
      </div>
    </main>
  </div>

  <!-- Script showToast -->
  <script src="../../app/assets/script/toast.js"></script>
  
  <script>
    document.getElementById("askForm").addEventListener("submit", async function (e) {
      e.preventDefault();

      const submitButton = this.querySelector('input[type="submit"]');
      const originalText = submitButton.value;
      
      // Add loading state
      submitButton.classList.add('loading');
      submitButton.value = 'Envoyer le lien de récupération';

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

        // Remove loading state
        submitButton.classList.remove('loading');
        submitButton.value = originalText;

        if (result.erreur) {
          showToast(false, result.erreur, '');
        } else if (result.valid) {
          // Add success animation
          document.querySelector('.body-container').classList.add('success-animation');
          showToast(true, result.valid, "");
        }
      } catch (error) {
        // Remove loading state on error
        submitButton.classList.remove('loading');
        submitButton.value = originalText;
        
        showToast(false, "Une erreur est survenue", "");
      }
    });

    // Add focus animations
    document.querySelector('input[type="text"]').addEventListener('focus', function() {
      this.parentElement.style.transform = 'translateY(-2px)';
    });
    
    document.querySelector('input[type="text"]').addEventListener('blur', function() {
      this.parentElement.style.transform = 'translateY(0)';
    });
  </script>
</body>
</html>