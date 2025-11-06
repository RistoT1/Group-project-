<?php
function getVaraukset($pdo)
{
    $stmt = $pdo->query("SELECT * FROM varaukset");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>