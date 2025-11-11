<?php
header("Content-Type: application/json");

require_once "../config/config.php";
// handlerit
require_once "../handler/Kayttajat/getKayttajat.php";
require_once "../handler/Kayttajat/addKayttaja.php";
require_once "../handler/Kayttajat/deleteKayttaja.php";
require_once "../handler/Kayttajat/editKayttaja.php";
require_once "../handler/Luokat/getLuokat.php";
require_once "../handler/Luokat/addLuokka.php";
require_once "../handler/luokat/editLuokka.php";
require_once "../handler/Luokat/deleteLuokka.php";
require_once "../handler/Varaus/getVaraukset.php";
require_once "../handler/Varaus/addVaraus.php";
require_once "../handler/Varaus/cancelVaraus.php";
require_once "../handler/Varaus/getKayttajaVaraukset.php";
require_once "../handler/Auth/kirjaudu.php";
require_once "../handler/Luokat/getLuokkatID.php";
require_once "../handler/Auth/auth.php"; // tarkistaAuth()
require_once "../handler/Ajat/getAikaValues.php"; 

$routes = [
    'GET' => [
        'kayttajat' => 'getKayttajat',
        'luokat' => 'getLuokat',
        'varaukset' => 'getVaraukset',
        'aikavalues' => 'getAikaValues',//helpperi aikojen  renderöintiin
    ],
    'POST' => [
        'kirjaudu' => 'kirjaudu',
        'addKayttaja' => 'addKayttaja',
        'editKayttaja' => 'editKayttaja',
        'deleteKayttaja' => 'deleteKayttaja',
        'addLuokka' => 'addLuokka',
        'editLuokka' => 'editLuokka',
        'deleteLuokka' => 'deleteLuokka',
        'addVaraus' => 'addVaraus',
        'cancelVaraus' => 'cancelVaraus',
        'getLuokatID' => 'getLuokatID',
        'kayttajaVaraukset' => 'getkayttajaVaraukset'
    ]
];

$adminOnlyRoutes = [
    'GET' => ['kayttajat'],
    'POST' => ['addKayttaja', 'deleteKayttaja', 'addAika', 'addLuokka', 'editLuokka']
];

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

            // kaikki paitsi kirjaudu vaatii autentikoinnin
            if ($param !== 'kirjaudu') {
                $decoded = tarkistaAuth();

                //admin reittien tarkistus
                if (in_array($param, $adminOnlyRoutes[$method] ?? []) && ($decoded->role ?? 'user') !== 'ylläpitäjä') {
                    http_response_code(403);
                    echo json_encode(["success" => false, "message" => "Ei käyttöoikeutta"]);
                    exit;
                }
            }

            // Kutsu käsittelijää 
            $result = $method === 'POST'
                ? $handler($pdo, $input, $decoded)
                : $handler($pdo, $decoded);

            echo json_encode($result, JSON_UNESCAPED_UNICODE);
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
