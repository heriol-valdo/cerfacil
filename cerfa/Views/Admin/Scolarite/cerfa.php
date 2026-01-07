<?php



use Projet\Database\Entreprise;
use Projet\Database\Formation;
use Projet\Database\Opco;
use Projet\Model\App;
use Projet\Model\Paginator;
use Projet\Model\StringHelper;
use Projet\Model\JWTHandler;



$url = substr(explode('?',$_SERVER["REQUEST_URI"])[0],1);
$paginator = new Paginator($pageCourante, $nbrePages, [],"cerfas",$search,'searchcerfas');
App::setTitle("Les Dossiers");
App::setNavigation("Les Dossiers");
App::setBreadcumb('<li class="active"> Dossiers</li>');
App::addScript('assets/js/cerfa.js',true);
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-dark" style="border-radius: 10px;border: 5px solid #fff;">
            <div class="panel-heading">
                <h5 class="panel-title">
                    Dossiers <small>(<?= thousand($nbre); ?>)</small>
                </h5>
                <div class="panel-control">
                    <a href="javascript:void(0);" data-toggle="tooltip" id="add" data-original-title="Nouveau cerfa">
                        <i class="icon-plus text-success fa-2x"></i>
                    </a>
                    <a target="_blank" href="<?= App::url('cerfas/csv') ?>"
                    data-toggle="tooltip" data-original-title="Generer le fichierCSV des alternants" ><i class="fa fa-file-text-o fa-2x text-success"></i>
                    </a>
                   
                </div>
            </div>
            <div class="panel-body">
                <div class="row m-t-sm">
                    <div class="col-md-12">
                        <form action="<?= App::url('cerfas') ?>" method="POST" autocomplete="off">
                            <div class="row">
                                <div class="col-md-11" >
                                    <div class="form-group">
                                    <input type="text" name="searchcerfas" style="border-radius: 10px;border: 1px solid #ccc;" <?= !empty($search)?'value="'.$search.'"':''; ?> placeholder="Chercher par  nom d'apprenant   " class="form-control btn-rounded" title="Chercher par le nom d'apprenant">
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
                            <table class="table table-striped ">
                                <thead class="noBackground">
                                <tr>
                                    <th class="">Nom entreprise</th>
                                    <th class="">Intitulé précis de la formation  / Nom du Centre de Formation </th>
                                    <th class="text-left">Nom Apprenant </th>
                                    <th class="text-left">Email Apprenant</th>
                                    <th class="text-left">Etat</th>
                                    <th class="text-left">#</th>
                                </tr>
                                </thead>
                                <tbody id="table-Villes">
                               
                               <?php



                                function genererDPoPToken($url, $method, $privateKeyPath) {
                                    // En-tête du JWT pour DPoP
                                    $header = [
                                        "alg" => "RS256", 
                                        "typ" => "dpop+jwt"
                                        
                                    ];

                                    // Création du payload (charge utile) du DPoP token
                                    $payload = [
                                        "jti" => bin2hex(random_bytes(16)), // ID unique du token
                                        "htm" => $method,  // Méthode HTTP (GET, POST, etc.)
                                        "htu" => $url,     // URI cible
                                        "iat" => time()    // Horodatage de la création du token
                                    ];

                                    // Lire la clé privée en format PEM pour signer le JWT
                                    $privateKey = file_get_contents($privateKeyPath);
                                    if (!$privateKey) {
                                        throw new Exception("La clé privée n'a pas pu être lue.");
                                    }

                                // Créer le token en appelant la méthode de la classe JWTHandler
                                    try {
                                        return JWTHandler::createToken($payload, $privateKey, $header);
                                    } catch (\Exception $e) {
                                        throw new \Exception('Erreur lors de la création du token : ' . $e->getMessage());
                                    }
                                }


                                function obtenirTokenCaches($clid, $clse, $lienT, $privateKeyPath) {
                                    static $tokens = []; // Cache pour les tokens par client_id

                                    // Vérifier si un token est déjà en cache pour ce client
                                    if (isset($tokens[$clid])) {
                                        return $tokens[$clid]; // Retourner le token en cache
                                    }

                                    // Générer le DPoP token
                                    $dpopToken = genererDPoPToken($lienT, 'POST', $privateKeyPath);
                                    //var_dump($result); // Debug pour voir la réponse complète

                                

                                    // Initialiser la requête cURL pour obtenir le token d'accès
                                    $ch = curl_init();
                                    $post_data = [
                                        'grant_type' => 'client_credentials',
                                        'client_id' => $clid,
                                        'client_secret' => $clse
                                    
                                    ];

                                    // Configurer cURL avec les bonnes options
                                    curl_setopt($ch, CURLOPT_URL, $lienT); // URL du serveur d'authentification
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Retourner la réponse sous forme de chaîne
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data)); // Paramètres du POST
                                    curl_setopt($ch, CURLOPT_POST, true); // Type de la requête : POST
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                        'Content-Type: application/x-www-form-urlencoded',
                                        "DPoP: $dpopToken" // Ajouter le token DPoP dans l'en-tête
                                    ]);
                                    curl_setopt($ch, CURLOPT_VERBOSE, true);


                                    // Exécution de la requête cURL
                                    $response = curl_exec($ch);
                                    curl_close($ch);

                                    // Vérifier si une erreur cURL s'est produite
                                    if ($response === false) {
                                        throw new Exception("Erreur cURL : " . curl_error($ch));
                                    }

                                    // Décoder la réponse JSON
                                    $result = json_decode($response, true);
                                    //var_dump($result); // Debug pour voir la réponse complète

                                    // Vérifier la présence du token d'accès dans la réponse
                                    $access_token = $result['access_token'] ?? null;

                                    // Si un token est trouvé, le stocker dans le cache
                                    if ($access_token) {
                                        $tokens[$clid] = $access_token;
                                    }

                                    return $access_token; // Retourner le token d'accès
                                }

                                

                               function obtenirTokenCache($clid, $clse, $lienT,$nom) {
                                   static $tokens = []; // Cache pour les tokens par client_id
                               
                                   if (isset($tokens[$clid])) {
                                       return $tokens[$clid];
                                   }
                                   
                                   // Initialiser cURL
                                   $ch = curl_init();
                                   $post_data = [
                                    'grant_type' => 'client_credentials',
                                    'client_id' => $clid,
                                    'client_secret' => $clse,
                                   'scope' => ($nom !== "EP") ? 'api.read api.write' : null,
                                   ];
                                   if (isset($post_data['scope']) && $post_data['scope'] === null) {
                                    unset($post_data['scope']);
                                }
                                   curl_setopt($ch, CURLOPT_URL, $lienT);
                                   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                   curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
                                   curl_setopt($ch, CURLOPT_POST, true);
                               
                                   // Exécuter la requête
                                   $response = curl_exec($ch);
                                   curl_close($ch);
                               
                                   // Extraire le token
                                   $result = json_decode($response, true);
                                   //var_dump($result);
                                   $access_token = $result['access_token'] ?? null;
                                   if ($access_token) {
                                       $tokens[$clid] = $access_token;
                                   }
                                   return $access_token;
                               }
                               
                               function etat($numeroInterne, $token, $lienE, $cle) {
                                   // Initialiser cURL
                                   $ch = curl_init();
                                   $url = $lienE . "?numeroInterne=" . $numeroInterne;
                                   curl_setopt($ch, CURLOPT_URL, $url);
                                   curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                       'Content-Type: application/json',
                                       'accept: application/json',
                                       'EDITEUR: LGX-CREATION',
                                       'LOGICIEL: LGX-CERFA',
                                       'VERSION: 1.0.0',
                                       "Authorization: Bearer $token",
                                       "X-API-KEY: $cle"
                                   ]);
                                   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                   $response = curl_exec($ch);
                                   $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                   curl_close($ch);
                               
                                   $responseJson = json_decode($response, true);
                                   if ($httpCode === 200 && $responseJson['cerfa']["numeroInterne"] == $numeroInterne) {
                                       return $responseJson['cerfa']['etat'];
                                   }
                                   $error = isset($responseJson['errors']) ? $responseJson['errors'] : "Erreur : " . $httpCode;
                                   //var_dump($response) ;
                                   return ($httpCode === 404) ? "NONTROUVERS" : "Erreur : $httpCode";
                               }
                               
                               function getEtatLabel($numeroInterne, $ligneopco) {
                                   if (empty($numeroInterne)) {
                                       return StringHelper::$tabetatcerfa[''];
                                   }
                               
                                   $filePath  = PATH_FILE;
                                   $filePath .=  '/public/' . 'assets/pdf/private-key.pem';

                                   if($ligneopco['data']->nom == "AFDAS"){
                                    $token = obtenirTokenCaches($ligneopco['data']->clid, $ligneopco['data']->clse, $ligneopco['data']->lienT,$filePath);
                                   }else{
                                    $token = obtenirTokenCache($ligneopco['data']->clid, $ligneopco['data']->clse, $ligneopco['data']->lienT,$ligneopco['data']->nom);
                                   }
                                
                                   if (!$token) {
                                       return '<span class="label label-danger">ERREUR: Impossible d\'obtenir le token</span>';
                                   }
                               
                                   $etat = etat($numeroInterne, $token, $ligneopco['data']->lienCe, $ligneopco['data']->cle);
                                   return StringHelper::$tabetatcerfa[$etat] ?? '<span class="label label-warning" style="border-radius: 5px;border: 0px solid #ccc;"">ÉTAT INCONNU: ' . htmlspecialchars($etat) . '</span>';
                               }
                               
                               if (!empty($items)) {
                                   $formationsCache = [];
                                   $employeursCache = [];
                                   ?>
                               
                                   <tbody id="table-Villes">
                                       <?php foreach ($items as $item) {
                                           // Cacher les données de formation
                                           if ($item->idformation != 0 && !isset($formationsCache[$item->idformation])) {
                                               $ligneformation = Formation::find($item->idformation);
                                               $formationsCache[$item->idformation] = $ligneformation['valid'] ? $ligneformation['data'] : null;
                                           }
                                           $data = $formationsCache[$item->idformation] ?? null;
                                           $nomF = $data ? ($data->intituleF ?? '') . ' / ' . ($data->nomF ?? '') : StringHelper::isEmpty('');
                               
                                           // Cacher les données d'employeur
                                           if (!isset($employeursCache[$item->idemployeur])) {
                                               $employeursCache[$item->idemployeur] = Entreprise::find($item->idemployeur);
                                           }
                                           $ligneemployeur = $employeursCache[$item->idemployeur];
                                           $ligneopco = Opco::find($ligneemployeur['data']->idopco);
                               
                                           // Actions HTML
                                           $stat3 = '<li><a href="javascript:void(0);" class="trash text-danger" data-url="' . App::url('cerfas/delete') . '" title="Supprimer le cerfa" data-id="' . $item->id . '">Supprimer le cerfa</a></li>';
                                           $stat01 = '<li><a><form action="' . App::url('cerfasdetails') . '" method="POST" style="display:inline;"><input type="hidden" name="data" value="' . htmlspecialchars($item->id) . '"><button type="submit" style="background:none;border:none;cursor:pointer;font:inherit;" title="Details Dossiers">Details Dossiers</button></form></a></li>';
                                           ?>
                               
                                           <tr>
                                               <td><?= StringHelper::isEmpty($ligneemployeur['data']->nomE); ?></td>
                                               <td><?= $nomF; ?></td>
                                               <td class="text-left"><?= StringHelper::isEmpty($item->nomA); ?></td>
                                               <td class="text-left"><?= StringHelper::isEmpty($item->emailA); ?></td>
                                               <td class="text-left"><?= getEtatLabel($item->numeroInterne, $ligneopco); ?></td>
                                               <td class="text-left">
                                                   <div class="btn-group">
                                                       <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-rounded" data-toggle="dropdown" aria-expanded="false">Actions <span class="caret"></span></button>
                                                       <ul class="dropdown-menu dropdown-menu-right no-scrollbar" role="menu">
                                                           <?= $stat01 . $stat3 ?>
                                                       </ul>
                                                   </div>
                                               </td>
                                           </tr>
                                       <?php } ?>
                                   </tbody>
                               
                               <?php } else { ?>
                                   <tbody id="table-Villes">
                                       <tr>
                                           <td colspan="9" class="text-danger text-center">Liste des cerfas vide</td>
                                       </tr>
                                   </tbody>
                               <?php } ?>
                               
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
<div class="modal fade newModal"   id="new" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content" style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title " id="introplus"></h2>
            </div>
            <form action="<?= App::url('cerfas/savenew') ?>" id="newForm1" method="post">
                <div class="modal-body">
                    <input type="hidden" id="actionE">
                    <input type="hidden" id="idElementE">
                    <p class="mainColor text-right">* Champs obligatoires</p>

                    <div class="row">

                        <div class="col-md-7">
                            <div class="form-group">
                                <label class="control-label">Nom et prénom ou dénomination Employeur:<b>*</b></label>
                                <input type="text" list="employeurs-list" name="employeur_nom" id="employeur_nom" class="form-control" 
                                    autocomplete="off" required style="border-radius: 5px;">
                                <input type="hidden" name="idemployeur" id="idemployeur">
                                <datalist id="employeurs-list">
                                    <?php foreach ($employeurs as $employeur): ?>
                                        <option data-id="<?= $employeur->id ?>" value="<?= htmlspecialchars($employeur->nomE) ?>">
                                    <?php endforeach; ?>
                                </datalist>
                                <small class="text-muted">Commencez à taper pour rechercher ou <a href="employeurs">ajouter un employeur</a></small>
                            </div>
                        </div>


                        <div class="col-md-5 form-group">
                            <label for="email">Email de l'apprenant <b>*</b></label>
                            <input type="email" class="form-control" id="emailAA" name="emailAA" placeholder="email" required style="border-radius: 5px;">
                        </div>

                        
                    </div>

                    <div class="row">
                       <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Intitulé précis de la formation / Nom du Centre de Formation:<b>*</b></label>
                                <input type="text" list="formations-list" name="formation_nom" id="formation_nom" class="form-control" 
                                    autocomplete="off" required style="border-radius: 5px;">
                                <input type="hidden" name="idformation" id="idformation">
                                <datalist id="formations-list">
                                    <?php foreach ($formations as $formation): 
                                        $nom = (empty($formation->intituleF)) 
                                            ? StringHelper::isEmpty('').' / '.$formation->nomF 
                                            : $formation->intituleF.' / '.$formation->nomF.' / '.StringHelper::dateFormation(
                                                date("d/m/Y", strtotime($formation->debutO)),
                                                date("d/m/Y", strtotime($formation->prevuO))
                                            );
                                    ?>
                                        <option data-id="<?= $formation->id ?>" value="<?= htmlspecialchars($nom) ?>">
                                    <?php endforeach; ?>
                                </datalist>
                                <small class="text-muted">Commencez à taper pour rechercher une formation</small>
                            </div>
                        </div>
                    </div>
                    
                   
                </div>
                <div class="modal-footer">
                    <button type="submit" id="confirmplus" class="newBtn btn btn-default" style="border-radius: 5px;">Ajouter</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="host">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="intro">Enregistrer un cerfa</h2>
            </div>
            <form action="<?= App::url('cerfas/save') ?>" id="newFrom" method="post">
                <div class="modal-body">
                    <input type="hidden" id="idElement">
                    <input type="hidden" id="action">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nom et prénom ou dénomination Employeur:<b>*</b></label>
                                <select name="idemployeurs" id="idemployeurs" class="form-control" >
                                <option value="">__</option>
                                <?php
                                foreach ($employeurs as $employeur) {
                                    echo '<option value="' . $employeur->id . '">' . $employeur->nomE . '</option>';
                                }
                                ?>
                            </select>
                            </div>
                        </div> 

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Intitulé précis de la formation  / Nom du Centre de Formation: </label>
                                <select name="idformations" id="idformations" class="form-control" >
                                <option value="">__</option>
                                <?php
                                foreach ($formations as $formation) {
                                    $nom = (empty($formation->intituleF)) ? StringHelper::isEmpty('').' / '. $formation->nomF: $formation->intituleF.' / '. $formation->nomF;
                                    echo '<option value="' . $formation->id . '">' .  $nom . '</option>';
                                }
                                ?>
                                ?>
                            </select>
                            </div>
                        </div> 
                    </div>



                        <!-- information apprentis -->


                    <div class="row">
                        <p class="mainColor text-left">L’APPRENTI(E)</p>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Nom de naissance de l’apprenti(e) : <b>*</b></label>
                                <input type="text" id="nomA" name="nomA" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Nom d’usage :  </label>
                                <input type="text" id="nomuA" name="nomuA" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Le premier prénom de l’apprenti(e)s  </label>
                                <input type="text" id="prenomA" name="prenomA" class="form-control"required >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Sexe : <b>*</b> </label>
                                <select  id="sexeA"  name="sexeA" class="form-control" required>
                                <option value="">__</option>
                                <option value="M">M</option>
                                <option value="F">F</option>
                               
                            </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                     
                       
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Date de naissance:<b>*</b> </label>
                                <input type="date" id="naissanceA" name="naissanceA" class="form-control" value="JJ/MM/AAAA" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Département de naissance : <b>*</b></label>
                                <select id="departementA" name="departementA" class="form-control" required>
                                    <option value="">_______</option>
                                    <option value="01">01 - Ain - Bourg-en-Bresse</option>
                                    <option value="02">02 - Aisne - Laon</option>
                                    <option value="03">03 - Allier - Moulins</option>
                                    <option value="04">04 - Alpes-de-Haute-Provence - Digne-les-Bains</option>
                                    <option value="05">05 - Hautes-Alpes - Gap</option>
                                    <option value="06">06 - Alpes-Maritimes - Nice</option>
                                    <option value="07">07 - Ardèche - Privas</option>
                                    <option value="08">08 - Ardennes - Charleville-Mézières</option>
                                    <option value="09">09 - Ariège - Foix</option>
                                    <option value="10">10 - Aube - Troyes</option>
                                    <option value="11">11 - Aude - Carcassonne</option>
                                    <option value="12">12 - Aveyron - Rodez</option>
                                    <option value="13">13 - Bouches-du-Rhône - Marseille</option>
                                    <option value="14">14 - Calvados - Caen</option>
                                    <option value="15">15 - Cantal - Aurillac</option>
                                    <option value="16">16 - Charente - Angoulême</option>
                                    <option value="17">17 - Charente-Maritime - La Rochelle</option>
                                    <option value="18">18 - Cher - Bourges</option>
                                    <option value="19">19 - Corrèze - Tulle</option>
                                    <option value="2a">2A - Corse-du-Sud - Ajaccio</option>
                                    <option value="2b">2B - Haute-Corse - Bastia</option>
                                    <option value="21">21 - Côte-d'Or - Dijon</option>
                                    <option value="22">22 - Côtes-d'Armor - Saint-Brieuc</option>
                                    <option value="23">23 - Creuse - Guéret</option>
                                    <option value="24">24 - Dordogne - Périgueux</option>
                                    <option value="25">25 - Doubs - Besançon</option>
                                    <option value="26">26 - Drôme - Valence</option>
                                    <option value="27">27 - Eure - Évreux</option>
                                    <option value="28">28 - Eure-et-Loir - Chartres</option>
                                    <option value="29">29 - Finistère - Quimper</option>
                                    <option value="30">30 - Gard - Nîmes</option>
                                    <option value="31">31 - Haute-Garonne - Toulouse</option>
                                    <option value="32">32 - Gers - Auch</option>
                                    <option value="33">33 - Gironde - Bordeaux</option>
                                    <option value="34">34 - Hérault - Montpellier</option>
                                    <option value="35">35 - Ille-et-Vilaine - Rennes</option>
                                    <option value="36">36 - Indre - Châteauroux</option>
                                    <option value="37">37 - Indre-et-Loire - Tours</option>
                                    <option value="38">38 - Isère - Grenoble</option>
                                    <option value="39">39 - Jura - Lons-le-Saunier</option>
                                    <option value="40">40 - Landes - Mont-de-Marsan</option>
                                    <option value="41">41 - Loir-et-Cher - Blois</option>
                                    <option value="42">42 - Loire - Saint-Étienne</option>
                                    <option value="43">43 - Haute-Loire - Le Puy-en-Velay</option>
                                    <option value="44">44 - Loire-Atlantique - Nantes</option>
                                    <option value="45">45 - Loiret - Orléans</option>
                                    <option value="46">46 - Lot - Cahors</option>
                                    <option value="47">47 - Lot-et-Garonne - Agen</option>
                                    <option value="48">48 - Lozère - Mende</option>
                                    <option value="49">49 - Maine-et-Loire - Angers</option>
                                    <option value="50">50 - Manche - Saint-Lô</option>
                                    <option value="51">51 - Marne - Châlons-en-Champagne</option>
                                    <option value="52">52 - Haute-Marne - Chaumont</option>
                                    <option value="53">53 - Mayenne - Laval</option>
                                    <option value="54">54 - Meurthe-et-Moselle - Nancy</option>
                                    <option value="55">55 - Meuse - Bar-le-Duc</option>
                                    <option value="56">56 - Morbihan - Vannes</option>
                                    <option value="57">57 - Moselle - Metz</option>
                                    <option value="58">58 - Nièvre - Nevers</option>
                                    <option value="59">59 - Nord - Lille</option>
                                    <option value="60">60 - Oise - Beauvais</option>
                                    <option value="61">61 - Orne - Alençon</option>
                                    <option value="62">62 - Pas-de-Calais - Arras</option>
                                    <option value="63">63 - Puy-de-Dôme - Clermont-Ferrand</option>
                                    <option value="64">64 - Pyrénées-Atlantiques - Pau</option>
                                    <option value="65">65 - Hautes-Pyrénées - Tarbes</option>
                                    <option value="66">66 - Pyrénées-Orientales - Perpignan</option>
                                    <option value="67">67 - Bas-Rhin - Strasbourg</option>
                                    <option value="68">68 - Haut-Rhin - Colmar</option>
                                    <option value="69">69 - Rhône - Lyon</option>
                                    <option value="70">70 - Haute-Saône - Vesoul</option>
                                    <option value="71">71 - Saône-et-Loire - Mâcon</option>
                                    <option value="72">72 - Sarthe - Le Mans</option>
                                    <option value="73">73 - Savoie - Chambéry</option>
                                    <option value="74">74 - Haute-Savoie - Annecy</option>
                                    <option value="75">75 - Paris - Paris</option>
                                    <option value="76">76 - Seine-Maritime - Rouen</option>
                                    <option value="77">77 - Seine-et-Marne - Melun</option>
                                    <option value="78">78 - Yvelines - Versailles</option>
                                    <option value="79">79 - Deux-Sèvres - Niort</option>
                                    <option value="80">80 - Somme - Amiens</option>
                                    <option value="81">81 - Tarn - Albi</option>
                                    <option value="82">82 - Tarn-et-Garonne - Montauban</option>
                                    <option value="83">83 - Var - Toulon</option>
                                    <option value="84">84 - Vaucluse - Avignon</option>
                                    <option value="85">85 - Vendée - La Roche-sur-Yon</option>
                                    <option value="86">86 - Vienne - Poitiers</option>
                                    <option value="87">87 - Haute-Vienne - Limoges</option>
                                    <option value="88">88 - Vosges - Épinal</option>
                                    <option value="89">89 - Yonne - Auxerre</option>
                                    <option value="90">90 - Territoire de Belfort - Belfort</option>
                                    <option value="91">91 - Essonne - Évry</option>
                                    <option value="92">92 - Hauts-de-Seine - Nanterre</option>
                                    <option value="93">93 - Seine-Saint-Denis - Bobigny</option>
                                    <option value="94">94 - Val-de-Marne - Créteil</option>
                                    <option value="95">95 - Val-d'Oise - Pontoise</option>
                                    <option value="971">971 - Guadeloupe - Basse-Terre</option>
                                    <option value="972">972 - Martinique - Fort-de-France</option>
                                    <option value="973">973 - Guyane - Cayenne</option>
                                    <option value="974">974 - La Réunion - Saint-Denis</option>
                                    <option value="976">976 - Mayotte - Dzaoudzi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Commune de naissance : <b>*</b></label>
                                <input type="text" id="communeNA" name="communeNA" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                         <div class="form-group">
                             <label class="control-label">Nationalité :  <b>*</b></label>
                             <select id="nationaliteA" name="nationaliteA" class="form-control" required>
                                <option value="">_______</option>
                                <option value="1">1 : Française</option>
                                <option value="2">2 : Union Européenne</option>
                                <option value="3">3 : Étranger hors Union Européenne</option>
                             </select>
                         </div>
                     </div>
                    </div>


                    <div class="row">
                     
                     
                     <div class="col-md-2">
                         <div class="form-group">
                             <label class="control-label">Régime social :   <b>*</b> </label>
                             <select id="regimeA" name="regimeA" class="form-control" required>
                                <option value="">_______</option>
                                <option value="1">1 : MSA</option>
                                <option value="2">2 : URSSAF</option>
                            </select>
                             
                         </div>
                     </div>
                     <div class="col-md-3">
                         <div class="form-group">
                             <label class="control-label">Situation avant ce contrat :  <b>*</b></label>
                             <select id="situationA" name="situationA" class="form-control" required>
                                <option value="">_______</option>
                                <option value="1">1 : Scolaire</option>
                                <option value="2">2 : Prépa apprentissage</option>
                                <option value="3">3 : Étudiant</option>
                                <option value="4">4 : Contrat d’apprentissage</option>
                                <option value="5">5 : Contrat de professionnalisation</option>
                                <option value="6">6 : Contrat aidé</option>
                                <option value="7">7 : En formation au CFA avant signature d’un contrat d’apprentissage (L6222-12-1 du code du travail)</option>
                                <option value="8">8 : En formation, au CFA, sans contrat, suite à rupture (5° de L6231-2 du code du travail)</option>
                                <option value="9">9 : Stagiaire de la formation professionnelle</option>
                                <option value="10">10 : Salarié</option>
                                <option value="11">11 : Personne à la recherche d’un emploi (inscrite ou non au Pôle Emploi)</option>
                                <option value="12">12 : Inactif</option>
                            </select>
                         </div>
                     </div>
                     <div class="col-md-4">
                         <div class="form-group">
                             <label class="control-label">Dernier diplôme ou titre préparé : <b>*</b>  </label>
                             <select id="titrePA" name="titrePA" class="form-control" required>
                                 <option value="">_______</option>
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
                     <div class="col-md-3">
                        <div class="form-group">
                            <label for="derniereCA" class="control-label">Dernière classe / année suivie : <b>*</b></label>
                            <select id="derniereCA" name="derniereCA" class="form-control" required>
                                <option value="">______</option>
                                <option value="1">1 : l’apprenti a suivi la dernière année du cycle de formation et a obtenu le diplôme ou titre</option>
                                <option value="11">11 : l’apprenti a suivi la 1ère année du cycle et l’a validée (examens réussis mais année non diplômante)</option>
                                <option value="12">12 : l’apprenti a suivi la 1ère année du cycle mais ne l’a pas validée (échec aux examens, interruption ou abandon de formation)</option>
                                <option value="21">21 : l’apprenti a suivi la 2è année du cycle et l’a validée (examens réussis mais année non diplômante)</option>
                                <option value="22">22 : l’apprenti a suivi la 2è année du cycle mais ne l’a pas validée (échec aux examens, interruption ou abandon de formation)</option>
                                <option value="31">31 : l’apprenti a suivi la 3è année du cycle et l’a validée (examens réussis mais année non diplômante, cycle adapté)</option>
                                <option value="32">32 : l’apprenti a suivi la 3è année du cycle mais ne l’a pas validée (échec aux examens, interruption ou abandon de formation)</option>
                                <option value="40">40 : l’apprenti a achevé le 1er cycle de l’enseignement secondaire (collège)</option>
                                <option value="41">41 : l’apprenti a interrompu ses études en classe de 3ème</option>
                            </select>
                        </div>
                    </div>
                 </div>

                 <div class="row">
                 
                 <div class="col-md-3">
                         <div class="form-group">
                             <label class="control-label">NIR de l’apprenti(e) : <b>*</b>  </label>
                             <input type="number" id="securiteA" name="securiteA" class="form-control" minlength="13" maxlength="15" required>
                             <small class="form-text text-muted"> Le numéro de sécurité sociale de l'apprenti doit contenir entre 13 et 15  caractères</small>
                            
                         </div>
                     </div>
                     
                     <div class="col-md-5">
                         <div class="form-group">
                             <label class="control-label">Intitulé précis du dernier diplôme ou titre préparé : <b>*</b>   </label>
                             <input type="text" id="intituleA"   name="intituleA" class="form-control" required>
                         </div>
                     </div>
                     <div class="col-md-4">
                         <div class="form-group">
                             <label class="control-label">Diplôme ou titre le plus élevé obtenu :<b>*</b> </label>
                             <select id="titreOA" name="titreOA" class="form-control" required>
                                  <option value="">_______</option>
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
                     
                 </div>




                    <div class="row">
                     <div class="col-md-4">
                         <div class="form-group">
                             <label class="control-label">Déclare être inscrit sur la liste des sportifs de haut  niveau : </label>
                             <select  id="declareSA"  name="declareSA" class="form-control">
                                <option value="">__</option>
                                <option value="oui">Oui</option>
                                <option value="non">Non</option>
                               
                            </select>
                         </div>
                     </div>
                     <div class="col-md-4">
                         <div class="form-group">
                             <label class="control-label">Déclare bénéficier de la reconnaissance travailleur handicapé : </label>
                             <select  id="declareHA" name="declareHA" class="form-control">
                                <option value="">__</option>
                                <option value="oui">Oui</option>
                                <option value="non">Non</option>
                               
                            </select>
                         </div>
                     </div>
                     <div class="col-md-4">
                         <div class="form-group">
                             <label class="control-label">Déclare avoir un projet de création ou de reprise d’entreprise :  </label>
                             <select  id="declareRA" id="declareRA" class="form-control">
                                <option value="">__</option>
                                <option value="oui">oui</option>
                                <option value="non">Non</option>
                               
                            </select>
                         </div>
                     </div>
                 </div>


                    <div class="row">
                        <p style="margin-left:15px; "   class=" text-left">Adresse de l’apprenti(e) :  <b>*</b> </p>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" id="rueA"   name="rueA" class="form-control" placeholder="N°  *" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                 <input type="text" id="voieA"  name="voieA" class="form-control"  placeholder="Voie  *"  required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                               <input type="text" id="complementA"  name="complementA" class="form-control" minlength="0" maxlength="115" placeholder="Complement" >   
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="number" id="postalA" name="postalA" class="form-control"  placeholder="Code Postal *" required>
                                <small class="form-text text-muted">Veuillez entrer exactement 5 chiffres</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                       <div class="col-md-4">
                            <div class="form-group">
                                 <input type="text" id="communeA"  name="communeA" class="form-control"  minlength="0" maxlength="80"  placeholder="Commune *"  required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                 <input type="number" id="numeroA"  name="numeroA" class="form-control" pattern="\d{10}" placeholder="Téléphone : "  required>
                                 <small class="form-text text-muted">Veuillez entrer exactement 10 chiffres</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                 <input type="email" id="emailA" name="emailA" class="form-control"  placeholder="email: *"  required>
                            </div>
                        </div>
                       
                    </div>


                                <!-- informations representant legal  -->

                    <div class="row">
                        <p class="mainColor text-left">Représentant légal (à renseigner si l’apprenti est mineur non  émancipé) </p>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Nom de naissance  : <b>*</b></label>
                                <input type="text" id="nomR" name="nomR" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Prenom : <b>*</b></label>
                                <input type="text" id="prenomR" name="prenomR" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                               <label class="control-label">Courriel : </label>
                                 <input type="email" id="emailR"  name="emailR" class="form-control"  placeholder="Courriel" >
                            </div>
                        </div>
                       
                      
                    </div>

                    <div class="row">
                        <p style="margin-left:15px; "   class=" text-left">Adresse du représentant légal :  <b>*</b></p>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" id="rueR"  name="rueR" class="form-control" placeholder="N° *" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                 <input type="text" id="voieR"  name="voieR" class="form-control"  placeholder="Voie *"  required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                               <input type="text" id="complementR"  name="complementR" class="form-control"  minlength="0" maxlength="115" placeholder="Complement">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                      
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="number" id="postalR"   minlength="5" maxlength="5"  name="postalR" class="form-control" placeholder="Code Postal *" required>
                                <small class="form-text text-muted">Veuillez entrer exactement 5 chiffres.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                 <input type="text" id="communeR" name="communeR" minlength="0" maxlength="80" class="form-control"  placeholder="Commune *" required>
                            </div>
                        </div>

                        
                       
                    </div>


                       <!-- informations maitres de stage  -->

                     
                     <div class="row">
                        <p class="mainColor text-left"> LE MAÎTRE D’APPRENTISSAGE</p>
                     </div>

                    <div class="row">
                        <p style="margin-left:15px; "   class=" text-left">Maître d’apprentissage n°1   </p>
                       
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Nom de naissance :   <b>*</b></label>
                                <input type="text" id="nomM"   name="nomM"  class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Prénom :  <b>*</b></label>
                                <input type="text" id="prenomM" name="prenomM" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Date de naissance :   <b>*</b></label>
                                <input type="date" id="naissanceM" name="naissanceM" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">NIR :  </label>
                                <input type="text" id="securiteM"  name="securiteM" class="form-control" >
                                <small class="form-text text-muted"> Le numéro de sécurité sociale du premier maître de stage doit contenir entre 13 et 15  caractères</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Courriel : </label>
                                <input type="email" id="emailM" name="emailM" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Emploi occupé : </label>
                                <input type="text" id="emploiM"  name="emploiM" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" style="font-size: 11px;">Diplôme ou titre le plus élevé obtenu :  </label>
                                <input type="text" id="diplomeM" name="diplomeM" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" >
                                <label class="control-label"  style="font-size: 10px;">Niveau de diplôme ou titre le plus élevé obtenu :  <b>*</b></label>
                                <select id="niveauM" name="niveauM" class="form-control" required>
                                    <option value="">________</option>
                                    <option value="1">CAP, BEP</option>
                                    <option value="2">Baccalauréat</option>
                                    <option value="3">DEUG, BTS, DUT, DEUST</option>
                                    <option value="4">Licence, Licence professionnelle, BUT, Maîtrise</option>
                                    <option value="5">Master, DEA, DESS, Diplôme d'ingénieur</option>
                                    <option value="6">Doctorat, Habilitation à diriger des recherches</option>
                                </select>
                            </div>
                        </div>   
                    </div>

                 

                    <div class="row">
                        <p style="margin-left:15px; "   class=" text-left">Maître d’apprentissage n°2   </p>
                       
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Nom de naissance :   <b>*</b></label>
                                <input type="text" id="nomM1"   name="nomM1"  class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Prénom :  <b>*</b></label>
                                <input type="text" id="prenomM1" name="prenomM1" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Date de naissance :  <b>*</b></label>
                                <input type="date" id="naissanceM1" name="naissanceM1" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">NIR :  </label>
                                <input type="text" id="securiteM1"  name="securiteM1" class="form-control" >
                                <small class="form-text text-muted"> Le numéro de sécurité sociale du deuxieme maître de stage doit contenir entre 13 et 15  caractères</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Courriel :</label>
                                <input type="email" id="emailM1" name="emailM1" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Emploi occupé : </label>
                                <input type="text" id="emploiM1"  name="emploiM1" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" style="font-size: 11px;">Diplôme ou titre le plus élevé obtenu :  </label>
                                <input type="text" id="diplomeM1" name="diplomeM1" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" >
                                <label class="control-label"  style="font-size: 10px;">Niveau de diplôme ou titre le plus élevé obtenu :  <b>*</b></label>
                                <select id="niveauM1" name="niveauM1" class="form-control" required>
                                     <option value="">________</option>
                                    <option value="1">CAP, BEP</option>
                                    <option value="2">Baccalauréat</option>
                                    <option value="3">DEUG, BTS, DUT, DEUST</option>
                                    <option value="4">Licence, Licence professionnelle, BUT, Maîtrise</option>
                                    <option value="5">Master, DEA, DESS, Diplôme d'ingénieur</option>
                                    <option value="6">Doctorat, Habilitation à diriger des recherches</option>
                                </select>
                            </div>
                        </div>   
                    </div>






                     <!-- informations sur le contrat  -->

                     
                     <div class="row">
                        <p class="mainColor text-left"> LE CONTRAT  </p>
                     </div>

                    
                    <div class="row">

                         <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"> 	Mode Contractuel d'apprentissage :  <b>*</b></label>
                                <select id="modeC" name="modeC" class="form-control" required>
                                    <option value="">______</option>
                                    <option value="1">1 : À durée limitée</option>
                                    <option value="2">2 : Dans le cadre d’un CDI</option>
                                    <option value="3">3 : Entreprise de travail temporaire</option>
                                    <option value="4">4 : Activités saisonnières à deux employeurs</option>
                                </select>
                               
                            </div>
                        </div>
                       
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"> Travail sur machines dangereuses ou exposition à des risques particuliers :  <b>*</b>    </label>
                                <select  id="travailC" name="travailC" class="form-control" required>
                                <option value="">__</option>
                                <option value="oui">Oui</option>
                                <option value="non">Non</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Type de dérogation : à renseigner si une dérogation existe pour ce contrat  </label>
                                <select id="derogationC" name="derogationC" class="form-control" >
                                     <option value="">______________</option>
                                    <option value="11">11 : Age de l’apprenti inférieur à 16 ans</option>
                                    <option value="12">12 : Age supérieur à 29 ans : cas spécifiques prévus dans le code du travail</option>
                                    <option value="21">21 : Réduction de la durée du contrat ou de la période d’apprentissage</option>
                                    <option value="22">22 : Allongement de la durée du contrat ou de la période d’apprentissage</option>
                                    <option value="50">50 : Cumul de dérogations</option>
                                    <option value="60">60 : Autre dérogation</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Numéro du contrat précédent ou du contrat sur lequel porte l’avenant :</label>
                                <input type="text" id="numeroC"  name="numeroC" class="form-control" >
                            </div>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" >Date de conclusion : (Date de signature du présent contrat) <b>*</b>  </label>
                                <input type="date" id="conclusionC"  name="conclusionC" class="form-control" required>
                            </div>
                        </div>
                       
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" > Date de début de formation pratique  chez l’employeur : </label>
                                <input type="date" id="debutC" name="debutC" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Date de fin du contrat ou de la période d’apprentissage : <b>*</b> </label>
                                <input type="date" id="finC"  name="finC" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Si avenant, date d’effet:</label>
                                <input type="date" id="avenantC"  name="avenantC" class="form-control" >
                            </div>
                        </div>   
                       
                       
                    </div>

                    <div class="row">
                      
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" style="font-size: 11px;">Date de début d’exécution du contrat:  <b>*</b></label>
                                <input type="date" id="executionC"  name="executionC" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"> Durée hebdomadaire du travail:(En heures)  <b>*</b></label>
                                <input type="number" id="dureC" name="dureC" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"> Durée hebdomadaire du travail:(En minutes)  </label>
                                <input type="number" id="dureCM" name="dureCM" class="form-control" >
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Type de contrat ou d’avenant : <b>*</b> </label>
                                <select id="typeC" name="typeC" class="form-control" required>
                                     <option value="">__________</option>
                                    <optgroup label="Contrat initial">
                                        <option value="11">Premier contrat d’apprentissage de l’apprenti</option>
                                    </optgroup>
                                    <optgroup label="Succession de contrats">
                                        <option value="21">Nouveau contrat avec un apprenti qui a terminé son précédent contrat auprès d’un même employeur</option>
                                        <option value="22">Nouveau contrat avec un apprenti qui a terminé son précédent contrat auprès d’un autre employeur</option>
                                        <option value="23">Nouveau contrat avec un apprenti dont le précédent contrat auprès d’un autre employeur a été rompu</option>
                                    </optgroup>
                                    <optgroup label="Avenant : modification des conditions du contrat">
                                        <option value="31">Modification de la situation juridique de l’employeur</option>
                                        <option value="32">Changement d’employeur dans le cadre d’un contrat saisonnier</option>
                                        <option value="33">Prolongation du contrat suite à un échec à l’examen de l’apprenti</option>
                                        <option value="34">Prolongation du contrat suite à la reconnaissance de l’apprenti comme travailleur handicapé</option>
                                        <option value="35">Modification du diplôme préparé par l’apprenti</option>
                                        <option value="36">Autres changements : changement de maître d’apprentissage, de durée de travail hebdomadaire, réduction de durée, etc.</option>
                                        <option value="37">Modification du lieu d’exécution du contrat</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        
                    </div>    

                    <div class="row">
                        <p style="margin-left:15px; "   class=" text-left">Rémunération   </p>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"> 1er annee <b>*</b></label>
                                <input type="date" id="rdC" name="rdC" class="form-control" placeholder="du" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label class="control-label"> .</label>
                                <input type="date" id="raC" name="raC" class="form-control" placeholder="au" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label class="control-label"> .</label>
                                <input type="number" id="rpC" name="rpC" class="form-control" placeholder="pourcentage" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label class="control-label"> .</label>
                                <select id="rsC" name="rsC" class="form-control">
                                    <option value="SMIC">salaire minimum de croissance</option>
                                    <option value="SMC">salaire minimum conventionnel</option>
                                </select>
                            </div>
                        </div> 
                        
                        
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"> 2eme annee </label>
                                <input type="date" id="rdC1" name="rdC1" class="form-control" placeholder="du">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label class="control-label"> .</label>
                                <input type="date" id="raC1" name="raC1" class="form-control" placeholder="au" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label class="control-label"> .</label>
                                <input type="number" id="rpC1" name="rpC1" class="form-control" placeholder="pourcentage" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label class="control-label"> .</label>
                            <select id="rsC1" name="rsC1" class="form-control" >
                                    <option value="SMIC">salaire minimum de croissance</option>
                                    <option value="SMC">salaire minimum conventionnel</option>
                                </select>
                            </div>
                        </div> 
                        
                        
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"> 3eme annee </label>
                                <input type="date" id="rdC2" name="rdC2" class="form-control" placeholder="du">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label class="control-label"> .</label>
                                <input type="date" id="raC2" name="raC2" class="form-control" placeholder="au" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label class="control-label"> .</label>
                                <input type="number" id="rpC2" name="rpC2" class="form-control" placeholder="pourcentage" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label class="control-label"> .</label>
                            <select id="rsC2" name="rsC2" class="form-control" >
                                    <option value="SMIC">salaire minimum de croissance</option>
                                    <option value="SMC">salaire minimum conventionnel</option>
                                </select>
                            </div>
                        </div> 
                        
                        
                    </div>

                   



                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Salaire brut mensuel à l’embauche :  <b>*</b></label>
                                <input type="text" id="salaireC" name="salaireC" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Caisse de retraite complémentaire :   <b>*</b></label>
                                <input type="text" id="caisseC" name="caisseC" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Logement :  € / mois   </label>
                                <input type="number" id="logementC" name="logementC" class="form-control" >
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"> Avantages en nature, le cas échéant : Nourriture :  € / repas  </label>
                                <input type="number" id="avantageC" name="avantageC" class="form-control" >
                            </div>
                        </div>
                        
                        
                        <div class="col-md-6">
                            <div class="form-group">
                            <label class="control-label"> Autre :    </label>
                            
                            <select  id="autreC" name="autreC" class="form-control">
                                <option value="">__</option>
                                <option value="oui">Oui</option>
                                <option value="non">Non</option>
                                </select>
                            </div>
                        </div> 
                    </div>

                    <div class="row">
                        
                        <div class="col-md-3">
                            <div class="form-group">
                            <label class="control-label">  Fait à     <b>*</b></label>
                                <input type="text" id="lieuO" name="lieuO" class="form-control" placeholder="lieude signature du contrat" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                            <label class="control-label">  entreprise prive ou public  </label>
                            <input type="hidden" id="attesteO"  name="attesteO" class="form-control" value="oui">
                            <select  id="priveO"  name="priveO" class="form-control">
                                <option value="">__</option>
                                <option value="oui">prive</option>
                                <option value="non">public</option>
                               
                            </select>
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


