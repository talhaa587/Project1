<?php
// /models/User.php

include_once '../config/database.php';

class User {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function register($username, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->conn->prepare("INSERT INTO Staff (StaffUsername, StaffEmail, StaffPassword, ApprovedStatus) VALUES (?, ?, ?, 0)");
        if ($stmt === false) {
            return ['status' => 'error', 'message' => 'Failed to prepare statement.'];
        }

        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            $stmt->close();
            return ['status' => 'success'];
        } else {
            $stmt->close();
            return ['status' => 'error', 'message' => 'Registration failed. Please try again.'];
        }
    }

    public function login($username, $password, $role) {
        if ($role == 'admin') {
            $stmt = $this->conn->prepare("SELECT AdminID, AdminPassword FROM Admin WHERE AdminUsername = ?");
        } else {
            $stmt = $this->conn->prepare("SELECT StaffID, StaffPassword, ApprovedStatus FROM Staff WHERE StaffUsername = ?");
        }

        if ($stmt === false) {
            return ['status' => 'error', 'message' => 'Failed to prepare statement.'];
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();

        if ($role == 'admin') {
            $stmt->bind_result($admin_id, $hashed_password);
            $stmt->fetch();
            $stmt->close();

            if (password_verify($password, $hashed_password)) {
                return ['status' => 'success', 'user_id' => $admin_id, 'user_role' => 'admin'];
            } else {
                return ['status' => 'error', 'message' => 'Invalid credentials.'];
            }
        } else {
            $stmt->bind_result($staff_id, $hashed_password, $approved_status);
            $stmt->fetch();
            $stmt->close();

            if ($approved_status == 0) {
                return ['status' => 'error', 'message' => 'Account not approved. Please contact admin.'];
            }

            if (password_verify($password, $hashed_password)) {
                return ['status' => 'success', 'user_id' => $staff_id, 'user_role' => 'staff'];
            } else {
                return ['status' => 'error', 'message' => 'Invalid credentials.'];
            }
        }
    }

    public function getPendingStaff() {
        $stmt = $this->conn->prepare("SELECT StaffID, StaffUsername, StaffEmail FROM Staff WHERE ApprovedStatus = 0");
        $stmt->execute();
        $result = $stmt->get_result();
        $pendingStaff = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $pendingStaff;
    }

    public function approveStaff($staff_id) {
        $stmt = $this->conn->prepare("UPDATE Staff SET ApprovedStatus = 1 WHERE StaffID = ?");
        $stmt->bind_param("i", $staff_id);
        $stmt->execute();
        $stmt->close();
    }
    public function updateStaff($id, $username, $email, $approvedStatus) {
        $query = "UPDATE Staff SET StaffUsername = ?, StaffEmail = ?, ApprovedStatus = ? WHERE StaffID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssii", $username, $email , $approvedStatus, $id);
        if ($stmt->execute()) {
            return ['status' => 'success'];
        } else {
            return ['status' => 'error'];
        }
    }

    public function getAllStaff() {
        $query = "SELECT * FROM Staff";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getStaffByID($id) {
        $query = "SELECT * FROM Staff WHERE StaffID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function deleteStaff($staff_id) {
        $stmt = $this->conn->prepare("DELETE FROM Staff WHERE StaffID = ?");
        $stmt->bind_param("i", $staff_id);
        $stmt->execute();
        $stmt->close();
    }

    public function addStaff($username, $email, $password,$approved_status)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->conn->prepare("INSERT INTO Staff (StaffUsername, StaffEmail, StaffPassword, ApprovedStatus) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            return ['status' => 'error', 'message' => 'Failed to prepare statement.'];
        }

        $stmt->bind_param("sssi", $username, $email, $hashed_password,$approved_status);

        if ($stmt->execute()) {
            $stmt->close();
            return ['status' => 'success'];
        } else {
            $stmt->close();
            return ['status' => 'error', 'message' => 'Registration failed. Please try again.'];
        }
    }
}
?>
