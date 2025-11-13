<?php
function getKayttajaVaraukset($pdo,$decodedToken)
{
    $KayttajaID = (int) $decodedToken->sub;

    try {

        $stmt = $pdo->prepare("SELECT * FROM varaukset WHERE KayttajaID = ?");
        $stmt->execute([$KayttajaID]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['success' => true, 'data' => $result];
    } catch (Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}
?>