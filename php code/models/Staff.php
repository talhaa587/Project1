<?php
class Staff {
    private $conn;
    private $table_name = "Staff";

    public $StaffID;
    public $StaffUsername;
    public $StaffEmail;
    public $StaffPassword;
    public $ApprovedStatus;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Create a new staff
    public function addStaff($username, $email, $password, $approvedStatus) {
        $query = "INSERT INTO " . $this->table_name . " (StaffUsername, StaffEmail, StaffPassword, ApprovedStatus) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssi", $username, $email, password_hash($password, PASSWORD_DEFAULT), $approvedStatus);
        return $stmt->execute();
    }

    // Read all staff
    public function getAllStaff() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Delete a staff
    public function deleteStaff($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE StaffID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Update a staff
    public function updateStaff($id, $username, $email, $password, $approvedStatus) {
        $query = "UPDATE " . $this->table_name . " SET StaffUsername = ?, StaffEmail = ?, StaffPassword = ?, ApprovedStatus = ? WHERE StaffID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssi", $username, $email, password_hash($password, PASSWORD_DEFAULT), $approvedStatus, $id);
        return $stmt->execute();
    }

    // Read a single staff
    public function getStaffByID($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE StaffID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
