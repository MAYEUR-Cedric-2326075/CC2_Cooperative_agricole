<?php

namespace control;

use service\BasketAccessInterface;

class Presenter
{
    protected BasketAccessInterface $basketAccess;

    public function __construct(BasketAccessInterface $basketAccess)
    {
        $this->basketAccess = $basketAccess;
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
            $content .= '<td>' . $basket->getId() . '</td>';
            $content .= '<td>' . $basket->getStatus() . '</td>';
            $content .= '<td>' . $basket->getCreatedAt() . '</td>';

            $items = '';
            foreach ($basket->getItems() as $item) {
                $items .= htmlspecialchars($item['productId']) . ' x' . intval($item['quantity']) . '<br>';
            }

            $content .= '<td>' . $items . '</td>';
            $content .= '<td>
                <a href="/index.php/editBasket?id=' . $basket->getId() . '">✏ Modifier</a> |
                <a href="/index.php/deleteBasket?id=' . $basket->getId() . '">🗑 Supprimer</a>
            </td>';
            $content .= '</tr>';
        }

        $content .= '</tbody></table>';

        $content .= '<p><a href="/index.php/createBasket">➕ Ajouter un nouveau panier</a></p>';

        return $content;
    }

    public function getAllBasketsHTML(): string
    {
        $baskets = $this->basketAccess->getAllBaskets();

        $content = '<h1>🧺 Gestion des Paniers</h1>';

        if (empty($baskets)) {
            $content .= '<p>Aucun panier disponible.</p>';
            return $content;
        }

        $content .= '<table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Statut</th>
                    <th>Créé le</th>
                    <th>Articles</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($baskets as $basket) {
            $content .= '<tr>';
            $content .= '<td>' . $basket->getId() . '</td>';
            $content .= '<td>' . $basket->getUserId() . '</td>';
            $content .= '<td>' . $basket->getStatus() . '</td>';
            $content .= '<td>' . $basket->getCreatedAt() . '</td>';

            $items = '';
            foreach ($basket->getItems() as $item) {
                $items .= htmlspecialchars($item['productId']) . ' x' . intval($item['quantity']) . '<br>';
            }

            $content .= '<td>' . $items . '</td>';
            $content .= '<td>
                <a href="/index.php/editBasket?id=' . $basket->getId() . '">✏ Modifier</a> |
                <a href="/index.php/deleteBasket?id=' . $basket->getId() . '">🗑 Supprimer</a>
            </td>';
            $content .= '</tr>';
        }

        $content .= '</tbody></table>';
        $content .= '<p><a href="/index.php/createBasket">➕ Ajouter un nouveau panier</a></p>';

        return $content;
    }
    public function getCreateBasketForm(): string
    {
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

        <label for="createdAt">Date de création :</label>
        <input type="datetime-local" id="createdAt" name="createdAt" required><br><br>

        <label for="items">Articles (format JSON) :</label><br>
        <textarea name="items" id="items" rows="5" cols="50" placeholder='[{"productId": "abc", "quantity": 2}]'></textarea><br><br>

        <input type="submit" value="Créer le panier">
    </form>
    <p><a href="/index.php/baskets">⬅ Retour à la liste des paniers</a></p>
    HTML;
    }

}
