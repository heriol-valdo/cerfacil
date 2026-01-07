<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/CerfaModel.php';
require_once __DIR__.'/../../models/EmailModel.php';
require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/FactureModel.php';
require_once __DIR__.'/../../models/ClientCerfaModel.php';
require_once __DIR__.'/../../models/EntreprisesModel.php';

require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';
require_once __DIR__.'/../../models/EtudiantModel.php';
require_once __DIR__.'/../../models/FinanceurModel.php';

$app->post('/api/addupdateCefa', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    $id=$data['id'];
    $idemployeur=$data['idemployeur'];
    $idformation=$data['idformation'];
    $nomA=$data['nomA'];
    $nomuA=$data['nomuA'];
    $prenomA=$data['prenomA'];
    $sexeA=$data['sexeA'];
    $naissanceA=$data['naissanceA'];
    $departementA=$data['departementA'];
    $communeNA=$data['communeNA'];
    $nationaliteA=$data['nationaliteA'];
    $regimeA=$data['regimeA'];
    $situationA=$data['situationA'];
    $titrePA=$data['titrePA'];
    $derniereCA=$data['derniereCA'];
    $securiteA=$data['securiteA'];
    $intituleA=$data['intituleA'];
    $titreOA=$data['titreOA'];
    $declareSA=$data['declareSA'];
    $declareHA=$data['declareHA'];
    $declareRA=$data['declareRA'];
    $rueA=$data['rueA'];
    $voieA=$data['voieA'];
    $complementA=$data['complementA'];
    $postalA=$data['postalA'];
    $communeA=$data['communeA'];
    $numeroA=$data['numeroA'];
    $emailA=$data['emailA']; 
    $nomR=$data['nomR']; 
    $emailR=$data['emailR']; 
    $rueR=$data['rueR'];
    $voieR=$data['voieR'];
    $complementR=$data['complementR'];
    $postalR=$data['postalR'];
    $communeR=$data['communeR'];
    $nomM=$data['nomM']; 
    $prenomM=$data['prenomM'];
    $naissanceM=$data['naissanceM'];  
    $securiteM=$data['securiteM'];
    $emailM=$data['emailM'];
    $emploiM=$data['emploiM'];
    $diplomeM=$data['diplomeM'];
    $niveauM=$data['niveauM']; 
    $nomM1=$data['nomM1']; 
    $prenomM1=$data['prenomM1'];
    $naissanceM1=$data['naissanceM1'];  
    $securiteM1=$data['securiteM1'];
    $emailM1=$data['emailM1'];
    $emploiM1=$data['emploiM1'];
    $diplomeM1=$data['diplomeM'];
    $niveauM1=$data['niveauM1'];
    $travailC=$data['travailC'];
    $derogationC=$data['derogationC'];
    $numeroC=$data['numeroC'];
    $conclusionC=$data['conclusionC'];
    $debutC=$data['debutC'];
    $finC=$data['finC'];
    $avenantC=$data['avenantC'];
    $executionC=$data['executionC'];
    $dureC=$data['dureC'];
    $typeC=$data['typeC'];
    $rdC=$data['rdC'];
    $raC=$data['raC'];
    $rpC=$data['rpC'];
    $rsC=$data['rsC'];
    $rdC1=$data['rdC1'];
    $raC1=$data['raC1'];
    $rpC1=$data['rpC1'];
    $rsC1=$data['rsC1'];
    $rdC2=$data['rdC2'];
    $raC2=$data['raC2'];
    $rpC2=$data['rpC2'];
    $rsC2=$data['rsC2'];
    $salaireC=$data['salaireC'];
    $caisseC=$data['caisseC'];
    $logementC=$data['logementC'];
    $avantageC=$data['avantageC'];
    $autreC=$data['autreC']; 
    $lieuO=$data['lieuO'];
    $priveO=$data['priveO'];
    $attesteO=$data['attesteO'];
    $modeC=$data['modeC'];
    $prenomR=$data['prenomR'];
    $dureCM=$data['dureCM'];
   
    if($id == null){
        $user = new User();
        $userselect = $user->getEtudiantDetailsForEmail($emailA);
        if($userselect !== null){
            $nomA=$userselect['firstname'];
            $prenomA=$userselect['lastname'];
            $naissanceA=$userselect['date_naissance'];
            $voieA=$userselect['adressePostale'];
            $postalA=$userselect['codePostal'];
            $communeA=$userselect['ville'];
        }
    }
   

   if ($role === 7 || $role === 3) {
        $cerfa = new Cerfa();

        if($role === 7){
            $clientCerfa = new ClientCerfa();
            $clientCerfa->id_users = $userConnected;

            $profilClient = $clientCerfa->getProfil();

            $effectiveUserId = $profilClient[0]['roleCreation'] == 2 ? $profilClient[0]['idCreation'] : $userConnected;

            if($id == null){
                //nouveau contrat 
                $user = new User(); 
                $user->email = $emailA;
                $emailExist = $user->boolEmail();

                 $userFinanceur = new User(); 
                 $entreprises = new  Entreprises;
                 $tableau = $entreprises->find( $idemployeur);
                 $userFinanceur->email =  $tableau['emailE'];
                 $emailExistFinanceur = $userFinanceur->boolEmail();
               
                if($emailExist &&  $emailExistFinanceur){
                    //j'envoie le mail d'ajout de dossier
                    if(Email::sendEmailAjoutCerFacilApp($emailA) && Email::sendEmailAjoutCerFacilApp($tableau['emailE'])){
                         $result = $cerfa->save(null, $effectiveUserId,
                            $idemployeur, $idformation,
                            $nomA, $nomuA, $prenomA, $sexeA,
                            $naissanceA, $departementA, $communeNA, 
                            $nationaliteA, $regimeA, $situationA, $titrePA,
                            $derniereCA, $securiteA, $intituleA, $titreOA, 
                            $declareSA, $declareHA, $declareRA, $rueA, 
                            $voieA, $complementA, $postalA, $communeA, 
                            $numeroA, $emailA,
                            
                            $nomR, $emailR, $rueR, $voieR, $complementR, $postalR, $communeR, 
                            $nomM, $prenomM, $naissanceM, $securiteM, $emailM, $emploiM, $diplomeM, $niveauM, 
                            $nomM1, $prenomM1, $naissanceM1, $securiteM1, $emailM1, $emploiM1, $diplomeM1, $niveauM1, 
                            $travailC, $derogationC, $numeroC, $conclusionC, $debutC, $finC, $avenantC, $executionC, $dureC, $typeC,
                            $rdC, $raC, $rpC, $rsC, $rdC1, $raC1, $rpC1, $rsC1,
                            $rdC2, $raC2, $rpC2, $rsC2,
                            $salaireC, $caisseC, $logementC, $avantageC, $autreC,
                            $lieuO, $priveO, $attesteO, $modeC, $prenomR, $dureCM,
                            $id 
                        );
                        $response->getBody()->write(json_encode(['valid' => "Vous avez ajouté un cerfa, Un email d'ajout de dossier a été envoyé"]));
                        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                    } else {
                        throw new Exception("Erreur lors de l'envoi de l'email");
                    }
                }elseif($emailExist &&  !$emailExistFinanceur ){

                    // je creer le compte etudiant 
                    $userFinanceur->password = Etudiant::genererMotDePasses();
                    $userFinanceur->id_role = 6;
                    $userFinanceur->addUser();

                    $financeur = new Financeur(); 
                    $financeur->id_users = $userFinanceur->searchIdForEmail();
                    $financeur->firstname = "";
                    $financeur->lastname = "";
                    $financeur->type_financeur = "";
                    $financeur->id_entreprises = "";

                    // Succès
                    if($financeur->addFinanceur()){
                        try{
                            $userFinanceur->id = $financeur->id_users;
                            $mailingInfos = $userFinanceur->getMailingInfo();
                            $mailEmail = $mailingInfos[0]['email'];
                            $nomRole = $mailingInfos[0]['role'];
                            if(Email::sendEmailCreationCompteCerFacilApp("",$nomRole,$mailEmail,$userFinanceur->password) 
                              && Email::sendEmailAjoutCerFacilApp($emailA)){
                                   $result = $cerfa->save(null, $effectiveUserId,
                                    $idemployeur, $idformation,
                                    $nomA, $nomuA, $prenomA, $sexeA,
                                    $naissanceA, $departementA, $communeNA, 
                                    $nationaliteA, $regimeA, $situationA, $titrePA,
                                    $derniereCA, $securiteA, $intituleA, $titreOA, 
                                    $declareSA, $declareHA, $declareRA, $rueA, 
                                    $voieA, $complementA, $postalA, $communeA, 
                                    $numeroA, $emailA,
                                    
                                    $nomR, $emailR, $rueR, $voieR, $complementR, $postalR, $communeR, 
                                    $nomM, $prenomM, $naissanceM, $securiteM, $emailM, $emploiM, $diplomeM, $niveauM, 
                                    $nomM1, $prenomM1, $naissanceM1, $securiteM1, $emailM1, $emploiM1, $diplomeM1, $niveauM1, 
                                    $travailC, $derogationC, $numeroC, $conclusionC, $debutC, $finC, $avenantC, $executionC, $dureC, $typeC,
                                    $rdC, $raC, $rpC, $rsC, $rdC1, $raC1, $rpC1, $rsC1,
                                    $rdC2, $raC2, $rpC2, $rsC2,
                                    $salaireC, $caisseC, $logementC, $avantageC, $autreC,
                                    $lieuO, $priveO, $attesteO, $modeC, $prenomR, $dureCM,
                                    $id 
                                );
                                $response->getBody()->write(json_encode(['valid' => "Vous avez ajouté un cerfa, Un email de validation de création du compte a été envoyé"]));
                                return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                            } else {
                                throw new Exception("Erreur lors de l'envoi de l'email de création");
                            }
                        } catch (Exception $e) {
                            $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
                            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                        }
                    } else {
                        $response->getBody()->write(json_encode(['erreur' => "Erreur dans la création de l'entreprise"]));
                        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                    }

                }elseif(!$emailExist &&  $emailExistFinanceur){
                    // je creer le compte etudiant 
                    $user->password = Etudiant::genererMotDePasses();
                    $user->id_role = 5;
                    $user->addUser();

                    $etudiant = new Etudiant(); 
                    $etudiant->id_users = $user->searchIdForEmail();
                    $etudiant->firstname = "";
                    $etudiant->lastname = "";
                    $etudiant->adressePostale = "";
                    $etudiant->codePostal = "";
                    $etudiant->ville = "";
                    $etudiant->date_naissance = "";

                    // Succès
                    if($etudiant->addEtudiant()){
                        try{
                            $user->id = $etudiant->id_users;
                            $mailingInfos = $user->getMailingInfo();
                            $mailEmail = $mailingInfos[0]['email'];
                            $nomRole = $mailingInfos[0]['role'];
                            if(Email::sendEmailCreationCompteCerFacilApp("",$nomRole,$mailEmail,$user->password) && Email::sendEmailAjoutCerFacilApp($tableau['emailE'])){
                                   $result = $cerfa->save(null, $effectiveUserId,
                                    $idemployeur, $idformation,
                                    $nomA, $nomuA, $prenomA, $sexeA,
                                    $naissanceA, $departementA, $communeNA, 
                                    $nationaliteA, $regimeA, $situationA, $titrePA,
                                    $derniereCA, $securiteA, $intituleA, $titreOA, 
                                    $declareSA, $declareHA, $declareRA, $rueA, 
                                    $voieA, $complementA, $postalA, $communeA, 
                                    $numeroA, $emailA,
                                    
                                    $nomR, $emailR, $rueR, $voieR, $complementR, $postalR, $communeR, 
                                    $nomM, $prenomM, $naissanceM, $securiteM, $emailM, $emploiM, $diplomeM, $niveauM, 
                                    $nomM1, $prenomM1, $naissanceM1, $securiteM1, $emailM1, $emploiM1, $diplomeM1, $niveauM1, 
                                    $travailC, $derogationC, $numeroC, $conclusionC, $debutC, $finC, $avenantC, $executionC, $dureC, $typeC,
                                    $rdC, $raC, $rpC, $rsC, $rdC1, $raC1, $rpC1, $rsC1,
                                    $rdC2, $raC2, $rpC2, $rsC2,
                                    $salaireC, $caisseC, $logementC, $avantageC, $autreC,
                                    $lieuO, $priveO, $attesteO, $modeC, $prenomR, $dureCM,
                                    $id 
                                );
                                $response->getBody()->write(json_encode(['valid' => "Vous avez ajouté un cerfa, Un email de validation de création du compte a été envoyé"]));
                                return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                            } else {
                                throw new Exception("Erreur lors de l'envoi de l'email de création");
                            }
                        } catch (Exception $e) {
                            $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
                            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                        }
                    } else {
                        $response->getBody()->write(json_encode(['erreur' => "Erreur dans la création de l'étudiant"]));
                        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                    }
                }elseif(!$emailExist &&  !$emailExistFinanceur){
                    $user->password = Etudiant::genererMotDePasses();
                    $user->id_role = 5;
                    $user->addUser();

                    $etudiant = new Etudiant(); 
                    $etudiant->id_users = $user->searchIdForEmail();
                    $etudiant->firstname = "";
                    $etudiant->lastname = "";
                    $etudiant->adressePostale = "";
                    $etudiant->codePostal = "";
                    $etudiant->ville = "";
                    $etudiant->date_naissance = "";

                     // je creer le compte etudiant 
                    $userFinanceur ->password = Etudiant::genererMotDePasses();
                    $userFinanceur ->id_role = 6;
                    $userFinanceur ->addUser();

                    $financeur = new Financeur(); 
                    $financeur->id_users = $userFinanceur->searchIdForEmail();
                    $financeur->firstname = "";
                    $financeur->lastname = "";
                    $financeur->type_financeur = "";
                    $financeur->id_entreprises = "";

                    if($etudiant->addEtudiant() && $financeur->addFinanceur()){
                        try{
                            $user->id = $etudiant->id_users;
                            $mailingInfos = $user->getMailingInfo();
                            $mailEmail = $mailingInfos[0]['email'];
                            $nomRole = $mailingInfos[0]['role'];

                            $userFinanceur->id = $financeur->id_users;
                            $mailingInfos = $userFinanceur->getMailingInfo();
                            $mailEmailFinanceur = $mailingInfos[0]['email'];
                            $nomRoleFinanceur = $mailingInfos[0]['role'];

                            
                            if(Email::sendEmailCreationCompteCerFacilApp("",$nomRole,$mailEmail,$user->password) 
                            && Email::sendEmailCreationCompteCerFacilApp("",$nomRoleFinanceur,$mailEmailFinanceur,$userFinanceur->password)){
                                   $result = $cerfa->save(null, $effectiveUserId,
                                    $idemployeur, $idformation,
                                    $nomA, $nomuA, $prenomA, $sexeA,
                                    $naissanceA, $departementA, $communeNA, 
                                    $nationaliteA, $regimeA, $situationA, $titrePA,
                                    $derniereCA, $securiteA, $intituleA, $titreOA, 
                                    $declareSA, $declareHA, $declareRA, $rueA, 
                                    $voieA, $complementA, $postalA, $communeA, 
                                    $numeroA, $emailA,
                                    
                                    $nomR, $emailR, $rueR, $voieR, $complementR, $postalR, $communeR, 
                                    $nomM, $prenomM, $naissanceM, $securiteM, $emailM, $emploiM, $diplomeM, $niveauM, 
                                    $nomM1, $prenomM1, $naissanceM1, $securiteM1, $emailM1, $emploiM1, $diplomeM1, $niveauM1, 
                                    $travailC, $derogationC, $numeroC, $conclusionC, $debutC, $finC, $avenantC, $executionC, $dureC, $typeC,
                                    $rdC, $raC, $rpC, $rsC, $rdC1, $raC1, $rpC1, $rsC1,
                                    $rdC2, $raC2, $rpC2, $rsC2,
                                    $salaireC, $caisseC, $logementC, $avantageC, $autreC,
                                    $lieuO, $priveO, $attesteO, $modeC, $prenomR, $dureCM,
                                    $id 
                                );
                                $response->getBody()->write(json_encode(['valid' => "Vous avez ajouté un cerfa, Un email de validation de création du compte a été envoyé"]));
                                return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                            } else {
                                throw new Exception("Erreur lors de l'envoi de l'email de création");
                            }
                        } catch (Exception $e) {
                            $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
                            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                        }
                    } else {
                        $response->getBody()->write(json_encode(['erreur' => "Erreur dans la création de l'étudiant"]));
                        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                    }

                }
            }else{
                         $result = $cerfa->save(null, $effectiveUserId,
                            $idemployeur, $idformation,
                            $nomA, $nomuA, $prenomA, $sexeA,
                            $naissanceA, $departementA, $communeNA, 
                            $nationaliteA, $regimeA, $situationA, $titrePA,
                            $derniereCA, $securiteA, $intituleA, $titreOA, 
                            $declareSA, $declareHA, $declareRA, $rueA, 
                            $voieA, $complementA, $postalA, $communeA, 
                            $numeroA, $emailA,
                            
                            $nomR, $emailR, $rueR, $voieR, $complementR, $postalR, $communeR, 
                            $nomM, $prenomM, $naissanceM, $securiteM, $emailM, $emploiM, $diplomeM, $niveauM, 
                            $nomM1, $prenomM1, $naissanceM1, $securiteM1, $emailM1, $emploiM1, $diplomeM1, $niveauM1, 
                            $travailC, $derogationC, $numeroC, $conclusionC, $debutC, $finC, $avenantC, $executionC, $dureC, $typeC,
                            $rdC, $raC, $rpC, $rsC, $rdC1, $raC1, $rpC1, $rsC1,
                            $rdC2, $raC2, $rpC2, $rsC2,
                            $salaireC, $caisseC, $logementC, $avantageC, $autreC,
                            $lieuO, $priveO, $attesteO, $modeC, $prenomR, $dureCM,
                            $id 
                        );  
            }

        } else if($role === 3) {
            // GESTION DU RÔLE 3 - GESTIONNAIRE DE CENTRE
            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_users = $userConnected;
            $datagestionnairescentre = $gestionnaireCentre->searchForId();
            $effectiveUserId = $datagestionnairescentre['id_centres_de_formation'];

            // AJOUT DE LA GESTION DES NOUVEAUX CONTRATS POUR LE RÔLE 3
           if($id == null){
                //nouveau contrat 
                $user = new User(); 
                $user->email = $emailA;
                $emailExist = $user->boolEmail();

                 $userFinanceur = new User(); 
                 $entreprises = new  Entreprises;
                 $tableau = $entreprises->find( $idemployeur);
                 $userFinanceur->email =  $tableau['emailE'];
                 $emailExistFinanceur = $userFinanceur->boolEmail();
               
                if($emailExist &&  $emailExistFinanceur){
                    //j'envoie le mail d'ajout de dossier
                    if(Email::sendEmailAjoutCerFacilApp($emailA) && Email::sendEmailAjoutCerFacilApp($tableau['emailE'])){
                         $result = $cerfa->save(null, $effectiveUserId,
                            $idemployeur, $idformation,
                            $nomA, $nomuA, $prenomA, $sexeA,
                            $naissanceA, $departementA, $communeNA, 
                            $nationaliteA, $regimeA, $situationA, $titrePA,
                            $derniereCA, $securiteA, $intituleA, $titreOA, 
                            $declareSA, $declareHA, $declareRA, $rueA, 
                            $voieA, $complementA, $postalA, $communeA, 
                            $numeroA, $emailA,
                            
                            $nomR, $emailR, $rueR, $voieR, $complementR, $postalR, $communeR, 
                            $nomM, $prenomM, $naissanceM, $securiteM, $emailM, $emploiM, $diplomeM, $niveauM, 
                            $nomM1, $prenomM1, $naissanceM1, $securiteM1, $emailM1, $emploiM1, $diplomeM1, $niveauM1, 
                            $travailC, $derogationC, $numeroC, $conclusionC, $debutC, $finC, $avenantC, $executionC, $dureC, $typeC,
                            $rdC, $raC, $rpC, $rsC, $rdC1, $raC1, $rpC1, $rsC1,
                            $rdC2, $raC2, $rpC2, $rsC2,
                            $salaireC, $caisseC, $logementC, $avantageC, $autreC,
                            $lieuO, $priveO, $attesteO, $modeC, $prenomR, $dureCM,
                            $id 
                        );
                        $response->getBody()->write(json_encode(['valid' => "Vous avez ajouté un cerfa, Un email d'ajout de dossier a été envoyé"]));
                        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                    } else {
                        throw new Exception("Erreur lors de l'envoi de l'email");
                    }
                }elseif($emailExist &&  !$emailExistFinanceur ){

                    // je creer le compte etudiant 
                    $userFinanceur->password = Etudiant::genererMotDePasses();
                    $userFinanceur->id_role = 6;
                    $userFinanceur->addUser();

                    $financeur = new Financeur(); 
                    $financeur->id_users = $userFinanceur->searchIdForEmail();
                    $financeur->firstname = "";
                    $financeur->lastname = "";
                    $financeur->type_financeur = "";
                    $financeur->id_entreprises = "";

                    // Succès
                    if($financeur->addFinanceur()){
                        try{
                            $userFinanceur->id = $financeur->id_users;
                            $mailingInfos = $userFinanceur->getMailingInfo();
                            $mailEmail = $mailingInfos[0]['email'];
                            $nomRole = $mailingInfos[0]['role'];
                            if(Email::sendEmailCreationCompteCerFacilApp("",$nomRole,$mailEmail,$userFinanceur->password) 
                              && Email::sendEmailAjoutCerFacilApp($emailA)){
                                   $result = $cerfa->save(null, $effectiveUserId,
                                    $idemployeur, $idformation,
                                    $nomA, $nomuA, $prenomA, $sexeA,
                                    $naissanceA, $departementA, $communeNA, 
                                    $nationaliteA, $regimeA, $situationA, $titrePA,
                                    $derniereCA, $securiteA, $intituleA, $titreOA, 
                                    $declareSA, $declareHA, $declareRA, $rueA, 
                                    $voieA, $complementA, $postalA, $communeA, 
                                    $numeroA, $emailA,
                                    
                                    $nomR, $emailR, $rueR, $voieR, $complementR, $postalR, $communeR, 
                                    $nomM, $prenomM, $naissanceM, $securiteM, $emailM, $emploiM, $diplomeM, $niveauM, 
                                    $nomM1, $prenomM1, $naissanceM1, $securiteM1, $emailM1, $emploiM1, $diplomeM1, $niveauM1, 
                                    $travailC, $derogationC, $numeroC, $conclusionC, $debutC, $finC, $avenantC, $executionC, $dureC, $typeC,
                                    $rdC, $raC, $rpC, $rsC, $rdC1, $raC1, $rpC1, $rsC1,
                                    $rdC2, $raC2, $rpC2, $rsC2,
                                    $salaireC, $caisseC, $logementC, $avantageC, $autreC,
                                    $lieuO, $priveO, $attesteO, $modeC, $prenomR, $dureCM,
                                    $id 
                                );
                                $response->getBody()->write(json_encode(['valid' => "Vous avez ajouté un cerfa, Un email de validation de création du compte a été envoyé"]));
                                return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                            } else {
                                throw new Exception("Erreur lors de l'envoi de l'email de création");
                            }
                        } catch (Exception $e) {
                            $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
                            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                        }
                    } else {
                        $response->getBody()->write(json_encode(['erreur' => "Erreur dans la création de l'entreprise"]));
                        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                    }

                }elseif(!$emailExist &&  $emailExistFinanceur){
                    // je creer le compte etudiant 
                    $user->password = Etudiant::genererMotDePasses();
                    $user->id_role = 5;
                    $user->addUser();

                    $etudiant = new Etudiant(); 
                    $etudiant->id_users = $user->searchIdForEmail();
                    $etudiant->firstname = "";
                    $etudiant->lastname = "";
                    $etudiant->adressePostale = "";
                    $etudiant->codePostal = "";
                    $etudiant->ville = "";
                    $etudiant->date_naissance = "";

                    // Succès
                    if($etudiant->addEtudiant()){
                        try{
                            $user->id = $etudiant->id_users;
                            $mailingInfos = $user->getMailingInfo();
                            $mailEmail = $mailingInfos[0]['email'];
                            $nomRole = $mailingInfos[0]['role'];
                            if(Email::sendEmailCreationCompteCerFacilApp("",$nomRole,$mailEmail,$user->password) && Email::sendEmailAjoutCerFacilApp($tableau['emailE'])){
                                   $result = $cerfa->save(null, $effectiveUserId,
                                    $idemployeur, $idformation,
                                    $nomA, $nomuA, $prenomA, $sexeA,
                                    $naissanceA, $departementA, $communeNA, 
                                    $nationaliteA, $regimeA, $situationA, $titrePA,
                                    $derniereCA, $securiteA, $intituleA, $titreOA, 
                                    $declareSA, $declareHA, $declareRA, $rueA, 
                                    $voieA, $complementA, $postalA, $communeA, 
                                    $numeroA, $emailA,
                                    
                                    $nomR, $emailR, $rueR, $voieR, $complementR, $postalR, $communeR, 
                                    $nomM, $prenomM, $naissanceM, $securiteM, $emailM, $emploiM, $diplomeM, $niveauM, 
                                    $nomM1, $prenomM1, $naissanceM1, $securiteM1, $emailM1, $emploiM1, $diplomeM1, $niveauM1, 
                                    $travailC, $derogationC, $numeroC, $conclusionC, $debutC, $finC, $avenantC, $executionC, $dureC, $typeC,
                                    $rdC, $raC, $rpC, $rsC, $rdC1, $raC1, $rpC1, $rsC1,
                                    $rdC2, $raC2, $rpC2, $rsC2,
                                    $salaireC, $caisseC, $logementC, $avantageC, $autreC,
                                    $lieuO, $priveO, $attesteO, $modeC, $prenomR, $dureCM,
                                    $id 
                                );
                                $response->getBody()->write(json_encode(['valid' => "Vous avez ajouté un cerfa, Un email de validation de création du compte a été envoyé"]));
                                return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                            } else {
                                throw new Exception("Erreur lors de l'envoi de l'email de création");
                            }
                        } catch (Exception $e) {
                            $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
                            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                        }
                    } else {
                        $response->getBody()->write(json_encode(['erreur' => "Erreur dans la création de l'étudiant"]));
                        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                    }
                }elseif(!$emailExist &&  !$emailExistFinanceur){
                    $user->password = Etudiant::genererMotDePasses();
                    $user->id_role = 5;
                    $user->addUser();

                    $etudiant = new Etudiant(); 
                    $etudiant->id_users = $user->searchIdForEmail();
                    $etudiant->firstname = "";
                    $etudiant->lastname = "";
                    $etudiant->adressePostale = "";
                    $etudiant->codePostal = "";
                    $etudiant->ville = "";
                    $etudiant->date_naissance = "";

                     // je creer le compte etudiant 
                    $userFinanceur ->password = Etudiant::genererMotDePasses();
                    $userFinanceur ->id_role = 6;
                    $userFinanceur ->addUser();

                    $financeur = new Financeur(); 
                    $financeur->id_users = $userFinanceur->searchIdForEmail();
                    $financeur->firstname = "";
                    $financeur->lastname = "";
                    $financeur->type_financeur = "";
                    $financeur->id_entreprises = "";

                    if($etudiant->addEtudiant() && $financeur->addFinanceur()){
                        try{
                            $user->id = $etudiant->id_users;
                            $mailingInfos = $user->getMailingInfo();
                            $mailEmail = $mailingInfos[0]['email'];
                            $nomRole = $mailingInfos[0]['role'];

                            $userFinanceur->id = $financeur->id_users;
                            $mailingInfos = $userFinanceur->getMailingInfo();
                            $mailEmailFinanceur = $mailingInfos[0]['email'];
                            $nomRoleFinanceur = $mailingInfos[0]['role'];

                            
                            if(Email::sendEmailCreationCompteCerFacilApp("",$nomRole,$mailEmail,$user->password) 
                            && Email::sendEmailCreationCompteCerFacilApp("",$nomRoleFinanceur,$mailEmailFinanceur,$userFinanceur->password)){
                                   $result = $cerfa->save(null, $effectiveUserId,
                                    $idemployeur, $idformation,
                                    $nomA, $nomuA, $prenomA, $sexeA,
                                    $naissanceA, $departementA, $communeNA, 
                                    $nationaliteA, $regimeA, $situationA, $titrePA,
                                    $derniereCA, $securiteA, $intituleA, $titreOA, 
                                    $declareSA, $declareHA, $declareRA, $rueA, 
                                    $voieA, $complementA, $postalA, $communeA, 
                                    $numeroA, $emailA,
                                    
                                    $nomR, $emailR, $rueR, $voieR, $complementR, $postalR, $communeR, 
                                    $nomM, $prenomM, $naissanceM, $securiteM, $emailM, $emploiM, $diplomeM, $niveauM, 
                                    $nomM1, $prenomM1, $naissanceM1, $securiteM1, $emailM1, $emploiM1, $diplomeM1, $niveauM1, 
                                    $travailC, $derogationC, $numeroC, $conclusionC, $debutC, $finC, $avenantC, $executionC, $dureC, $typeC,
                                    $rdC, $raC, $rpC, $rsC, $rdC1, $raC1, $rpC1, $rsC1,
                                    $rdC2, $raC2, $rpC2, $rsC2,
                                    $salaireC, $caisseC, $logementC, $avantageC, $autreC,
                                    $lieuO, $priveO, $attesteO, $modeC, $prenomR, $dureCM,
                                    $id 
                                );
                                $response->getBody()->write(json_encode(['valid' => "Vous avez ajouté un cerfa, Un email de validation de création du compte a été envoyé"]));
                                return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                            } else {
                                throw new Exception("Erreur lors de l'envoi de l'email de création");
                            }
                        } catch (Exception $e) {
                            $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
                            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                        }
                    } else {
                        $response->getBody()->write(json_encode(['erreur' => "Erreur dans la création de l'étudiant"]));
                        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                    }

                }
            }else{
                         $result = $cerfa->save(null, $effectiveUserId,
                            $idemployeur, $idformation,
                            $nomA, $nomuA, $prenomA, $sexeA,
                            $naissanceA, $departementA, $communeNA, 
                            $nationaliteA, $regimeA, $situationA, $titrePA,
                            $derniereCA, $securiteA, $intituleA, $titreOA, 
                            $declareSA, $declareHA, $declareRA, $rueA, 
                            $voieA, $complementA, $postalA, $communeA, 
                            $numeroA, $emailA,
                            
                            $nomR, $emailR, $rueR, $voieR, $complementR, $postalR, $communeR, 
                            $nomM, $prenomM, $naissanceM, $securiteM, $emailM, $emploiM, $diplomeM, $niveauM, 
                            $nomM1, $prenomM1, $naissanceM1, $securiteM1, $emailM1, $emploiM1, $diplomeM1, $niveauM1, 
                            $travailC, $derogationC, $numeroC, $conclusionC, $debutC, $finC, $avenantC, $executionC, $dureC, $typeC,
                            $rdC, $raC, $rpC, $rsC, $rdC1, $raC1, $rpC1, $rsC1,
                            $rdC2, $raC2, $rpC2, $rsC2,
                            $salaireC, $caisseC, $logementC, $avantageC, $autreC,
                            $lieuO, $priveO, $attesteO, $modeC, $prenomR, $dureCM,
                            $id 
                        );  
            }

          
        }

        // Gestion du résultat (commune aux deux rôles)
        if (!isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['valid' => 'Vous avez ajouté un cerfa']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
} else {
    $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
}


    
})->add($auth);

