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

    $('select#consultorio').change(function() {
        var marcado = $("input:checkbox#hoy:checked").length;
        if ($(this).val() > 0) {
            $('#btnaccion').prop('disabled', false);
        } else if ($(this).val() == 0 && marcado == 0 && $('#desde').val() == '' && $('#hasta').val() == '') {
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
    var url_m = '../../controlador/medico/medico.php';
    $('#btnbuscar_m').on('click', function() {
        var cedula_pm = $('#cedula_pm').val();
        if (cedula_pm != '') {
            $.post(url_m, {cedula_pm: cedula_pm, accion: 'BuscarDatos'}, function(resultado) {
                if(resultado != 0){
                    $('#nombre').val(resultado.nombre+' '+resultado.apellido);
                    $('#btnaccion').prop('disabled', false);
                }else{
                   window.parent.apprise('<span style="color:#FF0000">El N&uacute;mero de C\u00e9dula no se encuentra registrado</span>', {'textOk': 'Aceptar'}, function() {
                        $('#div_cedula_pm').addClass('has-error');
                        $('#cedula_pm').focus();
                    }); 
                }
            },'json');
        } else {
            window.parent.apprise('Debe ingresar el Num&eacute;ro de C&eacute;dula', {'textOk': 'Aceptar'}, function() {
                $('#div_cedula_pm').addClass('has-error');
                $('#cedula_pm').focus();
            });
        }
    });
    
    $('#btnaccion').click(function() {
        var data = "";
        var cons = $('select#consultorio').find(':selected').val();
        var fecha = $('#fecha').val();
        var cedula_pm = $('#cedula_pm').val();
      
        var url = 'reportes_consulta_ex.php?cons=' +cons+'&cedula_pm='+cedula_pm+'&fecha='+fecha;
        window.open(url);
    });
});