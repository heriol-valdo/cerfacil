<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__.'/../../models/EntrepriseModel.php';
require_once __DIR__.'/../../models/EntrepriseTypeModel.php';



//========================================================================================
// But : Permet d'ajouter une entreprise
// Rôles : admin
// Champs obligatoires : siret, nomEntreprise, nomDirecteur, adressePostale, codePostal,
// ville, telephone, ape, intracommunautaire, soumis_tva, domaineActivite, formeJuridique
// Champs facultatifs :  siteWeb, fax, logo, email
//========================================================================================
$app->post('/api/admin/entreprise/add', function (Request $request, Response $response) use ($key) {
    $data = $request->getParsedBody();
    $uploadedFiles = $request->getUploadedFiles();

    // Check => Intégrité du formulaire
    // -- Intégrité => Champs obligatoires
    if(!isset($data['siret'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Siret doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['nomEntreprise'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Nom de l'entreprise doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['nomDirecteur'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Nom du directeur doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['adressePostale'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Adresse doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['codePostal'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Code postal doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['ville'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Ville doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['telephone'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Téléphone doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['ape'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Ape doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['intracommunautaire'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Intracommunautaire doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['soumis_tva'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Soumis à la TVA doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['domaineActivite'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Domaine d'activité doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['formeJuridique'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Forme juridique doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // -- Intégrité => Champs facultatifs
    if(!isset($data['siteWeb'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Site web doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    
    if(!isset($data['fax'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Fax doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['email'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Email doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['id_centres_de_formation'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ id_centres_de_formation doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['is_accueil'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ is_accueil doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['is_financeur'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ is_financeur doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check => Champs obligatoires vides ?
    if(empty($data['siret'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Siret est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['nomEntreprise'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Nom de l'entreprise est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['nomDirecteur'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Nom du directeur est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['adressePostale'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Adresse est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['codePostal'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Code postal est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['ville'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Ville est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['telephone'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Téléphone est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['ape'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Ape est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['intracommunautaire'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Intracommunautaire est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['soumis_tva'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Soumis à la TVA est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['domaineActivite'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Domaine d'activité est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['formeJuridique'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Forme juridique est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    if($data['soumis_tva'] == "Oui"){
        $soumis_tva = 1;
    } else {
        $soumis_tva = 0;
    }
    
    // Check => Existence SIRET dans BDD
    $entreprise = new Entreprise(); 
    $entreprise->siret = $data['siret'];
    $result= $entreprise->boolSiret();
    if($result){
        $response->getBody()->write(json_encode(['erreur' => 'Ce  siret existe deja']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    //stockage du logo dans le dossier images
    $logoFile = $uploadedFiles['logo'] ?? null;
    if ($logoFile ) {
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
    }

    // Chargement données
    // -- Chargement => Données Entreprise obligatoires
    $entreprise->nomEntreprise = $data['nomEntreprise'];
    $entreprise->nomDirecteur = $data['nomDirecteur'];
    $entreprise->adressePostale = $data['adressePostale'];
    $entreprise->codePostal = $data['codePostal'];
    $entreprise->ville = $data['ville'];
    $entreprise->telephone = $data['telephone'];
    $entreprise->ape = $data['ape'];
    $entreprise->intracommunautaire = $data['intracommunautaire'];
    $entreprise->soumis_tva = $soumis_tva;
    $entreprise->domaineActivite = $data['domaineActivite'];
    $entreprise->formeJuridique = $data['formeJuridique'];

    $entreprise->isActif = 1;

    // -- Chargement => Données Entreprise facultatives
    $entreprise->siteWeb = !empty($data['siteWeb']) ? $data['siteWeb'] : '';
    $entreprise->fax = !empty($data['fax']) ? $data['fax'] : '';
    $entreprise->logo = $logoPath;
    $entreprise->email = !empty($data['email']) ? $data['email'] : '';

    // Ajout donnéees table entreprises_type
    if(!$entreprise->addEntreprise()){
        $response->getBody()->write(json_encode(['erreur' => 'Erreur lors de la création des données']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    //récupération id de l'entreprise
    $entreprise->siret= $data['siret'];
    $idEntreprise = $entreprise->searchIdBySiret();

    $entrepriseType = new EntrepriseType();
    $entrepriseType->id_entreprises = intval($idEntreprise);

    // -- Chargement => Données EntrepriseType facultatives
    $entrepriseType->is_accueil = !empty($data['is_accueil']) ? '1' : '0';
    $entrepriseType->id_centres_de_formation = !empty($data['id_centres_de_formation']) ? intval($data['id_centres_de_formation']) : '';
    $entrepriseType->is_financeur = !empty($data['is_financeur']) ? '1' : '0';

    if(!$entrepriseType->addEntrepriseType()){
        $entreprise->id = $idEntreprise;
        $entreprise->deleteEntreprise();
        $response->getBody()->write(json_encode(['erreur' => "Erreur dans l'enregistrement du type d'entreprise".$data['id_centres_de_formation']]));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }


    $response->getBody()->write(json_encode(['valid' => 'Vous avez ajouté une entreprise']));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

})->add($auth)->add($checkAdmin);