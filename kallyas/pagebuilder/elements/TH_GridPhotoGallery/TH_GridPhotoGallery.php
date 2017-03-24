<?php if (!defined('ABSPATH')) { return; }
/*
 Name: Grid Photo Gallery
 Description: Create and display a Grid based Photo Gallery element
 Class: TH_GridPhotoGallery
 Category: content
 Level: 3
*/
/**
 * Class TH_GridPhotoGallery
 *
 * Create and display a Grid Photo Gallery element
 *
 * @package  Kallyas
 * @category Page Builder
 * @author   Team Hogash
 * @since    4.0.0
 */
class TH_GridPhotoGallery extends ZnElements
{
	public static function getName(){
		return __( "Grid Photo Gallery", 'zn_framework' );
	}

	/**
	 * Load dependant resources
	 */
	function scripts(){
		$pg_layout_type = $this->opt('pg_layout_type','masonry');

		if($pg_layout_type == 'none') return;

		wp_enqueue_script( 'isotope');

		if( $pg_layout_type == 'packery' ){
			wp_enqueue_script( 'isotope-packery', THEME_BASE_URI . '/addons/isotope-addons/packery-mode.pkgd.min.js',  array ( 'jquery', 'isotope' ), ZN_FW_VERSION, true );
		}

	}

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){
		$css = '';
		$uid = $this->data['uid'];

		if($this->opt('pg_img_height','square') == 'custom'){

			// Custom Ratio
			$pg_img_customratio = $this->opt('pg_img_customratio','100');
			if( $pg_img_customratio != '100'){
				$sel = '.'.$uid.'.gridPhotoGallery--ratio-custom ';
				$css .= $sel.'.gridPhotoGalleryItem--w1 .gridPhotoGalleryItem--h1, '.$sel.'.gridPhotoGalleryItem--w2 .gridPhotoGalleryItem--h2 {padding-bottom:'.$pg_img_customratio.'% }';
				$css .= $sel.'.gridPhotoGalleryItem--w2 .gridPhotoGalleryItem--h1 {padding-bottom:'.($pg_img_customratio/2).'% }';
				$css .= $sel.'.gridPhotoGalleryItem--w1 .gridPhotoGalleryItem--h2 {padding-bottom:'.($pg_img_customratio*2).'% }';
			}
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

		$num_cols = $this->opt('pg_num_cols', 4);
		$gridGallery = $this->opt('single_photo_gallery');
		$pg_layout_type = $this->opt('pg_layout_type','masonry');

		$classes = array();
		$classes[] = $uid = $this->data['uid'];
		$classes[] = 'gridPhotoGallery mfp-gallery mfp-gallery--misc';
		$classes[] = 'gridPhotoGallery--ratio-' . $this->opt('pg_img_height', 'square');
		$classes[] = 'gridPhotoGallery--cols-' . $num_cols;
		$classes[] = 'gpg-gutter--' . $this->opt('pg_gutter_size','5');
		$classes[] = $pg_layout_type == 'none' ? 'stop-isotope' : '';
		$classes[] = zn_get_element_classes($options);

		$attributes = zn_get_element_attributes($options);

		?>
			<div class=" <?php echo implode(' ', $classes); ?>" data-cols="<?php echo $num_cols; ?>" data-layout="<?php echo $pg_layout_type; ?>" <?php echo $attributes; ?>>

				<div class="gridPhotoGallery__item gridPhotoGallery__item--sizer"></div>
				<?php
				if ( $gridGallery && is_array($gridGallery) ) {

					foreach ($gridGallery as $image) {

						$itemWidth = $image['spg_width'] ? $image['spg_width'] : 1;
						$itemHeight = $image['spg_height'] ? $image['spg_height'] : 1;
						$link_start = '';
						$img = $image['spg_image'];

						echo '<div class="gridPhotoGallery__item gridPhotoGalleryItem--w'.$itemWidth.' ">';

							// If Image
							if( isset($img) && !empty($img) ){
								$link_start = 'class="gridPhotoGalleryItem--h'.$itemHeight.' gridPhotoGallery__link kl-fontafter-alt" data-lightbox="mfp" data-mfp="image" href="' . $img . '"';
								$icon = '<i class="kl-icon glyphicon glyphicon-search circled-icon ci-'.$this->opt('pg_loupe_size','large').'"></i>';
							}
							// If Video
							if(isset($image['spg_video']) && !empty($image['spg_video'])){
								$link_start = 'class="gridPhotoGalleryItem--h'.$itemHeight.' gridPhotoGallery__link kl-fontafter-alt" data-lightbox="mfp" data-mfp="iframe" href="' . $image['spg_video'] . '"';
								$icon = '<i class="kl-icon glyphicon glyphicon-play circled-icon ci-'.$this->opt('pg_loupe_size','large').'"></i>';
							}

							echo '<a title="' . $image['spg_title'] . '" '.$link_start.' >';

								if( isset($img) && !empty($img) ){

									$resize_image = vt_resize( '', $img, (int)$this->opt('spg_img_width',''), '', true );

									$alt   = ZngetImageAltFromUrl( $img, true );
									$title = ZngetImageTitleFromUrl( $img, true );

									echo '<img class="gridPhotoGallery__img cover-fit-img" src="'.$resize_image['url'].'" '.$alt.' '.$title.'>';
									echo $icon;
								}

							echo '</a>';

						echo '</div>';

					}
				}
				?>
			</div>
	<?php
	}

	/**
	 * This method is used to retrieve the configurable options of the element.
	 * @return array The list of options that compose the element and then passed as the argument for the render() function
	 */
	function options()
	{
		$extra_options = array(
			"name" => __("Images", 'zn_framework'),
			"description" => __("Here you can add your desired images.", 'zn_framework'),
			"id" => "single_photo_gallery",
			"std" => "",
			"type" => "group",
			"add_text" => __("Image", 'zn_framework'),
			"remove_text" => __("Image", 'zn_framework'),
			"group_title" => "",
			"group_sortable" => true,
			"element_title" => "spg_title",
			"element_img"  => 'spg_image',
			"subelements" => array(
				array(
					"name" => __("Title", 'zn_framework'),
					"description" => __("Please enter a title for this image.", 'zn_framework'),
					"id" => "spg_title",
					"std" => "",
					"type" => "text"
				),
				array(
					"name" => __("Image", 'zn_framework'),
					"description" => __("Please select an image. This is mandatory", 'zn_framework'),
					"id" => "spg_image",
					"std" => "",
					"type" => "media"
				),
				array(
					"name" => __("Video URL", 'zn_framework'),
					"description" => __("Please enter the URL for your video. This video will appear when the user clicks on the image", 'zn_framework'),
					"id" => "spg_video",
					"std" => "",
					"type" => "text"
				),
				array(
					"name" => __("Item Width", 'zn_framework'),
					"description" => __("Select the width of the element.", 'zn_framework'),
					"id" => "spg_width",
					"std" => "1",
					"type" => "select",
					"options" => array(
						'1' => __('1x', 'zn_framework'),
						'2' => __('2x', 'zn_framework')
					)
				),
				array(
					"name" => __("Item Height", 'zn_framework'),
					"description" => __("Select the height of the element.", 'zn_framework'),
					"id" => "spg_height",
					"std" => "1",
					"type" => "select",
					"options" => array(
						'1' => __('1x', 'zn_framework'),
						'2' => __('2x', 'zn_framework')
					)
				),

			)
		);

		$uid = $this->data['uid'];

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => 'General options',
				'options' => array(
					array(
						"name" => __("Number of columns", 'zn_framework'),
						"description" => __("Select the desired number of columns for the grid.", 'zn_framework'),
						"id" => "pg_num_cols",
						"std" => "4",
						"type" => "select",
						"options" => array(
							'1' => __('1', 'zn_framework'),
							'2' => __('2', 'zn_framework'),
							'3' => __('3', 'zn_framework'),
							'4' => __('4', 'zn_framework'),
							'5' => __('5', 'zn_framework'),
							'6' => __('6', 'zn_framework')
						)
					),
					array(
						"name" => __("Images Height Ratio", 'zn_framework'),
						"description" => __("Select the desired image height ratio.", 'zn_framework'),
						"id" => "pg_img_height",
						"std" => "square",
						"type" => "select",
						"options" => array(
							'short' => __('Shorter Ratio', 'zn_framework'),
							'square' => __('Square Ratio', 'zn_framework'),
							'tall' => __('Taller Ratio', 'zn_framework'),
							'custom' => __('Custom Ratio', 'zn_framework'),
						)
					),
					array(
						"name" => __("Gutter size", 'zn_framework'),
						"description" => __("Please select the distance between images.", 'zn_framework'),
						"id" => "pg_gutter_size",
						"std" => "5",
						"type" => "select",
						"options" => array(
							'0' => __('0 gutter size', 'zn_framework'),
							'3' => __('6px gutter size', 'zn_framework'),
							'5' => __('10px gutter size', 'zn_framework'),
							'10' => __('20px gutter size', 'zn_framework'),
							'15' => __('30px gutter size', 'zn_framework'),
						),
						'live' => array(
							'type'        => 'class',
							'css_class' => '.'.$uid,
							'val_prepend' => 'gpg-gutter--',
						),
					),
					array(
						"name" => __("Custom Ratio", 'zn_framework'),
						"description" => __("Select the custom Ratio (1% to 200%).", 'zn_framework'),
						"id" => "pg_img_customratio",
						"std" => "100",
						"type" => "slider",
						'class'       => 'zn_full',
						'helpers'     => array(
							'min' => '1',
							'max' => '200',
							'step' => '1'
						),
						"dependency"  => array( 'element' => 'pg_img_height' , 'value'=> array('custom') ),
					),
					array(
						"name" => __("Isotope Layout Engine", 'zn_framework'),
						"description" => __("Select the isotope's engine that arranges the gallery.", 'zn_framework'),
						"id" => "pg_layout_type",
						"std" => "masonry",
						"type" => "select",
						"options" => array(
							'none' => __('None', 'zn_framework'),
							'masonry' => __('Masonry (built-in)', 'zn_framework'),
							'packery' => __('Packery (performance cost, automatically fills gaps). Need page reload!', 'zn_framework'),
						)
					),

					array(
					    "name" => __("Images Width", 'zn_framework'),
					    "description" => __("This option will resize and cache the images in your grid photo gallery items. Not specifying a width will result in full-width images that would slow down the loading of your page. ", 'zn_framework'),
					    "id" => "spg_img_width",
					    "std" => '',
					    "type" => "text",
					    "placeholder" => "eg: 600px",
					),

					array(
						"name" => __("Hover Loupe size", 'zn_framework'),
						"description" => __("Please select the hover's loupe icon size.", 'zn_framework'),
						"id" => "pg_loupe_size",
						"std" => "large",
						"type" => "select",
						"options" => array(
							'xsmall' => __('Extra small', 'zn_framework'),
							'small' => __('Small', 'zn_framework'),
							'medium' => __('Medium', 'zn_framework'),
							'large' => __('Large', 'zn_framework'),
						),
					),
				),
			),

			'images' => array(
				'title' => 'Images',
				'options' => array(
					$extra_options,
				)
			),

			'help' => znpb_get_helptab( array(
				'video'   => 'http://support.hogash.com/kallyas-videos/#Kj7XG3Vm-HQ',
				'docs'    => 'http://support.hogash.com/documentation/grid-based-photo-gallery/',
				'copy'    => $uid,
				'general' => true,
			)),

		);
		return $options;
	}
}
