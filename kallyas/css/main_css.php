<?php

/**
 * Scheme for main components load
 * @enabled: Flag if that part should be loaded
 * @path: the path of the filename
 * @filename: In filename, .css extension is not added so that we can also make a check for .php filename (for images load)
 */

$css = array(

	// 'bootstrap' => array(
	// 	'enabled' => true,
	// 	'path' => '/css/',
	// 	'filename' => 'bootstrap.min',
	// ),

	'main' => array(
		'enabled' => true,
		'path' => '/css/',
		'filename' => 'template',
	),

	'general' => array(
		'enabled' => true,
		'path' => '/css/',
		'filename' => 'general',
	),

	'header_general' => array(
		'enabled' => true,
		'path' => '/css/components/header/',
		'filename' => 'header-general',
	),
	'header_style' => array(
		'enabled' => true,
		'path' => '/css/components/header/',
		'filename' => 'header-'.$header_style,
	),
	'header_navs' => array(
		'enabled' => true,
		'path' => '/css/components/header/',
		'filename' => 'header-navs',
	),
	'header_cta' => array(
		'enabled' => zget_option( 'head_show_cta', 'general_options', false, 'no' ) == 'yes' ? true : false,
		'path' => '/css/components/header/',
		'filename' => 'header-cta',
	),
	'header_search' => array(
		'enabled' => zget_option( 'head_show_search', 'general_options', false, 'yes' ) == 'yes' ? true : false,
		'path' => '/css/components/header/',
		'filename' => 'header-search',
	),
	'header_support' => array(
		'enabled' => zget_option( 'head_show_support_pnl', 'general_options', false, 'yes' ) == 'yes' ? true : false,
		'path' => '/css/components/header/',
		'filename' => 'header-support',
	),
	'header_login' => array(
		'enabled' => zget_option( 'head_show_login', 'general_options', false, '1' ) == '1' ? true : false,
		'path' => '/css/components/header/',
		'filename' => 'header-login',
	),
	'header_lang' => array(
		'enabled' => zget_option( 'head_show_flags', 'general_options', false, '1' ) == '1' ? true : false,
		'path' => '/css/components/header/',
		'filename' => 'header-lang',
	),
	'header_cart' => array(
		'enabled' => zget_option( 'woo_show_cart', 'zn_woocommerce_options', false, '1' ) == '1' ? true : false,
		'path' => '/css/components/header/',
		'filename' => 'header-cart',
	),
	'header_infocard' => array(
		'enabled' => zget_option( 'infocard_display_status', 'general_options', false, 'no' ) == 'yes' ? true : false,
		'path' => '/css/components/header/',
		'filename' => 'header-infocard',
	),
	'header_social' => array(
		'enabled' => zget_option( 'social_icons_visibility_status', 'general_options', false, 'yes' ) == 'yes' ? true : false,
		'path' => '/css/components/header/',
		'filename' => 'header-social',
	),
	'header_sticky' => array(
		'enabled' => zget_option( 'menu_follow', 'general_options', false, 'no' ) == 'sticky' ? true : false,
		'path' => '/css/components/header/',
		'filename' => 'header-sticky',
	),
	'header_chaser' => array(
		'enabled' => zget_option( 'menu_follow', 'general_options', false, 'no' ) == 'yes' ? true : false,
		'path' => '/css/components/header/',
		'filename' => 'header-chaser',
	),

	'subheader' => array(
		'enabled' => true,
		'path' => '/css/components/',
		'filename' => 'subheader',
	),

	'widgets' => array(
		'enabled' => true,
		'path' => '/css/components/',
		'filename' => 'widgets',
	),

	'footer' => array(
		'enabled' => true,
		'path' => '/css/components/',
		'filename' => 'footer',
	),

	'pages' => array(
		'enabled' => true,
		'path' => '/css/components/',
		'filename' => 'pages',
	),

);

?>