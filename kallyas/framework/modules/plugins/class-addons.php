<?php if(! defined('ABSPATH')){ return; }

class ZnAddons{

	function __construct(){
	//	$this->includes();
		//add_action( 'admin_menu', array( &$this, 'admin_pages' ) );
		//add_action( 'zn_theme_installed', array( &$this, 'redirect_theme_install' ) );
	//	add_action( 'admin_enqueue_scripts', array( &$this, 'load_scripts' ) );
	}

	function includes(){
	//	include dirname( __FILE__ ) . '/ui/addons.view.php';
	//	include dirname( __FILE__ ) . '/class-plugins-ajax.php';
	}

	// function admin_pages(){
	// 	add_menu_page( 'Theme &ndash; Addons: Home', ZN()->theme_data['name'].' Addons', 'manage_options', 'zn-addons-home', 'zn_addons_page_extensions', NULL );
	// 	add_submenu_page( 'zn-addons-home', 'Theme &ndash; Addons: Home', 'Home', 'manage_options', 'zn-addons-home', 'zn_addons_page_extensions' );
	// }

	// function redirect_theme_install(){
	// 	wp_redirect( admin_url( 'admin.php?page=zn-addons-home' ) );
	// 	exit;
	// }

	function load_scripts( $hook ){
		if ( 'toplevel_page_zn-addons-home' != $hook ) {
	        return;
	    }
		wp_enqueue_style( 'zn_extensions_css', FW_URL .'/modules/plugins/assets/css/zn_extensions.css' );
		wp_enqueue_style( 'zn_html_css', FW_URL .'/assets/css/zn_html_css.css' );
		wp_enqueue_script( 'zn_modal', FW_URL .'/assets/js/zn_modal.js',array('jquery'),ZN_FW_VERSION,true );
		wp_enqueue_script( 'zn_extensions_js', FW_URL .'/modules/plugins/assets/js/zn_extensions.js', ZN_FW_VERSION, true );
	}
}
