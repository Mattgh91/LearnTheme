		<ul class="zn-extensions-list cf">
		<?php


		foreach ( TGM_Plugin_Activation::$instance->plugins as $plugin ) : ?>

			<?php
			$button = '';
			if( TGM_Plugin_Activation::$instance->is_plugin_installed( $plugin['slug'] ) ){

				// TODO
				if( TGM_Plugin_Activation::$instance->does_plugin_have_update( $plugin['slug'] ) ){
					$status			= 'has_update';
					$status_message	= __( 'Needs update', 'zn_framework');
					$text			= __( 'Update plugin', 'zn_framework');
					$class			= 'zn-extension-button button button-primary';
					$action			= 'update_plugin';
					$url   = wp_nonce_url(
						add_query_arg(
							array(
								'page' => TGM_Plugin_Activation::$instance->menu,
								'plugin' => $plugin['slug'],
								'tgmpa-update' => 'update-plugin',
							),
							admin_url( TGM_Plugin_Activation::$instance->parent_slug )
						),
						'tgmpa-update',
						'tgmpa-nonce'
					);

				}
				// TODO
				elseif ( TGM_Plugin_Activation::$instance->is_plugin_active( $plugin['slug'] ) ) {
					$status			= 'active';
					$status_message	= __( 'Active', 'zn_framework');

					$text			= __( 'Manage plugins', 'zn_framework');
					$class			= 'zn-extension-button button';
					$action			= 'disable_plugin';
					$url   = admin_url( 'plugins.php' );


				} else  {
					$status			= 'inactive';
					$status_message	= __( 'Inctive', 'zn_framework');
					$text			= __( 'Enable plugin', 'zn_framework');
					$class			= 'zn-extension-button button button-primary';
					$action			= 'enable_plugin';
					$url   = wp_nonce_url(
						add_query_arg(
							array(
								'page' => TGM_Plugin_Activation::$instance->menu,
								'plugin' => $plugin['slug'],
								'tgmpa-activate' => 'activate-plugin',
							),
							admin_url( TGM_Plugin_Activation::$instance->parent_slug )
						),
						'tgmpa-activate',
						'tgmpa-nonce'
					);

				}
			}
			// TODO
			else{
				$status			= 'not-installed';
				$status_message	= __( 'Not Installed', 'zn_framework');
				$text			= __( 'Install plugin', 'zn_framework');
				$class			= 'zn-extension-button button button-primary';
				$action			= 'install_plugin';
				$url   = wp_nonce_url(
					add_query_arg(
						array(
							'page' => TGM_Plugin_Activation::$instance->menu,
							'plugin' => $plugin['slug'],
							'tgmpa-install' => 'install-plugin',
						),
						admin_url( TGM_Plugin_Activation::$instance->parent_slug )
					),
					'tgmpa-install',
					'tgmpa-nonce'
				);

			}
				$button = '<a class="'.$class.'" href="'.$url.'">' . $text . '</a>';
			?>
			<li class="zn-extension <?php echo $status; ?>" id="<?php echo $plugin['slug']; ?>">
				<div class="zn-extension-inner">
					<img src="<?php echo $plugin['z_plugin_icon']; ?>" class="img">
					<div class="zn-extension-info">
						<h4 class="zn-extension-title"><?php echo $plugin['name']; ?></h4>
						<span class="zn-extension-status <?php echo $status; ?>"><?php echo $status_message; ?></span>
						<p class="zn-extension-desc"><?php echo $plugin['z_plugin_description']; ?></p>
						<p class="zn-extension-author"><cite>By <?php echo $plugin['z_plugin_author']; ?></cite></p>
					</div>
					<?php //if( !empty( $url ) ) : ?>
					<div class="zn-extension-actions">
						<div class="">
							<?php echo $button; ?>
						</div>
					</div>
					<?php //endif; ?>
				</div>

			</li>
		<?php endforeach; ?>
		</ul>