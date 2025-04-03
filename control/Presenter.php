<?php

namespace control;

use service\BasketAccessInterface;
use service\ProductAccessInterface;
use service\OrdersChecking;

class Presenter
{
    protected BasketAccessInterface $basketAccess;
    protected ProductAccessInterface $productAccess;
    protected OrdersChecking $ordersChecking;

    public function __construct(BasketAccessInterface $basketAccess, ProductAccessInterface $productAccess, OrdersChecking $ordersChecking)
    {
        $this->basketAccess = $basketAccess;
        $this->productAccess = $productAccess;
        $this->ordersChecking = $ordersChecking;
    }

    public function getCreateBasketForm(): string
    {
        $products = $this->productAccess->getAllProducts();

        $productInputs = '';
        foreach ($products as $product) {
            $productInputs .= '<label>' . htmlspecialchars($product->getName()) . ' (ID: ' . $product->getId() . ')</label><br>';
            $productInputs .= '<input type="number" name="quantities[' . $product->getId() . ']" min="0" value="0"><br><br>';
        }

        return <<<HTML
            <h2>➕ Ajouter un panier</h2>
            <form method="post" action="/index.php/createBasket">
                <label for="id">ID du panier :</label>
                <input type="text" id="id" name="id" required><br><br>

                <label for="status">Statut :</label>
                <select name="status" id="status">
                    <option value="open">Ouvert</option>
                    <option value="submitted">Soumis</option>
                    <option value="closed">Fermé</option>
                </select><br><br>

                <fieldset>
                    <legend>Articles :</legend>
                    $productInputs
                </fieldset>

                <input type="submit" value="Créer le panier">
            </form>
            <p><a href="/index.php/baskets">⬅ Retour à la liste des paniers</a></p>
        HTML;
    }

    public function getBasketsForManagerHTML(string $email): string
    {
        if (!method_exists($this->basketAccess, 'getBasketsByUser')) {
            return "<p>⚠️ Impossible de récupérer les paniers pour l'utilisateur : méthode manquante.</p>";
        }

        $baskets = $this->basketAccess->getBasketsByUser($email);

        $content = '<h1>🧺 Vos Paniers</h1>';

        if (empty($baskets)) {
            $content .= '<p>Aucun panier trouvé pour cet utilisateur.</p>';
            return $content;
        }

        $content .= '<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Statut</th>
            <th>Créé le</th>
            <th>Articles</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>';

        foreach ($baskets as $basket) {
            $content .= '<tr>';
            $content .= '<td>' . htmlspecialchars($basket->getId()) . '</td>';
            $content .= '<td>' . htmlspecialchars($basket->getStatus()) . '</td>';
            $content .= '<td>' . htmlspecialchars($basket->getCreatedAt()) . '</td>';

            $items = '';
            foreach ($basket->getItems() as $item) {
                $items .= htmlspecialchars($item['productId']) . ' x' . intval($item['quantity']) . '<br>';
            }

            $content .= '<td>' . $items . '</td>';
            $content .= '<td>
            <a href="/index.php/subscribers?basketId=' . urlencode($basket->getId()) . '">👥 Voir abonnés</a><br>
            <form method="post" action="/index.php/subscribe" style="display:inline;">
                <input type="hidden" name="basketId" value="' . htmlspecialchars($basket->getId()) . '">
                <button type="submit">📩 Abonner</button>
            </form><br>
            <a href="/index.php/editBasket?id=' . urlencode($basket->getId()) . '">✏ Modifier</a> |
            <a href="/index.php/deleteBasket?id=' . urlencode($basket->getId()) . '">🗑 Supprimer</a>
        </td>';
            $content .= '</tr>';
        }

        $content .= '</tbody></table>';
        $content .= '<p><a href="/index.php/createBasket">➕ Ajouter un nouveau panier</a></p>';

        return $content;
    }


