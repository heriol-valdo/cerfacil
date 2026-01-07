
<?php



use Projet\Database\Entreprise;
use Projet\Database\Formation;
use Projet\Database\Opco;
use Projet\Model\App;
use Projet\Model\Paginator;
use Projet\Model\StringHelper;
use Projet\Database\Abonnement;
use Projet\Database\Produit;
use Projet\Database\Cerfa;




App::setTitle("Les Dossiers");
App::setNavigation("Les Dossiers");
App::setBreadcumb('<li class="active"> Dossiers</li>');


function getNumeroDeca($numeroInterne,$opco){
      // Initialiser cURL
      $ch = curl_init();

      // Données pour obtenir le token
      $post_data = [
          'grant_type' => 'client_credentials',
          'client_id' => $opco->clid,
          'client_secret' => $opco->clse,
          'scope' => 'api.read api.write'
      ];
  
      // Configurer cURL pour obtenir le token
      curl_setopt($ch, CURLOPT_URL, $opco->lienT);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
      curl_setopt($ch, CURLOPT_POST, true);
  
      // Exécuter la requête pour obtenir le token
      $response = curl_exec($ch);
  
      // Vérifier les erreurs cURL
      if (curl_errno($ch)) {
          $error = 'Erreur cURL lors de l\'obtention du token : ' . curl_error($ch);
          curl_close($ch);
          return $error;
      }
  
      // Décoder la réponse pour récupérer le token
      $result = json_decode($response, true);
      if (!isset($result['access_token'])) {
          curl_close($ch);
          return 'Erreur : Impossible d\'obtenir le token d\'accès';
      }
      $access_token = $result['access_token'];
  
      // Configurer cURL pour obtenir les informations du dossier
   
      curl_setopt($ch, CURLOPT_URL, value: $opco->lienE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
          'Content-Type: application/json',
          'accept: application/json',
          'EDITEUR: LGX-CREATION',
          'LOGICIEL: LGX-CERFA',
          'VERSION: 1.0.0',
          "Authorization: Bearer $access_token",
          "X-API-KEY: $opco->cle"
      ]);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  
      // Exécuter la requête pour obtenir les informations du dossier
      $response = curl_exec($ch);
  
      // Vérifier les erreurs cURL
      if (curl_errno($ch)) {
          $error = 'Erreur cURL lors de l\'obtention des informations du dossier : ' . curl_error($ch);
          curl_close($ch);
          return $error;
      }
  
      // Obtenir le code de statut HTTP
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  
      // Fermer cURL
      curl_close($ch);
  
      // Analyser la réponse
      $responseJsons = json_decode($response, true);
  
      switch ($httpCode) {
          case 200:
            $response = "";
             foreach ($responseJsons as $responseJson){
                if($responseJson['numeroInterne'] == $numeroInterne){
                    $response =  $responseJson['numeroDeca'];
                    break; 
                }
             }
             return $response;
             
          case 400:
          case 401:
          case 404:
          case 403:
          case 500:
              $error = isset($responseJsons['errors']) ? $responseJsons['errors'] : "Erreur : " . $httpCode;
              return $error;
          default:
              return "Erreur inattendue : " . $httpCode;
      }

}

function etat($numeroInterne, $opco,$type) {
    // Initialiser cURL
    $ch = curl_init();

    // Données pour obtenir le token
    $post_data = [
        'grant_type' => 'client_credentials',
        'client_id' => $opco->clid,
        'client_secret' => $opco->clse,
        'scope' => 'api.read api.write'
    ];

    // Configurer cURL pour obtenir le token
    curl_setopt($ch, CURLOPT_URL, $opco->lienT);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_POST, true);

    // Exécuter la requête pour obtenir le token
    $response = curl_exec($ch);

    // Vérifier les erreurs cURL
    if (curl_errno($ch)) {
        $error = 'Erreur cURL lors de l\'obtention du token : ' . curl_error($ch);
        curl_close($ch);
        return $error;
    }

    // Décoder la réponse pour récupérer le token
    $result = json_decode($response, true);
    if (!isset($result['access_token'])) {
        curl_close($ch);
        return 'Erreur : Impossible d\'obtenir le token d\'accès';
    }
    $access_token = $result['access_token'];

    // Configurer cURL pour obtenir les informations du dossier
 
    $url = $opco->lienCe . "?numeroInterne=" .$numeroInterne ;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'accept: application/json',
        'EDITEUR: LGX-CREATION',
        'LOGICIEL: LGX-CERFA',
        'VERSION: 1.0.0',
        "Authorization: Bearer $access_token",
        "X-API-KEY: $opco->cle"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    // Exécuter la requête pour obtenir les informations du dossier
    $response = curl_exec($ch);

    // Vérifier les erreurs cURL
    if (curl_errno($ch)) {
        $error = 'Erreur cURL lors de l\'obtention des informations du dossier : ' . curl_error($ch);
        curl_close($ch);
        return $error;
    }

    // Obtenir le code de statut HTTP
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Fermer cURL
    curl_close($ch);

    // Analyser la réponse
    $responseJson = json_decode($response, true);

    switch ($httpCode) {
        case 200:
            if($type===1){ return $responseJson['echeances'];}
            elseif($type===2){ return $responseJson['detailsFacturation'];}
            elseif($type===3){ return $responseJson['engagementsFraisAnnexe'];}
            elseif($type===4){ return $responseJson['cerfa']['etat'];}
           
        case 400:
        case 401:
        case 404:
        case 403:
        case 500:
            $error = isset($responseJson['errors']) ? $responseJson['errors'] : "Erreur : " . $httpCode;
            return $error;
        default:
            return "Erreur inattendue : " . $httpCode;
    }
}

function getEtatLabel($numeroInterne, $opco,$type) {
    
    if (empty($numeroInterne)) {
        return StringHelper::$tabetatcerfa[''];
    }
    try {
        $etat = etat($numeroInterne, $opco,$type);
        

        if($type === 1){
            if (is_array($etat) && isset($etat[0]['dateOuverture'])) {
           
                return  $etat;
            }
    
            if (is_array($etat) && !isset($etat[0]['dateOuverture'])) {
                // Convertir les informations des échéances en chaîne de caractères lisible
                
                return json_encode($etat);
            }
            
        }elseif($type === 2){

            if (is_array($etat) && isset($etat['fraisPremierEquipementRegles'])) {
           
                return  $etat;
            }
            
            if (is_array($etat) && !isset($etat['fraisPremierEquipementRegles'])) {
                // Convertir les informations des échéances en chaîne de caractères lisible
                
                return json_encode($etat);
            }
            
            
        }elseif($type === 3){
            if (is_array($etat) && isset($etat[0]['natureFrais'])) {
           
                return  $etat;
            }
    
            if (is_array($etat) && !isset($etat[0]['natureFrais'])) {
                // Convertir les informations des échéances en chaîne de caractères lisible
                
                return json_encode($etat);
            }

        }elseif($type === 4){
            if (is_array($etat)) {
                // Log l'erreur pour le débogage
                error_log("Erreur lors de la récupération de l'état pour le numéro interne $numeroInterne : " . print_r($etat, true));
                return '<span class="label label-danger">Erreurs' . htmlspecialchars(json_encode($etat)) . '</span>';
            }
            
            // Vérifier si l'état retourné est une erreur
            if (is_string($etat) && strpos($etat, 'Erreur') === 0) {
                // Log l'erreur pour le débogage
                error_log("Erreur lors de la récupération de l'état pour le numéro interne $numeroInterne : $etat");
                return '<span class="label label-danger">ERREUR: ' . htmlspecialchars($etat) . '</span>';
            }
            
            return StringHelper::$tabetatcerfa[$etat] ?? '<span class="label label-default">ÉTAT INCONNU: ' . htmlspecialchars($etat) . '</span>';
        }  
    } catch (Exception $e) {
        error_log("Exception lors de la récupération de l'état pour le numéro interne $numeroInterne : " . $e->getMessage());
        return '<span class="label label-danger">ERREUR SYSTÈME</span>';
    }
}


?>
<style>

.modal {
    position: fixed; /* S'assurer que la modale est positionnée correctement */
   
   
   
    z-index: 1000; /* Doit être inférieur à celui du loader */
    display: none; /* Masqué par défaut */
    max-height: 1100px; /* Limite la largeur maximale */
}
     .hidden-div {
        display: none;
    }
    .spinner {
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 2s linear infinite;
}

