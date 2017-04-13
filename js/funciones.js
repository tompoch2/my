$(document).ready(function()
{
    $('#pc').show();
    $('#configg').hide();


    $(document).on('click', '#la_ubicacion_seleccionada', function(e){e.preventDefault();var idl = $(this).data('id');var idu = "id_ubicacion = " + idl;console.log(idu);$('#dynamic-content').html('');$('#modal-loader').show();$.ajax({url: 'php/lugares.php',type: 'POST',data: 'id_ubicacion='+idl,dataType: 'html'}).done(function(data){$('#dynamic-content').html('');$('#dynamic-content').html(data);$('#modal-loader').hide();}).fail(function(){console.log(data);$('#dynamic-content').html('<i class="glyphicon glyphicon-info-sign"></i> Ha ocurrido un problema, Intente nuevamente...');$('#modal-loader').hide();});});

    $(document).on('click', '#la_ubicacion_seleccionada2',
    function(e)
    {
        e.preventDefault();
        var idl = $(this).data('id');
        var idu = "id_ubicacion = " + idl;
        console.log(idu);
        $('#dynamic-content').html('');
        $('#modal-loader').show();
        $.ajax(
        {
            url: 'php/lugares.php',
            type: 'POST',
            data: 'id_ubicacion='+idl,
            dataType: 'html'
        })
            .done(function(data)
            {
                $('#dynamic-content').html('');
                $('#dynamic-content').html(data);
                $('#modal-loader').hide();
            })
            .fail(function()
            {
                console.log(data);
                $('#dynamic-content').html('<i class="glyphicon glyphicon-info-sign"></i> Ha ocurrido un problema, Intente nuevamente...');
                $('#modal-loader').hide();
            });
    });

    $(document).on('click', '#ellugar', function(e)
    {
        e.preventDefault();
        var idl = $(this).data('id');
        console.log("id_lugar="+idl);
        var idll = "id_lugar = " + idl;console.log(idll);
//        datos_ubicacion.style.visibility = "hidden";
        $('#dynamic-content').html('');
        $('#modal-loader').show();
        $.ajax(
        {
            url: 'php/controladores.php',type: 'POST',data: 'id_lugar='+idl,dataType: 'html'
        })
        .done(function(data)
        {
            $('#dynamic-content').html('');
            $('#dynamic-content').html(data);
            $('#modal-loader').hide();
            document.getElementById('btn_atras').style.visibility = 'visible';
 //           datos_ubicacion2.style.visibility = "visible";
        })
        .fail(function()
        {
            console.log(data);
            $('#dynamic-content').html('<i class="glyphicon glyphicon-info-sign"></i> Ha ocurrido un problema, Intente nuevamente...');
            $('#modal-loader').hide();
        });
    });

    $(document).on('click', '#eldispositivo', function(e)
    {
        console.log("entro a eldispositivo");

        e.preventDefault();
        var idisp = $(this).data('id');
        console.log("id_dispositivo="+idisp);
        var idispp = "id_dispositivo = " + idisp;
        console.log(idispp);
        //        datos_ubicacion.style.visibility = "hidden";
        $('#dynamic-dispositivo').html('');
        $('#modal-loader').show();
        $.ajax(
        {
            url: 'php/editar_dispositivo.php',type: 'POST',data: 'id_dispositivo='+idisp,dataType: 'html'
        })
        .done(function(data)
        {
            $('#dynamic-dispositivo').html('');
            $('#dynamic-dispositivo').html(data);
            $('#modal-loader').hide();
            document.getElementById('btn_atras').style.visibility = 'visible';
            //           datos_ubicacion2.style.visibility = "visible";
            $('#modal_dispositivos').hide();
//                wizard.show();
        })
        .fail(function()
        {
            console.log(data);
            $('#dynamic-dispositivo').html('<i class="glyphicon glyphicon-info-sign"></i> Ha ocurrido un problema, Intente nuevamente...');
            $('#modal-loader').hide();
        });
    });

    $(document).on('click', '#link_configuracion', function(e)
    {
        e.preventDefault();
        $('#pc').hide();
        $('#configg').show();
    });
    $(document).on('click', '#link_pc', function(e)
    {
        e.preventDefault();
        $('#pc').show();
        $('#configg').hide();
    });
    $(document).on('click', '#menu_config_general', function(e)
    {
        e.preventDefault();
        $('#config_general').show();
        $('#config_menu').hide();
        $('#config_sistema').hide();
    });
    $(document).on('click', '#menu_config_dispositivos', function(e)
    {
        e.preventDefault();
        $('#config_dispositivos').show();
        $('#config_menu').hide();
        $('#config_sistema').hide();
    });
    $(document).on('click', '#menu_sistema', function(e)
                   {
        e.preventDefault();
        $('#config_sistema').show();
        $('#config_dispositivos').hide();
        $('#config_menu').hide();
    });

    $(document).on('click', '#atras_config', function(e)
    {
        e.preventDefault();
        $('#config_menu').show();
        $('#config_general').hide();
        $('#config_sistema').hide();
    });
    $(document).on('click', '#atras_dispositivos', function(e)
                   {
        e.preventDefault();
        $('#config_menu').show();
        $('#config_dispositivos').hide();
        $('#config_sistema').hide();
    });
    $(document).on('click', '#atras_sistema', function(e)
                   {
        e.preventDefault();
        $('#config_menu').show();
        $('#config_sistema').hide();
        $('#config_dispositivos').hide();
    });

    $(document).on('click', '#link_form_casa', function(e)
    {
        e.preventDefault();
        $('#formulario-casa').html('');
        $.ajax(
        {
            url: 'php/form_casa.php',
            type: 'POST',
            data: '',
            dataType: 'html'
        })
        .done(function(data)
        {
            $('#formulario-casa').html('');
            $('#formulario-casa').html(data);
            $('#form_casa').show();
        })
        .fail(function()
        {
            console.log(data);
            $('#formulario-casa').html('<i class="glyphicon glyphicon-info-sign"></i> Ha ocurrido un problema, Intente nuevamente...');
        });
    });

    $(document).on('click', '#link_list_dispo', function(e)
    {
        e.preventDefault();
        $('#formulario-dispo').html('');
        $.ajax(
            {
                url: 'php/list_dispo.php',
                type: 'POST',
                data: '',
                dataType: 'html'
            })
        .done(function(data)
        {
            $('#formulario-dispo').html('');
            $('#formulario-dispo').html(data);
            $('#list_dispo').show();
            t_dispositivos();
        })
        .fail(function()
        {
            console.log(data);
            $('#formulario-dispo').html('<i class="glyphicon glyphicon-info-sign"></i> Ha ocurrido un problema, Intente nuevamente...');
        });
    });

    $(document).on('click', '#btn_cerrar_form_casa', function(e){e.preventDefault();$('#form_casa').hide();});
    $(document).on('click', '#btn_cerrar_list_dispo', function(e){e.preventDefault();$('#list_dispo').hide();});
});


