<?php
namespace gui;

include_once "View.php";
use gui\View as View;

class ViewLogin extends View
{
    public function __construct($layout)
    {
        parent::__construct($layout);

        $this->title = 'Exemple annonces Basic PHP: Connexion';

        $this->content = '
            <form method="post" action="/index.php/annonces">
                <label for="login"> Votre identifiant </label> :
                <input type="text" name="login" id="login" placeholder="defaut" maxlength="12" required />
                <br />
                <label for="password"> Votre mot de passe </label> :
                <input type="password" name="password" id="password" minlength="12" required />
        
                <input type="submit" value="Envoyer">
            </form>
            
            <a href="/index.php/create">Création d\'un nouveau compte</a>
            ';
    }
}
