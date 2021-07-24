<?php

require_once("../pdo/pdo_connection.php");try {

    $stmt = $db->query("SELECT COUNT(1) FROM histoire");
    header('Content-type: application/json');
    echo json_encode(['nbr_belles_histoires' => $stmt->fetchColumn()]);
} catch (Exception $e)
{
    var_dump($e->getMessage());
    var_dump($e->getLine());
    echo 'error';
}
