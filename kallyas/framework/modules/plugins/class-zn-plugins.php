<?php if(! defined('ABSPATH')){ return; }

class ZnPlugins{

	/**
	 * @var ZnPlugins The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	var $plugins = array();

	/**
	 * Main ZnPlugins Instance
	 *
	 * Ensures only one instance of ZnPlugins is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see ZnPlugins()
	 * @return ZnPlugins - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'zn_framework' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'zn_framework' ), '1.0.0' );
	}

	/**
	 * Auto-load in-accessible properties on demand.
	 *
	 * @access public
	 * @param mixed $key
	 * @return mixed
	 */
	public function __get( $key ) {
		return $this->$key();
	}


	function __construct(){
		do_action( 'zn_plugins_init' );
	}

	function init( $plugins ){
		foreach ( $plugins as $plugin ) {
			$this->register( $plugin );
		}
	}

	/**
	 * Performs plugins installation
	 * @return type
	 */
	function do_plugin_install( $slug, $saved_credentials = false ){
		if( empty( $this->plugins[$slug] ) ){
			return false;
		}

		$url = $this->get_download_url( $slug );
		$status = array(
			'status'		=> 'inactive',
			'status_text'	=> __( 'Inctive', 'zn_framework'),
			'action'	=> 'enable_plugin',
			'action_text'	=> __( 'Enable plugin', 'zn_framework'),
		);

		if( !empty( $saved_credentials ) ){
			parse_str($saved_credentials, $saved_credentials);
			if ( ! WP_Filesystem( $saved_credentials ) ) {
				$creds = $this->get_filesystem_credentials( $slug );
			}
			else{
				// We need to define the variables
				// look into includes/file.php
				if ( ! defined('FTP_HOST') && isset( $saved_credentials['hostname'] ) ) { define('FTP_HOST', $saved_credentials['hostname'] ); }
				if ( ! defined('FTP_USER') && isset( $saved_credentials['username'] ) ) { define('FTP_USER', $saved_credentials['username'] ); }
				if ( ! defined('FTP_PASS') && isset( $saved_credentials['password'] ) ) { define('FTP_PASS', $saved_credentials['password'] ); }
				if ( ! defined('FTP_PUBKEY') && isset( $saved_credentials['public_key'] ) ) { define('FTP_PUBKEY', $saved_credentials['public_key'] ); }
				if ( ! defined('FTP_PRIKEY') && isset( $saved_credentials['private_key'] ) ) { define('FTP_PRIKEY', $saved_credentials['private_key'] ); }

			}
		}
		else{
			$creds = $this->get_filesystem_credentials( $slug );
		}

		if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		}

		$upgrader = new Plugin_Upgrader( new Automatic_Upgrader_Skin() );
		$result = $upgrader->install( $url );

		if ( is_wp_error( $result ) ) {
			$status['error'] = $result->get_error_message();
	 		wp_send_json_error( $status );
		}

		if( !empty( $this->plugins[$slug]['force_activation'] ) ){
			$this->do_plugin_activate( $slug );
		}

