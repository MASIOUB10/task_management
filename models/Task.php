<?php
class Task {
    private $conn;
    private $table_name = 'tasks';

    public $task_id;
    public $title;
    public $description;
    public $created_by_admin;
    public $due_date;
    public $priority;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (title, description, created_by_admin, due_date, priority, status)
                  VALUES (:title, :description, :created_by_admin, :due_date, :priority, :status)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':created_by_admin', $this->created_by_admin);
        $stmt->bindParam(':due_date', $this->due_date);
        $stmt->bindParam(':priority', $this->priority);
        $stmt->bindParam(':status', $this->status);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET
                  title = :title,
                  description = :description,
                  due_date = :due_date,
                  priority = :priority,
                  status = :status
                  WHERE task_id = :task_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':due_date', $this->due_date);
        $stmt->bindParam(':priority', $this->priority);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':task_id', $this->task_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE task_id = :task_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':task_id', $this->task_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getTaskById($task_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE task_id = :task_id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':task_id', $task_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->task_id = $row['task_id'];
            $this->title = $row['title'];
            $this->description = $row['description'];
            $this->created_by_admin = $row['created_by_admin'];
            $this->due_date = $row['due_date'];
            $this->priority = $row['priority'];
            $this->status = $row['status'];
            return true;
        }
        return false;
    }
}
?>
