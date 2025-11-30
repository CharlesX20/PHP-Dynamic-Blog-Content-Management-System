<?php

class Database {
    private $host     = "172.31.22.43";
    private $db_name  = "Chukwuebuka200613207";
    private $username = "Chukwuebuka200613207";
    private $password = "IBL5pcC3HP";

    // This will hold the actual PDO connection
    public $conn;

    public function getConnection() {
        // Initialize connection to null
        $this->conn = null;

        try{
            // attempt to create a new PDO(PHP Data Object)
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            //if an error occurs display a connection error message
            echo "Connection Error: " . $e->getMessage();
        }
        // or then return the connection object
        return $this->conn;
    }
}

?>