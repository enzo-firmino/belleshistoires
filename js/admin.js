let emailUtilisateurs = [];
let allUtilisateurs = [];
let utilisateursFiltered = [];
let currentPage;

function buildListeUtilisateurs() {
    $("#utilisateurs tbody").empty();
    utilisateursFiltered.forEach(function (utilisateur) {
        let ligne = $("<tr id='" + utilisateur.id + "'></tr>");
        ligne.append($("<td>" + utilisateur.email + "</td>"));
        if (utilisateur.super_admin === '0') {
            ligne.append($("<td>Admin</td>"));
            ligne.append($("<td><button class=\"btn btn-primary changer_role\">Changer le rôle en admin</button></td>"));
        } else {
            ligne.append($("<td>Admin</td>"));
            ligne.append($("<td><button class=\"btn btn-primary changer_role\">Enlever le rôle d'admin</button></td>"));
        }
        ligne.append($("<td><button class=\"btn btn-danger supprimer\">Supprimer</button></td>"));
        $("#utilisateurs tbody").append(ligne);
        $("#" + utilisateur.id + " .changer_role").click(function () {
            changerRole(utilisateur.id, utilisateur.super_admin);
        });
        $("#" + utilisateur.id + " .supprimer").click(function () {
            supprimerUtilisateur(utilisateur.id);
        })
    });
}

function supprimerMotCle(motCle) {
    if (localStorage.getItem('dont_display_confirmation') !== null && localStorage.getItem('dont_display_confirmation') === 'true') {
        $.post(
            'ajax/supprimer_mot_cle.php',
            {id: motCle.id},
        )
            .done(
                function (data) {
                    location.reload();
                }, 'json')
            .fail(function (xhr, status, error) {
                console.log('err', xhr);
                console.log('err', error);
                console.log('err', status);
            });
    } else {
        let modalConfirm = $('#modalConfirm');
        $('#modalConfirm .modal-body').html(
            "<div><p>Etes-vous sûr de vouloir supprimer le mot clé \"" + motCle.mot + "\" ?</p>" +
            "<div style='display: flex; flex-direction: row; justify-content: space-between'>" +
            "<div> " +
            "<input type='checkbox' id='ne_plus_afficher' name='ne_plus_afficher'>" +
            " <label for='checkbox_super_admin'>" +
            " Ne plus afficher ?" +
            " </label>" +
            "</div>" +
            "<button id=\"annuler\" type=\"button\" class=\"btn btn-secondary\">Annuler</button> " +
            " <button id=\"supprimer\" type=\"button\" class=\"btn btn-danger\">Supprimer " +
            "</button></div></div>"
        );
        $("#annuler").click(function () {
            modalConfirm.modal('hide');
            if ($('#ne_plus_afficher').is(":checked")) {
                localStorage.setItem('dont_display_confirmation', 'true');
            }
        });
        $("#supprimer").click(function () {
            if ($('#ne_plus_afficher').is(":checked")) {
                localStorage.setItem('dont_display_confirmation', 'true');
            }
            $.post(
                'ajax/supprimer_mot_cle.php',
                {id: motCle.id},
            )
                .done(
                    function (data) {
                        location.reload();
                    }, 'json')
                .fail(function (xhr, status, error) {
                    console.log('err', xhr);
                    console.log('err', error);
                    console.log('err', status);
                });
        })
        modalConfirm.modal('show');
    }
}

function supprimerPublicCible(publicCible) {
    if (localStorage.getItem('dont_display_confirmation') !== null && localStorage.getItem('dont_display_confirmation') === 'true') {
        $.post(
            'ajax/supprimer_public_cible.php',
            {id: publicCible.id},
        )
            .done(
                function (data) {
                    location.reload();
                }, 'json')
            .fail(function (xhr, status, error) {
                console.log('err', xhr);
                console.log('err', error);
                console.log('err', status);
            });
    } else {
        let modalConfirm = $('#modalConfirm');
        $('#modalConfirm .modal-body').html(
            "<div><p>Etes-vous sûr de vouloir supprimer le public concerné \"" + publicCible.public_cible + "\" ?</p>" +
            "<div style='display: flex; flex-direction: row; justify-content: space-between'>" +
            "<div> " +
            "<input type='checkbox' id='ne_plus_afficher' name='ne_plus_afficher'>" +
            " <label for='checkbox_super_admin'>" +
            " Ne plus afficher ?" +
            " </label>" +
            "</div>" +
            "<button id=\"annuler\" type=\"button\" class=\"btn btn-secondary\">Annuler</button> " +
            " <button id=\"supprimer\" type=\"button\" class=\"btn btn-danger\">Supprimer " +
            "</button></div></div>"
        );
        $("#annuler").click(function () {
            modalConfirm.modal('hide');
            if ($('#ne_plus_afficher').is(":checked")) {
                localStorage.setItem('dont_display_confirmation', 'true');
            }
        });
        $("#supprimer").click(function () {
            if ($('#ne_plus_afficher').is(":checked")) {
                localStorage.setItem('dont_display_confirmation', 'true');
            }
            $.post(
                'ajax/supprimer_public_cible.php',
                {id: publicCible.id},
            )
                .done(
                    function (data) {
                        location.reload();
                    }, 'json')
                .fail(function (xhr, status, error) {
                    console.log('err', xhr);
                    console.log('err', error);
                    console.log('err', status);
                });
        })
        modalConfirm.modal('show');
    }
}

