<?php

require_once("../pdo/pdo_connection.php");
try {
    $stmt = $db->query("SELECT * FROM site_rattachement");
    header('Content-type: application/json');
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode( $result );
} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage() . '<br />';
    echo 'N° : ' . $e->getCode();
}

