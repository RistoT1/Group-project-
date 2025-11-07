<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function tarkistaAuth() {
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';

    if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Token puuttuu"]);
        exit;
    }

    $token = substr($authHeader, 7);

    try {
        $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
        return $decoded;
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Virheellinen tai vanhentunut token"]);
        exit;
    }
}