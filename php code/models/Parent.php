<?php
class Parents {
    private $conn;
    private $table_name = "Parent";
    private $error;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Create a new parent
    public function addParent($name, $address, $email, $phoneNumber) {
        $query = "INSERT INTO " . $this->table_name . " (ParentName, ParentAddress, ParentEmail, ParentPhoneNumber) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssss", $name, $address, $email, $phoneNumber);
        return $stmt->execute();
    }

    // Read all parents
    public function getAllParents() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }


    // Delete a parent
    public function deleteParent($id) {
        $checkQuery = "SELECT COUNT(*) FROM Pupil WHERE PupilParent1ID= ? OR PupilParent2ID = ?";
    $checkStmt = $this->conn->prepare($checkQuery);
    $checkStmt->bind_param("ii", $id, $id);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        throw new Exception("Parent has child/children enrolled in the school.");
    }

            $query = "DELETE FROM " . $this->table_name . " WHERE ParentID = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id);
            if($stmt->execute()){
                $error[] = "Parent Deleted Successfully";
            }
            else{
                $error = "Parent has child/children enrolled in the school.";
            }

        }
    

    // Update a parent
    public function updateParent($id, $name, $address, $email, $phoneNumber) {
        $query = "UPDATE " . $this->table_name . " SET ParentName = ?, ParentAddress = ?, ParentEmail = ?, ParentPhoneNumber = ? WHERE ParentID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssi", $name, $address, $email, $phoneNumber, $id);
        if($stmt->execute()){
            return  ['status' => 'success'];
        }
        else {

            return['status' => 'Failed to Update Parent'];
        }
    }

    // Read a single parent
    public function getParentByID($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE ParentID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

}
?>
