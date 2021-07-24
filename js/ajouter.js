let idMotsClesSelectedPredefini = []; // liste des id des mots clés prédéfinis sélectionnés
let motsClesPerso = []; // liste des values des mots clés perso
let idPublicsCiblesSelectedPredefini = []; // liste des id des publics cibles prédéfinis sélectionnés
let publicsCiblesPerso = []; // liste des values des publics cibles perso
let typesContactFetched = []; // liste des types de contact récupérée depuis la bd
let htmlOptionsTypesContact = ''; // Html contenant la liste des options des types de contact
let htmlOptionsSiteRattachement = ''; // Html contenant la liste des options des sites de rattachement
let htmlOptionsAgenceRattachement = ''; // Html contenant la liste des options des sites de rattachement
let debutPeriode; // Date du début de la période
let finPeriode; // Date de la fin de la période
let emailRedacteur;
let idHistoirePrerempli; //id de l'histoire si modification
let idSiteRattachementPrerempli; // id du site de rattachement si modification
let idAgenceRattachementPrerempli; // id de l'agence de rattachement si modification
let motsClesPreremplis; // liste histoire mots clés si modification
let publicsCiblesPreremplis; // liste histoire publics cibles si modification
let contactsPrerempli; // liste contacts si modification
let loading = false;

// Function appelée lors du changement du type de contact, rajoute un champ en fonction du type selectionné
function onChangeTypeContact(idType, id) {
    let indexContact = $('#contact_' + id + ' .index_contact');

    if (idType === '') {
        $("#contact_" + id + " .complement_1").empty();
        $("#contact_" + id + " .complement_2").empty();
        $("#contact_" + id + " .complement_3").empty();
        indexContact.text('Contact ' + id);
        return;
    }

    let typeContact = (typesContactFetched.filter(type_contact => type_contact.id === idType))[0];

    indexContact.text('Contact ' + id + ' : ' + typeContact.intitule);

    if (typeContact.hasOwnProperty('complement_1')) {
        if (typeContact.complement_1 === 'Site de rattachement') {
            $("#contact_" + id + " .champ_type_1").html('<label for="select_agences" class="form-label">*Site de rattachement</label>' +
                '<select class="custom-select complement_1" name="complement_1">' +
                '<option selected value="">...</option>' +
                htmlOptionsSiteRattachement +
                '</select>');
        } else {
            $("#contact_" + id + " .champ_type_1").html('<label class="form-label">' + typeContact.complement_1 + '</label>\n' +
                '                                <input type="text" class="form-control complement_1" name="complement_1">');
        }
    }

    if (typeContact.hasOwnProperty('complement_2')) {
        $("#contact_" + id + " .champ_type_2").html('<label class="form-label">' + typeContact.complement_2 + '</label>\n' +
            '                                <input type="text" class="form-control complement_2" name="complement_2">');
    }

    if (typeContact.hasOwnProperty('complement_3')) {
        $("#contact_" + id + " .champ_type_3").html('<label class="form-label">' + typeContact.complement_3 + '</label>\n' +
            '                                <input type="text" class="form-control complement_3" name="complement_3">');
    }
}

// Ajoute le mot clé au div contenant tous les mots clés sélectionnés
function ajouterMotCle(id, motCle, predefini) {
    $('#container_mots_cles').append("<p class='mots_cles' id='" + id + "' onclick='removeMotCle(this, " + predefini + ")'>" + motCle + "</p>");
    if (predefini) {
        $('#list_mots_cles_pred').find('option[value="' + escapeQuotes(motCle) + '"]').remove();
        idMotsClesSelectedPredefini.push(id);
    } else {
        motsClesPerso.push(motCle);
    }
}


// Ajoute le public cible au div contenant tous les publics cibles sélectionnés
function ajouterPublicCible(id, publicCible, predefini) {
    $('#container_publics_cibles').append("<p class='publics_cibles' id='" + id + "' onclick='removePublicCible(this, " + predefini + ")'>" + publicCible + "</p>");
    if (predefini) {
        $('#list_publics_cibles_pred').find('option[value="' + escapeQuotes(publicCible) + '"]').remove();
        idPublicsCiblesSelectedPredefini.push(id);
    } else {
        publicsCiblesPerso.push(publicCible);
    }
}


