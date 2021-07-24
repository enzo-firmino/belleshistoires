function exporterPdf(histoire, motsClesStr, publicsCiblesStr, agence, debutPeriode, finPeriode, contacts) {

    let doc = new jsPDF();

    let pageHeight = 277; // taille A4 moins les marges
    let position = 10; // position actuelle ou on écrit dans le pdf
    let marginLeft = 15; // marge a gaucher
    let marginRight = doc.internal.pageSize.width - marginLeft; // marge a droite

    doc.setFontSize(16.0);
    doc.setFontType("bold");
    let titre = doc.splitTextToSize(histoire.titre, doc.internal.pageSize.width - 30); //Split le titre en tableau de string
    doc.text(titre, doc.internal.pageSize.width / 2, position, null, null, 'center');
    position = titre.length * 5 + 30; // titre.length est le nombre de lignes que l'on multiple par 5 (hauteur d'une ligne) pour avoir la hauteur du titre + 30 de marge bottom
    doc.setFontType("normal");
    doc.setFontSize(12.0);
    doc.text('Période : ' + debutPeriode + ' - ' + finPeriode, marginLeft, position);
    position += 10; // on incrémente la position de 10 à chaque écriture
    doc.text('Agence de rattachement : ' + agence, marginLeft, position);
    position += 10;
    doc.text('Email rédacteur : ' + histoire.email_redacteur, marginLeft, position);
    position += 10;
    doc.line(marginLeft, position, marginRight, position);
    position += 10;
    doc.text('Mots clés : ' + motsClesStr, marginLeft, position);
    position += 10;
    doc.text('Publics cibles : ' + publicsCiblesStr, marginLeft, position);
    position += 10;
    doc.line(marginLeft, position, marginRight, position);
    position += 10;
    let recit = doc.splitTextToSize(histoire.recit, doc.internal.pageSize.width - 50);
    doc.text('Histoire : Qui – Quoi- Quand- Où - Comment ? : ', marginLeft, position);
    position += 10;
    for (let row of recit) {
        if (position > pageHeight) { // Check si il y a besoin d'une nouvelle page
            doc.addPage();
            position = 20;
        }
        doc.text(row, marginLeft+10, position);
        position += 6;
    }
    doc.line(marginLeft, position, marginRight, position);
    let evolutions = doc.splitTextToSize('Evolutions à venir : ' + histoire.evolutions, doc.internal.pageSize.width - 30);
    position += 10;
    if (position > pageHeight) {
        doc.addPage();
        position = 20;
    }
    doc.text(evolutions, marginLeft, position);
    position += evolutions.length * 5 + 10;
    doc.line(marginLeft, position, marginRight, position);
    position += 10;
    if (position > pageHeight) {
        doc.addPage();
        position = 20;
    }
    doc.text('Personnes à contacter : ', marginLeft, position);
    position += 15;
    for (let contact of contacts) {
        if (position + 40 > pageHeight) {
            doc.addPage();
            position = 20;
        }
        doc.line(marginLeft + 10, position, marginRight - 10, position);
        position += 10;
        if (contact.en_charge === '1') {
            doc.setFontType("bold");
            doc.text('En charge du dossier', marginLeft + 10, position);
            position += 10;
            doc.setFontType("normal");
        }
        doc.text(getStrTypeContact(contact.id_type, contact.complement_1), marginLeft + 10, position);
        position += 10;
        doc.text(contact.prenom + ' ' + contact.nom, marginLeft + 10, position);
        position += 10;
        let separateur = contact.email === "" ? '' : ', ';
        doc.text('Email/Téléphone : ' + contact.email + separateur + contact.telephone, marginLeft + 10, position);
        position += 10;
        doc.text(contact.communiquer_externe === '1'
            ? 'Ce contact est d\'accord pour communiquer à l\'externe'
            : 'Ce contact n\'est pas d\'accord pour communiquer à l\'externe', marginLeft + 10, position);
        position += 10;
    }


    doc.save('belle-histoire.pdf');

}

