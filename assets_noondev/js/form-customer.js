var FormAddCustomer = function () {

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
                    nama: {
                        required: true
                    },
                    nik: {
                        maxlength: 20,
                        minlength: 16
                    }
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    error.insertAfter(element); // for other inputs, just perform default behavior
                },

                invalidHandler: function (event, validator) { //display error alert on form submit   
                    success.hide();
                    error.show();
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
                    form.submit();
                    //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
                },

                ignore:[]

            });

            $.extend($.inputmask.defaults, {
                'autounmask': true
            });

            $(".mask_npwp").inputmask("99.999.999.9-999.999", {
                placeholder: " ",
                clearMaskOnLostFocus: true
            }); //default

            $(".mask_nik").inputmask("9999 9999 9999 9999", {
                placeholder: " ",
                clearMaskOnLostFocus: true
            }); //default

            $('.btn-save').click(function(){
                if(form.valid()){
                    $('#form_add_data').submit();
                }
            }); 


            
        }

    };

}();

var FormEditCustomer = function () {

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
                    nama: {
                        required: true
                    },
                    nik: {
                        maxlength: 20,
                        minlength: 16
                    }
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    error.insertAfter(element); // for other inputs, just perform default behavior
                },

                invalidHandler: function (event, validator) { //display error alert on form submit   
                    success.hide();
                    error.show();
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
                    form.submit();
                    //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
                },

                ignore:[]

            });

            $.extend($.inputmask.defaults, {
                'autounmask': true
            });

            $(".mask_npwp_edit").inputmask("99.999.999.9-999.999", {
                placeholder: " ",
                clearMaskOnLostFocus: true
            }); //default

            $(".mask_nik_edit").inputmask("9999 9999 9999 9999", {
                placeholder: " ",
                clearMaskOnLostFocus: true
            }); //default
            
            $('.btn-edit-save').click(function(){
                if(form.valid()){
                    $('#form_edit_data').submit();
                }
            });
        }

    };

}();