// Supprime le mot clé du div html et de la liste
function removeMotCle(selector, predefini) {
    let motCle = $(selector).text();
    if (predefini) {
        let idMotCle = $(selector).attr('id');
        idMotsClesSelectedPredefini = idMotsClesSelectedPredefini.filter(element => element != idMotCle);
        $('#list_mots_cles_pred').append("<option data-value=" + idMotCle + " value='" + escapeQuotes(motCle) + "'></option>");
    } else {
        motsClesPerso = motsClesPerso.filter(element => element !== motCle);
    }
    $(selector).remove();
}

// Supprime le public cible du div html et de la liste
function removePublicCible(selector, predefini) {
    let publicCible = $(selector).text();
    if (predefini) {
        let idPublicCible = $(selector).attr('id');
        idPublicsCiblesSelectedPredefini = idPublicsCiblesSelectedPredefini.filter(element => element != idPublicCible);
        $('#list_publics_cibles_pred').append("<option data-value=" + idPublicCible + " value='" + escapeQuotes(publicCible) + "'></option>");
    } else {
        publicsCiblesPerso = publicsCiblesPerso.filter(element => element !== publicCible);
    }
    $(selector).remove();
}


//Check si l'input existe dans la datalist
function checkExists(inputValue, listSelector) {
    let i;
    let flag = -1;
    let motCle = '';
    for (i = 0; i < listSelector.children().length; i++) {
        // Pour éviter les doublons on enlève les accents et majuscules avant de tester l'égalité
        let str1 = inputValue.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
        let str2 = listSelector.children()[i].value.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
        if (str1 === str2) {
            flag = listSelector.find('option[value="' + escapeQuotes(listSelector.children()[i].value) + '"]').data('value');
            motCle = listSelector.children()[i].value;
        }
    }
    return [flag, motCle];
}

// Supprime le contact
function removeContact(id) {
    $('#contact_' + id).remove();
    for (let i = id; i <= $('.contact').length; i++ ) {
       let newId =  i;
       let oldId = i+1;
       $('#contact_' + oldId).attr("id", 'contact_' + newId);
       $('#contact_' + newId + ' .index_contact').text('Contact '+newId);
        let select = $("#contact_" + newId + " .select_type_contact").first();
        select.unbind();
        select.change(function () {
            onChangeTypeContact(this.value, newId);
        });
        let btnSupprimerContact = $('#contact_' + newId + ' .btn_supprimer_contact');
        btnSupprimerContact.unbind();
        btnSupprimerContact.click(function () {
            removeContact(newId);
        });
    }
}

// Formate une date au format AAAA-MM-DD au format DD/MM/AAAA
function formatDateToDDMMA(date) {
    if (date === undefined) {
        return null;
    }
    let tabDate = date.split('-');
    tabDate.reverse();
    return tabDate.join('/');
}

// Prerempli le formulaire avec la belle histoire a modifier
function prerempliFormulaire(idHistoire) {
   idHistoirePrerempli = idHistoire;
}

// Prerempli les champs redacteur avec la personne connectée
function prerempliRedacteur(email) {
    emailRedacteur = email;
}

