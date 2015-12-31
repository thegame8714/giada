(function($) {
    function bindContentExtender(ed) {
        // On clicking #savecontentextenderEditor will insert turn form into shortcode
        $('#TB_ajaxContent #savecontentextenderEditor').click(function(e){
            e.preventDefault();
            // Vars
            var atts = ['id', 'theme', 'background', 'parallax', 'showinmenu', 'icon'],
                val  = null;
            // Loop all attributes, produce shortcode
            var shortcode = '[content';
            for (var i = 0; i < atts.length; i++) {
                val = $('#TB_ajaxContent').find('*[name=' + atts[i] + ']').val();
                // If it's a background, then use background/bgcolor depending on
                // where or not a url has been supplied.
                if (atts[i] == 'background' && !/(http(s?):)|([/|.|\w|\s])*\.(?:jpg|gif|png)/.test(val)) {
                    atts[i] = 'bgcolor';
                }
                if (val !== '') shortcode += ' ' + atts[i] + '="' + val + '"';
            }
            ed.insertContent(shortcode + '][/content]');
            tb_remove();
        });

        // Toggle the collection of icons
        $('#TB_ajaxContent .arrowdown, #TB_ajaxContent .arrowup').click(function() {
            var $sprite = $('#TB_ajaxContent .sprite');
            if ($(this).hasClass('arrowdown')) {
                $sprite.slideDown();
                $(this).removeClass('arrowdown').addClass('arrowup');
            } else if ($(this).hasClass('arrowup')) {
                $sprite.slideUp();
                $(this).removeClass('arrowup').addClass('arrowdown');
            }
        });
    }

    function showContentExtender(ed) {
        // Get form using AJAX
        $.ajax({
            type: 'post',
            dataType: 'html',
            url: wp_ajax.url,
            data: {
                action: 'contentextenderEditor',
                nonce: wp_ajax.nonce
            },
            success: function(response) {
                $('body').append(response);
                tb_show('Content Shortcode Editor', '#TB_inline?inlineId=contentextenderEditor');
                // Adjust thickbox width/height
                $('#TB_window').width($('#TB_ajaxContent').width() + 40);
                $('#TB_ajaxContent').height($('#TB_window').height() - 65);
                // Bind events
                bindContentExtender(ed);
                // Delete old container for no re-dupe
                $('form#contentextenderEditor').remove();
            }
        });
    }

    // Create plugin
    tinymce.create('tinymce.plugins.contentextender', {
        init: function(ed, url) {
            ed.addButton('contentextender', {
                text: 'Insert Content',
                icon: false,
                onclick: function() {
                    showContentExtender(ed);
                }
            });
        },
    });

    // Register plugin
    tinymce.PluginManager.add('contentextender', tinymce.plugins.contentextender);
})(jQuery);