.error-message {
    text-align: center;
    color: red;
    font-weight: bold;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
<div class="row" style=" margin: 13px;  background-color: #fff; border-radius: 10px;border: 5px solid #153C4A;">

        <div class="row" style=" background-color: #153C4A; height: 50px;">   
             <div class="col-md-5">
                <div class="form-group">
                  
                </div>
             </div>    
             <div class="col-md-7">
                <div class="form-group"style=" background-color: #153C4A;height: 20px;">
                  <div class="row">
                   <label class="control-label"><h1 style="color: #fff;">CONTRAT D'APPRENTISSAGE</h1>
                  </div> 

                  <div class="row" style="margin-left: 13%; ">
                   <label class="control-label" id="etatLabel"><?= empty($items->numeroInterne) ? StringHelper::$tabetatcerfa[''] : 'Chargement de l\'état...' ?></label> 
                  </div> 
   
                </div>
             </div>

           
  
        </div>

        <header style=" height: 65px;">
        <h1 style="color: #fff;"><?=empty($items->nomA)? $items->emailA: $items->nomA;?></h1> 
            <?php 
            if(empty($items->numeroDeca)){
                if(empty($items->numeroExterne)){
                   echo "<p style='color: #fff;'>  ID contrat :".($items->id)."1234 </p>";
                }else{
                   echo "<p style='color: #fff;' id='decaPlaceholder'> ID contrat : Chargement en cours...   </p> " ;
                }  
            } else{
                echo "<p style='color: #fff;'>  ID Deca :".$items->numeroDeca."</p>";
            }
            ?>
        
    </header>
  
    <section class="section">
        <section class="progress">
            <div class="cardDossier recap clickable">
                <div class="row">
                    <div class="col-md-8">
                        <div class="divcol">
                            <h2 class="h2">General</h2>
                            <div class="icon">⚙️</div>

                        </div>
                    </div>
                   
                </div>
            </div>
            <div class="cardDossier school clickable">
                <div class="row">
                    <div class="col-md-8">
                        <div class="divcol">
                            <h2 class="h2">École</h2>
                            <div class="icon">🎓</div>
                        </div>
                    </div>
                    <!-- <div class="col-md-4">
                        <p class="task">3/3 tâches</p>
                    </div> -->
                </div>
            </div>
            <div class="cardDossier student clickable ">
                <div class="row">
                    <div class="col-md-8">
                        <div class="divcol">
                            <h2 class="h2">Étudiant</h2>
                            <div class="icon">👤</div>
                        </div>
                    </div>
                    <!-- <div class="col-md-4">
                        <p class="task">4/4 tâches</p>
                    </div> -->
                </div>
            </div>

            <div class="cardDossier company clickable">
                <div class="row">
                    <div class="col-md-8">
                        <div class="divcol">
                            <h2 class="h2">Entreprise</h2>
                            <div class="icon">💼</div>
                        </div>
                    </div>
                    <!-- <div class="col-md-4">
                        <p class="task">5/5 tâches</p>
                    </div> -->
                </div>
            </div>

            <div class="cardDossier contrat clickable">
                <div class="row">
                    <div class="col-md-8">
                        <div class="divcol">
                            <h2 class="h2">Contrat</h2>
                            <div class="icon">📄</div>
                        </div>
                    </div>
                    <!-- <div class="col-md-4">
                        <p class="task">5/5 tâches</p>
                    </div> -->
                </div>
            </div>

            <div class="cardDossier comptabilite clickable">
                <div class="row">
                    <div class="col-md-8">
                        <div class="divcol">
                            <h2 class="h2">Comptabilite</h2>
                            <div class="icon">🧮</div>
                        </div>
                    </div>
                    <!-- <div class="col-md-4">
                        <p class="task">5/5 tâches</p>
                    </div> -->
                </div>
            </div>
        </section>
        <div id="section1" class="divcolmain  ecole" style="display: none;">
            <section class="document-status">

                <div class="status-card">
                        <div class="divcolel">          
                            <div class="documents">
                                <label class="control-label">Dénomination du CFA responsable</label>
                                    <ul>
                                        <input  type="hidden"  id="idformation" value="<?= $items->idformation?>">
                                        <li><?=  empty($formations)?StringHelper::isEmpty("") : $formations->nomF?></li>
                                    </ul>
                            </div>
                            <div class="documents">
                                <label class="control-label">Diplôme  ou  titre visé par l’apprenti</label>
                                    <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :$formations->diplomeF?></li>
                                    </ul>
                            </div>
                            <div class="documents">
                                <label class="control-label"> Intitulé précis du titre visé par l’apprenti</label>
                                        <ul>
                                        <li><?=empty($formations)?StringHelper::isEmpty("") :StringHelper::isEmpty($formations->intituleF) ?></li>
                                    </ul>
                            </div>

                            <div class="documents">
                                <label class="control-label" style="font-size:12.5px;">le CFA responsable est le lieu de formation principal</label>
                                    <ul>
                                        <li><?=empty($formations)?StringHelper::isEmpty("") :StringHelper::isEmpty( $formations->responsableF) ?></li>
                                    </ul>
                            </div>       
                        </div>
                </div>

                <div class="status-card">
                        <div class="divcolel">
                           <div class="documents">
                                <label class="control-label">Numéro UAI du CFA</label>
                                    <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :$formations->numeroF?></li>
                                    </ul>
                            </div>
                        
                            <div class="documents">
                                <label class="control-label">CFA d’entreprise </label>
                                        <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :StringHelper::isEmpty($formations->entrepriseF)?></li>
                                    </ul>
                            </div>
                        
                            <div class="documents">
                                <label class="control-label">Code RNCP  </label>
                                    <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :$formations->rnF?></li>
                                    </ul>
                            </div>

                            <div class="documents">
                                <label class="control-label"> Numéro SIRET CFA  </label>
                                    <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :$formations->siretF?></li>
                                    </ul>
                            </div>

                            <div class="documents">
                                <label class="control-label">Code du diplôme   </label>
                                    <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :$formations->codeF?></li>
                                    </ul>
                            </div>

                            <div class="documents">
                                <label class="control-label">Prix formation   </label>
                                    <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :$formations->prix?></li>
                                    </ul>
                            </div>
                        </div>
                </div>


                <div class="status-card">
                            <div>  <p style="margin-left:15px; "   class=" text-left">Adresse du CFA responsable  </p></div>
                        <div class="divcolel">
                            <div class="documents">
                                    <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :StringHelper::isEmpty($formations->rueF)?></li>
                                    </ul>
                            </div>
                            <div class="documents">
                                    <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :$formations->voieF?></li>
                                    </ul>
                            </div>
                            <div class="documents">
                                    <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :StringHelper::isEmpty($formations->complementF)?></li>
                                    </ul>
                            </div>
                            <div class="documents">
                                    <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :$formations->postalF?></li>
                                    </ul>
                            </div>
                            <div class="documents">
                                    <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :$formations->communeF?></li>
                                    </ul>
                            </div>

                            <div class="documents">
                                    <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :$formations->emailF?></li>
                                    </ul>
                            </div>
                                  
                                
                        </div>
                </div>

                <div class="status-card">
                        <div>  <p style="margin-left:15px; "   class=" text-left">Organisation de la formation en CFA  </p></div>
                        <div class="divcolel">
                            <div class="documents">
                            <label class="control-label"> Date prévue de fin des épreuves ou examens</label>
                                        <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :$formations->prevuO?></li>
                                    </ul>
                            </div>
    
                            <div  class="documents">
                            <label class="control-label"> Date de début de formation </label>
                                    <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :$formations->debutO?></li>
                                    </ul>
                            </div>
                        
                            <div  class="documents">
                            <label class="control-label">Durée de la formation(heures)</label>
                                    <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :$formations->dureO?></li>
                                    </ul>
                            </div>
                        </div>
                </div>

                <div class="status-card">
                        <div> <p style="margin-left:15px; "class=" text-left" >Lieu principal de réalisation de la formation si différent du CFA responsable     </p></div>
                        <div class="divcolel">
                            <div class="documents">
                            <label class="control-label">Dénomination du lieu de formation principal      </label>
                                    <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :StringHelper::isEmpty($formations->nomO)?></li>
                                    </ul>
                            </div>
                        
                            <div class="documents">
                                <label class="control-label">Numéro UAI   </label>
                                <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :StringHelper::isEmpty($formations->numeroO)?></li>
                                    </ul>
                            </div>
                        
                            <div class="documents">
                            <label class="control-label">Numéro SIRET     </label>
                                    <ul>
                                    <li><?= empty($formations)?StringHelper::isEmpty("") :StringHelper::isEmpty($formations->siretO)?></li>
                                </ul>
                            </div>
    
                        </div>
                </div>

                <div class="status-card">
                            <div>  <p style="margin-left:15px; "   class=" text-left">Adresse du lieu de formation principal   </p></div>
                        <div class="divcolel">
                            <div class="documents">
                                    <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :StringHelper::isEmpty($formations->rueO)?></li>
                                    </ul>
                            </div>
                            <div class="documents">
                                    <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :StringHelper::isEmpty($formations->voieO)?></li>
                                    </ul>
                            </div>
                            <div class="documents">
                                    <ul>
                                        <li><?=empty($formations)?StringHelper::isEmpty("") :StringHelper::isEmpty( $formations->complementO)?></li>
                                    </ul>
                            </div>
                            <div class="documents">
                                    <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :StringHelper::isEmpty($formations->postalO)?></li>
                                    </ul>
                            </div>
                            <div class="documents">
                                    <ul>
                                        <li><?= empty($formations)?StringHelper::isEmpty("") :StringHelper::isEmpty($formations->communeO)?></li>
                                    </ul>
                            </div>
                                  
                                
                        </div>
                </div>



            </section>

            
            <aside class="documents" style="background-color: #153C4A; border: 2px solid #153C4A;">
                <h3 style=" text-align: center;color:#fff;">Actions</h3>
                <button class="<?= empty($items->nomA) || empty($items->modeC) ? 'secondary-button': 'secondarys-button';?>"  id="selectActionSignature" data-toggle="tooltip"   
                <?= empty($items->nomA)|| empty($items->modeC)? 'disabled': '';?>
                data-original-title="<?= empty($items->nomA)|| empty($items->modeC)? "Vous devez d'abord Remplir les informations de l'Apprenti(e) et de son Contrat": 'Signer le cerfa';?>"  
                <?= empty($items->nomA)|| empty($items->modeC)? 'disabled': '';?>
                >Signer le cerfa</button>


                <button class="<?=empty($items->conventionOpco)? 'secondary-button': 'secondarys-button';?>"  id="selectActionSignatureConvention"
                data-toggle="tooltip" <?= empty($items->conventionOpco)? 'disabled': '';?>
                data-original-title="<?= empty($items->conventionOpco)? "Vous devez d'abord Remplir les informations de la convention": 'Signer la convention';?>"
                >Signer la convention </button>

                <button class="<?=!empty($items->signatureEmployeur)? 'secondary-button': 'secondarys-button';?>"  id="changeFormation" 
                data-toggle="tooltip" <?= !empty($items->signatureEmployeur)? 'disabled': '';?>
                data-original-title="<?= !empty($items->signatureEmployeur)? "Vous ne pouvez plus changer la formation car l'entreprise  à déjà effectuer la signature du dosier": "Changer la formation";?>"
                >Changer la formation </button>

            </aside>
        </div>

        <div id="section2" class="divcolmain  etudiant"  style="display: none;">

                <section class="document-status">

                        <div class="status-card">
                            <div class="divcolel">
                            
                                    <div class="documents">
                                        <label class="control-label">Nom de naissance de l’apprenti(e) <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->nomA)?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="text" id="nomA" name="nomA"  class="form-control input"  value="<?= $items->nomA?>" required>
                                                </div>
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">Nom d’usage : </label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->nomuA)?></li>
                                                <div class="form-group"  style="display: none;">
                                                  <input type="text" id="nomuA" name="nomuA"  class="form-control input"  value="<?= $items->nomuA?>" required>
                                                </div>
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">Le premier prénom <b style="display: none;">*</b>  </label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->prenomA)?></li>
                                                <div class="form-group"  style="display: none;">
                                                  <input type="text" id="prenomA" name="prenomA"  class="form-control input" value="<?= $items->prenomA?>" required>
                                                </div>
                                            </ul>
                                    </div>

                                

                                    <div class="documents">
                                        <label class="control-label">Date de naissance<b style="display: none;">*</b>   </label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->naissanceA)?></li>
                                                <div class="form-group"  style="display: none;">
                                                  <input type="date" id="naissanceA" name="naissanceA"  class="form-control input" value="<?= $items->naissanceA?>"  required>
                                                </div>
                                            </ul>
                                    </div>

                                    <div class="documents">
                                        <label class="control-label">Email  <b style="display: none;">*</b> </label>
                                            <ul>
                                                <li><?= $items->emailA?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="text" id="emailA" name="emailA"  class="form-control input" value="<?= $items->emailA?>" disabled >
                                                </div>
                                            </ul>
                                    </div>
                                    
                            </div>
                        </div>


                        <div class="status-card">
                            <div class="divcolel">
                                <div class="documents">
                                        <label class="control-label">Département de naissance <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->departementA)?></li>
                                                <div class="form-group"  style="display: none;">
                                                    <select id="departementA" name="departementA" class="form-control input"  value="<?= $items->departementA?>" required>
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
                                                        <option value="99">99 - Etranger</option>
                                                    </select>
                                                </div>
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">Commune de naissance <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->communeNA)?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="text" id="communeNA" name="communeNA"  class="form-control input"  value="<?= $items->communeNA?>" required>
                                                </div>
                                            </ul>
                                    </div>
                                    <div class="documents">
                                        <label class="control-label">Sexe <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->sexeA)?></li>
                                                <div class="form-group"  style="display: none;">
                                                    <select  id="sexeA"  name="sexeA" class="form-control input" value="<?= $items->sexeA?>"required>
                                                        <option value="">__</option>
                                                        <option value="M">M</option>
                                                        <option value="F">F</option>
                                                    </select>
                                                </div>
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">Nationalité  <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->nationaliteA)?></li>
                                                <div class="form-group"  style="display: none;">
                                                    <select id="nationaliteA" name="nationaliteA" class="form-control input"  value="<?= $items->nationaliteA?>" required>
                                                        <option value="">_______</option>
                                                        <option value="1">1 : Française</option>
                                                        <option value="2">2 : Union Européenne</option>
                                                        <option value="3">3 : Étranger hors Union Européenne</option>
                                                    </select>
                                                </div>
                                            </ul>
                                    </div>

                                    <div class="documents">
                                        <label class="control-label">Situation avant ce contrat <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->situationA)?></li>
                                                <div class="form-group"  style="display: none;">
                                                     <select id="situationA" name="situationA" class="form-control input" value="<?= $items->situationA?>" required>
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
                                            </ul>
                                    </div>

                                    <div class="documents">
                                        <label class="control-label">Régime social  <b style="display: none;">*</b> </label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->regimeA)?></li>
                                                <div class="form-group"  style="display: none;">
                                                     <select id="regimeA" name="regimeA" class="form-control input" value=" <?=$items->regimeA?>"required>
                                                        <option value="">_______</option>
                                                        <option value="1">1 : MSA</option>
                                                        <option value="2">2 : URSSAF</option>
                                                    </select>
                                                </div>
                                            </ul>
                                    </div>
                            </div>
                        </div>

                        <div class="status-card">
                            <div class="divcolel">
                                <div class="documents">
                                        <label class="control-label">Dernier diplôme ou titre préparé <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->titrePA)?></li>
                                                <div class="form-group"  style="display: none;">
                                                     <select id="titrePA" name="titrePA" class="form-control input" value="<?= $items->titrePA?>" required>
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
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">Dernière classe <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->derniereCA)?></li>
                                                <div class="form-group"  style="display: none;">
                                                    <select id="derniereCA" name="derniereCA" class="form-control input" value="<?= $items->derniereCA?>"required>
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
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">Numéro sécutite Sociale de l’apprenti(e)   <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->securiteA)?></li>
                                                <div class="form-group"  style="display: none;">
                                                    <input type="number" id="securiteA" name="securiteA" value="<?= $items->situationA?>" class="form-control input" minlength="13" maxlength="15" required>
                                                    <small class="form-text text-muted"> Le numéro de sécurité sociale de l'apprenti doit contenir entre 13 et 15  caractères</small>
                                            </ul>
                                    </div>

                                    <div class="documents">
                                        <label class="control-label">Intitulé précis du dernier diplôme ou titre préparé <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->intituleA)?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="text" id="intituleA"   name="intituleA" value="<?=$items->intituleA?>" class="form-control input" required>
                                                </div>
                                            </ul>
                                    </div>

                                    <div class="documents">
                                        <label class="control-label">Diplôme ou titre le plus élevé obtenu   <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->titreOA)?></li> 
                                                <div class="form-group"  style="display: none;">
                                                    <select id="titreOA" name="titreOA" class="form-control input" value="<?= $items->titreOA?>" required>
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
                                            </ul>
                                    </div>
                            </div>
                        </div>

                        <div class="status-card">
                            <div class="divcolel">
                                <div class="documents">
                                        <label class="control-label">Déclare être inscrit sur la liste des sportifs de haut  niveau <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= empty($items->declareSA)?StringHelper::isEmpty($items->declareSA) : $items->declareSA?></li>
                                                <div class="form-group"  style="display: none;">
                                                    <select  id="declareSA"  name="declareSA" value="<?= $items->declareSA?>" class="form-control input">
                                                        <option value="">__</option>
                                                        <option value="oui">Oui</option>
                                                        <option value="non">Non</option>
                                                    </select>
                                                </div>
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">Déclare bénéficier de la reconnaissance travailleur handicapé <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= empty($items->declareHA)?StringHelper::isEmpty($items->declareHA) : $items->declareHA?></li>
                                                <div class="form-group"  style="display: none;">
                                                    <select  id="declareHA" name="declareHA" value="<?= $items->declareHA?>"class="form-control input">
                                                        <option value="">__</option>
                                                        <option value="oui">Oui</option>
                                                        <option value="non">Non</option>
                                                    </select>
                                                </div>
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">Déclare avoir un projet de création ou de reprise d’entreprise <b style="display: none;">*</b> </label>
                                            <ul>
                                                <li><?= empty($items->declareRA)?StringHelper::isEmpty($items->declareRA) :$items->declareRA ?></li>
                                                <div class="form-group"  style="display: none;">
                                                    <select  id="declareRA" id="declareRA" value="<?= $items->declareRA?>" class="form-control input">
                                                        <option value="">__</option>
                                                        <option value="oui">oui</option>
                                                        <option value="non">Non</option>
                                                    </select>
                                                </div>
                                            </ul>
                                    </div>    
                            </div>
                        </div>

                        <div class="status-card">
                                <div>  <p style="margin-left:15px; "   class=" text-left">Adresse de l’apprenti(e)  </p></div>
                            <div class="divcolel">
                                <div class="documents">
                                        <ul>
                                            <li><?= StringHelper::isEmpty($items->rueA)?></li>
                                            <div class="form-group"  style="display: none;">
                                               <input type="text" id="rueA"   name="rueA" class="form-control input" value="<?=$items->rueA?>"  placeholder="N°  " >
                                            </div>
                                        </ul>
                                </div>
                                <div class="documents">
                                        <ul>
                                            <li><?= StringHelper::isEmpty($items->voieA)?></li>
                                            <div class="form-group"  style="display: none;">
                                               <input type="text" id="voieA"  name="voieA" class="form-control input"   value="<?= $items->voieA?>" placeholder="Voie  *"  required>
                                            </div>
                                        </ul>
                                </div>
                                <div class="documents">
                                        <ul>
                                            <li><?= StringHelper::isEmpty($items->complementA)?></li>
                                            <div class="form-group"  style="display: none;">
                                               <input type="text" id="complementA"  name="complementA" value="<?= $items->complementA?>" class="form-control input" minlength="0" maxlength="115" placeholder="Complement" >   
                                            </div>
                                        </ul>
                                </div>
                                <div class="documents">
                                        <ul>
                                            <li><?= StringHelper::isEmpty($items->postalA)?></li>
                                            <div class="form-group"  style="display: none;">
                                                <input type="number" id="postalA" name="postalA" class="form-control input" value="<?= $items->postalA?>" placeholder="Code Postal *" required>
                                                <small class="form-text text-muted">Veuillez entrer exactement 5 chiffres</small>
                                            </div>
                                        </ul>
                                </div>
                                <div class="documents">
                                        <ul>
                                            <li><?=StringHelper::isEmpty($items->communeA)?></li>
                                            <div class="form-group"  style="display: none;">
                                               <input type="text" id="communeA"  name="communeA" value="<?= $items->communeA?>" class="form-control input"  minlength="0" maxlength="80"  placeholder="Commune *"  required>
                                            </div>
                                        </ul>
                                </div>

                                <div class="documents">
                                        <ul>
                                            <li><?= StringHelper::isEmpty($items->numeroA)?></li>
                                            <div class="form-group"  style="display: none;">
                                                <input type="number" id="numeroA"  name="numeroA" value="<?= $items->numeroA?>"  class="form-control input" pattern="\d{10}" placeholder="Téléphone *"  required>
                                                <small class="form-text text-muted">Veuillez entrer exactement 10 chiffres</small>
                                            </div>
                                        </ul>
                                </div>
                                    
                                    
                            </div>
                        </div>

                        <div class="status-card">

                            <div><p class="mainColor text-left">Représentant légal (à renseigner si l’apprenti est mineur non  émancipé) </p> </div>

                            <div class="divcolel">
                                <div class="documents">
                                        <label class="control-label">Nom de naissance</label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->nomR)?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="text" id="nomR" name="nomR" value="<?= $items->nomR?>" class="form-control input" required>
                                                </div>
                                            </ul>
                                    </div>
                        
                                    <div class="documents">
                                        <label class="control-label">Prenom  </label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->prenomR)?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="text" id="prenomR" name="prenomR" value="<?= $items->prenomR?>" class="form-control input" required>
                                                </div>
                                            </ul>
                                    </div>
                        
                                    <div class="documents">
                                        <label class="control-label">Courriel  </label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->emailR)?></li>
                                                <div class="form-group"  style="display: none;">
                                                  <input type="email" id="emailR"  name="emailR" class="form-control input"  placeholder="Courriel" >
                                                </div>
                                            </ul>
                                    </div>
                            </div>
                        </div>

                        <div class="status-card">
                                <div>  <p style="margin-left:15px; "   class=" text-left">Adresse du représentant légal </p></div>
                            <div class="divcolel">
                                <div class="documents">
                                        <ul>
                                            <li><?= StringHelper::isEmpty($items->rueR)?></li>
                                            <div class="form-group"  style="display: none;">
                                               <input type="text" id="rueR"  name="rueR" class="form-control input" value="<?=$items->rueR?>" placeholder="N° " required>
                                            </div>
                                        </ul>
                                </div>
                                <div class="documents">
                                        <ul>
                                            <li><?= StringHelper::isEmpty($items->voieR)?></li>
                                            <div class="form-group"  style="display: none;">
                                                <input type="text" id="voieR"  name="voieR"  class="form-control input" value="<?= $items->voieR?>"  placeholder="Voie " required>
                                            </div>
                                        </ul>
                                </div>
                                <div class="documents">
                                        <ul>
                                            <li><?= StringHelper::isEmpty($items->complementR)?></li>
                                            <div class="form-group"  style="display: none;">
                                                <input type="text" id="complementR"  name="complementR" <?= $items->complementR?> class="form-control input" placeholder="Complement" required>
                                            </div>
                                        </ul>
                                </div>
                                <div class="documents">
                                        <ul>
                                            <li><?= StringHelper::isEmpty($items->postalR)?></li>
                                            <div class="form-group"  style="display: none;">
                                                <input type="number" id="postalR" value="<?= $items->postalR?>"  minlength="5" maxlength="5"  name="postalR" class="form-control input" placeholder="Code Postal " required>
                                                <small class="form-text text-muted">Veuillez entrer exactement 5 chiffres.</small>
                                            </div>
                                        </ul>
                                </div>
                                <div class="documents">
                                        <ul>
                                            <li><?= StringHelper::isEmpty($items->communeR)?></li>
                                            <div class="form-group"  style="display: none;">
                                               <input type="text" id="communeR" name="communeR"  value="<?= $items->communeR?>"  minlength="0" maxlength="80" class="form-control input"  placeholder="Commune " required>
                                            </div>
                                        </ul>
                                </div>

                            
                                    
                                    
                            </div>
                        </div>
                </section>

                <aside class="documents" style="background-color: #153C4A; border: 2px solid #153C4A;">
                    <h3 style=" text-align: center;color:#fff;">Actions</h3>
                    <button class="secondarys-button" id="update" data-toggle="tooltip"
                    data-original-title="Modifier les Informations de l'Apprenti(e)"
                    >Modifier</button>

                    <button class="secondary-button" id="delete" style="display: none;" data-toggle="tooltip"
                    data-original-title="Annuler les Modifications de l'Apprenti(e)"
                    >Annuler</button>
          
                    <button class="secondarys-button"  id="sendformEtudiant" data-toggle="tooltip"
                     data-original-title="Envoyer le formulaire cerfa"
                    >Envoyer le formulaire cerfa</button>

                    <button class="<?= empty($items->nomA)|| empty($items->modeC)? 'secondary-button': 'secondarys-button';?>"  id="sendformSignatureEtudiant"  data-toggle="tooltip"
                    data-original-title="<?= empty($items->nomA)|| empty($items->modeC)? "Vous devez D'abord Remplir les informations de l'apprenti et de son Contrat": "Signer le cerfa par l'étudiant";?>"
                    <?= empty($items->nomA)|| empty($items->modeC)? 'disabled': '';?>
                    >Signer le cerfa par l'étudiant</button>

                    <button class="<?= empty($items->nomA)|| empty($items->modeC)? 'secondary-button': 'secondarys-button';?>"  id="sendformSignatureEtudiantRepresentant"  data-toggle="tooltip"
                    data-original-title="<?= empty($items->nomA)|| empty($items->modeC)? "Vous devez D'abord Remplir les informations de l'apprenti et de son Contrat": "Signer le cerfa par représentant légal";?>"
                    <?= empty($items->nomA)|| empty($items->modeC)? 'disabled': '';?>
                    >Signer le cerfa par représentant légal</button>
                </aside>
           
        </div>

        <div id="section3" class="divcolmain  entreprise" style="display: none;">
            <section class="document-status">
                <div class="status-card">
                        <div class="divcolel">          
                            <div class="documents">
                                <label class="control-label">Dénomination</label>
                                    <ul>
                                        <input  type="hidden"  id="idemployeur" value="<?= $items->idemployeur?>">
                                        <li><?= StringHelper::isEmpty($employeurs->nomE) ?></li>
                                    </ul>
                            </div>
                            <div class="documents">
                                <label class="control-label">Type d’employeur</label>
                                    <ul>
                                        <li><?= StringHelper::isEmpty($employeurs->typeE) ?></li>
                                    </ul>
                            </div>
                            <div class="documents">
                                <label class="control-label"> Employeur spécifique </label>
                                        <ul>
                                        <li><?= StringHelper::isEmpty($employeurs->specifiqueE) ?> </li>
                                    </ul>
                            </div>

                            <div class="documents">
                                <label class="control-label" style="font-size:12.5px;">Effectif</label>
                                    <ul>
                                        <li><?= StringHelper::isEmpty($employeurs->totalE) ?></li>
                                    </ul>
                            </div>  
                            
                            <div class="documents">
                                <label class="control-label">Adresse Email</label>
                                        <ul>
                                        <li><?= StringHelper::isEmpty($employeurs->emailE) ?></li>
                                    </ul>
                            </div>

                          
                        </div>
                </div>

                <div class="status-card">
                        <div class="divcolel">   

                            <div class="documents">
                                <label class="control-label">Téléphone</label>
                                        <ul>
                                        <li><?=  StringHelper::isEmpty($employeurs->numeroE)?></li>
                                    </ul>
                            </div>
                            <div class="documents">
                                <label class="control-label">Numéro SIRET </label>
                                    <ul>
                                        <li><?=  StringHelper::isEmpty($employeurs->siretE)?></li>
                                    </ul>
                            </div>
                            <div class="documents">
                                <label class="control-label">Code activité(NAF)</label>
                                    <ul>
                                        <li><?= StringHelper::isEmpty($employeurs->codeaE)?></li>
                                    </ul>
                            </div>
                            <div class="documents">
                                <label class="control-label">Code IDCC</label>
                                        <ul>
                                        <li><?= StringHelper::isEmpty($employeurs->codeiE)?> </li>
                                    </ul>
                            </div>

                            <div class="documents">
                                <label class="control-label" style="font-size:12.5px;">Opco responsable de l'entreprise</label>
                                    <ul>
                                        <li><?= empty($opco)?StringHelper::isEmpty("") : $opco->nom ?> </li>
                                    </ul>
                            </div>       
                        </div>
                </div>

                <div class="status-card">
                            <div>  <p style="margin-left:15px; "   class=" text-left">Adresse de l’établissement d’exécution du contrat </p></div>
                        <div class="divcolel">
                            <div class="documents">
                                    <ul>
                                        <li><?=  StringHelper::isEmpty($employeurs->rueE)?></li>
                                    </ul>
                            </div>
                            <div class="documents">
                                    <ul>
                                        <li><?= StringHelper::isEmpty($employeurs->voieE)?></li>
                                    </ul>
                            </div>
                            <div class="documents">
                                    <ul>
                                        <li><?= StringHelper::isEmpty($employeurs->complementE)?> </li>
                                    </ul>
                            </div>
                            <div class="documents">
                                    <ul>
                                        <li><?= StringHelper::isEmpty($employeurs->postalE) ?></li>
                                    </ul>
                            </div>
                            <div class="documents">
                                    <ul>
                                        <li><?=  StringHelper::isEmpty($employeurs->communeE)?></li>
                                    </ul>
                            </div>
                                  
                                
                        </div>
                </div>

            </section>
            <aside class="documents" style="background-color: #153C4A; border: 2px solid #153C4A;">
                <h3 style=" text-align: center;color:#fff;">Actions</h3>
                <!-- lors de la modification d'un cerfa il faut update sa signature et autre document -->
                <!-- <button class="upload-button">Modifier</button>  -->
                <button class="secondarys-button"  id="sendformEntreprise" data-toggle="tooltip"
                 data-original-title="Envoyer le formulaire cerfa"
                >Envoyer le formulaire cerfa</button>

                   <!-- new -->
                   <button class="secondarys-button"  id="sendformContratEntreprise" data-toggle="tooltip"
                 data-original-title="Envoyer le formulaire pour remplir le contrat"
                >Envoyer le formulaire pour remplir le contrat</button>


                <!-- <button class="primary-button">signer la convention</button> -->
                <button class="<?= empty($items->nomA)|| empty($items->modeC)? 'secondary-button': 'secondarys-button';?>"  id="sendformSignatureEntreprise" data-toggle="tooltip"
                data-original-title="<?= empty($items->nomA)|| empty($items->modeC)? "Vous devez D'abord Remplir les informations de l'Apprenti et de son Contrat": 'Signer le cerfa';?>"
                <?= empty($items->nomA)|| empty($items->modeC)? 'disabled': '';?>
                >Signer le cerfa </button>

                <button class="<?=empty($items->conventionOpco)? 'secondary-button': 'secondarys-button';?>"  id="sendformSignatureConventionEntreprise" 
                data-toggle="tooltip" <?= empty($items->conventionOpco)? 'disabled': '';?>
                data-original-title="<?= empty($items->conventionOpco)? "Vous devez D'abord Remplir les informations de la convention": 'Signer la convention';?>"
                >Signer la convention </button>

                <button class="<?=!empty($items->signatureEmployeur)? 'secondary-button': 'secondarys-button';?>"  id="changeEntreprise" 
                data-toggle="tooltip" <?= !empty($items->signatureEmployeur)? '': '';?>
                data-original-title="<?= !empty($items->signatureEmployeur)? "Vous ne pouvez plus changer l'entreprise car elle a deja effectuer la signature du dosier": "Changer l'entreprise";?>"
                >Changer l'entreprise </button>

                <!-- <ul>
                    <li>CERFA.pdf</li>
                    <li>CERFAsigné.pdf</li>
                </ul> -->
            </aside>
        </div>

        <div id="section4" class="divcolmain  contrat" style="display: none;">
            <section class="document-status">
                        <div class="status-card">
                            <div class="divcolel">
                            
                                    <div class="documents">
                                        <label class="control-label">Mode Contractuel d'apprentissage<b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->modeC)?></li>
                                                <div class="form-group"  style="display: none;">
                                                    <select id="modeC" name="modeC" class="form-control" value="<?= $items->modeC?>" required style="border-radius: 5px;">
                                                        <option value="">______</option>
                                                        <option value="1">1 : À durée limitée</option>
                                                        <option value="2">2 : Dans le cadre d’un CDI</option>
                                                        <option value="3">3 : Entreprise de travail temporaire</option>
                                                        <option value="4">4 : Activités saisonnières à deux employeurs</option>
                                                    </select>
                                                </div>
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">Travail sur machines dangereuses ou exposition à des risques particuliers<b style="display: none;">*</b> </label>
                                            <ul>
                                                <li><?=empty($items->travailC)?StringHelper::isEmpty($items->travailC) : $items->travailC?></li>
                                                <div class="form-group"  style="display: none;">
                                                <select  id="travailC" name="travailC" class="form-control" value="<?= $items->travailC?>" required style="border-radius: 5px;">
                                                    <option value="">__</option>
                                                    <option value="oui">Oui</option>
                                                    <option value="non">Non</option>
                                                    </select>
                                                </div>
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">Type de dérogation : à renseigner si une dérogation existe pour ce contrat </label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->derogationC)?></li>
                                                <div class="form-group"  style="display: none;">
                                                    <select id="derogationC" name="derogationC" value="<?= $items->derogationC?>"  class="form-control" style="border-radius: 5px;">
                                                        <option value="">______________</option>
                                                        <option value="11">11 : Age de l’apprenti inférieur à 16 ans</option>
                                                        <option value="12">12 : Age supérieur à 29 ans : cas spécifiques prévus dans le code du travail</option>
                                                        <option value="21">21 : Réduction de la durée du contrat ou de la période d’apprentissage</option>
                                                        <option value="22">22 : Allongement de la durée du contrat ou de la période d’apprentissage</option>
                                                        <option value="50">50 : Cumul de dérogations</option>
                                                        <option value="60">60 : Autre dérogation</option>
                                                    </select>
                                                </div>
                                            </ul>
                                    </div>

                                    <div class="documents">
                                        <label class="control-label">Numéro du contrat précédent ou du contrat sur lequel porte l’avenant </label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->numeroC)?></li>
                                                <div class="form-group"  style="display: none;">
                                                  <input type="text" id="numeroC"  name="numeroC" value="<?= $items->numeroC?>" class="form-control" style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>

                                

                                    <div class="documents">
                                        <label class="control-label">Date de conclusion : (Date de signature du présent contrat) <b style="display: none;">*</b>  </label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->conclusionC)?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="date" id="conclusionC"  name="conclusionC"  value="<?= $items->conclusionC?>" class="form-control" required style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>

                                    <div class="documents">
                                        <label class="control-label">Date de début de formation pratique  chez l’employeur <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->debutC)?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="date" id="debutC" name="debutC"  value="<?= $items->debutC?>"  required class="form-control" style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>
                                    
                            </div>
                        </div>


                        <div class="status-card">
                            <div class="divcolel">
                            
                                    <div class="documents">
                                        <label class="control-label">Date de fin du contrat ou de la période d’apprentissage  <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->finC)?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="date" id="finC" value="<?= $items->finC?>" name="finC" class="form-control" required style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">Si avenant, date d’effet</label>
                                            <ul>
                                                <li><?=StringHelper::isEmpty($items->avenantC)?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="date" id="avenantC"  name="avenantC" value="<?= $items->avenantC?>"  class="form-control" style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">Date de début d’exécution du contrat <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->executionC)?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="date" id="executionC"  name="executionC"  value="<?= $items->executionC?>" class="form-control" style="border-radius: 5px;" required>
                                                </div>
                                            </ul>
                                    </div>

                                

                                    <div class="documents">
                                        <label class="control-label">Durée hebdomadaire du travail:(En heures)  <b style="display: none;">*</b> </label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->dureC)?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="number" id="dureC" name="dureC"  value="<?= $items->dureC?>" class="form-control" required style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>

                                    <div class="documents">
                                        <label class="control-label">Durée hebdomadaire du travail:(En minutes) <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->dureCM)?></li>
                                                <div class="form-group"  style="display: none;">
                                                  <input type="number" id="dureCM" name="dureCM"  value="<?= $items->dureCM?>" required class="form-control" style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>

                                    <div class="documents">
                                        <label class="control-label">Type de contrat ou d’avenant <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->typeC)?></li>
                                                <div class="form-group"  style="display: none;">
                                                    <select id="typeC" name="typeC" class="form-control" value="<?= $items->typeC?>" required style="border-radius: 5px;">
                                                        <option value="">__________</option>
                                                        <optgroup label="Contrat initial">
                                                            <option value="11">Premier contrat d’apprentissage de l’apprenti(e)</option>
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
                                            </ul>
                                    </div>
                                    
                            </div>
                        </div>


                        <div class="status-card">
                            <div>  <p style="margin-left:7px; "   class=" text-left">Rémunération </p></div>
                            <div class="divcolel">
                                    <div class="documents">
                                        <label class="control-label">1er année <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->rdC)?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="date" id="rdC" name="rdC" class="form-control" value="<?= $items->rdC?>" placeholder="du" required style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">.</label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->raC)?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="date" id="raC" name="raC" value="<?= $items->raC?>" class="form-control" placeholder="au" required style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">.</label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->rpC)?></li>
                                                <div class="form-group"  style="display: none;">
                                                  <input type="number" id="rpC" name="rpC" class="form-control" value="<?= $items->rpC?>" placeholder="pourcentage" required style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>

                                

                                    <div class="documents">
                                        <label class="control-label">.</label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->rsC)?></li>
                                                <div class="form-group"  style="display: none;">
                                                    <select id="rsC" name="rsC" value="<?= $items->rsC?>" class="form-control" style="border-radius: 5px;">
                                                        <option value="SMIC">salaire minimum interprofessionnel de croissance</option>
                                                        <option value="SMC">salaire minimum conventionnel</option>
                                                    </select>
                                                </div>
                                            </ul>
                                    </div>

                                  
                                    
                            </div>

                            <div class="divcolel">
                                    <div class="documents">
                                        <label class="control-label">2eme année</label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->rdC1)?></li>
                                                <div class="form-group"  style="display: none;">
                                                  <input type="date" id="rdC1" name="rdC1" value="<?= $items->rdC1?>" class="form-control" placeholder="du" style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">.</label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->raC1)?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="date" id="raC1" name="raC1"  <?= $items->raC1?> class="form-control" placeholder="au" style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">.</label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->rpC1)?></li>
                                                <div class="form-group"  style="display: none;">
                                                  <input type="number" id="rpC1" name="rpC1" class="form-control" value="<?= $items->rpC1?>" placeholder="pourcentage" style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>

                                

                                    <div class="documents">
                                        <label class="control-label">.</label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->rsC1)?></li>
                                                <div class="form-group"  style="display: none;">
                                                <select id="rsC1" name="rsC1" value="<?= $items->rsC1?>" class="form-control" style="border-radius: 5px;">
                                                <option value="">____________</option>
                                                <option value="SMIC">salaire minimum interprofessionnel de croissance</option>
                                                    <option value="SMC">salaire minimum conventionnel</option>
                                                </select>
                                                </div>
                                            </ul>
                                    </div>

                                  
                                    
                            </div>

                            <div class="divcolel">
                                    <div class="documents">
                                        <label class="control-label">3eme année</label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->rdC2)?></li>
                                                <div class="form-group"  style="display: none;">
                                                  <input type="date" id="rdC2" name="rdC2" <?= $items->rdC2?> class="form-control" placeholder="du" style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">.</label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->raC2)?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="date" id="raC2" name="raC2" <?= $items->raC2?> class="form-control" placeholder="au" style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">.</label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->rpC2)?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="number" id="rpC2" name="rpC2" value="<?= $items->rpC2?>" class="form-control" placeholder="pourcentage" style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>

                                

                                    <div class="documents">
                                        <label class="control-label">.</label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->rsC2)?></li>
                                                <div class="form-group"  style="display: none;">
                                                <select id="rsC2" name="rsC2"  value="<?= $items->rsC2?>" class="form-control" style="border-radius: 5px;">
                                                <option value="">____________</option>
                                                <option value="SMIC">salaire minimum interprofessionnel de croissance</option>
                                                    <option value="SMC">salaire minimum conventionnel</option>
                                                </select>
                                                </div>
                                            </ul>
                                    </div>
                            </div>

                           
                        </div>

                        <div class="status-card">
                            <div class="divcolel">
                            
                                    <div class="documents">
                                        <label class="control-label">Salaire brut mensuel à l’embauche <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->salaireC)?><b style="display: none;"></b></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="text" id="salaireC" name="salaireC" value="<?= $items->salaireC?>" class="form-control" style="border-radius: 5px;" required>
                                                </div>
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">Caisse de retraite complémentaire<b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->caisseC)?><b style="display: none;"></b></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="text" id="caisseC" name="caisseC" value="<?= $items->caisseC?>" class="form-control" style="border-radius: 5px;" required>
                                                </div>
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">Logement:€/mois </label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->logementC)?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="number" id="logementC" name="logementC" value="<?= $items->logementC?>" style="border-radius: 5px;" class="form-control" >
                                                </div>
                                            </ul>
                                    </div>

                                

                                    <div class="documents">
                                        <label class="control-label">Avantages en nature, le cas échéant:Nourriture:€/repas</label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->avantageC)?></li>
                                                <div class="form-group"  style="display: none;">
                                                  <input type="number" id="avantageC" name="avantageC" value="<?= $items->avantageC?>" style="border-radius: 5px;" class="form-control" >
                                                </div>
                                            </ul>
                                    </div>

                                    <div class="documents">
                                        <label class="control-label">Autre </label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->autreC)?></li>
                                                <div class="form-group"  style="display: none;">
                                                    <select  id="autreC" name="autreC"  value="<?= $items->autreC?>" style="border-radius: 5px;" class="form-control">
                                                        <option value="">__</option>
                                                        <option value="oui">Oui</option>
                                                        <option value="non">Non</option>
                                                    </select>
                                                </div>
                                            </ul>
                                    </div>

                                    <div class="documents">
                                        <label class="control-label"> Fait à  <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->lieuO)?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="text" id="lieuO" name="lieuO" class="form-control" value="<?= $items->lieuO?>" placeholder="lieude signature du contrat" style="border-radius: 5px;" required>
                                                </div>
                                            </ul>
                                    </div>

                                    <div class="documents">
                                        <label class="control-label"> entreprise privée ou public  <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= empty($items->priveO)?StringHelper::isEmpty($items->priveO) : $items->priveO?></li>
                                                <div class="form-group"  style="display: none;">
                                                    <select  id="priveO"  name="priveO" value="<?= $items->priveO?>" style="border-radius: 5px;" required class="form-control">
                                                        <option value="">__</option>
                                                        <option value="oui">privée</option>
                                                        <option value="non">public</option>
                                                    </select>
                                                </div>
                                            </ul>
                                    </div>
                                    
                            </div>
                        </div>

                        <div class="status-card">
                            <div>  <p class="mainColor text-left"> LE MAÎTRE D’APPRENTISSAGE</p></div>
                            <div><p style="margin-left:7px; "   class=" text-left">Maître d’apprentissage n°1   </p></div>
                            <div class="divcolel">
                            
                                    <div class="documents">
                                        <label class="control-label">Nom de naissance<b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->nomM)?></li>
                                                <div class="form-group"  style="display: none;">
                                                  <input type="text" id="nomM"   name="nomM" value="<?= $items->nomM?>" class="form-control" required style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">Prénom<b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->prenomM)?></li>
                                                <div class="form-group"  style="display: none;">
                                                  <input type="text" id="prenomM" name="prenomM" value="<?= $items->prenomM?>" class="form-control" required style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>
                                
                                    <div class="documents">
                                        <label class="control-label">Date de naissance <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->naissanceM)?></li>
                                                <div class="form-group"  style="display: none;">
                                                  <input type="date" id="naissanceM" name="naissanceM" value="<?= $items->naissanceM?>" class="form-control" required style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>

                                

                                    <div class="documents">
                                        <label class="control-label">NIR<b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->securiteM)?></li>
                                                <div class="form-group"  style="display: none;">
                                                    <input type="number" id="securiteM"  name="securiteM" value="<?= $items->securiteM?>" required class="form-control" style="border-radius: 5px;">
                                                    <small class="form-text text-muted"> Le numéro de sécurité sociale du premier maître de stage doit contenir entre 13 et 15  caractères</small>
                                                </div>
                                            </ul>
                                    </div>

                                    <div class="documents">
                                        <label class="control-label">Courriel <b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->emailM)?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="email" id="emailM" name="emailM" value="<?= $items->emailM?>" required class="form-control" style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>

                                    <div class="documents">
                                        <label class="control-label">Emploi occupé<b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->emploiM)?></li>
                                                <div class="form-group"  style="display: none;">
                                                   <input type="text" id="emploiM"  name="emploiM" value="<?= $items->emploiM?>" required class="form-control" style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>

                                    <div class="documents">
                                        <label class="control-label">Diplôme ou titre le plus élevé obtenu<b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->diplomeM)?></li>
                                                <div class="form-group"  style="display: none;">
                                                  <input type="text" id="diplomeM" name="diplomeM" value="<?= $items->diplomeM?>"  required class="form-control" style="border-radius: 5px;">
                                                </div>
                                            </ul>
                                    </div>

                                    <div class="documents">
                                        <label class="control-label">Niveau de diplôme ou titre le plus élevé obtenu<b style="display: none;">*</b></label>
                                            <ul>
                                                <li><?= StringHelper::isEmpty($items->niveauM)?></li>
                                                <div class="form-group"  style="display: none;">
                                                    <select id="niveauM" name="niveauM" class="form-control" value="<?= $items->niveauM?>" required style="border-radius: 5px;">
                                                        <option value="">________</option>
                                                        <option value="1">CAP, BEP</option>
                                                        <option value="2">Baccalauréat</option>
                                                        <option value="3">DEUG, BTS, DUT, DEUST</option>
                                                        <option value="4">Licence, Licence professionnelle, BUT, Maîtrise</option>
                                                        <option value="5">Master, DEA, DESS, Diplôme d'ingénieur</option>
                                                        <option value="6">Doctorat, Habilitation à diriger des recherches</option>
                                                    </select>
                                                </div>
                                            </ul>
                                    </div>
                                    
                            </div>

                            <div> <p style="margin-left:7px;  margin-top: 20px; "   class=" text-left">Maître d’apprentissage n°2   </p></div>
                            <div class="divcolel">
                                
                                <div class="documents">
                                    <label class="control-label">Nom de naissance</label>
                                        <ul>
                                            <li><?= StringHelper::isEmpty($items->nomM1)?></li>
                                            <div class="form-group"  style="display: none;">
                                            <input type="text" id="nomM1"   name="nomM1" value="<?= $items->nomM1?>" class="form-control"  style="border-radius: 5px;">
                                            </div>
                                        </ul>
                                </div>
                            
                                <div class="documents">
                                    <label class="control-label">Prénom</label>
                                        <ul>
                                            <li><?= StringHelper::isEmpty($items->prenomM1)?></li>
                                            <div class="form-group"  style="display: none;">
                                            <input type="text" id="prenomM1" name="prenomM1" value="<?= $items->prenomM1?>" class="form-control"  style="border-radius: 5px;">
                                            </div>
                                        </ul>
                                </div>
                            
                                <div class="documents">
                                    <label class="control-label">Date de naissance </label>
                                        <ul>
                                            <li><?= StringHelper::isEmpty($items->naissanceM1)?></li>
                                            <div class="form-group"  style="display: none;">
                                            <input type="date" id="naissanceM1" name="naissanceM1" value="<?= $items->naissanceM1?>" class="form-control"  style="border-radius: 5px;">
                                            </div>
                                        </ul>
                                </div>

                            

                                <div class="documents">
                                    <label class="control-label">NIR</label>
                                        <ul>
                                            <li><?= StringHelper::isEmpty($items->securiteM1)?></li>
                                            <div class="form-group"  style="display: none;">
                                                <input type="text" id="securiteM1"  name="securiteM1" value="<?= $items->securiteM1?>" class="form-control" style="border-radius: 5px;">
                                                <small class="form-text text-muted"> Le numéro de sécurité sociale du premier maître de stage doit contenir entre 13 et 15  caractères</small>
                                            </div>
                                        </ul>
                                </div>

                                <div class="documents">
                                    <label class="control-label">Courriel </label>
                                        <ul>
                                            <li><?= StringHelper::isEmpty($items->emailM1)?></li>
                                            <div class="form-group"  style="display: none;">
                                            <input type="email" id="emailM1" name="emailM1" value="<?= $items->emailM1?>" class="form-control" style="border-radius: 5px;">
                                            </div>
                                        </ul>
                                </div>

                                <div class="documents">
                                    <label class="control-label">Emploi occupé</label>
                                        <ul>
                                            <li><?= StringHelper::isEmpty($items->emploiM1)?></li>
                                            <div class="form-group"  style="display: none;">
                                            <input type="text" id="emploiM1"  name="emploiM1" value="<?=$items->emploiM1?>" class="form-control" style="border-radius: 5px;">
                                            </div>
                                        </ul>
                                </div>

                                <div class="documents">
                                    <label class="control-label">Diplôme ou titre le plus élevé obtenu</label>
                                        <ul>
                                            <li><?= StringHelper::isEmpty($items->diplomeM1)?></li>
                                            <div class="form-group"  style="display: none;">
                                            <input type="text" id="diplomeM1" name="diplomeM1" value="<?= $items->diplomeM1?>" class="form-control" style="border-radius: 5px;">
                                            </div>
                                        </ul>
                                </div>

                                <div class="documents">
                                    <label class="control-label">Niveau de diplôme ou titre le plus élevé obtenu</label>
                                        <ul>
                                            <li><?=StringHelper::isEmpty($items->niveauM1)?></li>
                                            <div class="form-group"  style="display: none;">
                                                <select id="niveauM1" name="niveauM1" value="<?= $items->niveauM1?>" class="form-control"  style="border-radius: 5px;">
                                                    <option value="">________</option>
                                                    <option value="1">CAP, BEP</option>
                                                    <option value="2">Baccalauréat</option>
                                                    <option value="3">DEUG, BTS, DUT, DEUST</option>
                                                    <option value="4">Licence, Licence professionnelle, BUT, Maîtrise</option>
                                                    <option value="5">Master, DEA, DESS, Diplôme d'ingénieur</option>
                                                    <option value="6">Doctorat, Habilitation à diriger des recherches</option>
                                                </select>
                                            </div>
                                        </ul>
                                </div>
                                
                            </div>
                        </div>
            </section>

            <aside class="documents" style="background-color: #153C4A; border: 2px solid #153C4A;">
                    <h3 style=" text-align: center;color:#fff;">Actions</h3>
                    <button class="secondarys-button" id="updateContrat"
                     data-original-title="Modifier les Informations sur le contrat l'Apprenti(e)"
                     data-toggle="tooltip"
                    >Modifier</button>

                    <button class="secondary-button" id="deleteContrat" style="display: none;"
                     data-toggle="tooltip"  data-original-title="Annuler les Modifications sur le contrat de l'Apprenti(e)"
                    >Annuler</button>
            </aside>
        </div>

        <div id="section5" class="divcolmain" >
             <aside  style="background-color: #f7f9fc; border: 2px solid #153C4A;">
                <h3 style=" text-align: center;text-decoration: underline;">LISTES DES CONTACTS</h3>
              
                <label class="row" style="margin-top:20px; margin-left:8px;"> <sapn class="icon">💼 </sapn> <span style="font-size: 25px;">Entreprise</span></label>
                         <div class="divcolel" style="margin-top:10px;">
                       
                            <div class="documents">
                                <label class="control-label"><span style="text-decoration: underline;">Dénomination</span> : <?= StringHelper::isEmpty($employeurs->nomE)?></label>
                            </div>    
                        
                        </div>  
                        <div class="divcolel">
                                <div class="documents">
                                    <label class="control-label"><span style="text-decoration: underline;">Email</span> : <?= $employeurs->emailE?></label>
                                </div>    
                                    
                        </div>
                        <div class="divcolel">
                                <div class="documents">
                                    <label class="control-label"><span style="text-decoration: underline;">Numéro</span> : <?= StringHelper::isEmpty($employeurs->numeroE)?></label>
                                </div>        
                        </div>    
                        
                        <label class="row" style="margin-top:20px;margin-left:8px;"> <sapn class="icon">👤 </sapn> <span style="font-size: 25px;">Étudiant</span></label>
                        <div class="divcolel" style="margin-top:10px;">
                            <div class="documents">
                                <label class="control-label"><span style="text-decoration: underline;">Nom</span> : <?= !empty($items->nomA)?StringHelper::getShortName($items->nomA,$items->prenomA):StringHelper::isEmpty("") ?></label>
                            </div>     
                        </div>  
                        <div class="divcolel">
                            <div class="documents">
                                <label class="control-label"><span style="text-decoration: underline;">Email</span> : <?= $items->emailA?></label>
                            </div>         
                        </div>
                        <div class="divcolel">
                            <div class="documents">
                                <label class="control-label"><span style="text-decoration: underline;">Numéro</span> : <?= StringHelper::isEmpty($items->numeroA)?></label>
                            </div>    
                        </div>  

                        <label class="row" style="margin-top:20px;margin-left:8px;"> <sapn class="icon">🎓 </sapn> <span style="font-size: 25px;">Ecole</span></label>
                         <div class="divcolel" style="margin-top:10px;">
                            <div class="documents">
                                <label class="control-label"><span style="text-decoration: underline;">Nom</span> : <?= StringHelper::isEmpty($formations->nomF)?></label>
                            </div>          
                        </div>
                        <div class="divcolel">
                            <div class="documents">
                                <label class="control-label"><span style="text-decoration: underline;">Email</span> : <?= StringHelper::isEmpty($formations->emailF)?></label>
                            </div>          
                        </div>


            </aside>
          <input  type="hidden"  id="idElement" value="<?=$items->id?>">
            <section class="document-status">
                    <div class="status-card" style="border: 2px solid #153C4A;">
                        <h3>Entreprise</h3>
                       <div class="notifications-container" style="width: 100%;">
                            <div class="<?= empty($items->signatureEmployeur)? 'error-alert' : 'valid-alert' ?>">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="<?= empty($items->signatureEmployeur)? 'error-svg' : 'valid-svg' ?>">
                                            <?=empty($items->signatureEmployeur)?
                                            '<path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" fill-rule="evenodd"></path>'
                                            :' <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path  fill="#606060" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>'
                                            ?>
                                        </svg>
                                    </div>
                                    <div class="error-prompt-container">
                                        <p class="<?= empty($items->signatureEmployeur)? 'error-prompt-heading' : 'valid-prompt-heading' ?>"><?= empty($items->signatureEmployeur)?"L'entreprise doit signer le cerfa." :"Le cerfa a été signé par l'entreprise." ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="notifications-container" style="width: 100%; margin-top:20px;">
                            <div class="<?= empty($items->signatureConventionEmployeur)? 'error-alert' : 'valid-alert' ?>">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="<?= empty($items->signatureConventionEmployeur)? 'error-svg' : 'valid-svg' ?>">
                                            <?=empty($items->signatureConventionEmployeur)?
                                            '<path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" fill-rule="evenodd"></path>'
                                            :' <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path  fill="#606060" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>'
                                            ?>
                                        </svg>
                                    </div>
                                    <div class="error-prompt-container">
                                        <p class="<?= empty($items->signatureConventionEmployeur)? 'error-prompt-heading' : 'valid-prompt-heading' ?>"><?= empty($items->signatureConventionEmployeur)?"L'entreprise doit signer la convention." :"La convention a été signé par l'entreprise." ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    </div>
                
                    <div class="status-card" style="border: 2px solid #153C4A;">
                        <h3>École</h3>
                       <div class="notifications-container" style="width: 100%;">
                            <div class="<?= empty($items->signatureEcole)? 'error-alert' : 'valid-alert' ?>">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="<?= empty($items->signatureEcole)? 'error-svg' : 'valid-svg' ?>">
                                            <?=empty($items->signatureEcole)?
                                            '<path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" fill-rule="evenodd"></path>'
                                            :' <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path  fill="#606060" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>'
                                            ?>
                                        </svg>
                                    </div>
                                    <div class="error-prompt-container">
                                        <p class="<?= empty($items->signatureEcole)? 'error-prompt-heading' : 'valid-prompt-heading' ?>"><?= empty($items->signatureEcole)?"L'école doit signer le cerfa." :"Le cerfa a été signé par l'école." ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="notifications-container" style="width: 100%; margin-top:20px;">
                            <div class="<?= empty($items->signatureConventionEcole)? 'error-alert' : 'valid-alert' ?>">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="<?= empty($items->signatureConventionEcole)? 'error-svg' : 'valid-svg' ?>">
                                            <?=empty($items->signatureConventionEcole)?
                                            '<path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" fill-rule="evenodd"></path>'
                                            :' <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path  fill="#606060" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>'
                                            ?>
                                        </svg>
                                    </div>
                                    <div class="error-prompt-container">
                                        <p class="<?= empty($items->signatureConventionEcole)? 'error-prompt-heading' : 'valid-prompt-heading' ?>"><?= empty($items->signatureConventionEcole)?"L'école doit signer la convention." :"La convention a été signé par l'école." ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    </div>


                    <div class="status-card" style="border: 2px solid #153C4A;">
                        <h3>Étudiant</h3>
                       <div class="notifications-container" style="width: 100%;">
                            <div class="<?= empty($items->signatureApprenti)? 'error-alert' : 'valid-alert' ?>">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="<?= empty($items->signatureApprenti)? 'error-svg' : 'valid-svg' ?>">
                                            <?=empty($items->signatureApprenti)?
                                            '<path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" fill-rule="evenodd"></path>'
                                            :' <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path  fill="#606060" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>'
                                            ?>
                                        </svg>
                                    </div>
                                    <div class="error-prompt-container">
                                        <p class="<?= empty($items->signatureApprenti)? 'error-prompt-heading' : 'valid-prompt-heading' ?>"><?= empty($items->signatureApprenti)?"L'étudiant doit signer le cerfa." :"Le cerfa a été signé par l'étudiant." ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="notifications-container" style="width: 100%; margin-top:20px;">
                            <div class="<?= empty($items->nomA)? 'error-alert' : 'valid-alert' ?>">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="<?= empty($items->nomA)? 'error-svg' : 'valid-svg' ?>">
                                            <?=empty($items->nomA)?
                                            '<path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" fill-rule="evenodd"></path>'
                                            :' <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path  fill="#606060" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>'
                                            ?>
                                        </svg>
                                    </div>
                                    <div class="error-prompt-container">
                                        <p class="<?= empty($items->nomA)? 'error-prompt-heading' : 'valid-prompt-heading' ?>"><?= empty($items->nomA)?"Vous devez renseigner les informations de L'étudiant." :"Les informations de l'étudiant sont correctement enregistrées." ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    </div>

                    <div class="status-card" style="border: 2px solid #153C4A;">
                        <h3>Contrat</h3>
                       <div class="notifications-container" style="width: 100%;">
                            <div class="<?= empty($items->modeC)? 'error-alert' : 'valid-alert' ?>">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="<?= empty($items->modeC)? 'error-svg' : 'valid-svg' ?>">
                                            <?=empty($items->modeC)?
                                            '<path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" fill-rule="evenodd"></path>'
                                            :' <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path  fill="#606060" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>'
                                            ?>
                                        </svg>
                                    </div>
                                    <div class="error-prompt-container">
                                        <p class="<?= empty($items->modeC)? 'error-prompt-heading' : 'valid-prompt-heading' ?>"><?= empty($items->modeC)?"Vous devez remplir les informations  sur le contrat de L'étudiant." :"Les informations sur le contrat de L'étudiant sont correctements enregistrées." ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                      

                        
                    </div>
              
            </section>
            <aside class="documents" style="background-color: #153C4A; border: 2px solid #153C4A;">
                <h3 style=" text-align: center;color:#fff;">Actions</h3>
                <button class="secondarys-button " data-toggle="tooltip"    data-original-title="Imprimer le cerfa">
                <a style="text-decoration: none;  color:black"  
                target="_blank" href="<?php echo App::url('cerfas/pdf?data=' . base64_encode($items->id)); ?>" > Imprimer le cerfa
                </a>
                
                </button>


                <button class="<?= empty($items->numeroInterne)? 'secondary-button': 'secondarys-button';?>" id="sendOpcoFacture"  data-id="<?php $items->id;?>"
                <?= empty($items->numeroInterne)? 'disabled': '';?> data-toggle="tooltip"
                data-original-title="<?= empty($items->numeroInterne)? "Vous devez D'abord Envoyer Le Cerfa": 'Remplir le dossier de prise  en charge';?>"
                >
                Remplir le dossier de prise  en charge
                </button>

                <button class="<?=empty($items->nomA) || empty($items->modeC)? 'secondary-button': 'secondarys-button';?>" id="remplirConvention" 
                <?= empty($items->nomA)|| empty($items->modeC)? 'disabled': '';?> data-toggle="tooltip" data-id="<?php $items->id;?>" 
                  data-original-title="<?= empty($items->nomA)|| empty($items->modeC)? "Vous devez D'abord Remplir les informations de l'Apprenti(e) et de son Contrat": 'Remplir la convention à envoyer';?>"
                >
                Remplir la convention 
                </button>
                

              
                <a  target="_blank" style="text-decoration: none;  color:white" href="<?php echo App::url('cerfas/pdf/convention?data=' . base64_encode($items->id)); ?>">
                <button class="secondarys-button" data-toggle="tooltip"    data-original-title="Imprimer la convention"> Imprimer la convention
                </button>
               
                </a>
                
                <button class="<?= empty($items->factureOpco)? 'secondary-button': 'secondarys-button';?>" id="remplirEcheance" data-toggle="tooltip"  data-id="<?php $items->id;?>"
                data-toggle="tooltip" <?= empty($items->factureOpco)? 'disabled': '';?>
                data-original-title="<?= empty($items->factureOpco)? "Vous devez d'abord Remplir le dossier de prise en charge": "Remplir la facture d'une échéance";?>"
                >
                Remplir la facture d'une échéance
                </button>
                
            </aside>
        </div>

        <div id="section6" class="divcolmain contact" style="display: none;">
            <section class="document-status">
                    <div class="status-card">
                        <div>  <p class="mainColor text-left"> Listes des contacts utiles</p></div>
                        <div><p style="margin-left:7px; "   class=" text-left">Entreprise   </p></div>
                        <div class="divcolel">
                                        <div class="documents">
                                            <label class="control-label">Dénomination :</label>
                                        </div>    
                                        <div class="documents">
                                            <label class="control-label"><?= StringHelper::isEmpty($employeurs->nomE)?></label>
                                        </div>
                        </div>  
                        <div class="divcolel">
                                <div class="documents">
                                    <label class="control-label">Email :</label>
                                </div>    
                                <div class="documents">
                                    <label class="control-label"><?= $employeurs->emailE?></label>
                                </div>      
                        </div>
                        <div class="divcolel">
                                <div class="documents">
                                    <label class="control-label">Numéro :</label>
                                </div>    
                                <div class="documents">
                                    <label class="control-label"><?= $employeurs->numeroE?></label>
                                </div>      
                        </div>
                    </div>

                    <div class="status-card">
                                <div><p style="margin-left:7px; "   class=" text-left">Apprenti(e)   </p></div>
                                <div class="divcolel">
                                        <div class="documents">
                                            <label class="control-label">Nom :</label>
                                        </div>    
                                        <div class="documents">
                                            <label class="control-label"><?= !empty($items->nomA)?StringHelper::getShortName($items->nomA,$items->prenomA):StringHelper::isEmpty("") ?></label>
                                         </div>  
                                </div>  
                                <div class="divcolel">
                                        <div class="documents">
                                            <label class="control-label">Email :</label>
                                        </div>    
                                        <div class="documents">
                                            <label class="control-label"><?= $items->emailA?></label>
                                        </div>      
                                </div>
                                <div class="divcolel">
                                        <div class="documents">
                                            <label class="control-label">Numéro :</label>
                                        </div>    
                                        <div class="documents">
                                            <label class="control-label"><?= StringHelper::isEmpty($items->numeroA)?></label>
                                        </div>  
                                </div>      
                       
                    </div>


                    <div class="status-card">
                                
                                
                                <div class="divcolel">
                                        <div class="documents">
                                            <label class="control-label">Email :</label>
                                        </div>    
                                        <div class="documents">
                                            <label class="control-label"><?= StringHelper::isEmpty($formations->emailF)?></label>
                                        </div>      
                                </div>
                                    
                       
                    </div>
                    
            </section>
            <!-- <aside class="documents">
                <h3>Documents disponibles</h3>
                <button class="upload-button">Supprimer</button>
                <ul>
                    <li>CERFA.pdf</li>
                    <li>CERFAsigné.pdf</li>
                </ul>
            </aside> -->
        </div>

        <div id="section7" class="divcolmain  comptabilite" style="display: none;">
            <section class="document-status">
                    <div class="status-card">
                        <div class="divcolel">
                            <div class="documents" style="width: 100%;">
                                <label class="control-label">Echéances</label>
                                <?php if(empty($items->numeroInterne)){ ?>
                                    <div class="notifications-container" style="width: 100%;">
                                        <div class="error-alert">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                     <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="error-svg">
                                                        <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" fill-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div class="error-prompt-container">
                                                    <p class="error-prompt-heading">Vous devez envoyer le Cerfa afin de récupérer les informations sur les échéances.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else { 
                                    ?>
                                    <div class="col-md-12">
                                        <div class="table-responsive project-stats">
                                            <table class="table table-striped">
                                                <thead class="noBackground" id="echeances">
                                                   
                                                </thead>
                                                <tbody id="echeances-container">
                                                    <tr id="loadingRow">
                                                            <td colspan="9" style="text-align:center;">
                                                                <div class="spinner"></div> Chargement des échéances, veuillez patienter...
                                                            </td>
                                                        </tr>
                                                </tbody>
                                                
                                            </table>
                                        </div>
                                    </div>
                                    
                                <?php }
                                
                                 ?>
                                            
                            </div>
                        </div>
                    </div>


                    <div class="status-card">
                        <div class="divcolel">
                            <div class="documents" style="width: 100%;">
                                <label class="control-label">Details Facturation</label>
                                <?php if (empty($items->numeroInterne)) { ?>
                                    <div class="notifications-container" style="width: 100%;">
                                        <div class="error-alert">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="error-svg">
                                                        <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" fill-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div class="error-prompt-container">
                                                    <p class="error-prompt-heading">Vous devez envoyer le cerfa afin de récupérer les informations sur la facturation.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else {
                                    ?>
                                     <div class="col-md-12">
                                        <div class="table-responsive project-stats">
                                            <table class="table table-striped">
                                                <thead class="noBackground" id="detailsecheances">
                                                   
                                                </thead>
                                                <tbody id="detailsecheances-container">
                                                    <tr id="loadingRowdetails">
                                                            <td colspan="9" style="text-align:center;">
                                                                <div class="spinner" style="text-align:center;"></div> Chargement des Details Facturation, veuillez patienter...
                                                            </td>
                                                        </tr>
                                                </tbody>
                                                
                                            </table>
                                        </div>
                                    </div>


                                        <?php }
                                     ?>
                            </div>
                        </div>
                    </div>


                    <div class="status-card">
                        <div class="divcolel">
                            <div class="documents" style="width: 100%;">
                                <label class="control-label">Engagements Frais Annexe</label>
                                <?php if(empty($items->numeroInterne)){ ?>
                                    <div class="notifications-container" style="width: 100%;">
                                        <div class="error-alert">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="error-svg">
                                                        <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" fill-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div class="error-prompt-container">
                                                    <p class="error-prompt-heading">Vous devez Envoyer le cerfa afin de recuperer les Informations sur les frais Annexes</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else { 
                                    ?>
                                    <div class="col-md-12">
                                        <div class="table-responsive project-stats">
                                            <table class="table table-striped">
                                                <thead class="noBackground" id="fraisannexesecheances">
                                                   
                                                </thead>
                                                <tbody id="fraisannexesecheances-container">
                                                    <tr id="loadingRowfrais">
                                                            <td colspan="9" style="text-align:center;">
                                                                <div class="spinner"></div> Chargement des Engagements Frais Annexes, veuillez patienter...
                                                            </td>
                                                        </tr>
                                                </tbody>
                                                
                                            </table>
                                        </div>
                                    </div>
                                    
                                <?php }
                                ?>
                                            
                            </div>
                        </div>
                    </div>


                   

                    <div style="height: 80px;"></div>

            </section>
          
        </div>

      
      
    </section>


    <footer style="margin-left:11px;margin-right:11px;">

          <div class="row">
            <div class="col-md-3">
            <button 
                    id="sendOpcoCerfa" 
                    data-toggle="tooltip"
                    data-original-title="<?php 
                        $hasType1Product = false;
                        $idabonement ='';
                        $abonnements = Abonnement::searchType();
                        $abonnements = $abonnements['data'];
                    
                    
                    
                    if (!empty($abonnements)) {
                        foreach ($abonnements as $abonnement) {
                            $tableauproduits = Produit::find($abonnement->id_produit);
                            $tableauproduit = $tableauproduits['data'];
                    
                            if ($tableauproduit->type == 1 && $abonnement->quantite > 0) {
                                $idabonement = $abonnement->id;
                                $hasType1Product = true;
                                break; 
                            }
                        }
                    }
                        if (empty($items->nomA) || empty($items->modeC)) {
                            echo 'Vous devez remplir toutes les informations concernant le cerfa (Étudiant + Contrat)';
                        } elseif (!$hasType1Product) {
                            echo 'Vous devez recharger votre compte  Dossier Apprentissage';
                        } else {
                            echo 'Envoyer le cerfa';
                        }
                    ?>"
                    <?= (empty($items->nomA) || empty($items->modeC)) ? 'disabled' : (!$hasType1Product? 'disabled': ''); ?>
                    class="<?= (empty($items->nomA) || empty($items->modeC)) ? 'secondary-button' : (!$hasType1Product? 'secondary-button': 'primary-button'); ?>"  
                    data-idProduitCerfa="<?= $idabonement ?>"
                    >
                Envoyer le cerfa à l'OPCO
            </button>
            </div>
            <div class="col-md-3">
                <button  class="<?= empty($items->numeroInterne)? 'secondary-button': 'upload-button';?>" id="sendOpcoConvention"  
                data-original-title="<?= empty($items->numeroInterne)? "Vous devez D'abord Envoyer Le Cerfa": 'Envoyer La convention';?>"
                data-toggle="tooltip"  <?= empty($items->numeroInterne)? 'disabled': '';?> >Envoyer la convention à l'opco</button>
            </div>
            
            <div class="col-md-3 hidden-div">
                <input  type="hidden"  id="urlFactureOpco" value="<?=$items->factureOpco?>">
                <button class="<?= empty($items->factureOpco) ? 'secondary-button' : 'primary-button';?>" 
                        data-toggle="tooltip" <?= empty($items->factureOpco) ? 'disabled' : '';?>
                        id="afficheFacture" 
                        data-original-title="<?= empty($items->factureOpco) ? "Vous devez D'abord Remplir le dossier de prise en charge" : 'Voir le dossier de prise en charge';?>">
                    Voir le dossier de prise en charge
                </button>
            </div>

            <div class="col-md-3">
                <button    id="sendEcheance"
                data-toggle="tooltip" <?= empty($items->factureOpco) ||empty($items->numeroInterne)? 'disabled': '';?>
                  data-original-title="<?= 
                    $hasType1Product1 = false;
                    $abonnements = Abonnement::searchType();
                    $abonnements = $abonnements['data'];
                    $idabonements ='';
                
                
                
                    if (!empty($abonnements)) {
                        foreach ($abonnements as $abonnement) {
                            $tableauproduits = Produit::find($abonnement->id_produit);
                            $tableauproduit = $tableauproduits['data'];
                    
                            if ($tableauproduit->type == 3 && $abonnement->quantite > 0) {
                                $idabonements = $abonnement->id;
                                $hasType1Product1 = true;
                                break; 
                            }
                        }
                    }

                    if (empty($items->factureOpco)||empty($items->numeroInterne)) {
                        echo "Vous devez D'abord Envoyer Le cerfa et par las Suite Remplir le dossier de prise en charge";
                    } elseif (!$hasType1Product1) {
                        echo 'Vous devez recharger votre compte Facturation Dossier Apprentissage';
                    } else {
                        echo "ENVOYER La facture d'une echeance";
                    }?>"

                      class="<?= (empty($items->factureOpco)||empty($items->numeroInterne)) ? 'secondary-button' : (!$hasType1Product1? 'secondary-button': 'primary-button'); ?>"
                    <?= (empty($items->factureOpco)||empty($items->numeroInterne)) ? 'disabled' : (!$hasType1Product1? 'disabled': ''); ?>
                     data-idProduitCerfas="<?= $idabonements ?>"

                >Envoyer la facture d'une échéance</button>
            </div>
            
            <div class="col-md-3">
                <button  class="<?= empty($items->numeroInterne)? 'secondary-button': 'upload-button';?>" id="sendOpcoDocument"  
                data-original-title="<?= empty($items->numeroInterne)? "Vous devez D'abord Envoyer Le Cerfa": 'Envoyer Un document Externe';?>"
                data-toggle="tooltip"  <?= empty($items->numeroInterne)? 'disabled': '';?> >Envoyer un document externe à l'OPCO</button>
            </div>
        </div> 
    </footer>
</div>

<!-- Modal send Document  -->
<div class="modal fade documentModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="FormDocument1">AJOUTER UN Document</h2>
            </div>
            <form action="<?= App::url('cerfas/sendOpcoDocument') ?>" id="FormDocument" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="idDocument" name="idDocument">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="Document">Choisissez un fichier à télécharger:pdf, jpg, jpeg, png<b>* </b> </label>
                            <input type="file" class="form-control" id="fileDocument"  name="fileDocument" required style="border-radius: 5px;">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="Document">Type de document<b>* </b> </label>
                            <select name="document_type" id="document_type" class="form-control" required>
                                 <option value="">___________</option>
                                <!-- <option value="CONVENTION_FORMATION">CONVENTION_FORMATION</option>
                                <option value="CONVENTION_REDUCTION_DUREE">CONVENTION_REDUCTION_DUREE</option>
                                <option value="CONVENTION_MOBILITE">CONVENTION_MOBILITE</option>
                                <option value="FACTURE">FACTURE</option> -->
                                <option value="CERTIFICAT_REALISATION">CERTIFICAT_REALISATION</option>
                            </select>

                        </div>

                        <div class="col-md-6 form-group">
                            <label for="Document">Numero Echeance<b>* </b> </label>
                            <select name="echeance" id="echeance" class="form-control" required>
                                 <option value="">___________</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>

                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="photoBtn btn btn-default" style="border-radius: 5px;">ENVOYER</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal   changer une formation  -->
<div class="modal fade changeFormationModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="FormChangeFormation1">Changer La formation</h2>
            </div>
            <form action="<?= App::url('cerfas/changeFormation') ?>" id="FormChangeFormation" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="idElementchangeformation" name="idElementchangeformation">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                    <div class="row">
                        <div class="col-md-12 form-group">
                              <label class="control-label">Choisissez une formation<b>*</b></label>
                                <input type="text" list="formations-list" name="formation_nom" id="formation_nom" class="form-control" 
                                    autocomplete="off" required style="border-radius: 5px;">
                                <input type="hidden" name="idformation" id="idformation">
                                <datalist id="formations-list">
                                    <?php foreach ($allformations as $allformation): 
                                        $nom = (empty($allformation->intituleF)) 
                                            ? StringHelper::isEmpty('').' / '.$allformation->nomF 
                                            : $allformation->intituleF.' / '.$allformation->nomF.' / '.StringHelper::dateFormation(
                                                date("d/m/Y", strtotime($allformation->debutO)),
                                                date("d/m/Y", strtotime($allformation->prevuO))
                                            );
                                    ?>
                                        <option data-id="<?= $allformation->id ?>" value="<?= htmlspecialchars($nom) ?>">
                                    <?php endforeach; ?>
                                </datalist>
                                <small class="text-muted">Commencez à taper pour rechercher une formation</small>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="photoBtn btn btn-default" style="border-radius: 5px;">ENVOYER</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Modal   changer une entreprise  -->
<div class="modal fade changeEntrepriseModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="FormChangeEntrprise1">Changer L'entreprise</h2>
            </div>
            <form action="<?= App::url('cerfas/changeEntreprise') ?>" id="FormChangeEntrprise" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="idElementchangeentreprise" name="idElementchangeentreprise">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            


                             <label class="control-label">Choisissez une entreprise:<b>*</b></label>
                                <input type="text" list="employeurs-list" name="employeur_nom" id="employeur_nom" class="form-control" 
                                    autocomplete="off" required style="border-radius: 5px;">
                                <input type="hidden" name="idemployeur" id="idemployeur">
                                <datalist id="employeurs-list">
                                    <?php foreach ($allemployeurs as $allemployeur): ?>
                                        <option data-id="<?= $allemployeur->id ?>" value="<?= htmlspecialchars($allemployeur->nomE) ?>">
                                    <?php endforeach; ?>
                                </datalist>
                                <small class="text-muted">Commencez à taper pour rechercher ou <a href="employeurs">ajouter un employeur</a></small>




                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="photoBtn btn btn-default" style="border-radius: 5px;">ENVOYER</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Modal   Remplissage facture  -->
<div class="modal fade FactureModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content"  style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="FormFacture1">Remplir le dossier de prise en charge</h2>
            </div>
            <form action="<?= App::url('cerfas/sendOpcoFacture') ?>" id="FormFacture" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="idFacture" name="idFacture">
                    <p class="mainColor text-right">* Champs obligatoires</p>


                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="numero">Numéro OF<b>* </b></label>
                            <input type="text" class="form-control"  id="numeroOF"  name="numeroOF" value="127639.001" style="border-radius: 5px;">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="lieu">Fait à<b>* </b> </label>
                            <input type="text" class="form-control" id="lieuF"  name="lieuF" required style="border-radius: 5px;">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="date Facture">le <b>* </b> </label>
                            <input type="date" class="form-control" id="dateF"  name="dateF" required style="border-radius: 5px;">
                        </div>
                    </div>

                    <div class="row">
                    <div class="col-md-6 form-group">
                            <label for="numeroInterne client">numéro Interne Client <b>* </b> </label>
                            <input type="text" class="form-control" id="numeroClient"  name="numeroClient" required style="border-radius: 5px;">
                        </div>      
                        <div class="col-md-6 form-group">
                            <label for="Iban">IBAN<b>* </b> </label>
                            <input type="text" class="form-control"  id="ibanF"  name="ibanF" required style="border-radius: 5px;">
                        </div>              
                    </div>      
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="RepresentantCentre">Représentant du centre <b>* </b></label>
                            <input type="text" class="form-control"  id="repreF"  name="repreF" style="border-radius: 5px;">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="EmploiOccupé ">Emploi occupé :   <b>* </b></label>
                            <input type="text" class="form-control" id="emploiRF"  name="emploiRF" style="border-radius: 5px;">
                        </div>  
                        <div class="col-md-4 form-group">
                            <label for="CoûtAnnuelBranche">Coût annuel Branche  <b>* </b> </label>
                            <input type="text" class="form-control" id="coutAB"  name="coutAB" required style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">
                        </div>  
                    </div>

                    

                    <div class="row">
                       <p style="margin-left:15px; "   class=" text-left">Depenses  <b>*</b> </p>
                        <div class="col-md-3 form-group">
                            <label for="motif">Motif<b>*</b> </label>
                            <select name="motif" id="motif" class="form-control" required style="border-radius: 5px;">
                                <option value="">_________</option>
                                <option value="PEDAGOGIE">PEDAGOGIE</option>
                            </select>

                        </div>
                        
                        <div class="col-md-3 form-group">
                            <label for="montant">Montant <b>* </b> </label>
                            <input type="text" class="form-control" id="montant"  name="montant" required style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">
                        </div>

                        <div class="col-md-3 form-group">
                            <label for="motif1">Motif </label>
                            <select name="motif1" id="motif1" class="form-control" style="border-radius: 5px;">
                                <option value="">_________</option>
                                <option value="PREMIEREQUIPEMENT">PREMIERE EQUIPEMENT</option>
                            </select>

                        </div>
                        <div class="col-md-3 form-group">
                            <label for="montant1">Montant </label>
                            <input type="text" class="form-control" id="montant1"  name="montant1" style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="motif2">Motif </label>
                            <select name="motif2" id="motif2"  class="form-control" style="border-radius: 5px;">
                                <option value="">_________</option>
                                <option value="MOBILITE">MOBILITE</option>
                                <option value="HEBERGEMENT">HEBERGEMENT</option>
                                <option value="RESTAURATION">RESTAURATION</option>
                                <option value="MAJORATION_RQTH">MAJORATION RQTH</option>
                            </select>

                        </div>
                        <div class="col-md-3 form-group">
                            <label for="montant2">Montant  </label>
                            <input type="text" class="form-control" id="montant2"  name="montant2" style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">
                        </div>

                        <div class="col-md-3 form-group">
                            <label for="motif3">Motif </label>
                            <select name="motif3" id="motif3" class="form-control" style="border-radius: 5px;">
                                <option value="">_________</option>
                                <option value="MOBILITE">MOBILITE</option>
                                <option value="HEBERGEMENT">HEBERGEMENT</option>
                                <option value="RESTAURATION">RESTAURATION</option>
                                <option value="MAJORATION_RQTH">MAJORATION RQTH</option>
                            </select>

                        </div>
                        <div class="col-md-3 form-group">
                            <label for="montant3">Montant  </label>
                            <input type="text" class="form-control" id="montant3"  name="montant3" style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="motif4">Motif </label>
                            <select name="motif4" id="motif4" class="form-control"style="border-radius: 5px;">
                                <option value="">_________</option>
                                <option value="MOBILITE">MOBILITE</option>
                                <option value="HEBERGEMENT">HEBERGEMENT</option>
                                <option value="RESTAURATION">RESTAURATION</option>
                                <option value="MAJORATION_RQTH">MAJORATION RQTH</option>
                            </select>

                        </div>
                        <div class="col-md-3 form-group">
                            <label for="montant4">Montant  </label>
                            <input type="text" class="form-control" id="montant4"  name="montant4" style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">
                        </div>


                        <div class="col-md-3 form-group">
                            <label for="motif5">Motif </label>
                            <select name="motif5" id="motif5" class="form-control">
                                <option value="">_________</option>
                                <option value="MOBILITE">MOBILITE</option>
                                <option value="HEBERGEMENT">HEBERGEMENT</option>
                                <option value="RESTAURATION">RESTAURATION</option>
                                <option value="MAJORATION_RQTH">MAJORATION RQTH</option>
                            </select>

                        </div>
                        <div class="col-md-3 form-group">
                            <label for="montant5">Montant  </label>
                            <input type="text" class="form-control" id="montant5"  name="montant5" style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">
                        </div>
                    </div>

                    <div class="row">
                       <p style="margin-left:15px; "   class=" text-left">Échéancier.  <b>*</b> </p>
                        <div class="col-md-4 form-group">
                            <label for="date1">Dates des échéances.</label>
                            <input type="date" class="form-control" id="date1"  name="date1" required style="border-radius: 5px;">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="date2"><b>* </b> </label>
                            <input type="date" class="form-control" id="date2"  name="date2" required style="border-radius: 5px;">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="date3"><b>* </b> </label>
                            <input type="date" class="form-control" id="date3"  name="date3" required  style="border-radius: 5px;">
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="date4">.</label>
                            <input type="date" class="form-control" id="date4"  name="date4"  style="border-radius: 5px;">
                        </div>
                    </div>

                    <div class="row">
                   
                        <div class="col-md-4 form-group">
                            <label for="echeance1">Numéro de l’échéance<b>*</b> </label>
                            <select name="echeance1" id="echeance1" class="form-control" required  style="border-radius: 5px;">
                                <option value="">_________</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                              
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="echeance2"><b>* </b> </label>
                            <select name="echeance2" id="echeance2" class="form-control" required  style="border-radius: 5px;">
                                <option value="">_________</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="echeance3"><b>* </b> </label>
                            <select name="echeance3" id="echeance3" class="form-control" required  style="border-radius: 5px;">
                               <option value="">_________</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <ance for="echeance4">.</label>
                            <select name="echeance4" id="echeance4" class="form-control" style="border-radius: 5px;">
                               <option value="">_________</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                    </div>

                    <!-- <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="numero">Coût branche engagée<b>*</b></label>
                            <input type="text" class="form-control" id="CBE1"  name="CBE1" required  style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">

                        </div>
                        <div class="col-md-3 form-group">
                            <label for="numeroEcheance"><b>*</b>  </label>
                            <input type="text" class="form-control" id="CBE2"  name="CBE2" required  style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="numero"><b>* </b> </label>
                            <input type="text" class="form-control" id="CBE3"  name="CBE3" required  style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="numero">.</label>
                            <input type="text" class="form-control" id="CBE4"  name="CBE4"  style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="numero">Montant àverser HT<b>*</b></label>
                            <input type="text" class="form-control" id="ht1"  name="ht1" required  style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">

                        </div>
                        <div class="col-md-3 form-group">
                            <label for="numeroEcheance"> <b>* </b>  </label>
                            <input type="text" class="form-control" id="ht2"  name="ht2" required  style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="numero"><b>* </b> </label>
                            <input type="text" class="form-control" id="ht3"  name="ht3" required  style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="numero">.</label>
                            <input type="text" class="form-control" id="ht4"  name="ht4"  style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">
                        </div>
                    </div>



                   
                   
                   
                </div>
                <div class="modal-footer">
                    <button type="submit" class="photoBtn btn btn-default" style="border-radius: 5px;">REMPLIR</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal cerfa  -->
<div class="modal fade cerfaModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 10px;border: 5px  #fff;">
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
                           <input type="hidden" id="idProduitCerfa" name="idProduitCerfa">
                            <label for="Cerfa">Choisissez un fichier à télécharger :  pdf, jpg, jpeg, png<b>* </b> </label>
                            <input type="file" class="form-control" id="cerfa"  name="cerfa" required style="border-radius: 5px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="photoBtn btn btn-default" style="border-radius: 5px;">ENVOYER</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal send convention  -->
<div class="modal fade conventionModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="FormConvention1">AJOUTER UNE CONVENTION</h2>
            </div>
            <form action="<?= App::url('cerfas/sendOpcoConvention') ?>" id="FormConvention" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="idConvention" name="idConvention">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="Convention">Choisissez un fichier à télécharger:pdf, jpg, jpeg, png<b>* </b> </label>
                            <input type="file" class="form-control" id="file"  name="file" required style="border-radius: 5px;">
                        </div>
                        

                    <div class="row">
                       
                        <div class="col-md-6 form-group">
                            <label for="ConventionDate">Date de signature de la convention<b>* </b> </label>
                            <input type="date" class="form-control" id="dateConvention"  name="dateConvention" required style="border-radius: 5px;">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="montantPremierEquipement">Montant Premier Equipement<b>* </b> </label>
                            <input type="text" class="form-control" id="montantPremierEquipement"  name="montantPremierEquipement" value="500" required style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">
                        </div>
                    </div>

                    <div class="row">
                       <div class="col-md-4 form-group">
                            <label for="nombreHebergementTotaux">Montant Hebergement Totaux </label>
                            <input type="text" class="form-control" id="nombreHebergementTotaux"  name="nombreHebergementTotaux" style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="nombreRepasTotaux">Montant Repas Totaux </label>
                            <input type="text" class="form-control" id="nombreRepasTotaux"  name="nombreRepasTotaux"  style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="montantRQTHAnnee1">montant RQTH Année1 </label>
                            <input type="text" class="form-control" id="montantRQTHAnnee1"  name="montantRQTHAnnee1" style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">
                        </div>
                    </div>    

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="montantRQTHAnnee2">montant RQTH Année2 </label>
                            <input type="text" class="form-control" id="montantRQTHAnnee2"  name="montantRQTHAnnee2"  style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="montantRQTHAnnee3">montant RQTH Année3 </label>
                            <input type="text" class="form-control" id="montantRQTHAnnee3"  name="montantRQTHAnnee3" style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="montantRQTHAnnee4">montant RQTH Année4</label>
                            <input type="text" class="form-control" id="montantRQTHAnnee4"  name="montantRQTHAnnee4"  style="border-radius: 5px;" pattern="[0-9]+([,\.][0-9]+)?" step="any" inputmode="decimal">
                        </div>
                    </div>    

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="mentionMobilitéInternationale">Mention Mobilité Internationale</label>
                            <select  id="mentionMobilitéInternationale"  name="mentionMobilitéInternationale" class="form-control" style="border-radius: 5px;">
                                <option value="false">non</option> 
                                <option value="true">oui</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="accompagnementDROM">Accompagnement DROM<b>* </b> </label>
                            <select  id="accompagnementDROM"  name="accompagnementDROM" class="form-control" style="border-radius: 5px;">
                                <option value="false">non</option>
                                <option value="true">oui</option>
                               
                            </select>
                        </div>
                    </div>   
                       
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="photoBtn btn btn-default" style="border-radius: 5px;">ENVOYER</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Remplissage  convention  -->
<div class="modal fade conventionModalRemplir" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="FormConvention1">Remplir La convention à envoyée</h2>
            </div>
            <form action="<?= App::url('cerfas/remplirConvention') ?>" id="FormRemplirConvention" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="idRemplirConvention" name="idRemplirConvention">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                    <div class="row">
                       
                        <div class="col-md-5 form-group">
                            <label for="dateRemplirConvention">Date de signature de la convention<b>* </b> </label>
                            <input type="date" class="form-control" id="dateRemplirConvention"  name="dateRemplirConvention" required style="border-radius: 5px;">
                        </div>

                        <div class="col-md-7 form-group">
                            <label for="RepresentantEmployeur">Représentant de l'employeur<b>* </b> </label>
                            <input type="text" class="form-control" id="RepresentantEmployeur"  name="RepresentantEmployeur"  required style="border-radius: 5px;">
                        </div>
                    </div>

                    <p class="mainColor text-left">Modalitées de déroulement de suivi et d'obtention du titre ou diplôme</p>
                    <div class="row">
                       <div class="col-md-6 form-group">
                           <label for="dateRemplirConvention">En présentiel<b>* </b> </label>
                           <input type="number" class="form-control" id="dureepresentiel"  name="dureepresentiel" required style="border-radius: 5px;">
                       </div>

                       <div class="col-md-6 form-group">
                           <label for="RepresentantEmployeur">En distanciel<b>* </b> </label>
                           <input type="number" class="form-control" id="dureedistenticiel"  name="dureedistenticiel"  required style="border-radius: 5px;">
                       </div>
                   </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="NumeroActivite">Numéro de déclaration  d'activité du CFA <b>* </b> </label>
                            <input type="number" class="form-control" id="NumeroActivite"  name="NumeroActivite" required style="border-radius: 5px;">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="RegionActivite">Région d'activité<b>* </b> </label>
                                <select id="RegionActivite" name="RegionActivite" class="form-control" required style="border-radius: 5px;">
                                    <option value="">___________________</option>
                                    <option value="auvergne-rhone-alpes">Auvergne-Rhône-Alpes</option>
                                    <option value="bourgogne-franche-comte">Bourgogne-Franche-Comté</option>
                                    <option value="bretagne">Bretagne</option>
                                    <option value="centre-val-de-loire">Centre-Val de Loire</option>
                                    <option value="corse">Corse</option>
                                    <option value="grand-est">Grand Est</option>
                                    <option value="hauts-de-france">Hauts-de-France</option>
                                    <option value="ile-de-france">Île-de-France</option>
                                    <option value="normandie">Normandie</option>
                                    <option value="nouvelle-aquitaine">Nouvelle-Aquitaine</option>
                                    <option value="occitanie">Occitanie</option>
                                    <option value="pays-de-la-loire">Pays de la Loire</option>
                                    <option value="provence-alpes-cote-dazur">Provence-Alpes-Côte d’Azur</option>
                                    <option value="guadeloupe">Guadeloupe</option>
                                    <option value="martinique">Martinique</option>
                                    <option value="guyane">Guyane</option>
                                    <option value="la-reunion">La Réunion</option>
                                    <option value="mayotte">Mayotte</option>
                                </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="RepresentantCerfa">Représentant du CFA.<b>* </b> </label>
                            <input type="text" class="form-control" id="RepresentantCerfa"  name="RepresentantCerfa"  required>
                        </div>
                    </div>

                    <p class="mainColor text-left">Les trois grandes missions de l'apprenti(e)</p>
                    <div class="row">
                        <label for="Mission1" style="margin-left:15px;" class="text-left">Première Mission<b>*</b></label>
                        <div class="col-md-12 form-group">
                            <textarea class="form-control" rows="2" id="Mission1" name="Mission1" required style="border-radius: 5px;"></textarea>
                        </div>
                    </div>  

                    <div class="row">
                        <label for="Mission2" style="margin-left:15px;" class="text-left">Deuxième  Mission<b>*</b></label>
                        <div class="col-md-12 form-group">
                            <textarea class="form-control" rows="2" id="Mission2" name="Mission2" required style="border-radius: 5px;"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <label for="Mission3" style="margin-left:15px;" class="text-left">Troisième Mission<b>*</b></label>
                        <div class="col-md-12 form-group">
                            <textarea class="form-control" rows="2" id="Mission3" name="Mission3" required style="border-radius: 5px;"></textarea>
                        </div>
                    </div>
                    <p class="mainColor text-left">Gestion et suivi du dossier de formation</p>
                    <div class="row">
                        <div class="col-md-12 form-group">
                           <label for="Prise en charge par l'opco">La gestion du dossier de formation au près de l'opco responsable  est pris en charge par le centre de formation<b>* </b> </label>
                            <select id="prisechargeentreprise" name="prisechargeentreprise" class="form-control" required style="border-radius: 5px;">
                                <option value="">___________________</option>
                                <option value="oui">OUI</option>
                                <option value="non">NON</option>
                            
                            </select>
                        </div>
                    </div>

                   
                       
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="photoBtn btn btn-default" style="border-radius: 5px;">Remplir</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal view facture send  -->
<div class="modal fade factureModalView" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog  modal-lg">
        <div class="modal-content" style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" >AFFICHER Le dossier de prisee en charge </h2>
            </div>
            <form action="<?= App::url('') ?>" id="" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <input type="hidden" id="urlsend">
                            <div id="documentContainerFacture" style="border-radius: 5px;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">  
                  
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Fermer</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Modal Selection signature cerfa + convention send  -->
<div class="modal fade signatureModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog" style="border-radius: 10px;border: 5px  #fff;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" >Selectioner L'action à effectuer </h2>
            </div>
          
                <div class="modal-body" style="  margin-top: 40px;">
                    <div class="row">
                        <div class="col-md-2 form-group"></div>
                        <div class="col-md-8 form-group">
                            <button  class="upload-button" id="sendformSignatureEcole">Envoyer le formulaire de signature du cerfa</button>
                        </div>
                        <div class="col-md-2 form-group"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 form-group"></div>
                        <div class="col-md-6 form-group">
                            <button  class="danger-button" id="signatureManuelleEcole" >Signer Manuellement</button>
                        </div>
                        <div class="col-md-3 form-group"></div>
                    </div>
                </div>
                <div class="modal-footer">  
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Fermer</button>
                </div>
           
        </div>
    </div>
</div>

<div class="modal fade signatureConventionModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" >Selectioner L'action à éffectuer </h2>
            </div>
          
                <div class="modal-body" style="  margin-top: 40px;">
                    <div class="row">
                        <div class="col-md-2 form-group"></div>
                        <div class="col-md-8 form-group">
                            <button  class="upload-button" id="sendformSignatureConventionEcole">Envoyer la convention pour signature </button>
                        </div>
                        <div class="col-md-2 form-group"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 form-group"></div>
                        <div class="col-md-6 form-group">
                            <button  class="danger-button" id="signatureManuelleConventionEcole">Signer Manuellement</button>
                        </div>
                        <div class="col-md-3 form-group"></div>
                    </div>
                </div>
                <div class="modal-footer">  
                    <button type="button" class="btn btn-warning" data-dismiss="modal"style="border-radius: 5px;">Fermer</button>
                </div>
           
        </div>
    </div>
</div>


<!-- Modal Signature Manuelle Ecole cerfa -->
<div class="modal fade signatureManuelleEcoleModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title">Selectionner un Fichier </h2>
            </div>
            <form action="<?= App::url('cerfas/signatureManuelleEcole') ?>" id="FormSignatureManuelleEcole" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="idsignatureManuelleEcole" name="idsignatureManuelleEcole">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="fileSignatureManuelleEcole">Choisissez un fichier à télécharger :   jpg, jpeg, png<b>* </b> </label>
                            <input type="file" class="form-control" id="fileSignatureManuelleEcole" name="fileSignatureManuelleEcole" accept="image/png, image/jpeg, image/jpg" required style="border-radius: 5px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="photoBtn btn btn-default" style="border-radius: 5px;">ENVOYER</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Signature Manuelle Ecole convention -->
<div class="modal fade signatureManuelleConventionEcoleModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title">Selectionner un Fichier </h2>
            </div>
            <form action="<?= App::url('cerfas/signatureManuelleConventionEcole') ?>" id="FormSignatureManuelleConventionEcole" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="idsignatureManuelleConvenionEcole" name="idsignatureManuelleConventionEcole">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="fileSignatureManuelleEcole">Choisissez un fichier à télécharger :   jpg, jpeg, png<b>* </b> </label>
                            <input type="file" class="form-control" id="fileSignatureManuelleConventionEcole" name="fileSignatureManuelleConventionEcole" accept="image/png, image/jpeg, image/jpg" required style="border-radius: 5px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="photoBtn btn btn-default" style="border-radius: 5px;">ENVOYER</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Modal   Remplissage Echeance  -->
<div class="modal fade remplirEcheanceModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="FormFacture1">Remplir une echeance</h2>
            </div>
            <form action="<?= App::url('cerfas/remplirEcheance') ?>" id="FormremplirEcheance" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="idremplirEcheance" name="idremplirEcheance">
                    <p class="mainColor text-right">* Champs obligatoires</p>

                    <div class="row">
                      
                   
                        <div class="col-md-4 form-group">
                            <label for="selectEcheance">Numéro de l’échéance<b>*</b> </label>
                            <select name="selectEcheance" id="selectEcheance" class="form-control" required style="border-radius: 5px;">
                                <option value="">_________</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                              
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="selectEcheance">Date Emission<b>*</b> </label>
                            <input type="date" class="form-control" id="dateEmissionEcheance"  name="dateEmissionEcheance" required style="border-radius: 5px;">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="selectEcheance">Numéro Facture</label>
                            <input type="text" class="form-control" id="numeroFacture"  name="numeroFacture"  style="border-radius: 5px;">
                        </div>
                     </div>

                     <div class="row additionalFields"  style="display: none;">
                        <div class="col-md-4 form-group">
                            <label for="selectEcheance">Date début.<b>*</b> </label>
                            <input type="date" class="form-control" id="dateDebutCertificat"  name="dateDebutCertificat"style="border-radius: 5px;" >
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="selectEcheance">Date fin<b>*</b> </label>
                            <input type="date" class="form-control" id="dateFinCertificat"  name="dateFinCertificat" style="border-radius: 5px;">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="selectEcheance">Durée<b>*</b> </label>
                            <input type="number" class="form-control" id="duree"  name="duree" style="border-radius: 5px;">
                        </div>
                    </div>
                        
                    <div class="row additionalFields"  style="display: none;">
                        <div class="col-md-4 form-group">
                            <label for="selectEcheance">Motif à intégrer. </label>
                            <select name="motifRemplir2" id="motifRemplir2"  class="form-control" style="border-radius: 5px;">
                                <option value="">_________</option>
                                <option value="MOBILITE">MOBILITE</option>
                                <option value="HEBERGEMENT">HEBERGEMENT</option>
                                <option value="RESTAURATION">RESTAURATION</option>
                                <option value="MAJORATION_RQTH">MAJORATION RQTH</option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="selectEcheance">Motif à intégrer. </label>
                            <select name="motifRemplir3" id="motifRemplir3"  class="form-control" style="border-radius: 5px;">
                                <option value="">_________</option>
                                <option value="MOBILITE">MOBILITE</option>
                                <option value="HEBERGEMENT">HEBERGEMENT</option>
                                <option value="RESTAURATION">RESTAURATION</option>
                                <option value="MAJORATION_RQTH">MAJORATION RQTH</option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="selectEcheance">Motif à intégrer. </label>
                            <select name="motifRemplir4" id="motifRemplir4"  class="form-control" style="border-radius: 5px;">
                                <option value="">_________</option>
                                <option value="MOBILITE">MOBILITE</option>
                                <option value="HEBERGEMENT">HEBERGEMENT</option>
                                <option value="RESTAURATION">RESTAURATION</option>
                                <option value="MAJORATION_RQTH">MAJORATION RQTH</option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="selectEcheance">Motif à intégrer. </label>
                            <select name="motifRemplir5" id="motifRemplir5"  class="form-control" style="border-radius: 5px;">
                                <option value="">_________</option>
                                <option value="MOBILITE">MOBILITE</option>
                                <option value="HEBERGEMENT">HEBERGEMENT</option>
                                <option value="RESTAURATION">RESTAURATION</option>
                                <option value="MAJORATION_RQTH">MAJORATION RQTH</option>
                            </select>
                        </div>


                     </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="photoBtn btn btn-default" style="border-radius: 5px;">REMPLIR</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal   Envoie Echeance  -->
<div class="modal fade EnvoieEcheanceModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" >Envoi  d'une echeance</h2>
            </div>
            <form action="<?= App::url('cerfas/sendFacture') ?>" id="FormFactureSend" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                <input type="hidden" id="idFactureSend" name="idFactureSend">
                <input type="hidden" id="idProduitCerfas" name="idProduitCerfas">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="Facture">Choisissez un fichier à télécharger :  pdf<b>* </b> </label>
                            <input type="file" class="form-control" id="fileFactureEcheanceSend"  name="fileFactureEcheanceSend" accept=".pdf,application/pdf" required style="border-radius: 5px;">
                        </div>
                   
                        <div class="col-md-4 form-group">
                            <label for="selectEcheance">Numéro de l’échéance<b>*</b> </label>
                            <select name="selectSendEcheance" id="selectSendEcheance" class="form-control" required style="border-radius: 5px;">
                                <option value="">_________</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                              
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="selectEcheance">Date Emission<b>*</b> </label>
                            <input type="date" class="form-control" id="dateEmissionFactureEcheance"  name="dateEmissionFactureEcheance" style="border-radius: 5px;" required>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="NumeroFacture">Numéro Facture<b>* </b> </label>
                            <input type="text" class="form-control" id="numeroFactureEcheance"  name="numeroFactureEcheance" required style="border-radius: 5px;">
                        </div>
                     </div>

                     <div class="row additionalField"  style="display: none;">
                        <div class="col-md-4 form-group">
                            <label for="selectEcheance">Motif à intégrer. </label>
                            <select name="motifSend2" id="motifSend2"  class="form-control" style="border-radius: 5px;">
                                <option value="">_________</option>
                                <option value="MOBILITE">MOBILITE</option>
                                <option value="HEBERGEMENT">HEBERGEMENT</option>
                                <option value="RESTAURATION">RESTAURATION</option>
                                <option value="MAJORATION_RQTH">MAJORATION RQTH</option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="selectEcheance">Motif à intégrer. </label>
                            <select name="motifSend3" id="motifSend3"  class="form-control" style="border-radius: 5px;">
                                <option value="">_________</option>
                                <option value="MOBILITE">MOBILITE</option>
                                <option value="HEBERGEMENT">HEBERGEMENT</option>
                                <option value="RESTAURATION">RESTAURATION</option>
                                <option value="MAJORATION_RQTH">MAJORATION RQTH</option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="selectEcheance">Motif à intégrer. </label>
                            <select name="motifSend4" id="motifSend4"  class="form-control" style="border-radius: 5px;">
                                <option value="">_________</option>
                                <option value="MOBILITE">MOBILITE</option>
                                <option value="HEBERGEMENT">HEBERGEMENT</option>
                                <option value="RESTAURATION">RESTAURATION</option>
                                <option value="MAJORATION_RQTH">MAJORATION RQTH</option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="selectEcheance">Motif à intégrer.</label>
                            <select name="motifSend5" id="motifSend5"  class="form-control" style="border-radius: 5px;">
                                <option value="">_________</option>
                                <option value="MOBILITE">MOBILITE</option>
                                <option value="HEBERGEMENT">HEBERGEMENT</option>
                                <option value="RESTAURATION">RESTAURATION</option>
                                <option value="MAJORATION_RQTH">MAJORATION RQTH</option>
                            </select>
                        </div>


                     </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="photoBtn btn btn-default" style="border-radius: 5px;">ENVOYER</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
// loader all  update 
function toggleButtonLoadingContrat(isLoading, isSuccess) {
    var $button = $('#updateContrat');
    if (isLoading) {
        $button.html('<i class="fa fa-refresh fa-spin"></i> Chargement...').prop('disabled', true);
    } else {
        if (isSuccess) {
            $button.html('Modifier').prop('disabled', false);
        } else {
            $button.html('Valider').prop('disabled', false);
        }
    }
}
function toggleButtonLoading(isLoading,isSuccess) {
    var $button = $('#update');
    if (isLoading) {
        $button.html('<i class="fa fa-refresh fa-spin"></i> Chargement...').prop('disabled', true);
    } else {
        if (isSuccess) {
            $button.html('Modifier').prop('disabled', false);
        } else {
            $button.html('Valider').prop('disabled', false);
        }
    }
}

// fonction etudiant
document.getElementById('update').addEventListener('click', function() {
    const documents = document.querySelectorAll('#section2 .documents ul');
    const documentsLabels = document.querySelectorAll('#section2 .documents label');
    const updateButton = this;
    const deleteButton = document.getElementById('delete');

    if (updateButton.textContent === 'Modifier') {
        documents.forEach((ul, index) => {
            const li = ul.querySelector('li');
            const inputGroup = ul.querySelector('.form-group');
            const input = inputGroup.querySelector('input');
            const select = inputGroup.querySelector('select');
          
            
            const label = documentsLabels[index];
            const b = label ? label.querySelector('b') : null;
            if (b) b.style.display = 'inline';

            if (input) {
                li.style.display = 'none';
                inputGroup.style.display = 'block';
                // For input elements
                if(li.textContent !== "Pas renseigné"){
                    input.value = li.textContent;
                }
               
            } else if (select) {
                // For select elements
                const selectedOption = select.querySelector(`option[value="${li.textContent}"]`);
                if (selectedOption) {
                    select.value = selectedOption.value;
                }
                li.style.display = 'none';
                inputGroup.style.display = 'block';
            }
        });

        deleteButton.style.display = 'block'; // Show the delete button
        updateButton.textContent = 'Valider';
    } 
    else if (updateButton.textContent === 'Valider') {
       
        nomA = $('#nomA').val();
        nomuA = $('#nomuA').val();
        prenomA = $('#prenomA').val();
        sexeA = $('#sexeA').val();
        naissanceA = $('#naissanceA').val();
        departementA = $('#departementA').val();
        communeNA = $('#communeNA').val();
        nationaliteA = $('#nationaliteA').val();
        regimeA = $('#regimeA').val();
        situationA = $('#situationA').val();
        titrePA = $('#titrePA').val();
        derniereCA = $('#derniereCA').val();
        securiteA = $('#securiteA').val();
        intituleA = $('#intituleA').val();
        titreOA = $('#titreOA').val();
        declareSA = $('#declareSA').val();
        declareHA = $('#declareHA').val();
        declareRA = $('#declareRA').val();
        rueA =$('#rueA').val();
        voieA = $('#voieA').val();
        complementA = $('#complementA').val();
        postalA = $('#postalA').val();
        communeA = $('#communeA').val();
        numeroA = $('#numeroA').val();
        emailA = $('#emailA').val();


        nomR = $('#nomR').val();
        prenomR = $('#prenomR').val();
        emailR = $('#emailR').val();
        rueR =$('#rueR').val();
        voieR = $('#voieR').val();
        complementR = $('#complementR').val();
        postalR = $('#postalR').val();
        communeR = $('#communeR').val();

       // les donneees que l'envoie 

        id= $('#idElement').val();
   
        
        if (nomA === '' || prenomA === '' || sexeA === '' || naissanceA === '' || 
            departementA === '' || communeNA === '' || nationaliteA === '' || 
            regimeA === '' || situationA === '' || titrePA === '' || derniereCA === '' || 
            intituleA === '' || titreOA === '' || declareSA === '' || declareHA === '' || 
            declareRA === '' ||  voieA === ''  || communeA === '') {
            
            toastr.error("Veuillez remplir tous les champs obligatoires de l'apprenti(e).", 'Oups!');
            return;
        }

        if(emailA ===''){
            toastr.error("Veuillez remplir l'email de l'apprenti(e). ",'Oups!');
            return;
        }

        if (!/^\d{10}$/.test(numeroA)) {
                toastr.error("Veuillez remplir exactement 10 chiffres sur le numéro de l'apprenti(e).",'Oups!');
                return ;
            }

        if (!/^\d{13,15}$/.test(securiteA)) {
            toastr.options.timeOut = 2000;
            toastr.error("Le numéro de sécurité sociale de l'apprenti(e) doit contenir entre 13 et 15 caractères.",'Oups!');
            return ;
        }


        if (!/^\d{5}$/.test(postalA)) {
                toastr.error("Le code postal de l'apprenti(e) doit contenir exactement 5 chiffres.",'Oups!');
                return ;
        }
            
       if(postalR !== ""){
        if (!/^\d{5}$/.test(postalR)) {
                toastr.error("Le code postal du représentant légal doit contenir exactement 5 chiffres.",'Oups!');
                return ;
        }
       }
        
        
     
        $.ajax({
            type: 'POST',
            url: 'cerfas/updateCerfaEtudiant', 
            data: {

            nomA: nomA,
            nomuA: nomuA,
            prenomA: prenomA,
            sexeA: sexeA,
            naissanceA: naissanceA,
            departementA: departementA,
            communeNA: communeNA,
            nationaliteA: nationaliteA,
            regimeA: regimeA,
            situationA: situationA,
            titrePA: titrePA,
            derniereCA: derniereCA,
            securiteA: securiteA,
            intituleA: intituleA,
            titreOA: titreOA,
            declareSA: declareSA,
            declareHA: declareHA,
            declareRA: declareRA,
            rueA: rueA,
            voieA: voieA,
            complementA: complementA,
            postalA: postalA,
            communeA: communeA,
            numeroA: numeroA,
            emailA: emailA,

            nomR: nomR,
            prenomR: prenomR,
            emailR: emailR,
            rueR: rueR,
            voieR: voieR,
            complementR: complementR,
            postalR: postalR,
            communeR: communeR,

            id: id,
            action: 'edit'
            },
            
            beforeSend: function () {
                toggleButtonLoading(true,false);
            },
            success: function (json) {
                console.log(json);
                //toastr.success(json, 'Succès!');
                if (json && typeof json === 'object') {
                        if ('statuts' in json && json.statuts === 0) {
                            toastr.success(json.mes, 'Succès!');
                            window.location.reload();
                            documents.forEach((ul, index) => {
                                const li = ul.querySelector('li');
                                const inputGroup = ul.querySelector('.form-group');
                                const input = inputGroup.querySelector('input');
                                const select = inputGroup.querySelector('select');

                                //if (b) b.style.display = 'none';
                                if (input) {
                                    // For input elements
                                    if (input.value.trim() !== '') {
                                        li.textContent = input.value;
                                    }
                                   
                                    inputGroup.style.display = 'none';
                                    li.style.display = 'block';
                                } else if (select) {
                                    if (select.value.trim() !== '') {
                                        li.textContent = select.options[select.selectedIndex].text;
                                    }
                                    inputGroup.style.display = 'none';
                                    li.style.display = 'block';
                                }
                            });
                            deleteButton.style.display = 'none'; 
                            toggleButtonLoading(false, true);

                        } else if ('mes' in json) {
                            toastr.error(json.mes, 'Oups!');
                            toggleButtonLoading(false, false);
                        } else {
                            console.error('Structure de réponse inattendue:', json);
                        }
                } else {
                        console.error('Réponse invalide:', json);
                }
            },
            complete: function () {
              //toggleButtonLoading(false);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            }
        });

      

    }
});
document.getElementById('delete').addEventListener('click', function() {
    const documents = document.querySelectorAll('#section2 .documents ul');
    const updateButton = document.getElementById('update');
    const documentsLabels = document.querySelectorAll('#section2 .documents label');

    documents.forEach((ul,index) => {
        const li = ul.querySelector('li');
        const inputGroup = ul.querySelector('.form-group');

        const label = documentsLabels[index];
        const b = label ? label.querySelector('b') : null;
        if (b) b.style.display = 'none';

        li.style.display = 'block';
        inputGroup.style.display = 'none';
    });

    updateButton.textContent = 'Modifier' ;
    this.style.display = 'none'; // Hide the delete button
});

document.getElementById('sendformEtudiant').addEventListener('click', function() {
  
    
        var url = "cerfas/send",
            id= $('#idElement').val();
        swal({
                title: "Etes vous sûr?",
                text: "Le formulaire cerfa va être envoyé à l'apprenti(e).",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#153C4A",
                confirmButtonText: "Oui, valider!",
                cancelButtonText: "Annuler",
                closeOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url : url,
                        data: 'id='+id,
                        datatype: 'json',
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        complete: function () {
                            dismiss_waitMe();
                            //window.location.reload();
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                                toastr.success(json.mes,'Succès!');
                                //window.location.reload();
                            } else {
                         	       toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown){}
                    });
                }
            });
    
  
});

document.getElementById('sendformSignatureEtudiantRepresentant').addEventListener('click', function() {
  
    
  var url = "cerfas/sendSignatureApprentiRepresentant",
      id= $('#idElement').val();
      swal({
                title: "Etes vous sûr?",
                text: "Le formulaire CERFA va être envoyé au représentant pour signature.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#153C4A",
                confirmButtonText: "Oui, valider!",
                cancelButtonText: "Annuler",
                closeOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url : url,
                        data: { id: id },
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        complete: function () {
                            dismiss_waitMe();
                            //window.location.reload();
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                                toastr.success(json.mes,'Succès!');
                                //window.location.reload();
                            } else {
                         	    toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            console.error('Error:', jqXHR, textStatus, errorThrown); 
                            toastr.error(jqXHR+ textStatus+ errorThrown,'Oups!');
                        }
                    });
                }
            });
 

});