function actualiza_camaras(){$('#loading-indicator').show();$.ajax({url: "php/camaras.php",dataType: "html",success: function(data){jQuery("#las_camaras").html(data);$('#loading-indicator').hide();}});};

function descubriendo_red(){$('#loading-indicator').show();$.ajax({url: "php/descubrir.php",dataType: "html",success: function(data){jQuery("#la_red").html(data);$('#loading-indicator').hide();}});};

function abriendo_ubicaciones(param){alert(param);var datos = param;$('#ubicaa').modal({show: 'false'});var data = datos;print (datos);}

function atraselvalordeidubicacion(){var labelText = document.getElementById('elvalordeidubicacion').textContent;console.log(labelText);var idl = labelText;var idu = "id_ubicacion = " + idl;console.log(idu);$('#dynamic-content').html('');$('#modal-loader').show();$.ajax({url: 'php/lugares.php',type: 'POST',data: 'id_ubicacion='+idl,dataType: 'html'}).done(function(data){$('#dynamic-content').html('');$('#dynamic-content').html(data);$('#modal-loader').hide();document.getElementById('btn_atras').style.visibility = 'hidden';}).fail(function(){console.log(data);$('#dynamic-content').html('<i class="glyphicon glyphicon-info-sign"></i> Ha ocurrido un problema, Intente nuevamente...');$('#modal-loader').hide();});}

function actualiza_casa(){var casa_nombre = document.getElementById("casa_nombre").value;var casa_direccion = document.getElementById("casa_direccion").value;var casa_mqtt = document.getElementById("casa_mqtt").value;var dataString = 'casa_nombre1=' + casa_nombre + '&casa_direccion1=' + casa_direccion + '&casa_mqtt1=' + casa_mqtt;if (casa_nombre == '' || casa_direccion == '' || casa_mqtt == ''){swal("Atencion!", "Todos los Campos son necesarios!", "warning")}else{$.ajax({type: "POST",url: "php/form_casa_actualiza.php",data: dataString,cache: false,success: function(html){swal("Ok!", html, "success")}});}return false;}

function resizeIframe(obj) {
    obj.style.height = 0;
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}

function t_dispositivos()
{
    console.log("entro")
    $('#tabla_dispositivos').DataTable(
        {
            "columns": [
                {"data": "id_controlador"},
                {"data": "nombre"},
                {"data": "lugar"},
                {"data": "ubicacion"},
                {"data": "tipo"},
                {"data": "tipo_sub"},
                {"data": "visible"}
            ],
            "processing": true,
            "serverSide": true,
            "ajax":
            {
                url: 'php/data_vista_controladores.php',
                type: 'POST'
            }
        });
}