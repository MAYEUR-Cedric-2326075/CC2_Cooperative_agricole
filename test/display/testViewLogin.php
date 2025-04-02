<?php


include_once '../../gui/ViewLogin.php';
include_once '../../gui/Layout.php';

use gui\{ViewLogin,Layout};

$layout = new Layout("./../../gui/layoutUnLogged.html" );
$vueLogin = new ViewLogin( $layout );

$vueLogin->display();