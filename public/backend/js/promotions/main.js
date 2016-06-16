$(function () {
    $("[data-mask]").inputmask();
    datePromotions();

    $( "input.customer-name" ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                dataType: "json",
                type : 'Get',
                data: {'text_search':$('#nameCustomer').val() },
                url: '/admin/all-customer',
                success: function(data) {
                    $('input.suggest-user').removeClass('ui-autocomplete-loading');
                    // hide loading image

                    // $( "input.customer-name" ).val();

                    response( $.map( data.customers, function(item) {
                        // your operation on data
                        return {
                            value: item.id,
                            label: item.name
                        }
                    }));

                    // response(data.customers.name);
                },
                error: function(data) {
                    $('input.suggest-user').removeClass('ui-autocomplete-loading');
                }
            });
        },
        minLength: 2,
        change: function (event, ui) {
            if (ui.item == null){
                //here is null if entered value is not match in suggestion list
                $(this).val((ui.item ? ui.item.label : ""));
            }
        },
        open: function() {},
        select: function(event, ui) {
            setTimeout(function(){
                $('#nameCustomer').val(ui.item.label);
            });

            $('#name').val(ui.item.value);
        },
        close: function() {},
        focus: function(event, ui) {}
    });

    $('#createCustomer').on('show.bs.modal', function (event) {
        var element = $(event.relatedTarget) // Button that triggered the modal
        $('#error_first_name').html('');
        $('#error_last_name').html('');
        $('#error_email').html('');
        $('#error_mobile_phone').html('');
        $('#error_suburb').html('');
        $('#error_postcode').html('');
    });

    $('#createNewCustomer').click(function () {

        var first_name = $('#first_name').val();
        var last_name = $('#last_name').val();
        var email = $('#email_box').val();
        var mobile_phone = $('#mobile_phone').val();
        var state = $('#state').val();
        var suburb = $('#suburb').val();
        var postcode = $('#postcode').val();

        var datas = {first_name: first_name, last_name: last_name, email: email, mobile_phone: mobile_phone, state: state, suburb: suburb, postcode: postcode};

        var CSRF_TOKEN = $('#tokenID').val();

        $.ajax({
            url : baseUrl()+"/admin/customers/create",
            method: 'post',
            dataType: 'json',
            data: {_token: CSRF_TOKEN, 'datas': datas, 'post_type': 1 },
            success : function(data){
                console.log('data '+data);
                if(data.status == 1){
                    $('#createCustomer').modal('hide');
                    $('#nameCustomer').val(data.customer_name);
                    $('#name').val(data.customer_id);


                }else{
                    var errors = data.validator;
                    if(errors.first_name) {
                        $('#error_first_name').html(errors.first_name);
                    }else{
                        $('#error_first_name').html('');
                    }
                    if(errors.last_name){
                        $('#error_last_name').html(errors.last_name);
                    }else{
                        $('#error_last_name').html('');
                    }

                    if(errors.email){
                        $('#error_email').html(errors.email);
                    }else{
                        $('#error_email').html('');
                    }

                    if(errors.mobile_phone){
                        $('#error_mobile_phone').html(errors.mobile_phone);
                    }else{
                        $('#error_mobile_phone').html('');
                    }

                    if(errors.suburb){
                        $('#error_suburb').html(errors.suburb);
                    }else{
                        $('#error_suburb').html('');
                    }
                    if(errors.postcode){
                        $('#error_postcode').html(errors.postcode);
                    }else{
                        $('#error_postcode').html('');
                    }
                }

            }
        },"html");


    });

});

<!-- Define base url -->
function baseUrl(){
    pathArray = location.href.split( '/' );
    protocol = pathArray[0];
    host = pathArray[2];
    url = protocol + '//' + host;
    return url;
}

function datePromotions() {
    $('#promotionForm .input-group.date').datepicker({
        format: "dd/mm/yyyy"
    });

    $('#LeadForm .input-group.date').datepicker({
        format: "dd/mm/yyyy"
    });
}

