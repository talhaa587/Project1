<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// /registerPupil.php
include 'config/database.php';
include 'models/Pupil.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'staff') {
    $pupilParent1ID = $_POST['pupilParent1ID'];
    $pupilParent2ID = $_POST['pupilParent2ID'];

if ($pupilParent1ID == $pupilParent2ID) {
    include 'views/staffDashboard.php';
    echo "<script>document.getElementById('error-block').innerHTML = '<div class=\"error-block\">Parent/Guardian 1 and Parent/Guardian 2 cannot be the same.</div>';</script>";
    exit;
}

$pupilParent1ID = ($pupilParent1ID === 'null') ? null : $pupilParent1ID;
$pupilParent2ID = ($pupilParent2ID === 'null') ? null : $pupilParent2ID;

    $pupilName = $_POST['pupilName'];
    $pupilAddress = $_POST['pupilAddress'];
    $pupilAge = $_POST['pupilAge'];
    $pupilHeight = $_POST['pupilHeight'];
    $pupilWeight = $_POST['pupilWeight'];
    $pupilClassID = $_POST['pupilClassID'];
    $pupilParent1ID = $_POST['pupilParent1ID'];
    $pupilParent2ID = $_POST['pupilParent2ID'];

    $pupil = new Pupil();
    $result = $pupil->registerPupil($pupilName, $pupilAddress, $pupilAge, $pupilHeight, $pupilWeight, $pupilClassID, $pupilParent1ID, $pupilParent2ID);

    if ($result['status'] == 'success') {
        header("Location: views/staffDashboard.php");
    } else {
        include 'views/staffDashboard.php';
        echo "<script>document.getElementById('error-block').innerHTML = '<div class=\"error-block\">" . $result['message'] . "</div>';</script>";
    }
}
?>
