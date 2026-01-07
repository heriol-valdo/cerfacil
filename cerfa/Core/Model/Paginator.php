<?php


namespace Projet\Model;

use Projet\Model\App;


class Paginator {
    public $urlPrecedent = "";
    public $urlSuivant = "";
    public $disabledPrecedent = " class='disabled'";
    public $disabledSuivant = " class='disabled'";
    private $params = [];
    private $pageCourante;
    private $url;
    private $search;

    private $typesearch;
    private $nbrePages;
    private $translator;
    public function __construct($pageCourante, $nbrePages, $params,$url,$search,$typesearch) {
        $this->translator = new Translator();
        $this->nbrePages = $nbrePages < 1 ? 1 : $nbrePages;
        $this->pageCourante = $pageCourante <= $nbrePages ? $pageCourante : 1;
        $this->params = $params;
        $this->url = $url;
        $this->search = $search;
        $this->typesearch = $typesearch;
    }
    
    public function paginateTwo() {
        $ulBegin = '<ul class="pagination pull-right" style="list-style: none; padding: 0; margin: 0;">';
        $ulEnd = '</ul>';
        $content = "";
    
        // Previous Button
        if ($this->pageCourante > 1) {
            $queryParams = $_SERVER['QUERY_STRING'];
            $queryParams = preg_replace('/page=[^&]*/', 'page=' . ($this->pageCourante - 1), $queryParams);
            if (empty($queryParams)) {
                $queryParams = 'page=' . ($this->pageCourante - 1);
            }
            $content .= "<li style='display: inline; margin: 0 5px;'>" . $this->getPostForm($this->pageCourante - 1, false, "‹ Précédent", $queryParams) . "</li>";
        } else {
            $content .= "<li style='display: inline; margin: 0 5px;'>
                            <button class='btn btn-light' disabled style='border: 1px solid #ddd; padding: 5px 10px; border-radius: 5px;'>‹ Précédent</button>
                         </li>";
        }
    
        // Current Page Button
        $content .= "<li style='display: inline; margin: 0 5px;'>" . $this->getPostForm($this->pageCourante, true) . "</li>";
    
       // Next Button
        if ($this->pageCourante < $this->nbrePages) {
            // Générer les paramètres de la requête pour passer à la page suivante
            $queryParams = $_SERVER['QUERY_STRING'];
            $queryParams = preg_replace('/page=[^&]*/', 'page=' . ($this->pageCourante + 1), $queryParams);
            if (empty($queryParams)) {
                $queryParams = 'page=' . ($this->pageCourante + 1);
            }
            // Ajout de la page suivante au contenu
            $content .= "<li style='display: inline; margin: 0 5px;'>" . $this->getPostForm($this->pageCourante + 1, false, "Suivant ›", $queryParams) . "</li>";
        } else {
            // Si la page courante est la dernière page, désactiver le bouton "Suivant"
            $content .= "<li style='display: inline; margin: 0 5px;'>
                            <button class='btn btn-light' disabled style='border: 1px solid #ddd; padding: 5px 10px; border-radius: 5px;'>Suivant ›</button>
                        </li>";
        }

        // Fermer la balise <ul> et afficher le contenu
        echo $ulBegin . $content . $ulEnd;
    }
    
    // Modified getPostForm method to accept query parameters
    public function getPostForm($page, $isActive = false, $label = null, $queryParams = null) {
        $activeClass = $isActive ? "btn-primary" : "btn-light";
        $disabledAttr = $isActive ? "disabled" : "";
        $label = $label ?? $page; // If no label is defined, use the page number
        
      
    
        return "
            <form method='POST' style='display: inline;' action=".App::url($this->url).">
                <input type='hidden' name='page' value='{$page}'>
                   <input type='hidden' name='{$this->typesearch}' value='{$this->search}'>
                <button type='submit' class='btn {$activeClass}' {$disabledAttr} style='border: 1px solid #ddd; padding: 5px 10px; border-radius: 5px;'>
                    {$label}
                </button>
            </form>
        ";
    }
    
    
    
    
    
    public function getForm($page, $label, $disabled = false) {
        $disabledAttr = $disabled ? "disabled" : "";
       
    
        return "
            <form method='POST' style='display: inline;' action=".App::url($this->url).">
                <input type='hidden' name='page' value='{$page}'>
               <input type='hidden' name='{$this->typesearch}' value='{$this->search}'>
                <button type='submit' class='btn btn-light' {$disabledAttr} style='border: 1px solid #ddd; padding: 5px 10px; border-radius: 5px;'>
                    {$label}
                </button>
            </form>
        ";
    }
    
    

    public function paginateOne() {
        $prevDisabled = $this->pageCourante == 1;
        $nextDisabled = $this->pageCourante == $this->nbrePages;

        $prevForm = $this->getForm($this->pageCourante - 1, "<i class='uk-icon-angle-double-left' style='list-style: none; padding: 0;color:black;'></i> " . $this->translator->get('page.menu.precedent'), $prevDisabled);
        $nextForm = $this->getForm($this->pageCourante + 1, $this->translator->get('page.menu.suivant') . " <i class='uk-icon-angle-double-right'></i>", $nextDisabled);

        $info = 'Page ' . $this->pageCourante . ' sur ' . $this->nbrePages;
        echo "<p class='small-text pull-left'><i>{$info}</i></p>";
        echo "<ul class='pager pull-right mar-no'>";
        echo "<li" . ($prevDisabled ? " class='disabled'" : "") . ">{$prevForm}</li>";
        echo "<li" . ($nextDisabled ? " class='disabled'" : "") . ">{$nextForm}</li>";
        echo "</ul>";
    }

   
}




