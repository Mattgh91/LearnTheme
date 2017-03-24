<?php if(! defined('ABSPATH')){ return; }
/*
 Name: Media Container
 Description: Create and display a Media Container element
 Class: TH_MediaContainer
 Category: content, media
 Level: 3
*/
/**
 * Class TH_MediaContainer
 *
 * Create and display a Media Container element
 *
 * @package  Kallyas
 * @category Page Builder
 * @author   Team Hogash
 * @since    4.0.0
 */
class TH_MediaContainer extends ZnElements
{

	public static function getName(){
		return __( "Media Container", 'zn_framework' );
	}

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){
		$css = '';
		$uid = $this->data['uid'];
		$link_type = $this->opt('mc_link_type','');
		$link_style = $this->opt('mc_link_style', 'lined');

		if($this->opt('mc_height_lg', '300') != 300){
			$css .= '@media screen and (min-width: 1200px){ .'.$uid.' { height:'.$this->opt('mc_height_lg', '300').'px;  } }';
		}
		if($this->opt('mc_height_md', '300') != 300){
			$css .= '@media screen and (min-width: 992px) and (max-width: 1199px){ .'.$uid.' { height:'.$this->opt('mc_height_md', '300').'px;  } }';
		}
		if($this->opt('mc_height_sm', '300') != 300){
			$css .= '@media screen and (min-width: 768px) and (max-width: 991px){ .'.$uid.' { height:'.$this->opt('mc_height_sm', '300').'px;  } }';
		}
		if($this->opt('mc_height_xs', '400') != 400){
			$css .= '@media screen and (max-width: 767px){ .'.$uid.' { height:'.$this->opt('mc_height_xs', '400').'px;  } }';
		}

		if( $link_type == 'btn' ) {
			if($mc_btn_color = $this->opt('mc_btn_color','')){
				$css .= '.'.$uid.' .media-container__link {color:'.$mc_btn_color.';}';
			}
			if($mc_btn_color_hover = $this->opt('mc_btn_color_hover','')){
				$css .= '.'.$uid.' .media-container__link:hover {color:'.$mc_btn_color_hover.';}';
			}
		}

		if( $link_type == 'btn' && $link_style == 'borderanim2' ) {

			$borderWidth = 100; // Starting width of the bottom border
			$boxWidth = $this->opt('mc_borderanim2_width','400');    // Box width
			$boxHeight = 70;    // Box height

			$css .= ' .'.$uid.' .borderanim2-svg {width: '.$boxWidth.'px; }
				.'.$uid.' .borderanim2-svg .media-container__text {line-height: '.$boxHeight.'px; }
				.'.$uid.' .borderanim2-svg__shape {stroke-dasharray: '.$borderWidth.' 1000; stroke-dashoffset: -'.( $boxWidth + $boxHeight + (( $boxWidth - $borderWidth ) / 2) ).'; }
				.'.$uid.':hover .borderanim2-svg__shape {stroke-dasharray: '.( ($boxWidth*2)+($boxHeight*2) ).'; }
			';
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

		$elm_classes=array();
		$elm_classes[] = $uid = $this->data['uid'];
		$elm_classes[] = zn_get_element_classes($options);

		$attributes = zn_get_element_attributes($options);
?>
	<div class="media-container <?php echo implode(' ', $elm_classes); ?>" <?php echo $attributes; ?>>

		<?php

		WpkPageHelper::zn_background_source( array(
			'source_type' => $this->opt('source_type'),
			'source_background_image' => $this->opt('background_image'),
			'source_vd_yt' => $this->opt('source_vd_yt'),
			'source_vd_embed_iframe' => $this->opt('source_vd_embed_iframe'),
			'source_vd_self_mp4' => $this->opt('source_vd_self_mp4'),
			'source_vd_self_ogg' => $this->opt('source_vd_self_ogg'),
			'source_vd_self_webm' => $this->opt('source_vd_self_webm'),
			'source_vd_vp' => $this->opt('source_vd_vp'),
			'source_vd_autoplay' => $this->opt('source_vd_autoplay'),
			'source_vd_loop' => $this->opt('source_vd_loop'),
			'source_vd_muted' => $this->opt('source_vd_muted'),
			'source_vd_controls' => $this->opt('source_vd_controls'),
			'source_vd_controls_pos' => $this->opt('source_vd_controls_pos'),
			'source_overlay' => $this->opt('source_overlay'),
			'source_overlay_color' => $this->opt('source_overlay_color'),
			'source_overlay_opacity' => $this->opt('source_overlay_opacity'),
			'source_overlay_color_gradient' => $this->opt('source_overlay_color_gradient'),
			'source_overlay_color_gradient_opac' => $this->opt('source_overlay_color_gradient_opac'),
			'source_overlay_gloss' => $this->opt('source_overlay_gloss',''),
		) );


		$link_style = $this->opt('mc_link_style', 'lined');

		$link_class= '';
		$link_type = $this->opt('mc_link_type','');
		$btn_text = $this->opt('mc_btn_text');

		// Target
		$target = '';
		$mc_link_target = $this->opt('mc_link_target','self');
		if($mc_link_target == 'self') $target = 'target="_self"';
		elseif($mc_link_target == 'blank') $target = 'target="_blank"';
		elseif($mc_link_target == 'img') $target = 'data-lightbox="image"';
		elseif($mc_link_target == 'iframe') $target = 'data-lightbox="iframe"';

		$mc_btn_link = $this->opt('mc_btn_link');
		// Override link if modal image selected
		if($mc_link_target == 'img'){
			$mc_btn_link = $this->opt('mc_btn_modalimg');
		}

		if($link_type == 'btn' && ($link_style == 'lined' || $link_style == 'linedplay' ) && !empty($btn_text) ){
			$link_class .= 'btn btn-lined';
		}

		$mct_link=array();
		$mct_link[] = 'media-container__link--'.$link_type;
		$mct_link[] = 'media-container__link--style-'.$link_style;
		$mct_link[] = $link_class;

		if($link_style == 'borderanim1' || $link_style == 'borderanim2'){
			$mct_link[] = 'kl-font-alt';
		}

		if( !empty($mc_btn_link) ){
		?>
		<a class="media-container__link <?php echo implode(' ', $mct_link); ?>" href="<?php echo $mc_btn_link; ?>" <?php echo $target; ?> >
			<?php
			// Show the text
			$text = '';
			if( $link_style != 'circle' && !empty($btn_text) ){
				$text = '<span class="media-container__text">'.$btn_text.'</span>';
			}

			// Show icon if circle or linedplay style
			if( $link_type == 'btn' && $link_style == 'lined' ) {
				// Show text
				echo $text;
			}

			// Show icon if circle or linedplay style
			if( $link_type == 'btn' && $link_style == 'linedplay' ) {
				echo '<i class="kl-icon glyphicon glyphicon-play"></i>';
				// Show text
				echo $text;
			}

			// Show icon if circle or circle style
			if( $link_type == 'btn' && $link_style == 'circle' ) {
				echo '
				<div class="circleanim-svg">
					<svg height="108" width="108" xmlns="http://www.w3.org/2000/svg" >
						<circle stroke-opacity="0.1" fill="#FFFFFF" stroke-width="5" cx="54" cy="54" r="48" class="circleanim-svg__circle-back"></circle>
						<circle stroke-width="5" fill="#FFFFFF" cx="54" cy="54" r="48" class="circleanim-svg__circle-front" transform="rotate(50 54 54) "></circle>
						<path d="M62.1556183,56.1947505 L52,62.859375 C50.6192881,63.7654672 49.5,63.1544098 49.5,61.491212 L49.5,46.508788 C49.5,44.8470803 50.6250889,44.2383396 52,45.140625 L62.1556183,51.8052495 C64.0026693,53.0173767 63.9947588,54.9878145 62.1556183,56.1947505 Z"  fill="#FFFFFF"></path>
					</svg>
				  '.$text.'
				</div>';
			}

			// Show border animation Style 1 (part 1)
			if( $link_type == 'btn' && $link_style == 'borderanim1' ) {
				// part 1
				echo '<i class="media-container__border-tt"></i><i class="media-container__border-tl"></i>';
				// Show text
				echo $text;
				// part 2
				echo '<i class="media-container__border-bb"></i><i class="media-container__border-br"></i>';
			}


			// Show border animation Style 1 (part 1)
			if( $link_type == 'btn' && $link_style == 'borderanim2' ) {

				// There's a math behind the stroke animation,
				$boxWidth = $this->opt('mc_borderanim2_width','400');    // Box width
				$boxHeight = 70;    // Box height

				echo '

				<div class="borderanim2-svg">
				  <svg height="'.$boxHeight.'" width="'.$boxWidth.'" xmlns="http://www.w3.org/2000/svg">
					<rect class="borderanim2-svg__shape" height="'.$boxHeight.'" width="'.$boxWidth.'" />
				  </svg>
				  '.$text.'
				</div>';
			}


			?>
		</a>
		<?php } ?>

	</div>
<?php


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
						"name"        => __( "Link Type", 'zn_framework' ),
						"description" => __( "Add a center button?", 'zn_framework' ),
						"id"          => "mc_link_type",
						"std"         => "",
						"type"        => "select",
						"options"     => array (
							''  => __( "No link, just media.", 'zn_framework' ),
							'btn'  => __( "Centered Button", 'zn_framework' ),
							'wrap'  => __( "Link the container", 'zn_framework' ),
						)
					),

					array (
						"name"        => __( "Button Style", 'zn_framework' ),
						"description" => __( "Select a button style", 'zn_framework' ),
						"id"          => "mc_link_style",
						"std"         => "lined",
						"type"        => "select",
						"options"     => array (
							'lined'  => __( "Lined button. Requires text!", 'zn_framework' ),
							'circle'  => __( "Circle Play", 'zn_framework' ),
							'linedplay'  => __( "Lined with play icon. Requires text!", 'zn_framework' ),
							'borderanim1'  => __( "Border animation. Requires text!", 'zn_framework' ),
							'borderanim2'  => __( "Alternative border animation. Requires text!", 'zn_framework' ),
						),
						"dependency"  => array( 'element' => 'mc_link_type' , 'value'=> array('btn') ),
					),

					array (
						"name"        => __( "Button Text", 'zn_framework' ),
						"description" => __( "Add a text inside the button. If no text is added, an icon will be displayed.", 'zn_framework' ),
						"id"          => "mc_btn_text",
						"std"         => "",
						"type"        => "text",
						"dependency"  => array( 'element' => 'mc_link_type' , 'value'=> array('btn') ),
					),

					array (
						"name"        => __( "Button Text Color", 'zn_framework' ),
						"description" => __( "Select the color you want.", 'zn_framework' ),
						"id"          => "mc_btn_color",
						"std"         => "",
						"type"        => "colorpicker",
						"alpha"        => "true",
						"dependency"  => array(
							array( 'element' => 'mc_link_type' , 'value'=> array('btn') ),
							array( 'element' => 'mc_link_style' , 'value'=> array('lined','linedplay','borderanim1','borderanim2') ),
						),
					),
					array (
						"name"        => __( "Button Text Hover Color", 'zn_framework' ),
						"description" => __( "Select the color you want.", 'zn_framework' ),
						"id"          => "mc_btn_color_hover",
						"std"         => "",
						"type"        => "colorpicker",
						"alpha"        => "true",
						"dependency"  => array(
							array( 'element' => 'mc_link_type' , 'value'=> array('btn') ),
							array( 'element' => 'mc_link_style' , 'value'=> array('lined','linedplay','borderanim1','borderanim2') ),
						),
					),

					array (
						"name"        => __( "Alternative border animation - box width", 'zn_framework' ),
						"description" => __( "set a width for the alternative border animation.", 'zn_framework' ),
						"id"          => "mc_borderanim2_width",
						"std"         => "400",
						"type"        => "text",
						"placeholder" => "",
						"dependency"  => array(
							array( 'element' => 'mc_link_type' , 'value'=> array('btn') ),
							array( 'element' => 'mc_link_style' , 'value'=> array('borderanim2') ),
						),
					),

					array (
						"name"        => __( "Link Target", 'zn_framework' ),
						"description" => __( "Add a center button?", 'zn_framework' ),
						"id"          => "mc_link_target",
						"std"         => "self",
						"type"        => "select",
						"options"     => array (
							'self'  => __( "Link to another page in site", 'zn_framework' ),
							'blank' => __( "Link to a new window", 'zn_framework' ),
							'img' => __( "Link to modal image", 'zn_framework' ),
							'iframe' => __( "Link to modal iframe (Youtube, Vimeo, Gmaps etc.)", 'zn_framework' )
						),
						"dependency"  => array( 'element' => 'mc_link_type' , 'value'=> array('btn','wrap') ),
					),

					array (
						"name"        => __( "Link URL", 'zn_framework' ),
						"description" => __( "Add a link for the link.", 'zn_framework' ),
						"id"          => "mc_btn_link",
						"std"         => "",
						"type"        => "text",
						"placeholder" => "http:// ...",
						"dependency"  => array( 'element' => 'mc_link_type' , 'value'=> array('btn','wrap') ),
					),

					array (
						"name"        => __( "Modal image link", 'zn_framework' ),
						"description" => __( "Add an image for the modal.", 'zn_framework' ),
						"id"          => "mc_btn_modalimg",
						"std"         => "",
						"type"        => "media",
						"dependency"  => array( 'element' => 'mc_link_target' , 'value'=> array('img') ),
					),


				)
			),

			'height' => array(
				'title' => 'Height options',
				"options" => array(
					array (
						"name"        => __( "Height LG - Large breakpoint", 'zn_framework' ),
						"description" => __( "Set the height of the media container for large breakpoint '> 1200px' ", 'zn_framework' ),
						"id"          => "mc_height_lg",
						'type'        => 'slider',
						'std'         => '300',
						'class'       => 'zn_full',
						'helpers'     => array(
							'min' => '0',
							'max' => '1000',
							'step' => '5'
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$this->data['uid'],
							'css_rule'  => 'height',
							'unit'      => 'px'
						)
					),
					array (
						"name"        => __( "Height MD - Medium breakpoint", 'zn_framework' ),
						"description" => __( "Set the height of the media container for medium breakpoint '> 992px' and '< 1199px' ", 'zn_framework' ),
						"id"          => "mc_height_md",
						'type'        => 'slider',
						'std'         => '300',
						'class'       => 'zn_full',
						'helpers'     => array(
							'min' => '0',
							'max' => '1000',
							'step' => '5'
						)
					),
					array (
						"name"        => __( "Height SM - Small breakpoint", 'zn_framework' ),
						"description" => __( "Set the height of the media container for small breakpoint '> 768px' and '< 991px' ", 'zn_framework' ),
						"id"          => "mc_height_sm",
						'type'        => 'slider',
						'std'         => '300',
						'class'       => 'zn_full',
						'helpers'     => array(
							'min' => '0',
							'max' => '1000',
							'step' => '5'
						)
					),
					array (
						"name"        => __( "Height XS - Extra small breakpoint", 'zn_framework' ),
						"description" => __( "Set the height of the media container for extra small breakpoint '< 767px' ", 'zn_framework' ),
						"id"          => "mc_height_xs",
						'type'        => 'slider',
						'std'         => '400',
						'class'       => 'zn_full',
						'helpers'     => array(
							'min' => '0',
							'max' => '1000',
							'step' => '5'
						)
					),
				)
			),

			'background' => array(
				'title' => 'Background & Styles Options',
				'options' => array(

					// Background image/video or youtube
					array (
						"name"        => __( "Background Source Type", 'zn_framework' ),
						"description" => __( "Please select the source type of the background.", 'zn_framework' ),
						"id"          => "source_type",
						"std"         => "",
						"type"        => "select",
						"options"     => array (
							''  => __( "None (Will just rely on the background color (if any) )", 'zn_framework' ),
							'image'  => __( "Image", 'zn_framework' ),
							'video_self' => __( "Self Hosted Video", 'zn_framework' ),
							'video_youtube' => __( "Youtube Video", 'zn_framework' ),
							'embed_iframe' => __( "Embed Iframe (Vimeo etc.)", 'zn_framework' )
						)
					),

					array(
						'id'          => 'background_image',
						'name'        => 'Background image',
						'description' => 'Please choose a background image for this section.',
						'type'        => 'background',
						'options' => array( "repeat" => true , "position" => true , "attachment" => true, "size" => true ),
						'class'       => 'zn_full',
						'dependency' => array( 'element' => 'source_type' , 'value'=> array('image') )
					),

					// array(
					//  'id'            => 'enable_parallax',
					//  'name'          => 'Enable parallax',
					//  'description'   => 'Select if you want to enable parallax effect on background image',
					//  'type'          => 'toggle2',
					//  'std'           => '',
					//  'value'         => 'yes'
					// ),



					// Youtube video
					array (
						"name"        => __( "Slide Video Youtube ID", 'zn_framework' ),
						"description" => __( "Add an Youtube ID", 'zn_framework' ),
						"id"          => "source_vd_yt",
						"std"         => "",
						"type"        => "text",
						"placeholder" => "ex: tR-5AZF9zPI",
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_youtube') )
					),
					// Embed Iframe
					array (
						"name"        => __( "Embed Iframe link", 'zn_framework' ),
						"description" => __( "Add a link", 'zn_framework' ),
						"id"          => "source_vd_embed_iframe",
						"std"         => "",
						"type"        => "text",
						"placeholder" => "ex: https://vimeo.com/17874452",
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('embed_iframe') )
					),

					/* LOCAL VIDEO */
					array(
						'id'          => 'source_vd_self_mp4',
						'name'        => 'Mp4 video source',
						'description' => 'Add the MP4 video source for your local video',
						'type'        => 'media_upload',
						'std'         => '',
						'data'  => array(
							'type' => 'video/mp4',
							'button_title' => 'Add / Change mp4 video',
						),
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self') )
					),
					array(
						'id'          => 'source_vd_self_ogg',
						'name'        => 'Ogg/Ogv video source',
						'description' => 'Add the OGG video source for your local video',
						'type'        => 'media_upload',
						'std'         => '',
						'data'  => array(
							'type' => 'video/ogg',
							'button_title' => 'Add / Change ogg video',
						),
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self') )
					),
					array(
						'id'          => 'source_vd_self_webm',
						'name'        => 'Webm video source',
						'description' => 'Add the WEBM video source for your local video',
						'type'        => 'media_upload',
						'std'         => '',
						'data'  => array(
							'type' => 'video/webm',
							'button_title' => 'Add / Change webm video',
						),
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self') )
					),
					array(
						'id'          => 'source_vd_vp',
						'name'        => 'Video poster',
						'description' => 'Using this option you can add your desired video poster that will be shown on unsuported devices.',
						'type'        => 'media',
						'std'         => '',
						'class'       => 'zn_full',
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self','video_youtube') )
					),
					array(
						'id'          => 'source_vd_autoplay',
						'name'        => 'Autoplay video?',
						'description' => 'Enable autoplay for video?',
						'type'        => 'select',
						'std'         => 'yes',
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self','video_youtube') ),
						"options"     => array (
							"yes" => __( "Yes", 'zn_framework' ),
							"no"  => __( "No", 'zn_framework' )
						),
						"class"       => "zn_input_xs"
					),
					array(
						'id'          => 'source_vd_loop',
						'name'        => 'Loop video?',
						'description' => 'Enable looping the video?',
						'type'        => 'select',
						'std'         => 'yes',
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self','video_youtube') ),
						"options"     => array (
							"yes" => __( "Yes", 'zn_framework' ),
							"no"  => __( "No", 'zn_framework' )
						),
						"class"       => "zn_input_xs"
					),
					array(
						'id'          => 'source_vd_muted',
						'name'        => 'Start mute?',
						'description' => 'Start the video with muted audio?',
						'type'        => 'select',
						'std'         => 'yes',
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self','video_youtube') ),
						"options"     => array (
							"yes" => __( "Yes", 'zn_framework' ),
							"no"  => __( "No", 'zn_framework' )
						),
						"class"       => "zn_input_xs"
					),
					array(
						'id'          => 'source_vd_controls',
						'name'        => 'Video controls',
						'description' => 'Enable video controls?',
						'type'        => 'select',
						'std'         => 'yes',
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self','video_youtube') ),
						"options"     => array (
							"yes" => __( "Yes", 'zn_framework' ),
							"no"  => __( "No", 'zn_framework' )
						),
						"class"       => "zn_input_xs"
					),
					array(
						'id'          => 'source_vd_controls_pos',
						'name'        => 'Video controls position',
						'description' => 'Video controls position in the slide',
						'type'        => 'select',
						'std'         => 'bottom-right',
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self','video_youtube') ),
						"options"     => array (
							"top-right" => __( "top-right", 'zn_framework' ),
							"top-left" => __( "top-left", 'zn_framework' ),
							"top-center"  => __( "top-center", 'zn_framework' ),
							"bottom-right"  => __( "bottom-right", 'zn_framework' ),
							"bottom-left"  => __( "bottom-left", 'zn_framework' ),
							"bottom-center"  => __( "bottom-center", 'zn_framework' ),
							"middle-right"  => __( "middle-right", 'zn_framework' ),
							"middle-left"  => __( "middle-left", 'zn_framework' ),
							"middle-center"  => __( "middle-center", 'zn_framework' )
						),
						"class"       => "zn_input_sm"
					),

					array(
						'id'          => 'source_overlay',
						'name'        => 'Background colored overlay',
						'description' => 'Add slide color overlay over the image or video to darken or enlight?',
						'type'        => 'select',
						'std'         => '0',
						"options"     => array (
							"1" => __( "Yes (Normal color)", 'zn_framework' ),
							"2" => __( "Yes (Horizontal gradient)", 'zn_framework' ),
							"3" => __( "Yes (Vertical gradient)", 'zn_framework' ),
							"0"  => __( "No", 'zn_framework' )
						)
					),

					array(
						'id'          => 'source_overlay_color',
						'name'        => 'Overlay background color',
						'description' => 'Pick a color',
						'type'        => 'colorpicker',
						'std'         => '#353535',
						"dependency"  => array( 'element' => 'source_overlay' , 'value'=> array('1', '2', '3') ),
					),
					array(
						'id'          => 'source_overlay_opacity',
						'name'        => 'Overlay\'s opacity.',
						'description' => 'Overlay background colors opacity level.',
						'type'        => 'slider',
						'std'         => '30',
						"helpers"     => array (
							"step" => "5",
							"min" => "0",
							"max" => "100"
						),
						"dependency"  => array( 'element' => 'source_overlay' , 'value'=> array('1', '2', '3') ),
					),

					array(
						'id'          => 'source_overlay_color_gradient',
						'name'        => 'Overlay Gradient 2nd Bg. Color',
						'description' => 'Pick a color',
						'type'        => 'colorpicker',
						'std'         => '#353535',
						"dependency"  => array( 'element' => 'source_overlay' , 'value'=> array('2', '3') ),
					),
					array(
						'id'          => 'source_overlay_color_gradient_opac',
						'name'        => 'Gradient Overlay\'s 2nd Opacity.',
						'description' => 'Overlay gradient 2nd background color opacity level.',
						'type'        => 'slider',
						'std'         => '30',
						"helpers"     => array (
							"step" => "5",
							"min" => "0",
							"max" => "100"
						),
						"dependency"  => array( 'element' => 'source_overlay' , 'value'=> array('2', '3') ),
					),

					array(
						'id'            => 'source_overlay_gloss',
						'name'          => 'Enable Gloss Overlay',
						'description'   => 'Display a gloss over the background',
						'type'          => 'toggle2',
						'std'           => '',
						'value'         => '1'
					),

				),
			),


			'help' => znpb_get_helptab( array(
				'video'   => 'http://support.hogash.com/kallyas-videos/#n7OFxV7XIkc',
				'docs'    => 'http://support.hogash.com/documentation/media-container/',
				'copy'    => $uid,
				'general' => true,
			)),

		);
		return $options;

	}
}
