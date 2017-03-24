<?php if(! defined('ABSPATH')){ return; }
/*
 Name: Video Box
 Description: Create and display a Video Box element
 Class: TH_VideoBox
 Category: content, media
 Level: 3
*/
/**
 * Class TH_VideoBox
 *
 * Create and display a Video Box element
 *
 * @package  Kallyas
 * @category Page Builder
 * @author   Team Hogash
 * @since    4.0.0
 */
class TH_VideoBox extends ZnElements
{
	public static function getName(){
		return __( "Video Box", 'zn_framework' );
	}

	/**
	 * This method is used to display the output of the element.
	 * @return void
	 */
	function element()
	{
		$options = $this->data['options'];

		$classes=array();
		$classes[] = $this->data['uid'];
		$classes[] = zn_get_element_classes($options);

		$attributes = zn_get_element_attributes($options);

		?>

			<?php
			if ( ! empty ( $options['vb_video_image'] ) && ! empty ( $options['vb_video_url'] ) ) {
				echo '<div class="adbox video '.implode(' ', $classes).'" '.$attributes.'>';
					$image = vt_resize( '', $options['vb_video_image'], '', '', true );
					echo '<img class="adbox-img" src="' . $image['url'] . '" alt="'. ZngetImageAltFromUrl( $options['vb_video_image'] ) .'" title="'.ZngetImageTitleFromUrl( $options['vb_video_image'] ).'">';
					echo '<div class="video_trigger_wrapper">';
						echo '<div class="adbox_container">';
							echo '<a class="playVideo" data-lightbox="iframe" href="' . $options['vb_video_url'] . '"></a>';
							if( isset($options['vb_video_title']) && !empty($options['vb_video_title'])  ){
								echo '<h5 class="adbox-title kl-font-alt">' . $options['vb_video_title'] . '</h5>';
							}
						echo '</div>';
					echo '</div>'; // close video_trigger_container
				echo '</div>'; // close adbox
			}
			else {
				if ( ! empty ( $options['vb_video_url'] ) ) {
					if ( ! empty( $options['vb_video_title'] ) ) {
						echo '<h4 class="m_title text-custom">' . $options['vb_video_title'] . '</h4>';
					}
					echo get_video_from_link( $options['vb_video_url'], '', '', '' );
				}
			}
			?>
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
						"name"        => __( "Video URL", 'zn_framework' ),
						"description" => __( "Please enter a link to your desired video ( Youtube and Vimeo ).", 'zn_framework' ),
						"id"          => "vb_video_url",
						"std"         => "",
						"type"        => "text"
					),
					array (
						"name"        => __( "Image", 'zn_framework' ),
						"description" => __( "Please select an image that you want to display.If
											 no image is selected, the video will be shown directly.", 'zn_framework' ),
						"id"          => "vb_video_image",
						"std"         => "",
						"type"        => "media"
					),
					array (
						"name"        => __( "Video title", 'zn_framework' ),
						"description" => __( "Please enter a title that will appear over the
											play icon. This will only be shown if you select an image.", 'zn_framework' ),
						"id"          => "vb_video_title",
						"std"         => "",
						"type"        => "text"
					),
				),
			),


			'help' => znpb_get_helptab( array(
				'video'   => 'http://support.hogash.com/kallyas-videos/#flIrJ1rcYlo',
				'docs'    => 'http://support.hogash.com/documentation/video-box/',
				'copy'    => $uid,
				'general' => true,
			)),

		);
		return $options;
	}
}