    public function getAllBasketsHTMLForCustomer(): string
    {
        $baskets = $this->basketAccess->getAllBaskets();

        $content = '<h1>🛒 Paniers disponibles</h1>';

        if (empty($baskets)) {
            $content .= '<p>Aucun panier disponible pour le moment.</p>';
            return $content;
        }

        $content .= '<table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Créateur</th>
                <th>Statut</th>
                <th>Créé le</th>
                <th>Articles</th>
                <th>Commander</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($baskets as $basket) {
            $content .= '<tr>';
            $content .= '<td>' . htmlspecialchars($basket->getId()) . '</td>';
            $content .= '<td>' . htmlspecialchars($basket->getUserId()) . '</td>';
            $content .= '<td>' . htmlspecialchars($basket->getStatus()) . '</td>';
            $content .= '<td>' . htmlspecialchars($basket->getCreatedAt()) . '</td>';

            $items = '';
            foreach ($basket->getItems() as $item) {
                $items .= htmlspecialchars($item['productId']) . ' x' . intval($item['quantity']) . '<br>';
            }
            $content .= '<td>' . $items . '</td>';

            // 🛒 Bouton Commander
            $content .= '<td>
            <form method="post" action="/index.php/order" style="display:inline;">
                <input type="hidden" name="basketId" value="' . htmlspecialchars($basket->getId()) . '">
                <button type="submit">🛒 Commander</button>
            </form>
        </td>';

            $content .= '</tr>';
        }

        $content .= '</tbody></table>';

        return $content;
    }



    public function getSubscribersHTML(string $basketId): string
    {
        $basket = $this->basketAccess->getBasketById($basketId);

        if (!$basket) {
            return '<p>❌ Panier non trouvé.</p>';
        }

        $subscribers = $basket->getSubscribers();

        $content = '<h2>👥 Abonnés du panier</h2>';

        if (empty($subscribers)) {
            return $content . '<p>Aucun abonné.</p>';
        }

        $content .= '<ul>';
        foreach ($subscribers as $email) {
            $content .= '<li>' . htmlspecialchars($email) . ' 
            <form method="post" action="/index.php/unsubscribe" style="display:inline;">
                <input type="hidden" name="basketId" value="' . htmlspecialchars($basketId) . '">
                <input type="hidden" name="email" value="' . htmlspecialchars($email) . '">
                <button type="submit">🚫 Désabonner</button>
            </form>
        </li>';
        }
        $content .= '</ul>';

        return $content;
    }


    public function getSubscriptionsHTML(array $baskets): string
    {
        $content = '<h2>📩 Vos abonnements</h2>';

        if (empty($baskets)) {
            return $content . '<p>Aucun abonnement.</p>';
        }

        $content .= '<ul>';
        foreach ($baskets as $basket) {
            $content .= '<li>' . htmlspecialchars($basket->getId()) . ' (Statut : ' . htmlspecialchars($basket->getStatus()) . ')</li>';
        }
        $content .= '</ul>';

        return $content;
    }
    public function getOrdersHTMLForCustomer(string $email): string
    {
        $orders = $this->ordersChecking->getOrdersForCustomer($email);
        $content = '<h2>📦 Mes commandes</h2>';

        if (empty($orders)) {
            return $content . '<p>Aucune commande passée.</p>';
        }

        $content .= '<table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Panier</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($orders as $order) {
            $content .= '<tr>';
            $content .= '<td>' . htmlspecialchars($order->getId()) . '</td>';
            $content .= '<td>' . htmlspecialchars($order->getBasketId()) . '</td>';
            $content .= '<td>' . htmlspecialchars($order->getOrderedAt()) . '</td>';
            $content .= '</tr>';
        }

        $content .= '</tbody></table>';

        return $content;
    }


    public function getOrdersForBasketHTML(string $basketId): string
    {
        $orders = $this->ordersChecking->getOrdersForBasket($basketId);
        $content = '<h2>📦 Commandes du panier ' . htmlspecialchars($basketId) . '</h2>';

        if (empty($orders)) {
            return $content . '<p>Aucune commande pour ce panier.</p>';
        }

        $content .= '<table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID Commande</th>
                <th>Client</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($orders as $order) {
            $content .= '<tr>';
            $content .= '<td>' . htmlspecialchars($order->getId()) . '</td>';
            $content .= '<td>' . htmlspecialchars($order->getCustomerEmail()) . '</td>';
            $content .= '<td>' . htmlspecialchars($order->getOrderedAt()) . '</td>';
            $content .= '</tr>';
        }

        $content .= '</tbody></table>';
        return $content;
    }

}
