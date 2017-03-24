<?php if(! defined('ABSPATH')){ return; }

// Add ajax functionality
add_action( 'wp_ajax_zn_ajax_callback', 'zn_ajax_callback' );
add_action( 'wp_ajax_zn_theme_registration', 'theme_registration_hook' );
add_action( 'wp_ajax_zn_dummy_install', 'dummy_install_hook' );

function dummy_install_hook(){
	if ( ! isset( $_POST['zn_nonce'] ) || ! wp_verify_nonce( $_POST['zn_nonce'], 'zn_dummy_install' ) ) {
		print 'Sorry, your nonce did not verify.';
		exit;
	}

	require_once( FW_PATH . '/admin/class-dummy-install.php' );

	$install_folder = isset( $_POST['install_folder'] ) ? $_POST['install_folder'] : false;
	$importer = new ZnDummyDataManager( $install_folder );

	wp_send_json_success();
	wp_die();
}

function theme_registration_hook(){
	if ( ! isset( $_POST['zn_nonce'] ) || ! wp_verify_nonce( $_POST['zn_nonce'], 'zn_theme_registration' ) ) {
		print 'Sorry, your nonce did not verify.';
		exit;
	}

	$option_name = ZN()->theme_data['theme_id'].'_update_config';
	$tf_username = isset( $_POST['username'] ) ? $_POST['username'] : '';
	$tf_api      = isset( $_POST['api_key'] ) ? $_POST['api_key'] : '';

	if( ! empty( $tf_username ) && ! empty( $tf_api ) ){
		$config = array(
			'tf_username' => $tf_username,
			'tf_api' => $tf_api,
		);

		// Save the updater values
		update_option( $option_name, $config, false );
	}

	wp_die();

}


function zn_ajax_callback() {

	check_ajax_referer( 'zn_framework', 'zn_ajax_nonce' );

	$save_action = $_POST['zn_action'];

	if ( $save_action == 'zn_save_options' ) {

		// DO ACTION FOR SAVED OPTIONS
		do_action( 'zn_save_theme_options' );

		$data = json_decode( stripslashes($_POST['data']), true );

		/* REMOVE THE HIDDEN FORM DATA */
		unset($data['action']);
		unset($data['zn_action']);
		unset($data['zn_ajax_nonce']);

		$options_field = $data['zn_option_field'];

		// Combine all options
		// Get all saved options
		$saved_options = zget_option( '' , '' , true );
		$saved_options[$options_field] = $data;

		// Save the Custom CSS in c sutom field
		if ( isset( $saved_options['advanced']['custom_css'] ) ) {
			$custom_css = $saved_options['advanced']['custom_css'];
			update_option( 'zn_'.ZN()->theme_data['theme_id'].'_custom_css', $custom_css, false );

			// Remove custom css from the main options field
			unset( $saved_options['advanced']['custom_css'] );
		}

		if ( isset( $saved_options['advanced']['custom_js'] ) ) {
			$custom_js = $saved_options['advanced']['custom_js'];
			update_option( 'zn_'.ZN()->theme_data['theme_id'].'_custom_js', $custom_js, false );

			// Remove custom js from the main options field
			unset( $saved_options['advanced']['custom_js'] );
		}

		$saved_options['advanced']['saved_date'] = microtime(true);
		$saved_options = apply_filters( 'zn_options_to_save', $saved_options );

		$result = update_option( ZN()->theme_data['options_prefix'], $saved_options);
		generate_options_css($saved_options); //generate static css file

		if ( $result == 0 || $result ) {
				echo 'Settings successfully save';
			die();
		}
		else {
				echo 'There was a problem while saving the options';
			die();
		}

	}
	elseif ( $save_action == 'zn_add_element' ) {

		$data = $_POST;

		if ( empty( $data['zn_elem_type'] ) ) {
			return;
		}

		$value = json_decode ( base64_decode( $data['zn_json'] ), true );
		$value['dynamic'] = true;

		echo ZN()->html()->zn_render_single_option( $value );

		die();
	}
	elseif ( $save_action == 'zn_add_google_font' ) {

		$data = $_POST;

		if ( empty( $data['zn_elem_type'] ) ) {
			return;
		}

		$value = json_decode ( base64_decode( $data['zn_json'] ), true );
		if( isset( $data['selected_font'] ) ) {
			$value['selected_font'] = $data['selected_font'];
		}
		$value['dynamic'] = true;

		echo ZN()->html()->zn_render_single_option( $value );

		die();
	}
	elseif( $save_action == 'zn_process_theme_updater' ){
		ZN()->installer->update( $_POST['step'], $_POST['data'] );
		die();
	}
	elseif( $save_action == 'zn_refresh_pb' ){
		ZN()->pagebuilder->refresh_pb_data();
		die();
	}
	else {
		die('Are you cheating ?');
	}
}
