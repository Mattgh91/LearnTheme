;(function($) {
	"use strict";
	/** Create shortcode button **/
	tinymce.create('tinymce.plugins.zn_button', {
		init : function(editor, url)
		{
			editor.addButton('zn_button', {
				title : 'Shortcodes',
				image : url+'/zn_button.png',
				onclick : function()
				{
					var params = {};
					params.modal_content = $('.zn_shortcodes_wrapper').html();
					// params.type = 'inline';
					params.modal_title = 'Shortcodes';
					var modal = new $.ZnModal(params);
				}
			});
		}
	});

	tinymce.PluginManager.add('zn_button', tinymce.plugins.zn_button);

	/** Add shortcode to page **/
	$(document).on( 'click', '.zn_sc_title', function(e){
		var shortcode = $(this).next('.zn_shortcode_text').html();
		tinyMCE.activeEditor.execCommand("mceInsertContent", false, window.switchEditors.wpautop(shortcode));
		$.ZnModal.openInstance[0].close();
	});

	/** Make the shortcode navigation work **/
	$(document).on( 'click', '.zn_activate_nav li a', function(e)
	{
		var container = $(this).closest( '.zn_shortcodes_inner' ),
			page = $(this).attr('href');

		$('.zn_activate_nav li a').removeClass('active');
		$(this).addClass('active');

		container.find('.zn_page').hide();
		$(container).find(page).fadeIn(100);

		e.preventDefault();
	});
})(jQuery);