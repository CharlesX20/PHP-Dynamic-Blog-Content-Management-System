<?php

class ContentCrud {
    // PDO connection
    private $conn;
    // Table name
    private $table = 'posts';

    // Post properties
    public $id;         // post ID (primary key)
    public $userId;     // author ID (foreign key to users.id)
    public $title;      // post title
    public $body;       // main content of the post
    public $image;      // filename for uploaded post image

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        // INSERT includes the image column
        $sql = "INSERT INTO {$this->table}
                  (user_id, title, body, image)
                VALUES
                  (:user_id, :title, :body, :image)";
        $stmt = $this->conn->prepare($sql);

        // bind object properties to named parameters
        $stmt->bindParam(':user_id', $this->userId);
        $stmt->bindParam(':title',   $this->title);
        $stmt->bindParam(':body',    $this->body);
        // ← here we bind the uploaded image filename stored in $this->image
        $stmt->bindParam(':image',   $this->image);

        return $stmt->execute();
    }

    public function readAll() {
        try {
            $sql  = "SELECT id, user_id, title, body, image, created_at
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
        $sql  = "SELECT id, user_id, title, body, image, created_at
                 FROM {$this->table}
                 WHERE id = :id
                 LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        // Base UPDATE for title and body
        $sql = "UPDATE {$this->table}
                SET title = :title,
                    body  = :body";

        // include image only if a new filename was assigned
        if (!empty($this->image)) {
            $sql .= ", image = :image";
        }
        $sql .= " WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        // bind required parameters
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body',  $this->body);
        $stmt->bindParam(':id',    $this->id);

        // bind the image filename if provided
        if (!empty($this->image)) {
            // ← binds the new uploaded image filename into the UPDATE
            $stmt->bindParam(':image', $this->image);
        }

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
