$(document).ready(function() {

    $('#consultorio').select2();
    $('#ps').select2();
    var ano = (new Date).getFullYear();
    ano = parseInt(ano);


    $('#fecha').datepicker({
        language: "es",
        format: 'dd/mm/yyyy',
        weekStart: 1,
        daysOfWeekDisabled: "0,6",
        startDate: '01/01/' + ano,
        endDate: 'Today',
        autoclose: true,
        clearBtn: true,
        todayBtn: "linked",
        orientation: "top auto"
    }).on('changeDate', function(selected, e) {
        $('#btnaccion').prop('disabled', false);
        var consul = $('select#consultorio').find('option:selected').val();
        var cedula_pm    = $('#cedula_pm').find('option:selected').val();
        if ($(this).val() == '' && consul == 0 && cedula_pm == "") {
            $('#btnaccion').prop('disabled', true);
        }
    });
 
    
    $("ul#nacionalidad_m > li > span").click(function() {
        var nac = $(this).attr('id');
        if (nac != 'N') {
            $('#btn_nac_m').removeClass('btn-danger');
            if (nac == 'V') {
                var tamano = 10;
            } else {
                tamano = 11;
            }
            $('#hnac_m').val(nac);
            $('#cedula_pm').attr('maxlength', tamano).val(nac + '-').focus();
        } else {
            
            $('#hnac_m').val('');
            $('#cedula_pm').val('');
        }
    });
    
    $("ul#nacionalidad_p > li > span").click(function() {
        var nac = $(this).attr('id');
        if (nac != 'N') {
            $('#btn_nac_p').removeClass('btn-danger');
            if (nac == 'V') {
                var tamano = 10;
            } else {
                tamano = 11;
            }
            $('#hnac_p').val(nac);
            $('#cedula_p').attr('maxlength', tamano).val(nac + '-').focus();
        } else {
            
            $('#hnac_p').val('');
            $('#cedula_p').val('');
        }
    });
    
    $("ul#nacionalidad_a > li > span").click(function() {
        var nac = $(this).attr('id');
        if (nac != 'N') {
            $('#btn_nac_a').removeClass('btn-danger');
            if (nac == 'V') {
                var tamano = 10;
            } else {
                tamano = 11;
            }
            $('#hnac_a').val(nac);
            $('#cedula_a').attr('maxlength', tamano).val(nac + '-').focus();
        } else {
            
            $('#hnac_a').val('');
            $('#cedula_a').val('');
        }
    });
    
    var url_m = '../../controlador/medico/medico.php';
    $('#btnbuscar_m,#btnbuscar_p,#btnbuscar_a').on('click', function() {
        var id  = $(this).attr('id');
        var pref = id.split('_');
        if(pref['1'] == 'm'){
            var cedula = 'cedula_pm';
            var nombre = 'nombre';
            var div = 'div_cedula_pm';
        }else if(pref['1'] == 'p'){
            var cedula = 'cedula_p';
            var nombre = 'nombre_p';
            var div = 'div_cedula_p';
        }else if(pref['1'] == 'a'){
            var cedula = 'cedula_a';
            var nombre = 'nombre_a';
            var div = 'div_cedula_a';
        }  
        var cedula_pm = $('#'+cedula).val();
        if (cedula_pm != '') {
            $.post(url_m, {cedula_pm: cedula_pm, accion: 'BuscarDatos'}, function(resultado) {
                if(resultado != 0){
                    $('#'+nombre).val(resultado.nombre+' '+resultado.apellido);
                    $('#btnaccion').prop('disabled', false);
                }else{
                   window.parent.apprise('<span style="color:#FF0000">El N&uacute;mero de C\u00e9dula no se encuentra registrado</span>', {'textOk': 'Aceptar'}, function() {
                        $('#'+div).addClass('has-error');
                        $('#'+cedula).focus();
                    }); 
                }
            },'json');
        } else {
            window.parent.apprise('<span style="color:#FF0000">Debe ingresar el Num&eacute;ro de C&eacute;dula</span>', {'textOk': 'Aceptar'}, function() {
                $('#'+div).addClass('has-error');
                $('#'+cedula).focus();
            });
        }
    });
    
    $('#btnaccion').click(function() {
        var data = "";
        
        var fecha = $('#fecha').val();
        var med = $('#cedula_pm').val();
        var ped = $('#cedula_pm').val();
        var ane = $('#cedula_pm').val();
        var url = 'reporte_morbilidad.php?med=' +med+'&ped='+ped+'&ane='+ane+'&fecha='+fecha;
        window.open(url);
    });
});