<?php


namespace Projet\Controller\Scolarite;



use Projet\Controller\Admin\AdminsController;

use Projet\Database\Alternant;

use Projet\Model\App;

use Projet\Model\Privilege;
use Exception;



class AlternantController extends AdminsController
{
    public function index(){
        $user = $this->user;
        //Privilege::hasPrivilege(Privilege::$AllView,$user->privilege);
        $nbreParPage = 20;
        
        $search = (isset($_GET['search'])&&!empty($_GET['search'])) ? $_GET['search'] : null;
        $sexe = (isset($_GET['sexe'])&&!empty($_GET['sexe'])) ? $_GET['sexe'] : null;
       
        $debut = (isset($_GET['debut'])&&!empty($_GET['debut'])) ? date(MYSQL_DATE_FORMAT, strtotime($_GET['debut'])) : null;
        $end = (isset($_GET['end'])&&!empty($_GET['end'])) ? date(MYSQL_DATE_FORMAT, strtotime($_GET['end'])) : null;
        $nbre = Alternant::countBySearchType($search,$sexe,$debut,$end);
        $nbrePages = ceil($nbre->Total / $nbreParPage);
        if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbrePages) {
            $pageCourante = $_GET['page'];
        } else {
            $pageCourante = 1;
            $params['page'] = $pageCourante;
        }
        $alternants = Alternant::searchType($nbreParPage,$pageCourante,$search,$sexe,$debut,$end);
        $this->render('admin.scolarite.alternant',compact('search','alternants','user','nbre','nbrePages'));
       
    }
    public function save(){
       
        header('content-type: application/json');
        $return = [];
        $tab = ["add", "edit"];
        if (isset($_POST['nom']) && !empty($_POST['nom']) &&isset($_POST['prenom']) && !empty($_POST['prenom'])
            &&isset($_POST['sexe']) && !empty($_POST['sexe']) &&isset($_POST['numero']) && !empty($_POST['numero'])
            &&isset($_POST['email'])&& isset($_POST['action']) && !empty($_POST['action']) && isset($_POST['id']) 
            && in_array($_POST["action"], $tab)) {
            $nom = trim($_POST['nom']);
            $prenom = trim($_POST['prenom']);
            $sexe = $_POST['sexe'];
            $numero = str_replace(' ','',trim($_POST['numero']));
            $email = trim($_POST['email']);
            $action = $_POST['action'];
            $id = (int)$_POST['id'];
            $errorEmail = "Cette adresse email existe déjà, veuillez le changer";
            $errorTel = "Ce numéro de téléphone existe déjà, veuillez le changer";
            
            
            
                
                if($action == "edit") {
                    Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
                    if (!empty($id)){
                        $alternant = Alternant::find($id);
                       
                            $bool = true;
                            $bool1 = true;
                            if($alternant->numero!=$numero){
                                $cla = Alternant::byNumero($numero);
                                if($cla)
                                    $bool1 = false;
                            }
                            if(!empty($email) && $alternant->email!=$email){
                                $cla = Alternant::byEmail($email);
                                if($cla)
                                    $bool = false;
                            }
                            if($bool1){
                                if($bool){
                                       
                                        $pdo = App::getDb()->getPDO();
                                        try{
                                            $pdo->beginTransaction();
                                            Alternant::save($nom,$prenom,$numero,$sexe,$email,$id);
                                            $message = "L'alternant  a été mis à jour avec succès";
                                            $this->session->write('success',$message);
                                            $pdo->commit();
                                            $return = array("statuts" => 0, "mes" => $message);
                                        }catch (Exception $e){
                                            $pdo->rollBack();
                                            $message = $this->error;
                                            $return = array("statuts" => 1, "mes" => $message);
                                        }
                                       
                                }else{
                                    $return = array("statuts" => 1, "mes" => $errorEmail);
                                }
                            }else{
                                $return = array("statuts" => 1, "mes" => $errorTel);
                            }
                        
                    } else {
                        $message = $this->error;
                        $return = array("statuts" => 1, "mes" => $message);
                    }
                } else {
                    Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
                    $bool1 = true;
                    $cla = Alternant::byNumero($numero);
                    if($cla)
                        $bool1 = false;
                    $bool = true;
                    if(!empty($email)){
                        $cla1 = Alternant::byEmail($email);
                        if($cla1)
                            $bool = false;
                    }
                    if($bool1){
                        if($bool){
                                $pdo = App::getDb()->getPDO();
                                    try{
                                        $pdo->beginTransaction();
                                       
                                        
                                        Alternant::save($nom,$prenom,$numero,$sexe,$email);
                                        
                                        $message = "L'alternant a été ajouté avec succès";
                                        $this->session->write('success',$message);
                                        $pdo->commit();
                                        $return = array("statuts" => 0, "mes" => $message);
                                    }catch (Exception $e){
                                        $pdo->rollBack();
                                        $message = $this->error;
                                        $return = array("statuts" => 1, "mes" => $message);
                                    }
                                
                        }else{
                            $return = array("statuts" => 1, "mes" => $errorEmail);
                        }
                    }else{
                        $return = array("statuts" => 1, "mes" => $errorTel);
                    }
                }
            
        } else {
            $message = "Veiullez renseigner tous les champs requis";
            $return = array("statuts" => 1, "mes" => $message);
        }
        echo json_encode($return);
    }

    public function delete(){
        Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        header('content-type: application/json');
        if (isset($_POST['id'])&&!empty($_POST['id'])){
            $id = $_POST['id'];
            $alternant = Alternant::find($id);
            if ($alternant){
                Alternant::delete($id);
                $message = "L'alternant  a été supprimée avec succès";
                $this->session->write('success',$message);
                $return = array("statuts"=>0, "mes"=>$message);

            }else{
                $message = "L'alternant  n'existe plus";
                $return = array("statuts"=>1, "mes"=>$message);
            }
        }else{
            $message = "Renseigner l'id SVP !!!";
            $return = array("statuts"=>1, "mes"=>$message);
        }
        echo json_encode($return);
    }


   


}