// Ajoute les champs pour un nouveau contact
function ajouterContact() {
    let idCourant = $('.contact').length;
    let newId = idCourant + 1;
    $("<div class=\"contact\" id='contact_" + newId + "'>\n" +
        "                <div class=\"row full_width space_between\" style=\"margin-left: 5px \">\n" +
        "                       <div class='index_contact'>Contact " + newId + "</div>" +
        "                       <div class='btn_supprimer_contact'><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"15\" height=\"15\" fill=\"currentColor\" class=\"bi bi-x-lg\" viewBox=\"0 0 16 16\">\n" +
        "  <path d=\"M1.293 1.293a1 1 0 0 1 1.414 0L8 6.586l5.293-5.293a1 1 0 1 1 1.414 1.414L9.414 8l5.293 5.293a1 1 0 0 1-1.414 1.414L8 9.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L6.586 8 1.293 2.707a1 1 0 0 1 0-1.414z\"/>\n" +
        "</svg>Supprimer ce contact</div>" +
        "                </div>\n" +
        "                <form class=\"form_contacts\">\n" +
        "                    <fieldset>\n" +
        "                        <div class=\"row full_width\">\n" +
        "                            <div class=\"col\">\n" +
        "                               <div class=\"row\">\n" +
        "                                   <div class=\"col-sm-3\">\n" +
        "                                       <label class=\"form-label\">*Type du contact</label>\n" +
        "                                       <select class=\"custom-select select_type_contact\" name=\"select_type_contact\">\n           " +
        "                                           <option selected value=" + '' + ">...</option>\n" + htmlOptionsTypesContact +
        "                                       </select>\n" +
        "                                   </div>\n" +
        "                                   <div class=\"col-sm-3 champ_type_1\"></div>\n" +
        "                                   <div class=\"col-sm-3 champ_type_2\"></div>\n" +
        "                                   <div class=\"col-sm-3 champ_type_3\"></div>\n" +
        "                               </div>\n" +
        "                               <div class=\"row\" style=\"justify-content: space-between\">\n" +
        "                                   <div class=\"col-sm-3\">\n" +
        "                                       <label class=\"form-label\">*Prénom</label>\n" +
        "                                       <input type=\"text\" class=\"form-control prenom_contact\" name=\"prenom_contact\">\n" +
        "                                   </div>\n" +
        "                                   <div class=\"col-sm-3\">\n" +
        "                                       <label class=\"form-label\">*Nom</label>\n" +
        "                                       <input type=\"text\" class=\"form-control nom_contact\" name=\"nom_contact\">\n" +
        "                                   </div>\n" +
        "                                   <div class=\"col-sm-3\">\n" +
        "                                       <label class=\"form-label\">Email</label>\n" +
        "                                       <input type=\"text\" class=\"form-control email_contact\" name=\"email_contact\">\n" +
        "                                   </div>\n" +
        "                                   <div class=\"col-sm-3\">\n" +
        "                                       <label class=\"form-label\">Téléphone</label>\n" +
        "                                       <input type=\"text\" class=\"form-control telephone_contact\" name=\"telephone_contact\">\n" +
        "                                   </div>\n" +
        "                               </div>\n" +
        "                           </div>\n" +
        "                           <div class=\"container_commentaires\" style=\"width: auto;\"> \n" +
        "                               <label>Commentaires</label> \n" +
        "                               <textarea class=\"form-control commentaires\" rows=\"4\"></textarea> \n" +
        "                           </div> \n" +
        "                        </div> \n" +
        "                        <div class=\"row full_width align_center\" style=\"margin-top: 10px\">\n" +
        "                            <div class=\"col\">\n" +
        "                               <div class=\"custom-control custom-checkbox\">\n" +
        "                                    <input class=\"custom-control-input en_charge\" type=\"checkbox\" id=\"en_charge" + newId + "\">\n" +
        "                                        <label class=\"custom-control-label\" for=\"en_charge" + newId + "\">\n" +
        "                                            Ce contact est en charge du dossier ?\n" +
        "                                        </label>\n" +
        "                               </div>\n" +
        "                            </div>\n" +
        "                            <div class=\"col\">\n" +
        "                               <div class=\"custom-control custom-checkbox\">\n" +
        "                                    <input class=\"custom-control-input communiquer_externe\" type=\"checkbox\" id=\"communiquer_externe" + newId + " \">\n" +
        "                                        <label class=\"custom-control-label\" for=\"communiquer_externe" + newId + " \">\n" +
        "                                            Est d'accord pour communiquer à l'externe ?\n" +
        "                                        </label>\n" +
        "                               </div>\n" +
        "                            </div>\n" +
        "                        </div>\n" +
        "                    </fieldset>\n" +
        "                </form>\n" +
        "            </div>").insertAfter(".contact#contact_" + idCourant);
    let select = $("#contact_" + newId + " .select_type_contact").first();
    select.val('');
    $("#contact_" + newId + " .complement_1").empty();
    $("#contact_" + newId + " .complement_2").empty();
    $("#contact_" + newId + " .complement_3").empty();
    select.change(function () {
        onChangeTypeContact(this.value, newId);
    });
    $("#contact_" + newId + " .btn_supprimer_contact").click(function () {
        removeContact(newId);
    });
}

