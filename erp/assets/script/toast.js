/* Liens à importer au début du fichier : 

  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

*/

/* Lien à importer après contenu : 

    <!-- Script showToast -->
    <script src="../../assets/script/toast.js"></script>

*/
    
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
      duration: 1000,
      backgroundColor: value ? "green" : "red",
      gravity: "top",
      stopOnFocus: true,
      className: "toast-with-progress",
      escapeMarkup: false,
      node: toastContainer,
    });

    toastInstance.showToast();
  }