<?php
class Database {
    private $host = "localhost"; // Your database host
    private $db_name = "SchoolDB"; // Your database name
    private $username = "root"; // Your database username
    private $password = "Admin@123"; // Your database password
    public $conn;

    // Get the database connection
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

            // Check connection
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }

        return $this->conn;
    }
}
?>
