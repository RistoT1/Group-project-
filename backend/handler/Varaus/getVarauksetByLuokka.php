<?php
function getVarauksetByLuokka($pdo, $data)
{
    if (empty($data['LuokkaID'])) {
        return ["success" => false, "message" => "kayttajaId is required"];
    }

    try {
        $LuokkaID = $data['LuokkaID'];

        $stmt = $pdo->prepare("SELECT * FROM varaukset WHERE LuokkaID = ?");
        $stmt->execute([$LuokkaID]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['success' => true, 'data' => $result];
    } catch (Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}
?>