<!-- /views/manageTeachers.php -->
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include '../config/database.php';
include '../models/Teacher.php';

$teacher = new Teacher();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['deleteTeacher'])) {
        $teacher->deleteTeacher($_POST['deleteTeacher']);
    } elseif (isset($_POST['addTeacher'])) {
        $teacher->addTeacher($_POST['teacherName'], $_POST['teacherAddress'], $_POST['teacherPhoneNumber'], $_POST['teacherAnnualSalary'], $_POST['teacherBackgroundCheck']);
    }
}

$allTeachers = $teacher->getAllTeachers();
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
    <title>Manage Teachers</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/manageEntity.css">
    
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div class="container">
    <button onclick="location.href='<?php echo htmlspecialchars($dashboardUrl); ?>'">Go to Dashboard</button>
        <h2>Manage Teachers</h2>

        <!-- Add Teacher Form -->
        <h3>Add Teacher</h3>
        <form action="manageTeachers.php" method="POST">
            <div class="form-group">
                <label for="teacherName">Name:</label>
                <input type="text" id="teacherName" name="teacherName" required>
            </div>
            <div class="form-group">
                <label for="teacherAddress">Address:</label>
                <input type="text" id="teacherAddress" name="teacherAddress" required>
            </div>
            <div class="form-group">
                <label for="teacherPhoneNumber">Phone Number:</label>
                <input type="text" id="teacherPhoneNumber" name="teacherPhoneNumber" required>
            </div>
            <div class="form-group">
                <label for="teacherAnnualSalary">Annual Salary:</label>
                <input type="number" id="teacherAnnualSalary" name="teacherAnnualSalary" required>
            </div>
            <div class="form-group">
                <label for="teacherBackgroundCheck">Background Check:</label>
                <input type="text" id="teacherBackgroundCheck" name="teacherBackgroundCheck" required>
            </div>
            <button type="submit" name="addTeacher">Add Teacher</button>
        </form>

        <!-- List All Teachers -->
        <h3>All Teachers</h3>
        <?php if (!empty($allTeachers)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone Number</th>
                        <th>Annual Salary</th>
                        <th>Background Check</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allTeachers as $teacher) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($teacher['TeacherName']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['TeacherAddress']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['TeacherPhoneNumber']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['TeacherAnnualSalary']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['TeacherBackgroundCheck']); ?></td>
                            <td>
                                <form action="manageTeachers.php" method="POST" style="display:inline;">
                                    <button type="submit" name="deleteTeacher" value="<?php echo htmlspecialchars($teacher['TeacherID']); ?>">Delete</button>
                                </form>
                                <a href="editTeacher.php?id=<?php echo htmlspecialchars($teacher['TeacherID']); ?>">Edit</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No teachers available.</p>
        <?php } ?>
    </div>
    <?php include 'partials/footer.php'; ?>
</body>
</html>
