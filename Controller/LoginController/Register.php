<?php

require_once 'Model/Login.php';

class RegisterController
{
    private $usermodel;

    public function __construct(Login $usermodel)
    {
        $this->usermodel = $usermodel;
    }

    public function register()
    {
        $firstname       = $_POST['Firstname']       ?? '';
        $lastname        = $_POST['Lastname']        ?? '';
        $email           = $_POST['email']           ?? '';
        $nationalcode    = $_POST['Nationalcode']    ?? '';
        $phonenumber     = $_POST['Phonenumber']     ?? '';
        $role            = $_POST['role']            ?? '';
        $password        = $_POST['password']        ?? '';
        $confirmpassword = $_POST['confirmPassword'] ?? '';

        if (empty($firstname) || empty($lastname) || empty($email) || empty($nationalcode) || empty($phonenumber) || empty($password) || empty($role)) {
            $_SESSION['error'] = "All fields are required";
            header("Location: index.php?action=register");
            exit;
        }

        if ($password !== $confirmpassword) {
            $_SESSION['error'] = "Passwords do not match";
            header("Location: index.php?action=register");
            exit;
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = "Password must be at least 6 characters";
            header("Location: index.php?action=register");
            exit;
        }

        if (strlen($nationalcode) !== 10 || !ctype_digit($nationalcode)) {
            $_SESSION['error'] = "National ID must be exactly 10 digits";
            header("Location: index.php?action=register");
            exit;
        }

        if ($role === 'customer') {

            $result = $this->usermodel->registerCustomer($firstname, $lastname, $email, $nationalcode, $phonenumber, $password);

            if ($result) {
                $_SESSION['username'] = $result['FirstName'];
                $_SESSION['user_id']  = $result['CustomerID'];
                $_SESSION['role']     = 'customer';
            }

        } elseif ($role === 'courier') {

            $result = $this->usermodel->registerCourier($firstname, $lastname, $nationalcode, $phonenumber, $password);

            if ($result) {
                $_SESSION['username'] = $result['FirstName'];
                $_SESSION['user_id']  = $result['CourierID'];
                $_SESSION['role']     = 'courier';
            }

        } else {
            $_SESSION['error'] = "Invalid role selected";
            header("Location: index.php?action=register");
            exit;
        }

        if ($result) {
            if ($_SESSION['role'] === 'courier') {
                header("Location: index.php?action=courier_dashboard");
            } else {
                header("Location: index.php?action=homepage");
            }
            exit;
        } else {
            $_SESSION['error'] = "Something went wrong, try again later";
            header("Location: index.php?action=register");
            exit;
        }
    }

    public function showRegisterForm()
    {
        require 'View/Register.php';
    }
}
?>