<?php
function getAjat($pdo)
{
    $stmt = $pdo->query("SELECT * FROM varattavatajat");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return ['success' => true, 'data' => $result];
}
?>