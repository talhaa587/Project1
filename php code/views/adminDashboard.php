<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include '../config/database.php';
include '../models/User.php';
include '../models/Classes.php';
include '../models/Pupil.php';
include '../models/Parent.php';
include '../models/Teacher.php';

$user = new User();
$class = new Classes();
$pupil = new Pupil();
$parent = new Parents();
$teacher = new Teacher();

$pendingStaff = $user->getPendingStaff();
$allStaff = $user->getAllStaff();
$allClasses = $class->getAllClasses();
$allPupils = $pupil->getAllPupils();
$allParents = $parent->getAllParents();
$allTeachers = $teacher->getAllTeachers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">  
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div class="container">
        <h2>Admin Dashboard</h2>
        <!-- Logout Button -->
        <form action="logout.php" method="POST" style="display: inline;">
            <button type="submit" class="logout-button">Logout</button>
        </form>
        <!-- Pending Staff Approvals Section -->
        <h3>Pending Staff Approvals</h3>
        <?php if (!empty($pendingStaff)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingStaff as $staff) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($staff['StaffUsername']); ?></td>
                            <td><?php echo htmlspecialchars($staff['StaffEmail']); ?></td>
                            <td>
                                <form action="../approveStaff.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="staff_id" value="<?php echo $staff['StaffID']; ?>">
                                    <button type="submit" name="approve">Approve</button>
                                </form>
                                <form action="../deleteStaff.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="staff_id" value="<?php echo $staff['StaffID']; ?>">
                                    <button type="submit" name="delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No pending staff approvals.</p>
        <?php } ?>

        <!-- Manage Entities Sections -->
        <h3>Manage Entities</h3>
        <div class="entity-sections">

            <!-- Staff Section -->
            <div class="entity-card">
                <h4>Staff</h4>
                <?php if (!empty($allStaff)) { ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Approved</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allStaff as $staff) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($staff['StaffUsername']); ?></td>
                                    <td><?php echo htmlspecialchars($staff['StaffEmail']); ?></td>
                                    <td><?php echo htmlspecialchars($staff['ApprovedStatus'] ? 'Yes' : 'No'); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>No staff members available.</p>
                <?php } ?>
                <a href="manageStaff.php" class="manage-button">Manage Staff</a>
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

            <!-- Pupils Section -->
            <div class="entity-card">
                <h4>Pupils</h4>
                <?php if (!empty($allPupils)) { ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Age</th>
                                <th>Class</th>
                                <th>Parent 1</th>
                                <th>Parent 2</th>
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
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>No pupils available.</p>
                <?php } ?>
                <a href="managePupils.php" class="manage-button">Manage Pupils</a>
            </div>

             <!-- Parent Section -->
            <div class="entity-card">
                <h4>Parents</h4>
                <?php if (!empty($allParents)) { ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allParents as $parent) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($parent['ParentName']); ?></td>
                                    <td><?php echo htmlspecialchars($parent['ParentAddress']); ?></td>
                                    <td><?php echo htmlspecialchars($parent['ParentEmail']); ?></td>
                                    <td><?php echo htmlspecialchars($parent['ParentPhoneNumber']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>No parents available.</p>
                <?php } ?>
                <a href="manageParents.php" class="manage-button">Manage parents</a>
            </div>

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
        </div>
        <?php include 'partials/footer.php'; ?>
</body>
</html>