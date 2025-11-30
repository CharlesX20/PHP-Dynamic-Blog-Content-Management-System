<?php

class Database {
    private $host     = "";
    private $db_name  = "";
    private $username = "";
    private $password = "";

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
