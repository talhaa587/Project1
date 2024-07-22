<!-- /views/editParent.php -->
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
include '../models/Parent.php';

$parent = new Parents();

if (isset($_GET['id'])) {
    $parentId = $_GET['id'];
    $parentData = $parent->getParentById($parentId);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $parentId = $_POST['parentId'];
    $name = $_POST['parentName'];
    $address = $_POST['parentAddress'];
    $email = $_POST['parentEmail'];
    $phoneNumber = $_POST['parentPhoneNumber'];

    $result = $parent->updateParent($parentId, $name, $address, $email, $phoneNumber);
    if ($result['status'] == 'success') {
        header("Location: manageParents.php");
        exit;
    } else {
        $error = $result['message'];
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
    <title>Edit Parent</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/manageEntity.css">
    <link rel="stylesheet" href="../assets/css/editpages.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div class="container">
    <button onclick="location.href='<?php echo htmlspecialchars($dashboardUrl); ?>'">Go to Dashboard</button>

        <h2>Edit Parent</h2>
        <?php if (isset($error)) { echo "<div class='error-block'>$error</div>"; } ?>
        <form action="editParent.php" method="POST">
            <input type="hidden" name="parentId" value="<?php echo htmlspecialchars($parentData['ParentID']); ?>">
            <div class="form-group">
                <label for="parentName">Name:</label>
                <input type="text" id="parentName" name="parentName" value="<?php echo htmlspecialchars($parentData['ParentName']); ?>" required>
            </div>
            <div class="form-group">
                <label for="parentAddress">Address:</label>
                <input type="text" id="parentAddress" name="parentAddress" value="<?php echo htmlspecialchars($parentData['ParentAddress']); ?>" required>
            </div>
            <div class="form-group">
                <label for="parentEmail">Email:</label>
                <input type="email" id="parentEmail" name="parentEmail" value="<?php echo htmlspecialchars($parentData['ParentEmail']); ?>" required>
            </div>
            <div class="form-group">
                <label for="parentPhoneNumber">Phone Number:</label>
                <input type="text" id="parentPhoneNumber" name="parentPhoneNumber" value="<?php echo htmlspecialchars($parentData['ParentPhoneNumber']); ?>" required>
            </div>
            <button type="submit">Update Parent</button>
        </form>
    </div>
    <?php include 'partials/footer.php'; ?>
</body>
</html>
