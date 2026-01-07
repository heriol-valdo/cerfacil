<!-- Modale pour modifier un étudiant -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Details Achat :  <span  id="stripe_id" ></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editForm">
          
          <div class="row" style="margin-top: 35px;" id="rowabonement">
              <div class="col-md-6">
                  <div class="form-group">
                     
                      <table class="table">
                        <thead>
                          <tr>
                            <td>Date Debut</td> 
                            <td>Date Fin</td> 
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td> <input  id="date_debut" class="form-control" disabled style="border-radius: 5px;"></td> 
                            <td ><input  id="date_fin" class="form-control" disabled style="border-radius: 5px;"></td>
                          </tr>
                          
                        </tbody>
                      </table>
                  </div>
              </div>
              

              <div class="col-md-3" >
                  <div class="form-group">
                      <label class="control-label" style="margin-top: 10px;">Prix unitaire </label>
                      <input  id="prix_abonement" class="form-control" disabled style="border-radius: 5px;margin-top: 15px;">
                  </div>
              </div>

              <div class="col-md-3">
                  <div class="form-group">
                      <label class="control-label">. </label>
                      <input id="totalAbonement"  class="form-control"  disabled style="border-radius: 5px;margin-top: 25px;">
                  </div>
              </div>

          </div>

          <div class="row" id="rowquantite" style="margin-top: 35px;">
              <div class="col-md-6">
                  <div class="form-group">
                      <label class="control-label">Quantite de dossier</label>
                      <input type="number" id="quantite" min="1"  name="quantite" class="form-control" required disabled style="border-radius: 5px;">
                  </div>
              </div>

              
              
              <div class="col-md-3">
                  <div class="form-group">
                      <label class="control-label">Prix unitaire </label>
                      <input id="prix_dossier"  class="form-control" disabled style="border-radius: 5px;">
                  </div>
              </div>

              <div class="col-md-3">
                  <div class="form-group">
                      <label class="control-label">. </label>
                      <input id="totalDossier" value="0" class="form-control" disabled style="border-radius: 5px;">
                  </div>
              </div>
            
          </div>

          <div class="row" style="margin-top: 35px;">
                <div class="col-md-9">
                  
              </div>
              
              <div class="col-md-3" >
                  <div class="form-group">
                      <label class="control-label">TVA( 20%) </label>
                      <input id="tva" class="form-control"  disabled style="border-radius: 5px;">
                  </div>
              </div>
              
              
            
          </div>

          <div class="row" style="margin-top: 35px;">
                <div class="col-md-9">
                  
              </div>
              
              <div class="col-md-3" >
                  <div class="form-group">
                      <label class="control-label">Total Facture </label>
                      <input id="totalFacture" class="form-control"  disabled style="border-radius: 5px;">
                  </div>
              </div>
              
              
            
          </div>
                

            
          <div class="modal-footer" style="margin-top: 35px;">
            <button type="button"  data-bs-dismiss="modal"  class="btn btn-secondary">Fermer</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var editModal = document.getElementById('editModal');
  var rowquantite = document.getElementById('rowquantite');
  var rowabonement = document.getElementById('rowabonement');
  editModal.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget;
    var totalFacture = button.getAttribute('data-totalFacture');

    var quantite = button.getAttribute('data-quantite');
    var prixDossier = button.getAttribute('data-PrixDossier');
    var totalDossier = button.getAttribute('data-totalDossier');

    var type = button.getAttribute('data-type');

    var totalAbonement = button.getAttribute('data-totalAbonement');
    var PrixAbonement = button.getAttribute('data-PrixAbonement');
    var DateDebut = button.getAttribute('data-DateDebut');
    var DateFin = button.getAttribute('data-DateFin');
    var StripeID = button.getAttribute('data-StripeID');

    

   
    let dateObjDebut = new Date(DateDebut);
    let dateObjFin = new Date(DateFin);
    // Définir les options pour le format "jour mois année"
    let options = { 
        day: 'numeric', 
        month: 'numeric', 
        year: 'numeric' 
    };

    let formattedDateDebut = dateObjDebut.toLocaleDateString('fr-FR', options);
    let formattedDateFin = dateObjFin.toLocaleDateString('fr-FR', options);

    // Remplir les champs du formulaire avec les données du bouton
    var editForm = document.getElementById('editForm');
    var stripe_id = document.getElementById('stripe_id').textContent = StripeID;
    editForm.querySelector('#totalFacture').value = totalFacture;

    editForm.querySelector('#quantite').value = quantite;
    editForm.querySelector('#prix_dossier').value = prixDossier;
    editForm.querySelector('#totalDossier').value = totalDossier;

    editForm.querySelector('#totalAbonement').value = totalAbonement;
    editForm.querySelector('#prix_abonement').value = PrixAbonement;

    editForm.querySelector('#date_debut').value = formattedDateDebut;
    editForm.querySelector('#date_fin').value = formattedDateFin;
    

    let tva = totalFacture * (20 / 120);

    editForm.querySelector('#tva').value = tva;


    
    if(quantite == 0){
      rowquantite.style.display = "none";
    }else{
      rowquantite.style.display = "flex";
    }

    if(type == 3 || type == 4){
      rowabonement.style.display = "none";
    }else{
      console.log(totalAbonement);
      if(totalAbonement==""){
        rowabonement.style.display = "none";
      }else{
        rowabonement.style.display = "flex";
      }
     
    }

    
   

  });

  
 
});
</script>
