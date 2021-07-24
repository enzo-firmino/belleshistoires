$(document).ready(function () {

    let mailUser = $("#mail_user");
    $("form#form-identification").submit(function (event) {
        event.preventDefault();
        $.post('ajax/identification.php',
            {
                function: 'identification',
                email: mailUser.val(),
                password: $("#pass_user").val(),
                remember: $("#remember").is(":checked"),
            },
            function (data) {
                $("#alerte").hide();
                if (data.valid) {
                    document.location = "index.php";
                } else {
                    $("#alerte").show("slow");
                }
            }, 'json').fail(function (xhr, status, error) {
            console.log('err', xhr);
            console.log('err', error);
            console.log('err', status);
        });
    });

    $("#btrenewpass").click(function () {
        $("#form-renew").show();
        if (mailUser.val() !== "" && mailUser.val() !== null) {
            $("#mail_renew").val(mailUser.val());
        }
    });

    $("form#form-renew").submit(function (event) {
        event.preventDefault();
        $.post('ajax/identification.php',
            {
                function: 'renewpass',
                email: $("#mail_renew").val()
            },
            function (data) {
                $("#alert-renew, #success-renew").hide();
                if (data.value) {
                    $("#success-renew").show();
                } else {
                    $("#alert-renew").show();
                }
            }, 'json').fail(function (xhr, status, error) {
            console.log('err', xhr);
            console.log('err', error);
            console.log('err', status);
        });
    })

});