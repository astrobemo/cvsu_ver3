function reset_number_format(number_data){
    if(number_data == '' || number_data == null )
        number_data = 0;
    if (number_data != 0) {
        // alert(number_data); 
        number_data = number_data.toString();
        number_data = number_data.replace(/\./g,'');
    };
    number_data = parseInt(number_data);
    return number_data;
}


function qty_general(number_data){
    
    number_data = number_data.replace('.00','');
    return number_data;
}


function change_number_format(number_data){
    // var no_break = number_data.split('.');

    var number_asli = number_data;
    if (number_asli < 0) {
        number_data = number_asli * -1;
    };

    // alert(number_data+'='+number_data.toString().length);
    if (number_data.toString().length > 3 ) {

        number_data = number_data.toString();
        number_data = number_data.replace(/\./g,'');
        var byk_digit = number_data.length;
        if (byk_digit != 1) {
            var pembagi = parseInt(byk_digit / 3);        
        }else{
            var pembagi = 1;
        };
        var nilai = [];

        var sisa = byk_digit - (pembagi * 3);
        if (sisa == 0){
            nilai['first'] = '';
        }else{
            nilai['first'] = number_data.substr(0,sisa);
        }

        var number_data_new = number_data.substr(sisa, byk_digit);

        for(var i = 0; i < pembagi; i++){
            var y = i * 3;
            nilai[i] = number_data_new.substr(y,3);
        }

        var nilai_format = '';
        for(var i = 0; i < pembagi; i++){
            if(i == 0){
                if(nilai['first'] != ''){
                    nilai_format = nilai['first'];
                    nilai_format = nilai_format + '.' + nilai[i];
                }else{
                    nilai_format = nilai[i];    
                }
                
            }else{
                nilai_format = nilai_format +'.'+nilai[i];
            }

        }
    }else{
        nilai_format = number_data;
    }

    if (number_asli < 0) {
        nilai_format = '-'+nilai_format;
    };

    return nilai_format;
}

function reset_number_format2(number_data){
    if(number_data == '' || number_data == null )
        number_data = 0;
    if (number_data != 0) {
        // alert(number_data); 
        number_data = number_data.toString();
        number_data = number_data.replace(/\,/g,'');
    };
    number_data = parseInt(number_data);
    return number_data;
}

function change_number_format2(number_data){

    // var no_break = number_data.split('.');

    var number_asli = number_data;
    if (number_asli < 0) {
        number_data = number_asli * -1;
    };

    // alert(number_data+'='+number_data.toString().length);
    if (number_data.toString().length > 3 ) {

        number_data = number_data.toString();
        number_data = number_data.replace(/\,/g,'');
        var byk_digit = number_data.length;
        if (byk_digit != 1) {
            var pembagi = parseInt(byk_digit / 3);        
        }else{
            var pembagi = 1;
        };
        var nilai = [];

        var sisa = byk_digit - (pembagi * 3);
        if (sisa == 0){
            nilai['first'] = '';
        }else{
            nilai['first'] = number_data.substr(0,sisa);
        }

        var number_data_new = number_data.substr(sisa, byk_digit);

        for(var i = 0; i < pembagi; i++){
            var y = i * 3;
            nilai[i] = number_data_new.substr(y,3);
        }

        var nilai_format = '';
        for(var i = 0; i < pembagi; i++){
            if(i == 0){
                if(nilai['first'] != ''){
                    nilai_format = nilai['first'];
                    nilai_format = nilai_format + ',' + nilai[i];
                }else{
                    nilai_format = nilai[i];    
                }
                
            }else{
                nilai_format = nilai_format +','+nilai[i];
            }

        }
    }else{
        nilai_format = number_data;
    }

    if (number_asli < 0) {
        nilai_format = '-'+nilai_format;
    };

    return nilai_format;
}

/*====================number comma===============*/

function reset_number_comma(number_data){
    if(number_data == '' && number_data == null )
        number_data = 0;
    if (number_data != 0) {
        // alert(number_data); 
        number_data = number_data.toString();
        if ( number_data.length > 4 ) {
            // number_data = number_data.replace(/\,/g,'');
            number_data = number_data.replace(/\,/g,'');
        };
    };
    // number_data = parseFloat(number_data);
    if (number_data == '') {
        number_data = 0;
    };
    return number_data;
}

