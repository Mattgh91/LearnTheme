/*
*	Fire up the jQuery sortable and draggable
*/

(function($)
{
	"use strict";

	$.ZnHtml = function()
	{
		// Here we can set some global variables if needed
		this.scope = ( $('.zn_pb_wrapper').length > 0 ) ? $('.zn_pb_wrapper') : $(document);

		//activate the plugin
		this.zinit();
		// This is needed for the data conversion
		this.zn_dummy_step = 0;
		this.failed = 0;

	};

	$.ZnHtml.prototype = {

		zinit : function()
		{

			var fw = this,
				file_uploader = '';

			fw.addactions();
			fw.refresh_events();

		},

		refresh_events : function(content){

			var fw = this;

			//Refresh modal
			fw.launch_modal(content);
			fw.enable_footer_widget_layouts(content);
			fw.launch_ui_slider(content);
			fw.launch_color_picker(content);
			fw.launch_ui_buttons(content);
			fw.add_el(content);
			fw.remove_el(content);
			fw.clone_el(content);
			fw.icon_list(content);
			fw.make_sortable(content);
			fw.dependencies(content);
			fw.timepicker(content);
			fw.datepicker(content);
			fw.ace_editor(content);
			fw.boxmodel(content);
			fw.tabbed_group(content);
			fw.date_format(content);
			fw.radio_image(content);

			// enable copytoclipboard
			fw.copyToClipboard(content);
		},



		addactions : function() {

			var fw = this;

			fw.scope.on('ZnNewFWContent',function(e){
				fw.refresh_events(e.content);
			});

			// USING THE WP TRIGGERS
			$( document ).on( 'widget-updated widget-added', function(data, el){
				fw.refresh_events( $(el) );
			});

			fw.file_upload();
			fw.remove_font();
			fw.add_g_font();

			// ENABLE Theme updater
			fw.theme_updater();
			fw.zn_refresh_pb();
		},



		zn_refresh_pb : function(){
			var fw = this;
			/* Add a new element for theme options */
			$('.zn_refresh_pb_btn').click(function(e) {

				var el = $(this);

				if( el.hasClass('zn_blocked_btn') ) {return false;}

				// SHOW THE MESSAGE

				el.addClass( 'zn_blocked_btn' );

				var data = {
					zn_action:'zn_refresh_pb',
					action: 'zn_ajax_callback',
					zn_ajax_nonce: ZnAjax.security
				};

				jQuery.post( ajaxurl, data, function(response) {
					new $.ZnModalMessage( 'Page builder data refreshed' );
					el.removeClass( 'zn_blocked_btn' );
				});

			e.preventDefault();

			});
		},

		tabbed_group : function(scope){
            var fw = this,
                element = (scope) ? scope.find('.znopt_tabbed_menu li a') : $('.znopt_tabbed_menu li a');
                element.on('click', function(e){
                	e.preventDefault();

                	var container = $(this).closest('.znopt_tabbed_group'),
                		menu = container.find('.znopt_tabbed_menu li'),
                		tabs = container.find('.znopt_single_tab');

                	menu.removeClass('active');
                	tabs.removeClass('active');
                	$(this).parent().addClass('active');
                	$( $(this).attr('href') ).addClass('active');
                });
		},

		date_format : function(scope){

            var fw = this,
                element = (scope) ? scope.find('.zn_date_format_input') : $('.zn_date_format_input');

			element.change( function() {
				var format = $(this);
				format.siblings( '.spinner' ).addClass( 'is-active' );
				$.post(ajaxurl, {
						action: 'date_format',
						date : format.val()
					}, function(d) { format.siblings( '.spinner' ).removeClass( 'is-active' ); format.siblings('.example').text(d); } );
			});
		},

		radio_image : function(scope){

			var element = (scope) ? scope.find('.zn-radio-image-container') : $('.zn-radio-image-container');

			if (!element.length) {return false;}

			var btn = element.find('.zn-radio-button:not([data-dummy])'),
				select = element.find('.zn-radio-image-select');

			btn.on('click', function(event) {
				event.preventDefault();
				btn.removeClass('active');
				btn.parent().removeClass('active');

				var $this = $(this),
					val = $this.attr('data-value');

				$this.addClass('active');
				$this.parent().addClass('active');
				select.val(val).change();
			});
		},

        // Enable footer widgets option
        enable_footer_widget_layouts: function (scope)
        {
            var fw = this,
                element = (scope) ? scope.find('.zn_mp') : $('.zn_mp'),
                widget_columns = (scope) ? scope.find('.zn_mp .zn_nop ul li') : $('.zn_mp .zn_nop ul li'),
                widget_columns_styles = (scope) ? scope.find('.zn_mp .zn_position_var_options ul li') : $('.zn_mp .zn_position_var_options ul li');

            if (!element.length) {
                return false;
            }

            /* Columns numbers */
            widget_columns.on('click', function (e)
            {
                e.preventDefault();

                var val = $(this).html(),
                    container = $(this).closest('.zn_mp'),
                    json = container.find('.zn_all_options').html(),
                    all_styles = $.parseJSON(json),
                    divs = container.find('.zn_positions_display'),
                    new_value = {},
                    i;

                /* Add active class to current option*/
                $(this).closest('.zn_nop').children('input').attr("value", val);
                /* ADD ATTRIBUTE FOR NUMBER OF COLUMNS*/
                container.find('.zn_positions .zn_widgets_positions').attr("data-columns", val);
                new_value[val] = [all_styles[val][0]];
                /* UPDATE INPUT VALUE BASED ON SELECTION*/
                container.find('.zn_positions .zn_widgets_positions').attr("value", JSON.stringify(new_value));
                $(this).closest('.zn_nop').find('li').removeClass('active');
                $(this).addClass('active');
                /* Hide the extra divs*/
                divs.children().removeClass('hidden');
                divs.children().slice(val).addClass('hidden');
                for (i = 0; i < all_styles[val][0].length; i++) {
                    container.find('.zn_position:nth-child(' + (i + 1) + ')').attr("class", "zn_position zn-grid-" + all_styles[val][0][i] + "");
                }

                /* Show the proper styles*/
                container.find('.zn_position_var_options .zn_number_list').html('');

                for (i = 0; i < all_styles[val].length; i++) {
                    var css = '';
                    if (i === 0) {
                        css = 'class="active"';
                    }
                    container.find('.zn_position_var_options .zn_number_list').append('<li ' + css + '>' + (i + 1) + '</li>');
                }

            });

            /* Columns styles */
            element.on('click', '.zn_position_var_options ul li', function (e)
            {
                e.preventDefault();

                var val = $(this).html(), /* GET SELECTED MODULE VARIATION*/
                    container = $(this).closest('.zn_mp'), divs = container.find('.zn_positions_display'), /* get option top parent*/
                    all_val = container.find('.zn_positions .zn_widgets_positions').attr("data-columns"), /* GET THE SELECTED NUMBER OF COLUMNS*/
                    json = container.find('.zn_all_options').html(), /* GET ALL POSSIBLE COMBINATIONS*/
                    all_styles = $.parseJSON(json), new_value = {};
                /* CREATE NEW JSON ARRAY TO POPULATE THE INPUT*/

                /* UPDATE THE INPUT WITH SELECTED COMBINATION*/
                new_value[all_val] = [all_styles[all_val][(val - 1)]];

                $(this).closest('.zn_positions').children('input').val(JSON.stringify(new_value));
                $(this).closest('.zn_number_list').find('li').removeClass('active');
                $(this).addClass('active');
                /* Hide the extra divs*/
                divs.children().removeClass('hidden');
                divs.children().slice(all_val).addClass('hidden');
                for (var i = 0; i < all_styles[all_val][(val - 1)].length; i++) {
                    container.find('.zn_position:nth-child(' + (i + 1) + ')').attr("class", "zn_position zn-grid-" + all_styles[all_val][(val - 1)][i] + "");
                }
            });
        },

		theme_updater : function(){
			var fw = this;
			/* Add a new element for theme options */
			$('.zn_run_theme_updater').click(function(e) {

				var el = $(this);

				if( el.hasClass('zn_blocked_btn') ) {return false;}

				// SHOW THE MESSAGE
				new $.ZnModalConfirm( 'We strongly recommend making a full theme backup ? Please note that data loss may occur. Are you sure you want to continue ?', 'Cancel', 'Perform theme update', function(){

					el.addClass( 'zn_blocked_btn' );

					var data = {
						zn_action:'zn_process_theme_updater',
						step:fw.zn_dummy_step,
						data:false,
						action: 'zn_ajax_callback',
						zn_ajax_nonce: ZnAjax.security
					};

					fw.process_theme_updater(data, function(){
						el.removeClass( 'zn_blocked_btn' );
					});
				});

			e.preventDefault();

			});
		},

		process_theme_updater : function( data, callback ) {

			var fw = this,
				message_container = $('.zn_updater_msg_container');
			jQuery.post( ajaxurl, data, function(response,textStatus, jqXHR) {

				if( textStatus.status == '500' || typeof response === 'undefined' || ! response ){
					setTimeout(function(){
						message_container.append('<div>Something went wrong... will retry the last convert</div>');
						fw.process_theme_updater(data,callback);
					}, 3000);
					return false;
				}

				// GET ONLY THE AJAX RESPONSE
				var source = $('<div>' + response + '</div>');
				response = source.find(".zn_json_response").html();
				response = $.parseJSON( response );

				if( response.status == 'ok' ) {

					if ( response.response_text.length > 0 ){
						message_container.append('<div>'+response.response_text+'</div>');
					}

					data.data = {};
					data.step = response.step;
					if( typeof response.data != 'undefined' ){
						data.data = response.data;
					}
					fw.process_theme_updater(data,callback);

				}
				else if( response.status == 'done' ){
					if ( response.response_text.length > 0 ){
						message_container.append('<div>'+response.response_text+'</div>');
					}
					callback();
					new $.ZnModalMessage('All done !');
				}
				else{
					fw.zn_dummy_step = 0;
				}
				//console.log(response);
			}, 'html').fail(function(){
				setTimeout(function(){
					message_container.append('<div>Something went wrong... will retry the last convert</div>');
					fw.process_theme_updater(data,callback);
				}, 3000);
			});

		},

		enable_tinymce : function(scope){

			if( typeof tinymce === 'undefined' ){
				return;
			}

			var elements =  (scope) ? scope.find('.zn_tinymce').not( '.zn_group .zn_tinymce' ) : $('.zn_tinymce').not( '.zn_group .zn_tinymce' );
			var length = elements.length;

			if( length>0 ) {
				for (var i=0; i<length; i++) {

					var id = elements[i].id,
						object = $(elements[i]);

					// Init Quicktag
					if(_.isUndefined(tinyMCEPreInit.qtInit[id])) {
						window.tinyMCEPreInit.qtInit[id] = _.extend({}, window.tinyMCEPreInit.qtInit[wpActiveEditor], {id: id});
						QTags( tinyMCEPreInit.qtInit[id] );
						QTags._buttonsInit();
					}
					else {

					}

					// Init tinymce
					if( window.tinyMCEPreInit && window.tinyMCEPreInit.mceInit[wpActiveEditor] && !window.tinyMCEPreInit.mceInit[id] ) {
						window.tinyMCEPreInit.mceInit[id] = _.extend({}, window.tinyMCEPreInit.mceInit[wpActiveEditor], {
							id: id
						});

						window.tinyMCE.execCommand( 'mceAddEditor', true, id );
						wpActiveEditor = id;

					}
					else {
						var content = tinymce.get(id).getContent();
							window.tinyMCE.execCommand( 'mceRemoveEditor', true, id );
							window.tinyMCE.execCommand( 'mceAddEditor', true, id );
						tinymce.get(id).setContent(content);
						wpActiveEditor = id;
					//	window.tinyMCE.execCommand( 'mceAddEditor', true, id );
					}

				}
			}

		},

		// Launch modals
		launch_modal : function( scope ){
			var fw = this,
			element = (scope) ? scope.find('.zn_modal_trigger') : $('.zn_modal_trigger');

			element.znmodal();

		},

		timepicker : function(scope){

			var element = (scope) ? scope.find('.zn_time_picker') : $('.zn_time_picker');

			element.timepicker({
				'timeFormat': 'H:i'
			});

		},

		datepicker : function(scope){

			var element = (scope) ? scope.find('.zn_date_picker') : $('.zn_date_picker');

			element.datepicker({
				dateFormat: "yy-mm-dd"
			}).datepicker('widget').wrap('<div class="ll-skin-nigran"/>');
		},

		boxmodel : function(scope){

			var element = (scope) ? scope.find('.js-boxmodel-field') : $('.js-boxmodel-field');

			element.each(function(index, el) {

				var linked = $(el).find('.zn-boxmodel-linked'),
					fields = $(el).find('.zn_input[type=text]');

				// sync fields when linked
				fields.on('change keyup', function(event) {

					var curTraget = $(event.currentTarget),
						ctv = curTraget.val().trim(),
						ctv_numer = parseInt(ctv),
						ctv_unit = ctv.replace(ctv_numer, ''),
						ctv_step = 1;

					// Check if shift is pressed and change to 10
					if(event.shiftKey){
						ctv_step = 10;
					}
					// get key and do the math
					switch(event.which) {
						 // up
				        case 38:
				        	ctv = ( ctv_numer + ctv_step ) + ctv_unit;
				        	curTraget.val( ctv );
				        break;
				         // down
				        case 40:
				        	ctv = ( ctv_numer - ctv_step ) + ctv_unit;
				        	curTraget.val( ctv );
				        break;
				         // exit this handler for other keys
				        default: return;
				    }
				     // prevent the default action (scroll / move caret)
				    event.preventDefault();
				    // update others if linked is enabled.
					if(linked.is(':checked')){
						fields.each(function(index, el) {
							$(el).val( ctv );
						});
					}
				});
				// sync fields when linked is changed (and sync with the first one)
				linked.on('change', function(event) {
					if($(this).is(':checked')){
						fields.each(function(index, el) {
							$(el).val( $( fields.first() ).val().trim() );
						});
					}
				});
			});


		},



		ace_editor : function( scope ){
			var element = (scope) ? scope.find('.zn_code_input') : $('.zn_code_input');
			element.each(function(){
				var editor_element = $(this),
					editor = ace.edit( editor_element.attr('id') ),
					editor_type = $(this).data( 'editor_type' );
					editor.setTheme("ace/theme/chrome");
					editor.getSession().setMode("ace/mode/"+editor_type);
					editor.on("change", function() {
						editor_element.next('textarea').val( editor.getValue() );
					});
			});


		},

		// jQuery UI slider
		launch_ui_slider : function( scope ) {

			var fw = this,
			element = (scope) ? scope.find('.slider-range-max') : $('.slider-range-max');

			// Activate jQuery UI slider
			$(element).each(function() {

				var el = $(this),
					step = parseInt(jQuery(this).attr('data-step')) || 1;

				jQuery( this ).slider({
					range: "max",
					min: parseInt(jQuery(this).attr('data-min')),
					max: parseInt(jQuery(this).attr('data-max')),
					value: parseInt(jQuery(this).attr('data-value')),
					step: step,
					slide: function( event, ui ) {
						jQuery( this ).prev().val( ui.value ).trigger('change');
					}
				});

				$(this).prev('input').on( 'change', function(){
					if( parseInt($(this).val()) < parseInt(el.attr('data-min') ) ) { $(this).val( parseInt(el.attr('data-min') ) ); }
					if( parseInt($(this).val()) > parseInt(el.attr('data-max') ) ) { $(this).val( parseInt(el.attr('data-max') ) ); }

					// CHECK IF THE INPUT IS NOT A NUMBER
					if( isNaN($(this).val()) ) { $(this).val( parseInt(el.attr('data-min') ) ); }

					el.slider("value" ,  parseInt($(this).val() ) );
				});

			});
		},

		// WP Color Picker
		launch_color_picker : function( scope ){

			var fw = this,
				element = (scope) ? scope.find('.zn_colorpicker') : $('.zn_colorpicker');

			element.each(function(el){

				var colors = (typeof zn_color_palette != 'undefined') ? zn_color_palette : '["#000","#fff","#d33","#d93","#ee2","#81d742","#1e73be","#8224e3"]';

				var input = $(this);

				// RE-CREATE THE COLORPICKER ON CLONE
				if ( $(this).hasClass('wp-color-picker') ) {
					var container = $(this).closest('.input-append');

					input = container.find('input.zn_colorpicker');

					container.html(input);
				}

				input.wpColorPicker({
					change: function(event, ui) {
						// TRIGGER A SPECIAL EVENT FOR THE LIVE CHANGE
						$(this).trigger('zn_change');
					},
					palettes: colors
				});

			});

		},

		// jQuery UI buttons
		launch_ui_buttons : function(scope){

			var fw = this,
				element = (scope) ? scope.find('.zn_buttons') : $('.zn_buttons');

			// Activate the buttons
			$(element).button();
		},

		// Launch icon list
		icon_list : function(scope){

			var fw = this,
				element = (scope) ? scope.find('.zn_icon_container span') : $('.zn_icon_container span');
			$(element).click(function() {
				var icon = jQuery(this).attr('data-unicode'),
					family = jQuery(this).attr('data-zniconfam'),
					opts_container = jQuery(this).closest('.zn_icon_op_container');

				if ( $(this).hasClass('zicon_active') )
				{
					// CLEAR THE FIELDS
					opts_container.find('.zn_icon_family').val('').trigger('change');
					opts_container.find('.zn_icon_unicode').val('').trigger('change');

					$(this).removeClass('zicon_active');

				}
				else
				{
					opts_container.find('.zn_icon_family').val(family).trigger('change');
					opts_container.find('.zn_icon_unicode').val(icon).trigger('change');

					//opts_container.find('input').val(icon).trigger('change');
					opts_container.closest('.zn_icon_op_container').find('.zicon_active').removeClass('zicon_active');
					jQuery(this).addClass('zicon_active');
				}
			});


		},

		// Add new element button
		add_el : function(scope) {

			var fw = this,
				element = (scope) ? scope.find('.zn_add_button') : $('.zn_add_button');

			/* Add a new element for theme options */
			$(element).click(function(e) {

				e.preventDefault();

				// GET THE ELEMENT TO BE ADDED
				var el = $(this),
					elemetnt_type = el.attr('data-type'),
					nonce = ZnAjax.security,
					container = el.prev(),
					context = ' ',
					max_items = el.attr('data-max_items'),
					JSONdata = el.data('zn_data');

				if ( container.hasClass('zn_add_button_inactive') ){
					return false;
				}

				// Don't allow the user to spam the add button
				container.addClass( 'zn_add_button_inactive' );

				var data = {
					zn_elem_type: elemetnt_type,
					zn_json: JSONdata,
					zn_action:'zn_add_element',
					action: 'zn_ajax_callback',
					context: context ,
					zn_ajax_nonce: nonce
				};

				jQuery.post( ajaxurl, data, function(response) {

					if (response) {

						var new_content = $(response);

						container.append(new_content).zn_sortable_order();

						// Create an event for new content received
						fw.scope.trigger({type: "ZnNewFWContent",content : new_content});

						// CHECK TO SEE IF THE DUMMY INPUT IS PRESENT
						if ( container.children('.zn_group_placeholder').length > 0 ) {
							container.children('.zn_group_placeholder').remove();
						}


						if ( typeof max_items !== 'undefined' ) {
							var childrens_num = container.children('.zn_group').length;
							if ( childrens_num < max_items ){
								container.removeClass('zn_add_button_inactive');
							}
						}
						else{
							container.removeClass('zn_add_button_inactive');
						}
					}
					else{
						alert('Something went wrong');
						container.removeClass('zn_add_button_inactive');
					}
				});

			});
		},

		// REMOVE ELEMENT FROM OPTION GROUP
		remove_el : function(scope){

			var fw = this,
				element = (scope) ? scope.find('.zn_remove') : $('.zn_remove');

			$(element).click(function(e) {

				e.preventDefault();

				// GET THE CONTAINER GROUP
				var el = $(this),
					container = el.closest('.zn_pb_group_content'),
					add_button = container.next('.zn_add_button'),
					max_items = add_button.attr('data-max_items'),
					element_to_delete = el.closest('.zn_group'),
					option_id = $(container).closest('.zn_group_container').data('baseid');

				var callback = function() {


					$(document).trigger('znpb:element:removed', element_to_delete);
					element_to_delete.remove();

					// ADD A DUMMY ELEMENT IN CASE NO ELEMENTS EXISTS
					if ( container.children().length === 0 ) {
						container.append('<input type="hidden" class="zn_group_placeholder" name='+option_id+' value="" />');
					}

					// This needs to be in place for when the new content is actually a group option. It will add the form change data to the pagebuilder
					if ( fw.scope.is('.zn_pb_wrapper') ) {
						$('.znpb-form-edit-show').find('form').data('changed', true);
					}

					container.zn_sortable_order();

					// Check if we have a max items set
					if (  typeof max_items !== 'undefined'  ){
						var childrens_num = container.children('.zn_group').length;
						if( childrens_num < max_items ){
							container.removeClass( 'zn_add_button_inactive' );
						}
					}

				};

				new $.ZnModalConfirm( 'Are you sure you want to remove this element ?', 'No', 'Yes', callback  );

			});

		},

		clone_el : function(scope){

			var fw = this,
				element = (scope) ? scope.find('.zn_clone_button') : $('.zn_clone_button');

			$(element).click(function(e) {

				e.preventDefault();

				// GET THE ELEMENT TO BE ADDED
				var el = $(this),
					elemetnt_type = el.attr('data-type'), // Element type
					container = el.closest('.zn_pb_group_content'), // Container
					add_button = container.next('.zn_add_button'),
					max_items = add_button.attr('data-max_items'),
					to_be_cloned = el.closest('.zn_group');

					if ( container.hasClass('zn_add_button_inactive') ){
						return false;
					}

					// DISABLE TIYMCE AND REPLACE TINYMCE IDS
					to_be_cloned.find( '.zn_tinymce' ).each(function(){
						var id = $(this).attr('id');
						window.tinyMCE.execCommand( 'mceRemoveEditor', true, id );
					});

					// Repare textarea
					to_be_cloned.find( 'textarea' ).each(function(){
						var textarea_content = $(this).val();
						$(this).html( textarea_content );
					});

					var cloned_data = to_be_cloned.clone(), // Cloned data
					new_content = $(cloned_data);

					// Activate the editors again
					to_be_cloned.find( '.zn_tinymce' ).each(function(){
						var id = $(this).attr('id');
						window.tinyMCE.execCommand( 'mceAddEditor', true, id );
					});

				// MAKE HIDDEN INPUTS AVAILABLE AGAIN
				new_content.find('.zn_class_text').children('input[type="hidden"]').attr( 'type', 'text' ).next(' div.disabled').remove();

				// Create an event for new content received
				fw.scope.trigger({type: "ZnNewFWContent",content : new_content});
				fw.repare_modals(new_content);
				container.append(new_content).zn_sortable_order();

				// Check if we have a max items set
				if (  typeof max_items !== 'undefined'  ){
					var childrens_num = container.children('.zn_group').length;
					if( childrens_num >= max_items ){
						container.addClass( 'zn_add_button_inactive' );
					}
				}

			});
		},

		add_g_font : function(){

			var fw = this;

			$('.zn_add_gfont').click(function(e) {

				e.preventDefault();

				// GET THE ELEMENT TO BE ADDED
				var elemetnt_type = jQuery(this).attr('data-type'),
					selected_font = jQuery(this).prev().val(),
					nonce = ZnAjax.security,
					conainer = jQuery('.zn_google_fonts_holder');

				// Return if no font was selected
				if ( selected_font == "Please select a font" ) {
					return false;
				}

				var JSONdata = jQuery(this).data('zn_data');

				var data = {
					zn_elem_type: elemetnt_type,
					selected_font: selected_font,
					zn_json: JSONdata,
					zn_action:'zn_add_google_font',
					action: 'zn_ajax_callback',
					zn_ajax_nonce: nonce
				};

				jQuery.post(ajaxurl, data, function(response) {

					if (response) {

						var new_content = $(response);

						// Create an event for new content received
						fw.scope.trigger({type: "ZnNewFWContent",content : new_content});

						conainer.append(new_content).zn_sortable_order();
					}
					else{
						alert('Something went wrong');
					}
				});

			});



		},

		// REPLACE THE MODAL IDS AND HREF SO THAT THEY WILL POINT CORRECTLY
		repare_modals : function(el){

			// REPARE MODAL TRIGGERS
			el.find('.zn_modal_trigger').each(function(){
				var id = $(this).attr('href') + (new Date()).getTime();
				el.find($(this).attr('href')).attr('id', id.replace('#',''));
				$(this).attr('href', id);
			});

			// Remove quicktags toolbar
			el.find('.quicktags-toolbar').remove();

			// REPARE TINYMCE's ID's
			el.find( '.zn_tinymce' ).each(function(){
				var old_id = $(this).attr('id'), // OLD id
					new_id = old_id + (new Date()).getTime(), // New id
					replace_string = new RegExp(old_id,"g"), // Replace RegEx
					old_content = $(this).closest('.zn_class_visual_editor'), // Get the old content object
					old_content_html = old_content.html(), // Get the old content HTML
					new_content = old_content_html.replace(replace_string,new_id); // Replace all editor id's to new one

				// Add content back
				old_content.html( new_content );

			});

		},

		// Add sortable
		make_sortable : function(scope){

			var fw = this,
				element = (scope) ? scope.find('.zn_group_inner') : $('.zn_group_inner');

			element.sortable({
				handle: 'a.zn_group_handle',
				containment: "parent",
				tolerance: "pointer",
				update: function( event, ui ) {

					// This needs to be in place for when the new content is actually a group option. It will add the form change data to the pagebuilder
					if ( fw.scope.is('.zn_pb_wrapper') ) {
						$('.znpb-form-edit-show').find('form').data('changed', true);
					}

					jQuery(this).zn_sortable_order();
				}
			});
		},
		dependencies : function( scope ) {
			var fw = this,
				element = (scope) ? scope.find('[data-dependency]') : $('[data-dependency]');

			// THIS SCRIPT WILL SHOW AN OPTION BASED ON THE DEPENDENCY
			element.each(function() {

				var el 			= $(this),
					config      = el.data('dependency');

				// Do an initial check
				checkDependency( el, config );
				// Bind change event to options
				triggerChange( config );

				// Checks a dependencies for a given option
				function checkDependency( el, config ){

					// Set initial state
					if( doesQualify( config ) ){
						el.slideDown();
					}
					else{
						el.slideUp();
					}

				}

				// Bind to change trigger for options
				function triggerChange( config ){
					var length = config.length,
						d = 0;

					for ( d; d < length; d++ ) {
						// var option = $( '[data-optionid="'+config[d]['element']+'"]' ),
						var option = el.closest( '.zn-modal-form').length ? el.closest('.zn-modal-form').find( '[data-optionid="'+config[d]['element']+'"]' ) : $( '[data-optionid="'+config[d]['element']+'"]' ),
							input = option.find( ':input' ),
							value = $(input).val();

						input.on( 'change', function(){
							checkDependency( el, config );
						});
					}
				}

				// This function will check the values
				function doesQualify( config ){

					var length = config.length,
						d = 0;

					for ( d; d < length; d++ ) {

						var option = el.closest( '.zn-modal-form').length ? el.closest('.zn-modal-form').find( '[data-optionid="'+config[d]['element']+'"]' ) : $( '[data-optionid="'+config[d]['element']+'"]' ),
							input = option.find( ':input' ),
							value = $(input).val();

						// This is a special case for toggle2 option
						if( input.last().attr('type') == 'checkbox' ){
							value = $( ':checked', option ).val();
						}

						// Radios and checkboxes have the same name and jQuery only takes the first option value
						// We need to get the value for the checked option
						if ( $(input).attr('type') == 'radio' || $(input).attr('type') == 'checkbox' ) {
							value = $( ':checked', option ).val();
						}

						if( $.inArray( value, config[d]['value'] ) === -1 ) {
							return false;
						}

					}

					return true;
				}

			});

		},

		remove_font : function( scope ){
			var fw = this,
				element = (scope) ? $(scope).find('.zn_remove_font_trigger') : $('.zn_remove_font_trigger');

			if( !element.length ) {return;}

			element.on( 'click', function(e){
				var font = $(this).parent(),
					data = {
						action: 'zn_remove_icons',
						font_name: $(this).data('font_name'),
						security: ZnAjax.security
					};

				// Make the ajax call
				jQuery.post( ZnAjax.ajaxurl, data, function( response ) {

					if ( response.message ) {
						new $.ZnModalMessage( response.message );
						font.remove();
					}
					else{
						new $.ZnModalMessage('There was a problem deleting the icons !');
					}
				});
			});
		},

		// NOT AJAXIFIED ... IF NEDED ADD A SCOPE AND ADD IT TO THE LIST OF REFRESHED EVENTS
		file_upload : function(){
			var fw = this,
				trigger = $('.zn_file_upload');

			trigger.on( 'click', function(e){

				e.preventDefault();

				if ( fw.file_uploader ) {
					fw.file_uploader.open();
					return;
				}

				var button = jQuery(this),
					title = button.data('title'),
					zn_button = button.data('button'),
					file_type = button.data('file_type'),
					field = button.prev();

				fw.file_uploader = wp.media.frames.file_frame = wp.media({
					title: title,
					button: {
						text: zn_button
					},
					library: {
						type: file_type
					},
					multiple: false
				}).on( 'select' , function(){
					var attachment = fw.file_uploader.state().get('selection').first().toJSON();
					//console.log(attachment);

					var data = {
						action: 'zn_upload_icons',
						attachment: attachment,
						security: ZnAjax.security
					};

					// Make the ajax call
					jQuery.post( ZnAjax.ajaxurl, data, function( response ) {

						if ( response.message ) {
							new $.ZnModalMessage( response.message );
							var new_res = response.html;
							$('.uploads_container').append( new_res );
							fw.remove_font( new_res );
						}
						else{
							new $.ZnModalMessage('There was a problem uploading the icons !');
						}
					});

				});

				fw.file_uploader.open();

			});

		},


		copyToClipboard: function(content) {
			$(content).find('[data-clipboard-text]').on('click', function(e){
				e.preventDefault();
				var thisText = $(this).attr('data-clipboard-text'),
					$temp = $("<input>");
				$("body").append( $temp );
				$temp.val( thisText ).select();
				document.execCommand("copy");
				$temp.remove();
				$(this).addClass('u-text-copied');
			});

		},

	};


/*
*	INIT the JS framework
*/

	$(document).on('ready',function(){
		$.zn_html = new $.ZnHtml();
		//for(var b in window) {if(window.hasOwnProperty(b)) console.log(b); }
	});

})(jQuery);


