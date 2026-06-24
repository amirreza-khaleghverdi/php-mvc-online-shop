<?php

require_once 'Model/Orders.php';
require_once 'Model/Cart.php';
require_once 'Model/OrderItems.php';

class checkout
{
    private $orders;
    private $cart;
    private $order_items;

    public function __construct(Orders $orders, OrderItems $order_items, Cart $cart)
    {
        $this->orders      = $orders;
        $this->order_items = $order_items;
        $this->cart        = $cart;
    }

    public function Checkout()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $cart_items = $this->cart->getCartItemsByUser($_SESSION['user_id']);

        if (empty($cart_items)) {
            $_SESSION['error'] = "Your cart is empty.";
            header("Location: index.php?action=cart");
            exit;
        }

        // calculate total using correct column names
        $grand_total = 0;
        foreach ($cart_items as $item) {
            $grand_total += $item['total'];
        }

        // create pending order
        $order_id = $this->orders->makeOrder($_SESSION['user_id'], $grand_total, 'pending');

        if (!$order_id) {
            header("Location: index.php?action=cart");
            exit;
        }

        // add each cart item to order_items
        foreach ($cart_items as $item) {
            $this->order_items->addOrderItem(
                $order_id,
                $item['ProductID'],
                $item['quantity'],
                $item['Price']
            );
        }

        // get order and items for the view
        $order       = $this->orders->getOrderById($order_id);
        $order_items = $this->order_items->getOrderItemsByOrderId($order_id);
        
        $order_id = $order['id'];
        include 'View/Checkout.php';
    }

    public function confirmOrder($order_id)
    {
        if ($this->orders->confirmOrder((int)$order_id)) {
            $_SESSION['message'] = "✅ Thank you for your purchase!";
            header("Location: index.php?action=dashboard");
            exit;
        } else {
            $_SESSION['error'] = $_SESSION['error'] ?? "❌ Could not complete your order.";
            header("Location: index.php?action=show_checkout");
            exit;
        }
    }

    public function cancelOrder($order_id)
    {
        if ($this->orders->cancelOrder((int)$order_id)) {
            $_SESSION['message'] = "Order cancelled.";
            header("Location: index.php?action=homepage");
            exit;
        } else {
            $_SESSION['error'] = "Something went wrong.";
            header("Location: index.php?action=homepage");
            exit;
        }
    }
}
?>