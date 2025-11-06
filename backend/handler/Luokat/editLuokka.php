<?php
function editLuokka($pdo, $input)
{
    if (!isset($input['LuokkaID'])) {
        return ["success" => false, "message" => "LuokkaID puuttuu"];
    }

    $LuokkaID = $input['LuokkaID'];
    $updates = [];

    try {
        // Fetch current data
        $stmt = $pdo->prepare("SELECT * FROM luokat WHERE LuokkaID = :LuokkaID");
        $stmt->execute(['LuokkaID' => $LuokkaID]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$current) {
            return ["success" => false, "message" => "Luokkaa ei löydetty"];
        }

        // Compare and collect changes
        if (isset($input['Nimi']) && $input['Nimi'] !== $current['Nimi']) {
            $updates['Nimi'] = $input['Nimi'];
        }
        if (isset($input['Varusteet']) && $input['Varusteet'] !== $current['Varusteet']) {
            $updates['Varusteet'] = $input['Varusteet'];
        }
        if (isset($input['Kapasiteetti']) && $input['Kapasiteetti'] !== $current['Kapasiteetti']) {
            $updates['Kapasiteetti'] = $input['Kapasiteetti'];
        }
        if (isset($input['Sijainti']) && $input['Sijainti'] !== $current['Sijainti']) {
            $updates['Sijainti'] = $input['Sijainti'];
        }
        if (isset($input['Tila']) && $input['Tila'] !== $current['Tila']) {
            $updates['Tila'] = $input['Tila'];
        }

        // Run update if needed
        if (!empty($updates)) {
            $setClause = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($updates)));
            $stmt = $pdo->prepare("UPDATE luokat SET $setClause WHERE LuokkaID = :LuokkaID");
            $updates['LuokkaID'] = $LuokkaID;
            $stmt->execute($updates);
        }

        return ["success" => true, "message" => "Luokan tiedot päivitetty onnistuneesti"];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Tietokantavirhe: " . $e->getMessage()];
    }
}
?>