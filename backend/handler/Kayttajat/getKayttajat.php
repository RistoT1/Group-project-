<?php
function getKayttajat($pdo)
{
    $stmt = $pdo->query("SELECT * FROM kayttajat");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return ['success' => true, 'data' => $result];
}
?>