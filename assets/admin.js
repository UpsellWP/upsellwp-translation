jQuery(function ($) {
    let uwp_tr_ajx_url = uwp_tr_admin_script_data.ajax_url;
    let uwp_tr_nonce = uwp_tr_admin_script_data.nonce;

    const uwp_tr_admin_view = {
        init: function () {
            this.event_listeners();
        },

        event_listeners: function () {
            $('#uwp-tr-wpml').on('click', function () {
                $.ajax({
                    url: uwp_tr_ajx_url,
                    type: 'post',
                    data: {
                        action: 'uwp_tr_add_custom_strings_to_wpml',
                        nonce: uwp_tr_nonce || false,
                    },
                    success: function (response) {
                        if (response.success) {
                            cuw_page.notify(response.data.message);
                        } else {
                            cuw_page.notify(response.data.message, 'error');
                        }
                    }
                });
            });
        }
    }

    /* Init */
    $(document).ready(function () {
        uwp_tr_admin_view.init();
    });
});