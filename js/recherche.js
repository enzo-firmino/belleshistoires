let motsCles = [];
let publicsCibles = [];
let typesContact = [];
let sitesRattachement = [];
let scrollPos;


jQuery(document).ready(function ($) {

    // Workaround pour le retour en arrière qui ne change pas de page mais qui reviennent à la liste des résultats
    if (window.history && window.history.pushState) {
        $(window).on('popstate', function () {
            if (!$('#content_detail_histoire').is(':empty')) {
                retour();
            }
        });
    }


    // Bouton pour scroll top
    $(window).scroll(function() {
        if($(window).scrollTop() == 0){
            $('#scrollToTop').fadeOut("fast");
        } else if ($('#content_recherche').css("display") !== "none") {
            if($('#scrollToTop').length == 0){
                $('body').append('<div id="scrollToTop"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="white" class="bi bi-arrow-up-square-fill" viewBox="0 0 16 16">\n' +
                    '  <path d="M2 16a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2zm6.5-4.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 1 0z"/>\n' +
                    '</svg></a></div>');
            }
            $('#scrollToTop').fadeIn("fast");
        }
    });

    $('#scrollToTop a').click(function(event){
        event.preventDefault();
        $('html, body').animate({scrollTop: 0}, 1600);
    });


    // Créer les options du select période
    let date = new Date();
    let periode = '';
    date.setMonth(date.getMonth() - 3);
    periode += "<option value=" + date.toISOString() + ">moins de 3 mois</option>";
    date.setMonth(date.getMonth() - 3);
    periode += "<option value=" + date.toISOString() + ">moins de 6 mois</option>";
    date.setMonth(date.getMonth() - 6);
    periode += "<option selected value=" + date.toISOString() + ">moins de 1 an</option>";
    date.setFullYear(1000);
    periode += "<option value=" + date.toISOString() + ">sans importance</option>";
    $('#periode').append(periode);


    // Fetch liste agences
    $.get(
        "ajax/get_agences_recherche_html.php",
        function (response) {
            $("#agences").append(response);
            $("#agences option").each(function () {
                sitesRattachement.push({id: $(this).val(), nom: $(this).text()})
            });
        },
        "html"
    );

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
        "json",
    );


    // Vide le champ text
    $('#btn_vider_text').click(function () {
        $('#text').val('');
    })

    // Reset tous les champs
    $('#btn_vider_all').click(function () {
        $('#text').val('');
        $('#agences').val('');
        let date = new Date();
        date.setMonth(date.getMonth() - 12);
        $('#periode option:eq(2)').prop('selected', true);
    })


    // Btn rechercher
    $('#btn_rechercher').click(function () {
        let $agences = $('#agences option:selected');
        let idAgence = $agences.val();
        let classAgence = $agences.attr("class");
        let text = $('#text').val();
        let dateFin = $("#periode option:selected").val();
        $.post(
            "ajax/recherche_histoire.php",
            {
                dateFin: dateFin,
                id_agence: idAgence === '' ? undefined : idAgence,
                text: text === undefined ? '' : text,
                class_agence: classAgence,
            },
            null,
            'json'
        ).done(async function (response) {
            let $listeHistoires = $('#liste_histoires');
            let $nbrHistoiresTrouve = $('#nbr_histoires_trouve');
            $listeHistoires.empty();
            $nbrHistoiresTrouve.empty();
            if (response.length !== 0) {
                response.length > 1
                    ? $nbrHistoiresTrouve.html('<p class="text-center" style="margin-bottom: 40px; color: white; font-size: 1.2rem">' + response.length + ' Belles histoires trouvées</p>\n')
                    : $nbrHistoiresTrouve.html('<p class="text-center" style="margin-bottom: 40px; color: white; font-size: 1.2rem">' + response.length + ' Belle histoire trouvée</p>\n');
                await appendItemHistoire(response);
            } else {
                $listeHistoires.append("<div class='no_result'>Pas de résultat pour cette recherche</div>");
            }
        })
            .fail(function (xhr, status, error) {
                console.log('xhr.responseText', xhr.responseText);
                console.log('status', status);
                console.log('error', error);
            })
    })


});


