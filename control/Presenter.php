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
            $content .= '</tr>';
        }

        $content .= '</tbody></table>';
        return $content;
    }
}
