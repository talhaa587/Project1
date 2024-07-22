<!-- /views/login.php -->
<?php
session_start();
session_destroy();
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div class="container">
        <h2>Login</h2>
        <form action="../loginHandler.php" method="POST" id="loginForm">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Login as:</label>
                <select id="role" name="role">
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                </select>
            </div>
            <button type="submit">Login</button>
        </form>
        <div id="error-block" style = "display:none;"></div>
    </div>
    <p>Don't have an account? <a href="register.php">Create an Account</a></p>
    <?php include 'partials/footer.php'; ?>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>
