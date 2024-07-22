<?php
// /deleteStaff.php
include 'config/database.php';
include 'models/User.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin') {
    $staff_id = $_POST['staff_id'];

    $user = new User();
    $user->deleteStaff($staff_id);

    header("Location: views/adminDashboard.php");
}
?>
