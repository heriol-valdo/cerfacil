<?php
use Slim\Factory\AppFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
$app->addBodyParsingMiddleware();



require_once __DIR__ . '/middlewares/auth.php';
require_once __DIR__ . '/middlewares/checkRole.php';
require_once __DIR__ . '/middlewares/checks.php';

require_once __DIR__ . '/routes/user/postUser.php';
require_once __DIR__ .'/routes/user/putUser.php';
require_once __DIR__ .'/routes/user/deleteUser.php';
require_once __DIR__ .'/routes/user/getUser.php';

require_once __DIR__ . '/routes/admin/postAdmin.php';
require_once __DIR__ .'/routes/admin/putAdmin.php';
require_once __DIR__ .'/routes/admin/getAdmin.php';

require_once __DIR__ .'/routes/gestionnaire-entreprise/postGestionnaireEntreprise.php';
require_once __DIR__ .'/routes/gestionnaire-entreprise/putGestionnaireEntreprise.php';
require_once __DIR__ .'/routes/gestionnaire-entreprise/getGestionnaireEntreprise.php';
require_once __DIR__ .'/routes/gestionnaire-entreprise/deleteGestionnaireEntreprise.php';

require_once __DIR__ .'/routes/formateur/postFormateur.php';
require_once __DIR__ .'/routes/formateur/getFormateur.php';
require_once __DIR__ .'/routes/formateur/putFormateur.php';

require_once __DIR__ .'/routes/etudiant/postEtudiant.php';
require_once __DIR__ .'/routes/etudiant/getEtudiant.php';
require_once __DIR__ .'/routes/etudiant/getAllEtudiant.php';
require_once __DIR__ .'/routes/etudiant/putEtudiant.php';

require_once __DIR__ .'/routes/gestionnaire-centre/postGestionnaireCentre.php';
require_once __DIR__ .'/routes/gestionnaire-centre/getGestionnaireCentre.php';
require_once __DIR__ .'/routes/gestionnaire-centre/putGestionnaireCentre.php';

require_once __DIR__ .'/routes/cours/postCours.php';
require_once __DIR__.'/routes/cours/deleteCours.php';
require_once __DIR__.'/routes/cours/getCours.php';

require_once __DIR__ .'/routes/formation/postFormation.php';
require_once __DIR__ .'/routes/formation/getFormation.php';
require_once __DIR__ .'/routes/formation/putFormation.php';
require_once __DIR__ .'/routes/formation/deleteFormation.php';

require_once __DIR__ .'/routes/session/postSession.php';
require_once __DIR__ .'/routes/session/getSession.php';
require_once __DIR__ .'/routes/session/putSession.php';
require_once __DIR__ .'/routes/session/deleteSession.php';

require_once __DIR__ .'/routes/formateurs-participant-session/postFormateursParticipantSession.php';
require_once __DIR__ .'/routes/formateurs-participant-session/getFormateursParticipantSession.php';

require_once __DIR__ .'/routes/conseiller-financeur/putFinanceur.php';
require_once __DIR__ .'/routes/conseiller-financeur/postFinanceur.php';
require_once __DIR__ .'/routes/conseiller-financeur/getFinanceur.php';

require_once __DIR__ .'/routes/centre-formation/postCentre.php';
require_once __DIR__ .'/routes/centre-formation/putCentre.php';
require_once __DIR__ .'/routes/centre-formation/getCentre.php';
require_once __DIR__ .'/routes/centre-formation/deleteCentre.php';

require_once __DIR__ .'/routes/entreprise/postEntreprise.php';
require_once __DIR__ .'/routes/entreprise/putEntreprise.php';
require_once __DIR__ .'/routes/entreprise/getEntreprise.php';
require_once __DIR__ .'/routes/entreprise/deleteEntreprise.php';

