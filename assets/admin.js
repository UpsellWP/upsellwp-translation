jQuery(function ($) {
    let uwp_tr_ajx_url = uwp_tr_admin_script_data.ajax_url;
    let uwp_tr_nonce = uwp_tr_admin_script_data.nonce;
    let uwp_tr_loco_translate = uwp_tr_admin_script_data.loco_translate || [];
    let uwp_tr_i18n = uwp_tr_admin_script_data.i18n;

    const uwp_tr_admin_view = {
        init: function () {
            this.event_listeners();
        },

        event_listeners: function () {
            // to add dynamic strings to wpml.
            $('#uwp-tr-wpml').on('click', function () {

                $('#uwp-tr-wpml').prop('disabled', true);
                $('#uwp-tr-wpml').text(uwp_tr_i18n.syncing);

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
                            $('#uwp-tr-wpml').text(uwp_tr_i18n.synced);
                        } else {
                            cuw_page.notify(response.data.message, 'error');
                        }
                    }
                });
            });

            // to add dynamic strings in Loco translator.
            $('#uwp-tr-loco-sync').on('click', function () {

                $('#uwp-tr-loco-sync').prop('disabled', true);
                $('#uwp-tr-loco-sync').text(uwp_tr_i18n.syncing);

                $.ajax({
                    url: uwp_tr_loco_translate.endpoint_url || '',
                    type: 'post',
                    data: {
                        'bundle': uwp_tr_loco_translate.bundle || '',
                        'domain': uwp_tr_loco_translate.domain || '',
                        'type': uwp_tr_loco_translate.sync_data.type || '',
                        'path': uwp_tr_loco_translate.path || '',
                        'sync': uwp_tr_loco_translate.sync_data.sync || '',
                        'mode': uwp_tr_loco_translate.sync_data.mode || '',
                        'action': uwp_tr_loco_translate.action || '',
                        'route': uwp_tr_loco_translate.sync_data.route || '',
                        'loco-nonce': uwp_tr_loco_translate.sync_data.loco_nonce || '',
                    },
                    success: function (response) {
                       if (response.data.done != '') {
                            $.ajax({
                               url: uwp_tr_loco_translate.endpoint_url || '',
                                type: 'post',
                                data: {
                                   'locale' : uwp_tr_loco_translate.save_data.locale || '',
                                    'path' : uwp_tr_loco_translate.path || '',
                                    'bundle' : uwp_tr_loco_translate.bundle || '',
                                    'domain' : uwp_tr_loco_translate.domain || '',
                                    'po' : uwp_tr_loco_translate.save_data.po || '',
                                    'action' : uwp_tr_loco_translate.action || '',
                                    'route' : uwp_tr_loco_translate.save_data.route || '',
                                    'loco-nonce' : uwp_tr_loco_translate.save_data.loco_nonce || '',
                                },
                                success: function (save_response) {
                                   if (save_response.data != '') {
                                       $('#uwp-tr-loco-sync').css('display', 'none');
                                       $('#uwp-tr-loco-edit').css('display', 'block');
                                   }
                                }
                            });
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