<?php if(! defined('ABSPATH')){ return; }

function zn_addons_page_extensions(){
	?>
	<div class="wrap zn-extensions-container">
		<header class="zn-extensions-header">
			<h2><?php _e( 'THEME EXTENSIONS', 'zn_framework' ); ?></h2>
			<p><?php _e( 'Expand the functionality of the theme with the following extensions.', 'zn_framework' ); ?></p>

		</header>

		<?php
		// Check to see if an older TGM class is instantiated
		if( ! method_exists( TGM_Plugin_Activation::$instance, 'is_plugin_installed' ) ){

			echo '<div class="zn-extensions-error-container">';
				echo '<p class="zn-extensions-error-message">'. __( 'It seems that one of your plugins included an outdated version of the TGM Plugin Activation class. This means that you will not get the full features of this page. Please contact that plugin developer and kindly ask him to upgrade the TGM Plugin Activation class', 'zn_framework' ).'</p>';
				echo '<p><a href="'. admin_url( 'themes.php?page=install-required-plugins' ).'">'.__( 'In the meantime, install plugins from here', 'zn_framework' ).'</a></p>';

				// Extra check for reflection class as it is not installed on all servers
				if( class_exists( 'ReflectionClass' ) ){
					$reflection_class = new ReflectionClass( TGM_Plugin_Activation::$instance );
					echo '<div class="zn-extensions-error-location">'.__( 'The file was loaded from this location :','zn_framework') . $reflection_class->getFileName() .'</div>';
				}

			echo '</div>';

			return false;
		}
		?>

		<?php include dirname( __FILE__ ) . '/tmpl-addons-list.php'; ?>

	</div>
	<?php
}
