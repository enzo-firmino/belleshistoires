<?php
session_start();
?>

<a id="btn_retour" onclick="retour()"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="white" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
        <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1z"/>
    </svg></a>
<div id="histoire_detail">
    <?php
    if (isset($_POST['histoire'])) {
    ?>
    <div class="titre text-center" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 25px ">
        <?php
        echo $_POST['histoire']['titre'];
        ?>
    </div>
    <div>
        <span style="font-weight: 500">Période : </span><span><?php echo $_POST['debutPeriode'] ?> - <?php echo $_POST['finPeriode'] ?></span>
    </div>
    <div>
        <span style="font-weight: 500">Agence de rattachement : </span><span> <?php echo $_POST['agence'] ?></span>
    </div>
    <div>
        <span style="font-weight: 500">Email rédacteur : </span><span><a href="mailto:<?php echo $_POST['histoire']['email_redacteur'] ?>"><?php echo $_POST['histoire']['email_redacteur'] ?></a></span>
    </div>
    <div class="separateur"></div>
    <div class="margin_bottom"><span style="font-weight: 500">Mots clés : </span><?php echo $_POST['motsCles'] ?></div>
    <div class="margin_bottom"><span
                style="font-weight: 500"> Publics cibles : </span><?php echo $_POST['publicsCibles'] ?></div>
    <div class="separateur"></div>
    <div class="margin_bottom">
        <span style="font-weight: 500"> Histoire : Qui – Quoi- Quand- Où - Comment ? </span>
        <br><br>
        <?php
        echo '<div style="width: 90%; margin: auto">' . $_POST['histoire']['recit'] . '</div>';
        ?>
    </div>
    <div class="separateur"></div>
    <div class="margin_bottom">
            <span style="font-weight: 500">
            Evolutions possibles de la belle histoire :
            </span>
        <?php
        echo $_POST['histoire']['evolutions'];
        ?>
    </div>
    <div class="separateur"></div>
    <div style="margin-top: 25px">
            <span style="font-weight: 500">
            Personnes à contacter :
            </span>
        <br><br>
        <?php
        foreach ($_POST['contacts'] as $contact) {
            $en_charge = '';
            $commentaires = '';
            $email = '<a href="mailto:' . $contact['email'] . '">' . $contact['email'] . '</a>';
            $telephone = '<a href="tel:' . $contact['telephone'] . '">' . $contact['telephone'] . '</a>';
            if ($contact['en_charge'] == 1) {
                $en_charge = '<div> Ce contact est en charge du dossier </div>';
            }
            if ($contact['communiquer_externe']) {
                $communiquer_externe = '<div> Ce contact est d\'accord pour communiquer à l\'externe </div>';
            } else {
                $communiquer_externe = '<div> Ce contact n\'est pas d\'accord pour communiquer à l\'externe </div>';
            }

            if ($contact['commentaires'] != null) {
                $commentaires = '<div>Commentaires : ' . $contact['commentaires'] . '</div>';
            }
            echo '<div class="contact_histoire" id="' . $contact['id'] . '"> 
                        <script>  getHtmlTypeContact(' . $contact['id_type'] . ', "' . $contact['complement_1'] . '",' . $contact['id'] . ')</script>
                        <div>' . $contact['prenom'] . ' ' . $contact['nom'] . '</div>
                        <div>' . $email . ', ' . $telephone . '</div>'
                . $en_charge . $communiquer_externe . $commentaires . '
                    </div>';
        }
        echo '</div>';
        if (isset($_POST['majs']) && count($_POST['majs']) > 0) {
            echo '<a class="btn btn-light" id="toggleMajs"> Afficher les mises à jour </a><div id="majs" style="display: none; padding-left: 20px">';

            foreach ($_POST['majs'] as $maj) {
                echo('Mis à jour le : ' . $maj['date_maj'] . ' par : ' . $maj['email']);
                echo '<br>';

            }
            echo '</div>';
        }


        echo '<div style="display: flex; justify-content: space-around; margin: 40px">
                 <button class="btn btn-primary btn" id="btn_modifier_detail">
                 Modifier</button>
                 <button class="btn btn-primary btn" id="btn_exporter_detail">
                     Exporter en pdf
                 </button>';

        if (isset($_SESSION['admin']) && $_SESSION['admin']['super_admin'] == 1) {
            echo ' <button class="btn btn-danger btn" id="btn_supprimer_detail">
                            Supprimer cette belle histoire
                        </button>';
        }

        echo '</div>';

        if (isset($_SESSION['admin'])) {
            echo '<p>Lien de modification : <a href="ajouter.php?id=' . $_POST['histoire']['id'] . '" style="margin-top: 30px;">http://belleshistoires/ajouter.php?id=' . $_POST['histoire']['id'] . '</a>';
        }

        } else {
            ?>
            <span>
        Pas de belles histoires
    </span>
            <?php
        }
        ?>
    </div>
