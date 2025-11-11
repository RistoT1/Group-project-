<?php
function addVaraus($pdo, $input)
{
    if (empty($input['KayttajaID']) || empty($input['LuokkaID'])) {
        return ["success" => false, "message" => "KayttajaID ja LuokkaID vaaditaan"];
    }

    $KayttajaID = (int) $input['KayttajaID'];
    $LuokkaID = (int) $input['LuokkaID'];
    $Paivamaara = trim($input['Paivamaara'] ?? '');
    $AloitusAika = trim($input['AloitusAika'] ?? '');
    $LopetusAika = trim($input['LopetusAika'] ?? '');
    $Tarkoitus = isset($input['Tarkoitus']) ? trim($input['Tarkoitus']) : null;

    try {
        // 1️⃣ Check for overlapping active reservations (ignore 'peruttu')
        $overlapStmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM varaukset
            WHERE LuokkaID = ?
              AND Paivamaara = ?
              AND Tila != 'peruttu'
              AND (
                    (AloitusAika < ? AND LopetusAika > ?)  -- overlap
                  )
        ");
        $overlapStmt->execute([$LuokkaID, $Paivamaara, $LopetusAika, $AloitusAika]);
        $overlapCount = $overlapStmt->fetchColumn();

        if ($overlapCount > 0) {
            return ["success" => false, "message" => "Tämä aika on jo varattu."];
        }

        $stmt = $pdo->prepare("
            INSERT INTO varaukset 
                (KayttajaID, LuokkaID, Paivamaara, AloitusAika, LopetusAika, Tarkoitus, Tila) 
            VALUES (?, ?, ?, ?, ?, ?, 'varattu')
        ");
        $stmt->execute([$KayttajaID, $LuokkaID, $Paivamaara, $AloitusAika, $LopetusAika, $Tarkoitus]);

        if ($stmt->rowCount() === 0) {
            return ["success" => false, "message" => "Varauksen lisääminen epäonnistui"];
        }

        return ["success" => true, "message" => "Varaus lisätty onnistuneesti", "VarausID" => $pdo->lastInsertId()];

    } catch (PDOException $e) {
        if ($e->getCode() == "23000") {
            return ["success" => false, "message" => "Tietoja ei ole olemassa tietokannassa."];
        }
        return ["success" => false, "message" => "Tietokantavirhe: " . $e->getMessage()];
    }
}
?>
