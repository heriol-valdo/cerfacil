<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../elements/header.php';

require_once __DIR__ . "/../../controller/User/validTokenController.php";
require_once __DIR__ . '/../../requestFile/authRequet.php';
require_once __DIR__ . '/../../controller/Sessions/getMenuSessionsController.php';
?>

<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>Accueil | ErpFacil</title>
    <link rel="stylesheet" href="../../assets/style/contentContainer.css" />
    <link rel="stylesheet" href="../../assets/style/cardStyle.css" />
    <link rel="stylesheet" href="../../assets/style/tableStyle.css" />
    <link rel="stylesheet" href="../../assets/style/profilstyle.css" />
    <link rel="stylesheet" href="../../assets/style/loaderPointage.css" />


    <!-- Google Font Link for Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="../../assets/style/calendarStyle.css" />
</head>

<body>
    <div class="content-container">
        <header>
            <div class="header-container">
                <i class="fa-regular fa-user header-icon"></i>
                <div class="title">
                    <h1>Planning</h1>
                </div>
            </div>
            <div class="back-icon-container">
                <a href="<?= $backlink; ?>">
                    <i class="fa-solid fa-arrow-left-long fa-2xl" style="color: #263b4a;"></i>
                </a>
            </div>
        </header>
        <main>
            <?php if (in_array($_SESSION['user']['role'], [1, 2, 3, 4, 6])): ?>
                <div style="margin-bottom: 10px; display:flex; flex-direction: row; align-items: flex-start;">
                    <?php if (in_array($_SESSION['user']['role'], [4])): ?>
                        <a style="margin-right: 15px; height:38px; margin-top: 10px" href="../event/planning.php?selectedUser=<?= $_SESSION["user"]["id"] ?>"
                            class="btn btn-secondary">Mon planning</a>
                    <?php endif; ?>
                    <form action="" method="GET" id="sessionForm">
                        <?php if (in_array($_SESSION['user']['role'], [1])): ?>
                            <input type="hidden" name="centreId" value="<?= $_GET['centreId']; ?>"></input>
                        <?php endif; ?>
                        <div class="form-group" style="display:flex; flex-direction: column; margin-right: 15px;">
                            <label for="selectedSession" style="margin-bottom: 0;" class="form-label">Sélectionner une session :</label>
                            <select id="selectedSession" name="selectedSession"
                                onchange="document.getElementById('sessionForm').submit();" <?= !is_array($sessionsList) || empty($sessionsList) ? "disabled" : "" ?>>
                                <?php if (is_array($sessionsList) && !empty($sessionsList)): ?>
                                    <option value="">-- Sélectionner une session --</option>
                                    <?php foreach ($sessionsList as $session): ?>
                                        <?php $sessionEnd = new DateTime($session->dateFin);
                                        $currentDate = new DateTime();
                                        $sessionEnded = $sessionEnd < $currentDate ? " (terminée)" : "";
                                        $isSelected = isset($_GET['selectedSession']) && $_GET['selectedSession'] == $session->id ? 'selected' : '';
                                        ?>
                                        <option value="<?= $session->id; ?>" <?= $isSelected; ?>>
                                            <?= htmlspecialchars($session->nomSession); ?>             <?= $sessionEnded; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">Aucune session enregistrée</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </form>
                    <form action="" method="GET" id="userForm">
                        <?php if (in_array($_SESSION['user']['role'], [1])): ?>
                            <input type="hidden" name="centreId" value="<?= $_GET['centreId']; ?>"></input>
                            <?php if (isset($_GET['selectedSession'])): ?>
                                <input type="hidden" name="selectedSession" value="<?= $_GET['selectedSession']; ?>"></input>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="form-group" style="display:flex; flex-direction: column;">
                            <label for="selectedUser" style="margin-bottom: 0;" class="form-label">Sélectionner un utilisateur :</label>
                            <select id="selectedUser" name="selectedUser"
                                onchange="document.getElementById('userForm').submit();" <?= empty($_GET['selectedSession']) || !is_array($sessionsList) || empty($sessionsList) || !is_array($userList) || empty($userList) ? "disabled" : "" ?>>
                                <?php if (is_array($sessionsList) && !empty($sessionsList) && !empty($_GET['selectedSession'])): ?>
                                    <?php if (is_array($userList) && !empty($userList)): ?>
                                        <option value="">-- Sélectionner un utilisateur--</option>
                                        <?php foreach ($userList as $user): ?>
                                            <?php $userIsSelected = isset($_GET['selectedUser']) && $_GET['selectedUser'] == $user->id_users ? 'selected' : ''; ?>
                                            <option value="<?= $user->id_users; ?>" <?= $userIsSelected; ?>>
                                                (<?= htmlspecialchars($user->role); ?> ) <?= htmlspecialchars($user->lastname); ?>
                                                <?= htmlspecialchars($user->firstname); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="">Aucun participant enregistré</option>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <option value="">Aucune session sélectionnée</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
            <!-- Fin de la partie à déplacer -->
            <?php include_once __DIR__ . '/../event/event_calendar.php'; ?>
        </main>
        <footer>
            <?php
            include __DIR__ . '/../elements/footer.php';
            ?>
        </footer>
    </div>
</body>