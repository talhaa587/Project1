<?php
// /models/Pupil.php

include_once '../config/database.php';
include_once '../utils/helper.php';

class Pupil {
    private $conn;
    private $table_name = "Pupil";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllPupils() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $pupils = [];
        while ($row = $result->fetch_assoc()) {
            $pupils[] = $row;
        }
        return $pupils;
    }

    public function doesClassExist($ClassID){
        $stmt = $this->conn->prepare("SELECT 1 FROM Classes WHERE ClassID = ?");
        $stmt->execute();
        $result = $stmt->get_result();
        if($ClassID == $result['ClassID'])
        {
            return true;
        } else {
            return false;
        }

    }

    public function registerPupil($pupilName, $pupilAddress, $pupilAge, $pupilHeight, $pupilWeight, $pupilClassID, $pupilParent1ID, $pupilParent2ID) {
        // Check if classID exists
        if (!$this->doesClassExist($pupilClassID)) {
            return ['status' => 'error', 'message' => 'ClassID does not exist.'];
        }
    
        // Check if parentIDs exist
        if ($pupilParent1ID !== 'null' && !$this->doesParentExist($pupilParent1ID)) {
            return ['status' => 'error', 'message' => 'Parent1ID does not exist.'];
        }
    
        if ($pupilParent2ID !== 'null' && !$this->doesParentExist($pupilParent2ID)) {
            return ['status' => 'error', 'message' => 'Parent2ID does not exist.'];
        }
        
        if($pupilParent1ID == $pupilParent2ID)
        {
            return ['status' => 'error', 'message' => 'Both Parent IDs cannot be the same'];
        }

        // Insert pupil if all checks pass
        $stmt = $this->conn->prepare("
            INSERT INTO " . $this->table_name . " 
            (PupilName, PupilAddress, PupilAge, PupilHeight, PupilWeight, PupilClassID, PupilParent1ID, PupilParent2ID) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssiffiii", $pupilName, $pupilAddress, $pupilAge, $pupilHeight, $pupilWeight, $pupilClassID, $pupilParent1ID, $pupilParent2ID);
        
        if ($stmt->execute()) {
            $stmt->close();
            return ['status' => 'success'];
        } else {
            $stmt->close();
            return ['status' => 'error', 'message' => 'Failed to register pupil.'];
        }
    }
    public function getPupilById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE PupilID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Method to update pupil
    public function updatePupil($id, $name, $address, $age, $height, $weight, $classId, $parent1Id, $parent2Id) {
        if (!$this->doesClassExist($classId)) {
            return false;
        }
        if($pupilParent1ID == $pupilParent2ID)
        {
            return ['status' => 'error', 'message' => 'Both Parent IDs cannot be the same'];
        }
        $query = "UPDATE " . $this->table_name . " SET PupilName = ?, PupilAddress = ?, PupilAge = ?, PupilHeight = ?, PupilWeight = ?, PupilClassID = ?, PupilParent1ID = ?, PupilParent2ID = ? WHERE PupilID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssiiiii", $name, $address, $age, $height, $weight, $classId, $parent1Id, $parent2Id, $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getAllParents() {
        $stmt = $this->conn->prepare("SELECT ParentID, ParentName FROM Parent");
        $stmt->execute();
        $result = $stmt->get_result();
        $allParents = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $allParents;
    }
}
?>
