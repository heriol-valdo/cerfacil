<?php

return
    [

        ""=>'\Projet\Controller\Page\AuthController#login',
        "ajax"=>'\Projet\Controller\Page\HomeController#log',
        "error"=>'\Projet\Controller\Error\HomeController#error',
        "error_db"=>'\Projet\Controller\Error\HomeController#error_db',
        "unauthorize"=>'\Projet\Controller\Error\HomeController#unauthorize',
        "expired"=>'\Projet\Controller\Error\HomeController#expired',
        "logout"=>'\Projet\Controller\Page\HomeController#logout',
        "login"=>'\Projet\Controller\Page\AuthController#login',

        "resetPasswordSend"=>'\Projet\Controller\Page\AuthController#resetPasswordSend',
        "ajaxresetPasswordSend"=>'\Projet\Controller\Page\HomeController#resetPasswordSend',

        "resetPassword"=>'\Projet\Controller\Page\AuthController#resetPassword',
        "ajaxresetPassword"=>'\Projet\Controller\Page\HomeController#resetPassword',

        "home"=>'\Projet\Controller\Admin\HomeController#index',
        "session"=>'\Projet\Controller\Admin\HomeController#session',
        "profil"=>'\Projet\Controller\Admin\HomeController#profil',
        "password"=>'\Projet\Controller\Admin\HomeController#password',
        "password/change"=>'\Projet\Controller\Admin\HomeController#changePassword',
        "searchhome"=>'\Projet\Controller\Admin\HomeController#searchhome',

        "admins"=>'\Projet\Controller\Admin\AdminsController#index',
        "admins/save"=>'\Projet\Controller\Admin\AdminsController#save',
        "admins/activate"=>'\Projet\Controller\Admin\AdminsController#activate',
        "admins/reset"=>'\Projet\Controller\Admin\AdminsController#reset',
        "admins/delete"=>'\Projet\Controller\Admin\AdminsController#delete',
        "admins/setPhoto"=>'\Projet\Controller\Admin\AdminsController#setPhoto',

        "formations"=>'\Projet\Controller\Admin\FormationsController#index',
        "formations/save"=>'\Projet\Controller\Admin\FormationsController#save',
        "formations/delete"=>'\Projet\Controller\Admin\FormationsController#delete',

        "opco"=>'\Projet\Controller\Admin\OpcoController#index',
        "opco/save"=>'\Projet\Controller\Admin\OpcoController#save',
        "opco/delete"=>'\Projet\Controller\Admin\OpcoController#delete',

        "employeurs"=>'\Projet\Controller\Admin\EntreprisesController#index',
        "employeurs/save"=>'\Projet\Controller\Admin\EntreprisesController#save',
        "employeurs/sendEmail"=>'\Projet\Controller\Admin\EntreprisesController#sendEmail', // new
        "employeurs/delete"=>'\Projet\Controller\Admin\EntreprisesController#delete',

        "produits"=>'\Projet\Controller\Scolarite\ProduitsController#index',
        "produits/save"=>'\Projet\Controller\Scolarite\ProduitsController#save',
        "produits/saves"=>'\Projet\Controller\Scolarite\ProduitsController#saves',

     

    


        "alternants"=>'\Projet\Controller\Scolarite\AlternantController#index',
        "alternants/save"=>'\Projet\Controller\Scolarite\AlternantController#save',
        "alternants/delete"=>'\Projet\Controller\Scolarite\AlternantController#delete',
       
       

        "cerfas"=>'\Projet\Controller\Scolarite\CerfaController#index',
        "cerfasdetails"=>'\Projet\Controller\Scolarite\CerfaController#indexDetails',
        "cerfas/save"=>'\Projet\Controller\Scolarite\CerfaController#save',
        "cerfas/updateCerfaEtudiant"=>'\Projet\Controller\Scolarite\CerfaController#updateCerfaEtudiant',
        "cerfas/updateCerfaContrat"=>'\Projet\Controller\Scolarite\CerfaController#updateCerfaContrat',
        "cerfas/savenew"=>'\Projet\Controller\Scolarite\CerfaController#savenew',
        "cerfas/delete"=>'\Projet\Controller\Scolarite\CerfaController#delete',
        "cerfas/send"=>'\Projet\Controller\Scolarite\CerfaController#send',
        "cerfas/sendEmployeur"=>'\Projet\Controller\Scolarite\CerfaController#sendEmployeur',
        "cerfas/sendSignatureEntreprise"=>'\Projet\Controller\Scolarite\CerfaController#sendSignatureEntreprise',
        "cerfas/sendSignatureConventionEntreprise"=>'\Projet\Controller\Scolarite\CerfaController#sendSignatureConventionEntreprise',
        "cerfas/sendSignatureApprenti"=>'\Projet\Controller\Scolarite\CerfaController#sendSignatureApprenti',
        "cerfas/sendSignatureEcole"=>'\Projet\Controller\Scolarite\CerfaController#sendSignatureEcole',
        "cerfas/sendSignatureConventionEcole"=>'\Projet\Controller\Scolarite\CerfaController#sendSignatureConventionEcole',
        "cerfas/signatureManuelleEcole"=>'\Projet\Controller\Scolarite\CerfaController#signatureManuelleEcole',
        "cerfas/signatureManuelleConventionEcole"=>'\Projet\Controller\Scolarite\CerfaController#signatureManuelleConventionEcole',
        "cerfas/sendOpco"=>'\Projet\Controller\Scolarite\CerfaController#sendOpco',
        "cerfas/sendOpcoConvention"=>'\Projet\Controller\Scolarite\CerfaController#sendOpcoConvention',
        "cerfas/remplirConvention"=>'\Projet\Controller\Scolarite\CerfaController#remplirConvention',
        "cerfas/sendOpcoFacture"=>'\Projet\Controller\Scolarite\CerfaController#sendOpcoFacture',
        "cerfas/sendFacture"=>'\Projet\Controller\Scolarite\CerfaController#sendFacture',
        "cerfas/pdf"=>'\Projet\Controller\Admin\StatsController#cerfas',
        "cerfas/pdf/convention"=>'\Projet\Controller\Admin\StatsController#cerfasConvention',
        "cerfas/csv"=>'\Projet\Controller\Admin\StatsController#cerfasCsv',
        "cerfas/remplirEcheance"=>'\Projet\Controller\Scolarite\CerfaController#remplirEcheance',
        "cerfas/sendContratEmployeur"=>'\Projet\Controller\Scolarite\CerfaController#sendContratEmployeur', //new 
        "cerfas/changeEntreprise"=>'\Projet\Controller\Scolarite\CerfaController#changeEntreprise', 
        "cerfas/changeFormation"=>'\Projet\Controller\Scolarite\CerfaController#changeFormation', 
        "cerfas/getControllerEtat"=>'\Projet\Controller\Scolarite\CerfaController#getControllerEtat', //new 
        "cerfas/sendOpcoDocument"=>'\Projet\Controller\Scolarite\CerfaController#sendOpcoDocument', //new 

        "cerfas/getNumeroDecas"=>'\Projet\Controller\Scolarite\CerfaController#getNumeroDecas', //new 
        "cerfas/getEtatLabels"=>'\Projet\Controller\Scolarite\CerfaController#getEtatLabelss', //new 
        "cerfas/updateNumeroDeca" => '\Projet\Controller\Scolarite\CerfaController#updateNumeroDeca',
        "cerfas/sendSignatureApprentiRepresentant"=>'\Projet\Controller\Scolarite\CerfaController#sendSignatureApprentiRepresentant',

        "assistance"=>'\Projet\Controller\Scolarite\AssistanceController#index', //new 
        "assistance/save"=>'\Projet\Controller\Scolarite\AssistanceController#save', //new 
        "assistanceDetails"=>'\Projet\Controller\Scolarite\AssistanceController#details', //new 
        "assistanceDeleteMessage"=>'\Projet\Controller\Scolarite\AssistanceController#assistanceDeleteMessage', //new
        "assistance/saveMessage"=>'\Projet\Controller\Scolarite\AssistanceController#saveMessage', //new 
        

        "assistanceIA"=>'\Projet\Controller\Scolarite\AssistanceController#assistanceIA', //new 
        "simulateur"=>'\Projet\Controller\Scolarite\AssistanceController#simulateur', //new 
        "simulateur_generated_rncp"=>'\Projet\Controller\Scolarite\AssistanceController#simulateur_generated_rncp', //new 


        
        





       
        
        

    ];
