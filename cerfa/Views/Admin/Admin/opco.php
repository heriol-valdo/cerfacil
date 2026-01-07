<?php







use Projet\Model\App;

use Projet\Model\Paginator;

use Projet\Model\StringHelper;





$url = substr(explode('?',$_SERVER["REQUEST_URI"])[0],1);



$paginator = new Paginator($pageCourante, $nbrePages, [],"opco",$search,'searchopco');

App::setTitle("Les OPCO");

App::setNavigation("Les OPCO");

App::setBreadcumb('<li class="active">OPCO</li>');

App::addScript('assets/js/opco.js',true);



?>

<!DOCTYPE html>

<head>

<script async src="https://www.googletagmanager.com/gtag/js?id=G-P9BT7RE1SD"></script>

    <script>

    window.dataLayer = window.dataLayer || [];

    function gtag(){dataLayer.push(arguments);}

    gtag('js', new Date());



    gtag('config', 'G-P9BT7RE1SD');

    </script>

</head>

<body>



<div class="row">

    <div class="col-md-12">

        <div class="panel panel-dark" style="border-radius: 10px;border: 5px solid #fff;">

            <div class="panel-heading">

                <h5 class="panel-title">

                     OPCO <small>(<?= thousand($nbre); ?>)</small>

                </h5>

                <div class="panel-control">

                    <a href="javascript:void(0);" data-toggle="tooltip" id="add" data-original-title="Nouveau opco">

                        <i class="icon-plus text-success fa-2x"></i>

                    </a>

                  



                    <a href="javascript:void(0);" data-toggle="tooltip" class="panel-collapse" data-original-title="Reduire/Agrandir">

                        <i class="icon-arrow-down fa-2x"></i>

                    </a>

                    <a href="<?= App::url('opco') ?>" data-toggle="tooltip" class="panel-reload" data-original-title="Rafraichir">

                        <i class="icon-reload fa-2x"></i>

                    </a>

                     </div>

            </div>

            <div class="panel-body">

                <div class="row m-t-sm">

                    <div class="col-md-12">

                        <form action="<?= App::url('opco') ?>"  method="POST" autocomplete="off">

                            <div class="row">

                                <div class="col-md-11" >

                                    <div class="form-group">

                                    <input type="text" name="searchopco" style="border-radius: 10px;border: 1px solid #ccc;" <?= !empty($search)?'value="'.$search.'"':''; ?> placeholder="Chercher par  nom    " class="form-control btn-rounded" title="Chercher par le nom ">

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

                                    <th class="">Nom</th>

                                    <th class=""> cle </th>   

                                    <th class="text-center">Actions</th>

                                </tr>

                                </thead>

                                <tbody id="table-Villes">

                                <?php

                                if (!empty($items)){

                                    foreach ($items as $item) {

                                        ?>

                                        <tr>

                                            <td class=""><?= StringHelper::isEmpty($item->nom); ?></td>

                                            <td class=""><?= StringHelper::isEmpty(substr($item->cle, 0, 70) . (strlen($item->cle) > 70 ? '...' : '')); ?></td>

                                            <td class="text-center">

                                            <a href="javascript:void(0);" class="edit text-success"  title="Modifier"

                                                    data-id="<?= $item->id; ?>"





                                                    data-nom="<?= $item->nom; ?>"

                                                    data-cle="<?= $item->cle; ?>"



                                                    data-lienE="<?= $item->lienE; ?>"

                                                    data-lienCe="<?= $item->lienCe; ?>"

                                                    data-lienCo="<?= $item->lienCo; ?>"

                                                    data-lienF="<?= $item->lienF; ?>"

                                                    data-lienT="<?= $item->lienT; ?>"

                                                    data-clid="<?= $item->clid; ?>"

                                                    data-clse="<?= $item->clse; ?>"

                                                   

                                                    >

                                                        <i class="fa fa-edit fa-2x"></i>

                                                    </a>

                                                    &nbsp

                                                <a href="javascript:void(0);" class="trash text-danger" title="Supprimer"

                                                   data-url="<?= App::url('opco/delete'); ?>"

                                                   data-id="<?= $item->id; ?>"><i class="fa fa-trash fa-2x"></i>

                                                </a>

                                            </td>

                                        </tr>

                                    <?php } } else{ ?>

                                    <tr>

                                        <td colspan="9" class="text-danger text-center">Liste des opco vide</td>

                                    </tr>

                                <?php } ?>

                                </tbody>

                                <?php

                                if(!empty($items)){ ?>

                                    <tfoot>

                                    <tr>

                                        <td colspan="9">

                                         

                                              

                                                    <?php $paginator->paginateTwo(); ?>

                                              

                                           

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

        <div class="modal-content" style="border-radius: 10px;border: 5px  #fff;">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <h2 class="modal-title" id="intro">Enregistrer une opco</h2>

            </div>

            <form action="<?= App::url('opco/save') ?>" id="newFrom" method="post">

                <div class="modal-body">

                    <input type="hidden" id="idElement">

                    <input type="hidden" id="action">

                    <p class="mainColor text-right">* Champs obligatoires</p>

                   

                       



                    <div class="row">

                        <div class="col-md-4">

                            <div class="form-group">

                                <label class="control-label">Nom Opco: <b>*</b></label>

                                <select id="nom"  name="nom" class="form-control" required style="border-radius: 5px;">

                                    <option value="">___________</option>

                                    <option value="Akto">Akto</option>

                                    <option value="Atlas">Atlas</option>

                                    <option value="AFDAS">AFDAS</option>

                                    <option value="EP">EP</option>

                                    <option value="MOBILITES">MOBILITES</option>

                                </select>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="form-group">

                                <label class="control-label">Cle Api: <b>*</b> </label>

                                <input type="text" id="cle"   name="cle" class="form-control" required style="border-radius: 5px;">

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="form-group">

                                <label class="control-label">Lien Etat : <b>*</b></label>

                                <input type="text" id="lienE"  name="lienE" class="form-control" required style="border-radius: 5px;">

                            </div>

                        </div>

                       

                      

                    </div>



                    <div class="row">

                        <div class="col-md-4">

                            <div class="form-group">

                                <label class="control-label">Lien Cerfa : <b>*</b></label>

                                <input type="text" id="lienCe"  name="lienCe" class="form-control" required style="border-radius: 5px;">

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="form-group">

                                <label class="control-label">Lien Convention : <b>*</b> </label>

                                <input type="text" id="lienCo"   name="lienCo" class="form-control" required style="border-radius: 5px;">

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="form-group">

                                <label class="control-label">Lien Facture : <b>*</b> </label>

                                <input type="text" id="lienF"   name="lienF" class="form-control" required style="border-radius: 5px;">

                            </div>

                        </div>

                       

                      

                    </div>



                    <div class="row">

                        <div class="col-md-5">

                            <div class="form-group">

                                <label class="control-label">Lien Token : <b>*</b></label>

                                <input type="text" id="lienT"  name="lienT" class="form-control" required style="border-radius: 5px;">

                            </div>

                        </div>

                        <div class="col-md-3">

                            <div class="form-group">

                                <label class="control-label">client_id : <b>*</b> </label>

                                <input type="text" id="clid"   name="clid" class="form-control" required style="border-radius: 5px;">

                            </div>

                        </div>





                        <div class="col-md-4">

                            <div class="form-group">

                                <label class="control-label">client_secre : <b>*</b> </label>

                                <input type="text" id="clse"   name="clse" class="form-control" required style="border-radius: 5px;">

                            </div>

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

</body>

</html>



