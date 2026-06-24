<?php

require_once 'Database.php';

class Cart
{
    private $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->get_connection();
    }

    public function getCartItemsByUser(int $userId)
    {
        try
        {
            $sql = "SELECT c.id, c.quantity, c.ProductID, p.Title, p.Price, (p.Price * c.quantity) AS total
                    FROM cart c
                    JOIN product p ON c.ProductID = p.ProductID
                    WHERE c.CustomerID = :customer_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':customer_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    public function getCartItemsByRowID(int $rowId)
    {
        try
        {
            $sql = "SELECT * FROM cart WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $rowId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    public function removeItem(int $id)
    {
        try
        {
            $sql = "DELETE FROM cart WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    public function updateCart(int $id, int $quantity)
    {
        try
        {
            $sql = "UPDATE cart SET quantity = :quantity WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id',       $id,       PDO::PARAM_INT);
            $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    public function addToCart(int $productId)
    {
        try
        {
            // check if item already in cart
            $sql = "SELECT id FROM cart WHERE CustomerID = :customer_id AND ProductID = :product_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':customer_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':product_id',  $productId,          PDO::PARAM_INT);
            $stmt->execute();
            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($item) {
                // already in cart — increase quantity by 1
                $sql = "UPDATE cart SET quantity = quantity + 1 WHERE id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':id', $item['id'], PDO::PARAM_INT);
                $stmt->execute();
                return true;
            } else {
                // not in cart — insert new row
                $sql = "INSERT INTO cart (CustomerID, ProductID, quantity) VALUES (:customer_id, :product_id, 1)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':customer_id', $_SESSION['user_id'], PDO::PARAM_INT);
                $stmt->bindValue(':product_id',  $productId,          PDO::PARAM_INT);
                $stmt->execute();
                return true;
            }
        }
        catch(Exception $e)
        {
            return false;
        }
    }
}