<?php

require_once("../pdo/pdo_connection.php");//Fetch les mise à jour where l'id histoire est passé en paramètre
try {

    $stmt = $db->prepare('SELECT * FROM mise_a_jour WHERE id_histoire = :id_histoire ORDER BY date_maj DESC');
    $stmt->bindValue(':id_histoire', $_GET['id_histoire'], PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode( $result );
} catch (Exception $e)
{
    var_dump($e->getMessage());
    var_dump($e->getLine());
    echo 'error';
}
