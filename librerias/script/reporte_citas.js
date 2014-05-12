jQuery.extend(jQuery.fn.dataTableExt.oSort, {
    "date-uk-pre": function(a) {
        var ukDatea = a.split('-');
        return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
    },
    "date-uk-asc": function(a, b) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },
    "date-uk-desc": function(a, b) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
});

$(document).ready(function() {
    var TAsignar = $('#tabla_asignar').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "aaSorting": [[0, "desc"]],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sType": "date-uk"},
            {"sClass": "center", "sWidth": "20%"},
            {"sClass": "center", "sWidth": "8%"},
            {"sClass": "center", "sWidth": "40%"},
            {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });

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
    
    $('#desde').datepicker({
        language: "es",
        format: 'dd/mm/yyyy',
        weekStart: 1,
        daysOfWeekDisabled: "0,6",
        startDate: '01/01/' + ano,
        endDate: ToEndDate,
        autoclose: true,
        clearBtn:true,
        //todayBtn: "linked",
        orientation: "top auto"
    }).on('changeDate', function(selected,e) {
        $('#l_hoy').removeClass('success');
        $('#l_hoy').addClass('disabled');
        $('#btnaccion').prop('disabled',false);
        $('#hasta').val('');
        var consul = $('select#consultorio').find('option:selected').val();
        var ps = $('select#ps').find('option:selected').val();
        if ($(this).val() != '') {
            startDate = new Date(selected.date.valueOf());
            startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())) + 1);
            $('#hasta').datepicker('setStartDate', startDate);
        }else if($(this).val() == '' && $('#hasta').val() == '' && consul == 0 && ps == 0){
            var FromEndDate = new Date();
            var ToEndDate = new Date();
            ToEndDate.setDate(ToEndDate.getDate() + 90);
            $('#desde').datepicker('setEndDate', ToEndDate);
            $('#l_hoy').removeClass('disabled');
            $('#btnaccion').prop('disabled', true);
        }
    });
    
    $('#hasta').datepicker({
        language: "es",
        format: 'dd/mm/yyyy',
        weekStart: 1,
        daysOfWeekDisabled: "0,6",
        startDate: startDate,
        endDate: ToEndDate,
        autoclose: true,
        clearBtn:true,
        //todayBtn: "linked",
        orientation: "top auto"
    }).on('changeDate', function(selected) {
        $('#l_hoy').removeClass('success');
        $('#l_hoy').addClass('disabled');
        $('#btnaccion').prop('disabled',false);
        var consul = $('select#consultorio').find('option:selected').val();
        var ps = $('select#ps').find('option:selected').val();
        if ($(this).val() != '') {
            //FromEndDate = new Date(selected.date.valueOf());
            //FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf()))-1);
            var FromEndDate = new Date();
            var ToEndDate = new Date();
            ToEndDate.setDate(ToEndDate.getDate() + 90);
            $('#desde').datepicker('setEndDate', ToEndDate);
        }else if($(this).val() == '' && $('#desde').val() == '' && consul == 0 && ps == 0){
            var FromEndDate = new Date();
            var ToEndDate   = new Date();
            ToEndDate.setDate(ToEndDate.getDate() + 90);
            $('#desde').datepicker('setEndDate', ToEndDate);
            $('#l_hoy').removeClass('disabled');
            $('#btnaccion').prop('disabled', true);
        }
    });


    $('input:checkbox#hoy').change(function() {
        var este = $(this);
        var padre = este.closest('label');
        var seleccionado = $('select#consultorio').find(':selected').val();
        if (este.is(':checked')) {
            $('#btnaccion').prop('disabled',false);
            $('#desde').prop('disabled', true).val('');
            $('#hasta').prop('disabled', true).val('');
            padre.addClass('btn-success');
        } else{
            $('#desde').prop('disabled', false);
            $('#hasta').prop('disabled', false);
             padre.removeClass('btn-success');
            if(seleccionado == 0 && $('#desde').val() == '' && $('#hasta').val() == ''){
                $('#btnaccion').prop('disabled',true);
            }
           
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
        var hoy  = $('input:checkbox#hoy:checked').length;
        var cons = $('select#consultorio').find(':selected').val();
        var ps   = $('select#ps').find(':selected').val();
        if (hoy > 0) {
            var data = '&hoy=' + hoy;
        }
        if($('#desde').val() != ''){
            data += '&desde='+$('input:text#desde').val();
        }
        if($('#hasta').val() != ''){
            data += '&hasta='+$('input:text#hasta').val();
        }
        if(cons > 0){
             data += '&cons='+cons;
        }
        if(ps != 0){
             data += '&ps='+ps;
        }
        var url = 'reporte_cita.php?id=0' + data;
        window.open(url);
    });
});