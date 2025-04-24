<?php
require_once 'BaseModel.php';

class Classes extends BaseModel {
    public function __construct() {
        parent::__construct('classes');
    }

    public function getWithTrainerNames() {
        try {
            $stmt = $this->db->connect()->prepare("
                SELECT
                    c.id_classes,
                    t.name AS trainer_name,
                    t.phone AS trainer_phone,
                    s.name AS school_name,
                    s.address AS school_address
                FROM classes c
                LEFT JOIN trainers t ON c.id_trainers = t.id_trainers
                LEFT JOIN school s ON s.id_school = c.id_school
            ");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($results);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
