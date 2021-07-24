<?php

$PARAM_hote = 'localhost'; // le chemin vers le serveur
$PARAM_port = '3306';
$PARAM_nom_bd = 'belles_histoires'; // le nom de votre base de données
$PARAM_utilisateur = 'root'; // nom d'utilisateur pour se connecter
$PARAM_mot_passe = 'password'; // mot de passe de l'utilisateur pour se connecter

try
{
    $db = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
//    $db->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES utf8");
    $db->exec("SET CHARACTER SET utf8");
}

catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage().'<br />';
    echo 'N° : '.$e->getCode();
}

