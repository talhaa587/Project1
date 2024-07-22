<?php
// /models/Classes.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include_once '../config/database.php';

class Classes {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllClasses() {
        $stmt = $this->conn->prepare("SELECT * FROM Classes");
        $stmt->execute();
        $result = $stmt->get_result();
        $allClasses = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $allClasses;
    }

    public function getClassById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM Classes WHERE ClassID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $class = $result->fetch_assoc();
        $stmt->close();
        return $class;
    }

    public function validateClassYear($classYear) {
        $validYears = [
            "Reception Year", 
            "Year One", 
            "Year Two", 
            "Year Three", 
            "Year Four", 
            "Year Five", 
            "Year Six"
        ];
        return in_array($classYear, $validYears);
    }

    public function addClass($year, $name, $capacity, $pupilAmount, $teacherId) {

        if (!$this->validateClassYear($year)) {
            throw new Exception("Invalid class year");
        }
        $checkQuery = "SELECT COUNT(*) FROM Classes WHERE ClassTeacherID = ?";
    $checkStmt = $this->conn->prepare($checkQuery);
    $checkStmt->bind_param("i", $teacherId);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        throw new Exception("Teacher is already assigned to a class");
    }
        
        // SQL to add class
        $query = "INSERT INTO Classes (ClassYear, ClassName, ClassCapacity, PupilAmount, ClassTeacherID) VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssiii", $year, $name, $capacity, $pupilAmount, $teacherId);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function updateClass($id, $year, $name, $capacity, $pupilAmount, $teacherId) {
        if (!$this->validateClassYear($year)) {
            throw new Exception("Invalid class year");
        }

        $query = "UPDATE Classes SET ClassYear = ?, ClassName = ?, ClassCapacity = ?, PupilAmount = ?, ClassTeacherID = ? WHERE ClassID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssiiii", $year, $name, $capacity, $pupilAmount, $teacherId, $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }   

    public function deleteClass($id) {
        $checkQuery = "SELECT COUNT(*) FROM Pupil WHERE PupilClassID = ?";
    $checkStmt = $this->conn->prepare($checkQuery);
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        throw new Exception("Pupils are a part of this class therefore cannot delete class");
    }

        $stmt = $this->conn->prepare("DELETE FROM Classes WHERE ClassID = ?");
        $stmt->bind_param("i", $id);

        
        if ($stmt->execute()) {
            $stmt->close();
            return ['status' => 'success'];
        } else {
            $stmt->close();
            return ['status' => 'error', 'message' => 'Failed to delete class.'];
        }
    }
}
?>
