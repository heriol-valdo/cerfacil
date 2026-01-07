<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
include __DIR__ . '/../elements/header.php';
require_once __DIR__ . '/../../controller/Formateur-Session/ListFormateurSessionController.php';
require_once __DIR__ . '/../../controller/Formateur/ListFormateurController.php';
require_once __DIR__ . '/../../controller/Sessions/ListSessionsController.php';
require_once __DIR__ . '/../../controller/User/validTokenController.php';
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <title>Formateurs participants à une session | ErpFacil</title>
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/style/listeEtudiants.css" />
  <link rel="stylesheet" href="../../assets/style/contentContainer.css" />
  <link rel="stylesheet" href="../../assets/style/profilstyle.css" />
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
</head>

<body>
  <div class="content-container">
    <header>
      <div class="header-container">
        <i class="fa-solid fa-question header-icon"></i>
        <div class="title"><h1>Liste des formateurs participants à une session</h1></div>
      </div>
    </header>
    <main>
      <div class="d-flex justify-content-between mt-3 mb-3">
        <input type="text" id="searchInput" class="form-control w-25" placeholder="Rechercher...">
        <div>
          <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addModal">Ajouter un formateur à une session</button>
        </div>
      </div>
      <?php if (!empty($allformationssessions)): ?>

        <table class="table table-striped mt-3" id="formationTable">
          <thead>
            <tr>
              <th>Formateur</th>
              <th>Formation</th>
              <th>Date Debut</th>
              <th>Date Fin</th>
              <?php if ($_SESSION['user']['role'] == 1) {
                echo "<th>Centre de formation</th>";
              } ?>
            </tr>
          </thead>
          <tbody>
            <?php
            if($_SESSION['user']['role'] == 1){
              foreach ($allformationssessions as $allformationssession) {
                ?>
                <tr>
                  <td><?php echo $allformationssession->nom_formateur !== null ? htmlspecialchars($allformationssession->nom_formateur) : ''; ?></td>
                  <td><?php echo $allformationssession->nom_formation !== null ? htmlspecialchars($allformationssession->nom_formation) : ''; ?></td>
                  <td><?php echo $allformationssession->dateDebut !== null ? htmlspecialchars($allformationssession->dateDebut) : ''; ?></td>
                  <td><?php echo $allformationssession->dateFin !== null ? htmlspecialchars($allformationssession->dateFin) : ''; ?></td>
                </tr>
                <?php
              }

            }else{
              foreach ($allformationssessions as $allformationssession) {
                ?>
                <tr>
                <td><?php echo $allformationssession->nom_formateur !== null ? htmlspecialchars($allformationssession->nom_formateur) : ''; ?></td>
                  <td><?php echo $allformationssession->nom_formation !== null ? htmlspecialchars($allformationssession->nom_formation) : ''; ?></td>
                  <td><?php echo $allformationssession->dateDebut !== null ? htmlspecialchars($allformationssession->dateDebut) : ''; ?></td>
                  <td><?php echo $allformationssession->dateFin !== null ? htmlspecialchars($allformationssession->dateFin) : ''; ?></td>
                </tr>
                <?php
              }
            }
            ?>
          </tbody>
        </table>
        <p id="noResultsMessage" style="display:none;">Aucun résultat trouvé</p>
      <?php else: ?>
        <p><?php echo $erreur; ?></p>
      <?php endif; ?>

      <!-- Modale pour ajouter une formation -->
      <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addModalLabel">Ajouter un formateur a un session</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="addForm">
                <div class="mb-3">
                  <label for="id" class="form-label">Session</label>
                  <select name="id" id="id" class="form-control" required>
                          <option value="">............</option>
                          <?php
                            foreach ($filtered_list_sessions as $allsession) {
                                echo '<option value="' . $allsession->id . '">' . $allsession->nomSession . " (". $allsession->nom_formation.") ".'</option>';
                            }
                          ?>
                          
                    </select>
                </div>
                <div class="mb-3">
                  <label for="id_formateurs" class="form-label">Formateur</label>
                  <select name="id_formateurs" id="id_formateurs" class="form-control" required>
                          <option value="">............</option>
                          <?php
                            foreach ($allformateurs as $allformateur) {
                                echo '<option value="' . $allformateur->id . '">' . $allformateur->lastname ." ". $allformateur->firstname . '</option>';
                            }
                          ?>
                          
                    </select>
                </div>
                            
                <div class="modal-footer">
                  <button type="submit" class="btn btn-secondary">Ajouter</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>


      <script>
        function showToast(value, text, subtext) {
          const toastContainer = document.createElement("div");
          toastContainer.style.display = "flex";
          toastContainer.style.alignItems = "center";
          toastContainer.style.justifyContent = "space-between";

          const icon = document.createElement("i");
          icon.className = value ? "fas fa-thin fa-check" : "fas fa-exclamation-circle";
          icon.style.marginRight = "10px";
          const closeButton = document.createElement("button");
          closeButton.innerText = "✖";
          closeButton.style.marginLeft = "10px";
          closeButton.style.cursor = "pointer";
          closeButton.style.color = "white";
          closeButton.style.backgroundColor = "transparent";
          closeButton.style.border = "none";
          closeButton.addEventListener("click", () => {
            toastInstance.hideToast();
          });

          const toastText = document.createElement("span");
          toastText.innerHTML = value ? `<strong>${text}</strong>, ${subtext} ` : "<strong>Erreur</strong>: " + text + "," + subtext;

          const progressBar = document.createElement("div");
          progressBar.className = "progress-bar";

          toastContainer.appendChild(icon);
          toastContainer.appendChild(toastText);
          toastContainer.appendChild(progressBar);
          toastContainer.appendChild(closeButton);

          const toastInstance = Toastify({
            duration: 3000,
            backgroundColor: value ? "green" : "red",
            gravity: "top",
            stopOnFocus: true,
            className: "toast-with-progress",
            escapeMarkup: false,
            node: toastContainer,
          });

          toastInstance.showToast();
        }

        const updateForm = document.getElementById("addForm");

        if (updateForm) {
          updateForm.addEventListener("submit", async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = {
              id: formData.get("id"),
              id_formateurs: formData.get("id_formateurs"),
            };

            try {
              const response = await fetch("../../controller/Formateur-Session/addFormateurSessionController.php", {
                method: "POST",
                headers: {
                  "Content-Type": "application/json",
                },
                body: JSON.stringify(data),
              });
             
            

              const result = await response.json();
              if (result.error) {
                showToast(false, result.error, "Erreur lors l'ajout du formateur a la session");
              } else if (result.valid) {
                showToast(true, result.valid, "Ajout réussie");

                setTimeout(() => {
                  window.location.href = "./list-formation-session.php";
                }, 2000);
              }
            } catch (error) {
              console.error("Erreur réseau : ", error);
            }
          });
    }

    // Fonction filtre pour la barre de recherche
    const searchInput = document.getElementById('searchInput');
    const noResultsMessage = document.createElement('p');
    noResultsMessage.id = 'noResultsMessage';
    noResultsMessage.textContent = 'Aucun résultat trouvé';
    noResultsMessage.style.display = 'none';
    document.querySelector('.content-container').appendChild(noResultsMessage);
    const formationTable = document.getElementById('formationTable');

    searchInput.addEventListener('input', function () {
      const filter = searchInput.value.toLowerCase();
      const rows = formationTable.querySelectorAll('tbody tr');
      let visibleRowCount = 0;

      rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        let found = false;

        cells.forEach(cell => {
          if (cell.textContent.toLowerCase().includes(filter)) {
            found = true;
          }
        });

        if (found) {
          row.style.display = '';
          visibleRowCount++;
        } else {
          row.style.display = 'none';
        }
      });

      if (visibleRowCount === 0) {
        formationTable.style.display = 'none';
        noResultsMessage.style.display = 'block';
      } else {
        formationTable.style.display = 'table';
        noResultsMessage.style.display = 'none';
      }
    });

    
      </script>

      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    </main>
    <footer>
      <?php
      include __DIR__ . '/../elements/footer.php';
      ?>
    </footer>
  </div>
</body>