document.getElementById('sendformSignatureEtudiant').addEventListener('click', function() {
  
    
  var url = "cerfas/sendSignatureApprenti",
      id= $('#idElement').val();
      swal({
                title: "Etes vous sûr?",
                text: "Le formulaire  cerfa va être envoyé à l'apprenti(e) pour signature.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#153C4A",
                confirmButtonText: "Oui, valider!",
                cancelButtonText: "Annuler",
                closeOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url : url,
                        data: { id: id },
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        complete: function () {
                            dismiss_waitMe();
                            //window.location.reload();
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                                toastr.success(json.mes,'Succès!');
                                //window.location.reload();
                            } else {
                         	    toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            console.error('Error:', jqXHR, textStatus, errorThrown); 
                            toastr.error(jqXHR+ textStatus+ errorThrown,'Oups!');
                        }
                    });
                }
            });
 

});


//entreprise signature
document.getElementById('sendformEntreprise').addEventListener('click', function() {
  
    
  var url = "cerfas/sendEmployeur",
      id= $('#idElement').val();
      swal({
                title: "Etes vous sûr?",
                text: "Le formulaire cerfa va être envoyé à l'employeur.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#153C4A",
                confirmButtonText: "Oui, valider!",
                cancelButtonText: "Annuler",
                closeOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url : url,
                        data: { id: id },
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        complete: function () {
                            dismiss_waitMe();
                            //window.location.reload();
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                                toastr.success(json.mes,'Succès!');
                                //window.location.reload();
                            } else {
                         	    toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            console.error('Error:', jqXHR, textStatus, errorThrown); 
                            toastr.error(jqXHR+ textStatus+ errorThrown,'Oups!');
                        }
                    });
                }
            });
 

});

