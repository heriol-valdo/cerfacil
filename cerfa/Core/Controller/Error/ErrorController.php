<?php


namespace Projet\Controller\Error;

use Projet\Model\ExceptionController;

class ErrorController extends ExceptionController {

    protected $template = 'Templates/error';

    public function __construct(){
        parent::__construct();
        $this->viewPath = 'Views/';
    }

}