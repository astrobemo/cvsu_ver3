var FormNewPembelian = function () {


    return {
        //main function to initiate the module
        init: function () {

            var form = $('#form_add_data');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                rules: {
                    supplier_id: {
                        required: true,
                    },
                    gudang_id: {
                        required: true
                    },
                    tanggal: {
                        required: true
                    },
                    no_faktur: {
                        required: false,
                        checkAvail: true
                    },
                    no_surat_jalan: {
                        required: false,
                        checkAvailSJ: true
                    },
                    keterangan: {
                        required: false,
                    }
                },

                messages: { // custom messages for radio buttons and checkboxes
                    
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    error.insertAfter(element);
                },

                invalidHandler: function (event, validator) { //display error alert on form submit   
                    success.hide();
                    error.show();
                    Metronic.scrollTo(error, -200);
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .addClass('valid') // mark the current input as valid and display OK icon
                    .closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success.show();
                    error.hide();
                    form.submit()
                    //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
                },

                ignore:[]

            });


            var response;
            $.validator.addMethod(
                "checkAvail",  
                function(value, element) {
                    console.log(value);
                    if (value != '') {
                        var data_st = {};
                        data_st['no_faktur'] = value;
                        $.ajax({
                            type: "POST",
                            url: baseurl+"transaction/check_new_faktur_pembelian",
                            async: false,
                            data: data_st,
                            success: function(msg)
                            {   
                                if(msg == 'true'){
                                    response = true;
                                }else{
                                    response = false;
                                }

                                if (value == '') {
                                    response = true;
                                };

                                // alert(response);
                            }
                         });
                    }else{
                        response = true;
                    };
                    return response;
                },
                "No faktur telah terdaftar"
            );

            var responseSJ;
            $.validator.addMethod(
                "checkAvailSJ",  
                function(value, element) {
                    console.log(value);
                    if (value != '') {
                        var data_st = {};
                        data_st['no_surat_jalan'] = value;
                        $.ajax({
                            type: "POST",
                            url: baseurl+"transaction/check_new_surat_jalan",
                            async: false,
                            data: data_st,
                            success: function(msg)
                            {   
                                if(msg == 'true'){
                                    responseSJ = true;
                                }else{
                                    responseSJ = false;
                                }

                                if (value == '') {
                                    responseSJ = true;
                                };

                                // alert(response);
                            }
                         });
                    }else{
                        responseSJ = true;
                    };
                    return responseSJ;
                },
                "No Surat Jalan telah terdaftar"
            );


            $('.btn-save').click(function () {
                var ini = $(this);
                if (form.valid())
                {
                    // alert('OK')
                    $('.btn-save').prop('disabled',true);
                    form.submit();
                    btn_disabled_load(ini);
                }
            });

            $('#form_add_data input').keypress(function (e) {
                if (e.which == 13) {
                    if (form.valid())
                    {
                        // alert('OK')
                        $('.btn-save').prop('disabled',true);
                        form.submit();
                    }
                }
            });
        }

    };

}();

var FormEditPembelian = function () {

    return {
        //main function to initiate the module
        init: function () {

            var form = $('#form_edit_data');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                rules: {
                    supplier_id: {
                        required: true,
                    },
                    gudang_id: {
                        required: true
                    },
                    tanggal: {
                        required: true
                    },
                    no_faktur: {
                        required: false,
                        checkAvailEdit: true
                    },
                    no_surat_jalan: {
                        required: false,
                        checkAvailEditSJ: true
                    },
                    keterangan: {
                        required: false,
                    }
                },

                messages: { // custom messages for radio buttons and checkboxes
                    
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    error.insertAfter(element);
                },

                invalidHandler: function (event, validator) { //display error alert on form submit   
                    success.hide();
                    error.show();
                    Metronic.scrollTo(error, -200);
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .addClass('valid') // mark the current input as valid and display OK icon
                    .closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success.show();
                    error.hide();
                    form.submit()
                    //add here some ajax code to submit your form or just call form_edit.submit() if you want to submit the form without ajax
                },

                ignore:[]

            });


            var response=false;
            $.validator.addMethod(
                "checkAvailEdit",  
                function(value, element) {
                    if (value != '') {
                        var data_st = {};
                        data_st['no_faktur'] = value;
                        data_st['pembelian_id'] = $('#form_edit_data [name=pembelian_id]').val();
                        $.ajax({
                            type: "POST",
                            url: baseurl+"transaction/check_new_faktur_pembelian",
                            async: false,
                            data: data_st,
                            success: function(msg)
                            {   
                                if(msg == 'true'){
                                    response = true;
                                }else{
                                    response = false;
                                }
                                // alert(msg);
                            }
                        });
                    }else{
                        response = true;
                    };
                    return response;
                },
                "No faktur sudah terdaftar"
            );

            var responseSJ=false;
            $.validator.addMethod(
                "checkAvailEditSJ",  
                function(value, element) {
                    if (value != '') {
                        var data_st = {};
                        data_st['no_surat_jalan'] = value;
                        data_st['pembelian_id'] = $('#form_edit_data [name=pembelian_id]').val();
                        $.ajax({
                            type: "POST",
                            url: baseurl+"transaction/check_edit_surat_jalan",
                            async: false,
                            data: data_st,
                            success: function(msg)
                            {   
                                if(msg == 'true'){
                                    responseSJ = true;
                                }else{
                                    responseSJ = false;
                                }
                                // alert(msg);
                            }
                        });
                    }else{
                        responseSJ = true;
                    };
                    return responseSJ;
                },
                "No surat jalan sudah terdaftar"
            );


            $('.btn-edit-save').click(function () {
                if (form.valid())
                {
                    // alert('OK')
                    $('.btn-edit-save').prop('disabled',true);
                    $("#form_edit_data").submit();
                }
            });

            $('#form_edit_data input').keypress(function (e) {
                if (e.which == 13) {
                    if (form.valid())
                    {
                        // alert('OK')
                        $('.btn-edit-save').prop('disabled',true);
                        form.submit();
                    }
                }
            });
        }

    };

}();

var FormNewPembelianDetail = function () {


    return {
        //main function to initiate the module
        init: function () {

            var form = $('#form_add_barang');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                rules: {
                    barang_id: {
                        required: true,
                    },
                    warna_id: {
                        required: true
                    },
                    harga_beli: {
                        required: true
                    },
                    qty: {
                        required: true,
                    },
                    jumlah_roll:{
                        required: true,  
                    }
                },

                messages: { // custom messages for radio buttons and checkboxes
                    
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    error.insertAfter(element);
                },

                invalidHandler: function (event, validator) { //display error alert on form submit   
                    success.hide();
                    error.show();
                    Metronic.scrollTo(error, -200);
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .addClass('valid') // mark the current input as valid and display OK icon
                    .closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success.show();
                    error.hide();
                    form.submit()
                    //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
                },

                ignore:[]

            });


            $('.btn-add-qty').click(function () {
                if (form.valid())
                {
                    $('#portlet-config-qty').modal('toggle');
                    setTimeout(function(){
                        $('#qty-table').find('.input1').focus();
                    },700);
                }
            });

            $('#form_add_barang input').keypress(function (e) {
                if (e.which == 13) {
                    if (form.valid())
                    {
                        // $('#portlet-config-detail').modal('toggle');
                        $('#portlet-config-qty').modal('toggle');
                        setTimeout(function(){
                            $('#qty-table').find('.input1').focus();
                        },700);
                    }
                }
            });
        }

    };

}();