document.getElementById('sendformSignatureEntreprise').addEventListener('click', function() {
  
    
  var url = "cerfas/sendSignatureEntreprise",
      id= $('#idElement').val();
      swal({
                title: "Etes vous sûr?",
                text: "Le formulaire  cerfa va être envoyé à l'employeur pour signature.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#153C4A",
                confirmButtonText: "Oui, valider!",
                cancelButtonText: "Annuler",
                closeOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url : url,
                        data: { id: id },
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        complete: function () {
                            dismiss_waitMe();
                            //window.location.reload();
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                                toastr.success(json.mes,'Succès!');
                                //window.location.reload();
                            } else {
                         	    toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            console.error('Error:', jqXHR, textStatus, errorThrown); 
                            toastr.error(jqXHR+ textStatus+ errorThrown,'Oups!');
                        }
                    });
                }
            });
 

});

document.getElementById('sendformSignatureConventionEntreprise').addEventListener('click', function() {
  
    
  var url = "cerfas/sendSignatureConventionEntreprise",
      id= $('#idElement').val();
      swal({
                title: "Etes vous sûr?",
                text: "La convention va être envoyée à l'employeur pour signature.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#153C4A",
                confirmButtonText: "Oui, valider!",
                cancelButtonText: "Annuler",
                closeOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url : url,
                        data: { id: id },
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        complete: function () {
                            dismiss_waitMe();
                            //window.location.reload();
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                                toastr.success(json.mes,'Succès!');
                                //window.location.reload();
                            } else {
                         	    toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            console.error('Error:', jqXHR, textStatus, errorThrown); 
                            toastr.error(jqXHR+ textStatus+ errorThrown,'Oups!');
                        }
                    });
                }
            });
 

});

