<?php if(! defined('ABSPATH')){ return; }
/*
 Name: Text Box
 Description: Create and display a Text Box element
 Class: TH_TextBox
 Category: content
 Level: 3
*/

/**
 * Class TH_TextBox
 *
 * Create and display a Text Box element
 *
 * @package  Kallyas
 * @category Page Builder
 * @author   Team Hogash
 * @since    4.0.0
 */
class TH_TextBox extends ZnElements
{
	public static function getName(){
		return __( "Text Box", 'zn_framework' );
	}

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){

		//print_z($this);
		$uid = $this->data['uid'];
		$css = '';

		$top_padding = $this->opt('top_padding');
		if($top_padding != '0'){
			$tpadding = $top_padding || $top_padding === '0' ? 'padding-top : '.$top_padding.'px;' : '';
			$css .= '.'.$uid.'{'.$tpadding.'}';
		}

		$bottom_padding = $this->opt('bottom_padding');
		if($bottom_padding != '20'){
			$bpadding = $bottom_padding || $bottom_padding === '0' ? 'padding-bottom:'.$bottom_padding.'px;' : '';
			$css .= '.'.$uid.'{'.$bpadding.'}';
		}

		return $css;
	}
	/**
	 * This method is used to display the output of the element.
	 * @return void
	 */
	function element()
	{
		$options = $this->data['options'];
		$stb_title_heading = $this->opt( 'stb_title_heading', 'h3' );

		if( empty( $options ) ) { return; }

		$elm_classes=array();
		$elm_classes[] = $this->data['uid'];
		$elm_classes[] = zn_get_element_classes($options);

		$attributes = zn_get_element_attributes($options);

		$color_scheme = $this->opt( 'element_scheme', '' ) == '' ? zget_option( 'zn_main_style', 'color_options', false, 'light' ) : $this->opt( 'element_scheme', '' );
		$elm_classes[] = 'zn_text_box-'.$color_scheme;
		$elm_classes[] = 'element-scheme--'.$color_scheme;

		echo '<div class="zn_text_box '.implode(' ', $elm_classes).'" '.$attributes.'>';

		$style = !empty( $options['stb_style'] ) ? $options['stb_style'] : '';
		if ( ! empty( $options['stb_title'] )  ) {
			$text_custom = $style == 'style1' ? 'text-custom' : '';
			echo '<'.$stb_title_heading.' class="zn_text_box-title zn_text_box-title--'.$style.' '.$text_custom.'">' . $options['stb_title'] . '</'.$stb_title_heading.'>';
		}

		$stb_content = $this->opt('stb_content','');

		global $wp_embed;
		if ( is_object( $wp_embed ) ) {
			$content = $wp_embed->autoembed( $stb_content );
		}
		$content = wpautop( $content );
		if ( ! empty ( $stb_content ) ) {
				echo $content;
		}
		echo '</div>';
	}

	/**
	 * This method is used to retrieve the configurable options of the element.
	 * @return array The list of options that compose the element and then passed as the argument for the render() function
	 */
	function options()
	{
		$uid = $this->data['uid'];

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => 'Content',
				'options' => array(
					array (
						"name"        => __( "Content", 'zn_framework' ),
						"description" => __( "Please enter the box content.<br> ** If you plan on <strong style='color:black'>pasting a shortcode</strong>, please make sure to add it in <strong style='color:black'><em>Text Mode</em></strong> of the editor.", 'zn_framework' ),
						"id"          => "stb_content",
						"std"         => "",
						"type"        => "visual_editor",
						"class"        => "zn_full",
					),
					array(
						'id'          => 'element_scheme',
						'name'        => 'Element Color Scheme',
						'description' => 'Select the color scheme of this element',
						'type'        => 'select',
						'std'         => '',
						'options'        => array(
							'' => 'Inherit from Kallyas options > Color Options [Requires refresh]',
							'light' => 'Light (default)',
							'dark' => 'Dark'
						),
						'live'        => array(
							'multiple' => array(
								array(
									'type'      => 'class',
									'css_class' => '.'.$uid,
									'val_prepend'  => 'zn_text_box-',
								),
								array(
									'type'      => 'class',
									'css_class' => '.'.$uid,
									'val_prepend'  => 'element-scheme--',
								),
							)
						)
					),
				)
			),
			'title' => array(
				'title' => 'Title settings',
				'options' => array(
					array (
						"name"        => __( "Title", 'zn_framework' ),
						"description" => __( "Please enter the title for this box", 'zn_framework' ),
						"id"          => "stb_title",
						"std"         => "",
						"type"        => "text",
					),
					array (
						"name"        => __( "Title heading", 'zn_framework' ),
						"description" => __( "Select the desired heading type you want to use for the title", 'zn_framework' ),
						"id"          => "stb_title_heading",
						"std"         => "h3",
						"type"        => "select",
						"options"     => array(
							'h1' => 'H1',
							'h2' => 'H2',
							'h3' => 'H3',
							'h4' => 'H4',
							'h5' => 'H5',
							'h6' => 'H6',
						),
					),
					array (
						"name"        => __( "Title style", 'zn_framework' ),
						"description" => __( "Select the desired style for the title of this
											box", 'zn_framework' ),
						"id"          => "stb_style",
						"type"        => "select",
						"std"         => "style1",
						"options"     => array (
							'style1' => __( 'Style 1', 'zn_framework' ),
							'style2' => __( 'Style 2', 'zn_framework' )
						)
					),
				)
			),
			'padding' => array(
				'title' => 'Padding options',
				'options' => array(

					array(
						'id'          => 'top_padding',
						'name'        => 'Top padding',
						'description' => 'Select the top padding ( in pixels ) for this section.',
						'type'        => 'slider',
						'std'         => '0',
						'class'       => 'zn_full',
						'helpers'     => array(
							'min' => '0',
							'max' => '400',
							'step' => '5'
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$this->data['uid'],
							'css_rule'  => 'padding-top',
							'unit'      => 'px'
						)
					),
					array(
						'id'          => 'bottom_padding',
						'name'        => 'Bottom padding',
						'description' => 'Select the bottom padding ( in pixels ) for this section.',
						'type'        => 'slider',
						'std'         => '20',
						'class'       => 'zn_full',
						'helpers'     => array(
							'min' => '0',
							'max' => '400',
							'step' => '5'
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$this->data['uid'],
							'css_rule'  => 'padding-bottom',
							'unit'      => 'px'
						)
					)
				)
			),

			'help' => znpb_get_helptab( array(
				'video'   => 'http://support.hogash.com/kallyas-videos/#_ModlDp5ghI',
				'docs'    => 'http://support.hogash.com/documentation/text-box/',
				'copy'    => $uid,
				'general' => true,
			)),

		);
		return $options;
	}
}
