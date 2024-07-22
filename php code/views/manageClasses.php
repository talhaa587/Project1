<!-- /views/manageClasses.php -->
<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include '../config/database.php';
include '../models/Classes.php';
include '../models/Teacher.php';

$classes = new Classes();
$teacher = new Teacher();

$allTeachers = $teacher->getAllTeachers();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['deleteClass'])) {

        $deleteResult = $classes->deleteClass($_POST['deleteClass']);
    } elseif (isset($_POST['addClass'])) {
        $year = $_POST['classYear'];
        $name = $_POST['className'];
        $capacity = $_POST['classCapacity'];
        $pupilAmount = $_POST['pupilAmount'];
        $teacherId = $_POST['teacherId'];
        $classes->addClass($year, $name, $capacity, $pupilAmount, $teacherId);
    }
}

$allClasses = $classes->getAllClasses();
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
    <title>Manage Classes</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/manageEntity.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div class="container">
    <button onclick="location.href='<?php echo htmlspecialchars($dashboardUrl); ?>'">Go to Dashboard</button>
        <h2>Manage Classes</h2>

        <!-- Add Class Form -->
        <h3>Add Class</h3>
        <form action="manageClasses.php" method="POST">
            <div class="form-group">
                <label for="classYear">Year:</label>
                <input type="text" id="classYear" name="classYear" placeholder = "Reception Year, Year One, Year Two,... Year Six" required>
            </div>
            <div class="form-group">
                <label for="className">Class Name:</label>
                <input type="text" id="className" name="className" required>
            </div>
            <div class="form-group">
                <label for="classCapacity">Capacity:</label>
                <input type="number" id="classCapacity" name="classCapacity" required>
            </div>
            <div class="form-group">
                <label for="pupilAmount">Pupil Amount:</label>
                <input type="number" id="pupilAmount" name="pupilAmount" required>
            </div>
            <div class="form-group">
                <label for="teacherId">Teacher:</label>
                <select id="teacherId" name="teacherId" required>
                    <option value="">Select Teacher</option>
                    <?php foreach ($allTeachers as $teach) { ?>
                        <option value="<?php echo htmlspecialchars($teach['TeacherID']); ?>">
                            <?php echo htmlspecialchars($teach['TeacherName']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" name="addClass">Add Class</button>
        </form>

        <!-- List All Classes -->
        <h3>All Classes</h3>
        <?php if (!empty($allClasses)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Class Name</th>
                        <th>Capacity</th>
                        <th>Pupil Amount</th>
                        <th>Class Teacher</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allClasses as $class) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($class['ClassYear']); ?></td>
                            <td><?php echo htmlspecialchars($class['ClassName']); ?></td>
                            <td><?php echo htmlspecialchars($class['ClassCapacity']); ?></td>
                            <td><?php echo htmlspecialchars($class['PupilAmount']); ?></td>
                            <td><?php echo htmlspecialchars($class['ClassTeacherID']); ?></td>
                            <td>
                                <form action="manageClasses.php" method="POST" style="display:inline;">
                                    <button type="submit" name="deleteClass" value="<?php echo htmlspecialchars($class['ClassID']); ?>">Delete</button>
                                </form>
                                <a href="editClass.php?id=<?php echo htmlspecialchars($class['ClassID']); ?>">Edit</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No classes available.</p>
        <?php } ?>
    </div>
    <?php include 'partials/footer.php'; ?>
</body>
</html>
