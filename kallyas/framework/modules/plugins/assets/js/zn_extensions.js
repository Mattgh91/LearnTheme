(function ($) {

	// PLUGIN BUTTON ACTIONS
	$( document ).on( 'click', '.zn-extension-button', function(e){
		e.preventDefault();

		// Perform the ajax call based on action
		var config = {};

			config.button			= $( this );
			config.spinner			= config.button.find('.spinner');
			config.button_text		= config.button.find('.zn-extension-button-text');
			config.status			= config.button.closest('.zn-extension-inner').find('.zn-extension-status');
			config.status_classes	= 'active inactive not-installed';
			config.action			= config.button.data( 'action' );
			config.nonce			= config.button.data( 'nonce' );
			config.slug			= config.button.data( 'slug' );

		var data = {
			security 		: config.nonce,
			action 			: 'zn_do_plugin_action',
			plugin_action 	: config.button.data( 'action' ) 		|| false,
			slug 			: config.button.data( 'slug' ) 		|| false,
		};

		// Don't allow the user to spam the button
		if( config.spinner.hasClass('is-active') ) { return false; }

		perform_ajax_call( data, config );

		// Add the loading class
		config.spinner.addClass( 'is-active' );



		return false;
	});

	perform_ajax_call = function( data, config, callback ){
		// Perform the ajax call
		$.ajax({
			'type' : 'post',
			'dataType' : 'json',
			'url' : ajaxurl,
			'data' : data,
			'success' : function( response ){

				var status_classes	= 'active inactive not-installed';

				// We have to open a modal window with the credentials request
				if( typeof response.data.credential_form != 'undefined' ){
					request_credentials( response, data, config );
					return false;
				}

				// Update the plugin status
				config.status.removeClass( status_classes );
				config.status.addClass( response.data.status );
				config.status.text( response.data.status_text );

				// Update the plugin
				config.button.data( 'action', response.data.action );
				config.button_text.text( response.data.action_text );

				if( typeof callback != 'undefined' ){
					callback();
				}
				
				config.spinner.removeClass( 'is-active' );
			},
			'error' : function(response){
				if( typeof callback != 'undefined' ){
					callback();
				}
				config.spinner.removeClass( 'is-active' );
			}
		});
	}

	request_credentials = function( args, saved_data, config ){
		var data = {
			modal_content : args.data.credential_form,
			modal_title : 'File system credentials required',
			show_resize : false,
			modal_on_open : function( modal ){

				var form = $( modal.modal ).find('form');

				form.on('submit',function(e){
					var url = form.attr('action');
					
					saved_data.credentials = form.serialize();

					var callback = function(){
						modal.close();
					}

					perform_ajax_call( saved_data, config, callback );
					modal.close();
					e.preventDefault;

					return false;
				});
			}

		};
		new $.ZnModal( data );
	}

})(jQuery);