/*
*	START THE MAIN FUNCTIONS
*
*	CONTENTS :
*	1. AJAX CALLS
*	2. PLUGINS ENABLER
*
*/
(function ($) {


	jQuery.fn.zn_sortable_order = function () {

		baseid = '';
		if ( jQuery(this).attr('data-baseid') !== undefined ) {
			baseid = jQuery(this).attr('data-baseid');
		}
		else {
			baseid = jQuery(this).parents('div.zn_group:first').attr('data-baseid');
		}

		// PREPARE THE BASEID
		search_baseid = baseid.replace(/(\[)/g,'\\[');
		str = '('+search_baseid+'\\[\\d+\\])';
		var reg = new RegExp(str);

		this.children('div.zn_group').each(function (idx) {

			// CHANGE THE BASE ID
			jQuery(this).attr('data-baseid',baseid + '[' + idx + ']');

			jQuery(this).find('[data-baseid]').each(function () {
				//console.log(baseid +' '+ this.name+'  '+this.name.replace(reg, baseid + '[' + idx + ']'));
				jQuery(this).attr('data-baseid',jQuery(this).attr('data-baseid').replace(reg, baseid + '[' + idx + ']'));
			});

			// CHANGE THE INPUT BASE ID's
			var $inp = jQuery(this).find(':input');
			$inp.each(function () {

				//console.log(baseid +' '+ this.name+'  '+this.name.replace(reg, baseid + '[' + idx + ']'));
				this.name = this.name.replace(reg, baseid + '[' + idx + ']');

			});

		});



	};


})(jQuery);


// Uploading files
var file_frame;

	jQuery('body').on('click','.zn-remove-image',function(){
		jQuery(this).parents('.zn_option_content').find('.logo_upload_input').val('').trigger('change');
		jQuery(this).parent().html('Nothing selected...<a class="zn-remove-image" data-toggle="tooltip" data-title="Remove Image" href="#"></a>');
		return false;
	});


	jQuery('body').on('click','.zn_upload_image_button', function( event ){

	event.preventDefault();
	var button = jQuery(this),
	multiple = button.data('multiple'),
	title = button.data('title'),
	zn_button = button.data('button'),
	field = button.prev();


	// Create the media frame.
	file_frame = wp.media.frames.file_frame = wp.media({
	  title: title,
	  button: {
		text: zn_button,
	  },
	  multiple: multiple
	});

	// When an image is selected, run a callback.
	file_frame.on( 'select', function() {
	  // We set multiple to false so only get one image from the uploader
	  attachment = file_frame.state().get('selection').first().toJSON();

	// Do somethign with the images
	if ( !multiple ) {

		//console.log(attachment);
		if ( field.data('id') === true ) {
			field.val(attachment.id).trigger('change');
		}
		else {
			field.val(attachment.url).trigger('change');
		}

		image = '<button title="Close (Esc)" type="button" class=" zn-remove-image">&#215;</button><img class="zn_mu_image" src="'+attachment.url+'" alt="" />';
		field.parent().children('.zn-image-holder').html(image);


	}
	else {

	}
	  // Restore the main post ID
	  //wp.media.model.settings.post.id = wp_media_post_id;
	});

	// Finally, open the modal
	file_frame.open();
  });

  // Restore the main ID when the add media button is pressed
  //jQuery('a.add_media').on('click', function() {
	//wp.media.model.settings.post.id = wp_media_post_id;
	//file_frame.remove(0);
  //});
