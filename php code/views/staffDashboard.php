<!-- /views/staffDashboard.php -->
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'staff') {
    header("Location: login.php");
    exit;
}

include '../config/database.php';
include '../models/Pupil.php';
include '../models/Classes.php';

$pupil = new Pupil();
$class = new Classes();
$allPupils = $pupil->getAllPupils();
$allClasses = $class->getAllClasses();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <script>
        function togglePupilRegistrationForm() {
            var form = document.getElementById("registerPupilFormContainer");
            if (form.style.display === "none" || form.style.display === "") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        }
    </script>
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div class="container">
        <h2>Staff Dashboard</h2>
        <!-- Logout Button -->
        <form action="logout.php" method="POST" style="display: inline;">
            <button type="submit" class="logout-button">Logout</button>
        </form>
        <button onclick="togglePupilRegistrationForm()">Process Pupil Registrations</button>
        <div id="registerPupilFormContainer" style="display: none;">
        <h3>Register New Pupil</h3>
        <form action="../registerPupil.php" method="POST" id="registerPupilForm">
            <div class="form-group">
                <label for="pupilName">Pupil Name:</label>
                <input type="text" id="pupilName" name="pupilName" required>
            </div>
            <div class="form-group">
                <label for="pupilAddress">Pupil Address:</label>
                <input type="text" id="pupilAddress" name="pupilAddress" required>
            </div>
            <div class="form-group">
                <label for="pupilAge">Pupil Age:</label>
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
                <?php foreach ($allClasses as $class) { ?>
                    <option value="<?php echo htmlspecialchars($class['ClassID']); ?>" >
                        <?php echo htmlspecialchars($class['ClassName']); ?>
                    </option>
                <?php } ?>
            </select>
            </div>
            <div class="form-group">
            <label for="pupilParent1ID">Parent/Guardian 1:</label>
            <select id="pupilParent1ID" name="pupilParent1ID" required>
                <option value="">Select Parent</option>
                <option value="null">None</option>
                <?php foreach ($allParents as $parent) { ?>
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
                <?php foreach ($allParents as $parent) { ?>
                    <option value="<?php echo htmlspecialchars($parent['ParentID']); ?>">
                        <?php echo htmlspecialchars($parent['ParentName']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
            <button type="submit">Register Pupil</button>
        </form>
        <div id="error-block"></div>
        </div>
        <h3>Manage Entities</h3>
        <div class="entity-section">
            <!-- Teacher Section -->
            <div class="entity-card">
                <h4>Teachers</h4>
                <?php if (!empty($allTeachers)) { ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Phone Number</th>
                                <th>Annual Salary</th>
                                <th>Background Check</th>
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
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>No Teachers available.</p>
                <?php } ?>
                <a href="manageTeachers.php" class="manage-button">Manage Teachers</a>
            </div>

            <!-- Classes Section -->
            <div class="entity-card">
                <h4>Classes</h4>
                <?php if (!empty($allClasses)) { ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Class Name</th>
                                <th>Capacity</th>
                                <th>Pupil Amount</th>
                                <th>Class Teacher</th>
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
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>No classes available.</p>
                <?php } ?>
                <a href="manageClasses.php" class="manage-button">Manage Classes</a>
            </div>

        </div>
    </div>
    <?php include 'partials/footer.php'; ?>
</body>
</html>
