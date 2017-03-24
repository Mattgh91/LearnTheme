<?php if(! defined('ABSPATH')){ return; }
/**
 * Theme updater system for Envato marketplaces
 *
 * @author 		ThemeFuzz
 * @category 	Admin
 * @package 	ZnFramework/Modules/envato_theme_updater
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ZnThemeUpdater Class
 */
class ZnThemeUpdater {

	private $config = array();

	function __construct( $updater_config ){

		$this->config = $updater_config;
		add_action( 'init', array( $this, 'zn_check_updates' ) );

	}

	function zn_check_updates(){

		$option_name = ZN()->theme_data['theme_id'].'_update_config';
		$saved_config = get_option( $option_name );

		if ( ! empty( $saved_config['tf_username'] ) && ! empty( $saved_config['tf_api'] ) && ! empty( $this->config['author'] ) ) {
			require_once( FW_PATH .'/modules/envato_theme_updater/class-pixelentity-theme-update.php' );
			PixelentityThemeUpdate::init( $saved_config['tf_username'], $saved_config['tf_api'], $this->config['author'] );
		}
	}

}