		wp_send_json_success( $status );

	}

	function get_filesystem_credentials( $slug ){

		$filesystem_method = get_filesystem_method();
		ob_start();
		$filesystem_credentials_are_stored = request_filesystem_credentials( self_admin_url() );
		ob_end_clean();

		$request_filesystem_credentials = ( $filesystem_method != 'direct' && ! $filesystem_credentials_are_stored );
		if ( ! $request_filesystem_credentials ) {
			return true;
		}

		$credentials_url = wp_nonce_url(
			admin_url( 'admin-ajax.php?action=zn_do_plugin_action&plugin_action=install_plugin&slug='.$slug),
			'zn_plugins_nonce'
		);

		ob_start();
			request_filesystem_credentials( esc_url_raw( $credentials_url ), '', false, false, array() );
		$out = ob_get_clean();

		// Send the filesystem credentials so we can open a modal
		wp_send_json_success( array(
			'credential_form' => $out
		));
	}

	/**
	 * Performs plugins activation
	 * @return type
	 */
	function do_plugin_activate( $slug ){
		if( empty( $this->plugins[$slug] ) ){
			$status['error'] = 'We have no data about this plugin.';
	 		wp_send_json_error( $status );
		}

		$result = activate_plugin( $this->plugins[$slug]['zn_plugin'] );
		if ( is_wp_error( $result ) ) {
			$status['error'] = $result->get_error_message();
	 		wp_send_json_error( $status );
		}

		$status = array(
			'status'		=> 'active',
			'status_text'	=> __( 'Active', 'zn_framework'),
			'action'	=> 'disable_plugin',
			'action_text'	=> __( 'Disable plugin', 'zn_framework'),
		);
		wp_send_json_success( $status );
	}

	/**
	 * Performs a plugin dezactivation
	 * @return type
	 */
	function do_plugin_deactivate( $slug ){
		if( empty( $this->plugins[$slug] ) ){
			$status['error'] = 'We have no data about this plugin.';
	 		wp_send_json_error( $status );
		}

		deactivate_plugins( $this->plugins[$slug]['zn_plugin'] );

		$status = array(
			'status'		=> 'inactive',
			'status_text'	=> __( 'Inctive', 'zn_framework'),
			'action'	=> 'enable_plugin',
			'action_text'	=> __( 'Enable plugin', 'zn_framework'),
		);
		wp_send_json_success( $status );

	}

	function do_plugin_update( $slug ){
		if( empty( $this->plugins[$slug] ) ){
			$status['error'] = 'We have no data about this plugin.';
	 		wp_send_json_error( $status );
		}

		if( $this->plugin_has_update( $slug ) ){
			if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			}

			$upgrader = new Plugin_Upgrader( new Automatic_Upgrader_Skin() );
			$result = $upgrader->upgrade( $slug );

			if ( is_wp_error( $result ) ) {
				$status['error'] = $result->get_error_message();
		 		wp_send_json_error( $status );
			}

			if( !empty( $this->plugins[$slug]['force_activation'] ) ){
				$this->do_plugin_activate( $slug );
			}
		}
	}

	/**
	 * Checks if a plugin is installed
	 * @return type
	 */
	function is_plugin_installed( $slug ){

	}

	/**
	 * Performs plugin update
	 * @return type
	 */
	function plugin_has_update( $slug ){
		if( empty( $this->plugins[$slug] ) ){
			return false;
		}

		$installed_version = $this->get_installed_version( $slug );
		$minimum_version   = $this->plugins[ $slug ]['version'];

		return version_compare( $minimum_version, $installed_version, '>' );

	}

	function get_installed_version( $slug ){
		$installed_plugins = $this->get_plugins(); // Retrieve a list of all installed plugins (WP cached).

		if ( ! empty( $installed_plugins[ $this->plugins[ $slug ]['file_path'] ]['Version'] ) ) {
			return $installed_plugins[ $this->plugins[ $slug ]['file_path'] ]['Version'];
		}

		return '';
	}

	public function get_plugins( $plugin_folder = '' ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return get_plugins( $plugin_folder );
	}

	/**
	 * Returns the install url for the current plugin
	 * @param type $slug 
	 * @return type
	 */
	public function get_download_url( $slug ) {
		$dl_source = '';

		switch ( $this->plugins[ $slug ]['source_type'] ) {
			case 'repo':
				return $this->get_wp_repo_download_url( $slug );
			case 'external':
				return $this->plugins[ $slug ]['source'];
		}

		return $dl_source; // Should never happen.
	}

	function get_wp_repo_download_url( $slug ){
		include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' ); // for plugins_api..
		$api = plugins_api('plugin_information', array('slug' => $slug, 'fields' => array('sections' => false) ) ); //Save on a bit of bandwidth.
		if ( is_wp_error( $api ) ) {
	 		$status['error'] = $api->get_error_message();
	 		wp_send_json_error( $status );
	 	}

	 	return $api->download_link;
	}

	/**
	 * Registers a plugin
	 * @return null
	 */
	function register( $plugin ){
		if ( empty( $plugin['slug'] ) || ! is_string( $plugin['slug'] ) || isset( $this->plugins[ $plugin['slug'] ] ) ) {
			return;
		}

		$defaults = array(
			'name'               => '',      // String
			'slug'               => '',      // String
			'source'             => 'repo',  // Can be 'repo', 'local', 'custom url'
			'source_type'        => 'repo',  // Can be 'repo', 'local', 'custom url'
			'required'           => false,   // Boolean
			'version'            => '',      // String
			'force_activation'   => false,   // Boolean
			'force_deactivation' => false,   // Boolean
			'external_url'       => '',      // String
			'z_plugin_icon'       => '',      // String
			'z_plugin_author'       => '',      // String
			'z_plugin_description'       => '',      // String
			'zn_plugin'       => '',      // String
		);

		// Prepare the received data.
		$plugin = wp_parse_args( $plugin, $defaults );

		// Forgive users for using string versions of booleans or floats for version number.
		$plugin['version']            = (string) $plugin['version'];
		$plugin['source']             = empty( $plugin['source'] ) ? 'repo' : $plugin['source'];
		$plugin['required']           = $plugin['required'];
		$plugin['force_activation']   = $plugin['force_activation'];
		$plugin['force_deactivation'] = $plugin['force_deactivation'];

		// Enrich the received data.
		$plugin['file_path']   = $plugin['zn_plugin'];

		// Set the class properties.
		$this->plugins[ $plugin['slug'] ]    = $plugin;
	}
}

function ZnPlugins() {
	return ZnPlugins::instance();
}