<div class="modal fade conventionModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="FormConvention1">AJOUTER UNE CONVENTION</h2>
            </div>
            <form action="<?= App::url('cerfas/sendOpcoConvention') ?>" id="FormConvention" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="idConvention" name="idConvention">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                    <div class="row">
                        <div class="col-md-7 form-group">
                            <label for="Convention">Choisissez un fichier à télécharger:pdf, jpg, jpeg, png<b>* </b> </label>
                            <input type="file" class="form-control" id="file"  name="file" required>
                        </div>
                        <div class="col-md-5 form-group">
                            <label for="ConventionDate">Date de signature de la convention<b>* </b> </label>
                            <input type="date" class="form-control" id="dateConvention"  name="dateConvention" required>
                        </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="coutTotalPedagogieCFA">Cout Total Pedagogie CFA<b>* </b> </label>
                            <input type="number" class="form-control" id="coutTotalPedagogieCFA"  name="coutTotalPedagogieCFA" required>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="montantPremierEquipement">Montant Premier Equipement<b>* </b> </label>
                            <input type="number" class="form-control" id="montantPremierEquipement"  name="montantPremierEquipement" value="500" required>
                        </div>
                    </div>

                    <div class="row">
                       <div class="col-md-4 form-group">
                            <label for="nombreHebergementTotaux">Montant Hebergement Totaux </label>
                            <input type="number" class="form-control" id="nombreHebergementTotaux"  name="nombreHebergementTotaux" >
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="nombreRepasTotaux">Montant Repas Totaux </label>
                            <input type="number" class="form-control" id="nombreRepasTotaux"  name="nombreRepasTotaux"  >
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="montantRQTHAnnee1">montant RQTH Annee1 </label>
                            <input type="number" class="form-control" id="montantRQTHAnnee1"  name="montantRQTHAnnee1" >
                        </div>
                    </div>    

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="montantRQTHAnnee2">montant RQTH Annee2 </label>
                            <input type="number" class="form-control" id="montantRQTHAnnee2"  name="montantRQTHAnnee2"  >
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="montantRQTHAnnee3">montant RQTH Annee3 </label>
                            <input type="number" class="form-control" id="montantRQTHAnnee3"  name="montantRQTHAnnee3" >
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="montantRQTHAnnee4">montant RQTH Annee4</label>
                            <input type="number" class="form-control" id="montantRQTHAnnee4"  name="montantRQTHAnnee4"  >
                        </div>
                    </div>    

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="mentionMobilitéInternationale">Mention Mobilité Internationale</label>
                            <select  id="mentionMobilitéInternationale"  name="mentionMobilitéInternationale" class="form-control">
                                <option value="false">non</option> 
                                <option value="true">oui</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="accompagnementDROM">Accompagnement DROM<b>* </b> </label>
                            <select  id="accompagnementDROM"  name="accompagnementDROM" class="form-control" >
                                <option value="false">non</option>
                                <option value="true">oui</option>
                               
                            </select>
                        </div>
                    </div>   
                       
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="photoBtn btn btn-default">ENVOYER</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade cerfaModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="FormCerfa1">AJOUTER UN CERFA</h2>
            </div>
            <form action="<?= App::url('cerfas/sendOpco') ?>" id="FormCerfa" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="idCerfa" name="idCerfa">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="Cerfa">Choisissez un fichier à télécharger :  pdf, jpg, jpeg, png<b>* </b> </label>
                            <input type="file" class="form-control" id="cerfa"  name="cerfa" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="photoBtn btn btn-default">ENVOYER</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade FactureModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="FormFacture1">AJOUTER UNE FACTURE</h2>
            </div>
            <form action="<?= App::url('cerfas/sendOpcoFacture') ?>" id="FormFacture" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="idFacture" name="idFacture">
                    <p class="mainColor text-right">* Champs obligatoires</p>


                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="numero">Numero Facture</label>
                            <input type="text" class="form-control" id="numeroF"  name="numeroF" >
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="numero">Fait a<b>* </b> </label>
                            <input type="text" class="form-control" id="lieuF"  name="lieuF" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="numero">le <b>* </b> </label>
                            <input type="date" class="form-control" id="dateF"  name="dateF" required>
                        </div>
                    </div>

                    <div class="row">
                    <div class="col-md-6 form-group">
                            <label for="numero">numero Interne Client <b>* </b> </label>
                            <input type="text" class="form-control" id="numeroClient"  name="numeroClient" required>
                        </div>      
                        <div class="col-md-6 form-group">
                            <label for="numero">IBAN<b>* </b> </label>
                            <input type="hidden" class="form-control"  id="numeroOF"  name="numeroOF" value="127639.001">
                            <input type="number" class="form-control"  id="ibanF"  name="ibanF" required>
                        </div>              
                    </div>      
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="numero">Representant du centre </label>
                            <input type="text" class="form-control"  id="repreF"  name="repreF" >
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="numero">Emploi occupé :   </label>
                            <input type="text" class="form-control" id="emploiRF"  name="emploiRF" >
                        </div>  
                        <div class="col-md-4 form-group">
                            <label for="numero">Coût annuel Branche  <b>* </b> </label>
                            <input type="number" class="form-control" id="coutAB"  name="coutAB" required>
                        </div>  
                    </div>

                    

                    <div class="row">
                       <p style="margin-left:15px; "   class=" text-left">Depenses  <b>*</b> </p>
                        <div class="col-md-3 form-group">
                            <label for="numero">Motif<b>*</b> </label>
                            <select name="motif" id="motif" class="form-control" required>
                                <option value="">_________</option>
                                <option value="mobilite">MOBILITE</option>
                                <option value="hebergement">HEBERGEMENT</option>
                                <option value="restauration">RESTAURATION</option>
                                <option value="premiereequipement">PREMIERE EQUIPEMENT</option>
                                <option value="pedagogie">PEDAGOGIE</option>
                                <option value="majoration_rqth">MAJORATION RQTH</option>
                            </select>

                        </div>
                        
                        <div class="col-md-3 form-group">
                            <label for="numero">Montant <b>* </b> </label>
                            <input type="number" class="form-control" id="montant"  name="montant" required>
                        </div>

                        <div class="col-md-3 form-group">
                            <label for="numero">Motif </label>
                            <select name="motif1" id="motif1" class="form-control">
                                <option value="">_________</option>
                                <option value="mobilite">MOBILITE</option>
                                <option value="hebergement">HEBERGEMENT</option>
                                <option value="restauration">RESTAURATION</option>
                                <option value="premiereequipement">PREMIERE EQUIPEMENT</option>
                                <option value="pedagogie">PEDAGOGIE</option>
                                <option value="majoration_rqth">MAJORATION RQTH</option>
                            </select>

                        </div>
                        <div class="col-md-3 form-group">
                            <label for="numero">Montant </label>
                            <input type="number" class="form-control" id="montant1"  name="montant1" >
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="numero">Motif </label>
                            <select name="motif2" id="motif2"  class="form-control">
                                <option value="">_________</option>
                                <option value="mobilite">MOBILITE</option>
                                <option value="hebergement">HEBERGEMENT</option>
                                <option value="restauration">RESTAURATION</option>
                                <option value="premiereequipement">PREMIERE EQUIPEMENT</option>
                                <option value="pedagogie">PEDAGOGIE</option>
                                <option value="majoration_rqth">MAJORATION RQTH</option>
                            </select>

                        </div>
                        <div class="col-md-3 form-group">
                            <label for="numero">Montant  </label>
                            <input type="number" class="form-control" id="montant2"  name="montant2" >
                        </div>

                        <div class="col-md-3 form-group">
                            <label for="numero">Motif </label>
                            <select name="motif3" id="motif3" class="form-control">
                                <option value="">_________</option>
                                <option value="mobilite">MOBILITE</option>
                                <option value="hebergement">HEBERGEMENT</option>
                                <option value="restauration">RESTAURATION</option>
                                <option value="premiereequipement">PREMIERE EQUIPEMENT</option>
                                <option value="pedagogie">PEDAGOGIE</option>
                                <option value="majoration_rqth">MAJORATION RQTH</option>
                            </select>

                        </div>
                        <div class="col-md-3 form-group">
                            <label for="numero">Montant  </label>
                            <input type="number" class="form-control" id="montant3"  name="montant3" >
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="numero">Motif </label>
                            <select name="motif4" id="motif4" class="form-control">
                                <option value="">_________</option>
                                <option value="mobilite">MOBILITE</option>
                                <option value="hebergement">HEBERGEMENT</option>
                                <option value="restauration">RESTAURATION</option>
                                <option value="premiereequipement">PREMIERE EQUIPEMENT</option>
                                <option value="pedagogie">PEDAGOGIE</option>
                                <option value="majoration_rqth">MAJORATION RQTH</option>
                            </select>

                        </div>
                        <div class="col-md-3 form-group">
                            <label for="numero">Montant  </label>
                            <input type="number" class="form-control" id="montant4"  name="montant4" >
                        </div>


                        <div class="col-md-3 form-group">
                            <label for="numero">Motif </label>
                            <select name="motif5" id="motif5" class="form-control">
                                <option value="">_________</option>
                                <option value="mobilite">MOBILITE</option>
                                <option value="hebergement">HEBERGEMENT</option>
                                <option value="restauration">RESTAURATION</option>
                                <option value="premiereequipement">PREMIERE EQUIPEMENT</option>
                                <option value="pedagogie">PEDAGOGIE</option>
                                <option value="majoration_rqth">MAJORATION RQTH</option>
                            </select>

                        </div>
                        <div class="col-md-3 form-group">
                            <label for="numero">Montant  </label>
                            <input type="number" class="form-control" id="montant5"  name="montant5" >
                        </div>
                    </div>

                  

                    <div class="row">
                       <p style="margin-left:15px; "   class=" text-left">Echeancier  <b>*</b> </p>
                        <div class="col-md-4 form-group">
                            <label for="numero">Numéro de l’échéance<b>*</b> </label>
                            <select name="motif" id="motif" class="form-control" required>
                                <option value="">_________</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                              
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="numeroEcheance"><b>* </b> </label>
                            <select name="motif" id="motif" class="form-control" required>
                                <option value="">_________</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="numero"><b>* </b> </label>
                            <select name="motif" id="motif" class="form-control" required>
                               <option value="">_________</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="numero"></label>
                            <select name="motif" id="motif" class="form-control" >
                               <option value="">_________</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="numero">Coût branche engagée<b>*</b></label>
                            <input type="number" class="form-control" id="CBE1"  name="CBE1" >

                        </div>
                        <div class="col-md-3 form-group">
                            <label for="numeroEcheance"><b>*</b>  </label>
                            <input type="number" class="form-control" id="CBE2"  name="CBE2" >
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="numero"><b>* </b> </label>
                            <input type="number" class="form-control" id="CBE3"  name="CBE3" >
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="numero"></label>
                            <input type="number" class="form-control" id="CBE4"  name="CBE4" >
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="numero">Montant àverser HT<b>*</b></label>
                            <input type="number" class="form-control" id="ht1"  name="ht1" >

                        </div>
                        <div class="col-md-3 form-group">
                            <label for="numeroEcheance"> <b>* </b>  </label>
                            <input type="number" class="form-control" id="ht2"  name="ht2" >
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="numero"><b>* </b> </label>
                            <input type="number" class="form-control" id="ht3"  name="ht3" >
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="numero"></label>
                            <input type="number" class="form-control" id="ht4"  name="ht4" >
                        </div>
                    </div>

                   
                   
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="Cerfa">Choisissez un fichier à télécharger :  pdf<b>* </b> </label>
                            <input type="file" class="form-control" id="facture"  name="facture" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="photoBtn btn btn-default">ENVOYER</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade conventionModalView" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" >AFFICHER LA CONVENTION</h2>
            </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <div id="documentContainer"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">  
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>
                </div>
         
        </div>
    </div>
