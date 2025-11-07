<?php
    function deleteLuokka($pdo, $input)
    {
        if (!isset($input['LuokkaID'])) {
            return ["success" => false, "message" => "LuokkaID  tarvitaan"];
        }

        $LuokkaID = $input['LuokkaID'];

        $stmt = $pdo->prepare("DELETE FROM luokat WHERE LuokkaID = ?");
        $stmt->execute([$LuokkaID]);

        if ($stmt->rowCount() > 0) {
            return ["success" => true, "message" => "Luokka poistettu onnistuneesti"];
        } else {
            return ["success" => false, "message" => "Luokkaa ei löydetty"];
        }
    }
?>