$app->post('/api/updateCerfaContrat', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    $id=$data['id'];

   
    $nomM=$data['nomM']; 
    $prenomM=$data['prenomM'];
    $naissanceM=$data['naissanceM'];  
    $securiteM=$data['securiteM'];
    $emailM=$data['emailM'];
    $emploiM=$data['emploiM'];
    $diplomeM=$data['diplomeM'];
    $niveauM=$data['niveauM']; 
    $nomM1=$data['nomM1']; 
    $prenomM1=$data['prenomM1'];
    $naissanceM1=$data['naissanceM1'];  
    $securiteM1=$data['securiteM1'];
    $emailM1=$data['emailM1'];
    $emploiM1=$data['emploiM1'];
    $diplomeM1=$data['diplomeM1'];
    $niveauM1=$data['niveauM1'];
    $travailC=$data['travailC'];
    $derogationC=$data['derogationC'];
    $numeroC=$data['numeroC'];
    $conclusionC=$data['conclusionC'];
    $debutC=$data['debutC'];
    $finC=$data['finC'];
    $avenantC=$data['avenantC'];
    $executionC=$data['executionC'];
    $dureC=$data['dureC'];
    $typeC=$data['typeC'];
    $rdC=$data['rdC'];
    $raC=$data['raC'];
    $rpC=$data['rpC'];
    $rsC=$data['rsC'];
    $rdC1=$data['rdC1'];
    $raC1=$data['raC1'];
    $rpC1=$data['rpC1'];
    $rsC1=$data['rsC1'];
    $rdC2=$data['rdC2'];
    $raC2=$data['raC2'];
    $rpC2=$data['rpC2'];
    $rsC2=$data['rsC2'];
    $salaireC=$data['salaireC'];
    $caisseC=$data['caisseC'];
    $logementC=$data['logementC'];
    $avantageC=$data['avantageC'];
    $autreC=$data['autreC']; 
    $lieuO=$data['lieuO'];
    $priveO=$data['priveO'];
    $attesteO=$data['attesteO'];
    $modeC=$data['modeC'];
    $dureCM=$data['dureCM'];
   


   
        $cerfa = new Cerfa();
        $result =  $cerfa->updateCerfaContrat(
            $nomM, $prenomM, $naissanceM, $securiteM, $emailM, $emploiM, $diplomeM, $niveauM, 
            $nomM1, $prenomM1, $naissanceM1, $securiteM1, $emailM1, $emploiM1, $diplomeM1, $niveauM1, 
            $travailC, $derogationC, $numeroC, $conclusionC, $debutC, $finC, $avenantC, $executionC, $dureC, $typeC,
            $rdC, $raC, $rpC, $rsC, $rdC1, $raC1, $rpC1, $rsC1,
            $rdC2, $raC2, $rpC2, $rsC2,
            $salaireC, $caisseC, $logementC, $avantageC, $autreC,
            $lieuO, $priveO, $attesteO, $modeC, $dureCM,
            $id 
        );
        if (!isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['valid' => 'Vous avez ajouté un cerfa']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    


    
});

