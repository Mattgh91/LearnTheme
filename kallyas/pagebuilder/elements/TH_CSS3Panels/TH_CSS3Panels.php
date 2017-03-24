<?php if(! defined('ABSPATH')){ return; }
/*
Name: CSS3 Panels
Description: Create and display a CSS3 Panels element
Class: TH_CSS3Panels
Category: headers, Fullwidth
Level: 1
Scripts: true
*/

/**
 * Class TH_CSS3Panels
 *
 * Create and display a CSS3 Panels element
 *
 * @package  Kallyas
 * @category Page Builder
 * @author Team Hogash
 * @since 3.8.0
 */
class TH_CSS3Panels extends ZnElements
{
	public static function getName(){
		return __( "CSS3 Panels", 'zn_framework' );
	}

	/**
	 * Load dependant resources
	 */
	function scripts()
	{
		wp_enqueue_script('jquery');
	}

	/**
	 * This method is used to display the output of the element.
	 * @return void
	 */
	function element()
	{

		$options = $this->data['options'];

		if( empty( $options ) ) { return; }

		$all = 0;
		if( isset($options['single_css_panel']) && is_array($options['single_css_panel']) ){
			$all = count( $options['single_css_panel'] );
		}

		$sheight = $this->opt('css_height','600');

		$panels_resize = 'css3panels--resize';
		if( isset($options['panel_resize']) ) {
			$panels_resize = $options['panel_resize'] ? 'css3panels--resize' : '';
		}

		$elm_classes=array();
		$elm_classes[] = $this->data['uid'];
		$elm_classes[] = zn_get_element_classes($this->data['options']);
		// Flexbox Engine disable
		$elm_classes[] = $panel_flexbox = $this->opt('panel_flexbox', '1') != '1' ? 'no-flexbox' : 'css3p--flexbox';

		?>
		<div class="kl-slideshow kl-slideshow-css3panels <?php echo implode(' ', $elm_classes); ?>">

			<div class="clearfix"></div>
			<div class="fake-loading loading-1s"></div>

			<div class="css3panels-container <?php echo $panels_resize;?> <?php echo ( isset($options['panel_caption_effect']) ? 'cssp-capt-animated cssp-capt-'.$options['panel_caption_effect'] : ''); ?>" data-panels="<?php echo $all; ?>" style="<?php echo 'height:'.$sheight.'px'; ?>">
				<?php
				if ( isset ( $options['single_css_panel'] ) && is_array( $options['single_css_panel'] ) ) {
					$i   = 1;

					foreach ( $options['single_css_panel'] as $panel ) {

						echo '<div class="css3panel css3panel--' . $i . ' cp-theme--'.(isset($panel['panel_text_theme']) ? $panel['panel_text_theme'] : 'light').'">';

							echo '<div class="css3panel-inner" style=" height:'.$sheight.'px " >';
								echo '<div class="css3panel-mainimage-wrapper" style=" height:'.$sheight.'px " >';

								if ( isset ( $panel['panel_image'] ) && ! empty ( $panel['panel_image'] ) ) {
									$panel_img = $panel['panel_image'];

									$alt = ZngetImageAltFromUrl($panel_img, true);
									$title = ZngetImageTitleFromUrl($panel_img, true);

									echo '<div class="css3panel-mainimage">';

									echo '<img src="'.$panel_img.'"  class="css3panel-mainimage-img cover-fit-img" '.$alt.' '.$title.'>';

									if( isset($options['panel_effect']) && !empty($options['panel_effect']) ){
										echo '<div style="background-image:url('.$panel_img.')" class="css3panel-mainimage-img css3panel-mainimage-effect '. $options['panel_effect'] .'"></div>';
									}

									// check if overlay is enabled
									if( isset($panel['panel__overlay']) && $panel['panel__overlay'] && isset($panel['panel__overlay_color']) ){
										echo '<div class="css3p-overlay css3p--overlay-color" style="background: '.zn_hex2rgba_str( $panel['panel__overlay_color'], $panel['panel__overlay_opacity'] ).'"></div>';
									} else {
										echo '<div class="css3p-overlay css3p-overlay--gradient"></div>';
									}
									echo '</div>';
								}

								echo '</div>';
							echo '</div>';

						// Panel Position
						$panel_position = '';
						if ( isset ( $panel['panel_title_position'] ) && ! empty ( $panel['panel_title_position'] ) ) {
							$panel_position = 'css3caption--middle';
						}

						// Panel Content
						if (
							( isset ($panel['panel_title']) && ! empty ($panel['panel_title']) ) ||
							( isset ($panel['panel_text']) && ! empty ($panel['panel_text']) )
						) {
							echo '<div class="css3panel-caption ' . $panel_position . ' ">';

							// Panel title

							if ( isset ($panel['panel_title']) && ! empty ($panel['panel_title']) ) {

								$title_link = zn_extract_link( $panel['title_link'] );

								echo $title_link['start'];

									echo '<h3 class="css3panel-title ff-alternative '.(isset($panel['panel_title_style']) ? $panel['panel_title_style'] : '').' '.(isset($panel['panel_title_size']) ? 'title-size-'.$panel['panel_title_size'] : '' ).'">'.$panel['panel_title'].'</h3>';

								echo $title_link['end'];
							}

							// Panel text
							if ( isset ($panel['panel_text']) && ! empty ($panel['panel_text']) ) {
								echo '<div class="css3panel-text">'.$panel['panel_text'].'</div>';
							}

							echo '<div class="css3panel-btn-area">';

								// Panel Primary Button
								if(isset($panel['title_link']['title']) && !empty($panel['title_link']['title'])){
									$prim_link = zn_extract_link( $panel['title_link'], 'btn btn-fullcolor btn-skewed' );
									echo $prim_link['start'] . $panel['title_link']['title'] . $prim_link['end'];
								}

								// Panel Secondary Button
								if(isset($panel['sec_link']['title']) && !empty($panel['sec_link']['title'])){
									$sec_link = zn_extract_link( $panel['sec_link'], 'btn btn-lined btn-skewed' );
									echo $sec_link['start'] . $panel['sec_link']['title'] . $sec_link['end'];
								}

							echo '</div><!-- /.btn-area -->';

							echo '</div><!-- /.css3panel-caption -->';
						}

						echo '</div>';

						$i ++;
					}
				}
				?>
			</div>
			<!-- end panels -->
			<div class="clearfix"></div>

			<div class="kl-bottommask kl-bottommask--shadow_ud"></div>

		</div><!-- end kl-slideshow -->
	<?php
	}