require_once __DIR__ .'/routes/salle/postSalle.php';
require_once __DIR__ .'/routes/salle/putSalle.php';
require_once __DIR__ .'/routes/salle/getSalle.php';
require_once __DIR__ .'/routes/salle/deleteSalle.php';

require_once __DIR__ .'/routes/equipement/postEquipement.php';
require_once __DIR__ .'/routes/equipement/putEquipement.php';
require_once __DIR__ .'/routes/equipement/getEquipement.php';
require_once __DIR__ .'/routes/equipement/deleteEquipement.php';

require_once __DIR__ .'/routes/ticket/postTicket.php';
require_once __DIR__ .'/routes/ticket/putTicket.php';
require_once __DIR__ .'/routes/ticket/getTicket.php';
require_once __DIR__ .'/routes/ticket/deleteTicket.php';

require_once __DIR__ .'/routes/tickets_echanges/postTicketEchange.php';
require_once __DIR__ .'/routes/tickets_echanges/getTicketEchange.php';
require_once __DIR__ .'/routes/tickets_echanges/deleteTicketEchange.php';

require_once __DIR__ .'/routes/absence/postAbsence.php';
require_once __DIR__ .'/routes/absence/putAbsence.php';
require_once __DIR__ .'/routes/absence/getAbsence.php';
require_once __DIR__ .'/routes/absence/deleteAbsence.php';

require_once __DIR__ .'/routes/opco/getOpco.php';
require_once __DIR__ .'/routes/opco/postOpco.php';
require_once __DIR__ .'/routes/opco/deleteOpco.php';

require_once __DIR__ .'/routes/formations/getFormations.php';
require_once __DIR__ .'/routes/formations/postFormations.php';
require_once __DIR__ .'/routes/formations/deleteFormations.php';

require_once __DIR__ .'/routes/entreprises/getEntreprises.php';
require_once __DIR__ .'/routes/entreprises/postEntreprises.php';
require_once __DIR__ .'/routes/entreprises/deleteEntreprises.php';

require_once __DIR__ .'/routes/cerfa/getCerfa.php';
require_once __DIR__ .'/routes/cerfa/postCerfa.php';
require_once __DIR__ .'/routes/cerfa/deleteCerfa.php';

require_once __DIR__ .'/routes/pointage/postPointage.php';
require_once __DIR__ .'/routes/pointage/getPointage.php';

require_once __DIR__ .'/routes/reservation/postReservation.php';
require_once __DIR__ .'/routes/reservation/putReservation.php';
require_once __DIR__ .'/routes/reservation/getReservation.php';
require_once __DIR__ .'/routes/reservation/deleteReservation.php';

require_once __DIR__ .'/routes/event/postEvent.php';
require_once __DIR__ .'/routes/event/getEvent.php';
require_once __DIR__ .'/routes/event/putEvent.php';
require_once __DIR__ .'/routes/event/deleteEvent.php';

require_once __DIR__ .'/routes/matieres/getMatieres.php';
require_once __DIR__ .'/routes/matieres/postMatieres.php';

require_once __DIR__ .'/routes/clientCerfa/postClientCerfa.php';
require_once __DIR__ .'/routes/clientCerfa/getAllClientCerfa.php';
require_once __DIR__ .'/routes/clientCerfa/putClientCerfa.php';

require_once __DIR__ .'/routes/produitCerfa/postProduitCerfa.php';
require_once __DIR__ .'/routes/produitCerfa/getAllProduitCerfa.php';
require_once __DIR__ .'/routes/produitCerfa/putProduitCerfa.php';
require_once __DIR__ .'/routes/produitCerfa/deleteProduitCerfa.php';


require_once __DIR__ .'/routes/abonnementCerfa/postAbonnementCerfa.php';
require_once __DIR__ .'/routes/abonnementCerfa/getAbonnementCerfa.php';

require_once __DIR__ .'/routes/infos_complementaires/PostInfosComplementaires.php';
require_once __DIR__ .'/routes/infos_complementaires/GetInfosComplementaires.php';

$app->run();