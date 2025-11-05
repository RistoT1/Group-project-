<?php
header("Content-Type: application/json");

require_once "../config/config.php";



// Define routes
$routes = [
    'GET' => [
        'kayttajat' => 'getKayttajat',
        'luokat' => 'getLuokat',
        'varaukset' => 'getVaraukset',
        'ajat' => 'getVarattavatAjat'
    ],
    'POST' => [
        'addKayttaja' => 'addKayttaja',
        'addVaraus' => 'addVaraus',
        'addAika' => 'addVarattavaAika'
    ]
];

$method = $_SERVER['REQUEST_METHOD'];
$input = $method === 'POST'
    ? (json_decode(file_get_contents('php://input'), true) ?: [])
    : $_GET;

function apiResponse($data, $success = true) {
    return ['success' => $success, $success ? 'data' : 'error' => $data];
}

try {
    if (!isset($routes[$method])) {
        http_response_code(405);
        echo json_encode(apiResponse("Method not allowed", false));
        exit;
    }

    $handled = false;
    foreach ($routes[$method] as $param => $handler) {
        if (array_key_exists($param, $input)) {
            if (!function_exists($handler)) {
                throw new Exception("Handler $handler not defined");
            }

            $result = $method === 'POST'
                ? $handler($pdo, $input)
                : $handler($pdo);

            echo json_encode(apiResponse($result));
            $handled = true;
            break;
        }
    }

    if (!$handled) {
        http_response_code(404);
        echo json_encode(apiResponse("Unknown endpoint", false));
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(apiResponse($e->getMessage(), false));
}
