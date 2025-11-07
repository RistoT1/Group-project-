<?php
function getVaraukset($pdo)
{
    $stmt = $pdo->query("SELECT * FROM varaukset");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return ['success' => true, 'data' => $result];
}
?>