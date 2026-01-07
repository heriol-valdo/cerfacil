<?php


namespace Projet\Controller\Page;

use Projet\Database\Visite;
use Projet\Model\App;
use Projet\Model\Controller;
use Projet\Model\Encrypt;

class PageController extends Controller {

    protected $template = 'Templates/default';

    public function __construct(){
        parent::__construct();
        //$this->check();
        $this->viewPath = 'Views/';
    }

    public function check(){
        if(App::getDBAuth()->isLogged()){
            App::redirect(App::url('home'));
        }
    }

 

}