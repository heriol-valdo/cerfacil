<?php



use Projet\Model\App;
use Projet\Model\Paginator;
use Projet\Model\StringHelper;
use Projet\Model\FileHelper;
use Projet\Model\Session;


$url = substr(explode('?', $_SERVER["REQUEST_URI"])[0], 1);

$paginator = new Paginator($pageCourante, $nbrePages, [],"formations",$search,'search');
App::setTitle("Les FORMATIONS");
App::setNavigation("Les FORMATIONS");
App::setBreadcumb('<li class="active">FORMATIONS</li>');
App::addScript('assets/js/formation.js',true);
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-dark" style="border-radius: 10px;border: 5px solid #fff;">
            <div class="panel-heading">
                <h5 class="panel-title">
                     FORMATIONS <small>(<?= thousand($nbre); ?>)</small>
                </h5>
                <div class="panel-control">
                    <a href="javascript:void(0);" data-toggle="tooltip" id="add" data-original-title="Nouvelle formation">
                        <i class="icon-plus text-success fa-2x"></i>
                    </a>
                  

                    <!-- <a href="javascript:void(0);" data-toggle="tooltip" class="panel-collapse" data-original-title="Reduire/Agrandir">
                        <i class="icon-arrow-down fa-2x"></i>
                    </a> -->
                    <a href="<?= App::url('formations') ?>" data-toggle="tooltip" class="panel-reload" data-original-title="Rafraichir">
                        <i class="icon-reload fa-2x"></i>
                    </a>
                     </div>
            </div>
            <div class="panel-body">
                <div class="row m-t-sm">
                    <div class="col-md-12">
                        <form action="<?= App::url('formations') ?>" method="POST" autocomplete="off">
                            <div class="row">
                                <div class="col-md-11" >
                                    <div class="form-group">
                                        <input type="text" name="search" style="border-radius: 10px;border: 1px solid #ccc;" <?= !empty($search)?'value="'.$search.'"':''; ?> placeholder="Chercher par  nom centre   " class="form-control btn-rounded" title="Chercher par le nom ">
                                    </div>     
                                </div>
                                <div class="form-group">
                                    <div class="col-md-1">
                                      <button class="btn btn-block btn-default btn-rounded" style="max-width: 120px;" type="submit">Chercher</button>
                                    </div>
                                </div>     
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row m-t-sm" style="min-height: 470px;">
                    <div class="col-md-12">
                        <div class="table-responsive project-stats">
                            <table class="table table-striped">
                                <thead class="noBackground">
                                <tr>
                                    <th class="">Logo</th>
                                    <th class="">Nom</th>
                                    <th class=""> Diplôme ou titre visé </th> 
                                    <th class=""> Intitulé précis  </th> 
                                    <th class="text-left"> Session  </th> 
                                    <th class=""> Code du diplôme  </th> 
                                    <th class=""> Code RNCP </th> 
                                    <th class="text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody id="table-Villes">
                                <?php
                                if (!empty($items)){
                                    foreach ($items as $item) {
                                        $logo = !empty($item->logo) ? $item->logo : FileHelper::url('assets/img/defaultlogo.jpg');
                                        ?>
                                        <tr>
                                            <td class=""> <img class="img-circle " src="<?= $logo ?>" width="60" height="60" alt=""></td>
                                            <td class=""><?= StringHelper::isEmpty($item->nomF); ?></td>
                                            <td class=""><?= StringHelper::isEmpty($item->diplomeF); ?></td>
                                            <td class=""><?= StringHelper::isEmpty($item->intituleF); ?> </td>
                                            <td class="text-left"><?= StringHelper::dateFormation(date("d/m/Y", strtotime($item->debutO)),date("d/m/Y", strtotime($item->prevuO))) ?></td>
                                            <td class=""><?= StringHelper::isEmpty($item->codeF); ?></td>
                                            <td class=""><?= StringHelper::isEmpty($item->rnF); ?></td>
                                            <td class="text-center">
                                            <a href="javascript:void(0);" class="edit text-success" title="Modifier"
                                                    data-id="<?= $item->id; ?>"


                                                    data-nomF="<?= $item->nomF; ?>"
                                                    data-diplomeF="<?= $item->diplomeF; ?>"
                                                    data-intituleF="<?= $item->intituleF; ?>"
                                                    data-numeroF="<?= $item->numeroF; ?>"
                                                    data-siretF="<?= $item->siretF; ?>"
                                                    data-codeF="<?= $item->codeF; ?>"
                                                    data-rnF="<?= $item->rnF; ?>"
                                                    data-entrepriseF="<?= $item->entrepriseF; ?>"
                                                    data-responsableF="<?= $item->responsableF; ?>"
                                                    data-prix="<?= $item->prix; ?>"
                                                    data-rueF="<?= $item->rueF; ?>"
                                                    data-voieF="<?= $item->voieF; ?>"
                                                    data-complementF="<?= $item->complementF; ?>"
                                                    data-postalF="<?= $item->postalF; ?>"
                                                    data-communeF="<?= $item->communeF; ?>"

                                                    data-emailF="<?= $item->emailF; ?>"
                                                    data-debutO="<?= $item->debutO; ?>"
                                                    data-prevuO="<?= $item->prevuO; ?>"
                                                    data-dureO="<?= $item->dureO; ?>"
                                                    data-nomO="<?= $item->nomO; ?>"
                                                    data-numeroO="<?= $item->numeroO; ?>"
                                                    data-siretO="<?= $item->siretO; ?>"
                                                    data-rueO="<?= $item->rueO; ?>"
                                                    data-voieO="<?= $item->voieO; ?>"
                                                    data-complementO="<?= $item->complementO; ?>"
                                                    data-postalO="<?= $item->postalO; ?>"
                                                    data-communeO="<?= $item->communeO; ?>"
                                                    >
                                                        <i class="fa fa-edit fa-2x"></i>
                                                    </a>
                                                    &nbsp
                                                <a href="javascript:void(0);" class="trash text-danger" title="Supprimer"
                                                   data-url="<?= App::url('formations/delete'); ?>"
                                                   data-id="<?= $item->id; ?>"><i class="fa fa-trash fa-2x"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } } else{ ?>
                                    <tr>
                                        <td colspan="9" class="text-danger text-center">Liste des formations vide</td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                                <?php
                                if(!empty($items)){ ?>
                                    <tfoot>
                                    <tr>
                                        <td colspan="9">
                                         
                                              
                                                    <?php  $paginator->paginateTwo(); ?>
                                              
                                           
                                        </td>
                                    </tr>
                                    </tfoot>
                                <?php } ?>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="intro">Enregistrer une formation</h2>
            </div>
            <form action="<?= App::url('formations/save') ?>" id="newFrom" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="idElement" name="idElement">
                    <input type="hidden" id="action" name="action">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                    <div class="row">
                        <p class="mainColor text-left">Formation</p>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Dénomination du CFA responsable:<b>*</b></label>
                                <input type="text" id="nomF"  name="nomF" class="form-control" required style="border-radius: 5px;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Diplôme ou titre visé par l’apprenti : <b>*</b></label>
                                <select id="diplomeF" name="diplomeF" class="form-control" required style="border-radius: 5px;">
                                    <option value="">______</option>
                                    <optgroup label="Diplôme ou titre de niveau bac +5 et plus">
                                    <option value="80">80 : Doctorat</option>
                                    <option value="71">71 : Master professionnel/DESS</option>
                                    <option value="72">72 : Master recherche/DEA</option>
                                    <option value="73">73 : Master indifférencié</option>
                                    <option value="74">74 : Diplôme d'ingénieur, diplôme d'école de commerce</option>
                                    <option value="79">79 : Autre diplôme ou titre de niveau bac+5 ou plus</option>
                                </optgroup>
                                <optgroup label="Diplôme ou titre de niveau bac +3 et 4">
                                    <option value="61">61 : 1ère année de Master</option>
                                    <option value="62">62 : Licence professionnelle</option>
                                    <option value="63">63 : Licence générale</option>
                                    <option value="69">69 : Autre diplôme ou titre de niveau bac +3 ou 4</option>
                                </optgroup>
                                <optgroup label="Diplôme ou titre de niveau bac +2">
                                    <option value="54">54 : Brevet de Technicien Supérieur</option>
                                    <option value="55">55 : Diplôme Universitaire de technologie</option>
                                    <option value="58">58 : Autre diplôme ou titre de niveau bac+2</option>
                                </optgroup>
                                <optgroup label="Diplôme ou titre de niveau bac">
                                    <option value="41">41 : Baccalauréat professionnel</option>
                                    <option value="42">42 : Baccalauréat général</option>
                                    <option value="43">43 : Baccalauréat technologique</option>
                                    <option value="49">49 : Autre diplôme ou titre de niveau bac</option>
                                </optgroup>
                                <optgroup label="Diplôme ou titre de niveau CAP/BEP">
                                    <option value="33">33 : CAP</option>
                                    <option value="34">34 : BEP</option>
                                    <option value="35">35 : Mention complémentaire</option>
                                    <option value="38">38 : Autre diplôme ou titre de niveau CAP/BEP</option>
                                </optgroup>
                                <optgroup label="Aucun diplôme ni titre">
                                    <option value="25">25 : Diplôme national du Brevet (DNB)</option>
                                    <option value="26">26 : Certificat de formation générale</option>
                                    <option value="13">13 : Aucun diplôme ni titre professionnel</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                                <label class="control-label"> Intitulé précis : <b>*</b></label>
                                <input type="text" id="intituleF"  name="intituleF" class="form-control" required style="border-radius: 5px;">
                            </div>
                        </div>
                      
                       
                    </div>

                    <div class="row">
                    <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">N° UAI du CFA :  <b>*</b></label>
                                <input type="text" id="numeroF" name="numeroF" class="form-control" required style="border-radius: 5px;">
                                <small class="form-text text-muted">Le numero UAI  doit contenir exactement 8 caractères</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"> N° SIRET CFA :  <b>*</b></label>
                                <input type="number" id="siretF" name="siretF" class="form-control" required style="border-radius: 5px;">
                                <small class="form-text text-muted">Le numéro SIRET doit contenir exactement 14 chiffres</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Code du diplôme :   <b>*</b></label>
                                <input type="text" id="codeF" name="codeF" class="form-control" style="border-radius: 5px;">
                                <small class="form-text text-muted">Code du diplôme ne doit pas dépasser 8 caractères</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Code RNCP :     <b>*</b></label>
                                <input type="text" id="rnF"  name="rnF" class="form-control" required style="border-radius: 5px;">
                                <small class="form-text text-muted">Code RNCP ne doit pas dépasser 9 caractères</small>
                            </div>
                        </div>
                        
                    </div>


                    <div class="row">
                    <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Prix formation   <b>*</b></label>
                                <input type="number" id="prix" name="prix" class="form-control" required style="border-radius: 5px;">
                               
                               
                           
                            </div>
                    </div> 

                    <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">CFA d’entreprise :   <b>*</b></label>
                                <select  id="entrepriseF" name="entrepriseF" class="form-control" required style="border-radius: 5px;">
                                <option value="">............</option>
                                <option value="oui">Oui</option>
                                <option value="non">Non</option>
                               
                            </select>
                            </div>
                    </div>   
                    <div class="col-md-7">
                        <div class="form-group">
                                <label class="control-label">Si le CFA responsable est le lieu de formation principal cochez la case ci-contre  <b>*</b> </label>
                                <select id="responsableF"  name="responsableF" class="form-control" required style="border-radius: 5px;">
                                <option value="">............</option>
                                <option value="oui">Oui</option>
                                <option value="non">non</option>
                              
                               
                            </select>
                            </div>
                        </div>
                    
                    </div>


                    <div class="row">
                    
                        <p style="margin-left:15px; "   class=" text-left">Adresse du CFA responsable :    <b>*</b></p>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" id="rueF" name="rueF" class="form-control" placeholder="N°  *" required style="border-radius: 5px;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                 <input type="text" id="voieF"  name="voieF" class="form-control"  placeholder="Voie *"  required style="border-radius: 5px;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                               <input type="text" id="complementF"  name="complementF" class="form-control" placeholder="Complement" style="border-radius: 5px;">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                      
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="number" id="postalF" name="postalF" class="form-control" placeholder="Code Postal" required style="border-radius: 5px;">
                                <small class="form-text text-muted">Veuillez entrer exactement 5 chiffres.</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                 <input type="text" id="communeF"  name="communeF" class="form-control"  placeholder="Commune *"  required style="border-radius: 5px;">
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                 <input type="text" id="emailF"  name="emailF" class="form-control"  placeholder="Email *"  required style="border-radius: 5px;">
                            </div>
                        </div>
                       
                    </div>

                    <!-- break one  -->

                    <div class="row">
                        <p style="margin-left:15px; "   class=" text-left">Organisation de la formation en CFA :    </p>
                        <div class="col-md-4">
                            <div class="form-group">
                            <label class="control-label"> Date de début de formation en CFA :  <b>*</b></label>
                                <input type="date" id="debutO" name="debutO" class="form-control" placeholder="Date de début de formation en CFA :  " required style="border-radius: 5px;">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                            <label class="control-label"> Date prévue de fin des épreuves ou examens: <b>*</b></label>
                                 <input type="date" id="prevuO"  name="prevuO" class="form-control"  placeholder="Date prévue de fin des épreuves ou examens :"  required style="border-radius: 5px;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label class="control-label" style="font-size:11px; " >Durée de la formation : En heures   <b>*</b></label>
                               <input type="number" id="dureO" name="dureO" class="form-control" required style="border-radius: 5px;">
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <p style="margin-left:15px; "   class=" text-left">Lieu principal de réalisation de la formation si différent du CFA responsable :    </p>
                        <div class="col-md-4">
                            <div class="form-group">
                            <label class="control-label">Dénomination du lieu de formation principal :     </label>
                                <input type="text" id="nomO"  name="nomO"class="form-control" placeholder="  " style="border-radius: 5px;">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                            <label class="control-label">N° UAI :  </label>
                               <input type="text" id="numeroO" name="numeroO" class="form-control" placeholder="" style="border-radius: 5px;">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                            <label class="control-label">N° SIRET :    </label>
                               <input type="text" id="siretO" name="siretO" class="form-control" placeholder="" style="border-radius: 5px;">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <p style="margin-left:15px; "   class=" text-left">Adresse du lieu de formation principal :   </p>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" id="rueO" name="rueO" class="form-control" placeholder="N° " style="border-radius: 5px;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                 <input type="text" id="voieO" name="voieO" class="form-control"  placeholder="Voie "  style="border-radius: 5px;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                               <input type="text" id="complementO"  name="complementO" class="form-control" placeholder="Complement" style="border-radius: 5px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                               <input type="number" id="postalO"  name="postalO" class="form-control" placeholder="Code postal " style="border-radius: 5px;">
                               <small class="form-text text-muted">Veuillez entrer exactement 5 chiffres.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                               <input type="text" id="communeO" name="communeO" class="form-control" placeholder="Commune " style="border-radius: 5px;">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="photoImage">Logo (jpg, jpeg, png, JPG, JPEG, PNG)</label>
                            <input type="file" class="form-control" id="photoImage" accept="image/*" name="image">
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" id="confirm" class="newBtn btn btn-default" style="border-radius: 5px;">AJOUTER</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>


