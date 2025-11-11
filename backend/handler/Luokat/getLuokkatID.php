<?php
function getLuokatID($pdo, $input)
{
    if (!isset($input['LuokkaID'])) {
        return ["success" => false, "message" => "LuokkaID puuttuu"];
    }

    $LuokkaID = $input['LuokkaID'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM luokat WHERE LuokkaID = ?");
        $stmt->execute([$LuokkaID]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$result) {
            return ['success' => true, 'message' => "Luokkaa ei löytynyt"];
        }

        return ['success' => true, 'data' => $result];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => "Tietokantavirhe: " . $e->getMessage()];
    }
}