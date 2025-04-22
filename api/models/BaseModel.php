<?php
require_once 'Database.php';

abstract class BaseModel {
    protected $db;
    protected $table;
    protected $allowedFields = [];
    protected $requiredFields = [];
    protected $fieldTypes = [];

    public function __construct($table) {
        $this->db = new Database();
        $this->table = $table;
        header('Content-Type: application/json; charset=utf-8');
        $this->loadSchema();
    }

    protected function loadSchema() {
        $stmt = $this->db->connect()->prepare("DESCRIBE {$this->table}");
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($columns as $column) {
            $field = $column['Field'];

            // Пропускаємо ID-поле
            if ($field === "id_{$this->table}") continue;

            $this->allowedFields[] = $field;

            // Обов'язкові поля (NOT NULL без Default)
            if ($column['Null'] === 'NO' && $column['Default'] === null && $column['Extra'] !== 'auto_increment') {
                $this->requiredFields[] = $field;
            }

            // Типи
            $mysqlType = strtolower($column['Type']);
            $this->fieldTypes[$field] = $this->mapMysqlType($mysqlType);
        }
    }

    protected function mapMysqlType($mysqlType) {
        return match (true) {
            str_contains($mysqlType, 'int') => 'int',
            str_contains($mysqlType, 'varchar'),
            str_contains($mysqlType, 'text') => 'string',
            str_contains($mysqlType, 'char') => 'string',
            str_contains($mysqlType, 'timestamp'),
            str_contains($mysqlType, 'datetime') => 'string',
            str_contains($mysqlType, 'float'),
            str_contains($mysqlType, 'double'),
            str_contains($mysqlType, 'decimal') => 'float',
            default => 'string',
        };
    }

        public function getAll() {
        try {
            $stmt = $this->db->connect()->query("SELECT * FROM {$this->table}");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(["success" => true, "data" => $data]);
        } catch (PDOException $e) {
            $this->error($e->getMessage());
        }
    }

    public function getById($id) {
        try {
            $stmt = $this->db->connect()->prepare("SELECT * FROM {$this->table} WHERE id_{$this->table} = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(["success" => true, "data" => $data]);
        } catch (PDOException $e) {
            $this->error($e->getMessage());
        }
    }

    public function create($data) {
        $errors = $this->validate($data);
        if (!empty($errors)) {
            $this->error("Validation failed", $errors);
            return;
        }

        try {
            $filtered = $this->filterFields($data);
            $fields = implode(', ', array_keys($filtered));
            $placeholders = implode(', ', array_fill(0, count($filtered), '?'));

            $stmt = $this->db->connect()->prepare("INSERT INTO {$this->table} ($fields) VALUES ($placeholders)");
            $stmt->execute(array_values($filtered));

            echo json_encode(["success" => true, "message" => "Created successfully"]);
        } catch (PDOException $e) {
            $this->error($e->getMessage());
        }
    }

    public function update($id, $data) {
        unset($data['id']);
        $errors = $this->validate($data, false); // on update required fields not needed

        if (!empty($errors)) {
            $this->error("Validation failed", $errors);
            return;
        }

        try {
            $filtered = $this->filterFields($data);
            $setStr = implode(', ', array_map(fn($key) => "$key = ?", array_keys($filtered)));

            $stmt = $this->db->connect()->prepare("UPDATE {$this->table} SET $setStr WHERE id_{$this->table} = ?");
            $stmt->execute([...array_values($filtered), $id]);

            echo json_encode(["success" => true, "message" => "Updated successfully"]);
        } catch (PDOException $e) {
            $this->error($e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->db->connect()->prepare("DELETE FROM {$this->table} WHERE id_{$this->table} = ?");
            $stmt->execute([$id]);
            echo json_encode(["success" => true, "message" => "Deleted successfully"]);
        } catch (PDOException $e) {
            $this->error($e->getMessage());
        }
    }

    protected function filterFields($data) {
        return array_filter(
            $data,
            fn($key) => in_array($key, $this->allowedFields),
            ARRAY_FILTER_USE_KEY
        );
    }

    protected function validate($data, $checkRequired = true) {
        $errors = [];

        if ($checkRequired) {
            foreach ($this->requiredFields as $field) {
                if (!isset($data[$field]) || $data[$field] === '') {
                    $errors[$field] = 'Field is required';
                }
            }
        }

        foreach ($data as $key => $value) {
            if (isset($this->fieldTypes[$key])) {
                $type = $this->fieldTypes[$key];
                $valid = match ($type) {
                    'int' => filter_var($value, FILTER_VALIDATE_INT) !== false,
                    'string' => is_string($value),
                    'url' => filter_var($value, FILTER_VALIDATE_URL),
                    'email' => filter_var($value, FILTER_VALIDATE_EMAIL),
                    default => true,
                };

                if (!$valid) {
                    $errors[$key] = "Invalid type: expected $type";
                }
            }
        }

        return $errors;
    }

    protected function error($msg, $details = []) {
        echo json_encode([
            "success" => false,
            "error" => $msg,
            "details" => $details
        ]);
    }
}
