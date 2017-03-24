<?php
/**
 * Theme options > General Options  > Logo options
 */
$desc = sprintf(
    '%s ( <a href="%s" target="_blank" title="%s">%s</a>).',
    __( 'These options below are related to site\'s logo.', 'zn_framework' ),
    esc_url( 'http://hogash.d.pr/108qR' ),
    __( 'Click to open screenshot', 'zn_framework' ),
    __( 'Open screenshot', 'zn_framework' )
);
$admin_options[] = array (
    'slug'        => 'logo_options',
    'parent'      => 'general_options',
    "name"        => __( 'LOGO OPTIONS', 'zn_framework' ),
    "description" => $desc,
    "id"          => "info_title3",
    "type"        => "zn_title",
    "class"       => "zn_full zn-custom-title-large zn-toptabs-margin"
);

// Show LOGO In header
$admin_options[] = array (
    'slug'        => 'logo_options',
    'parent'      => 'general_options',
    "name"        => __( "Show LOGO in header", 'zn_framework' ),
    "description" => __( "Please choose if you want to display the logo or not.", 'zn_framework' ),
    "id"          => "head_show_logo",
    "std"         => "yes",
    "type"        => "zn_radio",
    "options"     => array (
        "yes" => __( "Show", 'zn_framework' ),
        "no"  => __( "Hide", 'zn_framework' )
    )
);

// Logo Upload
$admin_options[] = array (
    'slug'        => 'logo_options',
    'parent'      => 'general_options',
    "name"        => __( "Logo Upload", 'zn_framework' ),
    "description" => __( "Upload your logo.", 'zn_framework' ),
    "id"          => "logo_upload",
    "std"         => '',
    "type"        => "media"
);

// Logo auto size ?

$logo_size    = array (
    "yes"     => __( "Auto resize logo", 'zn_framework' ),
    "no"      => __( "Custom size", 'zn_framework' ),
    "contain" => __( "Contain in header", 'zn_framework' ),
);
$admin_options[] = array (
    'slug'        => 'logo_options',
    'parent'      => 'general_options',
    "name"        => __( "Logo Size :", 'zn_framework' ),
    "description" => __( "Auto resize logo will use the image dimensions, Custom size let's you set the desired logo size and Contain in header will select the proper logo size so that it will be displayed in the header.", 'zn_framework' ),
    "id"          => "logo_size",
    "std"         => "contain",
    "type"        => "zn_radio",
    "options"     => $logo_size,
);

// Logo Dimensions
$default_size = array (
    'height' => '55',
    'width'  => '125'
);
$admin_options[] = array (
    'slug'        => 'logo_options',
    'parent'      => 'general_options',
    "name"        => __( "Logo manual sizes", 'zn_framework' ),
    "description" => __( 'Please insert your desired logo size in pixels ( for example "35" )', 'zn_framework' ),
    "id"          => "logo_manual_size",
    "std"         => $default_size,
    "type"        => "image_size",
    'dependency'  => array ( 'element' => 'logo_size', 'value' => array ( 'no' ) ),
);

// Logo typography for link

$admin_options[] = array (
    'slug'        => 'logo_options',
    'parent'      => 'general_options',
    "name"        => __( "Logo Link Options", 'zn_framework' ),
    "description" => __( "Specify the logo typography properties. Will only work if you don't upload a logo image.", 'zn_framework' ),
    "id"          => "logo_font",
    "std"         => array (
        'font-size'   => '36px',
        'font-family'   => 'Open Sans',
        'font-style'  => 'normal',
        'color'  => '#000',
        'line-height' => '40px'
    ),
    'supports'   => array( 'size', 'font', 'style', 'color', 'line', 'weight' ),
    "type"        => "font"
);

// Logo Hover Typography

$admin_options[] = array (
    'slug'        => 'logo_options',
    'parent'      => 'general_options',
    "name"        => __( "Logo Link Hover Color", 'zn_framework' ),
    "description" => __( "Specify the logo hover color. Will only work if you don't upload a logo image. ", 'zn_framework' ),
    "id"          => "logo_hover",
    "std"         => array (
        'color' => '#CD2122',
        'font-family'  => 'Open Sans'
    ),
    'supports'   => array( 'font', 'color' ),
    "type"        => "font"
);

// Logo Sticky
$admin_options[] = array (
    'slug'        => 'logo_options',
    'parent'      => 'general_options',
    "name"        => __( "Sticky Logo", 'zn_framework' ),
    "description" => __( "Will display a secondary logo when header is sticky and scrolling the page. <strong>ONLY</strong> available if you have Sticky Header enabled in General Options. ", 'zn_framework' ),
    "id"          => "logo_sticky",
    "std"         => '',
    "type"        => "media"
);

// Logo Upload
$admin_options[] = array (
    'slug'        => 'logo_options',
    'parent'      => 'general_options',
    "name"        => __( "Logo on Mobile", 'zn_framework' ),
    "description" => __( "Upload your logo for displaying on viewports smaller than 767px (smartphones, phablets).", 'zn_framework' ),
    "id"          => "logo_upload_mobile",
    "std"         => '',
    "type"        => "media"
);


$admin_options[] = array (
    'slug'        => 'logo_options',
    'parent'      => 'general_options',
    "name"        => __( '<span class="dashicons dashicons-editor-help"></span> HELP:', 'zn_framework' ),
    "description" => __( 'Below you can find quick access to documentation, video documentation or our support forum.', 'zn_framework' ),
    "id"          => "lgo_title",
    "type"        => "zn_title",
    "class"       => "zn_full zn-custom-title-md zn-top-separator zn-sep-dark"
);

$admin_options[] = zn_options_video_link_option( 'http://support.hogash.com/kallyas-videos/#m2dbZdeciZs', __( 'Click here to access video tutorial for this options section.', 'zn_framework' ), array(
    'slug'        => 'logo_options',
    'parent'      => 'general_options'
));

$admin_options[] = wp_parse_args( znpb_general_help_option( 'zn-admin-helplink' ), array(
    'slug'        => 'logo_options',
    'parent'      => 'general_options',
));