function change_number_comma(number_get){
    if (number_get != '0' && number_get != '0.00' && number_get != null && number_get != '' && number_get != 0) {

        // alert(number_get + ':'+number_get.toString().length);

        number_get = number_get.toString();
        if (number_get.length >= 6) {

            number_break = number_get.split('.');
            number_data = number_break[0];
            number_data = number_data.replace(/\,/g,'');
            var byk_digit = number_data.length;
            var pembagi = parseInt(byk_digit / 3);
            var nilai = [];

            var sisa = byk_digit - (pembagi * 3);
            if (sisa == 0){
                nilai['first'] = '';
            }else{
                nilai['first'] = number_data.substr(0,sisa);
            }

            var number_data_new = number_data.substr(sisa, byk_digit);

            for(var i = 0; i < pembagi; i++){
                var y = i * 3;
                nilai[i] = number_data_new.substr(y,3);
            }

            var nilai_format = '';
            for(var i = 0; i < pembagi; i++){
                if(i == 0){
                    if(nilai['first'] != ''){
                        nilai_format = nilai['first'];
                        nilai_format = nilai_format + ',' + nilai[i];
                    }else{
                        nilai_format = nilai[i];    
                    }
                    
                }else{
                    nilai_format = nilai_format +','+nilai[i];
                }
                // alert(nilai_format);
            }

            var koma = (number_break[1] == '' || typeof number_break[1] === 'undefined' ? '00' : number_break[1] );
            // alert(koma);
            nilai_format = nilai_format;

            if (koma != '00') {
                // alert(koma);
                nilai_format = nilai_format+'.'+koma;
            }else{
                // alert(nilai_format);
                nilai_format = nilai_format;
            }
        }else{
            nilai_format = number_get;
        }
    }else{
        var nilai_format = '0.00';
    }

    return nilai_format;
}


/*===============================================*/


function qty_float_number(number){
    var angka = number.toString();
    new_number = angka.split('.');
    if (parseInt(new_number[1]) == 0 ) {
        return new_number[0];
    }else{
        return angka;
    };
}

function split_join_number(number, separator){
    var angka = number.split(separator);
    var new_number = [];
    $.each(angka,function(i,v){
        new_number[i] = change_number_format(v);
    });

    return new_number.join();
}

function ajax_data(url,data_st){
    var hasil = "fail";
    $.ajax({
        type:"POST",
        url:baseurl+url,
        async:false,
        data:data_st,
        success: function(data)
        {
            hasil = data;
        }
    });
    return hasil;
}

function ajax_data_sync(url,data_st){
    // var hasil = "fail";
    return $.ajax({
        type:"POST",
        url:baseurl+url,
        async:true,
        data:data_st//,
        
    });
}

$(function(){
});


jQuery(document).ready(function() {   

    $('.menu_admin li .title, .menu_admin li .fa, .menu_admin li ul li a, .menu_admin li i').css('color','white');
    $('.menu-toggler').css({'opacity':'1'});

    $('.date-picker, .date-picker2').datepicker({
        autoclose : true,
        format: "dd/mm/yyyy"
    });

    $('.date-picker-hour, .date-picker2').datepicker({
        autoclose : true,
        format: "dd/mm/yyyy"
    });

    $('.date-picker-month').datepicker({
        autoclose : true,
        format: "MM yyyy"
    });

    // $(".btn-trigger").click(function(){
    //     btn_disabled_load($(this));
    // });


    $('.btn-test').click(function(){
        var callbacks = $.Callbacks( "once" );
        callbacks.add(change_logo);
        callbacks.fire("TEST");
    });

    // $('#barang_id_select').select2({
    //     placeholder: "Pilih...",
    //     allowClear: true
    // });


    $(document).on('click','.qty_number',function(){
        if($(this).val() == 0){
            $(this).val('');
        }
    });

    $(document).on('focusin','.qty_number',function(){
        if($(this).val() == 0){
            $(this).val('');
        }
    });

    $(document).on('focusout','.qty_number',function(){
        if($(this).val() == ''){
            $(this).val(0);
        }else{
            $(this).val($(this).val().replace(',','.'));
        };
    });


    /*=======================================================*/
    $(document).on('click','.amount_number',function(){
        if($(this).val() == 0){
            $(this).val('');
        }else if($(this).val() != ''){
            var value = $(this).val();
            value_reset_format = reset_number_format(value);
            // alert(value_reset_format);
            $(this).val(value_reset_format);
        }
    });

    $(document).on('focusin','.amount_number', function(){
        if($(this).val() == 0){
            $(this).val('');
        }else if($(this).val() != ''){
            var value = $(this).val();
            value_reset_format = reset_number_format(value);
            $(this).val(value_reset_format);
        }
    });

    $(document).on('focusout','.amount_number',function(){
        if($(this).val() == ''){
            $(this).val(0);
        }else if($(this).val() != ''){
            var value = $(this).val();
            value_reset_format = change_number_format(value);
            $(this).val(value_reset_format);
        }
    });
    /*========================amount_number2===============================*/
    $(document).on('click','.amount_number2',function(){
        if($(this).val() == 0){
            $(this).val('');
        }else if($(this).val() != ''){
            var value = $(this).val();
            value_reset_format = reset_number_format2(value);
            // alert(value_reset_format);
            $(this).val(value_reset_format);
        }
    });

    $(document).on('focusin','.amount_number2', function(){
        if($(this).val() == 0){
            $(this).val('');
        }else if($(this).val() != ''){
            var value = $(this).val();
            value_reset_format = reset_number_format2(value);
            $(this).val(value_reset_format);
        }
    });

    $(document).on('focusout','.amount_number2',function(){
        if($(this).val() == ''){
            $(this).val(0);
        }else if($(this).val() != ''){
            var value = $(this).val();
            value_reset_format = change_number_format2(value);
            $(this).val(value_reset_format);
        }
    });

    /*==========================comma=============================*/
    $(document).on('click','.amount_number_comma',function(){
        if($(this).val() == 0){
            $(this).val('');
        }else if($(this).val() != ''){
            var value = $(this).val();
            value_reset_format = reset_number_comma(value);
            // alert(value_reset_format);
            $(this).val(value_reset_format);
        }
    });

    $(document).on('focusin','.amount_number_comma', function(){
        if($(this).val() == 0){
            $(this).val('');
        }else if($(this).val() != ''){
            var value = $(this).val();
            value_reset_format = reset_number_comma(value);
            $(this).val(value_reset_format);
        }
    });

    $(document).on('focusout','.amount_number_comma',function(){
        if($(this).val() == ''){
            $(this).val(0);
        }else if($(this).val() != ''){
            var value = $(this).val();
            value_reset_format = change_number_comma(value);
            $(this).val(value_reset_format);
            // alert(value_reset_format);
        }
    });
    /*=======================================================*/
    


    $(document).on('click','.amount-number', function(){
        $('.amount-number').number(true,0);
    });

    $(document).on('change','.amount-number', function(){
        $('.amount-number').number(true,0); 
    });

    



    $('.btn-form-add').click(function(){
        setTimeout(function(){
            $('#form_add_data .input1').focus();
        },700);
    });

    var timer = 18000000;

    // setTimeout(function(){
    //     window.location.replace(baseurl+'home/logout');
    // },1800000);

    $('.btn-edit').click(function(){
        setTimeout(function(){
            $('#form_edit_data .edit1').focus();
        },700);
    })

    $('[name=status_aktif_select]').change(function(){
        var status_aktif = $(this).val();
        var status_lain = 0;
        if (status_aktif == 0) {status_lain = 1};
        $('#general_table .status_aktif_'+status_aktif).show();
        $('#general_table .status_aktif_'+status_lain).hide();
    });


});

