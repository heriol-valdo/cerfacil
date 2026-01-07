<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../elements/header.php';
require_once __DIR__ . '/../../controller/User/validTokenController.php';
require_once __DIR__ . '/../../controller/Pointage/listPointageController.php';

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>Pointages | ErpFacil</title>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/style/listeEtudiants.css" />
    <link rel="stylesheet" href="../../assets/style/modals.css" />
    <link rel="stylesheet" href="../../assets/style/pagination.css" />
    <link rel="stylesheet" href="../../assets/style/cardStyle.css" />
    <link rel="stylesheet" href="../../assets/style/contentContainer.css" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
</head>

<body>
    <div class="content-container">
        <header>
            <div class="header-container">
                <i class="fa-solid fa-users header-icon"></i>
                <div class="title">
                    <h1>Pointages</h1>
                </div>
            </div>
            <div class="back-icon-container">
                <a href="<?= $backlink; ?>">
                    <i class="fa-solid fa-arrow-left-long fa-2xl" style="color: #263b4a;"></i>
                </a>
            </div>
        </header>
        <main>
            <div style="display:flex; flex-direction: row; ">
                <div style="display:flex; flex-direction:column; margin-right: 10px;">
                    <div class="d-flex justify-content-between mt-3 mb-3">
                        <select id="monthSelect" name="month" class="form-control">
                            <?php if($first_monthly_name != "00-00") : ?>
                                <?php foreach ($result_pointage_infos->data->monthly as $month => $details):
                                    $selected = ($month == $selected_month) ? 'selected' : '';
                                    $dateObj = DateTime::createFromFormat('Y-m', $month);
                                    $formattedMonth = formatDatetime("long", "none", $dateObj, "MMMM yyyy");
                                    ?>
                                    <option value="<?= $month ?>" <?= $selected ?>><?= ucfirst($formattedMonth) ?></option>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <option><?= ucfirst($currentMonth) ?></option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="container-card-column">
                        <h3><span id="monthName"></span></h3>
                        <p id="monthHours" class="card-main-text">
                            <?= $result_pointage_infos->data->monthly->$selected_month->month_hours ?? '00:00' ?>
                        </p>
                        <p class="card-sub-text">heures ce mois-ci</p>
                    </div>
                    <?php if(in_array($_SESSION['user']['role'], [1,2,3,4,6])) : ?>
                        <div class="container-big-card-column">
                            <h3>Profil de l'étudiant</h3>
                            <p style="font-size:14px"><?= strtoupper($result_profil_etudiant->data->etudiantDatas->lastname) ?> <?= $result_profil_etudiant->data->etudiantDatas->firstname ?></p>
                            <p><a href="mailto:<?= $result_profil_etudiant->data->etudiantDatas->email ?>"><?= $result_profil_etudiant->data->etudiantDatas->email ?></a></p>
                            <p>Né(e) le : <?= $date_naissance ?></p>
                            <p><?= $result_profil_etudiant->data->etudiantDatas->adressePostale ?></p>
                            <p><?= $result_profil_etudiant->data->etudiantDatas->codePostal ?> <?= $result_profil_etudiant->data->etudiantDatas->ville ?></p>
                        </div>
                    <?php endif; ?>
                    <div class="container-card-column">
                        <h3>Heures totales</h3>
                        <p class="card-main-text"><?= $result_pointage_infos->data->total_hours ?></p>
                        <p class="card-sub-text">heures totales</p>
                    </div>

                </div>
                <div style="width:75%">
                    <?php if (!empty($result_pointage_infos->data->monthly)): ?>
                        <div class="table-container">
                            <table class="table table-striped mt-3" id="gestionnaireTable">
                                <thead id="tableHead">
                                    <tr>
                                        <th>Date</th>
                                        <th>Heures enregistrées</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <?php
                                    if (isset($result_pointage_infos->data->monthly->$selected_month)):
                                        foreach ($result_pointage_infos->data->monthly->$selected_month->days as $date => $hours):
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($date); ?></td>
                                                <td><?= htmlspecialchars($hours); ?></td>
                                            </tr>
                                            <?php
                                        endforeach;
                                    else:
                                        ?>
                                        <tr>
                                            <td colspan="2">Aucune donnée disponible pour le mois sélectionné.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <p id="noResultsMessage" style="display:none;">Aucun résultat trouvé</p>
                        </div>
                    <?php else: ?>
                        <p><?php echo htmlspecialchars($erreur); ?></p>
                    <?php endif; ?>
                </div>

            </div>


            <!-- Script showToast -->
            <script src="../../assets/script/toast.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

            <script>
                function updateMonthDisplay() {
                    const selectedMonth = document.getElementById('monthSelect').value;

                    const dateObj = new Date(`${selectedMonth}-01`);
                    let monthName = dateObj.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
                    monthName = monthName.charAt(0).toUpperCase() + monthName.slice(1);

                    document.getElementById('monthName').textContent = monthName;
                    console.log(monthName);

                    const monthlyData = <?= json_encode($result_pointage_infos->data->monthly); ?>;
                    const monthHours = monthlyData[selectedMonth]?.month_hours || '00:00';
                    document.getElementById('monthHours').textContent = monthHours;

                    const tableBody = document.getElementById('tableBody');
                    const noResultsMessage = document.getElementById('noResultsMessage');
                    tableBody.innerHTML = '';

                    if (monthlyData[selectedMonth] && Object.keys(monthlyData[selectedMonth].days).length > 0) {
                        noResultsMessage.style.display = 'none';

                        Object.keys(monthlyData[selectedMonth].days).forEach(function (date) {
                            const hours = monthlyData[selectedMonth].days[date];

                            const row = document.createElement('tr');
                            const dateCell = document.createElement('td');
                            const hoursCell = document.createElement('td');

                            dateCell.textContent = date;
                            hoursCell.textContent = hours;

                            row.appendChild(dateCell);
                            row.appendChild(hoursCell);

                            tableBody.appendChild(row);
                        });
                    } else {
                        noResultsMessage.style.display = '';
                    }
                }

                window.onload = updateMonthDisplay;

                document.getElementById('monthSelect').addEventListener('change', updateMonthDisplay);

            </script>
        </main>
        <footer>
            <?php
            include __DIR__ . '/../elements/footer.php';
            ?>
        </footer>
    </div>
</body>

</html>