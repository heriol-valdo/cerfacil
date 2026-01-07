<?php


namespace Projet\Controller\Printer;

use Projet\Database\Enseignant;
use Projet\Database\Profil;
use Projet\Model\App;
use Projet\Model\Controller;

class PrintController extends Controller {

    protected $template = 'Templates/printer';
    protected $user;

    public function __construct(){
        parent::__construct();
        $this->viewPath = 'Views/';
        $auth = App::getDBAuth();
        if($auth->isLogged()){
            $user = Profil::find($auth->user());
            $this->user = $user;
        }else{
            App::interdit();
        }
    }
    
}