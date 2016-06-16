$(document).ready(function(){
    $('#myModal').on('show.bs.modal', function (event) {
        var element = $(event.relatedTarget) // Button that triggered the modal
        var productName = element.data('productname');
        var product_id = element.data('productid');
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        modal.find('#productName').text(productName);
        modal.find('#productId').val(product_id);
    });

    $("#deleteForm").click(function(e){

        var product_id = $("#productId").val();
        var promotion_id = $("#promotionId").val();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        var dataString = 'product_id='+product_id;
        $.ajax({
            url : baseUrl()+"/admin/promotions/" + promotion_id + '/product/delete',
            method: 'post',
            dataType: 'json',
            data: {_token: CSRF_TOKEN,'product_id':product_id },
            success : function(data){
                if(data.status == 1){
                    $('#myModal').modal('hide');
                    $('#productRow'+data.product_id).fadeOut( "slow", function() {
                        // Animation complete.
                    });
                }else{
                    console.log('Some thing wrong in delete Product!');
                }

            }
        },"html");
    });

    /* Process confirm box remove lead promotion*/
    $('#myModalLead').on('show.bs.modal', function (event) {
        var element = $(event.relatedTarget) // Button that triggered the modal
        var leadName = element.data('leadname');
        var lead_id = element.data('leadid');
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        modal.find('#leadName').text(leadName);
        modal.find('#leadId').val(lead_id);
    });


    $("#deleteFormLead").click(function(e){

        var lead_id = $("#leadId").val();
        var promotion_id = $("#promotionId").val();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url : baseUrl()+"/admin/promotions/" + promotion_id + '/lead/delete',
            method: 'post',
            dataType: 'json',
            data: {_token: CSRF_TOKEN, 'lead_id': lead_id },
            success : function(data){
                if(data.status == 1){
                    $('#myModalLead').modal('hide');
                    $('#leadRow'+data.lead_id).fadeOut( "slow", function() {
                        // Animation complete.
                    });
                }else{
                    console.log('Some thing wrong in delete Product!');
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