jQuery(document).ready(async function ($) {


    if (idHistoirePrerempli !== undefined) {
      await $.post(
            'ajax/get_histoire_with_id.php',
            {id: idHistoirePrerempli},
            null,
            'json'
        )
            .done(function (response) {
                    if (response.length > 0) {
                        let histoire = response[0];
                        $('#carousel_form').before("<div class='modif_info'>Modification de la belle histoire : " + histoire.titre + "</div>");
                        $('#prenom_vos_infos').val(histoire.prenom_redacteur);
                        $('#nom_vos_infos').val(histoire.nom_redacteur);
                        $('#email_vos_infos').val(histoire.email_redacteur);
                        $('#telephone_vos_infos').val(histoire.telephone_redacteur);
                        $('#fonction_vos_infos').val(histoire.fonction_redacteur);
                        idSiteRattachementPrerempli = histoire.id_agence;
                        idAgenceRattachementPrerempli = histoire.id_agence_rattachement;
                        debutPeriode = histoire.debut_periode;
                        finPeriode = histoire.fin_periode;
                        $('#recit').val(histoire.recit);
                        $('#titre').val(histoire.titre);
                        $('#evolutions').val(histoire.evolutions);
                    }
                }
            )
            .fail(function (xhr, status, error) {
                console.log('err', xhr);
                console.log('err', error);
                console.log('err', status);
            });
    }

    if (emailRedacteur !== undefined) {
        $('#email_vos_infos').val(emailRedacteur);
    }

    // Fetch liste sites de rattachement
    $.get(
        "ajax/get_sites_rattachements_html.php",
        function (response) {
            htmlOptionsSiteRattachement = response;
            $("#select_agences").append(response);
            if (idSiteRattachementPrerempli !== null) {
                $("#select_agences option[value='" + idSiteRattachementPrerempli + "']").attr("selected", "selected");
            }
        },
        "html"
    ).fail(function (xhr, status, error) {
        console.log('err', xhr);
        console.log('err', error);
        console.log('err', status);
    });

    $.get(
        "ajax/get_agences_rattachement_html.php",
        function (response) {
            htmlOptionsAgenceRattachement = response;
            $('#select_agence_rattachement').append(response);
            if (idAgenceRattachementPrerempli !== null) {
                $("#select_agence_rattachement option[value='" + idAgenceRattachementPrerempli + "']").attr("selected", "selected");
            }
        },
        "html"
    ).fail(function (xhr, status, error) {
        console.log('err', xhr);
        console.log('err', error);
        console.log('err', status);
    });

    // Fetch liste mots clés et ajoute la au select
    $.get(
        "ajax/get_all_mots_cles.php",
        async function (response) {
            let listMotsClesPred = '';
            for (let motClePred of response) {
                listMotsClesPred = listMotsClesPred + "<option data-value=" + motClePred.id + " value='" + escapeQuotes(motClePred.mot) + "'></option>"
            }
            $("#list_mots_cles_pred").append(listMotsClesPred);

            if (idHistoirePrerempli !== undefined) {
                motsClesPreremplis = await getMotsClesHistoire(idHistoirePrerempli);
                if (motsClesPreremplis.length > 0) {
                    // Pour chaque histoires mots clés préremplis le rajoute au div et a la liste
                    for (let motCle of motsClesPreremplis) {
                        let motClePredefini = response.filter(element => element.id === motCle.id_mot_cle);
                        if (motClePredefini.length > 0) {
                            let motCleVal = motClePredefini[0].mot;
                            ajouterMotCle(motCle.id_mot_cle, motCleVal, true);
                        }
                    }
                }
            }
        },
        "json"
    );

    // Fetch liste publics cibles prédéfinis et ajoute la au select
    $.get(
        "ajax/get_all_publics_cibles.php",
        async function (response) {
            let listPublicsCiblesPred = '';
            for (let publicCiblePred of response) {
                listPublicsCiblesPred = listPublicsCiblesPred + "<option data-value=" + publicCiblePred.id + " value='" + escapeQuotes(publicCiblePred.public_cible) + "'></option>"
            }
            $("#list_publics_cibles_pred").append(listPublicsCiblesPred);

            if (idHistoirePrerempli !== undefined) {
                publicsCiblesPreremplis = await getPublicsCiblesHistoire(idHistoirePrerempli);
                if (publicsCiblesPreremplis.length > 0) {
                    // Pour chaque publics cibles préremplis, s'il est prédéfini, le rajoute au div et a la liste
                    for (let publicCible of publicsCiblesPreremplis) {
                        let publicCiblePredefini = response.filter(element => element.id === publicCible.id_public_cible);
                        if (publicCiblePredefini.length > 0) {
                            let publicCibleVal = publicCiblePredefini[0].public_cible;
                            ajouterPublicCible(publicCible.id_public_cible, publicCibleVal, true);
                        }
                    }
                }
            }
        },
        "json"
    );

    // Fetch liste types contacts et ajoute la au select
    $.get(
        "ajax/get_types_contact.php",
        async function (response) {
            for (let type of response) {
                htmlOptionsTypesContact = htmlOptionsTypesContact + "<option value=" + type.id + ">" + type.intitule + "</option>"
            }
            $("#contact_1 .select_type_contact").append(htmlOptionsTypesContact);
            typesContactFetched = response;

            // Ajoute les contacts préremplis
            if (idHistoirePrerempli !== undefined) {
                contactsPrerempli = await getContacts(idHistoirePrerempli);
                for (let [index, contact] of contactsPrerempli.entries()) {
                    if (index > 0) {
                        ajouterContact();
                    }
                    let idContact = index + 1;
                    $('#contact_' + idContact + ' .select_type_contact option[value=' + escapeQuotes(contact.id_type) + ']').prop('selected', true);
                    onChangeTypeContact(contact.id_type, index + 1);
                    if ($('#contact_' + idContact + ' .complement_1').attr("name") === "select_agences" && contact.complement_1 != "") {
                        $('#contact_' + idContact + ' .complement_1 option[value=' + escapeQuotes(contact.complement_1) + ']').prop('selected', true);
                    } else {
                        $('#contact_' + idContact + ' .complement_1').val(contact.complement_1);
                    }
                    $('#contact_' + idContact + ' .prenom_contact').val(contact.prenom);
                    $('#contact_' + idContact + ' .nom_contact').val(contact.nom);
                    $('#contact_' + idContact + ' .email_contact').val(contact.email);
                    $('#contact_' + idContact + ' .telephone_contact').val(contact.telephone);
                    $('#contact_' + idContact + ' .commentaires').val(contact.commentaires);
                    $('#contact_' + idContact + ' .en_charge').prop('checked', contact.en_charge == 1);
                    $('#contact_' + idContact + ' .communiquer_externe').prop('checked', contact.communiquer_externe == 1);

                }
            }
        },
        "json"
    );

    // Paramètres pour le daterangepicker
    let $inputDateRange = $('#periode');
    $(function () {
        let options = {
            opens: 'center',
            autoApply: false,
            defaultDate: null,
            // autoUpdateInput: false,
            locale: {
                format: 'DD/MM/YYYY',
                cancelLabel: 'Supprimer',
                applyLabel: 'Appliquer',
                "customRangeLabel": "Custom",
                "weekLabel": "W",
                "daysOfWeek": [
                    "Di",
                    "Lu",
                    "Ma",
                    "Me",
                    "Je",
                    "Ve",
                    "Sa"
                ],
                "monthNames": [
                    "Janvier",
                    "Février",
                    "Mars",
                    "Avril",
                    "Mai",
                    "Juin",
                    "Juillet",
                    "Août",
                    "Septembre",
                    "Octobre",
                    "Novembre",
                    "Décembre"
                ],
                "firstDay": 1
            }
        }
        if (debutPeriode !== undefined && finPeriode !== undefined) {
            options.startDate = formatDateToDDMMA(debutPeriode);
            options.endDate = formatDateToDDMMA(finPeriode);
        }
        $inputDateRange.daterangepicker(options);
    });

    // Récupération des dates de début et fin
    $inputDateRange.on('apply.daterangepicker', function (ev, picker) {
        debutPeriode = picker.startDate.format('YYYY/MM/DD/');
        finPeriode = picker.endDate.format('YYYY/MM/DD/');
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });

    // Supprimer les dates du champs période
    $inputDateRange.on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
        debutPeriode = null;
        finPeriode = null;
    });

    let carousel = $('#carousel_form');
    // Stop autoplay du carousel
    carousel.carousel({
        interval: false,
        pause: true,
    });


    $(".btn_continuer:not(:first, #btn_continuer_titre_recit, #btn_continuer_periode)").click(function () {
        carousel.carousel('next');
    });

    $(".btn_precedent").click(function () {
        carousel.carousel('prev');
    });

    // Prérempli le premier contact si l'auteur est un acteur impliqué dans l'histoire
    $(".btn_continuer:first").click(function () {
        // Validation form vos informations
        let form_vos_informations = $("#form_vos_informations");
        form_vos_informations.validate({
            rules: {
                nom_vos_infos: {
                    required: true,
                },
                prenom_vos_infos: {
                    required: true,
                },
                email_vos_infos: {
                    required: true,
                    email: true
                },
                num_vos_infos: {
                    maxlength: 15,
                },
                fonction_vos_infos: {
                    required: true,
                },
                select_agences: {
                    required: true,
                },
            },
            messages: {
                nom_vos_infos: {
                    required: "Veuillez renseignez votre nom"
                },
                prenom_vos_infos: {
                    required: "Veuillez renseignez votre prénom"
                },
                email_vos_infos: {
                    required: "Veuillez renseignez votre email",
                    email: "Adresse non valide"
                },
                num_vos_infos: {
                    number: "Veuillez entrer un numéro valide",
                },
                select_agences: {
                    required: "Veuillez renseignez votre agence"
                },
                fonction_vos_infos: {
                    required: "Veuillez renseignez votre fonction"
                },
            }
        });
        if (form_vos_informations.valid()) {
            if ($('#checkbox_implique').is(":checked")) {
                let idCourant = $('.contact').length;
                if (idCourant > 1) {
                    ajouterContact();
                    idCourant += 1;
                }
                $('#contact_' + idCourant + ' .prenom_contact').val($('#prenom_vos_infos').val());
                $('#contact_' + idCourant + ' .nom_contact').val($('#nom_vos_infos').val());
                $('#contact_' + idCourant + ' .email_contact').val($('#email_vos_infos').val());
                $('#contact_' + idCourant + ' .telephone_contact').val($('#telephone_vos_infos').val());
                let idTypeContact = (typesContactFetched.find(element => element.intitule === 'Agent Pôle emploi')).id;
                $('#contact_' + idCourant + ' .select_type_contact').val(idTypeContact);
                onChangeTypeContact(idTypeContact, idCourant);
                $('#contact_' + idCourant + ' .champ_type_1 option[value=' + escapeQuotes($('#select_agences').val()) + ']').prop('selected', true);
                $('#contact_' + idCourant + ' .en_charge').prop('checked', true);
            }
            carousel.carousel('next');
        }
    });

    // Validation de la période de la belle histoire
    $("#btn_continuer_periode").click(function () {
        // Validation form vos informations
        let form_periode = $("#form_periode");
        form_periode.validate({
            rules: {
                periode: {
                    required: true,
                },
                select_agence_rattachement: {
                    required: true,
                }
            },
            messages: {
                periode: {
                    required: 'Veuillez renseigner une période',
                },
                select_agence_rattachement: {
                    required: 'Veuillez sélectionner une agence de rattachement',
                },
            }
        });
        if (debutPeriode === undefined || finPeriode === undefined) {
            $('#periode').after('<label id="periode-error" class="error" for="periode" style="display: block">Veuillez renseigner une période</label>');
            return;
        }
        if (form_periode.valid()) {
            carousel.carousel('next');
        }
    });


    // Validation du titre et du récit de la belle histoire
    $("#btn_continuer_titre_recit").click(function () {
        // Validation form vos informations
        let form_titre_recit = $("#form_titre_recit");
        form_titre_recit.validate({
            rules: {
                titre: {
                    required: true,
                },
                recit: {
                    required: true,
                },
            },
            messages: {
                titre: {
                    required: 'Veuillez renseignez un titre',
                },
                recit: {
                    required: 'Veuillez raconter la belle histoire',
                },
            }
        });
        if (form_titre_recit.valid()) {
            carousel.carousel('next');
        }
    });

    // Validation form contacts
    $("#btn_finaliser").click(function () {

        let form_contacts = $('.form_contacts');

        let contacts = [];

        form_contacts.each(function (index) {
            let id = index + 1;
            $(this).validate({
                rules: {
                    nom_contact: {
                        required: true,
                    },
                    prenom_contact: {
                        required: true,
                    },
                    email_contact: {
                        required: {
                            depends: function (elem) {
                                return $("#contact_" + id + " .telephone_contact").val() === '';
                            }
                        },
                        email: true
                    },
                    telephone_contact: {
                        required: {
                            depends: function (elem) {
                                return $("#contact_" + id + " .email_contact").val() === '';
                            }
                        },
                        maxlength: 15,
                    },
                    select_type_contact: {
                        required: true,
                    },
                },
                messages: {
                    nom_contact: {
                        required: "Veuillez renseignez le nom du contact"
                    },
                    prenom_contact: {
                        required: "Veuillez renseignez le prénom du contact"
                    },
                    email_contact: {
                        required: 'Veuillez renseignez un moyen de joindre ce contact',
                        email: "Adresse non valide"
                    },
                    telephone_contact: {
                        required: 'Veuillez renseignez un moyen de joindre ce contact',
                        maxlength: 'Veuillez entrer un numéro de téléphone valide',
                    },
                    select_type_contact: {
                        required: 'Veuillez selectionner un type de contact'
                    },
                }
            });
        })
        let valid = true;
        form_contacts.each(function (index) {
            if ($(this).valid()) {
                contacts.push({
                    ordre: index,
                    prenom_contact: $(this).find('.prenom_contact').val(),
                    nom_contact: $(this).find('.nom_contact').val(),
                    email_contact: $(this).find('.email_contact').val(),
                    telephone_contact: $(this).find('.telephone_contact').val(),
                    complement_1: $(this).find('.complement_1').val(),
                    type_contact: $(this).find('.select_type_contact').val(),
                    communiquer_externe: $(this).find('.communiquer_externe').is(":checked") ? 1 : 0,
                    en_charge: $(this).find('.en_charge').is(":checked") ? 1 : 0,
                    commentaires: $(this).find('.commentaires').val(),
                })
            } else {
                valid = false;
            }
        });

        // Si valide, insert la belle histoire
        if (valid) {
            let dataBelleHistoire = {
                prenom_vos_infos: $('#prenom_vos_infos').val(),
                nom_vos_infos: $('#nom_vos_infos').val(),
                telephone_vos_infos: $('#telephone_vos_infos').val(),
                email_vos_infos: $('#email_vos_infos').val(),
                fonction_vos_infos: $('#fonction_vos_infos').val(),
                agence_vos_infos: $('#select_agences').val(),
                agence_rattachement: $('#select_agence_rattachement').val(),
                titre: $('#titre').val(),
                recit: $('#recit').val(),
                debutPeriode,
                finPeriode,
                evolutions: $('#evolutions').val(),
                contacts,
                publicsCiblesPerso,
                motsClesPerso,
                idPublicsCibles: idPublicsCiblesSelectedPredefini,
                idMotsCles: idMotsClesSelectedPredefini,
            };

            if (idHistoirePrerempli !== undefined) {
                let confirmation = confirm("Êtes-vous sur de vouloir modifier cette belle histoire ?");
                if (confirmation === true) {
                    dataBelleHistoire.motsClesPreremplis = motsClesPreremplis;
                    dataBelleHistoire.publicsCiblesPreremplis = publicsCiblesPreremplis;
                    dataBelleHistoire.contactsPreremplis = contactsPrerempli;
                    dataBelleHistoire.idHistoire = idHistoirePrerempli;
                    $.post("ajax/insert.php",
                        dataBelleHistoire,
                        null,
                        'json'
                    ).done(function (result) {
                        console.log(result.id);
                        if (result.hasOwnProperty('id')) {
                            alert('La belle histoire a bien été modifiée');
                            for (form of $('form')) {
                                form.reset();
                            }
                            window.location.assign("fin_formulaire.php?id=" + result.id);
                        } else {
                            alert('La belle histoire n\'a pas été modifiée, veuillez réessayer');
                        }
                    })
                        .fail(function (xhr, status, error) {
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        });
                }
                return;
            }

            let confirmation = confirm("Êtes-vous sur de vouloir enregistrer cette belle histoire ? \n Vous pourrez toujours la modifier ultérieurement");
            if (confirmation === true) {
                $.post("ajax/insert.php",
                    dataBelleHistoire,
                    null,
                    'json',
                ).done(function (result) {
                    console.log(result.id);
                    if (result.hasOwnProperty('id')) {
                        alert('La belle histoire a bien été enregistrée');
                        for (form of $('form')) {
                            form.reset();
                        }
                        window.location.assign("fin_formulaire.php?id=" + result.id);
                    } else {
                        alert('La belle histoire n\'a pas été enregistrée, veuillez réessayer');
                        console.log(result);
                    }
                })
                    .fail(function (xhr, status, error) {
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    });
            }
        }
    });


    // Ajoute les mots clés à la liste
    $("#btn_ajouter_mots_cles").click(function (e) {
        e.preventDefault();
        let input = $('#mots_cles_input');
        let $listMotsCles = $('#list_mots_cles_pred');

        let [idMotCle, motCle] = checkExists(input.val(), $listMotsCles)

        if (idMotCle !== -1) {
            ajouterMotCle(idMotCle, motCle, true);
            input.val('');
        } else if (input.val().replace(/\s+/g, '').length > 0) {
            ajouterMotCle(0, input.val(), false);
            input.val('');
        }
    });


    // Ajoute les mots clés perso à la liste
    $("#btn_ajouter_publics_cibles").click(function (e) {
        e.preventDefault();

        let input = $('#publics_cibles_input');
        let $listPublicsCibles = $('#list_publics_cibles_pred');

        let [idPublicCible, publicCible] = checkExists(input.val(), $listPublicsCibles)

        if (idPublicCible !== -1) {
            ajouterPublicCible(idPublicCible, publicCible, true);
            input.val('');
        } else if (input.val().replace(/\s+/g, '').length > 0) {
            ajouterPublicCible(idPublicCible, input.val(), false);
            $(input.val(''));
        }
    });


    // Onchange premier contact
    $('#contact_1 .select_type_contact').change(function () {
        onChangeTypeContact(this.value, 1);
    });


    //Ajouter un contact
    $("#btn_ajouter_contact").click(function (e) {
        e.preventDefault();
        ajouterContact();
    })


});