$app->post('/api/updateCerfaEtudiant', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    $id=$data['id'];

    
    $nomA=$data['nomA'];
    $nomuA=$data['nomuA'];
    $prenomA=$data['prenomA'];
    $sexeA=$data['sexeA'];
    $naissanceA=$data['naissanceA'];
    $departementA=$data['departementA'];
    $communeNA=$data['communeNA'];
    $nationaliteA=$data['nationaliteA'];
    $regimeA=$data['regimeA'];
    $situationA=$data['situationA'];
    $titrePA=$data['titrePA'];
    $derniereCA=$data['derniereCA'];
    $securiteA=$data['securiteA'];
    $intituleA=$data['intituleA'];
    $titreOA=$data['titreOA'];
    $declareSA=$data['declareSA'];
    $declareHA=$data['declareHA'];
    $declareRA=$data['declareRA'];
    $rueA=$data['rueA'];
    $voieA=$data['voieA'];
    $complementA=$data['complementA'];
    $postalA=$data['postalA'];
    $communeA=$data['communeA'];
    $numeroA=$data['numeroA'];
    $emailA=$data['emailA']; 

    $nomR=$data['nomR']; 
    $prenomR=$data['prenomR'];
    $emailR=$data['emailR']; 
    $rueR=$data['rueR'];
    $voieR=$data['voieR'];
    $complementR=$data['complementR'];
    $postalR=$data['postalR'];
    $communeR=$data['communeR'];

   
    if($id == null){
        $user = new User();
        $userselect = $user->getEtudiantDetailsForEmail($emailA);
        if($userselect !== null){
            $nomA=$userselect['firstname'];
            $prenomA=$userselect['lastname'];
            $naissanceA=$userselect['date_naissance'];
            $voieA=$userselect['adressePostale'];
            $postalA=$userselect['codePostal'];
            $communeA=$userselect['ville'];
        }
    }
   
   
    if ($role === 7 || $role === 3) {
        $cerfa = new Cerfa();

        $result =  $cerfa->updateCerfaEtudiant(
            $nomA, $nomuA, $prenomA, $sexeA,
            $naissanceA, $departementA, $communeNA, 
            $nationaliteA, $regimeA, $situationA, $titrePA,
            $derniereCA, $securiteA, $intituleA, $titreOA, 
            $declareSA, $declareHA, $declareRA, $rueA, 
            $voieA, $complementA, $postalA, $communeA, 
            $numeroA, $emailA,
            $nomR,$prenomR, $emailR, $rueR, $voieR, $complementR, $postalR, $communeR,
            $id 
        );
      
        if (!isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['valid' => 'Vous avez ajouté un cerfa']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }


    
})->add($auth);

