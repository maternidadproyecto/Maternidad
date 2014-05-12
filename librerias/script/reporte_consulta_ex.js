$(document).ready(function() {

    $('#consultorio').select2();
    $('#ps').select2();
    var ano = (new Date).getFullYear();
    ano = parseInt(ano);
    var enero = '01-01-' + ano;
    var fech_desde = '';

    var startDate = new Date('01/01/' + ano);
    var FromEndDate = new Date();
    var ToEndDate = new Date();
    ToEndDate.setDate(ToEndDate.getDate() + 90);

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

    $('select#ps').change(function() {
        var marcado = $("input:checkbox#hoy:checked").length;
        if ($(this).val() != 0) {
            $('#btnaccion').prop('disabled', false);
        } else if ($(this).val() == 0 && marcado == 0 && $('#desde').val() == '' && $('#hasta').val() == '') {
            $('#btnaccion').prop('disabled', true);
        }
    });

    $('#btnaccion').click(function() {
        var data = "";
        var hoy = $('input:checkbox#hoy:checked').length;
        var cons = $('select#consultorio').find(':selected').val();
        var ps = $('select#ps').find(':selected').val();
        if (hoy > 0) {
            var data = '&hoy=' + hoy;
        }
        if ($('#desde').val() != '') {
            data += '&desde=' + $('input:text#desde').val();
        }
        if ($('#hasta').val() != '') {
            data += '&hasta=' + $('input:text#hasta').val();
        }
        if (cons > 0) {
            data += '&cons=' + cons;
        }
        if (ps != 0) {
            data += '&ps=' + ps;
        }
        var url = 'reporte_cita.php?id=0' + data;
        window.open(url);
    });
});