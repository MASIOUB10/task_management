<?php
require_once '../models/User.php';
require_once '../config/database.php';

class UserController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->user->username = $_POST['username'];
            $this->user->email = $_POST['email'];
            $this->user->password = $_POST['password'];

            if ($this->user->register()) {
                header("Location: ../views/login_user.php");
                exit();
            } else {
                echo "Registration failed.";
            }
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->user->email = $_POST['email'];
            $this->user->password = $_POST['password'];

            if ($this->user->login()) {
                session_start();
                $_SESSION['user_id'] = $this->user->user_id;
                $_SESSION['email'] = $this->user->email;
                header("Location: ../views/user_home.php");
                exit();
            } else {
                echo "Login failed.";
            }
        }
    }
}
?>
