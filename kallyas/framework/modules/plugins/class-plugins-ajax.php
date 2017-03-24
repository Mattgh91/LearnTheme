<?php if(! defined('ABSPATH')){ return; }

class ZnPluginsAjax {

	function __construct(){
		add_action('wp_ajax_zn_do_plugin_action', array( &$this, 'zn_do_plugin_action' ) );
	}

	public function zn_do_plugin_action(){
		check_ajax_referer( 'zn_plugins_nonce', 'security' );

		$action		= !empty( $_REQUEST['plugin_action'] ) ? $_REQUEST['plugin_action'] 	: false;
		$slug		= !empty( $_REQUEST['slug'] ) 			? $_REQUEST['slug'] 			: false;

		// Perform plugin actions here
		switch ( $action ) {
			case 'enable_plugin':
				ZnPlugins()->do_plugin_activate( $slug );

				break;
			case 'install_plugin':
				$credentials = !empty( $_REQUEST['credentials'] ) ? $_REQUEST['credentials'] : false;
				ZnPlugins()->do_plugin_install( $slug, $credentials );

				break;
			case 'disable_plugin':
				ZnPlugins()->do_plugin_deactivate( $slug );

				break;
			case 'update_plugin':
				ZnPlugins()->do_plugin_update( $slug );

				break;
			default:
				# code...
				break;
		}


		echo 'asdasd';
	}

}

new ZnPluginsAjax();
