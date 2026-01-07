/* Notes supplémentaires : 

    - enctype="multipart/form-data" dans le form
    - Api : Requêtes POST seulement pour multipart
    - récupérer form dans un des fichiers ailleurs

*/

/* Variables à copier dans le fichier modalAdd....php

    <script> // Variables pour addForm_multipart.js
        const addForm_multipart = document.getElementById("addAbsenceForm");
        const addForm_multipartController = "addEntreprise";
        const addForm_multipartSuccessHeader = "entreprises";
        const addForm_fileName = "justificatif";
    </script>
    <script src="../../erp/assets/script/addForm_multipart.js"></script>
    
*/


if (addForm_multipart) {
    const addForm_multipartFileName = `input[name="${addForm_fileName}"]`;
    addForm_multipart.addEventListener("submit", async function (e) {
        e.preventDefault();

        var formdata = (window.FormData) ? new FormData(this) : null;
        var formData = (formdata !== null) ? formdata : formdata.serialize();

        var fileInput = document.querySelector(addForm_multipartFileName);
        if (fileInput && fileInput.files[0]) {
            formData.set(addForm_fileName, fileInput.files[0]); 
        }

        try {
            const response = await fetch(addForm_multipartController , {
                method: "POST",
                body: formData,
            });

            const result = await response.json();
            if (result.valid) {
                showToast(true, result.valid, "Mise à jour réussie");
                setTimeout(() => {
                    window.location.href = addForm_multipartSuccessHeader;
                }, 1000);
            } else {
                showToast(false, result.erreur, "Erreur lors de la mise à jour");
            }
        } catch (error) {
            showToast(false, error, "Erreur au try view");
        }
    });
}