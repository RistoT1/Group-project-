<?php
function addVaraus($pdo, $input)
{
    if (empty($input['KayttajaID']) || empty($input['AikaID'])) {
        return ["success" => false, "message" => "KayttajaID ja AikaID vaaditaan"];
    }

    $KayttajaID = (int) $input['KayttajaID'];
    $AikaID = (int) $input['AikaID'];
    $Tarkoitus = isset($input['Tarkoitus']) ? trim($input['Tarkoitus']) : null;

    try {
        $queries = [
            "SELECT 1 FROM kayttajat WHERE KayttajaID = ?",
            "SELECT 1 FROM varattavatajat WHERE AikaID = ? AND Tila = 'vapaa' or Tila='peruttu'"
        ];
        $params = [$KayttajaID, $AikaID];
        $errors = ["Käyttäjää ei löydetty", "Aikaa ei löydetty tai se ei ole vapaa"];
        foreach ($queries as $index => $query) {
            $stmt = $pdo->prepare($query);
            $stmt->execute([$params[$index]]);
            if ($stmt->fetchColumn() === false) {
                return ["success" => false, "message" => $errors[$index]];
            }
        }

        $stmt = $pdo->prepare("INSERT INTO varaukset (KayttajaID, AikaID,Tarkoitus) VALUES (?, ?,?)");
        $stmt->execute([$KayttajaID, $AikaID, $Tarkoitus]);

        $stmt = $pdo->prepare("UPDATE varattavatajat SET Tila = 'varattu' WHERE AikaID = ?");
        $stmt->execute([$AikaID]);

        return ["success" => true, "message" => "Varaus lisätty onnistuneesti"];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Tietokantavirhe: " . $e->getMessage()];
    }
}
?>