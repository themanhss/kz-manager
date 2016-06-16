$(function () {
    datePromotions();
    $(document).ready(function () {
        var ckes = $('.wysiwyg-classic');
        ckes.each(function (index) {
            var ele = $('.wysiwyg-classic')[index];
            var id = ele.id;
            CKEDITOR.replace(id);
        });
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
    
    $('#PageForm .input-group.date').datepicker({
        format: "dd/mm/yyyy"
    });
}