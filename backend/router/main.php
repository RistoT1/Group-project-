<?php
header("Content-Type: application/json");

require_once "../config/config.php";
//kayttajat
require_once "../handler/Kayttajat/getKayttajat.php";
require_once "../handler/Kayttajat/addKayttaja.php";
require_once "../handler/Kayttajat/deleteKayttaja.php";
require_once "../handler/Kayttajat/editKayttaja.php";
//luokat
require_once "../handler/Luokat/getLuokat.php";
require_once "../handler/Luokat/addLuokka.php";
require_once "../handler/luokat/editLuokka.php";
require_once "../handler/Luokat/deleteLuokka.php";
//ajat
require_once "../handler/Ajat/getAjat.php";
require_once "../handler/Ajat/addAika.php";
require_once "../handler/Ajat/editAika.php";
//kirjautuminen
require_once "../handler/Auth/kirjaudu.php";
//autentikointi (admin/user)
require_once "../handler/Auth/auth.php";


$routes = [
    'GET' => [
        'kayttajat' => 'getKayttajat',
        'luokat' => 'getLuokat',
        'varaukset' => 'getVaraukset',
        'ajat' => 'getAjat'
    ],
    'POST' => [
        //käyttäjät
        'addKayttaja' => 'addKayttaja',
        'editKayttaja' => 'editKayttaja',
        'deleteKayttaja' => 'deleteKayttaja',
        //luokka
        'addLuokka' => 'addLuokka',
        'editLuokka' => 'editLuokka',
        'deleteLuokka' => 'deleteLuokka',
        //varaus
        'addVaraus' => 'addVaraus',
        //ajat
        'addAika' => 'addAika',
        'editAika' => 'editAika',
        //kirjautuminen
        'kirjaudu' => 'kirjaudu'
    ]
];

$adminOnlyRoutes = [
    'GET' => ['kayttajat'],
    'POST' => ['addKayttaja', 'deleteKayttaja', 'addAika', 'addLuokka', 'editLuokka']
];

$publicRoutes = ['kirjaudu','getLuokat','getAjat'];

$method = $_SERVER['REQUEST_METHOD'];
$input = $method === 'POST'
    ? (json_decode(file_get_contents('php://input'), true) ?: [])
    : $_GET;

try {
    if (!isset($routes[$method])) {
        http_response_code(405);
        echo json_encode(["success" => false, "message" => "Method not allowed"]);
        exit;
    }

    $handled = false;

    foreach ($routes[$method] as $param => $handler) {
        if (array_key_exists($param, $input)) {
            if (!function_exists($handler)) {
                throw new Exception("Handler $handler not defined");
            }

            $decoded = null;
            if (!in_array($param, $publicRoutes)) {
                $decoded = tarkistaAuth();
                $role = $decoded->role ?? 'user';

                if (in_array($param, $adminOnlyRoutes[$method] ?? []) && $role !== 'ylläpitäjä') {
                    http_response_code(403);
                    echo json_encode(["success" => false, "message" => "Ei käyttöoikeutta"]);
                    exit;
                }
            }

            $result = $method === 'POST'
                ? $handler($pdo, $input)
                : $handler($pdo);

            echo json_encode($result);
            $handled = true;
            break;
        }
    }

    if (!$handled) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Reittiä ei löytynyt"]);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}