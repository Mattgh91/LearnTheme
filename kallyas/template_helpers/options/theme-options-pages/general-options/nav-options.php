<?php
/**
 * Theme options > General Options  > Navigation options
 */


$admin_options[] = array (
    'slug'        => 'nav_options',
    'parent'      => 'general_options',
    "name"        => __( 'NAVIGATION OPTIONS', 'zn_framework' ),
    "description" => __( 'These options below are related to site\'s navigations. For example the header contains 2 registered menus: "Main Navigation" and "Header Navigation".', 'zn_framework' ),
    "id"          => "info_title7",
    "type"        => "zn_title",
    "class"       => "zn_full zn-custom-title-large zn-toptabs-margin"
);

$admin_options[] = array (
    'slug'        => 'nav_options',
    'parent'      => 'general_options',
    "name"        => __( "Header Dropdowns color scheme", 'zn_framework' ),
    "description" => __( "Select the color scheme for the dropdown menus in the site header (topnav, cart container, language dropdown etc.)", 'zn_framework' ),
    "id"          => "nav_color_theme",
    "std"         => '',
    'options'        => array(
        '' => 'Inherit from Kallyas options > Color Options [Requires refresh]',
        'light' => 'Light (default)',
        'dark' => 'Dark'
    ),
    "type"        => "select"
);


$admin_options[] = array (
    'slug'        => 'nav_options',
    'parent'      => 'general_options',
                "name"        => __( 'Main Menu', 'zn_framework' ),
                "description" => __( 'These options are dedicated to the Main Menu navigation in Header.', 'zn_framework' ),
                "id"          => "hd_title1",
                "type"        => "zn_title",
                "class"       => "zn_full zn-custom-title-large zn-top-separator"
);

// Menu TYPOGRAPHY
$nav_default = zget_option( 'menu_font', 'font_options', false, array (
        'font-size'   => '14px',
        'font-family'   => 'Lato',
        'line-height' => '14px',
        'font-style'  => 'normal',
        'font-weight'  => '700',
    )
);

if(isset($nav_default['color'])){
    unset($nav_default['color']);
}

$admin_options[] = array (
    'slug'        => 'nav_options',
    'parent'      => 'general_options',
    "name"        => __( "Menu style for 1st level menu items", 'zn_framework' ),
    "description" => __( "Specify the style of the Main Menu's first level links.", 'zn_framework' ),
    "id"          => "menu_style",
    "std"         => '',
    "type"        => "select",
    "options"     => array(
        ""      => __( "Inherit from header's layout", 'zn_framework' ),
        'active-bg' => __('Background Color on hover/active', 'zn_framework'),
        'active-text' => __('Text Color on hover/active', 'zn_framework')
    )
);

$admin_options[] = array (
    'slug'        => 'nav_options',
    'parent'      => 'general_options',
    "name"        => __( "Menu Font Options for 1st level menu items", 'zn_framework' ),
    "description" => __( "Specify the typography properties for the Main Menu's first level links.", 'zn_framework' ),
    "id"          => "menu_font",
    "std"         => $nav_default,
    'supports'   => array( 'size', 'font', 'line', 'color', 'style', 'weight' ),
    "type"        => "font"
);

$admin_options[] = array (
    'slug'        => 'nav_options',
    'parent'      => 'general_options',
    "name"        => __( "Hover / Active color for 1st level menu items", 'zn_framework' ),
    "description" => __( "Specify the hover or active color of the Main Menu's first level links.", 'zn_framework' ),
    "id"          => "menu_font_active",
    "std"         => zget_option( 'zn_main_color', 'color_options', false, '#cd2122' ),
    'alpha'   => true,
    "type"        => "colorpicker"
);

$admin_options[] = array(
    'slug'        => 'nav_options',
    'parent'      => 'general_options',
    'id'          => 'header_res_width',
    'name'        => __( 'Header responsive breakpoint-width', 'zn_framework'),
    'description' => __( 'Choose the desired width when the responsive menu should appear.', 'zn_framework' ),
    'type'        => 'slider',
    'class'       => 'zn_full',
    'std'        => '992',
    'helpers'     => array(
        'min' => '50',
        'max' => '1200'
    )
);

$admin_options[] = array (
    'slug'        => 'nav_options',
    'parent'      => 'general_options',
                "name"        => __( 'Main menu - Sub-menus options', 'zn_framework' ),
                "description" => __( "These options are dedicated to the main menu's submenu navigation in Header.", 'zn_framework' ),
                "id"          => "hd_title3",
                "type"        => "zn_title",
                "class"       => "zn_full zn-custom-title-large"
);

$admin_options[] = array (
    'slug'        => 'nav_options',
    'parent'      => 'general_options',
    "name"        => __( "Sub-Menu Font Options", 'zn_framework' ),
    "description" => __( "Specify the typography properties for the Main sub-menu.", 'zn_framework' ),
    "id"          => "menu_font_sub",
    "std"         => $nav_default,
    'supports'   => array( 'size', 'font', 'line', 'color', 'style', 'weight' ),
    "type"        => "font"
);

$admin_options[] = array (
    'slug'        => 'nav_options',
    'parent'      => 'general_options',
    "name"        => __( "Main menu Dropdowns color scheme", 'zn_framework' ),
    "description" => __( "Select the color scheme for the MAIN MENU in the site header", 'zn_framework' ),
    "id"          => "navmain_color_theme",
    "std"         => '',
    'options'        => array(
        '' => 'Inherit from Kallyas options > Color Options [Requires refresh]',
        'light' => 'Light (default)',
        'dark' => 'Dark'
    ),
    "type"        => "select"
);


$admin_options[] = array (
    'slug'        => 'nav_options',
    'parent'      => 'general_options',
                "name"        => __( 'Top-Header Menu', 'zn_framework' ),
                "description" => __( 'These options are dedicated to the Header-Navigation in TOP-Header.', 'zn_framework' ),
                "id"          => "hd_title1",
                "type"        => "zn_title",
                "class"       => "zn_full zn-custom-title-large zn-top-separator"
);
$admin_options[] = array (
    'slug'        => 'nav_options',
    'parent'      => 'general_options',
    "name"        => __( "Enable dropdown Top Header Navigation? Only available for smartphones.", 'zn_framework' ),
    "description" => __( "This option will enable a dropdown menu for the header-navigation (not main-menu!). If you have for example lots of menu items in header, this option will fallback nicely in the header.", 'zn_framework' ),
    "id"          => "header_topnav_dd",
    "std"         => "yes",
    "value"         => "yes",
    "type"        => "toggle2",
);


// HELP STARTS HERE

$admin_options[] = array (
    'slug'        => 'nav_options',
    'parent'      => 'general_options',
    "name"        => __( '<span class="dashicons dashicons-editor-help"></span> HELP:', 'zn_framework' ),
    "description" => __( 'Below you can find quick access to documentation, video documentation or our support forum.', 'zn_framework' ),
    "id"          => "nvo_title",
    "type"        => "zn_title",
    "class"       => "zn_full zn-custom-title-md zn-top-separator zn-sep-dark"
);

$admin_options[] = wp_parse_args( znpb_general_help_option( 'zn-admin-helplink' ), array(
    'slug'        => 'nav_options',
    'parent'      => 'general_options',
));