<?php


namespace Projet\Controller\Admin;

use Projet\Database\Profil;
use Projet\Database\User;
use Projet\Model\App;
use Projet\Model\Controller;
use Projet\Model\Router;
use Projet\Model\Guzzle;

class AdminController extends Controller {

    protected $template = 'Templates/admin_layout';
    protected $user;
    public $error = "Soucis lors de l'execution de la requête, recharger et réessayer";
    public $empty = "SVP renseigner correctement tous les champs requis";

    public function __construct(){
        parent::__construct();
        $this->viewPath = 'Views/';
        $auth = App::getDBAuth();
       
        if($auth->isLogged()){
            $result = Guzzle::sendRequest([], 'user/profile', 'get');
            $user = json_decode($result);

        
          
            if($user->data->role == 'ClientCERFA' || $user->data->role == 'Gestionnaire de centre'){
                $this->user = $user;
            }else{
                $this->session->delete('dbauth1');
                $this->session->write('lastUrlAsked',App::url(Router::getRoute()));
                $this->checker("Vous ne pouvez pas  accéder à cette ressource car votre role n'est pas permis",'');
                
            }
          
        }else{
            $this->session->write('lastUrlAsked',App::url(Router::getRoute()));
            $this->checker("Vous devez vous connecter pour accéder à cette ressource",'');
            
        }
    }
    
}