$(document).ready(function () {

    $.get('ajax/get_all_users.php',
        function (response) {
            emailUtilisateurs = [];
            allUtilisateurs = response;
            utilisateursFiltered = response;
            response.forEach(function (utilisateur) {
                emailUtilisateurs.push(utilisateur.email);
            });
            buildListeUtilisateurs();
        },
        "json"
    ).fail(function (xhr, status, error) {
        console.log('err', xhr);
        console.log('err', error);
        console.log('err', status);
    });


    $.get('ajax/get_all_mots_cles.php',
        function (response) {
            for (let motClePred of response) {
                if (motClePred.supprimer === '0') {
                    $("#mots_cles_admin").append(
                        "<div class='mots_publics_admin' id='mot_cle_" + motClePred.id + "'>" + motClePred.mot + " <span style='margin-left: 5px; cursor: pointer' class=\"badge badge-danger\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-x-lg\" viewBox=\"0 0 16 16\">\n" +
                        " <path d=\"M1.293 1.293a1 1 0 0 1 1.414 0L8 6.586l5.293-5.293a1 1 0 1 1 1.414 1.414L9.414 8l5.293 5.293a1 1 0 0 1-1.414 1.414L8 9.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L6.586 8 1.293 2.707a1 1 0 0 1 0-1.414z\"/>\n" +
                        "</svg></span></div>");
                    $("#mot_cle_" + motClePred.id).click(function () {
                        supprimerMotCle(motClePred);
                    });
                }
            }
        },
        "json"
    ).fail(function (xhr, status, error) {
        console.log('err', xhr);
        console.log('err', error);
        console.log('err', status);
    });

    $.get('ajax/get_all_publics_cibles.php',
        function (response) {
            for (let publicCible of response) {
                if (publicCible.supprimer === '0') {
                    $("#publics_cibles_admin").append(
                        "<div class='mots_publics_admin' id='public_cible_" + publicCible.id + "'>" + publicCible.public_cible + " <span style='margin-left: 5px; cursor: pointer' class=\"badge badge-danger\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-x-lg\" viewBox=\"0 0 16 16\">\n" +
                        " <path d=\"M1.293 1.293a1 1 0 0 1 1.414 0L8 6.586l5.293-5.293a1 1 0 1 1 1.414 1.414L9.414 8l5.293 5.293a1 1 0 0 1-1.414 1.414L8 9.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L6.586 8 1.293 2.707a1 1 0 0 1 0-1.414z\"/>\n" +
                        "</svg></span></div>");
                    $("#public_cible_" + publicCible.id).click(function () {
                        supprimerPublicCible(publicCible);
                    });
                }
            }
        },
        "json"
    ).fail(function (xhr, status, error) {
        console.log('err', xhr);
        console.log('err', error);
        console.log('err', status);
    });

    let carousel = $('#carousel_form');
// Stop autoplay du carousel
    carousel.carousel({
        interval: false,
        pause: true,
    });

    if (sessionStorage.getItem('lastSlide')) {
        currentPage = sessionStorage.getItem('lastSlide') * 1;
        carousel.carousel(sessionStorage.getItem('lastSlide') * 1);
        $("#change_page").html(currentPage === 0 ? 'Gérer les mots clés et publics cibles' : 'Gérer les utilisateurs');
    }

    $("#change_page").click(function () {
        carousel.carousel('next');
        currentPage = currentPage === 0 ? 1 : 0;
        sessionStorage.setItem('lastSlide', currentPage);
        $("#change_page").html(currentPage === 0 ? 'Gérer les mots clés et publics cibles' : 'Gérer les utilisateurs');
    });


    $('#filter_users').on('change input paste', function () {
        let value = $('#filter_users').val();
        utilisateursFiltered = allUtilisateurs.filter(utilisateur => utilisateur.email.includes(value));
        buildListeUtilisateurs();
    });

    $("form#form_ajout").submit(function (event) {
        event.preventDefault();
        let superAdmin = $('#checkbox_super_admin').is(":checked") ? 1 : 0;
        let email = $("#email").val();
        if (emailUtilisateurs.includes(email)) {
            alert('Cet email est déjà enregistré');
        } else {
            $.post('ajax/ajout_utilisateur.php',
                {
                    email: email,
                    super_admin: superAdmin,
                },
                function (data) {
                    if (data === 'success') {
                        alert('L\'utilisateur a bien été ajouté');
                    } else if (data === 'fail') {
                        alert('L\'utilisateur a bien été ajouté mais le mail n\' pas été envoyé');
                    } else {
                        alert('L\'utilisateur n\'a pas été ajouté, veuillez réessayer');
                    }
                    location.reload();
                })
                .fail(function (xhr, status, error) {
                    console.log('err', xhr);
                    console.log('err', error);
                    console.log('err', status);
                });
        }
    });

});

function changerRole(id, role) {
    let superAdmin = role === '0' ? 1 : 0;
    $.post(
        "ajax/changer_role.php",
        {
            id: id,
            super_admin: superAdmin,
        },
        function (result) {
            if (result === 'success') {
                window.alert('Le rôle de l\'utilisateur a bien été modifié');
                location.reload();
            } else {
                window.alert('Le rôle de l\'utilisateur n\'a pas été modifié, veuillez réessayer');
            }
        },
    ).fail(function (xhr, status, error) {
        console.log(xhr);
        console.log(status);
        console.log(error);
    });
}

function supprimerUtilisateur(id) {
    $.post(
        "ajax/supprimer_utilisateur.php",
        {id: id},
        function (result) {
            if (result === 'success') {
                window.alert('L\'utilisateur a bien été supprimé');
                location.reload();
            } else {
                window.alert('L\'utilisateur n\'a pas été supprimé, veuillez réessayer');
            }
        },
    ).fail(function (xhr, status, error) {
        console.log(xhr);
        console.log(status);
        console.log(error);
    });
}