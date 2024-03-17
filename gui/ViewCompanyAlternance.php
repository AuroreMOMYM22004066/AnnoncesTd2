<?php
namespace gui;

include_once "View.php";

class ViewCompanyAlternance extends View
{
    public function __construct($layout, $login, $presenter)
    {
        parent::__construct($layout, $login);

        $this->title= 'Exemple Annonces Basic PHP: Entreprise';

        $this->content = $presenter->getCurrentPostHTML();

        $this->content .= '<a href="/annonces/index.php/annoncesAlternance">retour</a>';
    }
}
