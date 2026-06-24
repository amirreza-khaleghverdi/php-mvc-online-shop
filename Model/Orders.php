<?php

require_once 'Database.php';

class Orders
{
    private $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->get_connection();
    }

    public function makeOrder(int $customer_id, $total, string $status)
    {
        try {
            $sql  = "INSERT INTO orders (CustomerId, TotalAmount, OrderStatus) VALUES (:customer_id, :total, :status)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':customer_id', $customer_id, PDO::PARAM_INT);
            $stmt->bindValue(':total',       $total);
            $stmt->bindValue(':status',      $status);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }

    public function confirmOrder(int $order_id)
    {
        try {
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['error'] = "You must be logged in.";
                return false;
            }

            $this->conn->beginTransaction();

            // get order items
            $sql  = "SELECT ProductID, Quantity FROM orderitem WHERE OrderID = :order_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->execute();
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($items)) {
                $this->conn->rollBack();
                $_SESSION['error'] = "No items found for this order.";
                return false;
            }

            foreach ($items as $item) {
                $product_id = $item['ProductID'];
                $quantity   = $item['Quantity'];

                // check stock from inventory table
                $sql  = "SELECT AvailableStock FROM inventory 
                         WHERE ProductID = :product_id FOR UPDATE";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
                $stmt->execute();
                $inventory = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$inventory || $inventory['AvailableStock'] < $quantity) {
                    $this->conn->rollBack();
                    $_SESSION['error'] = "Not enough stock for product ID: $product_id";
                    return false;
                }

                // reduce AvailableStock in inventory
                $sql  = "UPDATE inventory SET AvailableStock = AvailableStock - :quantity 
                         WHERE ProductID = :product_id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':quantity',   $quantity,   PDO::PARAM_INT);
                $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
                $stmt->execute();
            }

            // mark order as completed
            $sql  = "UPDATE orders SET OrderStatus = 'completed' WHERE OrderId = :order_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->execute();

            // clear cart
            $sql  = "DELETE FROM cart WHERE CustomerID = :customer_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':customer_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            $_SESSION['error'] = "Transaction failed: " . $e->getMessage();
            return false;
        }
    }

    public function cancelOrder(int $order_id)
    {
        try {
            $sql  = "UPDATE orders SET OrderStatus = 'cancelled' WHERE OrderId = :order_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getLatestOrdersByUserId(int $user_id)
    {
        try {
            $sql  = "SELECT OrderId as id, TotalAmount as total, OrderStatus as status, OrderDate as created_at
                     FROM orders
                     WHERE CustomerId = :id
                     ORDER BY OrderDate DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getOrderStatsByUserId(int $user_id)
    {
        try {
            $sql  = "SELECT 
                        SUM(OrderStatus = 'pending')   AS pending,
                        SUM(OrderStatus = 'completed') AS completed,
                        SUM(OrderStatus = 'cancelled') AS cancelled,
                        COUNT(*)                       AS total_orders
                     FROM orders
                     WHERE CustomerId = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getOrderById(int $order_id)
    {
        try {
            $sql  = "SELECT OrderId as id, TotalAmount as total, OrderStatus as status, OrderDate as created_at 
                     FROM orders WHERE OrderId = :order_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getAllOrders()
    {
        try {
            $sql  = "SELECT OrderId as id, TotalAmount as total, OrderStatus as status, OrderDate as created_at, CustomerId
                     FROM orders";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function updatePassword(string $table, string $idCol, int $id, string $hashed)
    {
        try {
            $sql  = "UPDATE $table SET password = :password WHERE $idCol = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':password', $hashed);
            $stmt->bindValue(':id',       $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>