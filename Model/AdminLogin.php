<?php

require_once 'Database.php';

class AdminLogin
{
    private $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->get_connection();
    }

    public function checkLogin(string $name, string $passkey)
    {
        try {
            $sql  = "SELECT * FROM admin WHERE Name = :name AND Passkey = :passkey LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':name',    $name);
            $stmt->bindValue(':passkey', $passkey);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($admin) {
                return $admin;
            }
            return false;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }

    public function getAllCustomers()
    {
        try {
            $sql  = "SELECT * FROM customers";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function editCustomer(int $id, string $firstname, string $lastname, string $email, string $phonenumber)
    {
        try {
            $sql  = "UPDATE customers SET FirstName = :firstname, LastName = :lastname, 
                     Email = :email, PhoneNumber = :phonenumber WHERE CustomerID = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':firstname',   $firstname);
            $stmt->bindValue(':lastname',    $lastname);
            $stmt->bindValue(':email',       $email);
            $stmt->bindValue(':phonenumber', $phonenumber);
            $stmt->bindValue(':id',          $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }

    public function deleteCustomer(int $id)
    {
        try {
            // check if customer has orders
            $sql  = "SELECT COUNT(*) FROM orders WHERE CustomerId = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $orderCount = $stmt->fetchColumn();

            if ($orderCount > 0) {
                $_SESSION['error'] = "Cannot delete customer — they have existing orders.";
                return false;
            }

            // delete their cart items first
            $sql  = "DELETE FROM cart WHERE CustomerID = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $sql  = "DELETE FROM customers WHERE CustomerID = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }

    public function getAllCouriers()
    {
        try {
            $sql  = "SELECT * FROM courier";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function deleteCourier(int $id)
    {
        try {
            $sql  = "SELECT COUNT(*) FROM shipment WHERE CourierID = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $shipmentCount = $stmt->fetchColumn();
    
            if ($shipmentCount > 0) {
                $_SESSION['error'] = "Cannot delete courier — they have assigned shipments.";
                return false;
            }
    
            $sql  = "DELETE FROM courier WHERE CourierID = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }

    public function editCourier(int $id, string $firstname, string $lastname, string $contactnumber, string $nationalcode, string $vehicletype)
    {
        try {
            $sql  = "UPDATE courier SET FirstName = :firstname, LastName = :lastname, 
                     ContactNumber = :contactnumber, NationalCode = :nationalcode,
                     VehicleType = :vehicletype WHERE CourierID = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':firstname',     $firstname);
            $stmt->bindValue(':lastname',      $lastname);
            $stmt->bindValue(':contactnumber', $contactnumber);
            $stmt->bindValue(':nationalcode',  $nationalcode);
            $stmt->bindValue(':vehicletype',   $vehicletype);
            $stmt->bindValue(':id',            $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }

}
?>