<?php if(! defined('ABSPATH')){ return; }
/*
 Name: Icon Box
 Description: Create and display an Icon Box element containing an icon, title description with different settings
 Class: TH_IconBox
 Category: content
 Level: 3
*/
/**
 * Class TH_IconBox
 *
 * Create and display an Icon Box element containing an icon, title description with different settings
 *
 * @package  Kallyas
 * @category Page Builder
 * @author   Team Hogash
 * @since    4.0.0
 */
class TH_IconBox extends ZnElements
{
	public static function getName(){
		return __( "Icon Box", 'zn_framework' );
	}

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){
		$css = '';
		$uid = $this->data['uid'];
		$icon_type = $this->opt('ibx_type', 'icon');

		// Title Styles
		$title_styles = '';
		$title_topmargin = $this->opt('ibx_floated_topmarg','0');
		if($title_topmargin != '0'){
			$title_styles .= 'margin-top:'. $title_topmargin.'px;';
		}
		$title_typo = $this->opt('title_typo');
		if( is_array($title_typo) && !empty($title_typo) ){
			foreach ($title_typo as $key => $value) {
				if(!empty($value)){
					$title_styles .= $key .':'. $value.';';
				}
			}
		}
		if(!empty($title_styles)){
			$css .= '.'.$uid.' .kl-iconbox__title {'.$title_styles.'} ';
		}


		// Description styles
		$desc_styles = '';
		$desc_typo = $this->opt('desc_typo');
		if( is_array($desc_typo) && !empty($desc_typo) ){
			foreach ($desc_typo as $key => $value) {
				if(!empty($value)){
					$desc_styles .= $key .':'. $value.';';
				}
			}
			$css .= '.'.$uid.' .kl-iconbox__desc {'.$desc_styles.'} ';
		}

		// Icon color default and on hover
		$ibx_shape = $this->opt('ibx_shape', '');
		if( $this->opt('ibx_type', 'icon') == 'icon' ){

			$ibx_icon_color = $this->opt('ibx_icon_color', '#343434' );
			$ibx_icon_color_hover = $this->opt('ibx_icon_color_hover', '#cd2122' );

			$cc_stroke_box_sh = '';
			$cc_stroke_box_sh_hov = '';
			if( $ibx_shape == 'sh-circle-stroke' ){
				$cc_stroke_box_sh = 'box-shadow: 0 0 0 2px '.$ibx_icon_color.';';
				$cc_stroke_box_sh_hov = 'box-shadow: 0 0 0 4px '.$ibx_icon_color_hover.';';
			}

			$css .= '.'.$uid.' .kl-iconbox__icon {color:'.$ibx_icon_color.'; '.$cc_stroke_box_sh.'} ';
			$css .= '.'.$uid.':hover .kl-iconbox__icon {color:'.$ibx_icon_color_hover.'; '.$cc_stroke_box_sh_hov.'} ';

			// If has a shape behind
			if( $ibx_shape != '' && $ibx_shape != 'sh-circle-stroke' ){
				$css .= '.'.$uid.'.kl-iconbox--sh span.kl-iconbox__icon {background-color:'.$this->opt('ibx_shape_color', '#dfdfdf' ).'} ';
				$css .= '.'.$uid.'.kl-iconbox--sh span.kl-iconbox__icon:after {background-color:'.$this->opt('ibx_shape_color_hover', '#cd2122' ).'} ';
			}
		}

		// Icon sizes
		$icon_size = $this->opt('ibx_size','42');
		if( $icon_size != '42' && $icon_type == 'icon'){
			$css .= ".{$uid} span.kl-iconbox__icon { font-size: {$icon_size}px }";
		}

		// Image size
		$img_size = $this->opt('ibx_imgwidth','100');
		if( $img_size != '100' && $icon_type == 'img'){
			$css .= ".{$uid} img.kl-iconbox__icon { max-width: {$img_size}px }";
		}

		// Icon Shaped Padding
		$ibx_shaped_padding = $this->opt('ibx_shaped_padding','22');
		if( $ibx_shaped_padding != '22' && $ibx_shape != '' ){
			$css .= ".{$uid} span.kl-iconbox__icon { padding: {$ibx_shaped_padding}px }";
		}

		// Icon Opacity
		$ibx_opacity = $this->opt('ibx_opacity','100');
		if( $ibx_opacity != '100' && $ibx_opacity != '' ){
			$css .= '.'.$uid.' .kl-iconbox__icon {opacity: '.($ibx_opacity/100).'; }';
		}


		// Add delay transitions
		// if( $this->opt('ibx_appear', '') == '1' ){
		// 	$css .= '.'.$uid.'.el--appear { -webkit-transition-delay:'.$this->opt('ibx_appear_delay', '0').'ms !important; transition-delay:'.$this->opt('ibx_appear_delay', '0').'ms !important; } ';

		// }

		return $css;
	}

	/**
	 * This method is used to display the output of the element.
	 *
	 * @return void
	 */
	function element()
	{
		$uid = $this->data['uid'];
		$options = $this->data['options'];

		$link_start = '';
		$link_end = '';

		// $appear = $this->opt('ibx_appear','');

		$ibx_floated = $this->opt('ibx_floated','');

		$link_type = $this->opt('ibx_link_type','0');
		$link_style = $link_type == 'cta' ? 'btn btn-fullcolor' : 'kl-iconbox__link';
		$ibx_link = $this->opt('ibx_link');
		$link_extracted = $ibx_link ? zn_extract_link( $ibx_link, $link_style ) : '';

		// Check if link is wrapped on Icon or Both Icon & Title
		if( $link_type == 'icon' || $link_type == 'icontitle' ){
			if (!empty($link_extracted)) {
				$link_start = $link_extracted['start'];
				$link_end = $link_extracted['end'];
			}
		}
		// Check if link is wrapped on Title or Both Icon & Title
		elseif( $link_type == 'title' || $link_type == 'icontitle' ){
			if (!empty($link_extracted)) {
				$link_start = $link_extracted['start'];
				$link_end = $link_extracted['end'];
			}
		}
		//  Check if link is displayed separately as a Call to action button
		elseif( $link_type == 'cta' ){
			$link_text = '';
			if ( is_array( $ibx_link ) || !empty( $ibx_link['title'] ) ) {
				$link_text = $ibx_link['title'];
			}
			$link_start = $link_extracted['start'] . $link_text . $link_extracted['end'];
		}

		// Title
		$titlefirst = $this->opt('ibx_titleorder', '1') == 1;
		$titlehtml = '';
		if( $title = $this->opt('ibx_title') ){
			// Check if title has link
			if( $link_type == 'title' || $link_type == 'icontitle' ){
				$title = $link_start.$title.$link_end;
			}
			$titlehtml = '
			<div class="kl-iconbox__el-wrapper kl-iconbox__title-wrapper">
				<h3 class="kl-iconbox__title element-scheme__hdg1">'.$title.'</h3>
			</div>';
		}

		// Stage points
		$points = '';
		$ibstg_point_stage = $this->opt('ibstg_point_stage');
		if( !empty($ibstg_point_stage) ){
			$points .= ' data-stageid="'.str_replace(' ', '',$ibstg_point_stage).'"';
			// point coordinates of the stage
			$pointv = $this->opt('ibstg_point','');
			$x = '';
			$y = '';
			if(!empty($pointv)){
				$pointv = explode(',', $pointv);
				if(is_array($pointv)){
					$x = $pointv['0'];
					$y = $pointv['1'];
				}
			}
			$points .= ' data-pointx="'.$x.'"';
			$points .= ' data-pointy="'.$y.'"';
			//  add title tooltip
			if( $this->opt('ibstg_point_title','') ){
				$points .= ' data-pointtitle="'.$this->opt('ibstg_point_title').'"';
			}
		}

		// Icon
		$icon_type = $this->opt('ibx_type', 'icon');

		// States and modificators
		$mods = array();
		$mods[] = $this->data['uid'];
		$mods[] = zn_get_element_classes($options);
		$attributes = zn_get_element_attributes($options);

		$mods[] = ' kl-iconbox--type-'.$icon_type;
		$mods[] = $this->opt('ibx_shape','') ? 'kl-iconbox--sh kl-iconbox--'.$this->opt('ibx_shape','') : '';
		$mods[] = $this->opt('ibx_floated','') ? 'kl-iconbox--'.$this->opt('ibx_floated','') : '';
		$mods[] = 'kl-iconbox--align-'.$this->opt('ibx_alignment','left').' text-'.$this->opt('ibx_alignment','left');
		// $mods[] = $appear == 1 ? 'el--appear el--appear-fadein' : '';

		$color_scheme = $this->opt( 'ibx_color_theme', '' ) == '' || $this->opt( 'ibx_color_theme', '' ) == 'default' ? zget_option( 'zn_main_style', 'color_options', false, 'light' ) : $this->opt( 'ibx_color_theme', '' );
		$mods[] = 'kl-iconbox--theme-'.$color_scheme;
		$mods[] = 'element-scheme--'.$color_scheme;


?>
<div class="kl-iconbox <?php echo implode(' ', $mods); ?>" <?php echo $points; ?> <?php echo $attributes; ?>>
	<div class="kl-iconbox__inner">

		<?php
		// Display title
		if($titlefirst && $ibx_floated == ''){
			echo $titlehtml;
		}
		?>

		<div class="kl-iconbox__icon-wrapper ">
			<?php
			if( $link_type == 'icon' || $link_type == 'icontitle' ){
				echo $link_start;
			}
			// Icon Font
			if( $icon_type == 'icon'){
				$theicon = $this->opt('ibx_icon');
				if( is_array($theicon) && !empty($theicon['unicode']) ){
					echo '<span class="kl-iconbox__icon" '. zn_generate_icon( $this->opt('ibx_icon') ) .'></span>';
				}
			}
			// Icon Image
			elseif ($icon_type == 'img'){
				if($icon_img = $this->opt('ibx_image')){
					echo '<img class="kl-iconbox__icon" src="' . $icon_img . '" alt="'. ZngetImageAltFromUrl( $icon_img ) .'" title="'.ZngetImageTitleFromUrl( $icon_img ).'">';
				}
			}
			if( $link_type == 'icon' || $link_type == 'icontitle' ){
				echo $link_end;
			}
			?>
		</div><!-- /.kl-iconbox__icon-wrapper -->

		<div class="kl-iconbox__content-wrapper">

			<?php
			// Display title after icon
			if( !$titlefirst ){
				echo $titlehtml;
			}

			// If floated style is selected, force title to display here
			else if( $titlefirst && $ibx_floated != '' ) {
				echo $titlehtml;
			}
			?>

			<?php if( $desc = $this->opt('ibx_desc') ): ?>
			<div class=" kl-iconbox__el-wrapper kl-iconbox__desc-wrapper">
				<p class="kl-iconbox__desc"><?php echo $desc; ?></p>
			</div>
			<?php endif; ?>

			<?php if( $link_type == 'cta' ): ?>
			<div class="kl-iconbox__el-wrapper kl-iconbox__cta-wrapper">
				<?php echo $link_start; ?>
			</div>
			<?php endif; ?>

		</div><!-- /.kl-iconbox__content-wrapper -->

	</div>
</div>

<?php
// print_r($this);
	}

	/**
	 * This method is used to retrieve the configurable options of the element.
	 * @return array The list of options that compose the element and then passed as the argument for the render() function
	 */
	function options()
	{
		$uid = $this->data['uid'];

		return array (
			'has_tabs'  => true,
			'general' => array(
				'title' => 'General options',
				'options' => array(
					array (
						"name"        => __( "Title", 'zn_framework' ),
						"description" => __( "Title text.", 'zn_framework' ),
						"id"          => "ibx_title",
						"std"         => "",
						"type"        => "text"
					),

					array (
						"name"        => __( "Description", 'zn_framework' ),
						"description" => __( "Description text.", 'zn_framework' ),
						"id"          => "ibx_desc",
						"std"         => "",
						"type"        => "textarea"
					),

					array (
						"name"        => __( "Link Type", 'zn_framework' ),
						"description" => __( "Link type of the icon box.", 'zn_framework' ),
						"id"          => "ibx_link_type",
						"std"         => "0",
						"type"        => "select",
						"options"     => array (
							'0' => __( 'No Link', 'zn_framework' ),
							'icon' => __( 'Link wrapping the Icon', 'zn_framework' ),
							'title' => __( 'Link wrapping the Title', 'zn_framework' ),
							'cta' => __( 'Call to action link', 'zn_framework' ),
							'icontitle' => __( 'Link wrapping both Icon and Title', 'zn_framework' ),
						),
					),

					array (
						"name"        => __( "The link", 'zn_framework' ),
						"description" => __( "Add a link here. For call to action button, title is used as anchor text.", 'zn_framework' ),
						"id"          => "ibx_link",
						"std"         => "",
						"type"        => "link",
						"options"     => zn_get_link_targets(),
						"dependency"  => array( 'element' => 'ibx_link_type' , 'value'=> array('icon','title','cta','icontitle') )
					),

					array (
						"name"        => __( "Icon Type", 'zn_framework' ),
						"description" => __( "Type of the icon.", 'zn_framework' ),
						"id"          => "ibx_type",
						"std"         => "icon",
						"type"        => "select",
						"options"     => array (
							'icon' => __( 'Font Icon', 'zn_framework' ),
							'img' => __( 'Image (PNG, JPG, SVG or even GIF)', 'zn_framework' )
						),
					),

					array (
						"name"        => __( "Image Icon", 'zn_framework' ),
						"description" => __( "Upload an Icon Image.", 'zn_framework' ),
						"id"          => "ibx_image",
						"std"         => "",
						"type"        => "media",
						"dependency"  => array( 'element' => 'ibx_type' , 'value'=> array('img') ),
					),

					array (
						"name"        => __( "Select Icon", 'zn_framework' ),
						"description" => __( "Select an icon to display.", 'zn_framework' ),
						"id"          => "ibx_icon",
						"std"         => "",
						"type"        => "icon_list",
						'class'       => 'zn_full',
						"dependency"  => array( 'element' => 'ibx_type' , 'value'=> array('icon') ),
					),




				),
			),
			'styling' => array(
				'title' => 'Style options',
				'options' => array(
				   array (
						"name"        => __( "Shaped Background Icon?", 'zn_framework' ),
						"description" => __( "Display the icon in a shape with hover effects? Available only for icon fonts to control the hover color.", 'zn_framework' ),
						"id"          => "ibx_shape",
						"std"         => "",
						"type"        => "select",
						"options"     => array (
							'' => __( 'No', 'zn_framework' ),
							'sh-circle' => __( 'Yes - Circle with hover', 'zn_framework' ),
							'sh-circle-stroke' => __( 'Yes - Circle Stroke with hover', 'zn_framework' ),
							'sh-square' => __( 'Yes - Square with hover', 'zn_framework' )
						),
						"dependency"  => array( 'element' => 'ibx_type' , 'value'=> array('icon') ),
						'live' => array(
						   'type'        => 'class',
						   'css_class' => '.'.$this->data['uid'],
						   'val_prepend'   => 'kl-iconbox--sh kl-iconbox--',
						)
					),

					array (
						"name"        => __( "Icon Color", 'zn_framework' ),
						"description" => __( "Color of the icon.", 'zn_framework' ),
						"id"          => "ibx_icon_color",
						"std"         => "#343434",
						"type"        => "colorpicker",
						"dependency"  => array( 'element' => 'ibx_type' , 'value'=> array('icon') ),
						'live' => array(
						   'type'        => 'css',
						   'css_class' => '.'.$this->data['uid'].'.kl-iconbox--sh .kl-iconbox__icon',
						   'css_rule'    => 'color',
						   'unit'        => ''
						),
					),

					array (
						"name"        => __( "Shape Background Color", 'zn_framework' ),
						"description" => __( "Background Color of the shape behind the icon.", 'zn_framework' ),
						"id"          => "ibx_shape_color",
						"std"         => "#dfdfdf",
						"type"        => "colorpicker",
						"dependency"  => array(
							array( 'element' => 'ibx_type' , 'value'=> array('icon') ),
							array( 'element' => 'ibx_shape' , 'value'=> array('sh-circle', 'sh-square') ),
						),
						'live' => array(
						   'type'        => 'css',
						   'css_class' => '.'.$this->data['uid'].'.kl-iconbox--sh span.kl-iconbox__icon',
						   'css_rule'    => 'background-color',
						   'unit'        => ''
						),
					),

					array (
						"name"        => __( "Icon Hover Color", 'zn_framework' ),
						"description" => __( "Hover Color of the icon.", 'zn_framework' ),
						"id"          => "ibx_icon_color_hover",
						"std"         => "#cd2122",
						"type"        => "colorpicker",
						"dependency"  => array( 'element' => 'ibx_type' , 'value'=> array('icon') ),
					),

					array (
						"name"        => __( "Shape Background Hover Color", 'zn_framework' ),
						"description" => __( "Hover background color of the shape behind the icon.", 'zn_framework' ),
						"id"          => "ibx_shape_color_hover",
						"std"         => "#cd2122",
						"type"        => "colorpicker",
						"dependency"  => array(
							array( 'element' => 'ibx_type' , 'value'=> array('icon') ),
							array( 'element' => 'ibx_shape' , 'value'=> array('sh-circle', 'sh-square') ),
						),
					),

					array (
						"name"        => __( "Icon Size", 'zn_framework' ),
						"description" => __( "Select the size of the icon.", 'zn_framework' ),
						"id"          => "ibx_size",
						"std"         => "42",
						'type'        => 'slider',
						'class'       => 'zn_full',
						'helpers'     => array(
							'min' => '10',
							'max' => '400',
							'step' => '1'
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$this->data['uid'] .' span.kl-iconbox__icon',
							'css_rule'  => 'font-size',
							'unit'      => 'px'
						),
						"dependency"  => array( 'element' => 'ibx_type' , 'value'=> array('icon') ),
					),

					array (
						"name"        => __( "Image Size", 'zn_framework' ),
						"description" => __( "Select the size of the image.", 'zn_framework' ),
						"id"          => "ibx_imgwidth",
						"std"         => "100",
						'type'        => 'slider',
						'class'       => 'zn_full',
						'helpers'     => array(
							'min' => '10',
							'max' => '400',
							'step' => '1'
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$this->data['uid'] .' img.kl-iconbox__icon',
							'css_rule'  => 'max-width',
							'unit'      => 'px'
						),
						"dependency"  => array( 'element' => 'ibx_type' , 'value'=> array('img') ),
					),

					array (
						"name"        => __( "Icon Padding (Shaped)", 'zn_framework' ),
						"description" => __( "Select the size of the icon.", 'zn_framework' ),
						"id"          => "ibx_shaped_padding",
						"std"         => "22",
						'type'        => 'slider',
						'class'       => 'zn_full',
						'helpers'     => array(
							'min' => '2',
							'max' => '400',
							'step' => '1'
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$this->data['uid'] .' span.kl-iconbox__icon',
							'css_rule'  => 'padding',
							'unit'      => 'px'
						),
						"dependency"  => array( 'element' => 'ibx_shape' , 'value'=> array('sh-circle', 'sh-square', 'sh-circle-stroke') ),
					),

					array (
						"name"        => __( "Box Alignment", 'zn_framework' ),
						"description" => __( "Alignment for the content inside the box.", 'zn_framework' ),
						"id"          => "ibx_alignment",
						"std"         => "left",
						"type"        => "select",
						"options"     => array (
							'left' => __( 'Align LEFT', 'zn_framework' ),
							'center' => __( 'Align CENTER', 'zn_framework' ),
							'right' => __( 'Align RIGHT', 'zn_framework' ),
						),
						'live' => array(
							'multiple' => array(
								array(
									'type'        => 'class',
								   'css_class' => '.'.$this->data['uid'],
								   'val_prepend'   => 'kl-iconbox--align-',
								),
								array(
									'type'        => 'class',
								   'css_class' => '.'.$this->data['uid'],
								   'val_prepend'   => 'text-',
								),
							)

						)
					),

					array(
						'id'          => 'ibx_color_theme',
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
									'val_prepend'  => 'kl-iconbox--theme-',
								),
								array(
									'type'      => 'class',
									'css_class' => '.'.$uid,
									'val_prepend'  => 'element-scheme--',
								),
							)
						)
					),

					array (
						"name"        => __( "Floated Style?", 'zn_framework' ),
						"description" => __( "Is the box left or right floated? Don't confuse with alignment.", 'zn_framework' ),
						"id"          => "ibx_floated",
						"std"         => "",
						"type"        => "select",
						"options"     => array (
							'' => __( 'No', 'zn_framework' ),
							'fleft' => __( 'Yes - Left floated', 'zn_framework' ),
							'fright' => __( 'Yes - Right floated', 'zn_framework' )
						),
					),

					array (
						"name"        => __( "Title Top Margin", 'zn_framework' ),
						"description" => __( "Select the top margin of the title.", 'zn_framework' ),
						"id"          => "ibx_floated_topmarg",
						"std"         => "0",
						'type'        => 'slider',
						'class'       => 'zn_full',
						"helpers"     => array (
							"step" => "1",
							"min" => "0",
							"max" => "100"
						),
						'live' => array(
							'type'        => 'css',
							'css_class' => '.'.$this->data['uid'].' .kl-iconbox__title',
							'css_rule'  => 'margin-top',
							'unit'      => 'px'
						),
						"dependency"  => array( 'element' => 'ibx_floated' , 'value'=> array('fleft','fright') )
					),

					array (
						"name"        => __( "Title settings", 'zn_framework' ),
						"description" => __( "Specify the typography properties for the title.", 'zn_framework' ),
						"id"          => "title_typo",
						"std"         => array (
							'font-size'   => '20px',
							'font-family'   => 'Open Sans',
							'line-height' => '30px',
							'font-style' => 'normal',
							'font-weight' => '400',
						),
						'supports'   => array( 'size', 'font', 'style', 'line', 'color', 'weight' ),
						"type"        => "font",
					),

					array (
						"name"        => __( "Title first?", 'zn_framework' ),
						"description" => __( "Display the title first?", 'zn_framework' ),
						"id"          => "ibx_titleorder",
						"std"         => "",
						"value"       => "1",
						"type"        => "toggle2",
					),

					array (
						"name"        => __( "Description text settings", 'zn_framework' ),
						"description" => __( "Specify the typography properties for the description text.", 'zn_framework' ),
						"id"          => "desc_typo",
						"std"         => array (
							'font-size'   => '13px',
							'font-family'   => 'Open Sans',
							'line-height' => '24px',
							'font-style' => 'normal',
							'font-weight' => '400',
						),
						'supports'   => array( 'size', 'font', 'style', 'line', 'weight', 'color' ),
						"type"        => "font"
					),

					array (
						"name"        => __( "Icon Opacity", 'zn_framework' ),
						"description" => __( "Select the opacity of the icon.", 'zn_framework' ),
						"id"          => "ibx_opacity",
						"std"         => "100",
						'type'        => 'slider',
						'class'       => 'zn_full',
						"helpers"     => array (
							"step" => "5",
							"min" => "0",
							"max" => "100"
						)
					),

				),
			),
			'stage_options' => array(
				'title' => 'Hover stage options',
				'options' => array(
					array (
						"name"        => __( "------- Hover Stage Points" , 'zn_framework' ),
						"description" => __( "Use the feature to display a target point onto a Stage object element. First create the Stage element and customise it, then, copy the ID below.", 'zn_framework' ),
						"id"          => "ibstg_docs",
						"std"         => "",
						"type"        => "zn_title",
						"class"       => "zn_full"
					),

					array (
						"name"        => __( "Point Target Stage ID", 'zn_framework' ),
						"description" => __( "Copy the ID from the Stage element you want to add points to.", 'zn_framework' ),
						"id"          => "ibstg_point_stage",
						"std"         => "",
						"type"        => "text",
						"placeholder" => "ex: eluidbacf913d",
					),

					array (
						"name"        => __( "Point Coordinates", 'zn_framework' ),
						"description" => __( "This will add an animated dot onto the stage image with the X and Y coordinates you provide. In px add \"x, y\" coordinates - X being distance from left and Y distance from top.", 'zn_framework' ),
						"id"          => "ibstg_point",
						"std"         => "",
						"type"        => "text",
						"placeholder" => "ex: 100, 125",
					),

					array (
						"name"        => __( "Point Tootip", 'zn_framework' ),
						"description" => __( "Add a custom tooltip text. Leave empty if you don't want to display a tooltip.", 'zn_framework' ),
						"id"          => "ibstg_point_title",
						"std"         => "",
						"type"        => "text",
					),
				),
			),
			// 'appear_options' => array(
			// 	'title' => 'Appear options',
			// 	'options' => array(
			// 		array (
			// 			"name"        => __( "Appear on scroll?", 'zn_framework' ),
			// 			"description" => __( "Start invisible and appear on scroll, when in viewport?", 'zn_framework' ),
			// 			"id"          => "ibx_appear",
			// 			"std"         => "",
			// 			"value"         => "1",
			// 			"type"        => "toggle2",
			// 		),

			// 		array (
			// 			"name"        => __( "Delay appearance (milliseconds)", 'zn_framework' ),
			// 			"description" => __( "Delay the appearance? If multiple icon boxes, you can delay each one to appear sequentially. The numbers are in milliseconds.", 'zn_framework' ),
			// 			"id"          => "ibx_appear_delay",
			// 			"std"         => "0",
			// 			"type"        => "slider",
			// 			"helpers"     => array (
			// 				"step" => "50",
			// 				"min" => "0",
			// 				"max" => "2500"
			// 			),
			// 			"dependency"  => array( 'element' => 'ibx_appear' , 'value'=> array('1') )
			// 		),
			// 	),
			// ),


			'help' => znpb_get_helptab( array(
				'video'   => 'http://support.hogash.com/kallyas-videos/#F1ttWpjkKqQ',
				'docs'    => 'http://support.hogash.com/documentation/icon-box/',
				'copy'    => $uid,
				'general' => true,
			)),

		);
	}
}
