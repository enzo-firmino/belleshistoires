<?php
session_start();
?>

<!DOCTYPE html>

<html lang="fr">
<head>
    <title>Formulaire des Belles Histoires</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/daterangepicker.css">
    <link href="css/bootstrap.min.css" rel="stylesheet"/>
</head>

<body>

<?php include("navbar.php"); ?>
<div id="carousel_form" class="carousel" data-interval="false" data-ride="carousel">
    <div class="carousel-inner">

        <!-- Renseignez vos informations -->
        <div class="carousel-item active">
            <span> Partie 1/6 </span>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 16.6%" aria-valuenow="16.6"
                     aria-valuemin="0"
                     aria-valuemax="100"></div>
            </div>
            <p>Les champs marqué d'un * sont obligatoires</p>
            <p id="date"></p>
            <script>
                n = new Date();
                y = n.getFullYear();
                m = n.getMonth() + 1;
                d = n.getDate();
                document.getElementById("date").innerHTML = 'Date de rédaction : ' + m + "/" + d + "/" + y;
            </script>
            <form id="form_vos_informations">
                <fieldset>
                    <legend>Qui saisit la belle histoire ?</legend>
                    <div class="row row_margin_bottom">
                        <div class="col-auto">
                            <label for="prenom_vos_infos" class="form-label">*Prénom</label>
                            <input type="text" class="form-control" name="prenom_vos_infos" id="prenom_vos_infos"
                                   required>
                        </div>
                        <div class="col-auto">
                            <label for="nom_vos_infos" class="form-label">*Nom</label>
                            <input type="text" class="form-control" name="nom_vos_infos" id="nom_vos_infos"
                                   required>
                        </div>
                        <div class="col-auto">
                            <label for="fonction_vos_infos" class="form-label">*Fonction</label>
                            <input type="text" class="form-control" name="fonction_vos_infos"
                                   id="fonction_vos_infos">
                        </div>
                    </div>
                    <div class="row row_margin_bottom">
                        <div class="col-auto">
                            <label for="email_vos_infos" class="form-label">*Email</label>
                            <input type="email" class="form-control" name="email_vos_infos" id="email_vos_infos"
                                   required>
                        </div>
                        <div class="col-auto">
                            <label for="telephone_vos_infos" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" name="telephone_vos_infos"
                                   id="telephone_vos_infos">
                        </div>
                        <div class="col-auto" style="display: flex;flex-direction: column;">
                            <label for="select_agences" class="form-label">*Site de rattachement</label>
                            <select class="custom-select" name="select_agences" id="select_agences">
                                <option selected value="">...</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-auto custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="checkbox_implique">
                        <label class="custom-control-label" for="checkbox_implique">
                            Etes-vous un acteur impliqué dans cette histoire ?
                        </label>
                    </div>
                </fieldset>
            </form>
            <button class="btn btn-primary btn_continuer" type="button">
                Continuer
            </button>
        </div>

        <!-- Période de la belle histoire -->
        <div class="carousel-item">
            <span> Partie 2/6 </span>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 33.3%" aria-valuenow="33.3"
                     aria-valuemin="0"
                     aria-valuemax="100"></div>
            </div>
            <form id="form_periode">
                <fieldset>
                    <legend>Période de la belle histoire</legend>
                    <p>Donnez la période approximative sur laquelle s'étend cette belle histoire </p>
                    <div class="col-4" style="padding-left: 0">
                        <input class="form-control" type="text" id="periode" value="" name="periode" readonly
                               placeholder="Période"/>
                    </div>
                </fieldset>
                <fieldset style="margin-top: 50px; margin-bottom: 50px">
                    <legend>Agence de référence pour cette belle histoire</legend>
                    <p>A quelle agence est rattachée cette belle histoire</p>
                    <div class="col-4" style="padding-left: 0;">
                        <select class="custom-select" name="select_agence_rattachement" id="select_agence_rattachement">
                            <option selected value="">...</option>
                        </select>
                    </div>
                </fieldset>
            </form>
            <button class="btn btn-primary btn_precedent" type="button">
                Précédent
            </button>
            <button class="btn btn-primary btn_continuer" id="btn_continuer_periode" type="button">
                Continuer
            </button>
        </div>

        <!--Titre et récit de la belle histoire -->
        <div class="carousel-item">
            <span> Partie 3/6 </span>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                     aria-valuemax="100"></div>
            </div>
            <form class="form-horizontal" id="form_titre_recit">
                <fieldset>
                    <legend>Titre de la belle histoire</legend>
                    <p>Donner un titre qui résume la belle histoire (Ex: recrutement d'un boucher en MRS sur Lille)</p>
                    <div class="form-group">
                        <input class="form-control input-md" name="titre" type="text" id="titre">
                    </div>
                    <legend>Racontez la belle histoire</legend>
                    <p> Quoi, Qui, Où, Quand, Comment</p>
                    <div class="form-group">
                        <textarea class="form-control" rows="8" style="max-height: 50%" name="recit"
                                  id="recit"></textarea>
                    </div>
                </fieldset>
            </form>
            <button class="btn btn-primary btn_precedent" type="button">
                Précédent
            </button>
            <button class="btn btn-primary btn_continuer" id="btn_continuer_titre_recit" type="button">
                Continuer
            </button>
        </div>

        <!-- Définissez la belle histoire -->
        <div class="carousel-item">
            <span> Partie 4/6 </span>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 66.6%" aria-valuenow="66.6"
                     aria-valuemin="0"
                     aria-valuemax="100"></div>
            </div>
            <legend class="legend">Informations complémentaires</legend>
            <div class="row_mots_publics">
                <div class="col_mots_publics">
                    <p style="margin: 0; font-weight: bold">Mots clés</p>
                    <div class="box_form_mots_publics">
                            <div class="col-auto">
                                <div class="row_input_ajouter">
                                    <input class="form-control" list="list_mots_cles_pred" id="mots_cles_input"
                                           name="mots_cles_input" placeholder="Tapez votre mot clé ou sélectionnez le dans la liste"/>
                                    <datalist id="list_mots_cles_pred">
                                    </datalist>
                                    <button class="btn btn-primary btn-sm btn_ajouter" id="btn_ajouter_mots_cles">
                                        Ajouter
                                    </button>
                                </div>
                        </div>
                        <div class="container_mots_publics" id="container_mots_cles">
                        </div>
                    </div>
                    <span style="margin-bottom: 40px; text-align: center">Appuyer sur un mot pour l'enlever</span>
                </div>
                <div class="col_mots_publics">
                    <p style="margin: 0; font-weight: bold">Publics concernés</p>
                    <div class="box_form_mots_publics">
                            <div class="col-auto">
                                <div class="row_input_ajouter">
                                    <input class="form-control" list="list_publics_cibles_pred"
                                           id="publics_cibles_input"
                                           name="publics_cibles_input" placeholder="Tapez votre public concerné ou sélectionnez le dans la liste"/>
                                    <datalist id="list_publics_cibles_pred">
                                    </datalist>
                                    <button class="btn btn-primary btn-sm btn_ajouter" id="btn_ajouter_publics_cibles">
                                        Ajouter
                                    </button>
                                </div>
                            </div>
                        <div class="container_mots_publics" id="container_publics_cibles">
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary btn_precedent" type="button">
                Précédent
            </button>
            <button class="btn btn-primary btn_continuer" type="button">
                Continuer
            </button>
        </div>

        <!-- Evolutions à venir -->
        <div class="carousel-item">
            <span> Partie 5/6 </span>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 83.3%" aria-valuenow="83.3"
                     aria-valuemin="0"
                     aria-valuemax="100"></div>
            </div>
            <form class="form-horizontal">
                <fieldset>
                    <legend>Perspectives d'évolutions de la belle histoire</legend>
                    <p>Comment peut évoluer cette histoire dans l'avenir ? (Ex : recrutement en CDI possible, entrée en
                        formation, etc...)
                    </p>
                    <div class="form-group">
                        <textarea class="form-control" rows="5" style="max-height: 50%" id="evolutions"></textarea>
                    </div>
                </fieldset>
            </form>
            <button class="btn btn-primary btn_precedent" type="button">
                Précédent
            </button>
            <button class="btn btn-primary btn_continuer" type="button">
                Continuer
            </button>
        </div>

        <!-- Personnes à contacter -->
        <div class="carousel-item">
            <span> Partie 6/6 </span>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100"
                     aria-valuemin="0"
                     aria-valuemax="100"></div>
            </div>
            <legend>Personnes à contacter :</legend>
            <div class="contact" id="contact_1">
                <div class="index_contact">Contact 1</div>
                <form class="form_contacts">
                    <fieldset>
                        <div class="row full_width">
                            <div class="col">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label class="form-label">*Type du contact</label>
                                        <select class="custom-select select_type_contact" name="select_type_contact">
                                            <option selected value="">...</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3 champ_type_1"></div>
                                    <div class="col-sm-3 champ_type_2"></div>
                                    <div class="col-sm-3 champ_type_3"></div>
                                </div>
                                <div class="row" style="justify-content: space-between">
                                    <div class="col-sm-3">
                                        <label class="form-label">*Prénom</label>
                                        <input type="text" class="form-control prenom_contact" name="prenom_contact">
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="form-label">*Nom</label>
                                        <input type="text" class="form-control nom_contact" name="nom_contact">
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control email_contact" name="email_contact">
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="form-label">Téléphone</label>
                                        <input type="tel" class="form-control telephone_contact"
                                               name="telephone_contact">
                                    </div>
                                </div>
                            </div>
                            <div class="container_commentaires">
                                <label>Commentaires</label>
                                <textarea class="form-control commentaires" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="row full_width align_center" style="margin-top: 10px">
                            <div class="col">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input en_charge" type="checkbox" id="en_charge1">
                                    <label class="custom-control-label" for="en_charge1">
                                        Ce contact est en charge du dossier ?
                                    </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input communiquer_externe" type="checkbox"
                                           id="communiquer_externe1">
                                    <label class="custom-control-label" for="communiquer_externe1">
                                        Est d'accord pour communiquer à l'externe ?
                                    </label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="text-right">
                <button class="btn btn-sm" id="btn_ajouter_contact">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor"
                         class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path d="M8 0a1 1 0 0 1 1 1v6h6a1 1 0 1 1 0 2H9v6a1 1 0 1 1-2 0V9H1a1 1 0 0 1 0-2h6V1a1 1 0 0 1 1-1z"/>
                    </svg>
                    Ajouter un nouveau contact
                </button>
            </div>
            <button class="btn btn-primary btn_precedent" type="button">
                Précédent
            </button>
            <button class="btn btn-lg btn-primary btn_finaliser" id="btn_finaliser" type="button">
                Finaliser
            </button>
        </div>
    </div>
</div>


<script src="js/jquery-3.6.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/moment.min.js"></script>
<script src="js/daterangepicker.js"></script>
<script src="js/ajouter.js"></script>
<script src="js/listeHistoires.js"></script>

<?php
if (isset($_GET['id'])) {
    echo '<script> prerempliFormulaire("' . $_GET['id'] . '") </script>';
} else if (isset($_SESSION['admin'])) {
    echo '<script> prerempliRedacteur("' . $_SESSION['admin']['email'] . '") </script>';
}
?>

</body>

</html>
