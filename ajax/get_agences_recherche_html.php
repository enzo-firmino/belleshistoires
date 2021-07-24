<?php

require_once("../pdo/pdo_connection.php");
try {
    $stmt = $db->query("SELECT * FROM dt ORDER BY nom");
    $list_dt = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($list_dt as $dt) {
        echo "<option class='dt' value=" . $dt['id'] . ">" . $dt['nom'] . "</option>\n";
        $stmt = $db->query("SELECT * FROM dtd WHERE id_dt =" . $dt['id']);
        $list_dtd = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($list_dtd as $dtd) {
            echo "<option class='dtd' value=" . $dtd['id'] . ">   " . $dtd['nom'] . "</option>\n";
            $stmt = $db->query("SELECT * FROM site_rattachement WHERE id_dtd = ".$dtd['id']."  ORDER BY nom ");
            while ($row = $stmt->fetch()) {
                echo "<option class='site_rattachement' value=" . $row['id'] . ">" . $row['nom'] . "</option>\n";
            }
        }
    }

} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage() . '<br />';
    echo 'N° : ' . $e->getCode();
}

