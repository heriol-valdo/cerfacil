/* Variables à initialiser en dehors : 

    <script> // Variables pour updateForm.js
        const updateForm = document.getElementById("editForm");
        const updateFormController = "updateCentreFormation";
        const updateFormSuccessHeader = "";
        // Tous les champs du formulaire et l'id associé
        // commencer par 'data-xxx' et finir par id des champs '#edit-xxx'
        const dataMap = {
            'data-id': '#edit-centreId',
            'data-nomCentre': '#edit-nomCentre',
            'data-adresseCentre': '#edit-adresseCentre',
            'data-codePostalCentre': '#edit-codePostalCentre',
            'data-villeCentre': '#edit-villeCentre',
            'data-telephoneCentre': '#edit-telephoneCentre',
            'data-idEntreprise': '#edit-entrepriseId'
        };
    </script>
    
*/
if (updateForm) {
    updateForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        var formdata = (window.FormData) ? new FormData(this) : null;
        var formData = (formdata !== null) ? formdata : formdata.serialize();
        console.log(formData);
        // Données ok

        try {
            const response = await fetch(updateFormController, {
                method: "POST",
                body: formData,
            });

            const result = await response.json();
            if (result.valid) {
                showToast(true, result.valid, "Mise à jour réussie !");
                setTimeout(() => {
                    window.location.href = updateFormSuccessHeader;
                }, 1000);
            } else {
                showToast(false, result.erreur, "Erreur lors de la mise à jour");
            }
        } catch (error) {
            showToast(false, error, "Erreur au try updateForm.js");
        }
    });
}


document.addEventListener('DOMContentLoaded', function () {
    var editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Button that triggered the modal
        
        // Pré remplit la modale avec les données de dataMap
        for (var dataAttr in dataMap) {
            var formField = updateForm.querySelector(dataMap[dataAttr]);
            if (formField) {
                formField.value = button.getAttribute(dataAttr);
            }
        }
    });
});