<?php if(! defined('ABSPATH')){ return; }
/*
	Name: Title Element
	Description: Create and display a title and or subtitle
	Class: TH_TitleElement
	Category: content
	Keywords: heading, headline, subtitle
	Level: 3
*/
/**
 * Class TH_TitleElement
 *
 * Create and display a Title  element
 *
 * @package  Kallyas
 * @category Page Builder
 * @author   Team Hogash
 * @since    4.0.0
 */
class TH_TitleElement extends ZnElements
{
	public static function getName(){
		return __( "Title Element", 'zn_framework' );
	}

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){

		$uid = $this->data['uid'];
		$css = '';

		$top_padding = $this->opt('top_padding');
		$tpadding = $top_padding != '0' ? 'padding-top:'.$top_padding.'px;' : '';

		$bottom_padding = $this->opt('bottom_padding');
		$bpadding = $bottom_padding != '35' ? 'padding-bottom:'.$bottom_padding.'px;' : '';

		if ( !empty($tpadding) || !empty($bpadding) )
		{
			$css .= '.'.$uid.'{';
			$css .= $tpadding;
			$css .= $bpadding;
			$css .= '}';
		}

		// Title Styles
		$title_styles = '';
		$title_typo = $this->opt('title_typo');
		if( is_array($title_typo) && !empty($title_typo) ){
			foreach ($title_typo as $key => $value) {
				if($value != '') {
					$title_styles .= $key.':'. $value.';';
				}
			}
			if(!empty($title_styles)){
				$css .= '.'.$uid.' .tbk__title{'.$title_styles.'}';
			}
		}

		// Title top margin
		$ttl_bottom_margin = $this->opt('title_bmargin', 10);
		$ttl_bmargin = $ttl_bottom_margin != '10' ? 'margin-bottom:'.$ttl_bottom_margin.'px;' : '';
		if(!empty($ttl_bmargin)){
			$css .= '.'.$uid.' .tbk__title{'.$ttl_bmargin.'}';
		}

		// Subtitle styles
		$subtitle_styles = '';
		$subtitle_typo = $this->opt('subtitle_typo');
		if( is_array($subtitle_typo) && !empty($subtitle_typo) ){
			foreach ($subtitle_typo as $key => $value) {
				if($value != '') {
					$subtitle_styles .= $key.':'. $value.';';
				}
			}
			if(!empty($subtitle_styles)){
				$css .= '.'.$uid.' .tbk__subtitle{'.$subtitle_styles.'}';
			}
		}
		// icon size
		$icon_size = $this->opt('icon_size') || $this->opt('icon_size') === '0' ? 'font-size:'.$this->opt('icon_size').'px;' : 'font-size:28px;';
		$css .= ".$uid .tbk__icon { $icon_size }";

		// symbol color
		if($this->opt('te_symbol_color', 'default') == 'theme') {
			$custom_color = $this->opt('te_symbol_custom_color', '#cd2122');
			$symbol = $this->opt('te_symbol');

			if( !empty($symbol) ){
				if($symbol == 'icon') {
					$css .= '.'.$uid.'.tbk--colored .tbk__icon {color:'.$custom_color.';}';

				} elseif($symbol == 'line' || $symbol == 'line_border' || $symbol == 'border') {
					$css .= '.'.$uid.'.tbk--colored .tbk__symbol span {background-color:'.$custom_color.';}';

				} elseif($symbol == 'border2') {
					$css .= '.'.$uid.'.tbk--colored .tbk__border-helper {border-bottom-color:'.$custom_color.';}';
				}
			}
		}


		return $css;
	}
	/**
	 * This method is used to display the output of the element.
	 *
	 * @return void
	 */
	function element()
	{
		$options = $this->data['options'];

		if( empty( $options ) ) { return; }

		$symbol = '';
		$te_symbol = $this->opt('te_symbol');
		$symbol_pos = $this->opt('te_symbol_pos', 'after-title');

		$symbol_color = $this->opt('te_symbol_color', 'default') == 'theme' ? 'tbk--colored' : '';

		if( !empty($te_symbol) ) {
			$symbol .= '<span class="tbk__symbol ">';

			$iconHolder = $this->opt('te_symbol_icon');
			if( $te_symbol == 'icon' && !empty( $iconHolder['family'] ) ){
				$symbol .=  '<span class="tbk__icon" '.zn_generate_icon( $this->opt('te_symbol_icon') ).'></span>';
			} else {
				$symbol .=  '<span></span>';
			}


			$symbol .= '</span>';
		}

		$classes = array();
		$classes[] = 'tbk--text-'.( $this->opt('te_color_theme','') == 'default' ? '' : $this->opt('te_color_theme','') );
		$classes[] = 'tbk--'.$this->opt('te_alignment', 'center');
		$classes[] = 'text-'.$this->opt('te_alignment', 'center');
		$classes[] = 'tbk-symbol--'.$te_symbol;
		$classes[] = $symbol_color;
		$classes[] = 'tbk-icon-pos--'.$symbol_pos;
		$classes[] = $this->data['uid'];
		$classes[] = zn_get_element_classes($options);

		$attributes = zn_get_element_attributes($options);

		echo '<div class="kl-title-block clearfix '.implode(' ', $classes).'" '.$attributes.'>';

			if( $symbol_pos == 'before-title'  )  echo $symbol;

			if($te_title = $this->opt('te_title')) {

				$brd2_start = '';
				$brd2_end = '';
				if(!empty($te_symbol) && $te_symbol == 'border2'){
					$brd2_start = '<span class="tbk__border-helper">';
					$brd2_end = '</span>';
				}

				$title_heading = $this->opt('te_tt_heading','3');
				echo '<h'.$title_heading.' class="tbk__title ">';

					if( $symbol_pos == 'left-title' && $te_symbol == 'icon' )  echo $symbol;

					echo $brd2_start;
						echo do_shortcode($te_title);
					echo $brd2_end;

				echo '</h'.$title_heading.'>';
			}

			// In case there's no icon and symbol placement is left, place the
			// symbol (whatever it is) after the title
			if( $symbol_pos == 'after-title' || ( $symbol_pos == 'left-title' && $te_symbol != 'icon' ) )  {
				echo $symbol;
			}

			if($te_subtitle = $this->opt('te_subtitle')) {
				$subtitle_tag = $this->opt('te_stt_tag','h4');
				echo '<'.$subtitle_tag.' class="tbk__subtitle">'.do_shortcode($te_subtitle).'</'.$subtitle_tag.'>';
			}

			if( $symbol_pos == 'after-subtitle' )  echo $symbol;

			if($te_text = $this->opt('te_text')) {
				echo '<div class="tbk__text">';
				$content = wpautop( $te_text );
				if ( ! empty ( $te_text ) ) {
					if ( preg_match( '%(<[^>]*>.*?</)%i', $content, $regs ) ) {
						echo do_shortcode( $content );
					}
					else {
						echo '<p>' . do_shortcode( $content ) . '</p>';
					}
				}
				echo '</div>';
			}

			if( $symbol_pos == 'after-text' )  echo $symbol;

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
				'title' => 'General options',
				'options' => array(

					array (
						"name"        => __( "Title", 'zn_framework' ),
						"description" => __( "Add the title. Shorcodes and HTML code allowed", 'zn_framework' ),
						"id"          => "te_title",
						"std"         => "",
						"type"        => "textarea",
					),

					array (
						"name"        => __( "Sub Title", 'zn_framework' ),
						"description" => __( "Add a sub-title. Shorcodes and HTML code allowed", 'zn_framework' ),
						"id"          => "te_subtitle",
						"std"         => "",
						"type"        => "textarea",
					),

					array (
						"name"        => __( "Alignment", 'zn_framework' ),
						"description" => __( "Select the alignment", 'zn_framework' ),
						"id"          => "te_alignment",
						"std"         => "center",
						"type"        => "select",
						"options"     => array(
							"center" => "Center",
							"left" => "Left",
							"right" => "Right"
						),
						'live'        => array(
							'multiple' => array(
								array(
									'type'      => 'class',
									'css_class' => '.'.$uid,
									'val_prepend'  => 'tbk--',
								),
								array(
									'type'      => 'class',
									'css_class' => '.'.$uid,
									'val_prepend'  => 'text-',
								),
							)
						)
					),

					array(
						'id'          => 'te_color_theme',
						'name'        => 'Element Color Scheme',
						'description' => 'Select a theme text color. In case you have a dark background you most definitely want a light colored text',
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
									'val_prepend'  => 'tbk--text-',
								),
								array(
									'type'      => 'class',
									'css_class' => '.'.$uid,
									'val_prepend'  => 'element-scheme--',
								),
							)
						)
					),

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
							'step' => '1'
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid,
							'css_rule'  => 'padding-top',
							'unit'      => 'px'
						)
					),
					array(
						'id'          => 'bottom_padding',
						'name'        => 'Bottom padding',
						'description' => 'Select the bottom padding ( in pixels ) for this section.',
						'type'        => 'slider',
						'std'         => '35',
						'class'       => 'zn_full',
						'helpers'     => array(
							'min' => '0',
							'max' => '400',
							'step' => '1'
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid,
							'css_rule'  => 'padding-bottom',
							'unit'      => 'px'
						)
					),
				)
			),
			'symbol' => array(
				'title' => 'Symbol options',
				'options' => array(
					array (
						"name"        => __( "Add a symbol?", 'zn_framework' ),
						"description" => __( "Add any symbol?.", 'zn_framework' ),
						"id"          => "te_symbol",
						"std"         => "",
						"type"        => "select",
						"options"     => array(
								"" => "None",
								"line" => "Small line (50px x 3px)",
								"border" => "Long bottom border",
								"border2" => "Long Thicker bottom border (with thicker border for title)",
								"line_border" => "Small line + Bottom Border",
								"icon" => "Icon",
							)
					),
					array (
						"name"        => __( "Symbol Color", 'zn_framework' ),
						"description" => __( "Select symbol color.", 'zn_framework' ),
						"id"          => "te_symbol_color",
						"std"         => "default",
						"type"        => "select",
						"options"     => array(
								"default" => "Default color",
								"theme" => "Custom color"
						),
						// "dependency"  => array( 'element' => 'te_symbol' , 'value'=> array('line', 'line_border', 'icon') )
					),


					array (
						"name"        => __( "Symbol Custom Color", 'zn_framework' ),
						"description" => __( "Select symbol color.", 'zn_framework' ),
						"id"          => "te_symbol_custom_color",
						"std"         => "#cd2122",
						"type"        => "colorpicker",
						"dependency"  => array( 'element' => 'te_symbol_color' , 'value'=> array('theme') )
					),

					array (
						"name"        => __( "Icon", 'zn_framework' ),
						"description" => __( "Add icon.", 'zn_framework' ),
						"id"          => "te_symbol_icon",
						"std"         => "",
						"type"        => "icon_list",
						'class'       => 'zn_full',
						"dependency"  => array( 'element' => 'te_symbol' , 'value'=> array('icon') )
					),

					array(
						'id'          => 'icon_size',
						'name'        => 'Icon Size',
						'description' => 'Select the icon size in px.',
						'type'        => 'slider',
						'std'         => '28',
						'class'       => 'zn_full',
						'helpers'     => array(
							'min' => '14',
							'max' => '80',
							'step' => '2'
						),
						"dependency"  => array( 'element' => 'te_symbol' , 'value'=> array('icon') ),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid.' .tbk__icon',
							'css_rule'  => 'font-size',
							'unit'      => 'px'
						)
					),

					array (
						"name"        => __( "Symbol position", 'zn_framework' ),
						"description" => __( "Select the symbol's position.", 'zn_framework' ),
						"id"          => "te_symbol_pos",
						"std"         => "after-title",
						"type"        => "select",
						"options"     => array(
								"before-title" => "Before title",
								"after-title" => "After title",
								"after-subtitle" => "After sub-title",
								"after-text" => "After Text",
								"left-title" => "Inline, in title's left side (Icon only!)",
						),
						"dependency"  => array( 'element' => 'te_symbol' , 'value'=> array('line', 'border', 'line_border', 'icon') ),
					),

				)
			),

			'text' => array(
				'title' => 'Description text',
				'options' => array(
					array (
						"name"        => __( "Some text maybe?", 'zn_framework' ),
						"description" => __( "Add a text paragraph.", 'zn_framework' ),
						"id"          => "te_text",
						"std"         => "",
						"type"        => "visual_editor",
					),
				)
			),

			'font' => array(
				'title' => 'Font settings',
				'options' => array(

					array (
						"name"        => __( "Title Heading", 'zn_framework' ),
						"description" => __( "Select a title heading. The title will be wrapped in this tag", 'zn_framework' ),
						"id"          => "te_tt_heading",
						"std"         => "3",
						"type"        => "select",
						"options"     => array(
								"1" => "H1",
								"2" => "H2",
								"3" => "H3",
								"4" => "H4",
								"5" => "H5",
								"6" => "H6"
							)
					),

					array (
						"name"        => __( "Title settings", 'zn_framework' ),
						"description" => __( "Specify the typography properties for the title.", 'zn_framework' ),
						"id"          => "title_typo",
						"std"         => '',
						'supports'   => array( 'size', 'font', 'style', 'line', 'color', 'weight', 'spacing' ),
						"type"        => "font",
					),

					array(
						'id'          => 'title_bmargin',
						'name'        => 'Title Bottom Margin',
						'description' => 'Select the bottom margin ( in pixels ) for the title.',
						'type'        => 'slider',
						'std'         => '10',
						'class'       => 'zn_full',
						'helpers'     => array(
							'min' => '0',
							'max' => '100',
							'step' => '1'
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid .' .tbk__title',
							'css_rule'  => 'margin-bottom',
							'unit'      => 'px'
						)
					),

					array (
						"name"        => __( "Sub-Title Tag", 'zn_framework' ),
						"description" => __( "Select the subtitle's tag. The title will be wrapped in this tag", 'zn_framework' ),
						"id"          => "te_stt_tag",
						"std"         => "h4",
						"type"        => "select",
						"options"     => array(
								"h1" => "H1",
								"h2" => "H2",
								"h3" => "H3",
								"h4" => "H4",
								"h5" => "H5",
								"h6" => "H6",
								"div" => "div",
								"p" => "Paragraph",
								"pre" => "Preformatted",
							)
					),

					array (
						"name"        => __( "Sub-Title settings", 'zn_framework' ),
						"description" => __( "Specify the typography properties for the sub-title.", 'zn_framework' ),
						"id"          => "subtitle_typo",
						"std"         => '',
						'supports'   => array( 'size', 'font', 'style', 'line', 'color', 'weight', 'spacing' ),
						"type"        => "font",
					),

				)
			),

			'help' => znpb_get_helptab( array(
				'video'   => 'http://support.hogash.com/kallyas-videos/#aBpgvHl6g6I',
				'docs'    => 'http://support.hogash.com/documentation/title-element/',
				'copy'    => $uid,
				'general' => true,
			)),

		);
		return $options;
	}
}