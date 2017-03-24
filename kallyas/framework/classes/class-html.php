<?php if(! defined('ABSPATH')){ return; }

	/**
	* 	to do :
	*
	*	Sa incarc google fonts doar daca optiunea e folosita
	*
	*/
	class ZnHtml
	{

		var $data,$icons;

		function zn_set_data( $data ){
			$this->data = $data;
		}

		function setup_options(){

			$options = array();

			foreach ( $this->data['theme_options'] as $key => $option ) {
				if( isset($option['parent']) && isset($option['slug']) ){
					$options[$option['parent']][$option['slug']][] = $option;
				}
			}

			return $options;
		}

		function zn_render_page_options() {

			$options = $this->setup_options();
			$output = '';
			$i = 0;

			$output .= '<div class="tab-content">';

			foreach ( $options[$this->data['slug']] as $slug => $options ) {

				if ( $i === 0 ) {
					$output .= '<div class="tab-pane active" id="'.$slug.'">';
				}
				else {
					$output .= '<div class="tab-pane" id="'.$slug.'">';
				}

				foreach ( $options as $key => $option ) {

					// Set-up the STD for normal options
					$saved_value = zget_option( $option['id'] , $this->data['slug'] );

					if ( !empty( $saved_value ) || $saved_value === '0' ) {
						$option['std'] = $saved_value;
					}

					// RENDER SINGLE OPTION
					$output .= $this->zn_render_single_option($option);

				}

				$output .= '</div>'; // Close tab-pane
				$i++;
			}

			$output .= '</div>'; // END tab-content

			return $output;

		}

		function zn_render_single_option( $option ) {
			$defaults = array(
					'class' => '',
					'placeholder' => '',
					'std' => '',
					'supports' => '',
					'show_blank' => false,
				);

			// Sanitize fields
			$option = wp_parse_args( $option, $defaults );

			$dynamic_start = ( !isset( $option['dynamic'] ) ) ? $this->zn_render_option_start($option) : '';
			$dynamic_end = ( !isset( $option['dynamic'] ) ) ? $this->zn_render_option_end($option) : '';

		//	return sprintf('%s%s%s',$dynamic_start,$this->$option['type']($option),$dynamic_end );

			//[[Fixes: #763
			$result = call_user_func(array($this, $option['type']), $option);
			return sprintf('%s%s%s',$dynamic_start,$result,$dynamic_end );
		}


		function zn_render_meta_start( $metabox ){
			return '<div class="zn_meta_box '.$metabox.'">';
		}

		function zn_render_meta_end(){
			return '</div>';
		}

		function zn_render_option_start($option) {
			$output = '';
			$class = '';
			// SHOW THE TITLE
			if ($option['type'] == 'group' ){
				$class = 'zn_group_container zn_full';
			}

			$data_atts = '';
			if ( isset( $option['dependency'] ) ) {

				$dependencies = array();

				// Set the proper dependency element name
				if( !empty( $option['is_in_group'] ) ){
					foreach ( $option['dependency'] as $key => &$value) {
						if( $key === 'element' ){
							$value = $option['dependency_id'].'['.$value.']';
						}
					}
				}

				// Special case when we only have one dependency
				// This is for old options when multiple dependencies were not available
				if( ! empty( $option['dependency']['element'] ) ){
					$dependencies[] = $option['dependency'];
				}
				else{
					$dependencies = $option['dependency'];
				}

				$config = json_encode( $dependencies );
				$data_atts = " data-dependency='{$config}' ";

			}

			/**
			 * Check if the options change needs to be done live
			 * TYPE : CSS , CLASS
			 */
			if ( isset( $option['live'] ) ) {

				if( !empty( $option['is_in_group'] ) ){
					$option['live']['is_in_group'] = true;
				}

				$live_config = json_encode( $option['live'] );
				$data_atts .= " data-live_setup='{$live_config}'";
				$class .= ' zn_live_change ';

			}

			$output .= '<div class="zn_option_container '.$option['class'].' '.$class.' clearfix" data-optionid="'.$option['id'].'" '.$data_atts.'>';

			if ( $option['type'] != 'hidden' && !$option['show_blank'] ) {
				// Add a label for livechanging options
				$live_text = '';
				if ( isset( $option['live'] ) && !empty( $option['name'] ) ) { $live_text = '<span class="zn_live_label">live</span>'; }
				if ( !empty( $option['name'] ) ) {
					$output .= '<h4>'.$option['name'].' '.$live_text.'</h4>';
				}

			}

			if( !empty($option['description'] ) && !$option['show_blank'] ) {

				$output .= '<p class="zn_option_desc">';
				$output .= $option['description'];
				$output .= '</p>';

			}

			$output .= '<div class="zn_option_content zn_class_'.$option['type'].'">';

			return $output;
		}

		function zn_render_option_end($option){
			return '</div></div>';
		}

		function zn_page_start() {

			$logo_path = CHILD_BASE.'/images/admin_logo.png';
			$logo = THEME_BASE_URI.'/images/admin_logo.png';
			if( file_exists( $logo_path ) ){
				$logo = CHILD_BASE_URI.'/images/admin_logo.png';
			}

			$output = '<div id="zn_theme_admin" class="zn_theme_admin">';
			$output .= '<form id="zn_options_form" class="zn_container" action="#" method="post" >';
			$output .= '<div class="zn_inner zn_row">';
			$output .= '<div class="zn_span2 zn_sidebar">';
			$output .= '<div class="zn_logo">';
			$output .= '<img src="'.$logo.'"/>';
			$output .= '<span>'.__('Version: ', 'zn_framework') .'<strong>'. ZN()->version.'</strong></span>';
			$output .= '</div>';

				$output .= $this->zn_get_sidebar_menu();

			$output .= '</div>';		// END zn_options_container
			//$output .= '<div class="zn_row">';
			$output .= '<div class="zn_span10 zn_page_content">';

			/* START THE HEADER */
			$output .= '<div class="zn_action zn_header clearfix">';
			$output .= '<a class="zn_admin_button zn_save" href="#">Save options</a>';
			$output .= '</div>'; // END zn_header


			return $output;
		}

		function zn_page_end(){
			$output = '';
			/* START THE FOOTER */
			// $output = '<div class="zn_action zn_footer  clearfix">';
			// $output .= '<a class="zn_admin_button  zn_save" href="#">Save options</a>';
			// $output .= '</div>'; // END zn_footer

			/* START THE HEADER */
			$output .= '<div class="zn_action zn_header clearfix">';
			$output .= '<a class="zn_admin_button zn_save" href="#">Save options</a>';
			$output .= '</div>'; // END zn_header

			$output .= '</div>';
			$output .= '</div>';
			//$output .= '</div>';		// END zn_inner
			$output .= '<div class="zn_hidden">';

			$output .= '<input type="hidden" name="zn_option_field" value="'.$this->data['slug'].'">';
			$output .= '<input type="hidden" name="action" value="zn_ajax_callback">';
			$output .= '<input type="hidden" name="zn_action" value="zn_save_options">';

			$output .= '</div>';
			$output .= '</form>';	// END zn_options_form
			$output .= '</div>'; // END zn_theme_admin

			return $output;
		}


		function zn_get_sidebar_menu() {

			$output = '<ul class="wp-ui-primary nav-stacked">';

				foreach ( $this->data['theme_pages'][$this->data['slug']]['submenus'] as $key => $page ) {

					if ($key === 0) {
						$output .= '<li class="wp-ui-highlight" id="'.$page['slug'].'_menu_item"><a href="#'.$page['slug'].'" data-toggle="tab">'.$page['title'].'</a></li>';
					}
					else {
						$output .= '<li id="'.$page['slug'].'_menu_item"><a href="#'.$page['slug'].'" data-toggle="tab">'.$page['title'].'</a></li>';
					}
				}

			$output .= '</ul>';

			return $output;

		}

/*--------------------------------------------------------------------------------------------------
	Start the options output
--------------------------------------------------------------------------------------------------*/

/*--------------------------------------------------------------------------------------------------
	Start group option
--------------------------------------------------------------------------------------------------*/
	function group($option) {
		$output = '';

		$uid = '';
		$has_std_elements = false;

		// Count all default values
		if ( is_array ( $option['std'] ) )
		{
			$number_of_elements = count( $option['std'] );
		}
		elseif( isset( $option['dynamic'] ) ){
			$number_of_elements = 1;
		}
		else {
			$number_of_elements = 0;
		}

		$extra_button_class = '';
		$max_items = isset( $option['max_items'] ) ? 'data-max_items="'.$option['max_items'].'"' : '';
		if( !empty( $max_items ) ){
			if ( $number_of_elements >= $option['max_items'] ){
				$extra_button_class = 'zn_add_button_inactive';
			}
		}

		if ( !isset( $option['dynamic'] ) ){
			$output .= '<div class="zn_group_inner zn_group_container zn_pb_group_content '.$extra_button_class.'" data-baseid="'.$option['id'].'">';
		}

		// ADD THE STD OPTIONS THAT CANNOT BE DELETED
		if ( !empty( $option['default_std'] ) && !isset( $option['dynamic'] ) ) {

			$number_of_std_elements = count( $option['default_std'] );
			$has_std_elements = true;

			for ( $i = 0; $i < $number_of_std_elements; $i++ ) {

				$uid = zn_uid();

				// GET THE ELEMENT TITLE
				$title = isset( $option['default_std'][$i]['name'] ) ? '( Default ) '.$option['default_std'][$i]['name'] : '';

				// SET CUSTOM STD IF SET
				if ( !empty( $option['default_std'][$i]['std'] ) && !empty( $option['std'][$i] ) ){
					$option['std'][$i] = array_merge( $option['default_std'][$i]['std'] , $option['std'][$i] );
				}
				elseif( !empty( $option['default_std'][$i]['std'] ) ){
					$option['std'][$i] = $option['default_std'][$i]['std'];
				}

				$output .= '<div class="zn_group">';

				$output .= '<div class="zn_group_header zn_gradient">';
					$output .= '<h4>'.$title.'</h4>';
					// START ACTIONS
					$output .= '<div class="zn_group_actions">';
						// Clone button
						$output .= '<a class="zn_clone_button zn_icon_clone" data-clone="clone"></a>';
						// Edit button
						$output .= '<a class="zn_modal_trigger zn_icon_edit no-scroll" href="#'.$uid.'" data-modal_title="Element options"></a>';
					$output .= '</div>'; // END zn_group_actions
				$output .= '</div>'; // END zn_group_header

				$output .= '<div id="'.$uid.'" class="zn-modal-form zn-modal-group-form zn_hidden no-scroll" >';

					if( isset( $option['subelements']['has_tabs'] ) ) {

						unset( $option['subelements']['has_tabs'] );

						$output .= '<div class="zn-tabs-container">';
							$output .= '<div class="zn-options-tab-header">';
								$tab_num = 0;
								foreach ( $option['subelements'] as $key => $tab) {
									$cls = '';
									if ( $tab_num == 0 ) { $cls = 'zn-tab-active'; }
									$output .= '<a href="#" class="'.$cls.'" data-zntab="'.$key.'">'.$tab['title'].'</a>';
									$tab_num++;
								}

							$output .= "</div>";

							$tab_num = 0;
							foreach ( $option['subelements'] as $key => $tab ) {

								$cls = '';
								if ( $tab_num == 0 ) { $cls = 'zn-tab-active'; }
								$output .= '<div class="zn-options-tab-content zn-tab-key-'.$key.' '.$cls.'">';

									foreach ( $tab['options'] as $key => $value ) {
										$value['is_in_group'] = true;

										// SET THE DEFAULT VALUE
										if( is_array ( $option['std'] ) && isset ( $option['std'][$i][$value['id']] ) ) {
											$value['std'] = $option['std'][$i][$value['id']];
										}

										// Set the proper id
										$value['id'] = $option['id'].'['.$i.']['.$value['id'].']';
										$value['dependency_id'] = $option['id'].'['.$i.']';

										// Generate the options
										$output .= $this->zn_render_single_option($value);
									}

								$output .= "</div>";
								$tab_num++;
							}
						$output .= '</div>';
						$option['subelements']['has_tabs'] = true;
					}
					else{
						foreach ($option['subelements'] as $key => $value) {

							$value['is_in_group'] = true;

							// SET THE DEFAULT VALUE
							if( is_array ( $option['std'] ) && isset ( $option['std'][$i][$value['id']] ) ) {
								$value['std'] = $option['std'][$i][$value['id']];
							}

							// Set the proper id
							$value['id'] = $option['id'].'['.$i.']['.$value['id'].']';
							$value['dependency_id'] = $option['id'].'['.$i.']';

							// Generate the options
							$output .= $this->zn_render_single_option($value);
						}
					}



				$output .= '</div>';

				$output .= '</div>'; // Close zn_group

			}

		}

		// IF WE HAVE STANDARD ELEMENTS, CHANGE THE START VALUE
		if ( $has_std_elements ) {
			$start = count( $option['default_std'] );
		}
		else {
			$start = 0;
		}

		// We do not have any fixed elemenets
		for ( $i = $start; $i < $number_of_elements; $i++ ) {

			$options = array();
			$uid = zn_uid();

			// GET THE ELEMENT TITLE IF SUPPORTED
			if( isset($option['element_title']) && !isset( $option['dynamic'] ) && isset( $option['std'][$i][$option['element_title']] ) ) {

				$title = sanitize_text_field( $option['std'][$i][$option['element_title']] );

				if ( strlen( $title ) > 45 ){
					$title = substr( $title , 0 , 45 ) .'...';
				}

				if( $option['element_title'] == 'zn_color' ){
					$title .= '<span style="background-color:'.$title.'" class="zn-color-preview"></span>';
				}

			}
			// If empty show title or #xx
			else{
				if(isset($option['element_title'])){

					$title =  $option['element_title'];

					if( $title == 'zn_color' ){
						$title .= '<span style="background-color:'.$title.'" class="zn-color-preview"></span>';
					}

				} else {
					$title = '#'.($i+1);
				}
			}

			// Added image preview of list
			if( isset($option['element_img']) && !isset( $option['dynamic'] ) && isset( $option['std'][$i][$option['element_img']] ) ) {
				$img = sanitize_text_field( $option['std'][$i][$option['element_img']] );
				if(!empty($img)){
					$title .= '<span style="background-image:url('.$img.')" class="zn-dyn-image-preview"></span>';
				}
			}

			$output .= '<div class="zn_group">';

				$output .= '<div class="zn_group_header zn_gradient">';
					$output .= '<h4>'.$title.'</h4>';
					// START ACTIONS
					$output .= '<div class="zn_group_actions">';
						// DELETE BUTTON
						$output .= '<a class="zn_remove" data-tooltip="'.__( 'Delete','zn_framework' ).'"><span class="zn_icon_trash"></span></a>';

						if ( !isset( $option['sortable'] ) || $option['sortable'] == 'true' ) {
							// RE-ORDER BUTTON
							$output .= '<a class="zn_group_handle" data-tooltip="'.__( 'Move','zn_framework' ).'"><span class="zn_icon_order"></span></a>';
						}

						// Clone button
						$output .= '<a class="zn_clone_button"  data-clone="clone" data-tooltip="'.__( 'Clone','zn_framework' ).'"><span class="zn_icon_clone"></span></a>';
						// Edit button
						$output .= '<a class="zn_modal_trigger zn_icon_edit no-scroll" href="#'.$uid.'" data-modal_title="Element options" data-tooltip="'.__( 'Edit','zn_framework' ).'"><span class="zn_icon_edit"></span></a>';
					$output .= '</div>'; // END zn_group_actions
				$output .= '</div>'; // END zn_group_header

				$output .= '<div id="'.$uid.'" class="zn-modal-form zn-modal-group-form zn_hidden no-scroll" >';

					if( isset( $option['subelements']['has_tabs'] ) ) {

						unset( $option['subelements']['has_tabs'] );

						$output .= '<div class="zn-tabs-container">';
							$output .= '<div class="zn-options-tab-header">';
								$tab_num = 0;
								foreach ( $option['subelements'] as $key => $tab) {
									$cls = '';
									if ( $tab_num == 0 ) { $cls = 'zn-tab-active'; }
									$output .= '<a href="#" class="'.$cls.'" data-zntab="'.$key.'">'.$tab['title'].'</a>';
									$tab_num++;
								}

							$output .= "</div>";

							$tab_num = 0;
							foreach ( $option['subelements'] as $key => $tab ) {

								$cls = '';
								if ( $tab_num == 0 ) { $cls = 'zn-tab-active'; }
								$output .= '<div class="zn-options-tab-content zn-tab-key-'.$key.' '.$cls.'">';

									foreach ( $tab['options'] as $key => $value ) {
										$value['is_in_group'] = true;

										// SET THE DEFAULT VALUE
										if( is_array ( $option['std'] ) && isset ( $option['std'][$i][$value['id']] ) ) {
											$value['std'] = $option['std'][$i][$value['id']];
										}

										// Set the proper id
										$value['id'] = $option['id'].'['.$i.']['.$value['id'].']';
										$value['dependency_id'] = $option['id'].'['.$i.']';

										// Generate the options
										$output .= $this->zn_render_single_option($value);
									}

								$output .= "</div>";
								$tab_num++;
							}
						$output .= '</div>';

						// Needs to be added because it's unset at the begginning
						$option['subelements']['has_tabs'] = true;
					} else {

						foreach ($option['subelements'] as $key => $value) {

							$value['is_in_group'] = true;

							// SET THE DEFAULT VALUE
							if( is_array ( $option['std'] ) && isset ( $option['std'][$i][$value['id']] ) ) {
								$value['std'] = $option['std'][$i][$value['id']];
							}

							// Set the proper id
							$value['id'] = $option['id'].'['.$i.']['.$value['id'].']';
							$value['dependency_id'] = $option['id'].'['.$i.']';

							// Generate the options
							$output .= $this->zn_render_single_option($value);
						}
					}


				$output .= '</div>'; // zn-modal-form
			$output .= '</div>'; // Close zn_group

		}
		if ( !isset( $option['dynamic'] ) ) {
			$output .= '</div>'; // Close zn_innter
			// Clear the std option
			$option['std'] = '';
			$output .= '<div class="zn_add_button zn-btn-done" '.$max_items.' data-zn_data=\''.base64_encode( json_encode( $option ) ).'\' data-type="'.$option['id'].'">Add more</div>';
		}


		return $output;
	}


/*--------------------------------------------------------------------------------------------------
	Start SELECT option
--------------------------------------------------------------------------------------------------*/
	function select ( $value ){

		if ( empty( $value['options'] ) ) { $value['options'] = array(); }

		if( isset( $value['multiple'] ) ) {
			$output = '<select class="select zn_input zn_input_select" multiple name="'.$value['id'].'[]" id="'. $value['id'] .'">';
			foreach ($value['options'] as $select_ID => $option) {

				$checked = '';
				if(is_array($value['std'])) {
					if(in_array($select_ID,$value['std'])) { $checked = 'selected="selected"'; } else { $checked = ''; }
				}
				/* id="' . $select_ID . '" */
				$output .= '<option value="'.$select_ID.'" '.$checked.' >'.$option.'</option>';
			}
			$output .= '</select>';
		}
		else {

			$output = '<select class="select zn_input zn_input_select" name="'.$value['id'].'" id="'. $value['id'] .'">';

			/* id="' . $select_ID . '" */

			foreach ($value['options'] as $select_ID => $option) {
				$output .= '<option  value="'.$select_ID.'" ' . selected($value['std'], $select_ID, false) . ' >'.$option.'</option>';
			}
			$output .= '</select>';
		}



		return $output;

	}



/*--------------------------------------------------------------------------------------------------
	Start Sidebar option
--------------------------------------------------------------------------------------------------*/
	function sidebar ( $value ) {

		// Get unlimited sidebars only once
		if ( empty( $this->sidebars ) ) {
			$sidebars = array();
			// Add the unlimited sidebars
			$unlimited_sidebars = zget_option( 'unlimited_sidebars' , 'unlimited_sidebars' );
			if ( is_array( $unlimited_sidebars ) ) {
				foreach ($unlimited_sidebars as $key => $sidebar) {
					$sidebars[zn_sanitize_widget_id($sidebar['sidebar_name'])] = $sidebar['sidebar_name'];
				}
			}

			$this->sidebars = $sidebars;

		}

		if( !empty( $value['supports']['default_sidebar'] ) ){
			$sidebars = array( $value['supports']['default_sidebar'] => 'Default Sidebar' );
		}
		else{
			$sidebars = array( 'default_sidebar' => 'Default Sidebar' );
		}

		if( is_array( $this->sidebars ) ){
			$sidebars = array_merge( $sidebars, $this->sidebars );
		}

		// Override default sidebar options
		if( !empty( $value['supports']['sidebar_options'] ) ){
			$sidebar_options = $value['supports']['sidebar_options'];
		}
		else{
			$sidebar_options = array( 'sidebar_right' => 'Right sidebar' , 'sidebar_left' => 'Left sidebar' , 'no_sidebar' => 'No sidebar' );
		}

		if ( !is_array( $value['std'] ) ) { $value['std'] = array(); }
		if ( !isset ( $value['std']['layout'] ) ) { $value['std']['layout'] = ''; }
		if ( !isset ( $value['std']['sidebar'] ) || empty( $value['std']['sidebar'] ) ) { $value['std']['sidebar'] = ''; }

		// Sidebar layout
		$output = '<label for="'. $value['id'] .'_layout">Sidebar layout</label><select class="select zn_input zn_input_select" name="'.$value['id'].'[layout]" id="'. $value['id'] .'_layout">';
		foreach ( $sidebar_options as $select_ID => $option ) {
			$output .= '<option id="' . $select_ID . '" value="'.$select_ID.'" ' . selected( $value['std']['layout'], $select_ID, false) . ' >'.$option.'</option>';
		}
		$output .= '</select>';

		// Sidebar select
		$output .= '<label for="'. $value['id'] .'_sidebar">Sidebar select</label><select class="select zn_input zn_input_select" name="'.$value['id'].'[sidebar]" id="'. $value['id'] .'_sidebar">';
		foreach ( $sidebars as $select_ID => $option ) {
			$output .= '<option id="' . $select_ID . '" value="'.$select_ID.'" ' . selected($value['std']['sidebar'], $select_ID, false) . ' >'.$option.'</option>';
		}
		$output .= '</select>';


		return $output;

	}

/*--------------------------------------------------------------------------------------------------
	Start CHECKBOX option
--------------------------------------------------------------------------------------------------*/
	function checkbox ( $value ) {

		$output = '';

		if ( empty($value['options']) || !is_array($value['options']) ) {
			return;
		}

		// Determine if radio-type toggle button group
		$is_radio = '';
		if ( isset( $value['supports']) && !empty( $value['supports'] ) ) {
			if ( in_array( 'zn_radio', $value['supports'] ) ){
				$is_radio = 'zn_radio';
			}
		}

		$output .= '<div class="zn_checkbox_wrapper '.$is_radio.'">';

		foreach ( $value['options'] as $select_ID => $option) {

			if ( !empty($value['std']) && in_array($select_ID, $value['std'] ) ) {
				$checked = 'checked="checked"';
			}
			else {
				$checked = '';
			}


			$output .= '<input type="checkbox" class="zn_input zn_input_checkbox zn_radio_check" name="'.$value['id'].'[]" id="' . $value['id'] .'_'. $select_ID . '" value="'.$select_ID.'" ' . $checked . ' ><label for="' . $value['id'] .'_'. $select_ID . '"> '.$option.'</label>';
			if(empty($is_radio)){
				$output .=  '<br/>';
			}
		}

		$output .= '</div>';


		return $output;

	}


/*--------------------------------------------------------------------------------------------------
	Start CHECKBOX option
--------------------------------------------------------------------------------------------------*/
	function toggle ( $value ) {

		$output = $checked = '';

		$output = '<div class="onoffswitch">';
			$output .= '<input type="checkbox" name="'.$value['id'].'" class="onoffswitch-checkbox" id="' . $value['id'] .'" '. checked( $value['value'] , $value['std'], false ) .' value="'. $value['std'].'">';
			$output .= '<label class="onoffswitch-label" for="' . $value['id'] .'">';
				$output .= '<div class="onoffswitch-inner"></div>';
				$output .= '<div class="onoffswitch-switch"></div>';
			$output .= '</label>';
		$output .= '</div>';

		return $output;

	}


/*--------------------------------------------------------------------------------------------------
	Start CHECKBOX option 2
--------------------------------------------------------------------------------------------------*/
	function toggle2 ( $value ) {

		$output = '';

		$new_id = $value['id'] . zn_uid();

		$output = '<div class="zn_toggle2">';
			$output .= '<input type="hidden" name="'.$value['id'].'" checked="checked" value="zn_dummy_value" />';
			$output .= '<input type="checkbox" name="'.$value['id'].'" id="' . $new_id .'" '. checked( $value['value'] , $value['std'], false ) .' value="'. $value['value'].'">';
			$output .= '<label class="slider-v3" for="' . $new_id .'"></label>';
		$output .= '</div>';

		return $output;

	}

/*--------------------------------------------------------------------------------------------------
	Start text option
--------------------------------------------------------------------------------------------------*/
		function text($option) {

			$t_value = esc_html(stripslashes($option['std']));

			// Disable the element if it has value
			if ( !empty( $option['std'] ) && !empty ( $option['supports'] ) && $option['supports'] == 'block' ) {
				$output = '<input type="hidden" name="'.$option['id'].'" value="'.$t_value.'" placeholder="'.$option['placeholder'].'">';
				$output .= '<div class="button disabled">'.$t_value.'</div>';
			}
			else {
				$output = '<input class="zn_input zn_input_text" type="text" name="'.$option['id'].'" value="'.$t_value.'"  placeholder="'.$option['placeholder'].'">';
			}


			return $output;
		}

		function date_format($option) {

			$t_value = esc_html(stripslashes($option['std']));

			// Disable the element if it has value
			$output = '<input class="zn_input zn_input_text zn_date_format_input" type="text" name="'.$option['id'].'" value="'.$t_value.'"  placeholder="'.$option['placeholder'].'"><span class="zn_date_format_example">Output</span><span class="example">' . date_i18n( $t_value ) . '</span> <span class="spinner"></span>';

			return $output;
		}

		function zn_message($option) {

			$message_type = ! empty( $option['supports'] ) ? $option['supports'] : 'ok';
			$output = '<div class="znhtml_message znhtml_message_'.$message_type.'">';
				$output .= '<p>'.$option['name'].'</p>';
				$output .= '<p>'.$option['description'].'</p>';
			$output .= '</div>';

			return $output;
		}


		function zn_setup_icons_array(){

			$all_icons = ZN()->icon_manager->get_icons();

			$all_icon_option = array();

			foreach ( $all_icons as $name => $icon_data ) {
				foreach ( $icon_data as $icon ) {
					$unicode = ZN()->icon_manager->get_icon( $icon );
					$all_icon_option[$name][$icon] = $unicode;
				}
			}

			return $all_icon_option;

		}

/*--------------------------------------------------------------------------------------------------
	Start icon_list option
--------------------------------------------------------------------------------------------------*/
		function icon_list($option) {

			if( empty( $this->icons ) ) { $this->icons = $this->zn_setup_icons_array(); }

			if( !is_array( $option['std'] ) ) {
				$std = array( 'family' => '' , 'unicode' => '' );
			}
			else {
				$std = $option['std'];
			}

			$uid = $class = $output = '';

			if ( !empty( $option['modal'] ) && $option['modal'] == true ){
				$uid = zn_uid();
				$class = 'zn-modal-form zn_hidden';
				$output .= '<a class="zn_admin_button zn_modal_trigger no-scroll" href="#'.$uid.'" data-modal_title="Select icon">Select icon</a>';
			}

			$output .= '<div class="zn_icon_op_container '.$class.'" id="'.$uid.'">';

				$output .= '<input type="hidden" class="zn_icon_family" name="'.$option['id'].'[family]" value="'.$std['family'].'">';
				$output .= '<input type="hidden" class="zn_icon_unicode" name="'.$option['id'].'[unicode]" value="'.$std['unicode'].'">';
				$output .= '<div class="zn_icon_container">';

					foreach ( $this->icons as $name => $icon_data ) {

						$output .= '<div class="zn_font_name">Font : '.$name.'</div>';

						foreach ( $icon_data as $icon => $unicode ) {
							$class = '';

							if ( $std['unicode'] == $icon && $std['family'] == $name ) {
								$class = 'zicon_active';
							}

							$output .= '<span class="'.$class.' zn_icon" data-unicode="'.$icon.'" data-zniconfam="'.$name.'" data-zn_icon="'.$unicode.'"></span>';
						}

					}

				$output .= '</div>';
			$output .= '</div>';


			return $output;
		}


/*--------------------------------------------------------------------------------------------------
	Start Hidden text option
--------------------------------------------------------------------------------------------------*/
		function hidden($option) {
			return '<input type="hidden" name="'.$option['id'].'" value="'.$option['std'].'">';
		}

/*--------------------------------------------------------------------------------------------------
	Start textarea option
--------------------------------------------------------------------------------------------------*/
		function textarea($option) {
			$t_value = esc_html(stripslashes($option['std']));
			$output = sprintf('<textarea class="zn_input zn_input_textarea" id="%1$s" name="%1$s" rows="4">%2$s</textarea>',$option['id'],$t_value);
			return $output;
		}

/*--------------------------------------------------------------------------------------------------
	Start Custom css option
--------------------------------------------------------------------------------------------------*/
		function custom_css($option) {

			$option['std'] = get_option( 'zn_'.ZN()->theme_data['theme_id'].'_custom_css', '' );
			return $this->custom_code( $option );

		}

/*--------------------------------------------------------------------------------------------------
	Start Custom css option
--------------------------------------------------------------------------------------------------*/
		function custom_js($option) {

			$option['std'] = get_option( 'zn_'.ZN()->theme_data['theme_id'].'_custom_js', '' );
			return $this->custom_code( $option );

		}



/* Select option group */
function group_select( $option ){
	$output = '';
	$id_base = $option['id'];
	$config = isset( $option['config'] ) ? (array)$option['config'] : array();

	if( empty($config) ) { return 'The element\'s options are not properly configured'; }

	$output = '<div class="zn_row">';

		$size = isset( $config['size'] ) ? $config['size'] : 'zn_span4';
		$all_saved_values = ! empty( $option['std'] ) ? $option['std'] : array();

		if( is_array( $config['options'] ) ){
			foreach ($config['options'] as $key => $value) {
				$output .= '<div class="'.$size.'">';
				$output .= '<h4>'.$value['name'].'</h4>';
				$output .= '<select id="'.$id_base.'_'.$value['id'].'" class="zn_input zn_input_select" name="'.$id_base.'['.$value['id'].']">';
					// $output .= '<option disabled>'.$value['name'].'</option>';
					$saved_value = isset( $all_saved_values[$value['id']] ) ? $all_saved_values[$value['id']] : '';

					foreach ( $value['options'] as $opt_id => $opt_name ) {
						$output .= '<option value="'.$opt_id.'" ' . selected( $saved_value , $opt_id, false) . '>'.$opt_name.'</option>';
					}

				$output .= '</select>';
				$output .= '</div>';
			}
		}

	$output .= '</div>';

	return $output;
}

/* Text option group */
function group_text( $option ){
	$output = '';
	$id_base = $option['id'];
	$config = isset( $option['config'] ) ? (array)$option['config'] : array();

	if( empty($config) ) { return 'The element\'s options are not properly configured'; }

	$output = '<div class="zn_row">';

		$size = isset( $config['size'] ) ? $config['size'] : 'zn_span4';
		$all_saved_values = ! empty( $option['std'] ) ? $option['std'] : array();

		if( is_array( $config['options'] ) ){
			foreach ($config['options'] as $key => $value) {
				$output .= '<div class="'.$size.'">';
				$output .= '<h4>'.$value['name'].'</h4>';

				$saved_value = isset( $all_saved_values[$value['id']] ) ? $all_saved_values[$value['id']] : '';

				$output .= '<input id="'.$id_base.'_'.$value['id'].'" class="zn_input zn_input_text" placeholder="'.$value['placeholder'].'" name="'.$id_base.'['.$value['id'].']" value="'.$saved_value.'" >';
				$output .= '</div>';
			}
		}

	$output .= '</div>';

	return $output;
}

/*--------------------------------------------------------------------------------------------------
	Start font option
--------------------------------------------------------------------------------------------------*/
		function font($option) {

			if ( empty( $option['supports'] ) ) {
				return 'Please make sure the option has the supports key set';
			}

			$output = '<div class="zn_row">';

				if ( isset( $option['std']['font-family'] ) ) {
					$font_family = $option['std']['font-family'];
				}
				else {
					$font_family = '';
				}

			// If supported font
			if ( in_array( 'font', $option['supports'] ) ){

				$normal_fonts = zn_get_fonts();

				$output .= '<div class="zn_span4">';
				$output .= '<h4>Font Family</h4>';
				$output .= '<select id="'.$option['id'].'_font" class="zn_input zn_input_select" name="'.$option['id'].'[font-family]">';
					$output .= '<option disabled>Font Family</option>';
					$output .= '<option value="">-- select --</option>';

					foreach ($normal_fonts as $key => $font) {
						$output .= '<option value="'.$key.'" ' . selected( $font_family , $key, false) . '>'.$font.'</option>';
					}

				$output .= '</select>';

				$output .= '</div>';

			}

			// If supprted font size
			if ( in_array( 'size', $option['supports'] ) ) {

				if ( isset( $option['std']['font-size'] ) ) {
					$size = $option['std']['font-size'];
				}
				else {
					$size = '';
				}

				$output .= '<div class="zn_span4">';
				$output .= '<h4>Font Size</h4>';
				$output .= '<select id="'.$option['id'].'_size" class="zn_input zn_input_select" name="'.$option['id'].'[font-size]">';
				$output .= '<option value="">-- select --</option>';

						for ($i = 9; $i < 120; $i++){
								$output .= '<option value="'. $i .'px" ' . selected( $size , $i.'px', false) . '>'. $i .'px</option>';
						}

					$output .= '</select>';

				$output .= '</div>';
			}

			// If supprted line height
			if ( in_array( 'line', $option['supports'] ) ) {

				if ( isset( $option['std']['line-height'] ) ) {
					$line = $option['std']['line-height'];
				}
				else {
					$line = '';
				}

				$output .= '<div class="zn_span4">';
				$output .= '<h4>Line Height</h4>';
				$output .= '<select id="'.$option['id'].'_line" class="zn_input zn_input_select" name="'.$option['id'].'[line-height]">';
				$output .= '<option value="">-- select --</option>';

						for ($i = 1; $i < 120; $i++){
								$output .= '<option value="'. $i .'px" ' . selected( $line , $i.'px', false) . '>'. $i .'px</option>';
						}

					$output .= '</select>';

				$output .= '</div>';
			}

			// If supprted font weight
			if ( in_array( 'weight', $option['supports'] ) ) {

				if ( isset( $option['std']['font-weight'] ) ) {
					$saved_weight = $option['std']['font-weight'];
				}
				else {
					$saved_weight = '';
				}

				$font_weight = array(
						 '400' => '400 (normal) ' , '700' =>'700 (bold)' , '100' => '100' , '200' => '200' , '300' => '300' , '500' => '500', '600' => '600', '800' => '800' , '900' => '900'
					);

				$output .= '<div class="zn_span4">';
				$output .= '<h4>Font Weight</h4>';
				$output .= '<select id="'.$option['id'].'_weight" class="zn_input zn_input_select" name="'.$option['id'].'[font-weight]">';
				$output .= '<option value="">-- select --</option>';

						foreach ( $font_weight as $key => $weight ){
								$output .= '<option value="'. $key .'" ' . selected( $saved_weight , $key, false) . '>'. $weight .'</option>';
						}

					$output .= '</select>';

				$output .= '</div>';
			}

			// If supports font color
			if ( in_array( 'color', $option['supports'] ) ) {

				if ( isset( $option['std']['color'] ) ) {
					$saved_color = $option['std']['color'];
				}
				else {
					$saved_color = '';
				}

				$output .= '<div class="zn_span4">';
				$output .= '<h4>Font color</h4>';
				$output .= '<input type="text" class="zn_colorpicker" data-default-color="'.$saved_color.'" name="'.$option['id'].'[color]" value="'.$saved_color.'" >';
				$output .= '</div>';
			}

			// If supprted font style
			if ( in_array( 'style', $option['supports'] ) ) {

				if ( isset( $option['std']['font-style'] ) ) {
					$saved_style = $option['std']['font-style'];
				}
				else {
					$saved_style = '';
				}

				$font_style = array(
						'normal' , 'italic'
					);

				$output .= '<div class="zn_span4">';
				$output .= '<h4>Font Style</h4>';
				$output .= '<select id="'.$option['id'].'_style" class="zn_input zn_input_select" name="'.$option['id'].'[font-style]">';
				$output .= '<option value="">-- select --</option>';

						foreach ( $font_style as $style ){
								$output .= '<option value="'. $style .'" ' . selected( $saved_style , $style, false) . '>'. $style .'</option>';
						}

					$output .= '</select>';

				$output .= '</div>';
			}


			// If supprted letter-spacing
			if ( in_array( 'spacing', $option['supports'] ) ) {

				if ( isset( $option['std']['letter-spacing'] ) ) {
					$saved_style = $option['std']['letter-spacing'];
				}
				else {
					$saved_style = '';
				}

				$letter_spacing = array(
						'20', '10', '5', '3', '2', '1', '0', '-1', '-2', '-3', '-5', '-10', '-20'
					);

				$output .= '<div class="zn_span4">';
				$output .= '<h4>Letter Spacing</h4>';
				$output .= '<select id="'.$option['id'].'_spacing" class="zn_input zn_input_select" name="'.$option['id'].'[letter-spacing]">';
				$output .= '<option value="">-- select --</option>';

						foreach ( $letter_spacing as $sp ){
							$output .= '<option value="'. $sp .'px" ' . selected( $saved_style , $sp.'px', false) . '>'. $sp .'px</option>';
						}

					$output .= '</select>';

				$output .= '</div>';
			}

			$output .= '</div>';

			return $output;
		}

/*--------------------------------------------------------------------------------------------------
	Start Google fonts options
--------------------------------------------------------------------------------------------------*/
		function zn_google_fonts_setup($option) {

			$output = '';

			include_once( FW_PATH . '/assets/google_fonts/google_fonts.php' );
			$all_fonts = $all_google_fonts;

			if ( empty ( $option['dynamic'] )  ) {

				$output .= '<select class="zn_input zn_input_select">';
					$output .= '<option>Please select a font</option>';

					foreach ($all_fonts as $key => $font) {
						$output .= '<option value="'.$font['family'].'">'.$font['family'].'</option>';
					}
				$output .= '</select>';

				$output .= '<a class="button button-primary button-large zn_add_gfont" data-zn_data=\''.base64_encode( json_encode($option) ).'\' data-type="'.$option['id'].'" href="#">Add Font</a>';

				$number_of_elements = 0;
				$uid = '';

				// Count all default values
				if ( is_array ( $option['std'] ) )
				{
					$number_of_elements = count($option['std']);
				}

				$output .= '<div class="zn_group_inner zn_group_container zn_pb_group_content zn_google_fonts_holder" data-baseid="'.$option['id'].'">';
					if ( !empty($option['std']) ) {
						foreach ( $option['std'] as $key => $font ) {

							$uid = zn_uid();
							$selected_font = $font['font_family'];
							$font_family = $key;

							$output .= '<div class="zn_group">';
							$output .= '<div class="zn_group_header zn_gradient">';
								$output .= '<h4>'.$selected_font.'</h4>';
								// START ACTIONS
								$output .= '<div class="zn_group_actions">';
									// DELETE BUTTON
									$output .= '<a class="zn_remove"><span data-toggle="tooltip" data-title="Delete" class="zn_icon_trash"></span></a>';
									// Edit button
									$output .= '<a class="zn_modal_trigger no-scroll" href="#'.$uid.'" data-modal_title="'.$selected_font.' font options"><span data-toggle="tooltip" data-title="Edit" class="zn_icon_edit no-scroll"></span></a>';
								$output .= '</div>'; // END GROUP ACTIONS

							$output .= '</div>'; // END GROUP HEADER

						$output .= '<div id="'.$uid.'" class="zn-modal-form zn-modal-group-form zn_hidden no-scroll">';

							// $output .= '<h3>'.$selected_font.'</h3>';
							$custom_css_class = '.ff-'.strtolower(str_replace(' ', '_', $font['font_family'])).' { }';

							$option['subelements'] = array(
								array(
									'id'          => 'font_family',
									'name'        => 'Font Family',
									'type'        => 'hidden',
									'class'		  => 'zn_hidden'
								),
								array(
									'id'          => 'font_class',
									'name'        => 'Font CSS Class',
									'description'=> 'This is the custom CSS class selector for this font-family: <code>'.$custom_css_class.'</code> or straight use the css property: <code>font-family:"'.$font['font_family'].'";</code> .',
									'type'        => 'zn_title',
									'class'		  => 'zn_full'
								),
								array(
									'id'          => 'font_variants',
									'name'        => 'Font variants',
									'description' => 'Here you can select the font variants you want to load.',
									'type'        => 'checkbox',
									'options' => $all_fonts[$selected_font]['variants'],
									'class'		=> 'zn_full'
								)
							);

							foreach ( $option['subelements'] as $key => $value ) {

								// SET THE DEFAULT VALUE
								if( is_array ( $option['std'] ) && isset ( $option['std'][$font_family][$value['id']] ) ) {
									$value['std'] = $option['std'][$font_family][$value['id']];
								}

								// Set the proper id
								$value['id'] = $option['id'].'['.$font_family.']['.$value['id'].']';

								// Generate the options
								$output .= $this->zn_render_single_option($value);
							}

							$output .= '</div>'; // End Modal

						$output .= '</div>'; // Close zn_group

						}
					}


				$output .= '</div>'; // END .zn_group_container

			}
			else {

				$uid = zn_uid();
				$i = 0;
				$selected_font = $option['selected_font'];
				$font_family = str_replace(' ', '+', $selected_font);

				$output .= '<div class="zn_group">';
				$output .= '<div class="zn_group_header zn_gradient">';
					$output .= '<h4>'.$all_fonts[$selected_font]['family'].'</h4>';
					// START ACTIONS
					$output .= '<div class="zn_group_actions">';
						// DELETE BUTTON
						$output .= '<a class="zn_remove"><span data-toggle="tooltip" data-title="Delete" class="zn_icon_trash"></span></a>';
						// Edit button
						$output .= '<a class="zn_modal_trigger no-scroll" href="#'.$uid.'" data-modal_title="'.$all_fonts[$selected_font]['family'].' font options"><span data-toggle="tooltip" data-title="Edit" class="zn_icon_edit no-scroll" ></span></a>';
					$output .= '</div>'; // END GROUP ACTIONS

				$output .= '</div>'; // END GROUP HEADER

			$output .= '<div id="'.$uid.'" class="zn-modal-form zn-modal-group-form zn_hidden no-scroll">';

					$option['subelements'] = array(
						array(
							'id'          => 'font_family',
							'name'        => 'Font Family',
							'type'        => 'hidden',
							'std'		  =>  $selected_font,
							'class'		  => 'zn_hidden'
						),
						array(
							'id'          => 'font_variants',
							'name'        => 'Font variants',
							'description' => 'Here you can select the font variants you want to load.',
							'type'        => 'checkbox',
							'options' => $all_fonts[$selected_font]['variants'],
							'class'		=> 'zn_full'
						)
					);

					foreach ( $option['subelements'] as $key => $value ) {

						// Set the proper id
						$value['id'] = $option['id'].'['.$font_family.']['.$value['id'].']';

						// Generate the options
						$output .= $this->zn_render_single_option($value);
					}

			$output .= '</div>';

				$output .= '</div>';
			}

			return $output;
		}



/*--------------------------------------------------------------------------------------------------
	Start color picker option
--------------------------------------------------------------------------------------------------*/
		function colorpicker($option) {

			$alpha = !empty( $option['alpha'] ) ? 'data-alpha="true"' : false;

			$output  = '<div class="input-append color">';
			$output .= '<input type="text" class="zn_colorpicker" data-default-color="'.$option['std'].'" name="'.$option['id'].'" '.$alpha.' value="'.$option['std'].'" >';
			$output .= '</div>';

			return $output;
		}

/*--------------------------------------------------------------------------------------------------
	Start link option (new fw)
--------------------------------------------------------------------------------------------------*/
		function link($option) {

			if ( empty( $option['std']['url'] ) ){ $url = ''; } else { $url = $option['std']['url']; }
			if ( empty( $option['std']['title'] ) ){ $title = ''; } else { $title = $option['std']['title']; }
			if ( empty( $option['std']['target'] ) ){ $target = ''; } else { $target = $option['std']['target']; }

			$title = esc_html(stripslashes($title));

			// URL , TARGET , TITLE
			$output = '<label for="'.$option['id'].'_url" class="zn-form--sym dashicons dashicons-admin-links"></label>';
			$output .= '<input type="text" id="'.$option['id'].'_url" class="zn_input zn_input_text zn-form--url" name="'.$option['id'].'[url]" value="'.$url.'" placeholder="URL" >';

			if( !empty( $option['options'] ) ) {
				$output .= '<select name="'.$option['id'].'[target]" class="zn_input zn_input_select zn-form--url-target">';

					foreach ($option['options'] as $key => $value ) {
						$output .= '<option '.selected($target , $key,false).' value="'.$key.'">'.$value.'</option>';
					}

				$output .= '</select>';
			}
			else{
				$output .= '<select name="'.$option['id'].'[target]" class="zn_input zn_input_select zn-form--url-target">';
					$output .= '<option '.selected($target , '_self',false).' value="_self">Same window</option>';
					$output .= '<option '.selected($target , '_blank',false).' value="_blank">New window</option>';
				$output .= '</select>';
			}

			// $output .= '<input type="text" class="zn_input zn_input_text zn-form--url-title" name="'.$option['id'].'[title]" value="'.$title.'" placeholder="Title" >';
			$output .= '<textarea class="zn_input zn_input_textarea zn-form--url-title" name="'.$option['id'].'[title]" placeholder="Title" >'.$title.'</textarea>';


			return $output;
		}

/*--------------------------------------------------------------------------------------------------
	Start button option ( checkbox and radio )
--------------------------------------------------------------------------------------------------*/
		function buttons($option) {

			$output = '';

			if ( $option['supports'] == 'Checkboxes' ) {
				// Checkboxes

			}
			else {

				// Radios
				$i = 0;
				foreach ( $option['options'] as $key => $soption ) {
					$output .= '<input class="zn_buttons zn_input" type="radio" '. checked( $option['std'] , $key,false) .' id="'.$option['id'].'_'.$i.'" name="'.$option['id'].'" value="'.$key.'" /><label for="'.$option['id'].'_'.$i.'">'.$soption.'</label>';
					$i++;
				}
			}

			return $output;
		}

/*--------------------------------------------------------------------------------------------------
	Start background option
--------------------------------------------------------------------------------------------------*/
	function background ( $value ){
		$output =	'';
	//	$value['std'] = array( 'image' => '' );
		if( !isset ( $value['std']['image'] ) || empty( $value['std']['image'] ) )
		{
			$value['std']['image'] = '';
		}


		$output .= '<input class="logo_upload_input zn_input" id="'.$value['id'].'" type="hidden" name="'.$value['id'].'[image]" value="'.$value['std']['image'].'" />';
		$output .= '<div class="zn_upload_image_button button button-hero" data-multiple="false" data-button="Insert" data-title="Upload Logo">Select Image</div>';

		if(  !empty( $value['std']['image'] ) )
		{
			$output .= '<div class="attachment-preview zn-image-holder"><button title="Close (Esc)" type="button" class=" zn-remove-image">&#215;</button><img alt="" src="'.$value['std']['image'].'"></div>';
		}
		else
		{
			$output .= '<div class="zn-image-holder">Nothing selected...</div>';
		}


		$output .= '<div class="clearfix zn_margin20"></div>';
		$output .= '<div class="zn_row zn_image_properties">';

		if ( isset( $value['options']['repeat'] ) || !empty( $value['std']['repeat'] ) )
		{

			if( !isset ( $value['std']['repeat'] ) || empty( $value['std']['repeat'] ) )
			{
				$value['std']['repeat'] = '';
			}

			$output .= '<div class="cf zn_span6">';
			$output .= '<label>Background repeat</label>';
			$output .= '<select class="zn_input zn_input_select" name="'.$value['id'].'[repeat]" id="' . $value['id'] . '_repeat'  . '">';
			$repeats = array ('repeat' ,'repeat-x' ,'repeat-y' ,'no-repeat');

			foreach ($repeats as $repeat) {
				$output .= '<option value="' . $repeat . '" ' . selected( $value['std']['repeat'], $repeat, false ) . '>'. $repeat . '</option>';
			}
			$output .= '</select>';
			$output .= '<div class="clear"></div>';
			$output .= '</div>';
		}

		if ( isset( $value['options']['attachment'] ) )
		{

			if( !isset ( $value['std']['attachment'] ) || empty( $value['std']['attachment'] ) )
			{
				$value['std']['attachment'] = '';
			}

			$output .= '<div class=" zn_span6">';
			$output .= '<label>Background attachment</label>';
			$output .= '<select class="select zn_input zn_input_select" name="'.$value['id'].'[attachment]" id="' . $value['id'] . '_attachment'  . '">';
			$attachments = array ('scroll' ,'fixed' );

			foreach ($attachments as  $attachment) {
				$output .= '<option value="' . $attachment . '" ' . selected( $value['std']['attachment'], $attachment, false ) . '>'. $attachment . '</option>';
			}
			$output .= '</select>';
			$output .= '<div class="clear"></div>';
			$output .= '</div>';
		}

		if ( isset( $value['options']['position'] ) )
		{

			if( !isset ( $value['std']['position']['x'] ) || empty( $value['std']['position']['x'] ) )
			{
				$value['std']['position']['x'] = '';
			}

			if( !isset ( $value['std']['position']['y'] ) || empty( $value['std']['position']['y'] ) )
			{
				$value['std']['position']['y'] = '';
			}

			// Position - X
			$output .= '<div class="cf zn_span6">';
			$output .= '<label>Background position-x</label>';
			$output .= '<select class="select zn_input zn_input_select" name="'.$value['id'].'[position][x]" id="' . $value['id'] . '_position-x'  . '">';
			$positionxs = array ('left' ,'center' ,'right');

			foreach ($positionxs as  $positionx) {
				$output .= '<option value="' . $positionx . '" ' . selected( $value['std']['position']['x'], $positionx, false ) . '>'. $positionx . '</option>';
			}
			$output .= '</select>';
			$output .= '<div class="clear"></div>';
			$output .= '</div>';

			// Position - Y
			$output .= '<div class=" zn_span6">';
			$output .= '<label>Background position-y</label>';
			$output .= '<select class="select zn_input zn_input_select" name="'.$value['id'].'[position][y]" id="' . $value['id'] . '_position-y'  . '">';
			$positionys = array ('top' ,'center' ,'bottom');

			foreach ($positionys as  $positiony) {
				$output .= '<option value="' . $positiony . '" ' . selected( $value['std']['position']['y'], $positiony, false ) . '>'. $positiony . '</option>';
			}
			$output .= '</select>';
			$output .= '<div class="clear"></div>';
			$output .= '</div>';
		}

		if ( isset( $value['options']['size'] ) || !empty( $value['std']['size'] ) )
		{

			if( !isset ( $value['std']['size'] ) || empty( $value['std']['size'] ) )
			{
				$value['std']['size'] = '';
			}

			$output .= '<div class="cf zn_span6">';
			$output .= '<label>Background size</label>';
			$output .= '<select class="zn_input zn_input_select" name="'.$value['id'].'[size]" id="' . $value['id'] . '_size'  . '">';
			$sizes = array ('auto' ,'cover' ,'contain');

			foreach ($sizes as $size) {
				$output .= '<option value="' . $size . '" ' . selected( $value['std']['size'], $size, false ) . '>'. $size . '</option>';
			}
			$output .= '</select>';
			$output .= '<div class="clear"></div>';
			$output .= '</div>';
		}

		$output .= '</div>';

		return $output;
	}

/*--------------------------------------------------------------------------------------------------
	Start slider option
--------------------------------------------------------------------------------------------------*/
		function slider($option) {

			$step = !empty($option['helpers']['step']) ? $option['helpers']['step'] : '';

			$output  = '<div class="zn_slider">';
			$output .= '<input type="text" class="zn_input zn_input_text" name="'.$option['id'].'" value="'.$option['std'].'" >';
			$output .= '<div class="wp-slider slider-range-max" data-min="'.$option['helpers']['min'].'" data-step="'.$step.'" data-max="'.$option['helpers']['max'].'" data-value="'.$option['std'].'"></div>';
			$output .= '</div>';

			return $output;
		}

/*--------------------------------------------------------------------------------------------------
	Start Media Element
--------------------------------------------------------------------------------------------------*/
	function media ( $option ) {
		$output =	'';

		// This is just for Kallyas
        if(is_array($option['std'])){
            if(isset($option['std']['image'])){
                $option['std'] = $option['std']['image'];
            }
        }

		$data = $option['supports'] == 'id' ? ' data-id="true" ': '';
		$image = $option['supports'] == 'id' ? wp_get_attachment_url( $option['std'] ) : $option['std'];

		$output .= '<input class="logo_upload_input" id="'.$option['id'].'" type="hidden" '.$data.' name="'.$option['id'].'" value="'.$option['std'].'" />';
		$output .= '<div class="zn_upload_image_button" data-multiple="false" data-button="Insert" data-title="Upload Logo">Select Image</div>';

		if( !empty( $image ) )
		{
			$output .= '<div class="attachment-preview zn-image-holder"><button title="Close (Esc)" type="button" class="zn-remove-image">&#215;</button><img alt="" src=" '.$image.' "></div>';
		}
		else
		{
			$output .= '<div class="zn-image-holder">Nothing selected...</div>';
		}

		return $output;
	}


/*--------------------------------------------------------------------------------------------------
	Start Gallery Element
--------------------------------------------------------------------------------------------------*/
	function gallery ( $option ) {

		// FOR GALLERY
		$defaults = array(
			'media_type' => 'image_gallery',
			'insert_title' => 'Insert gallery', // The text that will appear on the inser button from the media manager
			'button_title' => 'Add / Edit gallery', // The text that will appear as the main option button for adding images
			'title' => 'Add / Edit gallery', // The text that will appear as the main option button for adding images
			'type' => 'image', // The media type : image, video, etc
			'value_type' => 'id', // What to return - url, id
			'state' => 'gallery-library', // The media manager state
			'frame' => 'post', // The media manager frame
			'class' => 'zn-media-gallery media-frame', // The media manager state
		);

		// Set the data
		$option['data'] = !empty( $option['data'] ) ? wp_parse_args( $option['data'], $defaults ) : $defaults;
		$option['preview_holder'] = 'No video selected';

		if ( !empty( $option['std'] ) ) {
			$saved_images = !empty( $option['std'] ) ? explode( ',', $option['std'] ) : array();
			$option['preview_holder'] = self::get_media_preview( $saved_images );
		}

		return $this->zn_media( $option );
	}

	// Returns the HTML needed for the gallery type option
	static function get_media_preview( $images ) {
		$images_holder = '';
		foreach ( $images as $image ) {
			$image_url = wp_get_attachment_image_src( $image, 'thumbnail' );
			$images_holder .= '<span class="zn-media-gallery-preview-image"><img src="'.$image_url[0].'" /></span>';
		}

		return $images_holder;
	}


	/**
	 * Generates a video element
	 * @param type $option
	 * @return string
	 */
	function video_upload( $option ) {

		// FOR Video upload
		$defaults = array(
			'media_type' => 'html5video', // The text that will appear on the inser button from the media manager
			'insert_title' => 'Select video', // The text that will appear on the inser button from the media manager
			'button_title' => 'Add / Edit video', // The text that will appear as the main option button for adding images
			'title' => 'Add / Edit video', // The text that will appear as the main option button for adding images
			'type' => 'video', // The media type : image, video, etc
			'state' => 'video-details', // The media manager state
			'frame' => 'video', // The media manager frame - can be select, post, manage, image, audio, video, edit-attachments
			'class' => 'zn-media-video media-frame', // A css class that will be applied to the modal
		);

		// Set the data
		$option['data'] = !empty( $option['data'] ) ? wp_parse_args( $option['data'], $defaults ) : $defaults;
		$option['std'] = stripslashes( $option['std'] );
		$saved_video_settings = json_decode( $option['std'], true );
		$option['preview_holder'] = 'No video selected';

		// Build the preview holder
		if ( !empty( $saved_video_settings['mp4'] ) || !empty( $saved_video_settings['ogv'] ) || !empty( $saved_video_settings['webm'] ) ) {
			$option['preview_holder'] = '<video controls>';

				// Add the mp4 string if the user selected an mp4
				if ( !empty( $saved_video_settings['mp4'] ) ){
					$option['preview_holder'] .= '<source src="'.$saved_video_settings['mp4'].'" type="video/mp4">';
				}

				if ( !empty( $saved_video_settings['ogv'] ) ){
					$option['preview_holder'] .= '<source src="'.$saved_video_settings['ogv'].'" type="video/ogg">';
				}

				if ( !empty( $saved_video_settings['webm'] ) ){
					$option['preview_holder'] .= '<source src="'.$saved_video_settings['webm'].'" type="video/webm">';
				}

			$option['preview_holder'] .= '</video>';
		}

		return $this->zn_media( $option );
	}

	/**
	 * General WP media select window
	 * @param type $option
	 * @return type
	 */
	function media_upload( $option ){
		// FOR GENERAL UPLOADS
		$defaults = array(
			'media_type' => 'media_field_upload', // The text that will appear on the inser button from the media manager
			'insert_title' => 'Select video', // The text that will appear on the inser button from the media manager
			'button_title' => 'Add / Edit video', // The text that will appear as the main option button for adding images
			'title' => 'Add / Edit video', // The text that will appear as the main option button for adding images
			'type' => 'image', // The media type : image, video, etc
			'state' => 'library', // The media manager state
			'frame' => 'select', // The media manager frame - can be select, post, manage, image, audio, video, edit-attachments
			'class' => 'zn-media-video media-frame', // A css class that will be applied to the modal
			'value_type' => 'url', // The media manager state
		);

		$args = wp_parse_args( $option['data'], $defaults );
		$data_attributes = self::set_data_attributes( $args );
		$option['std'] = esc_html( $option['std'] );

		$output = '<input id="'.$option['id'].'" class="zn-media-value-container zn_input zn_input_text" type="text"  name="'.$option['id'].'" value="'.$option['std'].'" />';

		// The main button
		$output .= '<div class="zn-main-button zn_media_upload_add zn-add-media-trigger" '.$data_attributes.'>'.$args['button_title'].'</div>';

		return $output;
	}

/*--------------------------------------------------------------------------------------------------
	Main media function
--------------------------------------------------------------------------------------------------*/
	function zn_media( $option ){

		$defaults = array(
			'button_title' => 'Add image',
			'preview' => 'image_holder',
			'preview_holder' => 'Nothing selected',

		);

		$args = wp_parse_args( $option['data'], $defaults );
		$preview_holder_class = !empty( $option['std'] ) ? '' : 'zn-media-preview-holder-empty';
		$data_attributes = self::set_data_attributes( $args );
		$option['std'] = esc_html( $option['std'] );

		$field_type = ( $args['preview'] == 'text' ) ? 'text' : 'hidden';

		// The option id where we store the values
		$output = '<input id="'.$option['id'].'" class="zn-media-value-container zn_input zn_input_text" type="'.$field_type.'"  name="'.$option['id'].'" value="'.$option['std'].'" />';

		// The main button
		$output .= '<div class="zn-main-button zn_media_upload_add zn-add-media-trigger" '.$data_attributes.'>'.$args['button_title'].'</div>';

		// RENDER THE IMAGE HOLDER
		if( $args['preview'] == 'image_holder' ){
			$output .= '<div class="zn-media-preview-holder zn_preview_holder_'.$option['data']['media_type'].' '.$preview_holder_class.'">'.$option['preview_holder'].'</div>';
		}

		return $output;

	}

	// This function prepares the data attributes
	static function set_data_attributes( $data ){
		$data_string = "";

		foreach($data as $key=>$value)
		{
			if(is_array($value)) $value = implode(", ",$value);
			$data_string .= " data-$key='$value' ";
		}

		return $data_string;
	}

/*--------------------------------------------------------------------------------------------------
	DATE PICKER
--------------------------------------------------------------------------------------------------*/
	function date_picker ( $value ){

		// Check for url
		if ( isset($value['std']['date']) )
		{
			$date_val = stripslashes($value['std']['date']);
		}
		else {
			$date_val = '';
		}

		// Check for url text
		if ( isset($value['std']['time']) )
		{
			$time_val = stripslashes($value['std']['time']);
		}
		else {
			$time_val = '';
		}

		$output = '<label for="'. $value['id'].'[date]">Date:</label><input class="zn_input zn_date_picker" name="'.$value['id'].'[date]" id="'. $value['id'].'[date]" value="'. $date_val .'" type="text" /><label for="'. $value['id'].'[time]">Time :</label><input id="'. $value['id'].'[time]" name="'. $value['id'].'[time]" value="'. $time_val .'" type="text" class="zn_input zn_time_picker" />';

		return $output;
	}


/*--------------------------------------------------------------------------------------------------
	START UPLOAD OPTION
--------------------------------------------------------------------------------------------------*/
	function upload( $option ) {

		// ONLY ALLOW SUPER ADMINS TO UPLOAD NEW ICONS
		if ( !current_user_can( 'update_plugins' ) ){
			return 'You need super admin capabilities to use this option!';
		}

		// GET/SET DEFAULTS
		$supports = $option['supports'];
		$output = '';

		// CHECK TO SEE IF THE FILE TYPE IS ALLOWED
		// CHECK ON MULTISITE
		if ( is_multisite() && strpos( get_site_option( 'upload_filetypes' ), $supports['file_extension'] ) === false )
		{
			return 'It seems that the '.$supports['file_extension'].' file type is not allowed on your multisite enable network. Please go to <a title="Network settings page" href="'.network_admin_url('settings.php').'"">Network settings page</a> and add the '.$supports['file_extension'].' file extension to the list of "Upload file types"';
		}

		// CHECK TO SEE IF ZIPARCHIVE IS INSTALLED ON THE SERVER
		if ( !class_exists('ZipArchive') ) {
			return 'It seems that the "ZipArchive" class is not installed on your server. Please contact your server administrator and ask them to enable this class in order to use this option.';
		}

		$output .= '<div class="zn_file_upload zn_admin_button" data-file_type="'.$supports['file_type'].'" data-button="Upload" data-title="Upload File">Select file</div>';
		$output .= '<div class="uploads_container">';

			$fonts = ZN()->icon_manager->get_custom_fonts();

			if( !empty( $fonts ) ) {
				foreach ( $fonts as $key => $font ) {
					$output .= '<a class="zn_remove_font" href="#">'.$key.'<span data-font_name="'.$key.'" class="zn_remove_font_trigger">&#215;</span></a> ';
				}
			}



		$output .= '</div>';

		return $output;

	}

/*--------------------------------------------------------------------------------------------------
	Start Ajax call helper
--------------------------------------------------------------------------------------------------*/
		function zn_ajax_call($option) {

			$output = '<div class="'.$option['ajax_call_setup']['action'].'_btn zn_admin_button">'.$option['ajax_call_setup']['button_text'].'</div>';
			$output .= '<div class="'.$option['ajax_call_setup']['action'].'_msg_container"></div>';

			return $output;
		}

/*--------------------------------------------------------------------------------------------------
	Start custom code option
--------------------------------------------------------------------------------------------------*/
		function custom_code( $option ) {

			$editor_type = isset( $option['editor_type'] ) ? $option['editor_type'] : 'css';
			$option['std'] = htmlspecialchars( stripslashes ( $option['std'] ) );
			$output = '<div class="zn_code_input" id="zn_code_editor_'.$option['id'].'" data-editor_type="'.$editor_type.'">'.$option['std'] .'</div>';
			$output .= '<textarea class="zn_code_input_textarea zn_hidden" id="'.$option['id'].'" name="'.$option['id'].'">'.$option['std'].'</textarea>';
			return $output;
		}

/*--------------------------------------------------------------------------------------------------
	Custom HTML
--------------------------------------------------------------------------------------------------*/
		function custom_html( $option ) {
			// $option['std'] = htmlspecialchars( stripslashes ( $option['std'] ) );
			return $this->custom_code( $option );
		}


/*--------------------------------------------------------------------------------------------------
	Start Visual editor
--------------------------------------------------------------------------------------------------*/
		function visual_editor($option) {

		ob_start();

		$id  = preg_replace('![^a-zA-Z]!', "", $option['id']) .''.zn_uid();

		$args = array(
			'editor_class' => 'zn_tinymce',
			'default_editor' => 'tmce',
			'textarea_name' => $option['id'],
			'textarea_rows' => 5,
		);

		wp_editor( stripslashes($option['std']) , $id, $args );
		$output = ob_get_clean();
		return $output;
		}

  /**
	 * Create a day picker select box
	 * @param $value
	 * @return string
	 */
	function day_picker( $value ){
		if ( empty( $value['options'] ) ) {
			$value['options'] = array(
					__('Sunday', 'zn_framework') => __('Sunday', 'zn_framework'),
					__('Monday', 'zn_framework') => __('Monday', 'zn_framework'),
					__('Tuesday', 'zn_framework') => __('Tuesday', 'zn_framework'),
					__('Wednesday', 'zn_framework') => __('Wednesday', 'zn_framework'),
					__('Thursday', 'zn_framework') => __('Thursday', 'zn_framework'),
					__('Friday', 'zn_framework') => __('Friday', 'zn_framework'),
					__('Saturday', 'zn_framework') => __('Saturday', 'zn_framework'),
			);
		}

		if(empty($value['std'])){
			$value['std'] = __('Sunday', 'zn_framework');
		}

		$out = '<select class="select zn_input zn_input_select" name="'.$value['id'].'" id="'. $value['id'] .'">';
		if(! empty($value['options'])) {
			foreach ( $value['options'] as $select_ID => $option ) {
				$out .= '<option  value="' . $select_ID . '" ' . selected( $value['std'], $select_ID, false ) . '>' . $option . '</option>';
			}
		}
		$out .= '</select>';

		return $out;
	}


	/***************************************************
	 *                Zn Radio - iphone button
	 ***************************************************/
	function zn_radio( $value )
	{
		if ( isset ( $value['rel_id'] ) ) {
			$rel = $value['rel_id'];
		} else {
			$rel = $value['id'];
		}

		$output = '';
		$output .= '<div id="' . $value['id'] . '" class="zn_radio">';
		$i = 0;
		foreach ( $value['options'] as $option => $name ) {
			$i ++;
			$label = zn_uid();

			$tooltip = '';
			$label_name = $name;

			if(is_array($name) && !empty($name)){
				if(isset($name['title'])){
					$label_name = $name['title'];
				}
				if(isset($name['tip'])){
					$tooltip = 'data-tooltip="'.$name['tip'].'"';
				}
			}

			$output .= '<input rel="' . $rel . '" id="' . $label . $i . '" name="' . $value['id'] . '" type="radio" class="zn_radio_check" value="' . $option . '" ' . checked( $value['std'], $option, false ) . ' />';
			$output .= '<label for="' . $label . $i . '" '.$tooltip.'>' . $label_name . '</label>';
		}
		$output .= '</div>';
		return $output;
	}

	/**
	 * Simple title
	 * @param  [type] $option [list of options]
	 * @return [html]         [code to render]
	 */
	function zn_title($option) {
		return '';
	}



/******* ADDED FROM OLD FRAMEWORK *****/

	/***************************************************
	 *                Zn Image size
	 ***************************************************/
	function image_size( $value )
	{
		$output = '';

		if(! is_array($value) || !isset($value['id'])){
			return $output;
		}

		$image_size = $value['std'];

		if ( empty( $image_size['width'] ) ) {
			$image_size['width'] = '';
		}

		if ( empty( $image_size['height'] ) ) {
			$image_size['height'] = '';
		}

		$output .= '<div class="zn_image_size">';
		$output .= '<div>';
		$output .= '<label>' . __( 'Width', 'zn_framework' ) . '</label>';
		$output .= '<input type="text" value="' . $image_size['width'] . '" id="' . $value['id'] . '_width" name="' . $value['id'] . '[width]" class="zn_input zn_input_text">';
		$output .= '</div>';
		$output .= '<div class="separator">' . __( 'X', 'zn_framework' ) . '</div>';
		$output .= '<div>';
		$output .= '<label>' . __( 'Height', 'zn_framework' ) . '</label>';
		$output .= '<input type="text" value="' . $image_size['height'] . '" id="' . $value['id'] . '_height" name="' . $value['id'] . '[height]" class="zn_input zn_input_text">';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	/***************************************************
	 *                Test footer columns
	 ***************************************************/
	function widget_positions( $value )
	{
		$number_of_columns  = $value['number_of_columns'];
		$columns_variations = $value['columns_positions'];

		$saved_widgets_display = stripslashes( $value['std'] );
		$saved_widgets_array   = json_decode( $saved_widgets_display, true );
		$output                = '<div class="zn_mp">';
		$output .= '<div class="zn_nop">';
		$output .= '<span class="option_title">' . __( 'Columns :', 'zn_framework' ) . '</span>';
		$output .= '<ul class="zn_number_list">';

		for ( $i = 1; $i < $number_of_columns + 1; $i ++ ) {
			$active_class = '';
			if ( $i == key( $saved_widgets_array ) ) {
				$active_class = 'active';
			}
			$output .= '<li class="nof_trigger ' . $active_class . '">' . $i . '</li>';
		}

		$output .= '</ul>';
		$output .= '<div class="clear"></div>';

		$output .= '</div>';

		$alphabet = range('a', 'd');

		$output .= '<div class="zn_positions">';

		$output .= '<div class="zn_positions_display">';

		for ( $i = 1; $i < $number_of_columns + 1; $i ++ ) {
			$css             = '';
			$saved_variation = '';

			if ( $i > key( $saved_widgets_array ) ) {
				$css = 'hidden';
			} else {
				//$saved_variation = $value['columns_positions'][key($saved_widgets_array)][0][$i-1];
				$saved_variation = $saved_widgets_array[ key( $saved_widgets_array ) ][0][ $i - 1 ];
			}
			$output .= '<div class="zn_position zn-grid-' . $saved_variation . ' ' . $css . '"><span>' . $alphabet[ $i - 1 ] . '</span></div>';
		}
		$output .= '</div>';
		$output .= '<div class="clear"></div>';
		$output .= '<div class="zn_position_options">';

		// All position variations
		$output .= '<div class="zn_position_var_options">';

		$output .= '<span class="option_title">' . __( 'Styles :', 'zn_framework' ) . '</span>';
		$output .= '<ul class="zn_number_list">';

		foreach ( $columns_variations[ key( $saved_widgets_array ) ] as $key => $val ) {
			$active_class = '';
			if ( $saved_widgets_array[ key( $saved_widgets_array ) ][0] == $val ) {
				$active_class = 'active';
			}
			$pos_value = $key + 1;
			$output .= '<li class="' . $active_class . '">' . $pos_value . '</li>';
		}

		$output .= '</ul>';

		$output .= '</div>';

		// All position variations
		$output .= '<div class="zn_all_options hidden">';

		$output .= json_encode( $columns_variations );

		$output .= '</div>';

		$output .= '</div>';

		$output .= '<div class="clear"></div>';
		// Positions input
		$output .= '<input class="zn_widgets_positions hidden" data-columns="' . key( $saved_widgets_array ) . '" name="' . $value['id'] . '" id="' . $value['id'] . '" type="text" value="' . htmlspecialchars( $saved_widgets_display ) . '" />';

		$output .= '</div>';
		$output .= '</div>';
		return $output;
	}

	/**
	 * Function for usages in box model units
	 * "std"  => array( 'top' => '10px', 'right' => '30px', 'bottom' => '20px', 'left' => '40px', 'linked' => 1 ),
	 * linked can be 1 or nothing
	 * "placeholder" => "0px",
	 */
	function boxmodel($option) {

		$output = '<div class="zn_row zn_row_gutter5 zn-boxmodel js-boxmodel-field">';

			// TOP
			if ( isset( $option['std']['top'] ) ) {
				$bm_top = $option['std']['top'];
			}
			else {
				$bm_top = '';
			}
			$output .= '<div class="zn_span2 zn-boxmodel-field">';
				$output .= '<input class="zn_input zn_input_text" type="text" name="'.$option['id'].'[top]" id="'.$option['id'].'_top" value="'.$bm_top.'"  placeholder="'.$option['placeholder'].'">';
				$output .= '<label for="'.$option['id'].'_top">TOP</label>';
			$output .= '</div>';

			// RIGHT
			if ( isset( $option['std']['right'] ) ) {
				$bm_right = $option['std']['right'];
			}
			else {
				$bm_right = '';
			}
			$output .= '<div class="zn_span2 zn-boxmodel-field">';
				$output .= '<input class="zn_input zn_input_text" type="text" name="'.$option['id'].'[right]" id="'.$option['id'].'_right" value="'.$bm_right.'"  placeholder="'.$option['placeholder'].'">';
				$output .= '<label for="'.$option['id'].'_right">RIGHT</label>';
			$output .= '</div>';

			// BOTTOM
			if ( isset( $option['std']['bottom'] ) ) {
				$bm_bottom = $option['std']['bottom'];
			}
			else {
				$bm_bottom = '';
			}
			$output .= '<div class="zn_span2 zn-boxmodel-field">';
				$output .= '<input class="zn_input zn_input_text" type="text" name="'.$option['id'].'[bottom]" id="'.$option['id'].'_bottom" value="'.$bm_bottom.'"  placeholder="'.$option['placeholder'].'">';
				$output .= '<label for="'.$option['id'].'_bottom">BOTTOM</label>';
			$output .= '</div>';

			// LEFT
			if ( isset( $option['std']['left'] ) ) {
				$bm_left = $option['std']['left'];
			}
			else {
				$bm_left = '';
			}
			$output .= '<div class="zn_span2 zn-boxmodel-field">';
				$output .= '<input class="zn_input zn_input_text" type="text" name="'.$option['id'].'[left]" id="'.$option['id'].'_left" value="'.$bm_left.'"  placeholder="'.$option['placeholder'].'">';
				$output .= '<label for="'.$option['id'].'_left">LEFT</label>';
			$output .= '</div>';

			// Linked checkbox
			$output .= '<div class="zn_span4">';

				$checked = '';
				if ( isset( $option['std']['linked'] ) && $option['std']['linked'] == 1 ) {
					$checked = 'checked="checked"';
				}
				$output .= '<input type="checkbox" class="zn_input zn_input_checkbox zn-boxmodel-linked" name="'.$option['id'].'[linked]" id="'.$option['id'].'_linked" value="1" ' . $checked . ' ><label for="'.$option['id'].'_linked">Linked?</label>';
			$output .= '</div>';

		$output .= '</div>';

		return $output;
	}


/*--------------------------------------------------------------------------------------------------
	Start radio image
--------------------------------------------------------------------------------------------------*/
	function radio_image($option) {

		$output = '';
		$list_output = '';
		$item_output = '';

		$output .= '<div class="zn-radio-image-container clearfix">';

		$i = 0;
		foreach ( $option['options'] as $key => $soption ) {

			$s_value = '';
			if(isset($soption['value'])){
				$s_value = $soption['value'];
			}

			if( !empty($s_value) ){
				$list_output .= '<option value="'.$soption['value'].'" '.selected($option['std'], $soption['value'], false).'>'.$soption['name'].'</option>';
			}


			$active = !empty($s_value) && $option['std'] == $s_value ? 'active' : '';
			$dummy = empty($s_value) && $soption['dummy'] == true ? 'data-dummy="1"' : '';

			$item_output .= '<div class="zn-radio-image-box '.$active.'" '.$dummy.'>';

				$desc = '';
				if(isset($soption['desc'])){
					$desc = '<div class="zn-radio-image-desc">'.$soption['desc'].'</div>';
				}

				$item_output .= '<div class="zn-radio-button '.$active.'" '.$dummy.' data-value="'.$s_value.'"><img src="'.$soption['image'].'"/>'.$desc.'</div>';

				$item_output .= '<span class="zn-radio-img-title">' . $soption['name'].'</span>';
			$item_output .= '</div>';
			$i++;
		}

		$output .= '<select class="zn-radio-image-select" name="'.$option['id'].'" id="'.$option['id'].'">';
		$output .= $list_output;
		$output .= '</select>';
		$output .= $item_output;

		$output .= '</div>';
		return $output;
	}

/*--------------------------------------------------------------------------------------------------
	Start radio image
--------------------------------------------------------------------------------------------------*/
	function tabbed_form($option) {

		$menu = '';
		$i = 0;
		foreach ($option['menu'] as $menu_id => $menu_args) {
			$cls = $i === 0 ? 'active' : '';
			$menu .= '<li class="'.$cls.'" id="'.$menu_args['id'].'"><a href="#zn_tab_'.$menu_args['id'].'">'.$menu_args['name'].'</a></li>';
			$i++;
		}


		ob_start();
		?>
			<div class="znfb-row znopt_tabbed_group">
				<div class="znopt_tabbed_menu_container">
					<ul class="znopt_tabbed_menu">
						<?php echo $menu; ?>
					</ul>
				</div>
				<div class="znopt_tabbed_content">
					<?php
						$i = 0;
						foreach ( $option['menu'] as $menu_id => $menu_args) {
							$cls = $i === 0 ? 'active' : '';
							echo '<div class="znopt_single_tab '.$cls.'" id="zn_tab_'.$menu_args['id'].'">';
								if( empty( $menu_args['options'] ) ) continue;
								foreach ( $menu_args['options'] as $key => $single_option) {

									$saved_value = get_post_meta( $post->ID, $single_option['id'] , true);
									if(  !empty($saved_value) ) {
										$single_option['std'] = $saved_value;
									}

									echo $this->zn_render_single_option($single_option);
								}

							echo '</div>';
							$i++;
						}

					?>

				</div>
			</div>
		<?php
		$output = ob_get_clean();
		return $output;
	}

	function options_wrapper( $options ){
		if( ! empty( $options['option_file'] ) ){
			ob_start();
			include( $options['option_file'] );
			$output = ob_get_clean();
		}
		return $output;
	}

}



?>