function check_double_data(elmnt, nama_data){
    var data_st = {};
    var url = "admin/check_double_data";
    data_st['nama_data'] = nama_data;
    data_st['field'] = elmnt.attr('name');
    data_st['value'] = elmnt.val();

    ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
        if (data_respond != '') {
            elmnt.closest('div').find(".check-double-info").html("similar : "+data_respond);
        }else{
            elmnt.closest('div').find(".check-double-info").html('');
        };
    });
}

function change_logo(value){
    $('.page-logo h1').html(value);   
}


function addCommas(nStr)
{
    nStr += '';
    nStr = nStr.replace('.','',nStr);
    x = nStr.split(',');
    x1 = x[0];
    x2 = x.length > 1 ? ',' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        // console.log(x1);
        x1 = x1.replace(rgx, '$1' + '.' + '$2');
        // console.log(x1);

    }
    return x1 + x2;
}


function dataTableTrue(){
    TableAdvanced.init();    
    var oTable;
    oTable = $('#general_table').dataTable();
    oTable.fnFilter( 1, 0 );

    $('#status_aktif_select').change(function(){
        oTable.fnFilter( $(this).val(), 0 ); 
    });
}

function date_format_default(date){
    var tgl = date.split('/');
    return tgl[2]+'-'+tgl[1]+'-'+tgl[0];
}


function date_formatter(date){
    var tgl = date.split('-');
    return tgl[2]+'/'+tgl[1]+'/'+tgl[0];
}

function date_formatter_month_name(date){
    var monthNames = ["January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December"];
    var tgl = date.split('-');
    return tgl[2]+' '+monthNames[tgl[1]-1]+' '+tgl[0];
}


function notific8(theme_color, message){
    var settings = {
        theme: theme_color,
        sticky: false,
        horizontalEdge: "bottom",
        verticalEdge: "right",
        heading: "Message",
        life: 5000
    };
    $.notific8('zindex', 11500);
    $.notific8(message, settings);
}

function status_aktif_get(ini){
    var id = ini.find('.id').html();
    var status_aktif = ini.find('.status_aktif').html();
    if (status_aktif == 1) {
        status_aktif = 0;
    }else{
        status_aktif = 1;
    }
    return status_aktif+'=?='+id;
}

function shuffle(o) {
    for(var j, x, i = o.length; i; j = parseInt(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
    return o;
};