<?php
function addLuokka($pdo, $input)
{

    if (!isset($input['Nimi']) || trim($input['Nimi']) === '') {
        return ["success" => false, "message" => "Luokan nimi puuttuu"];
    }
    if (!isset($input['Kapasiteetti']) || !is_numeric($input['Kapasiteetti'])) {
        return ["success" => false, "message" => "Kapasiteetti puuttuu tai on virheellinen"];
    }

    $LuokkaNimi = trim($input['Nimi']);
    $Varusteet = trim($input['Varusteet'] ?? '');
    $Sijainti = trim($input['Sijainti'] ?? '');
    $Tila = trim($input['Tila'] ?? 'aktiivinen');
    $Kapasiteetti = (int) ($input['Kapasiteetti'] ?? 0);

    try {
        $stmt = $pdo->prepare("INSERT INTO luokat (Nimi,Varusteet,Kapasiteetti,Sijainti,Tila) VALUES (?,?,?,?,?)");
        $stmt->execute([$LuokkaNimi, $Varusteet, $Kapasiteetti, $Sijainti, $Tila]);
        if ($stmt->rowCount() === 0) {
            return ["success" => false, "message" => "Luokan lisääminen epäonnistui"];
        }

        return ["success" => true, "message" => "Luokka added successfully", "LuokkaID" => $pdo->lastInsertId()];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Tietokantavirhe: " . $e->getMessage()];
    }
}


?>