<!-- /views/editTeacher.php -->
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user_id']) ) {
    header("Location: login.php");
    exit;
}

include '../config/database.php';
include '../models/Teacher.php';

$teacher = new Teacher();

if (isset($_GET['id'])) {
    $teacherId = $_GET['id'];
    $teacherData = $teacher->getTeacherById($teacherId);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacherId = $_POST['teacherId'];
    $name = $_POST['teacherName'];
    $address = $_POST['teacherAddress'];
    $phoneNumber = $_POST['teacherPhoneNumber'];
    $annualSalary = $_POST['teacherAnnualSalary'];
    $backgroundCheck = $_POST['teacherBackgroundCheck'];

    $result = $teacher->updateTeacher($teacherId, $name, $address, $phoneNumber, $annualSalary, $backgroundCheck);
    if ($result['status'] == 'success') {
        header("Location: manageTeachers.php");
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
    <title>Edit Teacher</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/manageEntity.css">
    <link rel="stylesheet" href="../assets/css/editpages.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div class="container">
    <button onclick="location.href='<?php echo htmlspecialchars($dashboardUrl); ?>'">Go to Dashboard</button>
        <h2>Edit Teacher</h2>
        <?php if (isset($error)) { echo "<div class='error-block'>$error</div>"; } ?>
        <form action="editTeacher.php" method="POST">
            <input type="hidden" name="teacherId" value="<?php echo htmlspecialchars($teacherData['TeacherID']); ?>">
            <div class="form-group">
                <label for="teacherName">Name:</label>
                <input type="text" id="teacherName" name="teacherName" value="<?php echo htmlspecialchars($teacherData['TeacherName']); ?>" required>
            </div>
            <div class="form-group">
                <label for="teacherAddress">Address:</label>
                <input type="text" id="teacherAddress" name="teacherAddress" value="<?php echo htmlspecialchars($teacherData['TeacherAddress']); ?>" required>
            </div>
            <div class="form-group">
                <label for="teacherPhoneNumber">Phone Number:</label>
                <input type="text" id="teacherPhoneNumber" name="teacherPhoneNumber" value="<?php echo htmlspecialchars($teacherData['TeacherPhoneNumber']); ?>" required>
            </div>
            <div class="form-group">
                <label for="teacherAnnualSalary">Annual Salary:</label>
                <input type="number" id="teacherAnnualSalary" name="teacherAnnualSalary" value="<?php echo htmlspecialchars($teacherData['TeacherAnnualSalary']); ?>" required>
            </div>
            <div class="form-group">
                <label for="teacherBackgroundCheck">Background Check:</label>
                <input type="text" id="teacherBackgroundCheck" name="teacherBackgroundCheck" value="<?php echo htmlspecialchars($teacherData['TeacherBackgroundCheck']); ?>" required>
            </div>
            <button type="submit">Update Teacher</button>
        </form>
    </div>
    <?php include 'partials/footer.php'; ?>
</body>
</html>