$app->post('/api/updateCerfaByFormInformationApprenti', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    $id=$data['id'];
    $nomA=$data['nomA'];
    $nomuA=$data['nomuA'];
    $prenomA=$data['prenomA'];
    $sexeA=$data['sexeA'];
    $naissanceA=$data['naissanceA'];
    $departementA=$data['departementA'];
    $communeNA=$data['communeNA'];
    $nationaliteA=$data['nationaliteA'];
    $regimeA=$data['regimeA'];
    $situationA=$data['situationA'];
    $titrePA=$data['titrePA'];
    $derniereCA=$data['derniereCA'];
    $securiteA=$data['securiteA'];
    $intituleA=$data['intituleA'];
    $titreOA=$data['titreOA'];
    $declareSA=$data['declareSA'];
    $declareHA=$data['declareHA'];
    $declareRA=$data['declareRA'];
    $rueA=$data['rueA'];
    $voieA=$data['voieA'];
    $complementA=$data['complementA'];
    $postalA=$data['postalA'];
    $communeA=$data['communeA'];
    $numeroA=$data['numeroA'];
  

    $nomR=$data['nomR']; 
    $prenomR=$data['prenomR'];
    $emailR=$data['emailR']; 
    $rueR=$data['rueR'];
    $voieR=$data['voieR'];
    $complementR=$data['complementR'];
    $postalR=$data['postalR'];
    $communeR=$data['communeR'];

   
   
        $cerfa = new Cerfa();
        $result =  $cerfa->updateApprenti(
            $nomA, $nomuA, $prenomA, $sexeA,
            $naissanceA, $departementA, $communeNA, 
            $nationaliteA, $regimeA, $situationA, $titrePA,
            $derniereCA, $securiteA, $intituleA, $titreOA, 
            $declareSA, $declareHA, $declareRA, $rueA, 
            $voieA, $complementA, $postalA, $communeA, 
            $numeroA, 
            
            $nomR, $prenomR, $emailR, $rueR, $voieR, $complementR, $postalR, $communeR, 

           
            $id 
        );
        if (!isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['valid' => 'Vous avez modifié  un cerfa']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    


    
});