	/**
	 * This method is used to retrieve the configurable options of the element.
	 * @return array The list of options that compose the element and then passed as the argument for the render() function
	 */
	function options()
	{
		$uid = $this->data['uid'];

		$extra_options = array (
			"name"           => __( "CSS Panels", 'zn_framework' ),
			"description"    => __( "Here you can create your CSS3 Panels.", 'zn_framework' ),
			"id"             => "single_css_panel",
			"std"            => "",
			"type"           => "group",
			"add_text"       => __( "Panel", 'zn_framework' ),
			"remove_text"    => __( "Panel", 'zn_framework' ),
			"group_sortable" => true,
			"element_title" => "panel_title",
			"subelements"    => array (
				'has_tabs'  => true,
				'general' => array(
					'title' => 'General options',
					'options' => array(
						array (
							"name"        => __( "Panel image", 'zn_framework' ),
							"description" => __( "Select an image for this Panel. Recommended size 1000px x 700px", 'zn_framework' ),
							"id"          => "panel_image",
							"std"         => "",
							"type"        => "media"
						),
						array (
							"name"        => __( "Panel title", 'zn_framework' ),
							"description" => __( "Here you can enter a title that will appear on this panel.", 'zn_framework' ),
							"id"          => "panel_title",
							"std"         => "",
							"type"        => "text"
						),
						array (
							"name"        => __( "Title text size", 'zn_framework' ),
							"description" => __( "Select the size of the title.", 'zn_framework' ),
							"id"          => "panel_title_size",
							"std"         => "normal",
							"type"        => "select",
							"options"     => array (
								'normal'  => __( "Normal", 'zn_framework' ),
								'bigger' => __( "Huge", 'zn_framework' )
							)
						),
						array (
							"name"        => __( "Panel Text", 'zn_framework' ),
							"description" => __( "Here you can enter some that will appear on this panel, under the title.", 'zn_framework' ),
							"id"          => "panel_text",
							"std"         => "",
							"type"        => "textarea"
						),
						array (
							"name"        => __( "Primary Button", 'zn_framework' ),
							"description" => __( "Set the url & text of the button. Use title field as text inside the button.", 'zn_framework' ),
							"id"          => "title_link",
							"std"         => "",
							"type"        => "link",
							"options"     => zn_get_link_targets(),
						),
						array (
							"name"        => __( "Secondary Button", 'zn_framework' ),
							"description" => __( "Set the url & text of the button. Use title field as text inside the button.", 'zn_framework' ),
							"id"          => "sec_link",
							"std"         => "",
							"type"        => "link",
							"options"     => zn_get_link_targets(),
						),
					),
				),
				'style' => array(
					'title' => 'Styling options',
					'options' => array(
						array (
							"name"        => __( "Title style", 'zn_framework' ),
							"description" => __( "Select title style", 'zn_framework' ),
							"id"          => "panel_title_style",
							"std"         => "",
							"type"        => "select",
							"options"     => array (
								''                  => __( "No Background", 'zn_framework' ),
								'captiontitle--wbg' => __( "White Background", 'zn_framework' ),
								'captiontitle--dbg' => __( "Dark background", 'zn_framework' )
							)
						),
						array (
							"name"        => __( "Text color theme", 'zn_framework' ),
							"description" => __( "Select the theme of the colors", 'zn_framework' ),
							"id"          => "panel_text_theme",
							"std"         => "light",
							"type"        => "select",
							"options"     => array (
								'light'   => __( "Light", 'zn_framework' ),
								'dark'    => __( " Dark", 'zn_framework' )
							)
						),
						array (
							"name"        => __( "Panel Content Position", 'zn_framework' ),
							"description" => __( "Here you can choose where the panel content will be shown", 'zn_framework' ),
							"id"          => "panel_title_position",
							"std"         => "",
							"type"        => "select",
							"options"     => array (
								''      => __( "Normal (Bottom)", 'zn_framework' ),
								'upper' => __( "Upper (Middle)", 'zn_framework' )
							)
						),
						array(
							'id'          => 'panel__overlay',
							'name'        => 'Slide colored overlay',
							'description' => 'Add slide color overlay over the image or video to darken or enlight?',
							'type'        => 'select',
							'std'         => '0',
							"options"     => array (
								"1" => __( "Yes", 'zn_framework' ),
								"0"  => __( "No", 'zn_framework' )
							),
							"class"       => "zn_input_xs"
						),
						array(
							'id'          => 'panel__overlay_color',
							'name'        => 'Overlay background color',
							'description' => 'Pick a color',
							'type'        => 'colorpicker',
							'std'         => '#0da3be',
							"dependency"  => array( 'element' => 'panel__overlay' , 'value'=> array('1') ),
						),
						array(
							'id'          => 'panel__overlay_opacity',
							'name'        => 'Overlay\'s opacity.',
							'description' => 'Overlay background colors opacity level.',
							'type'        => 'slider',
							'std'         => '50',
							"helpers"     => array (
								"step" => "5",
								"min" => "10",
								"max" => "100"
							),
							"dependency"  => array( 'element' => 'panel__overlay' , 'value'=> array('1') ),
						),
					),
				),
			)
		);

		$options = array (
			'has_tabs'  => true,
			'general' => array(
				'title' => 'General options',
				'options' => array(
					array (
						"name"        => __( "Slider Height", 'zn_framework' ),
						"description" => __( "Please enter a numerical value in pixels for your slider height.", 'zn_framework' ),
						"id"          => "css_height",
						"std"         => "600",
						"type"        => "text",
						"class"       => ''
					),
					array (
						"name"        => __( "Image Effect", 'zn_framework' ),
						"description" => __( "Select an effect for normal and hover states.", 'zn_framework' ),
						"id"          => "panel_effect",
						"std"         => "",
						"type"        => "select",
						"options"     => array (
							''                              => __( "None", 'zn_framework' ),
							'anim--grayscale'               => __( "Grayscale Effect", 'zn_framework' ),
							'anim--blur'                    => __( "Blur effect", 'zn_framework' ),
							'anim--grayscale anim--blur'    => __( "Grayscale & Blur Effect", 'zn_framework' )
						)
					),
					array (
						"name"        => __( "Caption Effect", 'zn_framework' ),
						"description" => __( "Specify the caption effect.", 'zn_framework' ),
						"id"          => "panel_caption_effect",
						"std"         => "default",
						"type"        => "select",
						"options"     => array (
							'default'  => __( "No effect, captions always visible", 'zn_framework' ),
							'fadein' => __( "Hidden captions, fade in on hover", 'zn_framework' ),
							'fadeout' => __( "Visible captions, fade out (hide) on hover", 'zn_framework' ),
							'slidein' => __( "Hidden captions, slide in on hover", 'zn_framework' ),
							'slideout' => __( "Visible captions, slide out on hover", 'zn_framework' )
						)
					),
					array (
						"name"        => __( "Panel Resize on hover", 'zn_framework' ),
						"description" => __( "Resize the panel on hover?", 'zn_framework' ),
						"id"          => "panel_resize",
						"std"         => "1",
						"type"        => "select",
						"options"     => array (
							'1' => __( "Yes", 'zn_framework' ),
							'0' => __( "No", 'zn_framework' )
						)
					),
					array (
						"name"        => __( "Use Flexbox?", 'zn_framework' ),
						"description" => __( "Use Flexbox CSS as the default engine for the panel resizes.", 'zn_framework' ),
						"id"          => "panel_flexbox",
						"std"         => "1",
						"value"         => "1",
						"type"        => "toggle2"
					),
				),
			),
			'panels' => array(
				'title' => 'CSS panels',
				'options' => array(
					$extra_options,
				),
			),


			'help' => znpb_get_helptab( array(
				'video'   => 'http://support.hogash.com/kallyas-videos/#t702hKJbbns',
				'docs'    => 'http://support.hogash.com/documentation/css3-panels/',
				'copy'    => $uid,
				'general' => true,
			)),

		);
		return $options;
	}
}
