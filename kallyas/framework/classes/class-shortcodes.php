<?php if(! defined('ABSPATH')){ return; }
/*--------------------------------------------------------------------------------------------------

	File: class-shortcodes.php

	Description: This file contains all the shortcodes
	Please be carefull when editing this file

--------------------------------------------------------------------------------------------------*/

class ZnShortcodes {


	function __construct() {

		$shortcode_list = array(
			'alert_box' => '[alert_box type="success" dismissable="true"]CONTENT HERE[/alert_box]'
		);
		
		foreach ($shortcode_list as $key => $value) {
			add_shortcode( $key, array(  $this , 'alert_box' ) );
		}

	}


	/**
	 * Alert Boxes
	 *
	 * @access public
	 * TYPE : success , info , warning , danger
	 * dismissable : true , false
	 */
	function alert_box( $atts, $content="" ) {

		extract( shortcode_atts( array(
			'type' => 'success',
			'dismissable' => true
		), $atts ) );

		if( $dismissable ) {
			$output = '<div class="alert alert-'.$type.' alert-dismissable">';
			$output .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
			$output .= $content;
			$output .= '</div>';
		}
		else {
			$output = '<div class="alert alert-'.$type.'">'.$content.'</div>';
		}

		return $output;
	}

}
