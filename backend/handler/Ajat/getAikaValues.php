<?php
function getAikaValues()
{
    $startTime = "08:00:00";
    $endTime = "18:00:00";
    $intervals = [5]; // Tauot eri aikojen välillä minuutteina

    return ["success" => true, "startTime" => $startTime, "endTime" => $endTime, "intervals" => $intervals];
}
?>