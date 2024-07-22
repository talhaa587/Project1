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

// Initialize database connection and model
$parent = new Parents();

// Handle form submissions for adding and deleting parents
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['deleteParent'])) {
        $parentId = $_POST['deleteParent'];
        $parent->deleteParent($parentId);
    } elseif (isset($_POST['addParent'])) {
        $parentName = $_POST['parentName'];
        $parentAddress = $_POST['parentAddress'];
        $parentEmail = $_POST['parentEmail'];
        $parentPhoneNumber = $_POST['parentPhoneNumber'];
        $parent->addParent($parentName, $parentAddress, $parentEmail, $parentPhoneNumber);
    }
}

// Fetch all parents
$allParents = $parent->getAllParents();
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
    <title>Manage Parents</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/manageEntity.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div class="container">
    <button onclick="location.href='<?php echo htmlspecialchars($dashboardUrl); ?>'">Go to Dashboard</button>
        <h2>Manage Parents</h2>

        <!-- Add Parent Form -->
        <h3>Add Parent</h3>
        <form action="manageParents.php" method="POST">
            <div class="form-group">
                <label for="parentName">Name:</label>
                <input type="text" id="parentName" name="parentName" required>
            </div>
            <div class="form-group">
                <label for="parentAddress">Address:</label>
                <input type="text" id="parentAddress" name="parentAddress" required>
            </div>
            <div class="form-group">
                <label for="parentEmail">Email:</label>
                <input type="email" id="parentEmail" name="parentEmail" required>
            </div>
            <div class="form-group">
                <label for="parentPhoneNumber">Phone Number:</label>
                <input type="text" id="parentPhoneNumber" name="parentPhoneNumber" required pattern="\d+">
            </div>
            <button type="submit" name="addParent">Add Parent</button>
        </form>

        <!-- List All Parents -->
        <h3>All Parents</h3>
        <?php if (!empty($allParents)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allParents as $parent) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($parent['ParentName']); ?></td>
                            <td><?php echo htmlspecialchars($parent['ParentAddress']); ?></td>
                            <td><?php echo htmlspecialchars($parent['ParentEmail']); ?></td>
                            <td><?php echo htmlspecialchars($parent['ParentPhoneNumber']); ?></td>
                            <td>
                                <form action="manageParents.php" method="POST" style="display:inline;">
                                    <button type="submit" name="deleteParent" value="<?php echo htmlspecialchars($parent['ParentID']); ?>">Delete</button>
                                </form>
                                <a href="editParent.php?id=<?php echo htmlspecialchars($parent['ParentID']); ?>">Edit</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No parents available.</p>
        <?php } ?>
    </div>
    <?php include 'partials/footer.php'; ?>
</body>
</html>
