<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ .'/../../models/UserModel.php';
require_once __DIR__ .'/../../models/AdminModel.php';
require_once __DIR__ .'/../../models/EtudiantModel.php';
require_once __DIR__ .'/../../models/FormateurModel.php';
require_once __DIR__ .'/../../models/GestionnaireCentreModel.php';
require_once __DIR__ .'/../../models/GestionnaireEntrepriseModel.php';
require_once __DIR__ .'/../../models/FinanceurModel.php';

//========================================================================================
// But : modifier le mot de passe
// Rôles : tous
// champs: newPassword, oldPassword
//========================================================================================
$app->put('/api/password/update', function (Request $request, Response $response) use ($key) {  
    $token=$request->getAttribute('user');
    $id = $token['id'];

    $user = new User();
    $user->id = $id;

    $data = $request->getParsedBody();

    if(!isset($data['oldPassword'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ Mot de passe actuel doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    if(!isset($data['newPassword'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ Nouveau mot de passe doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    if(empty($data['oldPassword'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ Mot de passe actuel est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $oldPassword = $data['oldPassword'];
    $newPassword = $data['newPassword'];

    if(empty($oldPassword) || empty($newPassword)) {
        $response->getBody()->write(json_encode(['erreur' => "Les champs 'Mot de passe actuel' et 'Nouveau mot de passe' ne doivent pas être vides"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Verify if the current password matches the one stored in the database
    $og_user = $user->searchForId();
    $og_password = $og_user['password'];

    if(!password_verify($data['oldPassword'], $og_password)){
        $response->getBody()->write(json_encode(['erreur' => "Il y a une erreur dans le mot de passe actuel entré"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } 

    if(!password_verify($data['newPassword'], $og_password)){
        $changedPassword = 1;
        $user->password = $data['newPassword'];
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Le nouveau mot de passe doit être différent de l'ancien"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    if($user->updatePassword()) {
        $response->getBody()->write(json_encode(['valid' => "Le mot de passe a bien été modifié"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Le mot de passe n'a pas pu être modifié"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
})->add($auth);

$app->put('/api/user/{user_id}/updateprofil', function (Request $request, Response $response, $args) use ($key) {  
    $data = $request->getParsedBody();
    $token=$request->getAttribute('user');
    $id = $token['id'];
    $role =$token['role'];
    $idByRole=$token['idByRole'];

    // Check => Format param
    if(empty($args['user_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ user_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $args['user_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $user = new User();
    $user->id = $args['user_id'];

    if(!($user->boolId())){
        $response->getBody()->write(json_encode(['erreur' => "L'utilisateur n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $userDatas = $user->searchForId();

    // changements dans la table user

    // Reconnaissance si changement effectué
    $changedEmail = 0;

    $og_email = $userDatas['email'];

      if(isset($data['newEmail']) && !empty($data['newEmail']) && ($data['newEmail'] != $og_email)) {
        $user->email = $data['newEmail'];
        $emailExist = $user->boolEmail();
        if(!$emailExist){
            $changedEmail = 1;  
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Cette adresse mail est déjà utilisée"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
      }

    //changements en fonction du rôle
    if($userDatas["id_role"] == 1){
        if($role == 1 && $args['user_id'] == $id){
            $admin = new Admin();
            $admin->id_users = $userDatas['id'];

            $changedFirstname = 0;
            $changedLastname = 0;
            $changedTelephone = 0;
            $changedLieuTravail = 0;

            $og_role = $admin->searchForId();

            $og_firstname = $og_role['firstname'];
            $og_lastname = $og_role['lastname'];
            $og_telephone = $og_role['telephone'];
            $og_lieuTravail = $og_role['lieu_travail'];


            // Séries de conditions pour reconnaître un changement
            if(isset($data['newFirstname']) && !empty($data['newFirstname']) && ($data['newFirstname'] != $og_firstname)) {
                $changedFirstname = 1;
                $admin->firstname = $data['newFirstname'];
            }
            if(isset($data['newLastname']) && !empty($data['newLastname']) && ($data['newLastname'] != $og_lastname)) {
                $changedLastname = 1;
                $admin->lastname = $data['newLastname'];
            }
            if(isset($data['newTelephone']) && !empty($data['newTelephone']) && ($data['newTelephone'] != $og_telephone)) {
                $changedTelephone = 1;
                $admin->telephone = $data['newTelephone'];
            }
            if(isset($data['newLieuTravail']) && !empty($data['newLieuTravail']) && ($data['newLieuTravail'] != $og_lieuTravail)) {
                $changedLieuTravail = 1;
                $admin->lieu_travail = $data['newLieuTravail'];
            }

            // Si aucun changement >> erreur
            if($changedEmail == 0 &&
                $changedFirstname == 0 &&
                $changedLastname == 0 &&
                $changedTelephone == 0 &&
                $changedLieuTravail == 0){
                    $response->getBody()->write(json_encode(['erreur' => "Veuillez modifier au moins une donnée"]));
                    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }

            $changedFirstname && $admin->updateFirstname();
            $changedLastname && $admin->updateLastname();
            $changedTelephone && $admin->updateTelephone();
            $changedLieuTravail && $admin->updateLieuTravail();

        }else {
            $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits suffisants pour effectuer cette action"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    if($userDatas["id_role"] == 2){
        if($role==1 || ($role==2 && $id == $args['user_id'])){
        $gestionnaireEntreprise = new GestionnaireEntreprise();
        $gestionnaireEntreprise->id_users = $user->id;

        $changedFirstname = 0;
        $changedLastname = 0;
        $changedTelephone = 0;
        $changedLieuTravail = 0;
        $changedEntreprise = 0;

        $og_role = $gestionnaireEntreprise->searchForId();

        $og_firstname = $og_role['firstname'];
        $og_lastname = $og_role['lastname'];
        $og_telephone = $og_role['telephone'];
        $og_lieuTravail = $og_role['lieu_travail'];
        $og_entreprise = $og_role['id_entreprises'];

        if($role==1){
            if(isset($data['newEntreprise']) && !empty($data['newEntreprise']) && ($data['newEntreprise'] != $og_entreprise)) {
                $entreprise = new Entreprise();
                $entreprise->id = $data['newEntreprise'];
                $entrepriseExist = $entreprise->boolId();
                if($entrepriseExist){
                    $changedEntreprise = 1;
                    $gestionnaireEntreprise->id_entreprises = $data['newEntreprise'];
                } else {
                    $response->getBody()->write(json_encode(['erreur' => "L'entreprise sélectionnée n'existe pas'"]));
                    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                }
            }
        }

        if(isset($data['newFirstname']) && !empty($data['newFirstname']) && ($data['newFirstname'] != $og_firstname)) {
            $changedFirstname = 1;
            $gestionnaireEntreprise->firstname = $data['newFirstname'];
        }
        if(isset($data['newLastname']) && !empty($data['newLastname']) && ($data['newLastname'] != $og_lastname)) {
            $changedLastname = 1;
            $gestionnaireEntreprise->lastname = $data['newLastname'];
        }
        if(isset($data['newTelephone']) && !empty($data['newTelephone']) && ($data['newTelephone'] != $og_telephone)) {
            $changedTelephone = 1;
            $gestionnaireEntreprise->telephone = $data['newTelephone'];
        }
        if(isset($data['newLieuTravail']) && !empty($data['newLieuTravail']) && ($data['newLieuTravail'] != $og_lieuTravail)) {
            $changedLieuTravail = 1;
            $gestionnaireEntreprise->lieu_travail = $data['newLieuTravail'];
        }

        // Si aucun changement >> erreur
        if($changedEmail == 0 &&
        $changedFirstname == 0 &&
        $changedLastname == 0 &&
        $changedTelephone == 0 &&
        $changedLieuTravail == 0 &&
        $changedEntreprise == 0){
            $response->getBody()->write(json_encode(['erreur' => "Veuillez modifier au moins une donnée"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $changedFirstname && $gestionnaireEntreprise->updateFirstname();
        $changedLastname && $gestionnaireEntreprise->updateLastname();
        $changedTelephone && $gestionnaireEntreprise->updateTelephone();
        $changedLieuTravail && $gestionnaireEntreprise->updateLieuTravail();
        $changedEntreprise && $gestionnaireEntreprise->updateEntreprise();

        }else {
            $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits suffisants pour effectuer cette action"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

    } 

    if($userDatas["id_role"] == 3){
        
        if($role==1 || ($role==3 && $id == $args['user_id'])){
            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_users = $user->id;

            $changedFirstname = 0;
            $changedLastname = 0;
            $changedTelephone = 0;
            $changedCentreFormation = 0;

            $og_role = $gestionnaireCentre->searchForId();

            $og_firstname = $og_role['firstname'];
            $og_lastname = $og_role['lastname'];
            $og_telephone = $og_role['telephone'];
            $og_centre = $og_role['id_centres_de_formation'];

            if($role==1){
                if(isset($data['newCentre']) && !empty($data['newCentre']) && ($data['newCentre'] != $og_centre)) {
                    $centreFormation = new CentreFormation();
                    $centreFormation->id = $data['newCentre'];
                    $centreFormationExist = $centreFormation->boolId();
                    if($centreFormationExist){
                        $changedCentreFormation = 1;
                        $gestionnaireCentre->id_centres_de_formation = $data['newCentre'];
                    } else {
                        $response->getBody()->write(json_encode(['erreur' => "Le centre de formation sélectionné n'existe pas'"]));
                        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                    }
                }
            }

            if(isset($data['newFirstname']) && !empty($data['newFirstname'])  && ($data['newFirstname'] != $og_firstname)) {
                $changedFirstname = 1;
                $gestionnaireCentre->firstname = $data['newFirstname'];
            }
            if(isset($data['newLastname']) && !empty($data['newLastname'])  && ($data['newLastname'] != $og_lastname)) {
                $changedLastname = 1;
                $gestionnaireCentre->lastname = $data['newLastname'];
            }
            if(isset($data['newTelephone']) && !empty($data['newTelephone'])  && ($data['newTelephone'] != $og_telephone)) {
                $changedTelephone = 1;
                $gestionnaireCentre->telephone = $data['newTelephone'];
            }

            // Si aucun changement >> erreur
            if($changedEmail == 0 &&
            $changedFirstname == 0 &&
            $changedLastname == 0 &&
            $changedTelephone == 0 &&
            $changedCentreFormation == 0){
                $response->getBody()->write(json_encode(['erreur' => "Veuillez modifier au moins une donnée"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }

            $changedFirstname && $gestionnaireCentre->updateFirstname();
            $changedLastname && $gestionnaireCentre->updateLastname();
            $changedTelephone && $gestionnaireCentre->updateTelephone();
            $changedCentreFormation && $gestionnaireCentre->updateCentreFormation();

        }else {
            $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits suffisants pour effectuer cette action"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    if($userDatas["id_role"] == 4){
        
        if($role==1 || ($role==4 && $id == $args['user_id'])){
            $formateur = new Formateur();
            $formateur->id_users = $user->id;

        $changedFirstname = 0;
        $changedLastname = 0;
        $changedAdresse = 0;
        $changedCodePostal = 0;
        $changedVille = 0;
        $changedTelephone = 0;
        $changedSiret = 0;
        $changedCentreFormation = 0;

        $og_role = $formateur->searchForId();
        

        $og_firstname = $og_role['firstname'];
        $og_lastname = $og_role['lastname'];
        $og_adressePostale = $og_role['adressePostale'];
        $og_codePostal = $og_role['codePostale'];
        $og_ville = $og_role['ville'];
        $og_telephone = $og_role['telephone'];
        $og_centre = $og_role['id_centres_de_formation'];
        $og_siret = $og_role['siret'];

        if($role==1){
            if(!empty($data['newSiret'])  && ($data['newSiret'] != $og_siret)) {
                $changedSiret = 1;
                $formateur->siret = $data['newSiret'];
            }
            if(!empty($data['newCentre'])  && ($data['newCentre'] != $og_role['id_centres_de_formation'])) {
                $changedCentreFormation = 1;
                $formateur->id_centres_de_formation = $data['newCentre'];
            }
        }

        if(isset($data['newFirstname']) && !empty($data['newFirstname'])  && ($data['newFirstname'] != $og_firstname)) {
            $changedFirstname = 1;
            $formateur->firstname = $data['newFirstname'];
        }
        if(isset($data['newLastname']) && !empty($data['newLastname'])  && ($data['newLastname'] != $og_lastname)) {
            $changedLastname = 1;
            $formateur->lastname = $data['newLastname'];
        }
        if( isset($data['newAdressePostale']) && !empty($data['newAdressePostale'])  && ($data['newAdressePostale'] != $og_adressePostale)) {
            $changedAdresse = 1;
            $formateur->adressePostale = $data['newAdressePostale'];
        }
        if(isset($data['newCodePostal']) && !empty($data['newCodePostal'])  && ($data['newCodePostal'] != $og_codePostal)) {
            $changedCodePostal = 1;
            $formateur->codePostal = $data['newCodePostal'];
        }
        if(isset($data['newVille']) && !empty($data['newVille'])  && ($data['newVille'] != $og_ville)) {
            $changedVille = 1;
            $formateur->ville = $data['newVille'];
        }

        if(isset($data['newTelephone']) && isset($data['newTelephone']) && !empty($data['newTelephone'])  && ($data['newTelephone'] != $og_telephone)) {
            $changedTelephone = 1;
            $formateur->telephone = $data['newTelephone'];
        }

        // Si aucun changement >> erreur
        if($changedEmail == 0 &&
        $changedFirstname == 0 &&
        $changedLastname == 0 &&
        $changedAdresse == 0 &&
        $changedCodePostal == 0 &&
        $changedVille == 0 &&
        $changedTelephone == 0 &&
        $changedCentreFormation == 0 && 
        $changedSiret == 0){
            $response->getBody()->write(json_encode(['erreur' => "Veuillez modifier au moins une donnée"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $changedEmail && $user->updateEmail();

        $changedFirstname && $formateur->updateFirstname();
        $changedLastname && $formateur->updateLastname();
        $changedAdresse && $formateur->updateAdressePostale();
        $changedCodePostal && $formateur->updateCodePostal();
        $changedVille && $formateur->updateVille();
        $changedTelephone && $formateur->updateTelephone();
        $changedCentreFormation && $formateur->updateCentreFormation();
        $changedSiret && $formateur->updateSiret();

        } else {
            $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits suffisants pour effectuer cette action"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    if($userDatas["id_role"] == 5){

        if($role==1 || $role==3 || $role==5){

            $etudiant = new Etudiant();
            $etudiant->id_users = $user->id;  
        
            $changedFirstname = 0;
            $changedLastname = 0;
            $changedAdresse = 0;
            $changedCodePostal = 0;
            $changedVille = 0;
            $changedDateNaissance = 0;
        
            $changedEntreprise = 0;
            $changedCentreFormation = 0;
            $changedFinanceur = 0;
            $changedSession = 0;
            $og_role = $etudiant->searchForId();

            if($role==3){
                $gestionnaireCentre= new GestionnaireCentre();  
                $gestionnaireCentre->id_users=$id;
                $gestionnaireDatas= $gestionnaireCentre->searchForId();
                if($gestionnaireDatas['id_centres_de_formation']!= $og_role['id_centres_de_formation']){
                    $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits suffisants pour effectuer cette action"]));
                    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                }
            }
        
            $og_firstname = $og_role['firstname'];
            $og_lastname = $og_role['lastname'];
            $og_adressePostale = $og_role['adressePostale'];
            $og_codePostal = $og_role['codePostal'];
            $og_ville = $og_role['ville'];
            $og_dateNaissance = $og_role['date_naissance'];
        
            $og_entreprise = $og_role['id_entreprises'];
            $og_centre = $og_role['id_centres_de_formation'];
            $og_financeur = $og_role['id_conseillers_financeurs'];
            $og_session = $og_role['id_session'];

            if($role==1 || $role==3){
            
                if(isset($data['newLastname']) && !empty($data['newLastname'])  && ($data['newLastname'] != $og_lastname)) {
                    $changedLastname = 1;
                    $etudiant->lastname = $data['newLastname'];
                }
            
                if(isset($data['newFirstname']) && !empty($data['newFirstname'])  && ($data['newFirstname'] != $og_firstname)) {
                    $changedFirstname = 1;
                    $etudiant->firstname = $data['newFirstname'];
                }

                if(isset($data['newEntreprise']) && !empty($data['newEntreprise']) && ($data['newEntreprise'] != $og_entreprise)) {
                    $entreprise = new Entreprise();
                    $entreprise->id = $data['newEntreprise'];
                    $entrepriseExist = $entreprise->boolId();
                    if($entrepriseExist){
                        $changedEntreprise = 1;
                        $etudiant->id_entreprises = $data['newEntreprise'];
                    } else {
                        $response->getBody()->write(json_encode(['erreur' => "L'entreprise sélectionnée n'existe pas"]));
                        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                    }
                }
                if(isset($data['newCentre']) && !empty($data['newCentre']) && ($data['newCentre'] != $og_centre)) {
                    $centreFormation = new CentreFormation();
                    $centreFormation->id = $data['newCentre'];
                    $centreFormationExist = $centreFormation->boolId();
                    if($centreFormationExist){
                        $changedCentreFormation = 1;
                        $etudiant->id_centres_de_formation = $data['newCentre'];
                    
                    } else {
                        $response->getBody()->write(json_encode(['erreur' => "Le centre de formation sélectionné n'existe pas"]));
                        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                    }
                }

                if(isset($data['newDateNaissance']) && !empty($data['newDateNaissance'])  && ($data['newDateNaissance'] != $og_dateNaissance)) {
                    $changedDateNaissance = 1;
                    $etudiant->date_naissance = $data['newDateNaissance'];
                }
                
                if(isset($data['newFinanceur']) && !empty($data['newFinanceur']) && ($data['newFinanceur'] != $og_financeur)) {
                    $financeur = new Financeur();
                    $financeur->id = $data['newFinanceur'];
                    $financeurExist = $financeur->checkExist();
                    
                    if($financeurExist){
                        $changedFinanceur = 1;
                        $etudiant->id_conseillers_financeurs = $data['newFinanceur'];
                    } else {
                        $response->getBody()->write(json_encode(['erreur' => "Le conseiller financeur sélectionné n'existe pas"]));
                        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                    }
                }
                if(isset($data['newSession']) && !empty($data['newSession']) && ($data['newSession'] != $og_session)) {
                    $session = new Session();
                    $session->id = $data['newSession'];
                    $sessionExist = $session->boolId();
                    if($sessionExist){
                        $changedSession = 1;
                        $etudiant->id_session = $data['newSession'];
                    } else {
                        $response->getBody()->write(json_encode(['erreur' => "La session n'existe pas"]));
                        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                    }
                }
            }

            if(isset($data['newAdressePostale']) && !empty($data['newAdressePostale'])  && ($data['newAdressePostale'] != $og_adressePostale)) {
                $changedAdresse = 1;
                $etudiant->adressePostale = $data['newAdressePostale'];
            }
            if(isset($data['newCodePostal']) && !empty($data['newCodePostal'])  && ($data['newCodePostal'] != $og_codePostal)) {
                $changedCodePostal = 1;
                $etudiant->codePostal = $data['newCodePostal'];
            }
            if(isset($data['newVille']) && !empty($data['newVille'])  && ($data['newVille'] != $og_ville)) {
                $changedVille = 1;
                $etudiant->ville = $data['newVille'];
            }
        
            // Si aucun changement >> erreur
            if($changedEmail == 0 &&
            $changedFirstname == 0 &&
            $changedLastname == 0 &&
            $changedAdresse == 0 &&
            $changedCodePostal == 0 &&
            $changedVille == 0 &&
            $changedDateNaissance == 0 &&
            $changedEntreprise == 0 && 
            $changedCentreFormation == 0 &&
            $changedFinanceur == 0 &&
            $changedSession == 0){
                $response->getBody()->write(json_encode(['erreur' => "Veuillez modifier au moins une donnée"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        
        
            $changedFirstname && $etudiant->updateFirstname();
            $changedLastname && $etudiant->updateLastname();
            $changedAdresse && $etudiant->updateAdressePostale();
            $changedCodePostal && $etudiant->updateCodePostal();
            $changedVille && $etudiant->updateVille();
            $changedDateNaissance && $etudiant->updateDateNaissance();
            $changedEntreprise && $etudiant->updateEntreprise();
            $changedCentreFormation && $etudiant->updateCentreFormation();
            $changedFinanceur && $etudiant->updateFinanceur();
            $changedSession && $etudiant->updateSession();
        }else {
            $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits suffisants pour effectuer cette action "]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    if($userDatas["id_role"] == 6){
        if($role==1 || ($role==6 && $id == $args['user_id'])){
            $financeur = new Financeur();
            $financeur->id_users = $user->id;

            $changedFirstname = 0;
            $changedLastname = 0;
            //$changedTelephone = 0;
            $changedTypeFinanceur = 0;
            $changedEntreprise = 0;

            $og_role = $financeur->searchForId();

            $og_firstname = $og_role['firstname'];
            $og_lastname = $og_role['lastname'];
            //$og_telephone = $og_role['telephone'];
            $og_typeFinanceur = $og_role['type_financeur'];
            $og_entreprise = $og_role['id_entreprises'];


            if(isset($data['newFirstname']) && !empty($data['newFirstname']) && ($data['newFirstname'] != $og_firstname)) {
                $changedFirstname = 1;

                $financeur->firstname = $data['newFirstname'];
            }
            if(isset($data['newLastname']) && !empty($data['newLastname']) && ($data['newLastname'] != $og_lastname)) {
                $changedLastname = 1;
                $financeur->lastname = $data['newLastname'];
            }
            if(isset($data['newTypeFinanceur']) && !empty($data['newTypeFinanceur']) && ($data['newTypeFinanceur'] != $og_typeFinanceur)) {
                $changedTypeFinanceur = 1;
                $financeur->type_financeur = $data['newTypeFinanceur'];
            }
            if(isset($data['newEntreprise']) && !empty($data['newEntreprise']) && ($data['newEntreprise'] != $og_entreprise)) {
                require_once(__DIR__."/../../models/EntrepriseModel.php");
                $entreprise = new Entreprise();
                $entreprise->id = $data['newEntreprise'];
                $entrepriseExist = $entreprise->boolId();
                if($entrepriseExist){
                    $changedEntreprise = 1;
                    $financeur->id_entreprises = $data['newEntreprise'];
                } else {
                    $response->getBody()->write(json_encode(['erreur' => "L'entreprise sélectionnée n'existe pas'"]));
                    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                }
            }

            // Si aucun changement >> erreur / $changedTelephone == 0 &&
            if($changedEmail == 0 &&
            $changedFirstname == 0 &&
            $changedLastname == 0 &&
            $changedTypeFinanceur == 0 &&
            $changedEntreprise == 0){
                $response->getBody()->write(json_encode(['erreur' => "Veuillez modifier au moins une donnée"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }

            $changedFirstname && $financeur->updateFirstname();
            $changedLastname && $financeur->updateLastname();
            //$changedTelephone && $gestionnaireEntreprise->updateTelephone();
            $changedTypeFinanceur && $financeur->updateTypeFinanceur();
            $changedEntreprise && $financeur->updateEntreprise();

        }else {
            $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits suffisants pour effectuer cette action"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    $changedEmail && $user->updateEmail();

    $response->getBody()->write(json_encode(['valid' => "L'utilisateur a été mis à jour"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth);


//========================================================================================
// But : modifier le mot de passe sans attendre le mot de passe actuel
// Rôles : tous
// champs: newPassword
//========================================================================================

$app->put('/api/password/reset/check', function (Request $request, Response $response) use ($key) {  

    $user = new User();
     // Début vérification formulaire mdp
     $data = $request->getParsedBody();


    if(!isset($data['reset_token']) || empty($data['reset_token']) ){
        $response->getBody()->write(json_encode(['erreur' => "Il n'y a pas de token de réinitialisation"]));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    // Vérification validité du reset_token
    $resetToken = $data['reset_token'];

    if (strpos($resetToken, '::::') == false) {
        $response->getBody()->write(json_encode(['erreur' => "Le token de réinitialisation est au mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    $tokenParts = explode('::::', $data['reset_token']);

    $tokenDateTime = $tokenParts[1];
    $tokenDateTimeObj = DateTime::createFromFormat('Y-m-d_H:i:s', $tokenDateTime);

    // Add 15 minutes to the token datetime
    $tokenDateTimeObj->add(new DateInterval('PT15M'));

    // Get the current datetime
    $currentDateTime = new DateTime();

    // Si le token n'est plus en cours > Renvoi erreur 
    if ($tokenDateTimeObj < $currentDateTime) {
        $user->clearResetToken();
        $response->getBody()->write(json_encode(['erreur' => "La demande a expiré, veuillez recommencer"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }


    // Début vérification formulaire mdp

    if(!isset($data['newPassword'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ Nouveau mot de passe doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    if(empty($data['newPassword'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ Nouveau mot de passe est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    // Succès : changement mdp
    $user->reset_token = $resetToken;
    // Check reset_token existant
    $tokenExist = $user->boolResetToken();
    if(!$tokenExist){
        $response->getBody()->write(json_encode(['erreur' => "Le token n'existe pas dans la base de données"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    $user->password = $data['newPassword'];
    
    if($user->resetPassword()) {
        $user->clearResetToken();
        $response->getBody()->write(json_encode(['valid' => "Le mot de passe a bien été modifié"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } else {
        $response->getBody()->write(json_encode(['erreur' => "le mot de passe n'a pas pu être modifié"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
});

//========================================================================================
// But : Met à jour le champ id_apolearn d'un user donné
//========================================================================================
$app->put('/api/user/{id_users}/update/apolearn_infos', function (Request $request, Response $response, $param) use ($key) {  
    $token=$request->getAttribute('user');
    $user = new User();
    $user->id = $token['id'];
    $user->id_role= $token['role'];

    $data = $request->getParsedBody(); // Pas de body pour le moment juste ID

    try{
        if(filter_var($param['id_users'], FILTER_VALIDATE_INT) === false || filter_var($data['apolearn_id'], FILTER_VALIDATE_INT) === false){
            throw new Exception ("Paramètres incorrects");
        }

        $target = new User();
        $target->id = $param['id_users'];
        $targetExist = $target->boolId();
        if(!$targetExist){
            throw new Exception("L'utilisateur n'existe pas");
        }
        $target->id_apolearn = $data['apolearn_id'];
        $target->username_apolearn = $data['apolearn_username'];
        $target->id_role = $target->searchIdRoleForIdUsers();

        if(empty($target->id_role)){
            throw new Exception("Impossible de récupérer le rôle de l'utilisateur recherché");
        }
        
        switch ($user->role) {
            case 3:
                $user_id_centre = $user->searchIdCentreForIdUsers();
                $target_id_centre = $target->searchIdCentreForIdUsers();
                if($user_id_centre != $target_id_centre){
                    throw new Exception("Accès interdit");
                }
                break;
        }

        $result = $target->updateApolearnInfos();
        if($result == false){
            throw new Exception ('Erreur dans la mise à jour de la base de données');
        } else {
            $response->getBody()->write(json_encode(['valid' => "Le compte Apolearn a été créé avec succès. Un email de validation a été envoyé."]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
    } catch (Exception $e){
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth)->add($check_role([1,3]))->add($check_required(["apolearn_id", "apolearn_username"]));