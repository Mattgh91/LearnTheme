<?php if(! defined('ABSPATH')){ return; }
/*
 Name: Spacer Element
 Description: Create and display a Spacer Element element
 Class: TH_SpacerElement
 Category: content
 Level: 3
*/
/**
 * Class TH_SpacerElement
 *
 * Create and display a Spacer Element element
 *
 * @package  Kallyas
 * @category Page Builder
 * @author   Team Hogash
 * @since    4.0.0
 */
class TH_SpacerElement extends ZnElements
{
	public static function getName(){
		return __( "Spacer Element", 'zn_framework' );
	}

	/**
	 * This method is used to display the output of the element.
	 *
	 * @return void
	 */
	function element()
	{
		$options = $this->data['options'];
		$height = 30; // the default value

		if(isset($options['spacer_height']) && !empty($options['spacer_height'])){
			$height = absint($options['spacer_height']);
		}

		$hide = array();
		$hide[] = $this->opt('spacer_hide_lg','0') == 1 ? 'hidden-lg' : '';
		$hide[] = $this->opt('spacer_hide_md','0') == 1 ? 'hidden-md' : '';
		$hide[] = $this->opt('spacer_hide_sm','0') == 1 ? 'hidden-sm' : '';
		$hide[] = $this->opt('spacer_hide_xs','0') == 1 ? 'hidden-xs' : '';

		echo '<div class="th-spacer clearfix '.$this->data['uid'].' '. implode(' ', $hide ) .' '.zn_get_element_classes($this->data['options']).'" style="height: '.$height.'px;"></div>';
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
						"name"        => __( "Set height", 'zn_framework' ),
						"description" => __( "Set the height, in pixels, for this element", 'zn_framework' ),
						"id"          => "spacer_height",
						"std"         => "30",
						"type"        => "slider",
						"helpers"     => array (
							"step" => "5",
							"min" => "0",
							"max" => "600"
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$this->data['uid'],
							'css_rule'  => 'height',
							'unit'      => 'px'
						)
					),

					array (
						"name"        => __( "Hide on Large Breakpoint", 'zn_framework' ),
						"description" => __( "Bigger than 1200px", 'zn_framework' ),
						"id"          => "spacer_hide_lg",
						"std"         => "0",
						"value"       => "1",
						"type"        => "toggle2",
					),
					array (
						"name"        => __( "Hide on Medium Breakpoint", 'zn_framework' ),
						"description" => __( "Bigger than 992px and smaller than 1199px", 'zn_framework' ),
						"id"          => "spacer_hide_md",
						"std"         => "0",
						"value"       => "1",
						"type"        => "toggle2",
					),
					array (
						"name"        => __( "Hide on Small Breakpoint ", 'zn_framework' ),
						"description" => __( "Bigger than 768px and smaller than 991px", 'zn_framework' ),
						"id"          => "spacer_hide_sm",
						"std"         => "0",
						"value"       => "1",
						"type"        => "toggle2",
					),
					array (
						"name"        => __( "Hide on Extra small Breakpoint ", 'zn_framework' ),
						"description" => __( "Smaller than 767px", 'zn_framework' ),
						"id"          => "spacer_hide_xs",
						"std"         => "0",
						"value"       => "1",
						"type"        => "toggle2",
					),
				),
			),

			'help' => znpb_get_helptab( array(
				'video'   => 'http://support.hogash.com/kallyas-videos/#O03njJEtSNQ',
				'copy'    => $uid,
				'general' => true,
			)),

		);
		return $options;
	}
}
