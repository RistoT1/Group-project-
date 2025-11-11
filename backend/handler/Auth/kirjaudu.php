<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function kirjaudu($pdo, $input) {
    $sähköposti = $input['sahkoposti'] ?? '';
    $salasana = $input['salasana'] ?? '';

    if (!$sähköposti || !$salasana) {
        http_response_code(400);
        return ["success" => false, "message" => "Täytä kaikki kentät"];
    }

    $pdo->beginTransaction();
    $stmt = $pdo->prepare("SELECT * FROM kayttajat WHERE Sähköposti = ? LIMIT 1");
    $stmt->execute([$sähköposti]);
    $kayttaja = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($kayttaja && password_verify($salasana, $kayttaja['SalasanaHash'])) {
        $pdo->commit();

        $payload = [
            'sub' => $kayttaja['KayttajaID'],
            'email' => $kayttaja['Sähköposti'],
            'role' => $kayttaja['Rooli'] ?? 'user',
            'iat' => time(),
            'exp' => time() + 3600
        ];

        $jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

        return [
            "success" => true,
            "message" => "Kirjautuminen onnistui",
            "token" => $jwt,
            "data" => [
                "id" => $kayttaja['KayttajaID'],
                "nimi" => $kayttaja['Nimi'],
                "sukunimi" => $kayttaja['Sukunimi'],
                "sahkoposti" => $kayttaja['Sähköposti'],
                "puhelin" => $kayttaja['Puhelinnumero'],
                "rooli" => $kayttaja['Rooli']
            ]
        ];
    } else {
        $pdo->rollBack();
        http_response_code(401);
        return ["success" => false, "message" => "Virheellinen sähköposti tai salasana"];
    }
}