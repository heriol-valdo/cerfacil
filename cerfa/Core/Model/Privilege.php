<?php


namespace Projet\Model;


class Privilege {


   
    

    public static $AllView = 'ALL_VIEW';

    public static $RecrutementView = 'Voir Le  recrutement';
    public static$CandidatViewNew= 'Voir les candidat';

    public static function getLesPrivileges(){
        $tab = [
        self::$RecrutementView,
        self::$CandidatViewNew,    
        self::$AllView,    
    ];
        return $tab;
    }

    public static function getIndexTabOfPrivileges(){
        $tab = [
            self::$RecrutementView => 'Voir Le  recrutement',
            self::$CandidatViewNew=> 'Voir s candidat',
            self::$AllView=> 'Voir tous le contenu ',

            ];
        return $tab;
    }

    public static function getOptionsSelect(){
        $default = '<option value="">Ajouter un privilège</option>';

        $group1 = ' 
            <optgroup label="nouvel ajout"> 
                <option value="'.self::$RecrutementView.'">Voir les recrutement </option>  
                <option value="'.self::$CandidatViewNew.'">voir les candidat</option> 
                <option value="'.self::$AllView.'">Voir tous le contenu</option>   
             
            </optgroup>
        ';

        return $default.$group1;
    }

    public static function hasPrivilege($privilege,$myPrivileges){
        $answer = in_array($privilege,explode(',',$myPrivileges));
        if(!$answer){
            if(is_ajax()){
                header('content-type: application/json');
                $return['statuts']=1;
                $return['mes']="Vous n'avez pas les permissions d'acceder à cette ressource";
                echo json_encode($return);
                exit();
            }else{
                App::unauthorize();
            }
        }
    }

    public static function canView($privilege,$myPrivileges){
        return in_array($privilege,explode(',',$myPrivileges));
    }

    public static function getPrivilege($privilege,$myPrivileges){
        return in_array($privilege,explode(',',$myPrivileges));
    }

    public static function isPrivilege($privilege){
        return in_array($privilege,self::getLesPrivileges());
    }

    public static function showPrivilege($myPrivileges){
        $tab = self::getIndexTabOfPrivileges();
        $explodes = explode(',',$myPrivileges);
        $tags = "<p>";
        $i=0;
        foreach ($explodes as $explode) {
            $tags .= $i==0?$tab[$explode]:", $tab[$explode]";
            $i++;
        }
        return $tags.'</p>';
    }

}