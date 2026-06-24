<?php

require_once 'Model/Cart.php';

class Cartpage
{
    private $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function showCartpage()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "You need to log in to view your cart.";
            header("Location: index.php?action=login");
            exit;
        }

        if ($_SESSION['role'] !== 'customer') {
            $_SESSION['error'] = "Only customers can view the cart.";
            header("Location: index.php?action=homepage");
            exit;
        }

        $cart_items = $this->cart->getCartItemsByUser($_SESSION['user_id']);
        include 'View/cart.php';
    }

    public function removeItem($id)
    {
        $this->cart->removeItem($id);
        header("Location: index.php?action=cart");
        exit;
    }

    public function updateCart($quantities)
    {
        foreach ($quantities as $id => $quantity) {
            if ($quantity < 1) continue;
            $this->cart->updateCart((int)$id, (int)$quantity);
        }
        header("Location: index.php?action=cart");
        exit;
    }

    public function addToCart($productId)
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "You have to login first";
            header("Location: index.php?action=login");
            exit;
        }

        if ($_SESSION['role'] !== 'customer') {
            $_SESSION['error'] = "Only customers can add to cart.";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $result = $this->cart->addToCart((int)$productId);

        if ($result) {
            $_SESSION['message'] = "Added to cart!";
        } else {
            $_SESSION['error'] = "Could not add to cart, try again.";
        }

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
?>