//new
document.getElementById('sendformContratEntreprise').addEventListener('click', function() {
  
    
  var url = "cerfas/sendContratEmployeur",
      id= $('#idElement').val();
      swal({
                title: "Etes vous sûr?",
                text: "L'employeur recevra un formulaire à remplir avec les informations du contrat.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#153C4A",
                confirmButtonText: "Oui, valider!",
                cancelButtonText: "Annuler",
                closeOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url : url,
                        data: { id: id },
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        complete: function () {
                            dismiss_waitMe();
                            //window.location.reload();
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                                toastr.success(json.mes,'Succès!');
                                //window.location.reload();
                            } else {
                         	    toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            console.error('Error:', jqXHR, textStatus, errorThrown); 
                            toastr.error(jqXHR+ textStatus+ errorThrown,'Oups!');
                        }
                    });
                }
            });
 

});

//ecole signature
document.getElementById('sendformSignatureEcole').addEventListener('click', function() {
  
    
  var url = "cerfas/sendSignatureEcole",
      id= $('#idElement').val();
      swal({
                title: "Etes vous sûr?",
                text: "Le formulaire  cerfa va être envoyé à l'école pour signature.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#153C4A",
                confirmButtonText: "Oui, valider!",
                cancelButtonText: "Annuler",
                closeOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url : url,
                        data: { id: id },
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        complete: function () {
                            dismiss_waitMe();
                            //window.location.reload();
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                                toastr.success(json.mes,'Succès!');
                                //window.location.reload();
                                $('.signatureModal').modal('hide');
                            } else {
                         	    toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            console.error('Error:', jqXHR, textStatus, errorThrown); 
                            toastr.error(jqXHR+ textStatus+ errorThrown,'Oups!');
                        }
                    });
                }
            });
 

});

