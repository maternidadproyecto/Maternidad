$(document).ready(function() {
    var TSector = $('#tabla').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sClass": "center"},
            {"sClass": "center"},
            {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
             {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });

    var $sector     = $('form#frmsector #sector');
    var $municipio  = $('form#frmsector select#municipio');
    var $div_sector = $('div#div_sector');
// plugin para el combo
    $('select#municipio').select2();


    // caracteres permitidos para la caja de texto sector cuando escriba
    var val_sector = ' abcdefghijklmnñopqrstuvwxyzáéíóú1234567890';

    // validación de la caja de texto sector mientras este escribiendo
    $sector.validar(val_sector);


    var val_sectores = /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ0-9\s]{5,50}$/;
    var url = '../../controlador/mantenimiento/sector.php';

    $('#btnaccion').on('click', function() {

        $('#accion').remove();

        var $accion = ' <input type="hidden" id="accion" name="accion" value="" />';
        $($accion).prependTo($(this));

        //var $municipio = $('form#frmsector select#municipio option').filter(":selected").text();

        var accion = $(this).val();

//        var TotalRow = TSector.fnGetData().length;
//        var lastRow = TSector.fnGetData(TotalRow - 1);
//        alert(lastRow);
//        return false;
//        //var codigo = parseInt(lastRow[0]) + 1;
           
        if (accion === 'Agregar') {

            if ($municipio.val() == 0) {
                $municipio.addClass('has-error');
            } else if ($sector.val() === null || $sector.val().length === 0 || /^\s+$/.test($sector.val())) {
                $div_sector.addClass('has-error');
                $sector.focus();
            } else {

                $('#accion').val(accion);

                $.post(url, $('#frmsector').serialize(), function(data) {
                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'},function(){
                       if (cod_msg === 21) {
                        var municipio     = $municipio.find('option').filter(":selected").html();
                        var cod_municipio = $municipio.find('option').filter(":selected").val();
                        var codigo_sector = TSector.fnGetData().length;

                        codigo_sector = parseInt(codigo_sector) + 1;
                        var modificar = '<img id=' + cod_municipio + ',' + codigo_sector + ' class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
                        var eliminar  = '<img id=' + codigo_sector + ' class="eliminar" title="Eliminar" style="cursor: pointer" src="../../imagenes/datatable/eliminar.png" width="18" height="18" alt="Eliminar"/>';

                        TSector.fnAddData([municipio, $sector.val(), modificar,eliminar]);
                        limpiar();
                    } 
                    });

                    
                }, 'json');
            }
        } else {
            if ($municipio.val() == 0) {
                $municipio.addClass('has-error');
            } else if ($sector.val() === null || $sector.val().length === 0 || /^\s+$/.test($sector.val())) {
                $div_sector.addClass('has-error');
                $sector.focus();
            }else {
                window.parent.apprise('&iquest;Desea Modificar los datos del Registro?', {'verify': true, 'textYes': 'Aceptar', 'textNo': 'Cancelar'}, function(r) {
                    if (r) {

                        $('#accion').val(accion);
                        $.post(url, $('#frmsector').serialize(), function(data) {

                            var cod_msg = parseInt(data.error_codmensaje);
                            var mensaje = data.error_mensaje;
                            var tipo = data.tipo_error;

                            window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                            if (cod_msg === 22) {

                                var fila = $('#fila').val();
                                var municipio = $municipio.find('option').filter(":selected").html();

                                $("#tabla tbody tr:eq(" + fila + ")").find("td:eq(0)").html(municipio);
                                $("#tabla tbody tr:eq(" + fila + ")").find("td:eq(1)").html($sector.val());


                                $('#fila').remove();
                                $('#accion').remove();
                                $('#cod_sector').remove();
                                limpiar();
                            }
                        }, 'json');
                    } else {
                        limpiar();
                    }
                });
            }
        }

    });

    // Modificar
    $('table#tabla').on('click', 'img.modificar', function() {
        $('#fila').remove();
        $('#accion').remove();
        $('#cod_sector').remove();

        var $padre = $(this).closest('tr');
        var fila = $padre.index();

        var codigo = $(this).attr('id');

        var codigo = codigo.split(',');
        var cod_mun = codigo[0];
        var cod_sec = codigo[1];

        var sector = $(this).parents('tr').children('td').eq(1).text();

        var $fila = '<input type="hidden" id="fila"  value="' + fila + '" name="fila">';
        $($fila).prependTo($('form#frmsector #btnaccion'));

        var $cod_sector = '<input type="hidden" id="cod_sector"  value="' + cod_sec + '" name="cod_sector">';
        $($cod_sector).prependTo($('form#frmsector #btnaccion'));

        $municipio.select2('val', cod_mun);
        $sector.val(sector);
        $('#btnaccion').val('Modificar');
    });

    $('table#tabla').on('click', 'img.eliminar', function() {
        var cod_sector = $(this).attr('id');
        var nRow = $(this).parents('tr')[0];
        limpiar();
        window.parent.apprise('&iquest;Desea Eliminar los datos del Registro?', {'verify': true, 'textYes': 'Aceptar', 'textNo': 'Cancelar'}, function(r) {
            if (r) {
                $.post(url, {cod_sector: cod_sector, accion: 'Eliminar'}, function(data) {
                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    var tipo = data.tipo_error;
                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                    if (cod_msg === 23) {
                        TSector.fnDeleteRow(nRow);
                    }
                },'json');
            }
        });
    });

    $('#btnlimpiar').on('click', function() {
        limpiar();
    });

});

function limpiar() {
    $('form#frmsector select#municipio').select2('val', 0);
    $('form#frmsector #sector').val('');
    $('div,select').removeClass('has-error');
    $('#accion').remove();
    $('#btnaccion').val('Agregar');
}
