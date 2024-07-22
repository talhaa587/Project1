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

$database = new Database();
$db = $database->getConnection();
$user = new User();

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['deleteStaff'])) {
        $user->deleteStaff($_POST['deleteStaff']);
    }  elseif (isset($_POST['addStaff'])) {
        $user->addStaff($_POST['staffUsername'], $_POST['staffEmail'], $_POST['staffPassword'], $_POST['approvedStatus']);
    }
}

$allStaff = $user->getAllStaff();
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
    <title>Manage Staff</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/manageEntity.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div class="container">
    <button onclick="location.href='<?php echo htmlspecialchars($dashboardUrl); ?>'">Go to Dashboard</button>
        <h2>Manage Staff</h2>

        <!-- Add Staff Form -->
        <h3>Add Staff</h3>
        <form action="manageStaff.php" method="POST">
            <div class="form-group">
                <label for="staffUsername">Username:</label>
                <input type="text" id="staffUsername" name="staffUsername" required>
            </div>
            <div class="form-group">
                <label for="staffEmail">Email:</label>
                <input type="email" id="staffEmail" name="staffEmail" required>
            </div>
            <div class="form-group">
                <label for="staffPassword">Password:</label>
                <input type="password" id="staffPassword" name="staffPassword" required>
            </div>
            <div class="form-group">
                <label for="approvedStatus">Approved Status:</label>
                <select id="approvedStatus" name="approvedStatus" required>
                    <option value="0">Pending</option>
                    <option value="1">Approved</option>
                </select>
            </div>
            <button type="submit" name="addStaff">Add Staff</button>
        </form>

        <!-- List All Staff -->
        <h3>All Staff</h3>
        <?php if (!empty($allStaff)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Approved Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allStaff as $staffMember) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($staffMember['StaffUsername']); ?></td>
                            <td><?php echo htmlspecialchars($staffMember['StaffEmail']); ?></td>
                            <td><?php echo htmlspecialchars($staffMember['ApprovedStatus'] ? 'Approved' : 'Pending'); ?></td>
                            <td>
                                <form action="manageStaff.php" method="POST" style="display:inline;">
                                    <button type="submit" name="deleteStaff" value="<?php echo htmlspecialchars($staffMember['StaffID']); ?>">Delete</button>
                                </form>
                                <a href="editStaff.php?id=<?php echo htmlspecialchars($staffMember['StaffID']); ?>">Edit</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
            <p>No Staff available.</p>
        <?php } ?>
        </div>
    </div>
    <?php include 'partials/footer.php'; ?>
</body>
</html>