<?php if(! defined('ABSPATH')){ return; }
/*
 Name: Image Box
 Description: Create and display an Image Box element
 Class: TH_ImageBox
 Category: content, media
 Level: 3
*/
	/**
	 * Class TH_ImageBox
	 *
	 * Create and display an Image Box element
	 *
	 * @package  Kallyas
	 * @category Page Builder
	 * @author   Team Hogash
	 * @since    3.8.0
	 */
	class TH_ImageBox extends ZnElements
	{
		public static function getName(){
			return __( "Image Box", 'zn_framework' );
		}


		/**
		 * Output the inline css to head or after the element in case it is loaded via ajax
		 */
		function css(){

			$uid = $this->data['uid'];
			$css = '';

			//Bottom Margin (mostly for reset it)
			$bottom_margin = $this->opt('ib_bottom_margin',30);
			$bmargin = $bottom_margin != '30' ? 'margin-bottom:'.$bottom_margin.'px;' : '';

			if (!empty($bmargin) )
			{
				$css .= '.'.$uid.'{'.$bmargin.'}';
			}

			// Title Styles
			$title_styles = '';
			$title_typo = $this->opt('title_typo');
			if( is_array($title_typo) && !empty($title_typo) ){
				foreach ($title_typo as $key => $value) {
					if(!empty($value)){
						$title_styles .= $key.':'. $value.';';
					}
				}
				if(!empty($title_styles)){
					$css .= '.'.$uid.'.image-boxes .image-boxes-title{'.$title_styles.'}';
				}
			}

			// Image height
			if($this->opt('image_box_imgfit','no') == 'cover-fit-img'){
				$height = $this->opt('image_box_height','280');
				if(!empty($height)){
					$css .= '.'.$uid.'.image-boxes .image-boxes-img-wrapper{height:'.$height.'px}';
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

			$uid = $this->data['uid'];

			$custom_classes = zn_get_element_classes($options);
			$attributes = zn_get_element_attributes($options);

			$slide_image = $this->opt( 'image_box_image', false );

			$image      = '';
			$title      = '';
			$text       = '';
			$link_text  = '';

			$zn_style = '';

			// Title
			if ( ! empty ( $options['image_box_title'] ) ) {
				$title = '<h3 class="m_title m_title_ext text-custom imgboxes-title image-boxes-title">' . $options['image_box_title'] . '</h3>';
			}

			// TEXT
			if ( ! empty ( $options['image_box_text'] ) ) {
				$text = $options['image_box_text'];
			}

			// READ MORE TEXT
			if ( ! empty ( $options['image_box_link_text'] ) ) {
				$link_text = '<span class="kl-main-bgcolor image-boxes-readon u-trans-all-2s">' . $options['image_box_link_text'] . '</span>';
			}

			// Check to see if we have an image
			if ( $slide_image ) {

				$saved_alt   = ZngetImageAltFromUrl( $slide_image, true );
				$saved_title = ZngetImageTitleFromUrl( $slide_image, true );

				if ( is_array( $slide_image ) ) {

					if ( $saved_image = $slide_image['image'] ) {

						// Image alt
						if ( ! empty( $slide_image['alt'] ) ) {
							$saved_alt = 'alt="' . $slide_image['alt'] . '"';
						}

						// Image title
						if ( ! empty( $slide_image['title'] ) ) {
							$saved_title = 'title="' . $slide_image['title'] . '"';
						}
					}
				}
				else {
					$saved_image = $slide_image;
				}
			}

			$fitimg = ($this->opt('image_box_imgfit','no') != 'no' ? 'cover-fit-img' : '');
			if(!empty($fitimg)){
				$zn_style  .= ' image-boxes-cover-fit-img';
			}

			// Display the element based on what style is chosen

/**
 * --------------------------------
 * STYLE 2 - IMAGE IS ON THE RIGHT
 * --------------------------------
 */
			if ( ! empty ( $options['image_box_style'] ) && $options['image_box_style'] == 'style2' ) {
				$zn_style .= ' imgboxes_style1 zn_ib_style2';

				// IMAGE
				if ( ! empty ( $saved_image ) ) {
					$image = vt_resize( '', $saved_image, '220', '156', true );
					$image = '<div class="image-boxes-img-wrapper"><img class="image-boxes-img '.$fitimg.'" src="' . $image['url'] . '" width="' . $image['width'] . '" height="' . $image['height'] . '" ' . $saved_alt . ' ' . $saved_title . ' /></div>';
				}

				// Reset link's bottom margin if no title or text under the image link
				$reset_margin = !$text ? 'u-mb-0' : '';

				$image_box_link = zn_extract_link(
					$this->opt('image_box_link',''),
					'image-boxes-link hoverBorder alignright '.$reset_margin,
					'',
					'<span class="zn_image_box_cont image-boxes-holder alignright '.$reset_margin.'">',
					'</span>'
				);

				echo '<div class="box image-boxes image-boxes--2 ' . $zn_style . ' '.$uid.' '.$custom_classes.'" '.$attributes.'>';

					echo $title;

					if(!empty($image)){
						echo $image_box_link['start'];
							echo $image;
						echo $image_box_link['end'];
					}

					echo '<div class="image-boxes-text">'.$text.'</div>';

				echo '</div>';
			}

/**
 * --------------------------------
 * STYLE 3 - CONTENT IS OVER IMAGE
 * --------------------------------
 */
			elseif ( ! empty ( $options['image_box_style'] ) && $options['image_box_style'] == 'style3' ) {
				$zn_style .= ' imgboxes_style2';
				// IMAGE
				if ( ! empty ( $saved_image ) ) {
					$image = vt_resize( '', $saved_image, '', '', true );
					$image = '<div class="image-boxes-img-wrapper"><img class="image-boxes-img '.$fitimg.' sliding-details-img" src="' . $image['url'] . '" width="' . $image['width'] . '" height="' . $image['height'] . '" ' . $saved_alt . ' ' . $saved_title . ' /></div>';
				}

				$image_box_link = zn_extract_link(
					$this->opt('image_box_link',''),
					'image-boxes-link slidingDetails sliding-details u-mb-0',
					'',
					'<span class="image-boxes-holder sliding-details u-mb-0">',
					'</span>'
				);

				// Title
				if ( ! empty ( $options['image_box_title'] ) ) {
					$title = '<h4 class="image-boxes-title sliding-details-title">' . $options['image_box_title'] . '</h4>';
				}

				echo '<div class="box image-boxes image-boxes--3 ' . $zn_style . ' '.$uid.' '.$custom_classes.'" '.$attributes.'>';

				if(!empty($image)){
					echo $image_box_link['start'];;
						echo $image;
						echo '<div class="details sliding-details-content">';
							echo $title;
							echo $text;
						echo '</div>';
					echo $image_box_link['end'];;
				}

				echo '</div>';
			}

			//
/**
 * --------------------------------
 * STYLE 4 - IMAGE WITH READ MORE BUTTON OVER IT
 * --------------------------------
 */
			elseif ( ! empty ( $options['image_box_style'] ) && $options['image_box_style'] == 'style4' ) {
				$zn_style  .= ' imgboxes_style4';

				// IMAGE
				if ( ! empty ( $saved_image ) ) {

					$image = vt_resize( '', $saved_image, '', '', true );
					$image = '<div class="image-boxes-img-wrapper"><img src="' . $image['url'] . '" width="' . $image['width'] . '" height="' . $image['height'] . '" ' . $saved_alt . ' ' . $saved_title . ' class="img-responsive imgbox_image image-boxes-img '.$fitimg.'" /></div>';
				}

				// Add a call to action button
				if( $image_box_link_text = $this->opt('image_box_link_text','') ){
					$image_box_link_btn = zn_extract_link( $this->opt('image_box_link',''), 'btn btn-fullcolor btn-sm image-boxes-button');
					$link_text = $image_box_link_btn['start'] . $image_box_link_text . $image_box_link_btn['end'];
				}

				// Reset link's bottom margin if no title or text under the image link
				$reset_margin = !$text && !$link_text ? 'u-mb-0' : '';

				$image_box_link = zn_extract_link(
					$this->opt('image_box_link',''),
					'imgboxes4_link imgboxes-wrapper image-boxes-link '.$reset_margin,
					'',
					'<div class="imgboxes-wrapper image-boxes-holder '.$reset_margin.'">',
					'</div>'
				);

				$image_box_title_style = isset( $options['image_box_title_style'] ) && ! empty( $options['image_box_title_style']) ? $options['image_box_title_style'] : 'title_style_center';

				echo '<div class="box image-boxes image-boxes--4 ' . $zn_style . ' kl-'. $image_box_title_style .' '.$uid.' '.$custom_classes.'" '.$attributes.'>';

					if(!empty($image)){
						echo $image_box_link['start'];

							echo $image;
							echo '<span class="imgboxes-border-helper image-boxes-border-helper"></span>';

							echo $title;

						echo $image_box_link['end'];
					}

					if($text){
						echo '<div class="image-boxes-text"><p>'.$text.'</p></div>';
					}

					echo $link_text;

				echo '</div>';
			}

/**
 * --------------------------------
 * STYLE 1 - IMAGE WITH READ MORE BUTTON OVER IT
 * --------------------------------
 */
			else {
				$zn_style  .= ' imgboxes_style1';

				// IMAGE
				if ( ! empty ( $saved_image ) ) {
					$image = vt_resize( '', $saved_image, '', '', true );
					$image = '<div class="image-boxes-img-wrapper"><img class="image-boxes-img '.$fitimg.'" src="' . $image['url'] . '" width="' . $image['width'] . '" height="' . $image['height'] . '" ' . $saved_alt . ' ' . $saved_title . ' /></div>';
				}

				// Reset link's bottom margin if no title or text under the image link
				$reset_margin = !$text && !$title ? 'u-mb-0' : '';

				$image_box_link = zn_extract_link(
					$this->opt('image_box_link',''),
					'hoverBorder image-boxes-link '.$reset_margin,
					'',
					'<span class="image-boxes-holder '.$reset_margin.'">',
					'</span>'
				);

				echo '<div class="box image-boxes image-boxes--1 ' . $zn_style . ' '.$uid.' '.$custom_classes.'" '.$attributes.'>';

					if(!empty($image)){
						echo $image_box_link['start'];
							echo $image;
							echo $link_text;
						echo $image_box_link['end'];
					}
					echo $title;

					if($text){
						echo '<div class="image-boxes-text">'.$text.'</div>';
					}

				echo '</div>';
			}



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
							"name"        => __( "Image", 'zn_framework' ),
							"description" => __( "Please select an image that will appear above the title.", 'zn_framework' ),
							"id"          => "image_box_image",
							"std"         => "",
							"type"        => "media",
							"alt"         => true,
						),

						array (
							"name"        => __( "Force Image Fill?", 'zn_framework' ),
							"description" => __( "Please select how to fit the image.", 'zn_framework' ),
							"id"          => "image_box_imgfit",
							"std"         => "no",
							'options'     => array(
								'cover-fit-img' => 'Yes',
								'no' => 'No',
							),
							"type"        => "select",
							'live' => array(
								'multiple' => array(
									array(
									   'type'        => 'class',
									   'css_class' => '.'.$uid,
									   'val_prepend' => 'image-boxes-',
									),
									array(
									   'type'        => 'class',
									   'css_class' => '.'.$uid.' .image-boxes-img',
									),
								),
							),
						),

						array (
							"name"        => __( "Image Height", 'zn_framework' ),
							"description" => __( "Please enter your desired height in pixels for this image.", 'zn_framework' ),
							"id"          => "image_box_height",
							"std"         => "280",
							"type" 		  => "slider",
							'class'       => 'zn_full',
							'helpers'     => array(
								'min' => '10',
								'max' => '1000',
								'step' => '1'
							),
							'live' => array(
								'type'      => 'css',
								'css_class' => '.'.$uid.'.image-boxes-cover-fit-img .image-boxes-img-wrapper',
								'css_rule'  => 'height',
								'unit'      => 'px'
							),
							"dependency"  => array( 'element' => 'image_box_imgfit' , 'value'=> array('cover-fit-img') )
						),

						array (
							"name"        => __( "Link text", 'zn_framework' ),
							"description" => __( "Enter a that will appear as link over the image.", 'zn_framework' ),
							"id"          => "image_box_link_text",
							"std"         => "",
							"type"        => "text",
						),
						array (
							"name"        => __( "Image Box link", 'zn_framework' ),
							"description" => __( "Please choose the link you want to use for your Image box button.", 'zn_framework' ),
							"id"          => "image_box_link",
							"std"         => "",
							"type"        => "link",
							"options"     => zn_get_link_targets(),
						),
						array (
							"name"        => __( "Image Box Title", 'zn_framework' ),
							"description" => __( "Enter a title for your Image box", 'zn_framework' ),
							"id"          => "image_box_title",
							"std"         => "",
							"type"        => "text",
						),
						array (
							"name"        => __( "Image Box Text", 'zn_framework' ),
							"description" => __("Please enter a text that will appear inside your action Image button.", 'zn_framework' ),
							"id"          => "image_box_text",
							"std"         => "",
							"type"        => "textarea",
						),
					),
				),

				'style' => array(
					'title' => 'Style options',
					'options' => array(

						array (
							"name"        => __( "Image Box Style", 'zn_framework' ),
							"description" => __( "Please select the style you want to use.", 'zn_framework' ),
							"id"          => "image_box_style",
							"std"         => "imgboxes_style1",
							"options"     => array (
								'imgboxes_style1' => __( 'Style 1', 'zn_framework' ),
								'style2'          => __( 'Style 2', 'zn_framework' ),
								'style3'          => __( 'Style 3', 'zn_framework' ),
								'style4'          => __( 'Style 4 (since v4.0)', 'zn_framework' )
							),
							"type"        => "select",
						),
						array (
							"name"        => __( "Image Box Title Style", 'zn_framework' ),
							"description" => __( "Please select the style you want to use.", 'zn_framework' ),
							"id"          => "image_box_title_style",
							"std"         => "title_style_center",
							"options"     => array (
								'title_style_center' => __( 'Title Centered', 'zn_framework' ),
								'title_style_left'   => __( 'Title Left', 'zn_framework' ),
								'title_style_bottom' => __( 'Title Left with border bottom', 'zn_framework' )
							),
							"type"        => "select",
							 "dependency"  => array( 'element' => 'image_box_style' , 'value'=> array('style4') ),
						),

						array (
							"name"        => __( "Title settings", 'zn_framework' ),
							"description" => __( "Specify the typography properties for the title.", 'zn_framework' ),
							"id"          => "title_typo",
							"std"         => '',
							'supports'   => array( 'size', 'font', 'style', 'line', 'color', 'weight' ),
							"type"        => "font",
						),

						array(
							'id'          => 'ib_bottom_margin',
							'name'        => 'Bottom margin',
							'description' => 'Select the bottom margin ( in pixels ) for this element.',
							'type'        => 'slider',
							'std'         => '30',
							'class'       => 'zn_full',
							'helpers'     => array(
								'min' => '0',
								'max' => '100',
								'step' => '5'
							),
							'live' => array(
								'type'      => 'css',
								'css_class' => '.'.$uid,
								'css_rule'  => 'margin-bottom',
								'unit'      => 'px'
							)
						),
					),
				),


				'help' => znpb_get_helptab( array(
					'video'   => 'http://support.hogash.com/kallyas-videos/#aKNFr7BfB5k',
					'docs'    => 'http://support.hogash.com/documentation/image-box/',
					'copy'    => $uid,
					'general' => true,
				)),

			);
			return $options;
		}
	}
