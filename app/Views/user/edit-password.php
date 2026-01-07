<?php
include __DIR__ . '/../elements/header.php';
require_once __DIR__ . '/../../Controller/HomeController.php';

$userProfil = HomeController::getprofil();

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title> Mon Profil | CerFacil</title>
    <!-- <link rel="stylesheet" href="../../app/assets/style/profilstyle.css">
     <link rel="stylesheet" href="../../app/assets/style/contentContainer.css"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="../../app/assets/script/toast.js"></script>

 <style>
    /* Import Google Fonts */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

:root {
    --primary-color: #3b82f6;
    --secondary-color: #06b6d4;
    --accent-color: #10b981;
    --danger-color: #ef4444;
    --warning-color: #f59e0b;
    --dark-bg: #1e293b;
    --light-bg: #f8fafc;
    --white: #ffffff;
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --text-light: #94a3b8;
    --border-color: #e2e8f0;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

footer {
            padding: 0;
            text-align: center;
            color: var(--3main-color);
            width: 100%;
             background-color:rgb(228, 234, 245);
        }

        .footer-bottom p {
            font-size: 15px;
            margin-bottom: 0;
        }

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
    color: var(--text-primary);
}

/* Content Container */
.content-container {
    margin-left: 78px;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    transition: margin-left 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Header Styles */
header {
    background: linear-gradient(135deg, var(--white) 0%, #f1f5f9 100%);
    padding: 2rem 2.5rem;
    box-shadow: var(--shadow-md);
    border-bottom: 1px solid var(--border-color);
    position: sticky;
    top: 0;
    z-index: 50;
    backdrop-filter: blur(10px);
}

.header-container {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    max-width: 1200px;
    margin: 0 auto;
}

.header-icon {
    width: 60px;
    height: 60px;
     background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    box-shadow: var(--shadow-lg);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.header-icon:hover {
    transform: scale(1.05) rotate(5deg);
    box-shadow: var(--shadow-xl);
}

.title h1 {
    font-size: 2.5rem;
    font-weight: 700;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Main Content */
main {
    flex: 1;
    padding: 3rem 2.5rem;
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
}

/* Container */
.container {
    background: var(--white);
    border-radius: 20px;
    box-shadow: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    overflow: hidden;
    border: 1px solid linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.container:hover {
    transform: translateY(-2px);
    box-shadow: 0 25px 50px -12px  linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      
}

/* Role Input */
.input-role {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 1.5rem 2rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.input-role::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.input-role span {
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
    display: block;
    position: relative;
    z-index: 1;
}

.input-role input {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    width: 100%;
    backdrop-filter: blur(10px);
    position: relative;
    z-index: 1;
}

.input-role input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

/* Content */
.content {
    padding: 2.5rem;
}

/* Form */
form {
    width: 100%;
}

/* User Details Grid */
.user-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

/* Input Box */
.input-box {
    position: relative;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.input-box:hover {
    transform: translateY(-2px);
}

.input-box .details {
    display: block;
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    position: relative;
}

.input-box .details::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    width: 30px;
    height: 2px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 1px;
}

.input-box input {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 500;
    color: var(--text-secondary);
    background: var(--light-bg);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: not-allowed;
}

.input-box input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    background: var(--white);
}

.input-box input:hover {
    border-color: var(--primary-color);
    background: var(--white);
}

/* Input Centre (pour les champs spéciaux) */
.input-centre {
    margin: 2rem 0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.input-centre:hover {
    transform: translateY(-2px);
}

.input-centre .details {
    display: block;
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: var(--text-primary);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    position: relative;
}

.input-centre .details::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    width: 30px;
    height: 2px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 1px;
}

.input-centre input {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 500;
    color: var(--text-secondary);
    background: var(--light-bg);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: not-allowed;
}

/* Button Centre */
.button-centre {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    flex-wrap: wrap;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid var(--border-color);
}

/* Custom Button */
.custom-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: var(--shadow-md);
    position: relative;
    overflow: hidden;
    min-width: 200px;
}

.custom-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transition: left 0.5s;
}

.custom-button:hover::before {
    left: 100%;
}

.custom-button:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-xl);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.custom-button:active {
    transform: translateY(-1px);
}

.custom-button:nth-child(2) {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.custom-button:nth-child(2):hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}



/* Responsive Design */
@media (max-width: 1024px) {
    .content-container {
        margin-left: 0;
    }
    
    main {
        padding: 2rem 1.5rem;
    }
    
    .header-container {
        padding: 0 1.5rem;
    }
    
    .title h1 {
        font-size: 2rem;
    }
}

@media (max-width: 768px) {
    .user-details {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .button-centre {
        flex-direction: column;
        align-items: center;
    }
    
    .custom-button {
        width: 100%;
        max-width: 300px;
    }
    
    .title h1 {
        font-size: 1.75rem;
    }
    
    header {
        padding: 1.5rem 1rem;
    }
    
    main {
        padding: 1.5rem 1rem;
    }
    
    .content {
        padding: 2rem 1.5rem;
    }
}

@media (max-width: 480px) {
    .header-container {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .header-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
    
    .title h1 {
        font-size: 1.5rem;
    }
    
    .input-role {
        padding: 1rem 1.5rem;
    }
    
    .content {
        padding: 1.5rem 1rem;
    }
}

/* Animation Classes */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.container {
    animation: fadeInUp 0.6s ease-out;
}

.input-box:nth-child(odd) {
    animation: fadeInUp 0.6s ease-out 0.1s both;
}

.input-box:nth-child(even) {
    animation: fadeInUp 0.6s ease-out 0.2s both;
}

/* Loading States */
.input-box.loading input {
    background: linear-gradient(90deg, var(--light-bg) 25%, #e2e8f0 50%, var(--light-bg) 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* Focus States for Accessibility */
.custom-button:focus {
    outline: 2px solid var(--primary-color);
    outline-offset: 2px;
}

.input-box input:focus {
    outline: 2px solid var(--primary-color);
    outline-offset: 2px;
}
</style>
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
                            <a class="custom-button" href="home">Annuler</a>
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
                    window.location.href = "home";
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