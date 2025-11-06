<?php
    function deleteKayttaja($pdo, $data)
    {
        if (!isset($data['KayttajaID'])) {
            return ["success" => false, "message" => "kayttajaId is required"];
        }

        $KayttajaID = $data['KayttajaID'];

        $stmt = $pdo->prepare("DELETE FROM kayttajat WHERE KayttajaID = ?");
        $stmt->execute([$KayttajaID]);

        if ($stmt->rowCount() > 0) {
            return ["success" => true, "message" => "Käyttäjä poistettu onnistuneesti"];
        } else {
            return ["success" => false, "message" => "Käyttäjää ei löydetty"];
        }
    }
?>