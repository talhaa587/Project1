<?php
// /registerHandler.php
include 'config/database.php';
include 'models/User.php';
include 'utils/validate.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = validateRegistration($username, $email, $password, $confirm_password);
    $errors = checkUsernameExists($username);

    if (empty($errors)) {
        $user = new User();
        $registrationResult = $user->register($username, $email, $password);

        if ($registrationResult['status'] == 'success') {
            header("Location: views/login.php");
        } else {
            $errors[] = $registrationResult['message'];
        }
    }

    if (!empty($errors)) {
        include 'views/register.php';
        echo "<script>document.getElementById('error-block').innerHTML = '<div class=\"error-block\">" . implode("<br>", $errors) . " </div>';</script>";
        echo "<script>document.getElementById('error-block').style.display=(\"block\");</script>";

    }
}
?>
