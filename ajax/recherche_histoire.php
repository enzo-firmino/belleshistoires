<?php

require_once("../pdo/pdo_connection.php");

try {

    $scores = array();
    $ids_histoire = array();


    $sql = 'SELECT *, 
            MATCH(titre) AGAINST(:text) as score_titre, 
            MATCH(recit) AGAINST(:text) as score_recit, 
            MATCH(evolutions) AGAINST(:text) as score_evolutions 
            FROM histoire WHERE fin_periode > :fin_periode';

    if (isset($_POST['id_agence'])) {
        if ($_POST['class_agence'] == 'site_rattachement') {
            $sql .= ' AND id_agence_rattachement = :id_agence_rattachement';
        } else if ($_POST['class_agence'] == 'dtd') {
            $sql .= ' AND id_agence_rattachement IN (
             SELECT id FROM site_rattachement WHERE id_dtd = :id_agence_rattachement
             )';
        } else {
            $sql .= ' AND id_agence_rattachement IN ( 
            SELECT id FROM site_rattachement WHERE id_dtd IN (
            SELECT id FROM dtd WHERE id_dt = :id_agence_rattachement )
            )';
        }
    }

    $sql .= ' ORDER BY fin_periode DESC';

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':text', $_POST['text']);

    if (isset($_POST['id_agence'])) {
        $stmt->bindValue(':id_agence_rattachement', $_POST['id_agence']);
    }

    $stmt->bindValue(':fin_periode', date('Y-m-d', strtotime($_POST['dateFin'])));

    $stmt->execute();
    $result_histoires = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result_histoires as $histoire) {

        //Fetch le score des contacts
        $stmt = $db->prepare('SELECT MATCH(nom, prenom, email, telephone) AGAINST(:text) as score FROM contact WHERE id_histoire = :id_histoire');
        $stmt->bindValue(':id_histoire', $histoire['id']);
        $stmt->bindValue(':text', $_POST['text']);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $score_contact = 0;

        foreach ($result as $contact) {
            $score_contact += $contact['score'];
        }

        // Fetch les mots cles de l'histoire
        $stmt = $db->prepare('SELECT id_mot_cle FROM histoire_mot_cle WHERE id_histoire = :id_histoire');
        $stmt->bindValue(':id_histoire', $histoire['id']);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result_mot_cle = array();
            if (count($result) > 0) {
                //Construit la liste des mots clés en string
                $ids_mots_cles = array();
                foreach ($result as $id_mot_cle) {
                array_push($ids_mots_cles, $id_mot_cle['id_mot_cle']);
            }
            $idsStr = '(';
            $idsStr .= implode(',', $ids_mots_cles);
            $idsStr .= ')';

            // Fetch le score des mots clés
            $stmt = $db->prepare('SELECT MATCH(mot) AGAINST(:text) as score
                            FROM mot_cle
                            WHERE id IN '. $idsStr);
            $stmt->bindValue(':text', $_POST['text']);
            $stmt->execute();
            $result_mot_cle = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }


        // Fetch les publics cibles de l'histoire
        $stmt = $db->prepare('SELECT id_public_cible FROM histoire_public_cible WHERE id_histoire = :id_histoire');
        $stmt->bindValue(':id_histoire', $histoire['id']);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


        $result_public_cible = array();
        if (count($result) > 0) {
            //Construit la liste des publics cibles en string
            $ids_publics_cibles = array();
            foreach ($result as $id_public_cible) {
                array_push($ids_publics_cibles, $id_public_cible['id_public_cible']);
            }
            $idsStr = '(';
            $idsStr .= implode(',', $ids_publics_cibles);
            $idsStr .= ')';

            // Fetch le score des publics cibles
            $stmt = $db->prepare('SELECT MATCH(public_cible) AGAINST(:text) as score
                            FROM public_cible
                            WHERE id IN '. $idsStr);
            $stmt->bindValue(':text', $_POST['text']);
            $stmt->execute();
            $result_public_cible = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $score_mot_cle = 0;
        $score_public_cible = 0;

        foreach ($result_mot_cle as $mot_cle) {
            $score_mot_cle = $score_mot_cle + $mot_cle['score'];
        }

        foreach ($result_public_cible as $public_cible) {
            $score_public_cible = $score_public_cible + $public_cible['score'];
        }

        $score = 3*$histoire['score_titre'] + $histoire['score_recit'] + $histoire['score_evolutions'] + 2*$score_mot_cle + 2*$score_public_cible + $score_contact;


        if ($score > 0 || $_POST['text'] == '') {
            array_push($scores, $score);
            array_push($ids_histoire, $histoire['id']);
        }

        unset($histoire);
    }

    if ($_POST['text'] != '') {
        array_multisort($scores, SORT_DESC, $ids_histoire, SORT_DESC);
    }

    $response = array();
    foreach ($ids_histoire as $id_histoire) {
        $key = array_search($id_histoire, array_column($result_histoires, 'id'));
        array_push($response, $result_histoires[$key]);
    }

    echo(json_encode($response));



//        $sql = 'SELECT *,
//            MATCH(titre) AGAINST(:text)  AS  score_titre,
//            MATCH(recit) AGAINST(:text) AS score_recit,
//            MATCH(evolutions) AGAINST(:text) AS score_evolutions
//            FROM histoire
//            WHERE
//            (MATCH(titre) AGAINST(:text) OR
//            MATCH(recit) AGAINST(:text) OR
//            MATCH(evolutions) AGAINST(:text) )';
//
//    if (isset($_POST['siteRattachement'])) {
//        $sql .= ' AND id_agence = :id_agence';
//    }
//
//    if (isset($_POST['dateDebut']) && isset($_POST['dateFin'])) {
//        $sql .= ' AND date_redaction BETWEEN :start_date AND :end_date';
//    }
//
//
//    if (isset($_POST['idsMotsCles'])) {
//        $idsStr = '(';
//        $idsStr .= implode(',', $_POST['idsMotsCles']);
//        $idsStr .= ')';
//        $sql .= ' AND id IN (SELECT id_histoire
//        FROM histoire_mot_cle
//        WHERE id_mot_cle IN ' . $idsStr . ')';
//    }
//
//    if (isset($_POST['idsPublicsCibles'])) {
//        $idsStr = '(';
//        $idsStr .= implode(',', $_POST['idsPublicsCibles']);
//        $idsStr .= ')';
//        $sql .= ' AND id IN (SELECT id_histoire
//        FROM histoire_public_cible
//        WHERE id_public_cible IN ' . $idsStr . ')';
//    }
//
//    if (isset($_POST['contact'])) {
//        $sql .= ' AND id IN (SELECT id_histoire
//        FROM contact
//        WHERE nom LIKE :contact OR
//        prenom LIKE :contact OR
//        email LIKE :contact OR
//        telephone LIKE :contact)';
//    }
//
//    if (isset($_POST['text'])) {
//        $sql .= ' ORDER BY (score_titre+score_recit*0.5+score_evolutions*0.1) DESC, date_redaction DESC;';
//    } else {
//        $sql .= ' ORDER BY date_redaction DESC;';
//    }
//
//    print($sql);
//
//    $stmt = $db->prepare($sql);
//
//    if (isset($_POST['text'])) {
//        $stmt->bindValue(':text', $_POST['text']);
//    }
//
//    if (isset($_POST['siteRattachement'])) {
//        $stmt->bindValue(':id_agence', $_POST['siteRattachement']);
//    }
//
//    if (isset($_POST['dateDebut']) && isset($_POST['dateFin'])) {
//        $stmt->bindValue(':start_date', date('Y-m-d', strtotime($_POST['dateDebut'])));
//        $stmt->bindValue(':end_date', date('Y-m-d', strtotime($_POST['dateFin'])));
//    }
//
//    if (isset($_POST['contact'])) {
//        $stmt->bindValue(':contact', '%'.$_POST['contact'].'%');
//    }
//
//
//    $stmt->execute();
//    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//    echo json_encode($result);

} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage() . '<br />';
    echo 'Ligne : ' . $e->getLine() . '<br />';
    echo 'N° : ' . $e->getCode();
}


