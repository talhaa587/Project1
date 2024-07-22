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
include '../models/Pupil.php';
include '../models/Parent.php';
include '../models/Classes.php';

$pupil = new Pupil();
$parent = new Parents();
$classes = new Classes();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['deletePupil'])) {
        $pupil->deletePupil($_POST['deletePupil']);
    } elseif (isset($_POST['addPupil'])) {
        // Retrieve the necessary data to add a pupil
        $pupilName = $_POST['pupilName'];
        $pupilAddress = $_POST['pupilAddress'];
        $pupilAge = $_POST['pupilAge'];
        $pupilHeight = $_POST['pupilHeight'];
        $pupilWeight = $_POST['pupilWeight'];
        $pupilClassId = $_POST['pupilClassId'];
        $pupilParent1Id = $_POST['pupilParent1Id'];
        $pupilParent2Id = $_POST['pupilParent2Id'];

        // Add pupil
        $pupil->registerPupil($pupilName, $pupilAddress, $pupilAge, $pupilHeight, $pupilWeight, $pupilClassId, $pupilParent1Id, $pupilParent2Id);
    }
}

$allPupils = $pupil->getAllPupils();
$parentsList = $parent->getAllParents();
$classesList = $classes->getAllClasses();

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
    <title>Manage Pupils</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/manageEntity.css">
    
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div class="container">
    <button onclick="location.href='<?php echo htmlspecialchars($dashboardUrl); ?>'">Go to Dashboard</button>
        <div class = "addPupilContainer">
        <h2>Manage Pupils</h2>

        <!-- Add Pupil Form -->
        <h3>Add Pupil</h3>
        <form action="managePupils.php" method="POST">
            <div class="form-group">
                <label for="pupilName">Name:</label>
                <input type="text" id="pupilName" name="pupilName" required>
            </div>
            <div class="form-group">
                <label for="pupilAddress">Address:</label>
                <input type="text" id="pupilAddress" name="pupilAddress" required>
            </div>
            <div class="form-group">
                <label for="pupilAge">Age:</label>
                <input type="number" id="pupilAge" name="pupilAge" required>
            </div>
            <div class="form-group">
                <label for="pupilHeight">Pupil Height:</label>
                <input type="number" id="pupilHeight" name="pupilHeight" required>
            </div>
            <div class="form-group">
                <label for="pupilWeight">Pupil Weight:</label>
                <input type="number" id="pupilWeight" name="pupilWeight" required>
            </div>
            <div class="form-group">
                <label for="pupilClassID">Pupil Class:</label>
                <select id="pupilClassID" name="pupilClassID" required>
                <option value="">Select Class</option>
                <?php foreach ($classesList as $cl) { ?>
                    <option value="<?php echo htmlspecialchars($cl['ClassID']); ?>" >
                        <?php echo htmlspecialchars($cl['ClassName']); ?>
                    </option>
                <?php } ?>
            </select>
            </div>
            <div class="form-group">
            <label for="pupilParent1ID">Parent/Guardian 1:</label>
            <select id="pupilParent1ID" name="pupilParent1ID" required>
                <option value="">Select Parent</option>
                <option value="null">None</option>
                <?php foreach ($parentsList as $parent) { ?>
                    <option value="<?php echo htmlspecialchars($parent['ParentID']); ?>">
                        <?php echo htmlspecialchars($parent['ParentName']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="pupilParent2ID">Parent/Guardian 2:</label>
            <select id="pupilParent2ID" name="pupilParent2ID">
                <option value="">Select Parent</option>
                <option value="null">None</option>
                <?php foreach ($parentsList as $parent) { ?>
                    <option value="<?php echo htmlspecialchars($parent['ParentID']); ?>">
                        <?php echo htmlspecialchars($parent['ParentName']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
            <button type="submit">Add Pupil</button>
        </form>
        </div>
        <!-- List All Pupils -->
         <div class= "managePupilContainer" >
        <h3>All Pupils</h3>
        <?php if (!empty($allPupils)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Age</th>
                        <th>Height</th>
                        <th>Weight</th>
                        <th>Class</th>
                        <th>Parent 1</th>
                        <th>Parent 2</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php foreach ($allPupils as $pupil) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pupil['PupilName']); ?></td>
                            <td><?php echo htmlspecialchars($pupil['PupilAddress']); ?></td>
                            <td><?php echo htmlspecialchars($pupil['PupilAge']); ?></td>
                            <td><?php echo htmlspecialchars($pupil['PupilHeight']); ?></td>
                            <td><?php echo htmlspecialchars($pupil['PupilWeight']); ?></td>
                            <td><?php echo htmlspecialchars($pupil['PupilClassID']); ?></td>
                            <td><?php echo htmlspecialchars($pupil['PupilParent1ID']); ?></td>
                            <td><?php echo htmlspecialchars($pupil['PupilParent2ID']); ?></td>
                            <td>
                                <form action="managePupils.php" method="POST" style="display:inline;">
                                    <button type="submit" name="deletePupil" value="<?php echo htmlspecialchars($pupil['PupilID']); ?>">Delete</button>
                                </form>
                                <a href="editPupil.php?id=<?php echo htmlspecialchars($pupil['PupilID']); ?>">Edit</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No pupils available.</p>
        <?php } ?>
        </div>
    </div>
    <?php include 'partials/footer.php'; ?>
</body>
</html>