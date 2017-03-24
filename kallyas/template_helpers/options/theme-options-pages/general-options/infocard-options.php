<?php
$desc = sprintf(
    '%s ( <a href="%s" target="_blank" title="%s">%s</a>).',
    __( 'These options below are related to logo\'s info card panel.', 'zn_framework' ),
    esc_url( 'http://hogash.d.pr/TiFZ' ),
    __( 'Click to open screenshot', 'zn_framework' ),
    __( 'Open screenshot', 'zn_framework' )
);
$admin_options[] = array (
    'slug'        => 'info_card',
    'parent'      => 'general_options',
    "name"        => __( 'INFOCARD OPTIONS', 'zn_framework' ),
    "description" => $desc,
    "id"          => "info_title5",
    "type"        => "zn_title",
    "class"       => "zn_full zn-custom-title-large zn-toptabs-margin"
);

// Show Info Card on Logo Hover
$admin_options[] = array (
    'slug'        => 'info_card',
    'parent'      => 'general_options',
    "name"        => __( "Show Info Card when you hover over the logo", 'zn_framework' ),
    "description" => __( "Please choose if you want to display the info card or not.", 'zn_framework' ),
    "id"          => "infocard_display_status",
    "std"         => "no",
    "type"        => "zn_radio",
    "options"     => array (
        "yes" => __( "Show", 'zn_framework' ),
        "no"  => __( "Hide", 'zn_framework' )
    )
);

$saved_main_color = zget_option( 'zn_main_color', 'color_options', false, '#cd2122' );
// Background for the Info Card
$admin_options[] = array (
    'slug'        => 'info_card',
    'parent'      => 'general_options',
    "name"        => __( "Set a background for the Info Card", 'zn_framework' ),
    "description" => __( "Choose the background color for the Info Card", 'zn_framework' ),
    "id"          => "infocard_bg_color",
    "std"         => $saved_main_color,
    "type"        => "colorpicker",
    'dependency'  => array ( 'element' => 'infocard_display_status', 'value' => array ( 'yes' ) ),
);
// Text color for the Info Card
$admin_options[] = array (
    'slug'        => 'info_card',
    'parent'      => 'general_options',
    "name"        => __( "Set a text color for the Info Card", 'zn_framework' ),
    "description" => __( "Choose the text color for the Info Card", 'zn_framework' ),
    "id"          => "infocard_text_color",
    "std"         => '#ffffff',
    "type"        => "colorpicker",
    'dependency'  => array ( 'element' => 'infocard_display_status', 'value' => array ( 'yes' ) ),
);

// Info Card company logo
$admin_options[] = array (
    'slug'        => 'info_card',
    'parent'      => 'general_options',
    "name"        => __( "Choose company logo", 'zn_framework' ),
    "description" => __( "Choose your company logo which will appear in info card", 'zn_framework' ),
    "id"          => "infocard_logo_url",
    "std"         => "",
    "type"        => "media",
    'dependency'  => array ( 'element' => 'infocard_display_status', 'value' => array ( 'yes' ) ),
);

// Info Card company description
$admin_options[] = array (
    'slug'        => 'info_card',
    'parent'      => 'general_options',
    "name"        => __( "Company Description", 'zn_framework' ),
    "description" => __( "Please type a small description of your company", 'zn_framework' ),
    "id"          => "infocard_company_description",
    "std"         => "Kallyas is an ultra-premium, responsive theme built for today websites.",
    "type"        => "text",
    'dependency'  => array ( 'element' => 'infocard_display_status', 'value' => array ( 'yes' ) ),
);

// Info Card company description
$admin_options[] = array (
    'slug'        => 'info_card',
    'parent'      => 'general_options',
    "name"        => __( "Company phone", 'zn_framework' ),
    "description" => __( "Please type your company phone number", 'zn_framework' ),
    "id"          => "infocard_company_phone",
    "std"         => __( "T (212) 555 55 00", 'zn_framework' ),
    "type"        => "text",
    'dependency'  => array ( 'element' => 'infocard_display_status', 'value' => array ( 'yes' ) ),
);

// Info Card company description
$admin_options[] = array (
    'slug'        => 'info_card',
    'parent'      => 'general_options',
    "name"        => __( "Company email", 'zn_framework' ),
    "description" => __( "Please type your company email", 'zn_framework' ),
    "id"          => "infocard_company_email",
    "std"         => __( "sales@yourwebsite.com", 'zn_framework' ),
    "type"        => "text",
    'dependency'  => array ( 'element' => 'infocard_display_status', 'value' => array ( 'yes' ) ),
);

// Info Card company name
$admin_options[] = array (
    'slug'        => 'info_card',
    'parent'      => 'general_options',
    "name"        => __( "Company name", 'zn_framework' ),
    "description" => __( "Type your company name here", 'zn_framework' ),
    "id"          => "infocard_company_name",
    "std"         => __( "Your Company LTD", 'zn_framework' ),
    "type"        => "text",
    'dependency'  => array ( 'element' => 'infocard_display_status', 'value' => array ( 'yes' ) ),
);

// Info Card company address
$admin_options[] = array (
    'slug'        => 'info_card',
    'parent'      => 'general_options',
    "name"        => __( "Company address", 'zn_framework' ),
    "description" => __( "Type your company address here", 'zn_framework' ),
    "id"          => "infocard_company_address",
    "std"         => __( "Street nr 100, 4536534, Chicago, US", 'zn_framework' ),
    "type"        => "text",
    'dependency'  => array ( 'element' => 'infocard_display_status', 'value' => array ( 'yes' ) ),
);

// Info Card company name
$admin_options[] = array (
    'slug'        => 'info_card',
    'parent'      => 'general_options',
    "name"        => __( "Company map link", 'zn_framework' ),
    "description" => __( "Please enter you company map link", 'zn_framework' ),
    "id"          => "infocard_gmap_link",
    "std"         => "http://goo.gl/maps/1OhOu",
    "type"        => "text",
    'dependency'  => array ( 'element' => 'infocard_display_status', 'value' => array ( 'yes' ) ),
);


$admin_options[] = array (
    'slug'        => 'info_card',
    'parent'      => 'general_options',
    "name"        => __( '<span class="dashicons dashicons-editor-help"></span> HELP:', 'zn_framework' ),
    "description" => __( 'Below you can find quick access to documentation, video documentation or our support forum.', 'zn_framework' ),
    "id"          => "ico_title",
    "type"        => "zn_title",
    "class"       => "zn_full zn-custom-title-md zn-top-separator zn-sep-dark"
);

$admin_options[] = zn_options_video_link_option( 'http://support.hogash.com/kallyas-videos/#TuXcJu9jl7c', __( 'Click here to access video tutorial for this options section.', 'zn_framework' ), array(
    'slug'        => 'info_card',
    'parent'      => 'general_options'
));

$admin_options[] = wp_parse_args( znpb_general_help_option( 'zn-admin-helplink' ), array(
    'slug'        => 'info_card',
    'parent'      => 'general_options',
));