function escapeQuotes(string) {
    return string.replace('"', '&#34;').
    replace("'", '&#39;');
}

// Crée le string à ajouter au pdf
function getStrTypeContact(idType, complement1) {
    let strTypeContact = '';
    let typeContact = (typesContact.filter(element => element.id == idType))[0];
    if (typeContact.intitule === 'Autre') {
        strTypeContact = 'Type de contact : ' + complement1;
    } else if (typeContact.intitule === 'Agent Pôle Emploi') {
        let siteRattachement = (sitesRattachement.filter(element => element.id == complement1))[0];
        strTypeContact = 'Type de contact : ' + typeContact.intitule + ', ' + typeContact.complement_1 + ' : ' + siteRattachement.site;
    } else {
        strTypeContact = 'Type de contact : ' + typeContact.intitule + ', ' + typeContact.complement_1 + ' : ' + complement1;
    }
    return strTypeContact;
}


function modifier(idHistoire) {
    document.location = "ajouter.php?id="+idHistoire;
}

function supprimer(idHistoire) {
    let confirmation = confirm('Voulez-vous vraiment supprimer cette belle histoire ?');
    if (confirmation) {
        $.post(
            'ajax/supprimer_histoire.php',
            {id: idHistoire},
        )
            .done(
                function (data) {
                    if (data === 'success') {
                        alert('La belle histoire a bien été supprimée');
                    } else {
                        alert('La belle histoire n\'a pas été supprimée, veuillez réessayer');
                    }
                    location.reload();
                }, 'json')
            .fail(function (xhr, status, error) {
                console.log('err', xhr);
                console.log('err', error);
                console.log('err', status);
            });
    }
}

function retour() {
    $('#content_recherche').css("display", "block");
    $('#content_detail_histoire').empty();
    $(window).scrollTop(scrollPos || 0)
}


async function getMotsClesHistoire(idHistoire) {
    return await $.get(
        "ajax/get_histoire_mots_cles.php",
        {id_histoire: idHistoire},
        null,
        'json'
    ).done(function (data) {
        return data;
    });
}


async function getPublicsCiblesHistoire(idHistoire) {
    return await $.get(
        "ajax/get_histoire_publics_cibles.php",
        {id_histoire: idHistoire},
        null,
        'json'
    ).done(function (data) {
        return data;
    });
}

async function getMajs(idHistoire) {
    return await $.get(
        "ajax/get_majs.php",
        {id_histoire: idHistoire},
        null,
        'json'
    ).done(function (data) {
        return data;
    });
}


async function getContacts(idHistoire) {
    return await $.get(
        "ajax/get_histoire_contacts.php",
        {id_histoire: idHistoire},
        null,
        'json'
    )
        .done(function (data) {
            return data;
        })
        .fail(function (xhr, status, error) {
            console.log(xhr.responseText);
        });
}


// Ajoute le champ supplémentaires du contact en fonction du type
function getHtmlTypeContact(idType, complement1, idContact) {
    let typeContact = (typesContact.filter(element => element.id == idType))[0];
    if (typeContact.intitule === 'Autre') {
        $('#'+idContact).prepend('<div>Type de contact : ' + complement1 + '</div>');
    } else if (typeContact.intitule === 'Agent Pôle emploi') {
        let siteRattachement = (sitesRattachement.filter(element => element.id == complement1))[0];
        let complement = siteRattachement === undefined ? '' :  typeContact.complement_1 + ' : ' + siteRattachement.nom
        $('#'+idContact).prepend('<div>Type de contact : ' + typeContact.intitule + ', ' + complement + '</div>');
    } else {
        let complement = complement1 === '' ? '' :  typeContact.complement_1 + ' : ' + complement1;
        $('#'+idContact).prepend('<div>Type de contact : ' + typeContact.intitule + ', ' + complement + '</div>');
    }
}


