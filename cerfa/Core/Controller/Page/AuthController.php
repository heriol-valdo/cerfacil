<?php


namespace Projet\Controller\Page;


use Projet\Model\App;


class AuthController extends PageController{

    public function middleware(){
        if(App::getDBAuth()->isLogged()){
            App::redirect(App::url("home"));
            $this->session->write("danger","Cette page est indisponible en mode connection");
        }
    }

    public function login(){
        $this->middleware();
        $this->render('page.home.login');
    }


    public function resetPasswordSend(){
        $this->middleware();
        $this->render('page.home.resetPasswordSend');
    }

    public function resetPassword(){
        $this->middleware();
        $this->render('page.home.resetPassword');
    }
    public function loginAction(){

    }

}