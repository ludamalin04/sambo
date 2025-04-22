<?php
class Database {
    private $host = "dn579441.mysql.tools";
    private $db_name = "dn579441_sambo";
    private $username = "dn579441_sambo";
    private $password = "2MstP~5a~4";
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->returnError("Connection failed: " . $e->getMessage());
        }

        return $this->conn;
    }

    private function returnError($message) {
        echo json_encode([
            "success" => false,
            "error" => $message
        ]);
        exit;
    }
}
?>
