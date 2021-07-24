<?php

require_once("../pdo/pdo_connection.php");
try {
    $stmt = $db->query("SELECT * FROM public_cible");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode( $result );
} catch (Exception $e)
{
    echo 'error';

}
