<?php
function editAika($pdo, $input)
{
    $results = [];

    // If batch: loop through each item in 'ajat'
    if (isset($input['ajat']) && is_array($input['ajat'])) {
        foreach ($input['ajat'] as $aika) {
            $results[] = editAika($pdo, $aika); // Recursive call for each slot
        }
        return $results;
    }

    // Single item update
    if (!isset($input['AikaID']) || !is_numeric($input['AikaID'])) {
        return ["success" => false, "message" => "AikaID puuttuu tai ei ole numero"];
    }

    $AikaID = (int) $input['AikaID'];
    $updates = [];

    try {
        $stmt = $pdo->prepare("SELECT * FROM varattavatajat WHERE AikaID = :AikaID");
        $stmt->execute(['AikaID' => $AikaID]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$current) {
            return ["success" => false, "message" => "Aikaa ei löytynyt"];
        }

        // Allow changes to time and Tila only
        if (isset($input['AloitusAika']) && $input['AloitusAika'] !== $current['AloitusAika']) {
            $updates['AloitusAika'] = trim($input['AloitusAika']);
        }
        if (isset($input['LopetusAika']) && $input['LopetusAika'] !== $current['LopetusAika']) {
            $updates['LopetusAika'] = trim($input['LopetusAika']);
        }
        if (isset($input['Paivamaara']) && $input['Paivamaara'] !== $current['Paivamaara']) {
            $updates['Paivamaara'] = trim($input['Paivamaara']);
        }
        if (isset($input['Tila']) && $input['Tila'] !== $current['Tila']) {
            $updates['Tila'] = trim($input['Tila']);
        }

        if (isset($input['LuokkaID'])) {
            return ["success" => false, "message" => "Kenttää 'LuokkaID' ei voi muuttaa tällä toiminnolla"];
        }

        // Check for overlapping times if time is being changed
        if (isset($updates['AloitusAika']) || isset($updates['LopetusAika']) || isset($updates['Paivamaara'])) {
            $AloitusAika = $updates['AloitusAika'] ?? $current['AloitusAika'];
            $LopetusAika = $updates['LopetusAika'] ?? $current['LopetusAika'];
            $Paivamaara = $updates['Paivamaara'] ?? $current['Paivamaara'];
            $LuokkaID = $current['LuokkaID'];

            $stmt = $pdo->prepare("
                SELECT COUNT(*) FROM varattavatajat
                WHERE LuokkaID = ? AND Paivamaara = ? AND AikaID != ?
                AND (
                    (AloitusAika < ? AND LopetusAika > ?) OR
                    (AloitusAika >= ? AND AloitusAika < ?) OR
                    (LopetusAika > ? AND LopetusAika <= ?)
                )
            ");
            $stmt->execute([
                $LuokkaID, $Paivamaara, $AikaID,
                $LopetusAika, $AloitusAika,
                $AloitusAika, $LopetusAika,
                $AloitusAika, $LopetusAika
            ]);

            if ($stmt->fetchColumn() > 0) {
                return ["success" => false, "message" => "Päällekkäinen aika – muutos estetty"];
            }
        }

        // Run update
        if (!empty($updates)) {
            $setClause = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($updates)));
            $updates['AikaID'] = $AikaID;
            $stmt = $pdo->prepare("UPDATE varattavatajat SET $setClause WHERE AikaID = :AikaID");
            $stmt->execute($updates);
        }

        return ["success" => true, "message" => "Aika tai tila päivitetty onnistuneesti"];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Tietokantavirhe: " . $e->getMessage()];
    }
}
?>