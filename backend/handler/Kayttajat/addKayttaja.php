<?php
function addKayttaja($pdo, $input)
{
    $nimi = trim($input['nimi'] ?? '') ?: null;
    $sukunimi = trim($input['sukunimi'] ?? '') ?: null;
    $sahkoposti = trim($input['sahkoposti'] ?? '') ?: null;
    $puhelin = trim($input['puhelin'] ?? '') ?: null;

    if (!$nimi || !$sahkoposti || !$puhelin) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Täytä kaikki kentät"
        ]);
        exit;
    }

    $salasana = $input['salasana'] ?? null;

    if (strlen($salasana) < 8) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Salasanan tulee olla vähintään 8 merkkiä pitkä"
        ]);
        exit;
    }

    if (
        !preg_match('/[A-Z]/', $salasana) ||
        !preg_match('/[a-z]/', $salasana) ||
        !preg_match('/[0-9]/', $salasana) ||
        !preg_match('/[\W_]/', $salasana)
    ) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Salasanan tulee sisältää vähintään yksi iso kirjain, pieni kirjain, numero ja erikoismerkki"
        ]);
        exit;
    }
    try {
        $hashedPassword = password_hash($salasana, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO kayttajat (Nimi, Sukunimi, Sähköposti, Puhelinnumero, SalasanaHash) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nimi, $sukunimi, $sahkoposti, $puhelin, $hashedPassword]);
        return ["success" => true, "message" => "Käyttäjä lisätty onnistuneesti"];
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            http_response_code(400);
            return ["success" => false, "message" => "Sähköposti tai Puhelin on jo rekisteröity"];
        } else {
            throw $e;
        }
    }
}

?>