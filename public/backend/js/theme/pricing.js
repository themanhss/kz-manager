$(function () {
    addMore();
    savePricing();
});

function addMore() {
    var template = $('#hidden-template').html();

    $('#addMore').click(function () {
        var newField = $('#contentPricing').append(template);
        $('#contentPricing .form-group:last-child .field-tags input').tagsinput('refresh');
    });

    $(document).on('click','.remove-ico',function () {
        var remove = $('#removeID');
        var cur_id = $(this).parent().parent().parent().find('.variant-id');
        if(remove.val()){
            remove.val(remove.val()+','+cur_id.val());
        }else{
            remove.val(cur_id.val());
        }

        $(this).parent().parent().parent().remove();
    });
}

function savePricing() {
    var datas = [];
    var pass = true;
    $(document).on('click','#savePricing',function () {

        var elements = $('#contentPricing .form-group');
        var is_edit = $('#isEdit').val();

        $.each( elements, function( key, value ) {

            var label =  $(value).find('.field-label input').val();
            if(label == null || label == ''){
                pass = false;
                $(value).find('.field-label .text-danger').html('This field is required!')
            }else{
                pass = true;
                $(value).find('.field-label .text-danger').html('')
            }

            var tags =  $(value).find('.field-tags input.main-tags').val();
            if(tags == null || tags == ''){
                pass = false;
                $(value).find('.field-tags .text-danger').html('This field is required!')
            }else{
                pass = true;
                $(value).find('.field-tags .text-danger').html('')
            }
            var variant_id = 0;
            if(is_edit ==1){
                variant_id = $(value).find('input.variant-id').val();
            }


            var obj = {
                'label': label,
                'tags': tags,
                'variant_id': variant_id
            };
            console.log(obj);
            datas.push(obj);
        });

        if(pass){
            CSRF_TOKEN = $('#formToken').val();

            var theme_id = $('#themeID').val();

            $.ajax({
                url : baseUrl()+"/admin/themes/"+theme_id+"/pricing",
                headers: { "X-CSRF-TOKEN": CSRF_TOKEN },
                method: 'post',
                dataType: 'json',
                data: {
                    theme_id: $('#themeID').val(),
                    is_edit: is_edit,
                    remove_id : $('#removeID').val(),
                    datas: JSON.stringify(datas)
                },
                cache : false,
                success : function(data){
                    if(data.status == 1){
                        window.location.href = baseUrl()+"/admin/themes";
                    }else{
                        console.log('Some thing wrong in edit pricing theme!');
                    }

                }
            },"html");

        }

    });

}