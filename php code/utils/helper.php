

<?php

 function doesClassExist($classID) {
    $stmt = $this->conn->prepare("SELECT 1 FROM Classes WHERE ClassID = ?");
    $stmt->bind_param("i", $classID);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

 function doesParentExist($parentID) {
    $stmt = $this->conn->prepare("SELECT 1 FROM Parents WHERE ParentID = ?");
    $stmt->bind_param("i", $parentID);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}
?>