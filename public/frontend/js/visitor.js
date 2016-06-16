var Visitor = Visitor || {};

Visitor.actions = {
    /**
     * Send tracking email data
     * @param email
     *
     * */
    tracking: function (email) {
        $.ajax({
            url: '/tracking',
            type: 'POST',
            dataType: "JSON",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr('content'),
                "X-Requested-With": XMLHttpRequest
            },
            data: {
                email: email
            },
            success: function (data) {
            },
            error: function (data) {
            }
        });
    }
};