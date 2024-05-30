<?php
require_once '../models/Task.php';
require_once '../config/database.php';

class TaskController {
    private $db;
    private $task;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->task = new Task($this->db);
    }

    public function createTask() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->task->title = $_POST['title'];
            $this->task->description = $_POST['description'];
            $this->task->created_by_admin = $_SESSION['admin_id'];
            $this->task->due_date = $_POST['due_date'];
            $this->task->priority = $_POST['priority'];
            $this->task->status = $_POST['status'];

            if ($this->task->create()) {
                header("Location: ../views/admin_home.php");
                exit();
            } else {
                echo "Task creation failed.";
            }
        }
    }

    public function updateTask() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->task->task_id = $_POST['task_id'];
            $this->task->title = $_POST['title'];
            $this->task->description = $_POST['description'];
            $this->task->due_date = $_POST['due_date'];
            $this->task->priority = $_POST['priority'];
            $this->task->status = $_POST['status'];

            if ($this->task->update()) {
                header("Location: ../views/admin_home.php");
                exit();
            } else {
                echo "Task update failed.";
            }
        }
    }

    public function deleteTask() {
        if (isset($_POST['task_id'])) {
            $this->task->task_id = $_POST['task_id'];

            if ($this->task->delete()) {
                header("Location: ../views/admin_home.php");
                exit();
            } else {
                echo "Task deletion failed.";
            }
        }
    }

    public function getTasks() {
        return $this->task->read();
    }

    public function getTask($task_id) {
        return $this->task->getTaskById($task_id);
    }
}
?>
