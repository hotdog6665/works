jQuery(document).ready(function ($) {
    $("#connection_checker").submit(function (event) {
        event.preventDefault();
        var main_site_url = $(this).find('.main_site_url').val();
        var token = $(this).find('.token').val();
        $(this).find('.main_site_url').removeClass('invalid');
        if (isUrlValid(main_site_url)) {
            $('.invalid_url').remove();
            var data = {
                action: 'connection_checker',
                main_site_url: main_site_url,
                token: token,
            };
            $.post(ajaxurl, data, function (response) {
                if (response == 'Success') {
                    $('#connection_checker .not_connected').remove();
                    $('#connection_checker .connected').remove();
                    $('.not_connected_notice').remove();
                    $('.main_site_url').after('<span class="connected">Connected</span>');
                    $('input[type="submit"]').after('<span class="connected">Success &#10004</span>');
                }
                if (response == 'Fail') {
                    $('#connection_checker .connected').remove();
                    $('#connection_checker .not_connected').remove();
                    $('.main_site_url').after('<span class="not_connected">Not connected</span>');
                    $('input[type="submit"]').after('<span class="invalid_url">Fail &#10060</span>');
                }
            });
        } else {
            $(this).find('.main_site_url').addClass('invalid');
            $('.invalid_url').remove();
            $(this).find('.main_site_url').after('<span class="invalid_url">Invalid url</span>');
        }
    });
});

function isUrlValid(url) {
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
}