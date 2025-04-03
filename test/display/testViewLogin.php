<?php

include_once '../../gui/ViewCreateBasket.php';
include_once '../../gui/Layout.php';
include_once '../../control/Presenter.php';
include_once '../../service/interfaces/BasketAccessInterface.php';

// 🎭 Classe simulée pour les besoins du test
class FakeBasketAccess implements \service\BasketAccessInterface {
    public function getAllBaskets(): array { return []; }
    public function getBasketById(string $id): ?object { return null; }
    public function createBasket(object|array $basket): bool { return true; }
    public function deleteBasketById(string $id): bool { return true; }
    public function getBasketsByUser(string $email): array { return []; }
}

use gui\ViewCreateBasket;
use gui\Layout;
use control\Presenter;

$layout = new Layout("../../gui/layoutLoggedManager.html");
$presenter = new Presenter(new FakeBasketAccess());

$view = new ViewCreateBasket($layout, "manager@example.com", $presenter);

// 🖨️ Affiche le HTML dans la console (utile si layout contient %content%)
ob_start();
$view->display();
$html = ob_get_clean();

echo $html;
