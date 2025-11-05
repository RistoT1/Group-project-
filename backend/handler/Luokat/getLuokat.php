<?php
function getLuokat($pdo)
{
    $stmt = $pdo->query("SELECT * FROM luokat");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>