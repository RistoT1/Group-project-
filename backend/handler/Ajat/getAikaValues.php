<?php
function getAikaValues()
{
    $startTime = "08:00:00";
    $endTime = "18:00:00";
   

    return [
        "success" => true,
        "data" => [
            "startTime" => $startTime,
            "endTime" => $endTime,
        ]
    ];
}
?>