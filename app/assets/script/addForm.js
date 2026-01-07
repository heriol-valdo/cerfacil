/* Variables à copier dans le fichier modalAdd....php

    <script> // Variables pour addForm.js
        const addForm = document.getElementById("addAbsenceForm");
        const addFormController = "addCentreFormation";
        const addFormSuccessHeader = "centreFormation";
    </script>
    <script src="../../assets/script/addForm.js"></script>
    
*/


if (addForm) {
    addForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        var formdata = (window.FormData) ? new FormData(this) : null;
        var formData = (formdata !== null) ? formdata : formdata.serialize();

        try {
            const response = await fetch(addFormController , {
                method: "POST",
                body: formData,
            });

            const result = await response.json();
            if (result.valid) {
                showToast(true, result.valid, "Ajout réussi");
                setTimeout(() => {
                    window.location.href = addFormSuccessHeader;
                }, 1000);
            } else {
                showToast(false, result.erreur, "Erreur lors de l'ajout");
            }
        } catch (error) {
            showToast(false, error, "Erreur au try view");
        }
    });
}