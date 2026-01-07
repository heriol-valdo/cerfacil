  <?php


use Projet\Database\Test;
use Projet\Database\Vues;
use Projet\Database\Worker;
use Projet\Model\App;
use Projet\Model\DateParser;
use Projet\Model\FileHelper;
use Projet\Model\Paginator;
use Projet\Model\Privilege;
use Projet\Model\StringHelper;
use Projet\Database\Abonnement;

$url = substr(explode('?',$_SERVER["REQUEST_URI"])[0],1);

App::setTitle("Les Produits");
App::setNavigation("Les Produits ");
App::setBreadcumb('<li class="active">Produits</li>');
App::addScript('assets/js/produit.js',true);
?>
<style>
     #card-element {
            background-color: #f2f2f2;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .swal2-container {
           
    z-index: 9999; /* Ajustez ce chiffre selon vos besoins */
}
.custom-popup {
    height: 400px; /* Ajustez la hauteur ici */
    overflow-y: auto; /* Ajoute un défilement si le contenu déborde */
}

.loader {
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top: 4px solid #3498db; /* Couleur de la barre de progression */
    width: 30px; /* Taille du loader */
    height: 30px; /* Taille du loader */
    animation: spin 1s linear infinite;
    margin: 0 auto; /* Centrer le loader */
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
    .plan {
  border-radius: 16px;
  box-shadow: 0 30px 30px -25px rgba(0, 38, 255, 0.205);
  padding: 10px;
  background-color: #fff;
  color: #697e91;
  max-width: 300px;
  margin-bottom: 20px;
  margin-top: 20px;
  min-height: 360px;
}

.plan strong {
  font-weight: 600;
  color: #425275;
}

.plan .inner {
  align-items: center;
  padding: 20px;
  padding-top: 40px;
  background-color:  #ecf0ff;
  border-radius: 12px;
  position: relative;
  min-height: 340px;
}

.plan .pricing {
  position: absolute;
  top: 0;
  right: 0;
  background-color: #153C4A;
  border-radius: 99em 0 0 99em;
  display: flex;
  align-items: center;
  padding: 0.625em 0.75em;
  font-size: 1.25rem;
  font-weight: 600;
  color: #fff;
}

.plan .pricing small {
  color:#153C4A;
  font-size: 0.75em;
  margin-left: 0.25em;
}

.plan .title {
  font-weight: 600;
  font-size: 15px;
  color: #425675;
}

.plan .title + * {
  margin-top: 0.75rem;
}

.plan .info + * {
  margin-top: 1rem;
}


.list{
    display: flex;
    flex-direction: row;
    gap: 0.5rem;
}

.listdiv {
    display: flex;
    flex-direction: row;
    gap: 3.5rem;
}

@media (max-width: 768px) {
    .listdiv {
        flex-direction: column;
        gap: 1.5rem;
    }
}


@media (max-width: 1078px) {
    .listdiv {
        flex-direction: column;
        gap: 1.5rem;
    }
}

 .icon {
  background-color: #1FCAC5;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  border-radius: 50%;
  width: 24px;
  height: 24px;
 
}

.icon svg {
  width: 24px;
  height: 24px;
}



.plan .action {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: 15px;
}

.innercontain{
    min-height: 247px;
}

.plan .button {
  background-color: #153C4A;
  border-radius: 10px;
  color: #fff;
  font-weight: 500;
  font-size: 1.125rem;
  text-align: center;
  border: 0;
  outline: 0;
  width: 60%;
  padding: 0.625em 0.75em;
  text-decoration: none;
}

.plan .button:hover, .plan .button:focus {
  background-color: #153C6A;
}
</style>

<body>
<div class="row">
    <div class="col-md-12 listdiv">
        <?php if(!empty($produits)) { 
               foreach( $produits as $produit){
            ?>
          <div class="plan">
            <div class="inner">
                <div class="innercontain">
                    <span class="pricing">
                        <span>
                        €<?= intval($produit->prix_dossier) + intval($produit->prix_abonement) ?> <small>/ m</small>
                        </span>
                    </span>
                    <p class="title"><strong><?=$produit->nom?></strong></p>
                    <p class="info">Au prix de <?=$produit->prix_dossier?>€ HT/Dossier  <?php if(!empty($produit->prix_abonement)){ echo '+'. $produit->prix_abonement?>€ /mois <?php } ?>, vous avez droit aux options suivantes :</php>

                    <div class="list">
                        <span class="icon">
                            <svg height="24" width="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            </svg>
                        </span>
                        <span><?=$produit->caracteristique1?></span>
                    </div>

                    <div class="list">
                        <span class="icon">
                            <svg height="24" width="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            </svg>
                        </span>
                        <span><?=$produit->caracteristique2?></span>
                    </div>
                

                    <?php if(!empty($produit->caracteristique3)){ ?>
                    <div class="list">
                        <span class="icon">
                            <svg height="24" width="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            </svg>
                        </span>
                        <span><?=$produit->caracteristique3?></span>
                    </div>
                    <?php } ?>

                    <?php if(!empty($produit->caracteristique4)){ ?>
                    <div class="list">
                        <span class="icon">
                            <svg height="24" width="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            </svg>
                        </span>
                        <span><?=$produit->caracteristique4?></span>
                    </div>
                    <?php } ?>

                </div>

                <div class="action">
                    <?php 
                          $tableauAbonnement = Abonnement::find($produit->id);
                          if($tableauAbonnement['valid']){
                              if(empty($tableauAbonnement['data'])){ ?>
                                   <a class="button" href="javascript:void(0);"  data-idProduit="<?= $produit->id ?>"  data-prixAbonement="<?= $produit->prix_abonement ?>"  
                                   data-prixDossier="<?= $produit->prix_dossier ?>" data-toggle="tooltip" id="<?php if($produit->type == 1 || $produit->type == 2){  echo 'add';}else{ echo 'adds';}  ?>" 
                                   data-original-title="Debloquer">
                                      Debloquer
                                    </a>
                             <?php }

                                 else{ ?>

                                  <a class="button" href="javascript:void(0);"  data-idProduit="<?= $produit->id ?>"  data-prixAbonement="<?= $produit->prix_abonement ?>"  data-id="<?= $tableauAbonnement['data']->id ?>" 
                                   data-dateFin="<?= $tableauAbonnement['data']->date_fin ?>"    data-dateDebut="<?= $tableauAbonnement['data']->date_debut ?>"   data-quantite="<?= $tableauAbonnement['data']->quantite ?>" 
                                   data-prixDossier="<?= $produit->prix_dossier ?>" data-toggle="tooltip" id="<?php if($produit->type == 1 || $produit->type == 2){  echo 'edit';}else{ echo 'edits';}  ?>" 
                                   data-original-title="Recharger">
                                      Recharger
                                    </a>
                               <?php  }
                                 
                          }else{
                            ?>
                             <a class="button" href="#">
                                <?= $tableauAbonnement['error'] ?>
                             </a>
                          <?php }
                        ?>

                    
                </div>
            </div>
        </div>
             

            <?php } }else{?>
                <div> <p class="title"><strong>liste des produits vide, veuillez contacter votre Commercial</strong></p> </div>
            <?php  } ?>
        
      
    </div>
</div>

<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="intro">Debloquer ce produit </h2>
            </div>
            <form action="<?= App::url('produits/save') ?>" id="newFrom" method="post">
                <div class="modal-body">
                    <input type="hidden" id="idElement">
                    <input type="hidden" id="idProduit">
                    <input type="hidden" id="action">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                   
                       

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Type Abonnement <b>*</b></label>
                                <select id="selectAbonemet"  class="form-control"  required style="border-radius: 5px;">
                                    <option value="1">Mensuel</option>
                                    <option value="2">Annuel</option>
                                </select>
                            </div>
                        </div>
                       

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Prix unitaire </label>
                                <input  id="prix_abonement" class="form-control" disabled style="border-radius: 5px;">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">. </label>
                                <input id="totalAbonement"  class="form-control"  disabled style="border-radius: 5px;">
                            </div>
                        </div>

                       
                       
                      
                    </div>

                    


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Quantite de dossier( vous pourrez le recharger si il arrive a expiration)<b>*</b></label>
                                <input type="number" id="quantite" min="1"  name="quantite" class="form-control" required style="border-radius: 5px;">
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

                    <div class="row">
                         <div class="col-md-9">
                            
                        </div>
                       

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">TVA(20%) </label>
                                <input id="tva" class="form-control"  disabled style="border-radius: 5px;">
                            </div>
                        </div>
                       
                       
                      
                    </div>

                    <div class="row">
                         <div class="col-md-9">
                            
                        </div>
                       

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Total Facture </label>
                                <input id="totalFacture" class="form-control"  disabled style="border-radius: 5px;">
                            </div>
                        </div>
                       
                       
                      
                    </div>
                    


                </div>
                <div class="modal-footer">
                    <button type="submit" id="confirm" class="newBtn btn btn-default">AJOUTER</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="news" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="intros">Debloquer ce produit </h2>
            </div>
            <form action="<?= App::url('produits/saves') ?>" id="newFromFacture" method="post">
                <div class="modal-body">
                    <input type="hidden" id="idElements">
                    <input type="hidden" id="idProduits">
                    <input type="hidden" id="action">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                   
                       

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Quantite de dossier( vous pourrez le recharger si il arrive a expiration)<b>*</b></label>
                                <input type="number" id="quantites" min="1"  name="quantites" class="form-control" required style="border-radius: 5px;">
                            </div>
                        </div>

                       
                       
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Prix unitaire </label>
                                <input id="prix_dossiers"  class="form-control" disabled style="border-radius: 5px;">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">. </label>
                                <input id="totalDossiers" value="0" class="form-control" disabled style="border-radius: 5px;">
                            </div>
                        </div>
                      
                    </div>

                    <div class="row">
                         <div class="col-md-9">
                            
                        </div>
                       

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">TVA(20%) </label>
                                <input id="tvas" class="form-control"  disabled style="border-radius: 5px;">
                            </div>
                        </div>
                       
                       
                      
                    </div>

                    <div class="row">
                         <div class="col-md-9">
                            
                        </div>
                       

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Total Facture </label>
                                <input id="totalFactures" class="form-control"  disabled style="border-radius: 5px;">
                            </div>
                        </div>
                       
                       
                      
                    </div>
                    


                </div>
                <div class="modal-footer">
                    <button type="submit" id="confirms" class="newBtn btn btn-default" style="border-radius: 5px;">AJOUTER</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="recharge" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"  style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" >Recharger ce produit </h2>
            </div>
            <form action="<?= App::url('produits/save') ?>" id="rechargeFrom" method="post">
                <div class="modal-body">
                    <input type="hidden" id="quantiteuplodeRecharge">
                    <input type="hidden" id="idElementRecharge">
                    <input type="hidden" id="idProduitRecharge">
                    <input type="hidden" id="datedebutRecharge">
                    <input type="hidden" id="datefinRecharge">
                    <input type="hidden" id="actionRecharge">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                   
                    <div class="row" style="display: none;" id="diverror">
                        <div class="col-md-12">
                               <div class="notifications-container" style="width: 100%;">
                                        <div class="error-alert">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="error-svg">
                                                        <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" fill-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div class="error-prompt-container">
                                                    <p class="error-prompt-heading">Vous avez un abonnement en cours de ce fait vous ne pouvez pas Le recharger 
                                                        mais vous pouvez recharger la quantite de Dossiers que vous souhaitez Envoyer.</p>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                        </div>
                    </div>


                    <div class="row"  id="divabonnement">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Type Abonnement <b>*</b></label>
                                <select id="selectAbonemetrecharge"  class="form-control"  required style="border-radius: 5px;">
                                    <option value="1">Mensuel</option>
                                    <option value="2">Annuel</option>
                                </select>
                            </div>
                        </div>
                       

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Prix unitaire </label>
                                <input  id="prix_abonementrecharge" class="form-control" disabled style="border-radius: 5px;">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">. </label>
                                <input id="totalAbonementrecharge"  class="form-control"  disabled style="border-radius: 5px;">
                            </div>
                        </div>

                       
                       
                      
                    </div>

                    


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" id="stockRecharge"></label>
                                <input type="number" id="quantiterecharge"   name="quantite" class="form-control" style="border-radius: 5px;">
                            </div>
                        </div>

                       
                       
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Prix unitaire </label>
                                <input id="prix_dossierrecharge"  class="form-control" disabled style="border-radius: 5px;">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">. </label>
                                <input id="totalDossierrecharge" value="0" class="form-control" disabled style="border-radius: 5px;">
                            </div>
                        </div>
                      
                    </div>

                    <div class="row">
                         <div class="col-md-9">
                            
                        </div>
                       

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">TVA(20%) </label>
                                <input id="tvarecharge" class="form-control"  disabled style="border-radius: 5px;">
                            </div>
                        </div>
                       
                       
                      
                    </div>

                    <div class="row">
                         <div class="col-md-9">
                            
                        </div>
                       

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Total Facture </label>
                                <input id="totalFacturerecharge" class="form-control"  disabled style="border-radius: 5px;">
                            </div>
                        </div>
                       
                       
                      
                    </div>
                    


                </div>
                <div class="modal-footer">
                    <button type="submit" id="confirmsDisabledtotal" class="newBtn btn btn-default" style="border-radius: 5px;">Recharger</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="recharges" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" >Recharger ce produit</h2>
            </div>
            <form action="<?= App::url('produits/saves') ?>" id="rechargeFromFacture" method="post">
                <div class="modal-body">
                    <input type="hidden" id="idElementsrecharge">
                    <input type="hidden" id="idProduitsrecharge">
                    <input type="hidden" id="actionactioneecharges">
                    <input type="hidden" id="quantiteuploderechargefacture">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                   
                       

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" id="stockRechargesFacture"></label>
                                <input type="number" id="quantitesrecharge" min="1"  name="quantites" class="form-control" required style="border-radius: 5px;">
                            </div>
                        </div>

                       
                       
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Prix unitaire </label>
                                <input id="prix_dossiersrecharge"  class="form-control" disabled style="border-radius: 5px;">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">. </label>
                                <input id="totalDossiersrecharge" value="0" class="form-control" disabled style="border-radius: 5px;">
                            </div>
                        </div>
                      
                    </div>

                    <div class="row">
                         <div class="col-md-9">
                            
                        </div>
                       

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">TVA(20%) </label>
                                <input id="tvasrecharge" class="form-control"  disabled style="border-radius: 5px;">
                            </div>
                        </div>
                       
                       
                      
                    </div>

                    <div class="row">
                         <div class="col-md-9">
                            
                        </div>
                       

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Total Facture </label>
                                <input id="totalFacturesrecharge" class="form-control"  disabled style="border-radius: 5px;">
                            </div>
                        </div>
                       
                       
                      
                    </div>
                    


                </div>
                <div class="modal-footer">
                    <button type="submit"  class="newBtn btn btn-default" style="border-radius: 5px;">Recharger</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>

