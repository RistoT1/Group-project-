<?php
function cancelVaraus($pdo, $input)
{
    if (empty($input['VarausID'])) {
        return ["success" => false, "message" => "VarausID is vaadittu"];
    }

    $varausID = (int) $input['VarausID'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM varaukset WHERE VarausID = ?");
        $stmt->execute([$varausID]);
        if ($stmt->rowCount() === 0) {
            return ["success" => false, "message" => "Varausta ei löydetty"];
        }
        $stmt = $pdo->prepare("UPDATE varaukset SET Tila = ? WHERE VarausID = ?");
        $stmt->execute(['peruttu', $varausID]);

        return ["success" => true, "message" => "Varaus peruttu onnistuneesti"];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Tietokantavirhe: " . $e->getMessage()];
    }
}
?>