$app->post('/api/updateCerfaByFormInformation', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    $id=$data['id'];
    $nomA=$data['nomA'];
    $nomuA=$data['nomuA'];
    $prenomA=$data['prenomA'];
    $sexeA=$data['sexeA'];
    $naissanceA=$data['naissanceA'];
    $departementA=$data['departementA'];
    $communeNA=$data['communeNA'];
    $nationaliteA=$data['nationaliteA'];
    $regimeA=$data['regimeA'];
    $situationA=$data['situationA'];
    $titrePA=$data['titrePA'];
    $derniereCA=$data['derniereCA'];
    $securiteA=$data['securiteA'];
    $intituleA=$data['intituleA'];
    $titreOA=$data['titreOA'];
    $declareSA=$data['declareSA'];
    $declareHA=$data['declareHA'];
    $declareRA=$data['declareRA'];
    $rueA=$data['rueA'];
    $voieA=$data['voieA'];
    $complementA=$data['complementA'];
    $postalA=$data['postalA'];
    $communeA=$data['communeA'];
    $numeroA=$data['numeroA'];
  

    $nomR=$data['nomR']; 
    $prenomR=$data['prenomR'];
    $emailR=$data['emailR']; 
    $rueR=$data['rueR'];
    $voieR=$data['voieR'];
    $complementR=$data['complementR'];
    $postalR=$data['postalR'];
    $communeR=$data['communeR'];

    $nomM=isset($data['nomM'])?$data['nomM'] : null; 
    $prenomM=isset($data['prenomM'])?$data['prenomM'] : null;
    $naissanceM=isset($data['naissanceM'])?$data['naissanceM'] : null;  
    $securiteM=isset($data['securiteM'])?$data['securiteM'] : null;
    $emailM=isset($data['emailM'])?$data['emailM'] : null;
    $emploiM=isset($data['emploiM'])?$data['emploiM'] : null;
    $diplomeM=isset($data['diplomeM'])?$data['diplomeM'] : null;
    $niveauM=isset($data['niveauM'])?$data['niveauM'] : null; 

    $nomM1=isset($data['nomM1'])?$data['nomM1'] : null; 
    $prenomM1=isset($data['prenomM1'])?$data['prenomM1'] : null;
    $naissanceM1=isset($data['naissanceM1'])?$data['naissanceM1'] : null;  
    $securiteM1=isset($data['securiteM1'])?$data['securiteM1'] : null;
    $emailM1=isset($data['emailM1'])?$data['emailM1'] : null;
    $emploiM1=isset($data['emploiM1'])?$data['emploiM1'] : null;
    $diplomeM1=isset($data['diplomeM1'])?$data['diplomeM1'] : null;
    $niveauM1=isset($data['niveauM1']) ?$data['niveauM1'] : null;
   
        $cerfa = new Cerfa();
        $result =  $cerfa->update(
            $nomA, $nomuA, $prenomA, $sexeA,
            $naissanceA, $departementA, $communeNA, 
            $nationaliteA, $regimeA, $situationA, $titrePA,
            $derniereCA, $securiteA, $intituleA, $titreOA, 
            $declareSA, $declareHA, $declareRA, $rueA, 
            $voieA, $complementA, $postalA, $communeA, 
            $numeroA, 
            
            $nomR, $prenomR, $emailR, $rueR, $voieR, $complementR, $postalR, $communeR, 

            $nomM, $prenomM, $naissanceM, $securiteM, $emailM, $emploiM, $diplomeM, $niveauM, 
            $nomM1, $prenomM1, $naissanceM1, $securiteM1, $emailM1, $emploiM1, $diplomeM1, $niveauM1, 
            $id 
        );
        if (!isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['valid' => 'Vous avez modifié  un cerfa']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    


    
});

