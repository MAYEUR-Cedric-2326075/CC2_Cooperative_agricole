<?php

namespace gui;

include_once __DIR__ . '/View.php'; // Ajout important
use gui\View as View;
use control\Presenter;
use service\OrdersChecking;
use domain\Order;

class ViewOrders extends View
{
    public function __construct($layout, string $email, string $role, Presenter $presenter, OrdersChecking $ordersChecking)
    {
        parent::__construct($layout, $email);
        $this->title = "📦 Mes commandes";

        if ($role === 'customer') {
            $orders = $ordersChecking->getOrdersForCustomer($email);
        } elseif ($role === 'manager') {
            $orders = $ordersChecking->getOrdersForManager($email);
        } else {
            $this->content = "<p>❌ Rôle utilisateur inconnu.</p>";
            return;
        }

        if (empty($orders)) {
            $this->content = "<p>📭 Aucune commande trouvée.</p>";
            return;
        }

        $this->content = '<table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>ID Commande</th>
                    <th>Panier</th>
                    <th>Client</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($orders as $order) {
            $this->content .= '<tr>';
            $this->content .= '<td>' . htmlspecialchars($order->getId()) . '</td>';
            $this->content .= '<td>' . htmlspecialchars($order->getBasketId()) . '</td>';
            $this->content .= '<td>' . htmlspecialchars($order->getCustomerEmail()) . '</td>';
            $this->content .= '<td>' . htmlspecialchars($order->getOrderedAt()) . '</td>';
            $this->content .= '</tr>';
        }

        $this->content .= '</tbody></table>';
    }
}
