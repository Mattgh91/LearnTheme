<?php if(! defined('ABSPATH')){ return; }
/**
 * This file is autoloaded by framework/addons/plugins/class-plugins.php
 */

$plugins = array(
	array (
		'name'               => 'Revolution Slider',
		'slug'               => 'revslider',
		'source'             => 'http://kallyas.net/0f5afa285cb7d34ee6fa41b23bd51e61/a22ce5d69768192ed534fcb4335c9d195241.zip',
		'source_type'		 => 'external',
		'required'           => false,
		'version'            => '5.2.4.1',
		'force_activation'   => false,
		'force_deactivation' => false,
		'external_url'       => '',
		'z_plugin_icon'		 => THEME_BASE_URI . '/template_helpers/plugins/rev_slider.png',
		'z_plugin_author'        => 'themepunch',
		'z_plugin_description'       => 'Slider Revolution is not only for "Sliders". You can now build a beautiful one-page web presence with absolutely no coding knowledge required.',
		'zn_plugin'           => 'revslider/revslider.php',
	),
	/*
	 * Since the plugin is not maintained anymore by its author and we don't provide full support
	 * for its development, then there's no need to have it marked as a MUST HAVE installed plugin.
	*/
	array (
		'name'               => 'Cute Slider',
		'slug'               => 'CuteSlider',
		'source'             => THEME_BASE_URI . '/template_helpers/plugins/cutesliderwp.zip',
		'source_type'		 => 'external',
		'required'           => false,
		'version'            => '1.1.16',
		'force_activation'   => false,
		'force_deactivation' => false,
		'external_url'       => '',
		'z_plugin_icon'      => THEME_BASE_URI . '/template_helpers/plugins/cute_slider.png',
		'z_plugin_author'    => 'Averta and Kreatura Media',
		'z_plugin_description'=> 'Cute Slider is a unique and easy to use slider with awesome 3D and 2D transition effects, captions, 4 ready to use templates, video (youtube and vimeo) support and more impressive features',
		'zn_plugin'           => 'CuteSlider/cuteslider.php',
	),
	array(
		'name'      => 'WooCommerce',
		'slug'      => 'woocommerce',
		'required'  => false,
		'version' 	=> '2.5.5',
		'z_plugin_icon'		 => THEME_BASE_URI . '/template_helpers/plugins/woocommerce.png',
		'z_plugin_author'        => 'woothemes',
		'z_plugin_description'       => 'WooCommerce is a free eCommerce plugin that allows you to sell anything, beautifully. Built to integrate seamlessly with WordPress, WooCommerce is the world’s favorite eCommerce solution that gives both store owners and developers complete control.',
	),
	array (
		'name'               => 'PostLove - Content like plugin',
		'slug'               => 'hogash-post-love',
		'source'             => THEME_BASE_URI . '/template_helpers/plugins/hogash-post-love.zip',
		'source_type'		 => 'external',
		'required'           => false,
		'version'            => '1.0.0',
		'force_activation'   => false,
		'force_deactivation' => false,
		'external_url'       => '',
		'z_plugin_icon'      => THEME_BASE_URI . '/template_helpers/plugins/postlove.png',
		'z_plugin_author'    => 'Hogash',
		'z_plugin_description'=> 'Hogash Post Love brings article loving to your site. You users or visitors would be able to share their interest in your articles by loving them.',
		'zn_plugin'           => 'hogash-post-love/hogash-post-love.php',
	),
);

