<?php

require_once 'Model/Orders.php';
require_once 'Model/Login.php';

class Dashboard
{
    private $orders;

    public function __construct(Orders $orders, OrderItems $order_items)
    {
        $this->orders = $orders;
    }

    public function showDashboard()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $orders      = $this->orders->getLatestOrdersByUserId($_SESSION['user_id']);
        $order_stats = $this->orders->getOrderStatsByUserId($_SESSION['user_id']);
        $notifications = [];

        include 'View/Dashboard.php';
    }
}
?>