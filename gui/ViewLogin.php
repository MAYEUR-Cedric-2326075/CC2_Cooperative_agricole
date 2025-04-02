<?php
namespace gui;

include_once "View.php";
use gui\View as View;

class ViewLogin extends View
{
    public function __construct($layout)
    {
        parent::__construct($layout);

        $this->title = 'Connexion - Coopérative agricole';

        $this->content = '
            <h2>Connexion</h2>
            <form method="post" action="/index.php/login">
                <label for="email">Adresse e-mail :</label>
                <input type="email" name="email" id="email" required />
                <br />
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password" required />
                <br />
                <input type="submit" value="Se connecter">
            </form>
            <p><a href="/index.php/create">Créer un nouveau compte</a></p>
        ';
    }
}
