<?php if(! defined('ABSPATH')){ return; }
/*
	Name: Smart Container
	Description: This element will generate a smart custom container in which you can add elements
	Class: ZnCustomContainer
	Category: Layout
	Level: 3
    Style: true
*/

	class ZnCustomContainer extends ZnElements {

	function options() {
		$uid = $this->data['uid'];

		// TODO: clean this up around v4.2
		$padding_std_lg = array(
			'top' => ( isset($this->data['options']['top_padding']) && !empty($this->data['options']['top_padding']) ? $this->data['options']['top_padding'].'%' : '1%' ),
			'right' => ( isset($this->data['options']['right_padding']) && !empty($this->data['options']['right_padding']) ? $this->data['options']['right_padding'].'%' : '' ),
			'bottom' => ( isset($this->data['options']['bottom_padding']) && !empty($this->data['options']['bottom_padding']) ? $this->data['options']['bottom_padding'].'%' : '' ),
			'left' => ( isset($this->data['options']['left_padding']) && !empty($this->data['options']['left_padding']) ? $this->data['options']['left_padding'].'%' : '' ),
		);

		// Check if we previously had Bg color & Bg opacity,
		// converted to alpha colorpicker
		// TODO: remove after version 4.2, presumably users made the update already
		$std_bgcolor_with_opacity = '';
		if( isset($this->data['options']['background_color']) && !empty($this->data['options']['background_color'])) {
			$std_bgcolor_with_opacity = $this->data['options']['background_color'];
			if( isset($this->data['options']['background_color_opacity']) && !empty($this->data['options']['background_color_opacity'] ) ){
				$std_bgcolor_with_opacity = zn_hex2rgba_str($this->data['options']['background_color'], $this->data['options']['background_color_opacity'] ) ;
			}
		}

		$options = array(
			'has_tabs'  => true,
			'background' => array(
				'title' => 'Style options',
				'options' => array(

					array (
						'id'          => 'layout',
						'name'        => 'Container Layout',
						'description' => 'Select the Smart container\'s layout.',
						'type'        => 'select',
						'std'     => '',
						'options'	  => array(
							'default'		=> 'Default (No custom styling)',
							'action_box'	=> 'Action Box style',
						),
					),

					array(
						'id'          => 'abox_bgcolor',
						'name'        => 'Background color',
						'description' => 'Here you can choose a custom background color for this container.',
						'type'        => 'colorpicker',
						'std'         => '',
						"dependency"  => array( 'element' => 'layout' , 'value'=> array('action_box') ),
					),

					array(
						'id'          => 'normal_bgcolor',
						'name'        => 'Background color',
						'description' => 'Here you can choose a custom background color for this container.',
						'type'        => 'colorpicker',
						'alpha'        => true,
						'std'         => $std_bgcolor_with_opacity,
						'live'        => array(
							'type'		=> 'css',
							'css_class' => '.'.$uid,
							'css_rule'	=> 'background-color',
							'unit'		=> ''
						),
						"dependency"  => array( 'element' => 'layout' , 'value'=> array('default') )
					),

					array(
						'id'          => 'background_image',
						'name'        => 'Background image',
						'description' => 'Please choose a background image for this section.',
						'type'        => 'background',
						'options' => array( "repeat" => true , "position" => true , "attachment" => true, "size" => true ),
						'class'		  => 'zn_full',
						"dependency"  => array( 'element' => 'layout' , 'value'=> array('default') )
					),

					// BORDER SETTINGS
					array (
						'id'          => 'border_style',
						'name'        => 'Border style',
						'description' => 'Select a border style you wish to use for this container.',
						'type'        => 'select',
						'options'	  => array(
							'none'		=> 'None',
							'solid'		=> 'Solid',
							'dotted'	=> 'Dotted',
							'dashed'	=> 'Dashed',
							'double'	=> 'Double',
							'groove'	=> 'Groove',
							'ridge'		=> 'Ridge',
							'inset'		=> 'Inset',
							'outset'	=> 'Outset'
						),
						'live' => array(
							'type'		=> 'css',
							'css_class' => '.'.$uid,
							'css_rule'	=> 'border-style',
							'unit'		=> ''
						),
						"dependency"  => array( 'element' => 'layout' , 'value'=> array('default') )
					),

					array(
						'id'          => 'border_width',
						'name'        => 'Border width',
						'description' => 'Select the border width you wish to use for this container.',
						'type'        => 'slider',
						'std'		  => '0',
						'class'		  => 'zn_full',
						'helpers'	  => array(
							'min' => '0',
							'max' => '100',
							'step' => '1'
						),
						'live' => array(
							'type'		=> 'css',
							'css_class' => '.'.$uid,
							'css_rule'	=> 'border-width',
							'unit'		=> 'px'
						),
                		"dependency"  => array(
                			array( 'element' => 'border_style' , 'value'=> array('solid', 'dotted', 'dashed', 'double', 'groove', 'ridge', 'inset', 'outset') ),
                			array( 'element' => 'layout' , 'value'=> array('default') )
            			),
					),
					array(
						'id'          => 'border_color',
						'name'        => 'Border color',
						'description' => 'Here you can override the background color for this section.',
						'type'        => 'colorpicker',
						'std'         => '',
						'live'        => array(
							'type'		=> 'css',
							'css_class' => '.'.$uid,
							'css_rule'	=> 'border-color',
							'unit'		=> ''
						),
                		"dependency"  => array(
                			array( 'element' => 'border_style' , 'value'=> array('solid', 'dotted', 'dashed', 'double', 'groove', 'ridge', 'inset', 'outset') ),
                			array( 'element' => 'layout' , 'value'=> array('default') )
            			),
					),
					array(
						'id'          => 'corner_radius',
						'name'        => 'Corner radius',
						'description' => 'Select a corner radius (in pixels) for this container.',
						'type'        => 'slider',
						'std'		  => '0',
						'class'		  => 'zn_full',
						'helpers'	  => array(
							'min' => '0',
							'max' => '400',
							'step' => '1'
						),
						'live' => array(
							'type'		=> 'css',
							'css_class' => '.'.$uid,
							'css_rule'	=> 'border-radius',
							'unit'		=> 'px'
						),
						"dependency"  => array( 'element' => 'layout' , 'value'=> array('default') )
					),
				)
			),
			'spacing' => array(
				'title' => 'Spacing options',
				'options' => array(

					array (
			            "name"        => __( "Edit padding for each device breakpoint", 'zn_framework' ),
			            "description" => __( "Edit the padding options for each breakpoint (device). This will enable you to have more control over the padding of the container on each device. For example you might want the subheader to be shorter on mobiles, but taller on desktops.", 'zn_framework' ),
			            "id"          => "cc_spacing_breakpoints",
			            "std"         => "lg",
			            "type"        => "zn_radio",
			            "options"     => array (
			                "lg"        => __( "LARGE", 'zn_framework' ),
			                "md"        => __( "MEDIUM", 'zn_framework' ),
			                "sm"        => __( "SMALL", 'zn_framework' ),
			                "xs"        => __( "EXTRA SMALL", 'zn_framework' ),
			            ),
			            "class"       => "zn_full zn_breakpoints"
			        ),
					// PADDINGS
			        array(
						'id'          => 'cc_padding_lg',
						'name'        => 'Padding (Large Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => $padding_std_lg,
						'placeholder' => '0px',
                		"dependency"  => array(
                			array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('lg')),
                			array( 'element' => 'layout' , 'value'=> array('default') )
                		),
					),
			        array(
						'id'          => 'cc_padding_md',
						'name'        => 'Padding (Medium Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => 	'',
						'placeholder'        => '0px',
                		"dependency"  => array(
                			array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('md')),
                			array( 'element' => 'layout' , 'value'=> array('default') )
                		),
					),
			        array(
						'id'          => 'cc_padding_sm',
						'name'        => 'Padding (Small Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => 	'',
						'placeholder'        => '0px',
                		"dependency"  => array(
                			array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('sm')),
                			array( 'element' => 'layout' , 'value'=> array('default') )
                		),
					),
			        array(
						'id'          => 'cc_padding_xs',
						'name'        => 'Padding (Extra Small Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => 	'',
						'placeholder'        => '0px',
                		"dependency"  => array(
                			array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('xs')),
                			array( 'element' => 'layout' , 'value'=> array('default') )
                		),
					),
			        // MARGINS
					array(
						'id'          => 'cc_margin_lg',
						'name'        => 'Margin (Large Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container. Accepts negative margin.',
						'type'        => 'boxmodel',
						'std'	  => '',
						'placeholder' => '0px',
                		"dependency"  => array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('lg') ),
					),
					array(
						'id'          => 'cc_margin_md',
						'name'        => 'Margin (Medium Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => 	'',
						'placeholder'        => '0px',
                		"dependency"  => array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('md') ),
					),
			        array(
						'id'          => 'cc_margin_sm',
						'name'        => 'Margin (Small Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => 	'',
						'placeholder'        => '0px',
                		"dependency"  => array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('sm') ),
					),
			        array(
						'id'          => 'cc_margin_xs',
						'name'        => 'Margin (Extra Small Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => 	'',
						'placeholder'        => '0px',
                		"dependency"  => array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('xs') ),
					),


				)
			),
			'advanced' => array(
				'title' => 'Advanced',
				'options' => array(
					array(
                        'id'          => 'gutter_size',
                        'name'        => 'Gutter Size',
                        'description' => 'Select the gutter distance between columns',
                        "std"         => "",
                        "type"        => "select",
                        "options"     => array (
                            '' => __( 'Default (15px)', 'zn_framework' ),
                            'gutter-xs' => __( 'Extra Small (5px)', 'zn_framework' ),
                            'gutter-sm' => __( 'Small (10px)', 'zn_framework' ),
                            'gutter-md' => __( 'Medium (25px)', 'zn_framework' ),
                            'gutter-lg' => __( 'Large (40px)', 'zn_framework' ),
                            'gutter-0' => __( 'No distance - 0px', 'zn_framework' ),
                        ),
                        'live' => array(
                            'type'      => 'class',
                            'css_class' => '.'.$uid.' > .row'
                        )
                    ),

					array(
						'id'          => 'pad_type',
						'name'        => 'Equaliser padding',
						'description' => "Equalizer padding should only be used inside a full-width container and will help to display a proper alignment of the element's left or right edge in context to the site's container left and/or right boundries.<br> Make sure you select 'First' only if the column is the first in the row. Select 'Last' if the element is on the last column from the row.",
						'type'        => 'select',
						'std'        => '',
						'options' => array(
							"" => 'Disabled',
							"eq_first" => 'First Column Equalizer',
							"eq_last" => 'Last Column Equalizer'
						),
						"dependency"  => array( 'element' => 'layout' , 'value'=> array('default') )
					),

				)
			),

			'help' => znpb_get_helptab( array(
				'video'   => 'http://support.hogash.com/kallyas-videos/#Dg_OJQDUZoI',
				'docs'    => 'http://support.hogash.com/documentation/custom-container/',
				'copy'    => $uid,
				'general' => true,
			)),

		);

		return $options;

	}

	function element() {

		// print_z($this);
		$options = $this->data['options'];

		$layout = $this->opt('layout', 'default');

		$elm_classes = array();
		$elm_classes[] = $uid = $this->data['uid'];
		$elm_classes[] = zn_get_element_classes($options);
		$elm_classes[] = 'smart-cnt--'.$layout;

		$attributes = zn_get_element_attributes($options);

		$eq_pad_start = '';
		$eq_pad_end = '';
		$eq_pad_type = $this->opt('pad_type','');
		if( !empty($eq_pad_type) && $layout == 'default' ) {
			$eq_pad_start = '<div class="zn_col_'.$eq_pad_type.'">';
			$eq_pad_end = '</div>';
		}

		$action_box_start = '';
		$action_box_end = '';
		if($layout == 'action_box'){
			$action_box_start = '<div class="smart-cnt-inner">';
			$action_box_end = '</div>';
		}
	?>

	<div class="zn_custom_container <?php echo implode(' ', $elm_classes); ?> clearfix" <?php echo $attributes; ?>>
		<?php echo $eq_pad_start; ?>
		<?php echo $action_box_start; ?>
			<div class="row zn_columns_container zn_content <?php echo $this->opt('gutter_size',''); ?>" data-droplevel="1">
				<?php
					if ( empty( $this->data['content']) ) {
						$column = ZN()->pagebuilder->add_module_to_layout( 'ZnColumn', array() , array(), 'col-sm-12' );
						$this->data['content'] = array ( $column );
					}
					if ( !empty( $this->data['content'] ) ) {
			            ZN()->pagebuilder->zn_render_content( $this->data['content'] );
			        }
				?>
			</div>
		<?php echo $eq_pad_end; ?>
		<?php echo $action_box_end; ?>
	</div><!-- /.zn_custom_container -->


	<?php
	}

	function css(){

		//print_z($this);
		$css = '';
		$uid = $this->data['uid'];
		$pad_type = $this->opt('pad_type','');
		$layout = $this->opt('layout', 'default');

		$lg_css = '';
		$padding_css_lg = '';
		$margin_css_lg = '';

		$margin_css_lg = zn_add_boxmodel( $this->opt('cc_margin_lg', '' ), 'margin');
		$margin_css_md = zn_add_boxmodel( $this->opt('cc_margin_md', '' ), 'margin');
		$margin_css_sm = zn_add_boxmodel( $this->opt('cc_margin_sm', '' ), 'margin');
		$margin_css_xs = zn_add_boxmodel( $this->opt('cc_margin_xs', '' ), 'margin');

		if( !empty($margin_css_lg) ){
			$lg_css .= $margin_css_lg;
		}

		/**
		 * PADDING for LARGE Breakpoint
		 * Only on default layout
		 */
		if($layout == 'default'){

			// Old paddings, check if they're added;
			$tpadding = $this->opt('top_padding') || $this->opt('top_padding') != '0' ? 'padding-top:'.$this->opt('top_padding').'%;' : '';
			$rpadding = $this->opt('right_padding') || $this->opt('right_padding') != '0'  ? 'padding-right:'.$this->opt('right_padding').'%;' : '';
			$bpadding = $this->opt('bottom_padding') || $this->opt('bottom_padding') != '0'  ? 'padding-bottom:'.$this->opt('bottom_padding').'%;' : '';
			$lpadding = $this->opt('left_padding') || $this->opt('left_padding') != '0'  ? 'padding-left:'.$this->opt('left_padding').'%;' : '';

			// Padding large but old system
			if( !empty($tpadding) || !empty($rpadding) || !empty($bpadding) || !empty($lpadding) ) {
				$padding_css_lg .= $tpadding.$rpadding.$bpadding.$lpadding;
			} else {
				$padding_css_lg = zn_add_boxmodel( $this->opt('cc_padding_lg', array('top' => '1%') ), 'padding');
			}

			$padding_css_md = zn_add_boxmodel( $this->opt('cc_padding_md', '' ), 'padding');
			$padding_css_sm = zn_add_boxmodel( $this->opt('cc_padding_sm', '' ), 'padding');
			$padding_css_xs = zn_add_boxmodel( $this->opt('cc_padding_xs', '' ), 'padding');

			// Load margin & padding for LARGE breakpoint
			if( !empty($padding_css_lg) ){
				$lg_css .= $padding_css_lg;
			}

			//** Set the background image for the container
			$stored_background = $this->opt('background_image', false);
			$background_image = '';
			if ( $stored_background && !empty( $stored_background['image'] ) ){
				$background_image = "background-image: url('".$stored_background['image']."');";
				$background_image .= 'background-repeat:'. $stored_background['repeat'].';';
				$background_image .= 'background-position:'. $stored_background['position']['x'].' '.$stored_background['position']['y'].';';
				$background_image .= 'background-attachment:'. $stored_background['attachment'].';';
				$background_image .= 'background-size:'. $stored_background['size'].';';
			}

			//** Set the background color for the container
			$bkg_color = '';

			//Check old colorpicker
			$old_background_color = $this->opt('background_color', '');
			$old_background_color_opacity = $this->opt('background_color_opacity', '100');
			if( isset($old_background_color) && !empty($old_background_color)) {
				$bkg_color = $old_background_color;
				if( isset($old_background_color_opacity) && !empty($old_background_color_opacity ) ){
					$bkg_color = zn_hex2rgba_str($old_background_color, $old_background_color_opacity );
				}
			}
			// Switch to the new bg color (with alpha)
			else {
				$bkg_color = $this->opt('normal_bgcolor','');
			}
			// Add the style
			if (!empty($bkg_color))
			{
				$bkg_color = " background-color:".$bkg_color." !important; ";
			}

			//** Set the border for the container
			$border = "";
			$border_style = $this->opt('border_style','none');
			if ($border_style !== 'none') {
				$border_width = $this->opt('border_width',0);
				$border_color = $this->opt('border_color','transparent');
				$border = " border-style:$border_style; border-width:{$border_width}px; border-color:$border_color;";
				// shorten up if all are set
				if( $border_style != 'none' && !empty($border_width) && !empty($border_color) ) {
					$border = " border:$border_style {$border_width}px $border_color;";
				}
			}

			//** Set the corner radius
			$border_radius = "";
			$corner_radius = $this->opt('corner_radius','');
			if (!empty($corner_radius))
			{
				$border_radius =  "border-radius:{$corner_radius}px;";
			}
			// LOAD STYLES FOR LARGE (DEFAULT)
			if(!empty($background_image) || !empty($bkg_color) || !empty($border) || !empty($border_radius) ||  !empty($padding_css_lg) || !empty($margin_css_lg) ){
				$css .= '.'.$uid.'{';
				$css .= $background_image;
				$css .= $bkg_color;
				$css .= $border;
				$css .= $border_radius;
				$css .= "}";
			}
		}

		if(!empty($padding_css_lg) || !empty($margin_css_lg) ){
			$css .= '.'.$uid.'{'. $lg_css . '}';
		}

		// Action Box style background colors
		if($layout == 'action_box'){
			$abox_bgcolor = $this->opt('abox_bgcolor','');
			if( !empty($abox_bgcolor) ){
				$css .= '.'.$uid.'.smart-cnt--action_box, .'.$uid.'.smart-cnt--action_box:before, .'.$uid.' .smart-cnt-inner:before {background-color: '. $abox_bgcolor . ';}';
			}
		}

		// Load margin & padding for MEDIUM breakpoint
		if( !empty($padding_css_md) || !empty($margin_css_md) ){
			$css .= '@media screen and (min-width: 992px) and (max-width: 1199px){.'.$uid.'{';
			if( !empty($padding_css_md) && $layout == 'default' ){
				$css .= $padding_css_md;
			}
			if( !empty($margin_css_md) ){
				$css .= $margin_css_md;
			}
			$css .= '}}';
		}
		// Load margin & padding for SMALL breakpoint
		if( !empty($padding_css_sm) || !empty($margin_css_sm) ){
			$css .= '@media screen and (min-width: 768px) and (max-width:991px){.'.$uid.'{';
			if( !empty($padding_css_sm) && $layout == 'default' ){
				$css .= $padding_css_sm;
			}
			if( !empty($margin_css_sm) ){
				$css .= $margin_css_sm;
			}
			$css .= '}}';
		}
		// Load margin & padding for EXTRA SMALL breakpoint
		if( !empty($padding_css_xs) || !empty($margin_css_xs) ){
			$css .= '@media screen and (max-width: 767px){.'.$uid.'{';
			if( !empty($padding_css_xs) && $layout == 'default' ){
				$css .= $padding_css_xs;
			}
			if( !empty($margin_css_xs) ){
				$css .= $margin_css_xs;
			}
			$css .= '}}';
		}

		return $css;
	}

}

?>
