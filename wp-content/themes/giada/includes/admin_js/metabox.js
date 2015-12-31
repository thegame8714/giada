jQuery(document).ready(function($) {

    if(jQuery('.upload_field').length > 0 ) {

        // Media Uploader
        window.formfield = '';

        jQuery('body').on('click', '.upload_image_button', function() {

            window.formfield = jQuery('.upload_field',jQuery(this).parent());

            tb_show('', 'media-upload.php?type=file&TB_iframe=true');

            return false;

        });

        window.original_send_to_editor = window.send_to_editor;

        window.send_to_editor = function(html) {

            if (window.formfield) {

                imgurl = jQuery('a','<div>'+html+'</div>').attr('href');

                window.formfield.val(imgurl);

                tb_remove();

            } else {

                window.original_send_to_editor(html);

            }

            window.formfield = '';

            window.imagefield = false;

        }
    }

    jQuery('.add_new_field').on('click', function() {

        var field = jQuery(this).closest('td').find('div.repeatable_wrapper:last').clone(true);

        var fieldLocation = jQuery(this).closest('td').find('div.repeatable_wrapper:last');

        // set the new field val to blank
        jQuery('input', field).val('');

        field.insertAfter(fieldLocation, jQuery(this).closest('td'));

        return false;

    });

    jQuery('.add_new_textarea_field').on('click', function() {

        var field = jQuery(this).closest('td').find('div.repeatable_textarea_wrapper:last').clone(true);

        var fieldLocation = jQuery(this).closest('td').find('div.repeatable_textarea_wrapper:last');

        // set the new field val to blank
        jQuery('textarea', field).val('');

        field.insertAfter(fieldLocation, jQuery(this).closest('td'));

        return false;

    });

    jQuery('.add_new_textareawithtitle_field').on('click', function() {

        var field = jQuery(this).closest('td').find('div.repeatable_textareawithtitle_wrapper:last').clone(true);

        var fieldLocation = jQuery(this).closest('td').find('div.repeatable_textareawithtitle_wrapper:last');

        // set the new field val to blank
        jQuery('input', field).val('');

        jQuery('textarea', field).val('');

        field.insertAfter(fieldLocation, jQuery(this).closest('td'));

        return false;

    });

    jQuery('.add_new_select_field').on('click', function() {

        var field = jQuery(this).closest('td').find('div.repeatable_select_wrapper:last').clone(true);

        var fieldLocation = jQuery(this).closest('td').find('div.repeatable_select_wrapper:last');

        // set the new field val to blank
        jQuery('select', field).val('');

        field.insertAfter(fieldLocation, jQuery(this).closest('td'));

        return false;

    });

    jQuery('.add_new_tab_field').on('click', function() {

        var field = jQuery(this).closest('td').find('div.repeatable_tab_wrapper:last').clone(true);

        var fieldLocation = jQuery(this).closest('td').find('div.repeatable_tab_wrapper:last');

        // set the new field val to blank
        jQuery('input', field).val('');

        jQuery('textarea', field).val('');

        field.insertAfter(fieldLocation, jQuery(this).closest('td'));

        return false;

    });

    // add new repeatable upload field
    jQuery('.add_new_upload_field').on('click', function() {

        var container = jQuery(this).closest('tr');

        var field = jQuery(this).closest('td').find('div.repeatable_upload_wrapper:last').clone(true);

        var fieldLocation = jQuery(this).closest('td').find('div.repeatable_upload_wrapper:last');

        // set the new field val to blank
        jQuery('input[type="text"]', field).val("");

        field.insertAfter(fieldLocation, jQuery(this).closest('td'));

        return false;

    });

    // remove repeatable field
    jQuery('.remove_repeatable').on('click', function(e) {

        e.preventDefault();

        var field = jQuery(this).parent();

        jQuery('input', field).val("");

        jQuery('textarea', field).val("");

        field.remove();

        return false;

    });

    jQuery('.remove_repeatable_select').on('click', function(e) {

        e.preventDefault();

        var field = jQuery(this).parent();

        jQuery('select', field).val("");

        field.remove();

        return false;

    });

    jQuery('.remove_repeatable_textarea').on('click', function(e) {

        e.preventDefault();

        var field = jQuery(this).parent();

        jQuery('textarea', field).val("");

        field.remove();

        return false;

    });

    jQuery('.remove_repeatable_tab').on('click', function(e) {

        e.preventDefault();

        var field = jQuery(this).parent();

        jQuery('input', field).val("");

        jQuery('textarea', field).val("");

        field.remove();

        return false;

    });

});