document.getElementById('sendformSignatureConventionEcole').addEventListener('click', function() {
  
    
  var url = "cerfas/sendSignatureConventionEcole",
      id= $('#idElement').val();
      swal({
                title: "Etes vous sûr?",
                text: "La convention va être envoyée à l'école pour signature.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#153C4A",
                confirmButtonText: "Oui, valider!",
                cancelButtonText: "Annuler",
                closeOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url : url,
                        data: { id: id },
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        complete: function () {
                            dismiss_waitMe();
                            //window.location.reload();
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                                toastr.success(json.mes,'Succès!');
                                //window.location.reload();
                                $('.signatureConventionModal').modal('hide');
                            } else {
                         	    toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            console.error('Error:', jqXHR, textStatus, errorThrown); 
                            toastr.error(jqXHR+ textStatus+ errorThrown,'Oups!');
                        }
                    });
                }
            });
 

});

// fonction contrat 
document.getElementById('updateContrat').addEventListener('click', function() {
    const documents = document.querySelectorAll('#section4 .documents ul');
    const updateButton = this;
    const deleteButton = document.getElementById('deleteContrat');
    const documentsLabels = document.querySelectorAll('#section4 .documents label');

    if (updateButton.textContent === 'Modifier') {
        documents.forEach((ul, index) => {
            const li = ul.querySelector('li');
            const inputGroup = ul.querySelector('.form-group');
            const input = inputGroup.querySelector('input');
            const select = inputGroup.querySelector('select');

            const label = documentsLabels[index];
            const b = label ? label.querySelector('b') : null;
            if (b) b.style.display = 'inline';

            if (input) {
                li.style.display = 'none';
                inputGroup.style.display = 'block';
                // For input elements
                if(li.textContent !== "Pas renseigné"){
                    input.value = li.textContent;
                }else if(li.textContent === "pas renseigné"){

                   input.value = "";
                }
               
            } else if (select) {
                // For select elements
                const selectedOption = select.querySelector(`option[value="${li.textContent}"]`);
                if (selectedOption) {
                    select.value = selectedOption.value;
                }
                li.style.display = 'none';
                inputGroup.style.display = 'block';
            }
        });

        deleteButton.style.display = 'block'; // Show the delete button
        updateButton.textContent = 'Valider';
    } 
    else if (updateButton.textContent === 'Valider') {

        // les donneees que l'envoie 
        
            nomM = $('#nomM').val();
            prenomM = $('#prenomM').val();
            naissanceM = $('#naissanceM').val();
            securiteM = $('#securiteM').val();
            emailM = $('#emailM').val();
            emploiM = $('#emploiM').val();
            diplomeM = $('#diplomeM').val();
            niveauM = $('#niveauM').val();

            nomM1 = $('#nomM1').val();
            prenomM1 = $('#prenomM1').val();
            naissanceM1 = $('#naissanceM1').val();
            securiteM1 = $('#securiteM1').val();
            emailM1 = $('#emailM1').val();
            emploiM1 = $('#emploiM1').val();
            diplomeM1 = $('#diplomeM1').val();
            niveauM1 = $('#niveauM1').val();

            travailC=$('#travailC').val();
            modeC=$('#modeC').val();
            derogationC=$('#derogationC').val();
            numeroC=$('#numeroC').val();
            conclusionC=$('#conclusionC').val();
            debutC=$('#debutC').val();
            finC=$('#finC').val();
            avenantC=$('#avenantC').val();
            executionC=$('#executionC').val();
            dureC=$('#dureC').val();
            dureCM=$('#dureCM').val();
            typeC=$('#typeC').val();
            rdC=$('#rdC').val();
            raC=$('#raC').val();
            rpC=$('#rpC').val();
            rsC=$('#rsC').val();

            rdC1=$('#rdC1').val();
            raC1=$('#raC1').val();
            rpC1=$('#rpC1').val();
            rsC1=$('#rsC1').val();

            rdC2=$('#rdC2').val();
            raC2=$('#raC2').val();
            rpC2=$('#rpC2').val();
            rsC2=$('#rsC2').val();

            salaireC=$('#salaireC').val();
            caisseC=$('#caisseC').val();
            logementC=$('#logementC').val();
            avantageC=$('#avantageC').val();
            autreC=$('#autreC').val();

            lieuO = $('#lieuO').val();
            priveO = $('#priveO').val();
            attesteO = "oui";

            id= $('#idElement').val();

        if (travailC === '' || modeC === ''  || conclusionC === '' || 
            debutC === '' || finC === '' || executionC === '' || 
            dureC === '' || dureCM === '' ||   typeC === '' || rdC === '' || 
            raC === '' || rpC === '' || rsC === '' || salaireC === '' || 
            caisseC === '' || lieuO === '' || priveO === ''  || nomM === '' || prenomM === '' || naissanceM ==='' || emailM=== ''  || emploiM=== '' || diplomeM=== '' || niveauM=== '') {
            
            toastr.error("Veuillez remplir tous les champs obligatoires de l'apprenant", 'Oups!');
            return;
        }

        if (securiteM1 && !/^\d{13,15}$/.test(securiteM1)) {
                toastr.error("Le numéro de sécurité sociale du maitre de stage 2 doit contenir entre 13 et 15 caractères.",'Oups!');
                return ;
        }
        
           if(isNaN(salaireC.trim())){
                toastr.error("Veuillez remplir correctement le champ salaire ",'Oups!');
                return;
            }

            if (!/^\d{13,15}$/.test(securiteM)) {
                toastr.error("Le numéro de sécurité sociale du maitre de stage 1 doit contenir entre 13 et 15 caractères.",'Oups!');
                return ;
            }

            if (!parseInt(dureCM, 10) > 59) {
                toastr.error("La durée du contrat, exprimée en minutes, ne doit pas dépasser 59.", 'Oups!');
                return;
            }

          
        
        $.ajax({
            type: 'POST',
            url: 'cerfas/updateCerfaContrat', 
            data: {

                nomM: nomM,
                prenomM: prenomM,
                naissanceM: naissanceM,
                securiteM: securiteM,
                emailM: emailM,
                emploiM: emploiM,
                diplomeM: diplomeM,
                niveauM: niveauM,

                nomM1: nomM1,
                prenomM1: prenomM1,
                naissanceM1: naissanceM1,
                securiteM1: securiteM1,
                emailM1: emailM1,
                emploiM1: emploiM1,
                diplomeM1: diplomeM1,
                niveauM1: niveauM1,

                travailC: travailC,
                modeC: modeC,
                derogationC: derogationC,
                numeroC: numeroC,
                conclusionC: conclusionC,
                debutC: debutC,
                finC: finC,
                avenantC: avenantC,
                executionC: executionC,
                dureC: dureC,
                dureCM: dureCM,
                typeC: typeC,
                rdC: rdC,
                raC: raC,
                rpC: rpC,
                rsC: rsC,
                rdC1: rdC1,
                raC1: raC1,
                rpC1: rpC1,
                rsC1: rsC1,

                rdC2: rdC2,
                raC2: raC2,
                rpC2: rpC2,
                rsC2: rsC2,

                salaireC: salaireC,
                caisseC: caisseC,
                logementC: logementC,
                avantageC: avantageC,
                autreC: autreC,
                
                lieuO: lieuO,
                priveO: priveO,
                attesteO: attesteO,

                id: id,
                action: 'edit'
            },
            datatype: 'json', 
            beforeSend: function () {
                toggleButtonLoadingContrat(true, false);
            },
            success: function (json) {
                if (json && typeof json === 'object') {
                        if ('statuts' in json && json.statuts === 0) {
                            toastr.success(json.mes, 'Succès!');
                            window.location.reload();
                            documents.forEach((ul, index) => {
                                const li = ul.querySelector('li');
                                const inputGroup = ul.querySelector('.form-group');
                                const input = inputGroup.querySelector('input');
                                const select = inputGroup.querySelector('select');

                                //if (b) b.style.display = 'none';
                                if (input) {
                                    // For input elements
                                    if (input.value.trim() !== '') {
                                        li.textContent = input.value;
                                    }
                                   
                                    inputGroup.style.display = 'none';
                                    li.style.display = 'block';
                                } else if (select) {
                                    if (select.value.trim() !== '') {
                                        li.textContent = select.options[select.selectedIndex].text;
                                    }
                                    inputGroup.style.display = 'none';
                                    li.style.display = 'block';
                                }
                            });
                            deleteButton.style.display = 'none'; 
                            toggleButtonLoadingContrat(false, true);

                        } else if ('mes' in json) {
                            toastr.error(json.mes, 'Oups!');
                            toggleButtonLoadingContrat(false, false);
                        } else {
                            console.error('Structure de réponse inattendue:', json);
                        }
                } else {
                        console.error('Réponse invalide:', json);
                }
            },
            complete: function () {},
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            }
        });

    }
});
document.getElementById('deleteContrat').addEventListener('click', function() {
        const documents = document.querySelectorAll('#section4 .documents ul');
        const updateButton = document.getElementById('updateContrat');
        const documentsLabels = document.querySelectorAll('#section4 .documents label');

        documents.forEach((ul, index) => {
            const li = ul.querySelector('li');
            const inputGroup = ul.querySelector('.form-group');

            const label = documentsLabels[index];
            const b = label ? label.querySelector('b') : null;
            if (b) b.style.display = 'none';

            li.style.display = 'block';
            inputGroup.style.display = 'none';
        });

        updateButton.textContent = 'Modifier' ;
        this.style.display = 'none'; // Hide the delete button
    });


