<?php

require_once 'Model/Login.php';

class LoginController
{

    private $login;

    public function __construct(Login $login)
    {
        $this->login = $login;
    }

    public function showLoginForm()
    {
        require 'View/Login.php';
    }

    public function login()
    {
        $nationalcode = trim($_POST['Nationalcode'] ?? '');
        $password     = trim($_POST['password']     ?? '');
        $role         = trim($_POST['role']         ?? '');

        if (empty($nationalcode) || empty($password) || empty($role)) {
            $_SESSION['error'] = "All fields are required";
            header("Location: index.php?action=login");
            exit;
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = "Password must be at least 6 characters";
            header("Location: index.php?action=login");
            exit;
        }

        if (strlen($nationalcode) !== 10 || !ctype_digit($nationalcode)) {
            $_SESSION['error'] = "National ID must be exactly 10 digits";
            header("Location: index.php?action=login");
            exit;
        }

        if ($role === 'customer') {

            $user = $this->login->checkCustomerLogin($nationalcode, $password);

            if ($user) {
                $_SESSION['username'] = $user['FirstName'];
                $_SESSION['user_id']  = $user['CustomerID'];
                $_SESSION['role']     = 'customer';
            }

        } elseif ($role === 'courier') {

            $user = $this->login->checkCourierLogin($nationalcode, $password);

            if ($user) {
                $_SESSION['username'] = $user['FirstName'];
                $_SESSION['user_id']  = $user['CourierID'];
                $_SESSION['role']     = 'courier';
            }

        } elseif ($role === 'admin') {

            $user = $this->login->checkAdminLogin($nationalcode, $password);

            if ($user) {
                $_SESSION['username'] = $user['FirstName'];
                $_SESSION['user_id']  = $user['AdminID'];
                $_SESSION['role']     = 'admin';
            }

        } else {
            $_SESSION['error'] = "Invalid role selected";
            header("Location: index.php?action=login");
            exit;
        }

        if ($user) {
            if ($_SESSION['role'] === 'courier') {
                header("Location: index.php?action=courier_dashboard");
            } else {
                header("Location: index.php?action=homepage");
            }
            exit;
        } else {
            
            header("Location: index.php?action=login");
            exit;
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
}
?>