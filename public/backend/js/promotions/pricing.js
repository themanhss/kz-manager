$(function () {
    submitPricing();
});

function submitPricing(){

    $('#submitPrice').click(function () {

        var flag = true;

        $('.input-prices').each(function (index,value) {

            var price =  $(value).val();

            if( $.trim(price) == ''){
                $(value).parent().parent().find('.text-danger').html('This field is required!');
                flag = false;
            }else{
                $(value).parent().parent().find('.text-danger').html('');
            }

            if(!$.isNumeric(price) && price!='') {
                $(value).parent().parent().find('.text-danger').html('This field must be a number!');
                flag = false;
            }

        });
        
        if(flag) {
            $('#pricingForm').submit();
        }

    });
};