//fonction remplissage facture 
document.getElementById('sendOpcoFacture').addEventListener('click', function() {
    var  id= $('#idElement').val();
    $('#idFacture').val(id);
    $('.FactureModal').modal({backdrop: 'static'});
 });

 //fonction cerfa
 document.getElementById('sendOpcoCerfa').addEventListener('click', function() {
    var  id= $('#idElement').val();
    var  idProduitCerfa= $(this).attr('data-idProduitCerfa');
    $('#idCerfa').val(id); 
    $('#idProduitCerfa').val(idProduitCerfa);
    $('.cerfaModal').modal({backdrop: 'static'});
});

//fonction convention 
document.getElementById('sendOpcoConvention').addEventListener('click', function() {
        var  id= $('#idElement').val();
        $('#idConvention').val(id);
        $('.conventionModal').modal({backdrop: 'static'});

});

//fonction send document externe
document.getElementById('sendOpcoDocument').addEventListener('click', function() {
        var  id= $('#idElement').val();
        $('#idDocument').val(id);
        $('.documentModal').modal({backdrop: 'static'});

});

document.getElementById('remplirConvention').addEventListener('click', function() {
        var  id= $('#idElement').val();
        $('#idRemplirConvention').val(id);
        $('.conventionModalRemplir').modal({backdrop: 'static'});

});

//fonction envoie facture 
document.getElementById('afficheFacture').addEventListener('click', function() {
        $('.factureModalView').modal({backdrop: 'static'});
        var url = $('#urlFactureOpco').val();
        var  id= $('#idElement').val();
        $('#idFactureSend').val(id);
        $('#urlsend').val(url);
       
            const documentContainer = document.getElementById("documentContainerFacture");
            const fileType = url.split('.').pop().toLowerCase();
            documentContainer.innerHTML = '';
            
            if (fileType === 'pdf') {
                const pdfViewer = document.createElement('iframe');
                pdfViewer.src = url + '?v=' + new Date().getTime(); 
                pdfViewer.width = "100%";
                pdfViewer.height = "600px";
                documentContainer.appendChild(pdfViewer);
            } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileType)) {
                const imageViewer = document.createElement('img');
                imageViewer.src = url;
                imageViewer.style.maxWidth = "100%";
                imageViewer.style.height = "auto";
                documentContainer.appendChild(imageViewer);
            } else {
                documentContainer.innerHTML = 'Unsupported file type.';
            }
        
    });

//fonction select signature + signature Manuelle Ecole + convention
document.getElementById('selectActionSignature').addEventListener('click', function() {
        $('.signatureModal').modal({backdrop: 'static'});

});

document.getElementById('selectActionSignatureConvention').addEventListener('click', function() {
        $('.signatureConventionModal').modal({backdrop: 'static'});

});

// changer l'entrepise
document.getElementById('changeEntreprise').addEventListener('click', function() {
        var  id= $('#idElement').val();
        $('#idElementchangeentreprise').val(id);
        $('.changeEntrepriseModal').modal({backdrop: 'static'});

});

// changer la formation
document.getElementById('changeFormation').addEventListener('click', function() {
        var  id= $('#idElement').val();
        $('#idElementchangeformation').val(id);
        $('.changeFormationModal').modal({backdrop: 'static'});

});

document.getElementById('signatureManuelleEcole').addEventListener('click', function() {
        var  id= $('#idElement').val();
        $('#idsignatureManuelleEcole').val(id);
        $('.signatureModal').modal('hide');
        $('.signatureManuelleEcoleModal').modal({backdrop: 'static'});

});

document.getElementById('signatureManuelleConventionEcole').addEventListener('click', function() {
        var  id= $('#idElement').val();
        $('#idsignatureManuelleConvenionEcole').val(id);
        $('.signatureConventionModal').modal('hide');
        $('.signatureManuelleConventionEcoleModal').modal({backdrop: 'static'});

});

//remplir une echeance  remplirEcheance
document.getElementById('remplirEcheance').addEventListener('click', function() {
        var  id= $('#idElement').val();
        $('#idremplirEcheance').val(id);
        $('.remplirEcheanceModal').modal({backdrop: 'static'});

});


// Envoie d'une echeance 
document.getElementById('sendEcheance').addEventListener('click', function() {
        var  id= $('#idElement').val();
        var  idProduitCerfas= $(this).attr('data-idProduitCerfas');
        $('#idFactureSend').val(id);
        $('#idProduitCerfas').val(idProduitCerfas);
        $('.EnvoieEcheanceModal').modal({backdrop: 'static'});
     

});


//selection de l'echeance pour afficher le champ certificat de realisation et motif lors de l'envoie d'une echeance
document.getElementById('selectSendEcheance').addEventListener('change', function() {
    var selectedValue = this.value;
    var additionalFields = document.querySelectorAll('.additionalField');
   

    if (selectedValue !== '1' && selectedValue !== '') {
        // Afficher chaque champ ayant la classe 'additionalFields'
        additionalFields.forEach(function(field) {
            field.style.display = 'flex'; // Affiche l'élément
        });
        
       
    } else {
        // Masquer chaque champ ayant la classe 'additionalFields'
        additionalFields.forEach(function(field) {
            field.style.display = 'none'; // Cache l'élément
        });
        
       
    }
});

//selection de l'echeance pour afficher le champ certificat de realisation et motif lors du remplissage d'une echeance
document.getElementById('selectEcheance').addEventListener('change', function() {
    var selectedValue = this.value;
    var additionalFields = document.querySelectorAll('.additionalFields');
    var fileCertificat = document.getElementById('fileCertificat');
    var dateDebutCertificat = document.getElementById('dateDebutCertificat');
    var dateFinCertificat = document.getElementById('dateFinCertificat');
    var duree = document.getElementById('duree');

    if (selectedValue !== '1' && selectedValue !== '') {
        // Afficher chaque champ ayant la classe 'additionalFields'
        additionalFields.forEach(function(field) {
            field.style.display = 'flex'; // Affiche l'élément
        });
        
        // Ajouter l'attribut required
        fileCertificat.setAttribute('required', 'required');
        dateDebutCertificat.setAttribute('required', 'required');
        dateFinCertificat.setAttribute('required', 'required');
        duree.setAttribute('required', 'required');
    } else {
        // Masquer chaque champ ayant la classe 'additionalFields'
        additionalFields.forEach(function(field) {
            field.style.display = 'none'; // Cache l'élément
        });
        
        // Retirer l'attribut required et réinitialiser les champs
        fileCertificat.removeAttribute('required');
        fileCertificat.value = '';
        dateDebutCertificat.removeAttribute('required');
        dateDebutCertificat.value = '';
        dateFinCertificat.removeAttribute('required');
        dateFinCertificat.value = '';
        duree.removeAttribute('required');
        duree.value = '';
    }
});


