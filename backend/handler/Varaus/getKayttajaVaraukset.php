<?php
function getKayttajaVaraukset($pdo, $data)
{
    if (empty($data['KayttajaID'])) {
        return ["success" => false, "message" => "kayttajaId is required"];
    }

    try {
        $KayttajaID = $data['KayttajaID'];

        $stmt = $pdo->prepare("SELECT * FROM varaukset WHERE KayttajaID = ?");
        $stmt->execute([$KayttajaID]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['success' => true, 'data' => $result];
    } catch (Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}
?>