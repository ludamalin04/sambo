<?php
// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Обробка preflight (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once 'models/BaseModel.php';
require_once 'Database.php';

// Автозавантаження моделей
spl_autoload_register(function ($class) {
    $file = __DIR__ . "/models/$class.php";
    if (file_exists($file)) {
        require_once $file;
    }
});

// Отримуємо метод, URI, тіло запиту
$method = $_SERVER['REQUEST_METHOD'];
$uri = trim($_SERVER['REQUEST_URI'], '/');
$uri = explode('?', $uri)[0]; // видаляємо GET-параметри
$parts = explode('/', $uri);

// API endpoint: /api/{table}/{id?}
if ($parts[0] !== 'api') {
    http_response_code(404);
    echo json_encode(["error" => "Invalid path"]);
    exit;
}

$table = $parts[1] ?? null;
$id = $parts[2] ?? null;

// Клас моделі: Contacts → class Contacts
$className = ucfirst($table);

if (!class_exists($className)) {
    http_response_code(404);
    echo json_encode(["error" => "Unknown model: $className"]);
    exit;
}

$model = new $className();
$input = json_decode(file_get_contents('php://input'), true) ?? [];

// Маршрутизація
switch ($method) {
    case 'GET':
        if ($id) {
            $model->getById($id);
        } else {
            $model->getAll();
        }
        break;

    case 'POST':
        $model->create($input);
        break;

    case 'PUT':
        if (!$id) {
            http_response_code(400);
            echo json_encode(["error" => "ID required for update"]);
        } else {
            $model->update($id, $input);
        }
        break;

    case 'DELETE':
        if (!$id) {
            http_response_code(400);
            echo json_encode(["error" => "ID required for delete"]);
        } else {
            $model->delete($id);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
}
