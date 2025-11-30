<?php

class UserCrud {
    // PDO connection
    private $conn;
    // Table name
    private $table = 'users';

    // User properties
    public $id;            // user ID (primary key)
    public $name;          // user's full name
    public $email;         // user's email address
    public $password;      // user's password hash
    public $avatar;        // filename for uploaded avatar

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        // SQL with named placeholders—including avatar
        $sql = "INSERT INTO {$this->table}
                  (name, email, password_hash, avatar)
                VALUES
                  (:name, :email, :pwd, :avatar)";
        $stmt = $this->conn->prepare($sql);

        // bind object properties to SQL parameters
        $stmt->bindParam(':name',   $this->name);
        $stmt->bindParam(':email',  $this->email);
        $stmt->bindParam(':pwd',    $this->password);
        // ← here we bind whatever filename was uploaded into $this->avatar
        $stmt->bindParam(':avatar', $this->avatar);

        // execute and return result
        return $stmt->execute();
    }

    public function readAll() {
        try {
            $sql  = "SELECT id, name, email, avatar, created_at
                     FROM {$this->table}
                     ORDER BY id DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function readOne() {
        $sql  = "SELECT id, name, email, avatar, created_at
                 FROM {$this->table}
                 WHERE id = :id
                 LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        // build base SQL
        $sql = "UPDATE {$this->table}
                SET name = :name,
                    email = :email";

        // if password is set, include it
        if (!empty($this->password)) {
            $sql .= ", password_hash = :pwd";
        }
        // if avatar is set (i.e. user uploaded a new file), include it
        if (!empty($this->avatar)) {
            $sql .= ", avatar = :avatar";
        }
        $sql .= " WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        // bind required params
        $stmt->bindParam(':name',  $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':id',    $this->id);

        // bind optional params
        if (!empty($this->password)) {
            $stmt->bindParam(':pwd', $this->password);
        }
        if (!empty($this->avatar)) {
            // ← binding the new avatar filename into the UPDATE
            $stmt->bindParam(':avatar', $this->avatar);
        }

        // execute and return
        return $stmt->execute();
    }

    public function delete() {
        $sql  = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
}

?>
