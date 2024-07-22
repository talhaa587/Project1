<!-- /views/register.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Registration</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div class="container">
        <h2>Staff Registration</h2>
        <form action="../registerHandler.php" method="POST" id="registerForm">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <small>Password must be greater than 8 characters, contain at least 1 uppercase letter, 1 lowercase letter, 1 special character, and 1 numeric character.</small>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">Register</button>
        </form>
        <div id="error-block" style="display: none;"></div>
    </div>
    <p>Already have a account ? <a href="login.php">Login</a></p>
    <?php include 'partials/footer.php'; ?>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>
