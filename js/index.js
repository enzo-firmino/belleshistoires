let motsCles = [];
let publicsCibles = [];
let sitesRattachement = [];
let typesContact = [];

jQuery(document).ready(function ($) {

    // Workaround pour le retour en arrière qui ne change pas de page mais qui reviennent à la liste des résultats
    if (window.history && window.history.pushState) {
        $(window).on('popstate', function() {
            if (!$('#content_detail_histoire').is(':empty')) {
                retour();
            }
        });
    }

    // Fetch liste agences
    $.get("ajax/get_sites_rattachements_json.php", null, "json").done(function (response) {
        sitesRattachement = response;
    }).fail(function (xhr, status, error) {
        console.log('xhr.responseText', xhr.responseText);
        console.log('status', status);
        console.log('error', error);
    });

    // Fetch liste mots clés
    $.get(
        "ajax/get_all_mots_cles.php",
        function (response) {
            motsCles = response;
            let listMotsCles = '';
            for (let motCle of response) {
                listMotsCles = listMotsCles + "<option data-value=" + motCle.id + " value=" + motCle.mot + "></option>"
            }
            $("#list_mots_cles").append(listMotsCles);
        },
        "json"
    );

    // Fetch liste publics cibles
    $.get(
        "ajax/get_all_publics_cibles.php",
        function (response) {
            publicsCibles = response;
            let listPublicsCibles = '';
            for (let publicCible of response) {
                listPublicsCibles = listPublicsCibles + "<option data-value=" + publicCible.id + " value=" + publicCible.public_cible + "></option>"
            }
            $("#list_publics_cibles").append(listPublicsCibles);
        },
        "json"
    );

    // Fetch liste types contacts
    $.get(
        "ajax/get_types_contact.php",
        function (response) {
            typesContact = response;
        },
        "json"
    );


    $.get('ajax/get_nbr_belles_histoires.php', null, 'json').done(function (result) {
        let $nbrBellesHistoires = $('#nbr_belles_histoires');
        result.nbr_belles_histoires > 1
            ? $nbrBellesHistoires.text('Il y\'a actuellement ' + result.nbr_belles_histoires + ' belles histoires enregistrées')
            : $nbrBellesHistoires.text('Il y\'a actuellement ' + result.nbr_belles_histoires + ' belle histoire enregistrée')
    })
        .fail(function (xhr, status, error) {
            console.log('xhr.responseText', xhr.responseText);
            console.log('status', status);
            console.log('error', error);
        });



    $.get(
        "ajax/get_histoires_recentes.php",
        async function (response) {
            await appendItemHistoire(response);
        },
        "json",
    );

});



