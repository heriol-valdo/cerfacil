<?php

return [
    '' => 'LoginController#index',
    'l' => 'LoginController#index',
    'login' => 'LoginController#login',
    'logout' => 'LogoutController#logout',
    'home' => 'HomeController#index',
    'profil' => 'HomeController#profil',
    'editPassword' => 'HomeController#editPassword',
    'editPasswordSend' => 'HomeController#editPasswordSend',
    'updateProfil' => 'HomeController#updateProfil',
    'updateProfilSend' => 'HomeController#updateProfilSend',
    'askPassword' => 'HomeController#askPassword',
    'askPasswordSend' => 'HomeController#askPasswordSend',
    'resetPassword' => 'HomeController#resetPassword',
    'resetPasswordSend' => 'HomeController#resetPasswordSend',



    'clientCerfa' => 'ClientCerfaController#index',
    'addClientCerfa' => 'ClientCerfaController#save',
    'updateClientCerfa' => 'ClientCerfaController#update',
    'deleteClientCerfa' => 'ClientCerfaController#delete',
    'detailsAchatsClientCerfa' => 'ClientCerfaController#detailsAchatsClientCerfa',
    'detailsCerfasClientCerfa' => 'ClientCerfaController#detailsCerfasClientCerfa',

    'produitCerfa' => 'ProduitCerfaController#index',
    'addProduitCerfa' => 'ProduitCerfaController#save',
    'updateProduitCerfa' => 'ProduitCerfaController#update',
    'deleteProduitCerfa' => 'ProduitCerfaController#delete',

    'admins' => 'AdminsController#index',
    'addAdmins' => 'AdminsController#save',
    'updateAdmins' => 'AdminsController#update',
    'deleteAdmins' => 'AdminsController#delete',



    'centreFormation' => 'CentreFormationController#index',
    'updateCentreFormation' => 'CentreFormationController#update',
    'addCentreFormation' => 'CentreFormationController#save',
    'deleteCentreFormation' => 'CentreFormationController#delete',

    'entreprises' => 'EntrepriseController#index',
    'updateEntreprise' => 'EntrepriseController#update',
    'addEntreprise' => 'EntrepriseController#save',
    'deleteEntreprise' => 'EntrepriseController#delete',

    'assistance' => 'AssistanceController#index',
    'VoirDemande' => 'AssistanceController#VoirDemande',
    'repondreTicket' => 'AssistanceController#repondreTicket',
    'deleteMassageTicket' => 'AssistanceController#deleteMassageTicket',


    'gestionnaireCentreFormation' => 'GestionnaireCentreFormationController#index',
    'updateGestionnaireCentreFormation' => 'GestionnaireCentreFormationController#update',
    'addGestionnaireCentreFormation' => 'GestionnaireCentreFormationController#save',



];
?>
