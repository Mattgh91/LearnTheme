<?php if(! defined('ABSPATH')){ return; }

/**
* Display the default header
* Hooks mostly, markup loaded through own template
*/

/**
 * ==========================================================
 * CSS custom classes customisations
 * ==========================================================
 */

/**
 * Add custom CSS classes to the <header> tag
 */
// $header_class[] = '';


/**
 * Flexbox scheme override
 *
 * You can override the default markup's vertical and horizontal alignment, as well as "cell" stretch.
 * Also you can add custom CSS Classes.
 *
 */
$flexbox_scheme = array();


/**
 * Extra Rows CSS Classes
 *
 * These classes are added to their particular area's rows.
 */
// $top_extra_class = '';
// $main_extra_class = '';
// $bottom_extra_class = '';


/**
 * Extra CSS Class for "siteheader-container".
 *
 * This class will be added to the siteheader-container block.
 */
$siteheader_container_class = 'header--oldstyles';


/**
 * Sticky classes.
 *
 * The area's that will hide on scroll.
 *
 * @Types: sticky-top-area AND/OR sticky-main-area AND/OR sticky-bottom-area
 */
$sticky_class = 'sticky-top-area';


/**
 * ==========================================================
 * Hook header's components
 * ==========================================================
 *
 * Components are loaded through hooks into their predefined area.
 * You can move or reorder components through simple remove_action / add_action through Kallyas child theme.
 */

/**** SIDE LEFT */

if(zn_check_components('logo')) add_action( 'zn_head__side_left', 'zn_header_display_logo' ); // LOGO MARKUP


/**** TOP LEFT */

if($headerLayoutStyle == 'style2' || $headerLayoutStyle == 'style3'){
    if(zn_check_components('flags')) add_action( 'zn_head__top_left', 'zn_wpml_language_switcher', 10 ); // WPML LANGUAGE SWITCHER
    if(zn_check_components('cart')) add_action( 'zn_head__top_left', 'zn_woocomerce_cart_init', 20 ); // CART PANEL
    if(zn_check_components('custom_text')) add_action( 'zn_head__top_left', 'zn_header_head_text', 80 ); // CUSTOM TEXT
}


/**** TOP RIGHT */

if(zn_check_components('header_nav')) add_action( 'zn_head__top_right', 'zn_add_navigation', 10 ); // HEADER NAVIGATION
if(zn_check_components('hidden_panel')) add_action( 'zn_head__top_right', 'zn_hidden_pannel_link', 20 ); // HIDDEN PANEL LINK
if(zn_check_components('register')) add_action( 'zn_head__top_right', 'zn_register_text', 30 ); // REGISTER TEXT
if(zn_check_components('login')) add_action( 'zn_head__top_right', 'zn_login_text', 40 ); // LOGIN/LOGOUT TEXT
if(zn_check_components('social_icons')) add_action( 'zn_head__top_right', 'zn_header_social_icons', 70, 1 ); // SOCIAL ICONS
if(zn_check_components('search_box')) add_action( 'zn_head__top_right', 'zn_header_searchbox_def', 80 ); // SEARCH BOX

if($headerLayoutStyle == 'style1' || $headerLayoutStyle == 'style4' || $headerLayoutStyle == 'style5' || $headerLayoutStyle == 'style6'){
    if(zn_check_components('flags')) add_action( 'zn_head__top_right', 'zn_wpml_language_switcher', 50 ); // WPML LANGUAGE SWITCHER
    if(zn_check_components('custom_text')) add_action( 'zn_head__top_right', 'zn_header_head_text', 2 ); // CUSTOM TEXT
    if(zn_check_components('cart')) add_action( 'zn_head__top_right', 'zn_woocomerce_cart_init', 60 ); // CART PANEL
}


/**** MAIN RIGHT */

add_action( 'zn_head__main_right', 'zn_header_main_menu', 10 ); // MAIN NAVIGATION
add_action( 'zn_head__main_right', 'zn_header_calltoaction', 20 ); // CALL TO ACTION BUTTON button


/**** OTHERS */

// Add separators only for XS breakpoint
add_action( 'zn_head__before__top', 'zn_header_separator_xs', 10 );
add_action( 'zn_head__after__top', 'zn_header_separator_xs', 10 );

if($headerLayoutStyle == 'style5'){
    // Add separator after top
    add_action( 'zn_head__after__main_wrapper', 'zn_header_separator' );
}


/**
 * ==========================================================
 * Load general HTML markup
 * ==========================================================
 *
 * The header's markup is loaded for all headers. If you plan on overriding the HTML markup,
 * first make sure you can do it through hooks. If you're sure, simply copy the markup, paste it inside this file and
 * copy this file to ../kallyas-child/components/theme-header/ folder.
 */
include(locate_template('components/theme-header/header-markup.php'));