// Ajoute un item belle histoire au html
async function appendItemHistoire(response) {
    let $listeHistoires = $("#liste_histoires");
    for (let histoire of response) {
        let htmlItemHistoire = '';
        let motsClesHistoire = await getMotsClesHistoire(histoire.id);
        let motsClesStr = '';
        for (let motCle of motsClesHistoire) {
            let mot = motsCles.find(element => element.id === motCle.id_mot_cle);
            motsClesStr += mot.mot + ', ';
        }
        let publicsCiblesHistoire = await getPublicsCiblesHistoire(histoire.id);
        let publicsCiblesStr = '';
        for (let publicCible of publicsCiblesHistoire) {
            let publicMot = publicsCibles.find(element => element.id === publicCible.id_public_cible);
            publicsCiblesStr += publicMot.public_cible + ', ';
        }
        let majs = await getMajs(histoire.id);
        let agence = (sitesRattachement.filter(element => element.id === histoire.id_agence_rattachement))[0];
        let datePartsDebut = histoire.debut_periode.split("-");
        let datePartsFin = histoire.fin_periode.split("-");
        let debutPeriode = new Date(datePartsDebut[0], datePartsDebut[1] - 1, datePartsDebut[2].substr(0, 2));
        let finPeriode = new Date(datePartsFin[0], datePartsFin[1] - 1, datePartsFin[2].substr(0, 2));
        htmlItemHistoire = "<div class=\"item_histoire\" id=" + histoire.id + " onclick> " +
            "<div style=\"display: flex; justify-content: space-between;\">" +
            "<span style=\"font-weight: bold; font-size: 1.2rem\">" + histoire.titre + "</span>" +
            "<span>Periode : " + debutPeriode.toLocaleDateString() + " - " + finPeriode.toLocaleDateString() + "</span>" +
            "</div>" +
            "<div>" +
            "Mots clés : " + motsClesStr +
            "</div>" +
            "<div>" +
            "Publics cibles : " + publicsCiblesStr +
            "</div>" +
            "<div style=\"display: inline\">" +
            "Agence de rattachement : " + agence.nom +
            "<div style=\"display: flex; justify-content: flex-end;\">" +
            "<button class=\"btn btn-primary btn-sm btn_detail\" style=\"margin-right: 5px\">Voir le détail</button>" +
            "<button class=\"btn btn-primary btn-sm btn_modifier\" style=\"margin-right: 5px\">Modifier</button>" +
            "<button class=\"btn btn-primary btn-sm btn_exporter\">Exporter en pdf</button>" +
            "</div>" +
            "</div>" +
            "</div>"
        $listeHistoires.append(htmlItemHistoire);
        $('#' + histoire.id + " .btn_exporter").click(async function () {
            let contacts = await getContacts(histoire.id);
            exporterPdf(histoire, motsClesStr, publicsCiblesStr, agence.nom, debutPeriode.toLocaleDateString(), finPeriode.toLocaleDateString(), contacts);
        });
        $('#' + histoire.id + " .btn_modifier").click(async function () {
            modifier(histoire.id);
        });
        $('#' + histoire.id + " .btn_detail").click(async function () {
            scrollPos = $(window).scrollTop();
            let contacts = await getContacts(histoire.id);
            $('#content_recherche').css("display", "none");
            window.history.pushState('resultat', null, '');
            $('#content_detail_histoire').load(
                "histoire.php",
                {
                    histoire,
                    motsCles: motsClesStr,
                    publicsCibles: publicsCiblesStr,
                    agence: agence.nom,
                    debutPeriode: debutPeriode.toLocaleDateString(),
                    finPeriode: finPeriode.toLocaleDateString(),
                    contacts,
                    majs,
                },
                function () {
                    $('#btn_exporter_detail').click(async function () {
                        exporterPdf(histoire, motsClesStr, publicsCiblesStr, agence.nom ,debutPeriode.toLocaleDateString(), finPeriode.toLocaleDateString(), contacts);
                    });
                    $('#btn_modifier_detail').click(async function () {
                        modifier(histoire.id);
                    });
                    $('#btn_supprimer_detail').click(async function () {
                        supprimer(histoire.id);
                    });
                    $('#toggleMajs').click(function(){
                        $("#majs").toggle(200);
                    });
                }
            )
        })
    }
}