</div>

<div class="modal fade cerfaModalView" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" >AFFICHER LE Cerfa</h2>
            </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <div id="documentContainerCerfa"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">  
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>
                </div>
         
        </div>
    </div>
</div>

<div class="modal fade factureModalView" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" >AFFICHER LA Facture qui va etre envoyer </h2>
            </div>
            <form action="<?= App::url('cerfas/sendFacture') ?>" id="FormFactureSend" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <input type="hidden" id="idFactureSend">
                            <input type="hidden" id="urlsend">
                            <div id="documentContainerFacture"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">  
                    <button type="submit" class="photoBtn btn btn-default">ENVOYER</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nomInput = document.getElementById('employeur_nom');
    const idInput = document.getElementById('idemployeur');
    const datalist = document.getElementById('employeurs-list');
    
    // Mettre à jour l'ID caché quand une option est sélectionnée
    nomInput.addEventListener('input', function() {
        const option = Array.from(datalist.options).find(
            opt => opt.value === nomInput.value
        );
        idInput.value = option ? option.getAttribute('data-id') : '';
    });
    
    // Validation pour s'assurer qu'un employeur valide est sélectionné
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!idInput.value) {
            e.preventDefault();
            alert('Veuillez sélectionner un employeur valide dans la liste');
            nomInput.focus();
        }
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const nomInput = document.getElementById('formation_nom');
    const idInput = document.getElementById('idformation');
    const datalist = document.getElementById('formations-list');
    
    // Mettre à jour l'ID caché quand une option est sélectionnée
    nomInput.addEventListener('input', function() {
        const option = Array.from(datalist.options).find(
            opt => opt.value === nomInput.value
        );
        idInput.value = option ? option.getAttribute('data-id') : '';
    });
    
    // Validation pour s'assurer qu'une formation valide est sélectionnée
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!idInput.value) {
            e.preventDefault();
            alert('Veuillez sélectionner une formation valide dans la liste');
            nomInput.focus();
        }
    });
});
</script>
