<!-- /views/editTeacher.php -->
 
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include '../config/database.php';
include '../models/User.php';
include '../utils/validate.php';

$staff = new User();

if (isset($_GET['id'])) {
    $staffId = $_GET['id'];
    $staffData = $staff->getStaffByID($staffId);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staffId = $_POST['staffId'];
    $name = $_POST['staffUsername'];
    $email = $_POST['staffEmail'];
    $approved_status = $_POST['approvedStatus'];


    if (empty($errors)) {
        $result = $staff->updateStaff($staffId, $name, $email,$approved_status);
        if ($result['status'] == 'success') {
        header("Location: manageStaff.php");
        exit;
    }else {
        $error = $result['message'];
    }
    }
    if (!empty($errors)) {
        include 'views/editStaff.php';
        echo "<script>document.getElementById('error-block').innerHTML = '<div class=\"error-block\">" . implode("<br>", $errors) . "</div>';</script>";
    }
}
?>

<?php
// Determine the correct dashboard URL based on the user role
$dashboardUrl = '';
if (isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role'] === 'admin') {
        $dashboardUrl = 'adminDashboard.php';
    } elseif ($_SESSION['user_role'] === 'staff') {
        $dashboardUrl = 'staffDashboard.php'; // Replace with the actual staff dashboard file name
    } else {
        // Default or handle unexpected roles
        $dashboardUrl = 'login.php'; // Redirect to login or an error page
    }
} else {
    // Handle the case where session is not set
    $dashboardUrl = 'login.php'; // Redirect to login or an error page
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff</title>
    <link rel="stylesheet" href="../assets/css/style.css">    
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/manageEntity.css">
    <link rel="stylesheet" href="../assets/css/editpages.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div class="container">
    <button onclick="location.href='<?php echo htmlspecialchars($dashboardUrl); ?>'">Go to Dashboard</button>

        <h2>Edit Staff</h2>
        <?php if (isset($error)) { echo "<div class='error-block'>$error</div>"; } ?>
        <form action="editStaff.php" method="POST">
            <input type="hidden" name="staffId" value="<?php echo htmlspecialchars($staffData['StaffID']); ?>">
            <div class="form-group">
                <label for="staffUsername">Name:</label>
                <input type="text" id="staffUsername" name="staffUsername" value="<?php echo htmlspecialchars($staffData['StaffUsername']); ?>" required>
            </div>
            <div class="form-group">
                <label for="staffEmail">Email:</label>
                <input type="text" id="staffEmail" name="staffEmail" value="<?php echo htmlspecialchars($staffData['StaffEmail']); ?>" required>
            </div>
            <div class="form-group">
                <label for="approvedStatus">Approved Status:</label>
                <select id="approvedStatus" name="approvedStatus" value="<?php echo htmlspecialchars($staffData['ApprovedStatus']); ?>" required >
                    <option value="0">Pending</option>
                    <option value="1">Approved</option>
                </select>
            </div>
            <button type="submit">Update Staff</button>
        </form>
        <div id="error-block"></div>
    </div>
    <?php include 'partials/footer.php'; ?>
</body>
</html>
