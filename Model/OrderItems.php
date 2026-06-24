<?php

require_once 'Database.php';

class OrderItems
{
    private $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->get_connection();
    }

    public function addOrderItem(int $order_id, int $product_id, int $quantity, $price)
    {
        try {
            $sql  = "INSERT INTO orderitem (OrderID, ProductID, Quantity, UnitPrice) 
                     VALUES (:order_id, :product_id, :quantity, :price)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':order_id',   $order_id,   PDO::PARAM_INT);
            $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->bindValue(':quantity',   $quantity,   PDO::PARAM_INT);
            $stmt->bindValue(':price',      $price);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }

    public function getOrderItemsByOrderId(int $order_id)
    {
        try {
            $sql  = "SELECT oi.ProductID, oi.Quantity as quantity, oi.UnitPrice AS Price, p.Title,
                            (oi.UnitPrice * oi.Quantity) AS total
                     FROM orderitem oi
                     JOIN product p ON oi.ProductID = p.ProductID
                     WHERE oi.OrderID = :order_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }
}
?>