document.addEventListener('DOMContentLoaded', function() {

    //new 

    function updateNumeroDeca(numeroInterne, opco, itemId) {
       
       // Appel AJAX pour récupérer le numéro DECA
        $.ajax({
            type: 'POST',
            url: 'cerfas/getNumeroDecas',
            data: {
                numeroInterne: numeroInterne,
                opco: opco
            },
            dataType: 'json',
            success: function (data) {
                const decaElement = document.getElementById('decaPlaceholder');

                if (data.numeroDeca && data.numeroDeca.indexOf('Erreur') === -1) {
                    // Mise à jour en base avec un autre appel AJAX
                    $.ajax({
                        type: 'POST',
                        url: 'cerfas/updateNumeroDeca',
                        data: {
                            numeroDeca: data.numeroDeca,
                            itemId: itemId
                        },
                        dataType: 'json',
                        success: function (updateData) {
                            if (updateData.success) {
                                decaElement.innerText = `ID Deca : ${data.numeroDeca}`;
                            } else {
                                decaElement.innerText = `Erreur : ${updateData.message}`;
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.error('Erreur lors de la mise à jour en base :', jqXHR, textStatus, errorThrown);
                            decaElement.innerText = 'Erreur lors de la mise à jour en base.';
                        }
                    });
                } else {
                    decaElement.innerText = "ID contrat :  "+<?= json_encode($items->numeroExterne) ?>;
                  
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Erreur lors de la récupération du numéro DECA :', jqXHR, textStatus, errorThrown);
                document.getElementById('decaPlaceholder').innerText = `Erreurss : ${textStatus}`;
            }
        });

}


    // Fonction pour mettre à jour l'état
    function updateEtatLabel(numeroInterne, opco) {
       
        
            $.ajax({
            type: 'post',
            url: 'cerfas/getEtatLabels',
            data: {
                numeroInterne: numeroInterne,
                opco: opco,
                type: 4
            },
            datatype: 'json', 
            success: function (json) {
                if (json.statuts === 0) {
                    const etatLabelElement = document.getElementById('etatLabel');
                    const etatLabel = <?= json_encode(StringHelper::$tabetatcerfa) ?>[json.mes] || '<span class="label label-warning" style="border-radius: 5px;border: 0px solid #ccc;">Statut :'+json.mes[0].code +"-"+ json.mes[0].description  +'</span>';
                    etatLabelElement.innerHTML = etatLabel;
                    
                } else {
                    console.error("Erreur lors de la récupération de l'état :", json.mes);
                    const etatLabelElement = document.getElementById('etatLabel');
                    etatLabelElement.innerText = "Erreur lors de la récupération de l'état."+ json.mes;
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error(jqXHR + textStatus + errorThrown);
            }
        });
       
    }

    //fonction  pour mettre a jour les echeances
    function updateEcheances(numeroInterne, opco) {
        const loadingRow = document.getElementById('loadingRow');
        loadingRow.style.display = 'table-row'; // Afficher la ligne du loader
        $.ajax({
        type: 'post',
        url: 'cerfas/getEtatLabels',  // L'URL de votre API
        data: {
            numeroInterne: numeroInterne,
            opco: opco,
            type: 1  // Type 1 pour les échéances, selon votre logique
        },
        datatype: 'json',
        success: function (json) {
            if (json.statuts === 0) {
                console.log(json.mes)
                if(json.mes[0].code){
                    loadingRow.style.display = 'none';
                    // Gestion des erreurs
                    const errorMsg = json.mes[0].description || "Erreur lors de la récupération des échéances.";
                    console.error(errorMsg);

                    const echeancesContainer = document.getElementById('echeances-container');
                    echeancesContainer.innerHTML = `
                    <div class="notifications-container" style="width: 100%;">
                        <div class="error-alert">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="error-svg">
                                    <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" fill-rule="evenodd"></path>
                                </svg>
                                </div>
                                <div class="error-prompt-container">
                                    <p class="error-prompt-heading">
                                        <strong>Code :</strong>  ${json.mes[0].code}<br>
                                        <strong>Description :</strong>  ${json.mes[0].description} 
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    `; 

                }
                else if(!json.mes || json.mes.length === 0){
                    loadingRow.style.display = 'none';
    
                    // Gestion d'erreur avec un message par défaut
                    const errorMsg = "Erreur lors de la récupération des échéances. Votre dossier est transmis, patientez pour son examen.";
                    console.error(errorMsg);

                    const echeancesContainer = document.getElementById('echeances-container');
                    echeancesContainer.innerHTML = `
                    <div class="notifications-container" style="width: 100%;">
                        <div class="error-alert">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="error-svg">
                                        <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" fill-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="error-prompt-container">
                                    <p class="error-prompt-heading">
                                         Votre dossier est transmis. Patientez pour son examen.<br>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;
                }
                else{
                    loadingRow.style.display = 'none'; // Cacher la ligne de loader
                    const echeancesContainerheader = document.getElementById('echeances');
                    echeancesContainerheader.innerHTML = ''; // Vider le conteneur avant de le remplir

                    const rows = document.createElement('tr');
                    rows.innerHTML = `
                        <th class=''>Date Ouverture</th>
                        <th class=''>Montant Total</th>
                        <th class='text-left'>Dont Majoration RQTH</th>
                        <th class='text-left'>Dont Montant Pédagogie</th>
                        <th class='text-left'>Montant Réglé</th>
                        <th class='text-left'>Montant En Cours d'Instruction</th>
                        <th class='text-left'>Numéro</th>
                        <th class='text-left'>SIRET CFA</th>
                        <th class='text-left'>Codification</th>
                    `;
                    echeancesContainerheader.appendChild(rows);
                
                    // affichege entete echeance :  <tr>
                
                                                    
                    // Afficher les échéances récupérées
                    const echeancesContainer = document.getElementById('echeances-container');
                    echeancesContainer.innerHTML = ''; // Vider le conteneur avant de le remplir



                    json.mes.forEach((echeance, index) => {
                        // Créer une ligne de tableau pour chaque échéance
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${echeance.dateOuverture}</td>
                            <td>${echeance.montantTotal}</td>
                            <td>${echeance.dontMajorationRqth}</td>
                            <td>${echeance.dontMontantPedagogie}</td>
                            <td>${echeance.montantRegle}</td>
                            <td>${echeance.montantEnCoursInstruction}</td>
                            <td>${echeance.numero}</td>
                            <td>${echeance.siretCfa}</td>
                            <td>${echeance.codification}</td>
                        `;
                        echeancesContainer.appendChild(row); // Ajouter la ligne au tableau
                    });

                  
                }

             
            } else {
                loadingRow.style.display = 'none';
                // Gestion des erreurs
                const errorMsg = json.mes.description || "Erreur lors de la récupération des échéances.";
                console.error(errorMsg);

                const echeancesContainer = document.getElementById('echeances-container');
                echeancesContainer.innerHTML = `
                    <tr>
                        <td colspan="9" class="error-message">${errorMsg}</td>
                    </tr>
                `;
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            loadingRow.style.display = 'none';
            console.error('Erreur AJAX:', jqXHR, textStatus, errorThrown);

            const echeancesContainer = document.getElementById('echeances-container');
            echeancesContainer.innerHTML = `
                <tr>
                    <td colspan="9" class="error-message">Erreur de communication avec le serveur.</td>
                </tr>
            `;
        }
    });
}


    function updateDetailsFacturation(numeroInterne, opco) {
            const loadingRow = document.getElementById('loadingRowdetails');
            loadingRow.style.display = 'table-row'; // Afficher la ligne du loader
        $.ajax({
            type: 'post',
            url: 'cerfas/getEtatLabels',  // L'URL de votre API
            data: {
                numeroInterne: numeroInterne,
                opco: opco,
                type: 2  // Type 1 pour les échéances, selon votre logique
            },
            datatype: 'json',
            success: function (json) {
                if (json.statuts === 0) {
                    console.log(json);
                    if(json.mes && json.mes.length > 0 && json.mes[0].code){
                    loadingRow.style.display = 'none';
                    // Gestion des erreurs
                    const errorMsg = json.mes[0].description || "Erreur lors de la récupération des details de la facturation.";
                    console.error(errorMsg);

                    const echeancesContainer = document.getElementById('detailsecheances');
                    echeancesContainer.innerHTML = `
                    <div class="notifications-container" style="width: 100%;">
                        <div class="error-alert">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="error-svg">
                                    <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" fill-rule="evenodd"></path>
                                </svg>
                                </div>
                                <div class="error-prompt-container">
                                    <p class="error-prompt-heading">
                                        <strong>Code :</strong>  ${json.mes[0].code}<br>
                                        <strong>Description :</strong>  ${json.mes[0].description} 
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    `; 

                } 
                else if(json.mes=== null){
                    loadingRow.style.display = 'none';
    
                    // Gestion d'erreur avec un message par défaut
                    const errorMsg = "Erreur lors de la récupération des échéances. Votre dossier est transmis, patientez pour son examen.";
                    console.error(errorMsg);

                    const echeancesContainer = document.getElementById('echeances-container');
                    echeancesContainer.innerHTML = `
                    <div class="notifications-container" style="width: 100%;">
                        <div class="error-alert">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="error-svg">
                                        <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" fill-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="error-prompt-container">
                                    <p class="error-prompt-heading">
                                        <strong>Code :</strong> Votre dossier est transmis. Patientez pour son examen.<br>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;
                }
                else{
                    loadingRow.style.display = 'none'; // Cacher la ligne de loader
                    const echeancesContainerheader = document.getElementById('detailsecheances');
                    echeancesContainerheader.innerHTML = ''; // Vider le conteneur avant de le remplir

                    const rows = document.createElement('tr');
                    rows.innerHTML = `
                        <th>Frais Premier Équipement</th>
                        <th>Frais Mobilité</th>
                        <th class="text-left">Plafond Frais Premier Équipement</th>
                        <th class="text-left">Plafond Frais Mobilité</th>
                        <th class="text-left">Périodes Frais Annexes</th>
                    `;
                    echeancesContainerheader.appendChild(rows);
                
                    // affichege entete echeance :  <tr>
                
                                                    
                    // Afficher les échéances récupérées
                    const echeancesContainer = document.getElementById('detailsecheances-container');
                    echeancesContainer.innerHTML = ''; // Vider le conteneur avant de le remplir

                    const detailsFacturation = json.mes; // Supposons que json.mes est un objet unique

// Créer une seule ligne de tableau
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${detailsFacturation.fraisPremierEquipementRegles?"OUI" : "NON"}</td>
                        <td>${detailsFacturation.fraisMobiliteRegles?"OUI" : "NON"}</td>
                        <td>${detailsFacturation.plafondFraisPremierEquipement}</td>
                        <td>${detailsFacturation.plafondFraisMobilite}</td>
                       <td>
                            <table class="table table-condensed">
                                <thead>
                                    <tr>
                                        <th>Numéro Échéance</th>
                                        <th>Nature</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${
                                        detailsFacturation.periodesFraisAnnexes && detailsFacturation.periodesFraisAnnexes.length > 0
                                        ? detailsFacturation.periodesFraisAnnexes.map(echeance => `
                                            <tr>
                                                <td>${echeance.numeroEcheance || 'Non défini'}</td>
                                                <td>${echeance.nature || 'Non défini'}</td>
                                            </tr>
                                        `).join('')
                                        : `
                                            <tr>
                                                <td colspan="2">Aucune période annexe disponible</td>
                                            </tr>
                                        `
                                    }
                                </tbody>
                            </table>
                        </td>

                    `;
                    echeancesContainer.appendChild(row);
                }

                console.log(json.mes);
                  
                } else {
                    loadingRow.style.display = 'none';
                    // Gestion des erreurs
                    const errorMsg = json.mes.description || "Erreur lors de la récupération des échéances.";
                    console.error(errorMsg);

                    const echeancesContainer = document.getElementById('detailsecheances-container');
                    echeancesContainer.innerHTML = `
                        <tr>
                            <td colspan="9" class="error-message">${errorMsg}</td>
                        </tr>
                    `;
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                loadingRow.style.display = 'none';
                console.error('Erreur AJAX:', jqXHR, textStatus, errorThrown);

                const echeancesContainer = document.getElementById('detailsecheances-container');
                echeancesContainer.innerHTML = `
                    <tr>
                        <td colspan="9" class="error-message">Erreur de communication avec le serveur.</td>
                    </tr>
                `;
            }
        });
    }

    function updateEngagementsFraisAnnexe(numeroInterne, opco) {
        const loadingRow = document.getElementById('loadingRowfrais');
        loadingRow.style.display = 'table-row'; // Afficher la ligne du loader
        $.ajax({
        type: 'post',
        url: 'cerfas/getEtatLabels',  // L'URL de votre API
        data: {
            numeroInterne: numeroInterne,
            opco: opco,
            type: 3  // Type 1 pour les échéances, selon votre logique
        },
        datatype: 'json',
        success: function (json) {
            if (json.statuts === 0) {
                console.log(json.mes);

                // Vérifier si json.mes est défini et non vide
                if (json.mes && json.mes.length > 0 && json.mes[0].code) {
                    loadingRow.style.display = 'none';
                    
                    // Gestion des erreurs
                    const errorMsg = json.mes[0].description || "Erreur lors de la récupération des frais annexes.";
                    console.error(errorMsg);

                    const echeancesContainer = document.getElementById('fraisannexesecheances');
                    echeancesContainer.innerHTML = `
                        <div class="notifications-container" style="width: 100%;">
                            <div class="error-alert">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="error-svg">
                                            <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" fill-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="error-prompt-container">
                                        <p class="error-prompt-heading">
                                            <strong>Code :</strong>  ${json.mes[0].code}<br>
                                            <strong>Description :</strong>  ${json.mes[0].description} 
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `; 
                }
                // Vérifier si json.mes est un tableau vide
                else if (json.mes.length === 0) {
                    loadingRow.style.display = 'none';

                    // Gestion d'erreur avec un message par défaut
                    const errorMsg = "Erreur lors de la récupération des échéances. Votre dossier est transmis, patientez pour son examens.";
                    console.error(errorMsg);

                    const echeancesContainer = document.getElementById('echeances-container');
                    echeancesContainer.innerHTML = `
                        <div class="notifications-container" style="width: 100%;">
                            <div class="error-alert">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="error-svg">
                                            <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" fill-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="error-prompt-container">
                                        <p class="error-prompt-heading">
                                            <strong>Code :</strong> Votre dossier est transmis. Patientez pour son examen.<br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }
                else {
                    loadingRow.style.display = 'none'; // Cacher la ligne de loader
                    const echeancesContainerheader = document.getElementById('fraisannexesecheances');
                    echeancesContainerheader.innerHTML = ''; // Vider le conteneur avant de le remplir

                    const rows = document.createElement('tr');
                    rows.innerHTML = `
                        <th class="">Nature Frais</th>
                        <th class="">Montant Total</th>
                        <th class="text-left">Prix Unitaire</th>
                        <th class="text-left">Quantite</th>
                    `;
                    echeancesContainerheader.appendChild(rows);

                    // Affichage des échéances récupérées
                    const echeancesContainer = document.getElementById('fraisannexesecheances-container');
                    echeancesContainer.innerHTML = ''; // Vider le conteneur avant de le remplir

                    // Vérifier si json.mes est défini et s'il contient des éléments avant de parcourir
                    if (json.mes && json.mes.length > 0) {
                        json.mes.forEach((echeance, index) => {
                            // Créer une ligne de tableau pour chaque échéance
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${echeance.natureFrais}</td>
                                <td>${echeance.montantTotal}</td>
                                <td>${echeance.prixUnitaire}</td>
                                <td>${echeance.quantite}</td>
                            `;
                            echeancesContainer.appendChild(row); // Ajouter la ligne au tableau
                        });
                    }
                }
            } else {
                loadingRow.style.display = 'none';
                // Gestion des erreurs
                const errorMsg = json.mes && json.mes.description || "Erreur lors de la récupération des échéances.";
                console.error(errorMsg);

                const echeancesContainer = document.getElementById('fraisannexesecheances-container');
                echeancesContainer.innerHTML = `
                    <tr>
                        <td colspan="9" class="error-message">${errorMsg}</td>
                    </tr>
                `;
            }
        },

        error: function (jqXHR, textStatus, errorThrown) {
            loadingRow.style.display = 'none';
            console.error('Erreur AJAX:', jqXHR, textStatus, errorThrown);

            const echeancesContainer = document.getElementById('fraisannexesecheances-container');
            echeancesContainer.innerHTML = `
                <tr>
                    <td colspan="9" class="error-message">Erreur de communication avec le serveur.</td>
                </tr>
            `;
        }
    });
}

  


    // Appeler les fonctions après le rendu de la page
    const numeroInterne = <?= json_encode($items->numeroInterne) ?>;
    const opco = <?= json_encode($opco) ?>;
    const itemId = <?= json_encode($items->id) ?>;

    // Mettre à jour l'état et le numéro DECA en arrière-plan
    updateNumeroDeca(numeroInterne, opco, itemId);
    updateEtatLabel(numeroInterne, opco);
    if(numeroInterne){
        updateEcheances(numeroInterne, opco);
        updateDetailsFacturation(numeroInterne, opco);
        updateEngagementsFraisAnnexe(numeroInterne, opco);
    }

    // fin new

        //Envoyer un document Externe
        $(document).on('submit', '#FormDocument', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();

        swal({
                title: "Etes vous sûr?",
                text: "Le Document  va être envoyé à l'opco responsable.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#153C4A",
                confirmButtonText: "Oui, valider!",
                cancelButtonText: "Annuler",
                closeOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url: url,
                        data: data,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        complete: function () {
                            dismiss_waitMe();
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                               // showAlert($form,1,json.mes);
                                toastr.success(json.mes,'Succès!');
                                $('.conventionModal').modal('hide');
                                window.location.reload();
                            } else {
                         	       toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            toastr.error(jqXHR + textStatus + errorThrown, 'Oups2!');
                        }
                    });
                }
            });
    });

    //changer une formation
    $(document).on('submit', '#FormChangeFormation', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();

        swal({
                title: "Etes vous sûr?",
                text: "La formation  va être modifiée.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#153C4A",
                confirmButtonText: "Oui, valider!",
                cancelButtonText: "Annuler",
                closeOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url: url,
                        data: data,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        complete: function () {
                            dismiss_waitMe();
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                               // showAlert($form,1,json.mes);
                                toastr.success(json.mes,'Succès!');
                                $('.changeEntrepriseModal').modal('hide');
                                window.location.reload();
                            } else {
                         	       toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            toastr.error(jqXHR + textStatus + errorThrown, 'Oups2!');
                        }
                    });
                }
            });
    });


      //changer une entreprise
      $(document).on('submit', '#FormChangeEntrprise', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();

        swal({
                title: "Etes vous sûr?",
                text: "L'entreprise va être modifiée.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#153C4A",
                confirmButtonText: "Oui, valider!",
                cancelButtonText: "Annuler",
                closeOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url: url,
                        data: data,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        complete: function () {
                            dismiss_waitMe();
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                               // showAlert($form,1,json.mes);
                                toastr.success(json.mes,'Succès!');
                                $('.changeEntrepriseModal').modal('hide');
                                window.location.reload();
                            } else {
                         	       toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            toastr.error(jqXHR + textStatus + errorThrown, 'Oups2!');
                        }
                    });
                }
            });
    });



     //formulaire remplissage echeance
     $(document).on('submit', '#FormremplirEcheance', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            contentType: false,
            processData: false,
            beforeSend: function () {
                run_waitMe(current_effect,loadingText);
            },
            complete: function () {
                dismiss_waitMe();
            },
            success: function (json) {
                if (json.statuts === 0) {
                    //showAlert($form,1,json.mes);
                    toastr.success(json.mes,'Succès!');
                    $('.remplirEcheanceModal').modal('hide');
                      var url = json.url;
        
                    
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = url.substring(url.lastIndexOf('/') + 1); // Extraire le nom du fichier de l'URL

                    // Ajouter le lien au document et déclencher le clic
                    document.body.appendChild(a);
                    a.click();

                    // Nettoyer en retirant le lien du document
                    document.body.removeChild(a);
                    //window.location.reload();
                } else {
                        toastr.error(json.mes,'Oups!');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error(jqXHR + textStatus + errorThrown, 'Oups2!');
                showAlert($form,1,json);
            }
        });
    });

    //formulaire signature Manuelle cerfa
    $(document).on('submit', '#FormSignatureManuelleEcole', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            contentType: false,
            processData: false,
            beforeSend: function () {
                run_waitMe(current_effect,loadingText);
            },
            complete: function () {
                dismiss_waitMe();
            },
            success: function (json) {
                if (json.statuts === 0) {
                    //showAlert($form,1,json.mes);
                    toastr.success(json.mes,'Succès!');
                    $('.signatureManuelleEcoleModal').modal('hide');
                    //window.location.reload();
                } else {
                        toastr.error(json.mes,'Oups!');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error(jqXHR + textStatus + errorThrown, 'Oups2!');
                showAlert($form,1,json);
            }
        });
    });
  
    //formulaire signature Manuelle convention
    $(document).on('submit', '#FormSignatureManuelleConventionEcole', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            contentType: false,
            processData: false,
            beforeSend: function () {
                run_waitMe(current_effect,loadingText);
            },
            complete: function () {
                dismiss_waitMe();
            },
            success: function (json) {
                if (json.statuts === 0) {
                    //showAlert($form,1,json.mes);
                    toastr.success(json.mes,'Succès!');
                    $('.signatureManuelleConventionEcoleModal').modal('hide');
                    //window.location.reload();
                } else {
                        toastr.error(json.mes,'Oups!');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error(jqXHR + textStatus + errorThrown, 'Oups2!');
                showAlert($form,1,json);
            }
        });
    });

    //formulaire remplissage facture
    $(document).on('submit', '#FormFacture', function (e) {
        e.preventDefault();
        var url = "cerfas/sendOpcoFacture";
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            contentType: false,
            processData: false,
            beforeSend: function () {
                run_waitMe(current_effect,loadingText);
            },
            complete: function () {
                dismiss_waitMe();
            },
            success: function (json) {
                if (json.statuts === 0) {
                    //showAlert($form,1,json.mes);
                    toastr.success(json.mes,'Succès!');
                    $('.FactureModal').modal('hide');
                    window.location.reload();
                } else {
                        toastr.error(json.mes,'Oups!');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error(jqXHR + textStatus + errorThrown, 'Oups2!');
            }
        });
    });

    //formulaire cerfa
    $(document).on('submit', '#FormCerfa', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();

        swal({
                title: "Etes vous sûr?",
                text: "Le cerfa va être envoyé à l'OPCO responsable.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#153C4A",
                confirmButtonText: "Oui, valider!",
                cancelButtonText: "Annuler",
                closeOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url: url,
                        data: data,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        complete: function () {
                            dismiss_waitMe();
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                                //showAlert($form,1,json.mes);
                                toastr.success(json.mes,'Succès!');
                                $('.cerfaModal').modal('hide');

                                window.location.reload();
                            } else {
                         	       toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            toastr.error(jqXHR + textStatus + errorThrown, 'Oups2!');
                            showAlert($form,1,json);
                        }
                    });
                }
            });
    });
  
    //formulaire convention
    $(document).on('submit', '#FormConvention', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();

        swal({
                title: "Etes vous sûr?",
                text: "La convention va être envoyée à l'OPCO responsable.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#153C4A",
                confirmButtonText: "Oui, valider!",
                cancelButtonText: "Annuler",
                closeOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url: url,
                        data: data,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        complete: function () {
                            dismiss_waitMe();
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                               // showAlert($form,1,json.mes);
                                toastr.success(json.mes,'Succès!');
                                $('.conventionModal').modal('hide');
                                window.location.reload();
                            } else {
                         	       toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            toastr.error(jqXHR + textStatus + errorThrown, 'Oups2!');
                        }
                    });
                }
            });
    });

    $(document).on('submit', '#FormRemplirConvention', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            contentType: false,
            processData: false,
            beforeSend: function () {
                run_waitMe(current_effect,loadingText);
            },
            complete: function () {
                dismiss_waitMe();
            },
            success: function (json) {
                if (json.statuts === 0) {
                   // showAlert($form,1,json.mes);
                   $('.conventionModalRemplir').modal('hide');
                    toastr.success(json.mes,'Succès!');


                    window.location.reload();
                } else {
                        toastr.error(json.mes,'Oups!');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error(jqXHR + textStatus + errorThrown, 'Oups2!');
            }
        });
    });

    //formulaire send facture 
    $(document).on('submit', '#FormFactureSend', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();

        swal({
                title: "Etes vous sûr?",
                text: "La Facture va être envoyée à l'opco responsable.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#153C4A",
                confirmButtonText: "Oui, valider!",
                cancelButtonText: "Annuler",
                closeOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url: url,
                        data: data,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        complete: function () {
                            dismiss_waitMe();
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                               // showAlert($form,1,json.mes);
                                toastr.success(json.mes,'Succès!');
                               
                                $('.EnvoieEcheanceModal').modal('hide');
                                window.location.reload();
                            } else {
                                    toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            toastr.error(jqXHR + textStatus + errorThrown, 'Oups2!');
                        }
                    });
                  
                }
            });
    });

     // Select the Contrat card
     const contratCardrecap = document.querySelector('.cardDossier.recap');
    const contratCardschool = document.querySelector('.cardDossier.school');
    const contratCardstudent = document.querySelector('.cardDossier.student');
    const contratCardcompany = document.querySelector('.cardDossier.company');
    const contratCardcontrat = document.querySelector('.cardDossier.contrat');
    const comptabiliteCardcomptabilite = document.querySelector('.cardDossier.comptabilite');


    //  Progress
    const progress = document.querySelector('.progress');

    // Select the sections to toggle
    const section1 = document.getElementById('section1');
    const section2 = document.getElementById('section2');
    const section3 = document.getElementById('section3');
    const section4 = document.getElementById('section4');
    const section5 = document.getElementById('section5');
    const section6 = document.getElementById('section6');
    const section7 = document.getElementById('section7');

    

        


   

    // Add click event listener to the Contrat card
    contratCardrecap.addEventListener('click', function() {
           

        // Toggle display of sections
        if (section5.style.display === 'flex') {
            section1.style.display = 'none';
            section2.style.display = 'none';
            section3.style.display = 'none';
            section4.style.display = 'none';
            section5.style.display = 'flex';
            section6.style.display = 'none';
            section7.style.display = 'none';
        } else {
            section1.style.display = 'none';
            section2.style.display = 'none';
            section3.style.display = 'none';
            section4.style.display = 'none';
            section5.style.display = 'flex';
            section6.style.display = 'none';
            section7.style.display = 'none';
        }
    });
    contratCardschool.addEventListener('click', function() {
           
        // Toggle display of sections
        if (section1.style.display === 'flex') {
            section1.style.display = 'flex';
            section2.style.display = 'none';
            section3.style.display = 'none';
            section4.style.display = 'none';
            section5.style.display = 'none';
            section6.style.display = 'none';
            section7.style.display = 'none';
        } else {
            section1.style.display = 'flex';
            section2.style.display = 'none';
            section3.style.display = 'none';
            section4.style.display = 'none';
            section5.style.display = 'none';
            section6.style.display = 'none';
            section7.style.display = 'none';
        }
    });

    contratCardstudent.addEventListener('click', function() {
       
        

        // Toggle display of sections
        if (section2.style.display === 'flex') {
            section1.style.display = 'none';
            section2.style.display = 'flex';
            section3.style.display = 'none';
            section4.style.display = 'none';
            section5.style.display = 'none';
            section6.style.display = 'none';
            section7.style.display = 'none';
        } else {
            section1.style.display = 'none';
            section2.style.display = 'flex';
            section3.style.display = 'none';
            section4.style.display = 'none';
            section5.style.display = 'none';
            section6.style.display = 'none';
            section7.style.display = 'none';
        }
    });

    contratCardcompany.addEventListener('click', function() {
       

        // Toggle display of sections
        if (section3.style.display === 'flex') {
            section1.style.display = 'none';
            section2.style.display = 'none';
            section3.style.display = 'flex';
            section4.style.display = 'none';
            section5.style.display = 'none';
            section6.style.display = 'none';
            section7.style.display = 'none';
        } else {
            section1.style.display = 'none';
            section2.style.display = 'none';
            section3.style.display = 'flex';
            section4.style.display = 'none';
            section5.style.display = 'none';
            section6.style.display = 'none';
            section7.style.display = 'none';
        }
    });

    contratCardcontrat.addEventListener('click', function() {
       

        // Toggle display of sections
        if (section4.style.display === 'flex') {
            section1.style.display = 'none';
            section2.style.display = 'none';
            section3.style.display = 'none';
            section4.style.display = 'flex';
            section5.style.display = 'none';
            section6.style.display = 'none';
            section7.style.display = 'none';
        } else {
            section1.style.display = 'none';
            section2.style.display = 'none';
            section3.style.display = 'none';
            section4.style.display = 'flex';
            section5.style.display = 'none';
            section6.style.display = 'none';
            section7.style.display = 'none';
        }
    });

    comptabiliteCardcomptabilite.addEventListener('click', function() {
        

        // Toggle display of sections
        if (section7.style.display === 'flex') {
            section1.style.display = 'none';
            section2.style.display = 'none';
            section3.style.display = 'none';
            section4.style.display = 'none';
            section5.style.display = 'none';
            section6.style.display = 'none';
            section7.style.display = 'flex';
        } else {
            section1.style.display = 'none';
            section2.style.display = 'none';
            section3.style.display = 'none';
            section4.style.display = 'none';
            section5.style.display = 'none';
            section6.style.display = 'none';
            section7.style.display = 'flex';
        }
    });
});


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


</script>
