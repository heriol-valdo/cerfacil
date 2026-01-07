<?php


namespace Projet\Model;


class FileHelper{

    public static function url($path){
        $root = (strpos($path,"http") !== false)?"":ROOT_SITE;
        return $root.$path;
    }

    public static function deleteImage($path){
        $path = (strpos($path,"http") !== false)?str_replace(ROOT_URL,'',$path):$path;
        //$src = PATH_FILE.'\\'.str_replace('/','\\',str_replace(ROOT_URL,'',$path));
        $src = PATH_FILE.'/'.$path;
        if(file_exists($src)){
            try{
                unlink($src);
            }catch (\Exception $e){}
        }
    }

    public static function moveImage($tmp_name,$folder,$extension="",$name="",$isAbsolute=false){
        $extension = (empty($extension))?pathinfo($name, PATHINFO_EXTENSION):$extension;
        $name = md5(uniqid(rand(), true)).'.'.$extension;
        $path = PATH_FILE;
        $path.= '/public/'.$folder.'/'.str_replace('\\','/',$name);
        $root = $isAbsolute?ROOT_SITE.$folder.'/'.$name:$folder.'/'.$name;
        return move_uploaded_file($tmp_name, $path)?$root:false;
    }

    public static function moveImageArticle($ref,$tmp_name,$folder,$extension="",$name="",$isAbsolute=false){
        $extension = (empty($extension))?pathinfo($name, PATHINFO_EXTENSION):$extension;
        $name = StringHelper::buildSlug($ref).'_'.md5(uniqid(rand(), true)).'.'.$extension;
        $path = PATH_FILE;
        $path.= '/public/'.$folder.'/'.str_replace('\\','/',$name);
        $root = $isAbsolute?ROOT_SITE.$folder.'/'.$name:$folder.'/'.$name;
        return move_uploaded_file($tmp_name, $path)?$root:false;
    }

}