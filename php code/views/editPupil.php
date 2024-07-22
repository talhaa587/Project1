<!-- /views/editPupil.php -->
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include '../config/database.php';
include '../models/Pupil.php';
include '../models/Parent.php';
include '../models/Classes.php';

$pupil = new Pupil();
$parent = new Parents();
$classes = new Classes();

$pupilData = null;
$parentsList = $parent->getAllParents();
$classesList = $classes->getAllClasses();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pupilId = $_POST['pupilId'];
    $pupilName = $_POST['pupilName'];
    $pupilAddress = $_POST['pupilAddress'];
    $pupilAge = $_POST['pupilAge'];
    $pupilHeight = $_POST['pupilHeight'];
    $pupilWeight = $_POST['pupilWeight'];
    $pupilClassId = $_POST['pupilClassId'];
    $pupilParent1Id = $_POST['pupilParent1Id'];
    $pupilParent2Id = $_POST['pupilParent2Id'];

    try {
        $pupil->updatePupil($pupilId, $pupilName, $pupilAddress, $pupilAge, $pupilHeight, $pupilWeight, $pupilClassId, $pupilParent1Id, $pupilParent2Id);
        header("Location: managePupils.php");
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
} else {
    if (isset($_GET['id'])) {
        $pupilId = $_GET['id'];
        $pupilData = $pupil->getPupilById($pupilId);
    }
}
if (!$pupilData) {
    header("Location: managePupils.php");
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
    <title>Edit Pupil</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/manageEntity.css">
    <link rel="stylesheet" href="../assets/css/editpages.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div class="container">
    <button onclick="location.href='<?php echo htmlspecialchars($dashboardUrl); ?>'">Go to Dashboard</button>
w
        <h2>Edit Pupil</h2>
        <?php if (isset($error)) { echo "<div class='error-block'>$error</div>"; } ?>
        <form action="editPupil.php" method="POST">
            <input type="hidden" name="pupilId" value="<?php echo htmlspecialchars($pupilData['PupilID']); ?>">
            <div class="form-group">
                <label for="pupilName">Name:</label>
                <input type="text" id="pupilName" name="pupilName" value="<?php echo htmlspecialchars($pupilData['PupilName']); ?>" required>
            </div>
            <div class="form-group">
                <label for="pupilAddress">Address:</label>
                <input type="text" id="pupilAddress" name="pupilAddress" value="<?php echo htmlspecialchars($pupilData['PupilAddress']); ?>" required>
            </div>
            <div class="form-group">
                <label for="pupilAge">Age:</label>
                <input type="text" id="pupilAge" name="pupilAge" value="<?php echo htmlspecialchars($pupilData['PupilAge']); ?>" required>
            </div>
            <div class="form-group">
                <label for="pupilHeight">Height:</label>
                <input type="text" id="pupilHeight" name="pupilHeight" value="<?php echo htmlspecialchars($pupilData['PupilHeight']); ?>" required>
            </div>
            <div class="form-group">
                <label for="pupilWeight">Weight:</label>
                <input type="text" id="pupilWeight" name="pupilWeight" value="<?php echo htmlspecialchars($pupilData['PupilWeight']); ?>" required>
            </div>
            <div class="form-group">
                <label for="pupilClassId">Class:</label>
                <select id="pupilClassId" name="pupilClassId" required>
                    <?php foreach ($classesList as $class) { ?>
                        <option value="<?php echo htmlspecialchars($class['ClassID']); ?>" <?php echo ($class['ClassID'] == $pupilData['PupilClassID']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($class['ClassName']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="pupilParent1Id">Parent 1:</label>
                <select id="pupilParent1Id" name="pupilParent1Id" required>
                    <option value="null">None</option>
                    <?php foreach ($parentsList as $parent) { ?>
                        <option value="<?php echo htmlspecialchars($parent['ParentID']); ?>" <?php echo ($parent['ParentID'] == $pupilData['PupilParent1ID']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($parent['ParentName']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="pupilParent2Id">Parent 2:</label>
                <select id="pupilParent2Id" name="pupilParent2Id" required>
                    <option value="null">None</option>
                    <?php foreach ($parentsList as $parent) { ?>
                        <option value="<?php echo htmlspecialchars($parent['ParentID']); ?>" <?php echo ($parent['ParentID'] == $pupilData['PupilParent2ID']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($parent['ParentName']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit">Update Pupil</button>
        </form>
    </div>  
    <?php include 'partials/footer.php'; ?>
</body>
</html>