$app->post('/api/sendEmailFormApprenti', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    

    $requiredKeys = [
        'id', 'email'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['error' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
   
    $id=$data['id'];
    $email=$data['email'];

    if ($role === 7 || $role === 3) {
        $result =Email::sendEmailFormApprenti($email, $id);
        if($result){
            $response->getBody()->write(json_encode(['valid' => "Le formulaire cerfa a été envoyer  avec succès  a l'apprenti"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "Le formulaire cerfa n'a été envoyer    a l'apprenti"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }


    
})->add($auth);

$app->post('/api/sendEmailFormSignatureApprentiRepresentant', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    

    $requiredKeys = [
        'id', 'email'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['error' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
   
    $id=$data['id'];
    $email=$data['email'];

    if ($role === 7 || $role === 3) {
        $result =Email::sendEmailFormRepresentantApprenti($email, $id);
        if($result){
            $response->getBody()->write(json_encode(['valid' => "Le formulaire cerfa a été envoyé  avec succès  au représentant de  l'apprenti"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "Le formulaire cerfa n'a été envoyé au représentant del'apprenti"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }


    
})->add($auth);

$app->post('/api/sendEmailFormEmployeur', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    

    $requiredKeys = [
        'id', 'email'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['error' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
   
    $id=$data['id'];
    $email=$data['email'];

    if ($role === 7 || $role === 3) {
        $result =Email::sendEmailFormEmployeur($email, $id);
        if($result){
            $response->getBody()->write(json_encode(['valid' => "Le formulaire cerfa a été envoyer  avec succès  a l'employeur"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "Le formulaire cerfa n'a été envoyer    a l'employeur"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }


    
})->add($auth);
$app->post('/api/sendEmailFormSignatureEmployeur', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    

    $requiredKeys = [
        'id', 'email'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['error' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
   
    $id=$data['id'];
    $email=$data['email'];

    if ($role === 7 || $role === 3) {
        $result =Email::sendEmailFormSignatureEmployeur($email, $id);
        if($result){
            $response->getBody()->write(json_encode(['valid' => "Le cerfa a été envoyer  avec succès à l'employeur pour signature"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "Le cerfa n'a pas été envoyer  avec succès à l'employeur pour signature"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }


    
})->add($auth);

$app->post('/api/sendEmailFormSignatureConventionEmployeur', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    

    $requiredKeys = [
        'id', 'email'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['error' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
   
    $id=$data['id'];
    $email=$data['email'];

    if ($role === 7 || $role === 3) {
        $result =Email::sendEmailFormSignatureConventionEmployeur($email, $id);
        if($result){
            $response->getBody()->write(json_encode(['valid' => "La convention a été envoyer  avec succès à l'employeur pour signature"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "La convention n'a pas été envoyer  avec succès à l'employeur pour signature"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }


    
})->add($auth);

$app->post('/api/sendEmailFormContratEmployeur', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    

    $requiredKeys = [
        'id', 'email'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['error' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
   
    $id=$data['id'];
    $email=$data['email'];

    if ($role === 7 || $role === 3) {
        $result =Email::sendEmailFormContratEmployeur($email, $id);
        if($result){
            $response->getBody()->write(json_encode(['valid' => "Le coontrat a été envoyeé  avec succès à l'employeur "]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "Le contrat n'a pas été envoyé  avec succès à l'employeur "]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }


    
})->add($auth);


$app->post('/api/sendEmailFormSignatureApprenti', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    

    $requiredKeys = [
        'id', 'email'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['error' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
   
    $id=$data['id'];
    $email=$data['email'];

    if ($role === 7 || $role === 3) {
        $result =Email::sendEmailFormSignatureApprenti($email, $id);
        if($result){
            $response->getBody()->write(json_encode(['valid' => "Le cerfa a été envoyer  avec succès à l'apprenti pour signature"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "Le cerfa n'a pas été envoyer  avec succès à l'apprenti pour signature"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }


    
})->add($auth);

$app->post('/api/sendEmailFormSignatureEcole', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    

    $requiredKeys = [
        'id', 'email'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['error' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
   
    $id=$data['id'];
    $email=$data['email'];

    if ($role === 7 || $role === 3) {
        $result =Email::sendEmailFormSignatureEcole($email, $id);
        if($result){
            $response->getBody()->write(json_encode(['valid' => "Le cerfa a été envoyer  avec succès à l'école pour signature"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "Le cerfa n'a pas été envoyer  avec succès à l'école pour signature"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }


    
})->add($auth);

$app->post('/api/sendEmailFormSignatureConventionEcole', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    

    $requiredKeys = [
        'id', 'email'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['error' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
   
    $id=$data['id'];
    $email=$data['email'];

    if ($role === 7 || $role === 3) {
        $result =Email::sendEmailFormSignatureConventionEcole($email, $id);
        if($result){
            $response->getBody()->write(json_encode(['valid' => "La convention a été envoyer  avec succès à l'école pour signature"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "La convention n'a pas été envoyer  avec succès à l'école pour signature"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }


    
})->add($auth);

$app->post('/api/setPathSignature', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    

    $requiredKeys = [
        'id', 'path','prov'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['error' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
    $id=$data['id'];
    $path=$data['path'];
    $prov=$data['prov'];

    $cerfa = new Cerfa();
    $result =$cerfa->setPath($prov,$path,$id);
    if($result){
        $response->getBody()->write(json_encode(['valid' => "Le document a été mis à jour  avec succès"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }else{
        $response->getBody()->write(json_encode(['erreur' => "Le document n'a été mis à jour  "]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
    
});

$app->post('/api/cerfaSetFactureOpco', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $requiredKeys = [
        'id', 'factureOpco'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['erreur' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
    $id=$data['id'];
    $factureOpco=$data['factureOpco'];                               
    if ($role === 7 || $role === 3) {
        $cerfa= new Cerfa();
        $cerfas = $cerfa->setFactureOpco($factureOpco,$id);
        if (!$cerfas) {
            $response->getBody()->write(json_encode(['valid' => 'Modification réussie']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Erreur lors de la modification"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

$app->post('/api/cerfaSetConventionOpco', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $requiredKeys = [
        'id', 'conventionOpco'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['erreur' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
    $id=$data['id'];
    $conventionOpco=$data['conventionOpco'];                               

    if ($role === 7 || $role === 3) {
        $cerfa= new Cerfa();
        $cerfas = $cerfa->setConventionOpco($conventionOpco,$id);
        if (!$cerfas) {
            $response->getBody()->write(json_encode(['valid' => 'Modification réussie']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Erreur lors de la modification"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

$app->post('/api/cerfaSetCerfaOpco', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $requiredKeys = [
        'id', 'cerfaOpco'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['erreur' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
    $id=$data['id'];
    $cerfaOpco=$data['cerfaOpco'];                               

    if ($role === 7 || $role === 3) {
        $cerfa= new Cerfa();
        $cerfas = $cerfa->setCerfaOpco($cerfaOpco,$id);
        if (!$cerfas) {
            $response->getBody()->write(json_encode(['valid' => 'Modification réussie']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Erreur lors de la modification cerfaOpco"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

$app->post('/api/cerfaSetNumeroDeca', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $requiredKeys = [
        'id', 'numeroDeca'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['erreur' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
    $id=$data['id'];
    $numeroDeca=$data['numeroDeca'];                               

    if ($role === 7 || $role === 3) {
        $cerfa= new Cerfa();
        $cerfas = $cerfa->setNumeroDeca($numeroDeca,$id);
        if (!$cerfas) {
            $response->getBody()->write(json_encode(['valid' => 'Modification réussie']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Erreur lors de la modification  NumeroDeca"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

$app->post('/api/cerfaSetNumeroExterne', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $requiredKeys = [
        'id', 'numeroExterne'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['erreur' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
    $id=$data['id'];
    $numeroExterne=$data['numeroExterne'];                               

    if ($role === 7 || $role === 3) {
        $cerfa= new Cerfa();
        $cerfas = $cerfa->setNumeroExterne($numeroExterne,$id);
        if (!$cerfas) {
            $response->getBody()->write(json_encode(['valid' => 'Modification réussie']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Erreur lors de la modification NumeroExterne"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

$app->post('/api/cerfaSetNumeroInterne', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $requiredKeys = [
        'id', 'numeroInterne'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['erreur' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
    $id=$data['id'];
    $numeroInterne=$data['numeroInterne'];                               

    if ($role === 7 || $role === 3) {
        $cerfa= new Cerfa();
        $cerfas = $cerfa->setNumeroInterne($numeroInterne,$id);
        if (!$cerfas) {
            $response->getBody()->write(json_encode(['valid' => 'Modification réussie']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Erreur lors de la modification NumeroInterne"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

$app->post('/api/factureEcheance', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    
    $idcerfa=$data['idcerfa'];
    $numeroOF = $data['numeroOF'];
    $lieuF = $data['lieuF'];
  
   
   
    $ibanF = $data['ibanF'];
    $repreF = $data['repreF'];
    $emploiRF = $data['emploiRF'];
   
   

    $motif = $data['motif'];
    $montant = $data['montant'];

    $motif1 = $data['motif1'];
    $montant1 = $data['montant1'];

    $motif2 = $data['motif2'];
    $montant2 = $data['montant2'];

    $motif3 = $data['motif3'];
    $montant3 = $data['montant3'];

    $motif4 = $data['motif4'];
    $montant4 = $data['montant4'];

    $motif5 = $data['motif5'];
    $montant5 = $data['montant5'];

   


    $echeance1 = $data['echeance1'];
    $echeance2 = $data['echeance2'];
    $echeance3 = $data['echeance3'];
    $echeance4 = $data['echeance4'];
    

    $date1 = $data['date1'];
    $date2 = $data['date2'];
    $date3 = $data['date3'];
    $date4 = $data['date4'];

    $ht1 = $data['ht1'];
    $ht2 = $data['ht2'];
    $ht3 = $data['ht3'];
    $ht4 = $data['ht4'];

 

  
   
   


    if ($role === 7 || $role === 3) {
        $facture = new Facture();
        $tableaufacture = $facture->find($idcerfa);
    
        if (empty($tableaufacture)) {
            $type =1;
        } else {
            $type =2;
        }
    
        $result = $facture->save($type,
            $numeroOF, $lieuF, $ibanF, $repreF, $emploiRF, $motif, $motif1, $motif2, $motif3, $motif4, $motif5, 
            $montant, $montant1, $montant2, $montant3, $montant4, $montant5, 
            $echeance1, $echeance2, $echeance3, $echeance4, $date1, $date2, $date3, $date4, 
            $ht1, $ht2, $ht3, $ht4, $idcerfa
        );
    
        if (!isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['valid' => 'Vous avez ajouté une facture']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }


    
})->add($auth);

$app->post('/api/contactCerFacil', function (Request $request, Response $response) use ($key) {
    $data =$request->getParsedBody();
    

    $requiredKeys = [
      'name', 'email', 'phone',  'selectedDate','selectedTime'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['erreur' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
   
    $name=$data['name'];
    $email=$data['email'];
    $phone=$data['phone'];
    $message=$data['message'];
    $company=$data['company'];
    $selectedDate=$data['selectedDate'];
    $selectedTime=$data['selectedTime'];

  
     

        $result =Email::sendEmailContactCerFacil($name, $email, $phone, $message, $company, $selectedDate, 
        $selectedTime);
        if($result){
            $response->getBody()->write(json_encode(['valid' => "le rendez-vous a été pris avec succès"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "le rendez-vous n'a pas été pris avec succès"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    
});



$app->post('/api/newletterCerFacil', function (Request $request, Response $response) use ($key) {
    $data =$request->getParsedBody();
    

    $requiredKeys = [
      'email'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['erreur' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
   
       $email=$data['email'];


        $result =Email::sendEmailNewLetterCerFacil($email);
        if($result){
            $response->getBody()->write(json_encode(['valid' => "l'inscription a été pris avec succès"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "l'inscription n'a pas été pris avec succès"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    
});








