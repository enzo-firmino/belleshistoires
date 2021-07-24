<?php

require_once("../pdo/pdo_connection.php");
try {
    $stmt = $db->query("SELECT nom FROM dtd");
    $list_dtd = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $previousId = -1;
    $stmt = $db->query("SELECT * FROM site_rattachement WHERE id_dtd IS NOT NULL ORDER BY id_dtd, id_dt, nom ");
    while ($row = $stmt->fetch()) {
        if ($row['id_dtd'] != $previousId) {
            echo "</optgroup>";
            echo '<optgroup label="' . array_shift($list_dtd)['nom'] . '">';
            $previousId = $row['id_dtd'];
        }
        echo "<option value=" . $row['id'] . ">" . $row['nom'] . "</option>\n";
        if ($row['id_dtd'] == null && $row['id_dt'] == null) {
            echo "<optgroup label=DT>";
        }
    }
    echo "</optgroup>";


} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage() . '<br />';
    echo 'N° : ' . $e->getCode();
}

