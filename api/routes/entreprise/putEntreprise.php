<?php // /routes/entreprise/putEntreprise.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/EntrepriseModel.php';

//========================================================================================
// But : Permet de modifier les infos d'une entreprise
// Rôles : admin
// Param: entreprise_id
// Champs possibles : siret, nomEntreprise, nomDirecteur, adressePostale, codePostal, ville, telephone, ape, intracommunautaire, soumis_tva, domaineActivite, formeJuridique, siteWeb, fax, logo, email
//========================================================================================
$app->post('/api/admin/entreprise/{entreprise_id}/update', function (Request $request, Response $response, $param) use ($key) {
    $entreprise = new Entreprise();
    // Check => Format param
    if(empty($param['entreprise_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ entreprise_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $param['entreprise_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $uploadedFiles = $request->getUploadedFiles();

    $entreprise->id = $param['entreprise_id'];

    // Check existence de l'entreprise
    $entrepriseExist = $entreprise->boolId();
    if(!$entrepriseExist){
        $response->getBody()->write(json_encode(['erreur' => "L'entreprise n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    if($data['soumis_tva'] == "Oui"){
        $soumis_tva = 1;
    } else {
        $soumis_tva = 0;
    }

    // Détection de changement
    $changedSiret = 0;
    $changedNomEntreprise = 0;
    $changedNomDirecteur = 0; 
    $changedAdressePostale = 0; 
    $changedCodePostal = 0;
    $changedVille = 0;
    $changedTelephone = 0;
    $changedApe = 0; 
    $changedIntracommunautaire = 0; 
    $changedIsActif = 0; 
    $changedSoumisTva = 0;
    $changedDomaineActivite = 0;
    $changedFormeJuridique = 0;
    // -- Données facultatives
    $changedSiteWeb = 0; 
    $changedFax = 0; 
    $changedLogo = 0;
    $changedEmail = 0;

    // Récupération informations originales de l'entreprise
    $og_info = $entreprise->searchForId();

    $og_siret = $og_info['siret'];
    $og_nomEntreprise = $og_info['nomEntreprise'];
    $og_nomDirecteur = $og_info['nomDirecteur'];
    $og_adressePostale = $og_info['adressePostale'];
    $og_codePostal = $og_info['codePostal'];
    $og_ville = $og_info['ville'];
    $og_telephone = $og_info['telephone'];
    $og_ape = $og_info['ape'];
    $og_intracommunautaire = $og_info['intracommunautaire'];
    $og_isActif = $og_info['isActif'];
    $bool_isActif = filter_var($og_isActif, FILTER_VALIDATE_BOOLEAN);
    $og_soumisTva = $og_info['soumis_tva'];
    $bool_soumisTva = filter_var($og_soumisTva, FILTER_VALIDATE_BOOLEAN);
    $og_domaineActivite = $og_info['domaineActivite'];
    $og_formeJuridique = $og_info['formeJuridique'];
    $og_siteWeb = $og_info['siteWeb'];
    $og_fax = $og_info['fax'];
    $og_logo = $og_info['logo'];
    $og_email = $og_info['email'];

    // Comparaison données originales VS données de la requête
    $data = $request->getParsedBody();

    // -- SIRET
    if(isset($data['siret']) && !empty($data['siret']) && ($data['siret'] != $og_siret)) {
        $entreprise->siret = $og_info['siret'];
        $entrepriseSiretExist = $entreprise->boolSiret();
        if(!$boolSiret){
            $entreprise->siret = $data['siret'];
            $changedSiret = 1;
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Ce SIRET est déjà attribué"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // -- nomEntreprise
    if(isset($data['nomEntreprise']) && !empty($data['nomEntreprise'])  && ($data['nomEntreprise'] != $og_nomEntreprise)) {
        $changedNomEntreprise = 1;
        $entreprise->nomEntreprise = $data['nomEntreprise'];
    }

    // -- nomDirecteur
    if(isset($data['nomDirecteur']) && !empty($data['nomDirecteur'])  && ($data['nomDirecteur'] != $og_nomDirecteur)) {
        $changedNomDirecteur = 1;
        $entreprise->nomDirecteur = $data['nomDirecteur'];
    }

    // -- adressePostale
    if(isset($data['adressePostale']) && !empty($data['adressePostale'])  && ($data['adressePostale'] != $og_adressePostale)) {
        $changedAdressePostale = 1;
        $entreprise->adressePostale = $data['adressePostale'];
    }

    // -- codePostal
    if(isset($data['codePostal']) && !empty($data['codePostal'])  && ($data['codePostal'] != $og_codePostal)) {
        $changedCodePostal = 1;
        $entreprise->codePostal = $data['codePostal'];
    }

    // -- ville
    if(isset($data['ville']) && !empty($data['ville'])  && ($data['ville'] != $og_ville)) {
        $changedVille = 1;
        $entreprise->ville = $data['ville'];
    }

    // -- telephone
    if(isset($data['telephone']) && !empty($data['telephone'])  && ($data['telephone'] != $og_telephone)) {
        $changedTelephone = 1;
        $entreprise->telephone = $data['telephone'];
    }

    // -- ape
    if(isset($data['ape']) && !empty($data['ape'])  && ($data['ape'] != $og_ape)) {
        $changedApe = 1;
        $entreprise->ape = $data['ape'];
    }

    // -- intracommunautaire
    if(isset($data['intracommunautaire']) && !empty($data['intracommunautaire'])  && ($data['intracommunautaire'] != $og_intracommunautaire)) {
        $changedIntracommunautaire = 1;
        $entreprise->intracommunautaire = $data['intracommunautaire'];
    }
    
    // -- isActif
    if(isset($data['isActif']) && !empty($data['isActif'])) {
        // Transforme les données reçues en vrai booleen
        $newIsActif = filter_var($data['isActif'], FILTER_VALIDATE_BOOLEAN);
        if($newIsActif != $bool_isActif){
            $changedIsActif = 1;
            $entreprise->isActif = $newIsActif;
        }
    }

    // -- soumisTVA
    if(isset($data['soumis_tva']) ) {
        // Transforme les données reçues en vrai booleen
        $newSoumisTva = filter_var($data['soumis_tva'], FILTER_VALIDATE_BOOLEAN);
        if($newSoumisTva != $bool_soumisTva){
            $changedSoumisTva = 1;
            $entreprise->soumis_tva = $newSoumisTva;
        }
    }




    // -- domaineActivite
    if(isset($data['domaineActivite']) && !empty($data['domaineActivite'])  && ($data['domaineActivite'] != $og_domaineActivite)) {
        $changedDomaineActivite = 1;
        $entreprise->domaineActivite = $data['domaineActivite'];
    }

    // -- formeJuridique
    if(isset($data['formeJuridique']) && !empty($data['formeJuridique'])  && ($data['formeJuridique'] != $og_formeJuridique)) {
        $changedFormeJuridique = 1;
        $entreprise->formeJuridique = $data['formeJuridique'];
    }

    // -- siteWeb
    if(isset($data['siteWeb']) && !empty($data['siteWeb'])  && ($data['siteWeb'] != $og_siteWeb)) {
        $changedSiteWeb = 1;
        $entreprise->siteWeb = $data['siteWeb'];
    }

    // -- fax
    if(isset($data['fax']) && !empty($data['fax'])  && ($data['fax'] != $og_fax)) {
        $changedFax = 1;
        $entreprise->fax = $data['fax'];
    }

    // -- email
    if(isset($data['email']) && !empty($data['email'])  && ($data['email'] != $og_email)) {
        $changedEmail = 1;
        $entreprise->email = $data['email'];
    }


    //stockage du logo dans le dossier images
    $logoFile = $uploadedFiles['logo'] ?? null;
    if ($logoFile) {
        $logoContent = $logoFile->getStream()->getContents();
            if ($logoContent === false) {
                $response->getBody()->write(json_encode(['erreur' => 'Erreur lors de la lecture du fichier téléchargé.']));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }
            
            $originalFilename = $logoFile->getClientFilename();
            
            $extension = pathinfo($originalFilename, PATHINFO_EXTENSION);
            
            $filename = sprintf('%s.%s', uniqid(), $extension);
            
            $directory = __DIR__ . '/../../images';
            $targetPath = $directory . DIRECTORY_SEPARATOR . $filename;
            
            $logoFile->moveTo($targetPath);

          
            if (!file_exists($targetPath)) {
                $response->getBody()->write(json_encode(['erreur' => 'Erreur lors du déplacement du fichier.']));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }

            $logoPath = 'images/' . $filename;
            $entreprise->logo = $logoPath;
            $changedLogo = 1;
    }



    // Si aucun changement >> erreur
    if($changedSiret == 0 &&
    $changedNomEntreprise == 0 &&
    $changedNomDirecteur == 0 && 
    $changedAdressePostale == 0 && 
    $changedCodePostal == 0 &&
    $changedVille == 0 &&
    $changedTelephone == 0 &&
    $changedApe == 0 && 
    $changedIntracommunautaire == 0 && 
    $changedIsActif == 0 && 
    $changedSoumisTva == 0 &&
    $changedDomaineActivite == 0 &&
    $changedFormeJuridique == 0 &&
    $changedSiteWeb == 0 && 
    $changedFax == 0 && 
    $changedLogo == 0 &&
    $changedEmail == 0){
        $response->getBody()->write(json_encode(['erreur' => "Veuillez modifier au moins une donnée"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Si changement >>> Update
    $changedSiret && $entreprise->updateSiret();
    $changedNomEntreprise && $entreprise->updateNomEntreprise();
    $changedNomDirecteur && $entreprise->updateNomDirecteur();
    $changedAdressePostale && $entreprise->updateAdressePostale();
    $changedCodePostal && $entreprise->updateCodePostal();
    $changedVille && $entreprise->updateVille();
    $changedTelephone && $entreprise->updateTelephone();
    $changedApe && $entreprise->updateApe();
    $changedIntracommunautaire && $entreprise->updateIntracommunautaire();
    $changedIsActif && $entreprise->updateIsActif();
    $changedSoumisTva && $entreprise->updateSoumisTva();
    $changedDomaineActivite && $entreprise->updateDomaineActivite();
    $changedFormeJuridique && $entreprise->updateFormeJuridique();
    $changedSiteWeb && $entreprise->updateSiteWeb();
    $changedFax && $entreprise->updateFax();
    $changedLogo && $entreprise->updateLogo();
    $changedEmail && $entreprise->updateEmail();
    
    // Succès
    $response->getBody()->write(json_encode(['valid' => "L'entreprise a bien été mise à jour"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);    
})->add($auth)->add($checkAdmin);