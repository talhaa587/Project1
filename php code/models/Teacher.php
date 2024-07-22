<?php
// /models/Teacher.php

include_once '../config/database.php';

class Teacher {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllTeachers() {
        $stmt = $this->conn->prepare("SELECT * FROM Teacher");
        $stmt->execute();
        $result = $stmt->get_result();
        $allTeachers = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $allTeachers;
    }
    public function getTeacherById($id) {
        $query = "SELECT * FROM Teacher WHERE TeacherID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addTeacher($name, $address, $phoneNumber, $annualSalary, $backgroundCheck) {
        $stmt = $this->conn->prepare("
            INSERT INTO Teacher (TeacherName, TeacherAddress, TeacherPhoneNumber, TeacherAnnualSalary, TeacherBackgroundCheck) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssis", $name, $address, $phoneNumber, $annualSalary, $backgroundCheck);
        
        if ($stmt->execute()) {
            $stmt->close();
            return ['status' => 'success'];
        } else {
            $stmt->close();
            return ['status' => 'error', 'message' => 'Failed to add teacher.'];
        }
    }

    public function deleteTeacher($id) {
        $checkQuery = "SELECT COUNT(*) FROM Classes WHERE ClassTeacherID = ?";
    $checkStmt = $this->conn->prepare($checkQuery);
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        throw new Exception("Teacher is already assigned to a class.");
    }
        $stmt = $this->conn->prepare("DELETE FROM Teacher WHERE TeacherID = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $stmt->close();
            return ['status' => 'success'];
        } else {
            $stmt->close();
            return ['status' => 'error', 'message' => 'Failed to delete teacher.'];
        }
    }

    public function updateTeacher($id, $name, $address, $phoneNumber, $annualSalary, $backgroundCheck) {
        $query = "UPDATE Teacher SET TeacherName = ?, TeacherAddress = ?, TeacherPhoneNumber = ?, TeacherAnnualSalary = ?, TeacherBackgroundCheck = ? WHERE TeacherID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssisi", $name, $address, $phoneNumber, $annualSalary,$backgroundCheck, $id);
        if ($stmt->execute()) {
            $stmt->close();
            return ['status' => 'success'];
        } else {
            $stmt->close();
            return ['status' => 'error', 'message' => 'Failed to delete teacher.'];
        }
    }   
}
?>
