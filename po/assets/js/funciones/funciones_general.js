$(document).ready(function () {

    $("#fileinput").fileinput({
        showUpload: false,
        showBrowse: false,
        dropZoneEnabled: true,
        dropZoneTitle: "Arrastre y suelte su logo aqui",
        dropZoneClickTitle: " o click para seleccionar el logo",
        showRemove: false,
        showCaption: false,
        removeTitle: "Borrar",
        zoomTitle: 'Ver Detalles',
        //theme: "explorer-fa",
        removeFromPreviewOnError: true,
        overwriteInitial: false,
        showUpload: false,
        browseOnZoneClick: true,
        enableResumableUpload: true,
        language: "es",
        maxFileCount: 1,
        allowedFileExtensions: ["jpg", "png"]
    });
    $('#formulario').validate({
        rules: {
            nombre_empresa: {
                required: true,
            },
            direccion_empresa: {
                required: true,
            },
            iva: {
                required: true,
            },
            retencion: {
                required: true,
            },
        },
        messages: {
            nombre_empresa: "Ingrese un nombre para la empresa",
            direccion_empresa: "Ingrese una direccion para la empresa",
            iva: "Ingrese el porcentaje de iva",
            retencion: "Ingrese el porcentaje de retención",
        },
        submitHandler: function (form) {
            senddata();
        }
    });

});

function senddata() {
    var form = $("#formulario");
    var formdata = false;
    if (window.FormData) {
        formdata = new FormData(form[0]);
    }
    $.ajax({
        type: 'POST',
        url: base_url+'Config_General/cambios',
        cache: false,
        data: formdata ? formdata : form.serialize(),
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (datax) {
            display_notify(datax.type, datax.msg);
            if (datax.type == "success") {
                setTimeout("reload('" + datax.url + "');", 1000);
            }
        }
    });
}

function reload(url) {
    location.href = url + 'Config_General';
}
