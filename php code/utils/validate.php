<?php
// /utils/validate.php
require_once '../config/database.php';

function validateRegistration($username, $email, $password, $confirm_password) {
    $errors = [];

    if (strlen($password) <= 8 || 
        !preg_match('/[A-Z]/', $password) || 
        !preg_match('/[a-z]/', $password) || 
        !preg_match('/[0-9]/', $password) || 
        !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        $errors[] = "Password does not meet the required criteria.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    return $errors;
}

 function checkUsernameExists($username) {
    $errors = [];
    $query = "SELECT COUNT(*) as count FROM Staff WHERE StaffUsername = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if($row['count'] > 0)
    {
        $errors[] = "Username already exists in the database.";
    }
}
?>
