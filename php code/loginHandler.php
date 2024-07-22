<?php
// /loginHandler.php
include 'config/database.php';
include 'models/User.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $user = new User();
    $loginResult = $user->login($username, $password, $role);

    if ($loginResult['status'] == 'success') {
        $_SESSION['user_id'] = $loginResult['user_id'];
        $_SESSION['user_role'] = $loginResult['user_role'];
        header("Location: " . ($loginResult['user_role'] == 'admin' ? 'views/adminDashboard.php' : 'views/staffDashboard.php'));
    } else {
        include 'views/login.php';
        echo "<script>document.getElementById('error-block').innerHTML = '<div class=\"error-block\">" . $loginResult['message'] . "</div>';</script>";
        echo "<script>document.getElementById('error-block').style.display=(\"block\");</script>";
    }
}
?>
    