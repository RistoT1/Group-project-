<?php
function addAika($pdo, $input)
{
    $results = [];

    // Tarkista, että 'ajat' on olemassa ja taulukko
    if (!isset($input['ajat']) || !is_array($input['ajat'])) {
        return [["success" => false, "message" => "Syötteestä puuttuu 'ajat'-avain tai se ei ole taulukko"]];
    }

    foreach ($input['ajat'] as $aika) {
        // Tarkista pakolliset kentät
        if (!isset($aika['LuokkaID']) || !is_numeric($aika['LuokkaID']) ||
            !isset($aika['AloitusAika']) || trim($aika['AloitusAika']) === '' ||
            !isset($aika['LopetusAika']) || trim($aika['LopetusAika']) === '' ||
            !isset($aika['Paivamaara']) || trim($aika['Paivamaara']) === '') {
            $results[] = ["success" => false, "message" => "Puuttuvia tai virheellisiä kenttiä"];
            continue;
        }

        $LuokkaID = (int) $aika['LuokkaID'];
        $AloitusAika = trim($aika['AloitusAika']);
        $LopetusAika = trim($aika['LopetusAika']);
        $Paivamaara = trim($aika['Paivamaara']);
        $Tila = trim($aika['Tila'] ?? 'vapaa');

        try {
            // Tarkista päällekkäisyydet
            $stmt = $pdo->prepare("
                SELECT COUNT(*) FROM varattavatajat
                WHERE LuokkaID = ? AND Paivamaara = ?
                AND (
                    (AloitusAika < ? AND LopetusAika > ?) OR
                    (AloitusAika >= ? AND AloitusAika < ?) OR
                    (LopetusAika > ? AND LopetusAika <= ?)
                )
            ");
            $stmt->execute([$LuokkaID, $Paivamaara, $LopetusAika, $AloitusAika, $AloitusAika, $LopetusAika, $AloitusAika, $LopetusAika]);

            if ($stmt->fetchColumn() > 0) {
                $results[] = ["success" => false, "message" => "Päällekkäinen aika"];
                continue;
            }

            // Lisää uusi aika
            $stmt = $pdo->prepare("
                INSERT INTO varattavatajat (LuokkaID, AloitusAika, LopetusAika, Paivamaara, Tila)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$LuokkaID, $AloitusAika, $LopetusAika, $Paivamaara, $Tila]);

            $results[] = [
                "success" => true,
                "message" => "Aika lisätty",
                "AikaID" => $pdo->lastInsertId()
            ];
        } catch (PDOException $e) {
            $results[] = ["success" => false, "message" => "Tietokantavirhe: " . $e->getMessage(), "input" => $aika];
        }
    }

    return $results;
}
