<?php
function getKayttajat($pdo)
{
    $stmt = $pdo->query("SELECT * FROM kayttajat");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>