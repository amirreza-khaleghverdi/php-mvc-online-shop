<?php

require_once 'Database.php';

class Login
{
    private $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->get_connection();
    }
    
    public function checkCustomerLogin(string $nationalcode, string $password)
    {
        try {
            $sql  = "SELECT * FROM customers WHERE Nationalcode = :nationalcode LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':nationalcode', $nationalcode);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            return false;
        }
        catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }
    
    public function checkCourierLogin(string $nationalcode, string $password)
    {
        try {
            $sql  = "SELECT * FROM courier WHERE Nationalcode = :nationalcode LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':nationalcode', $nationalcode);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            return false;
        }
        catch (Exception $e) {
            return false;
        }
    }
    
    public function checkAdminLogin(string $nationalcode, string $password)
    {
        try {
            $sql  = "SELECT * FROM admin WHERE Nationalcode = :nationalcode LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':nationalcode', $nationalcode);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            return false;
        }
        catch (Exception $e) {
            return false;
        }
    }

    public function registerCustomer($firstname, $lastname, $email, $nationalcode, $phonenumber, $password)
    {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO customers (FirstName, LastName, Email, NationalCode, PhoneNumber, password) 
                    VALUES (:firstname, :lastname, :email, :nationalcode, :phonenumber, :password)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':firstname',    $firstname);
            $stmt->bindValue(':lastname',     $lastname);
            $stmt->bindValue(':email',        $email);
            $stmt->bindValue(':nationalcode', $nationalcode);
            $stmt->bindValue(':phonenumber',  $phonenumber);
            $stmt->bindValue(':password',     $hash);
            $stmt->execute();
    
            $id = $this->conn->lastInsertId();
    
            $sql  = "SELECT * FROM customers WHERE CustomerID = :id LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }
    
    public function registerCourier($firstname, $lastname, $phonenumber, $password)
    {
        try {
            $hash=password_hash($password,PASSWORD_DEFAULT);
            $sql = "INSERT INTO courier (Firstname, Lastname, ContactNumber, password) 
                    VALUES (:firstname, :lastname, :ContactNumber, :password)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':firstname',    $firstname);
            $stmt->bindValue(':lastname',     $lastname);
            $stmt->bindValue(':ContactNumber',  $phonenumber);
            $stmt->bindValue(':password',     $hash);
            $stmt->execute();
    
            $id = $this->conn->lastInsertId();
    
            $sql  = "SELECT * FROM courier WHERE CourierID = :id LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch (Exception $e)
        {
            return false;
        }
    }

    public function change_password($user_id, $password)
    {
        try{

            $hash=password_hash($password,PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password =:password WHERE id =:user_id ";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':password' ,$hash);
            $stmt->execute();
            return $stmt;

        }
        catch(Exception $e)
        {
            return false;
        }
    }

    public function get_all_users() 
    {
        $sql = "SELECT * FROM users";
        $stmt=$this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}




?>