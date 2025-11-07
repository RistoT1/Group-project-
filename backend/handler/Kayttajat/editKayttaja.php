<?php
function editKayttaja($pdo, $input)
{
    $KayttajaID = $input['KayttajaID'];
    $updates = [];

    // Fetch current user data
    $stmt = $pdo->prepare("SELECT * FROM kayttajat WHERE KayttajaID = :KayttajaID");
    $stmt->execute(['KayttajaID' => $KayttajaID]);
    $current = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$current) {
        return ["success" => false, "message" => "Käyttäjää ei löydetty"];
    }

    // Compare and collect changes
    if (isset($input['Nimi']) && $input['Nimi'] !== $current['Nimi']) {
        $updates['Nimi'] = $input['Nimi'];
    }
    if (isset($input['Sukunimi']) && $input['Sukunimi'] !== $current['Sukunimi']) {
        $updates['Sukunimi'] = $input['Sukunimi'];
    }
    if (isset($input['Puhelinnumero']) && $input['Puhelinnumero'] !== $current['Puhelinnumero']) {
        $updates['Puhelinnumero'] = $input['Puhelinnumero'];
    }

    // Run update if needed
    if (!empty($updates)) {
        $setClause = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($updates)));
        $stmt = $pdo->prepare("UPDATE kayttajat SET $setClause WHERE KayttajaID = :KayttajaID");
        $updates['KayttajaID'] = $KayttajaID;
        $stmt->execute($updates);
    }

    return ["success" => true, "message" => "Käyttäjätiedot päivitetty onnistuneesti"];
}
?>