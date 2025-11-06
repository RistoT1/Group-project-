<?php
    function deleteKayttaja($pdo, $data)
    {
        if (!isset($data['kayttajaId'])) {
            return ["success" => false, "message" => "kayttajaId is required"];
        }

        

        $kayttajaId = $data['kayttajaId'];

        $stmt = $pdo->prepare("DELETE FROM kayttajat WHERE id = ?");
        $stmt->execute([$kayttajaId]);

        if ($stmt->rowCount() > 0) {
            return ["success" => true, "message" => "Käyttäjä poistettu onnistuneesti"];
        } else {
            return ["success" => false, "message" => "Käyttäjää ei löydetty"];
        }
    }
?>