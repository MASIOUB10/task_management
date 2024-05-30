<?php
require_once '../models/Admin.php';
require_once '../config/database.php';

class AdminController {
    private $db;
    private $admin;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->admin = new Admin($this->db);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->admin->email = $_POST['email'];
            $this->admin->password = $_POST['password'];

            if ($this->admin->login()) {
                session_start();
                $_SESSION['admin_id'] = $this->admin->admin_id;
                $_SESSION['email'] = $this->admin->email;
                header("Location: ../views/admin_home.php");
                exit();
            } else {
                echo "Login failed.";
            }
        }
    }
}
?>
