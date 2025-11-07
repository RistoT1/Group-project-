<?php
function getLuokat($pdo)
{
    $stmt = $pdo->query("SELECT * FROM luokat");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return ['success' => true, 'data' => $result];
}
?>