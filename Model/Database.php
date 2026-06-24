<?php

class Database
{ 
    private $host="localhost";
    private $username="root";
    private $password = ""; 
    private $db_name = "project_db";

    public $conn;

    public function get_connection()
    {
        $this->conn=null;

        try {

            $this->conn=new PDO(
                "mysql:host=". $this->host . ";dbname=" . $this->db_name,
                $this->username,$this->password
            );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $e) {
            echo "connection failed:" . $e->getMessage();
        }
        
        return $this->conn;
    }



}

?>