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
include '../models/Classes.php';
include '../models/Teacher.php';

$classes = new Classes();
$teacher = new Teacher();

$classData = null;
$teachersList = $teacher->getAllTeachers();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $classId = $_POST['classId'];
    $classYear = $_POST['classYear'];
    $className = $_POST['className'];
    $classCapacity = $_POST['classCapacity'];
    $pupilAmount = $_POST['pupilAmount'];
    $teacherId = $_POST['teacherId'];

    try {
        $classes->updateClass($classId, $classYear, $className, $classCapacity, $pupilAmount, $teacherId);
        header("Location: manageClasses.php");
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
} else {
    if (isset($_GET['id'])) {
        $classId = $_GET['id'];
        $classData = $classes->getClassById($classId);
    }
}

if (!$classData) {
    header("Location: manageClasses.php");
    exit;
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
    <title>Edit Class</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/manageEntity.css">
    <link rel="stylesheet" href="../assets/css/editpages.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>
    
    <div class="container">
    <button onclick="location.href='<?php echo htmlspecialchars($dashboardUrl); ?>'">Go to Dashboard</button>
        <h2>Edit Class</h2>
        
        <?php if (isset($error)) { ?>
            <div class="error-block">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php } ?>
        <form action="editClass.php" method="POST">
            <input type="hidden" name="classId" value="<?php echo htmlspecialchars($classData['ClassID']); ?>">
            <div class="form-group">
                <label for="classYear">Year:</label>
                <input type="text" id="classYear" name="classYear" value="<?php echo htmlspecialchars($classData['ClassYear']); ?>" required>
            </div>
            <div class="form-group">
                <label for="className">Class Name:</label>
                <input type="text" id="className" name="className" value="<?php echo htmlspecialchars($classData['ClassName']); ?>" required>
            </div>
            <div class="form-group">
                <label for="classCapacity">Capacity:</label>
                <input type="number" id="classCapacity" name="classCapacity" value="<?php echo htmlspecialchars($classData['ClassCapacity']); ?>" required>
            </div>
            <div class="form-group">
                <label for="pupilAmount">Pupil Amount:</label>
                <input type="number" id="pupilAmount" name="pupilAmount" value="<?php echo htmlspecialchars($classData['PupilAmount']); ?>" required>
            </div>
            <div class="form-group">
                <label for="teacherId">Teacher:</label>
                <select id="teacherId" name="teacherId" required>
                    <?php foreach ($teachersList as $teacher) { ?>
                        <option value="<?php echo htmlspecialchars($teacher['TeacherID']); ?>" <?php echo ($teacher['TeacherID'] == $classData['ClassTeacherID']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($teacher['TeacherName']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit">Update Class</button>
        </form>
    </div>
    <?php include 'partials/footer.php'; ?>
</body>
</html>
