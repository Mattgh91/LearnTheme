<?php if(! defined('ABSPATH')){ return; }
/**
 * Display Header's HTML markup
 * DON'T OVERRIDE THIS FILE, instead copy the header-style##.php to Kallyas Child theme (same location) and paste this markup into it;
 */

// If Custom bg selected, make sure it's not default text scheme
if ( $header_text_scheme && $header_style == 'image_color' ) {
    if($header_text_scheme != 'default' && $header_text_scheme != ''){
        $headerTextScheme = 'sh--' . $header_text_scheme;
    }
}
$header_class[] = $headerTextScheme;

// IMPLODE HEADER CLASSES
$hc = implode( ' ', $header_class );

// DEFAULT FLEXBOX SCHEME
$inner = array(
    'left' => array(
        'alignment_x' => 'fxb-start-x',
        'alignment_y' => 'fxb-center-y',
        'stretch' => 'fxb-basis-auto',
    ),
    'center' => array(
        'alignment_x' => 'fxb-center-x',
        'alignment_y' => 'fxb-center-y',
        'stretch' => 'fxb-basis-auto',
    ),
    'right' => array(
        'alignment_x' => 'fxb-end-x',
        'alignment_y' => 'fxb-center-y',
        'stretch' => 'fxb-basis-auto',
    ),
);
$flexbox_scheme_defaults = array(
    'top' => $inner,
    'main' => $inner,
    'bottom' => $inner
);

// Extend Flexbox scheme defaults
$flexbox_scheme = zn_wp_parse_args( $flexbox_scheme, $flexbox_scheme_defaults );

// START MARKUP

// Area checks - TOP
$check_top =  has_action('zn_head__top_left') || has_action('zn_head_left_area') || has_action('zn_head_left_area_s7') || has_action('zn_head_left_area_s9') || has_action('zn_head__top_right') || has_action('zn_head_right_area') || has_action('zn_head_right_area_s7') || has_action('zn_head_right_area_s9') ;
// Area checks - BOTTOM
$check_bottom = has_action('zn_head__bottom_left') || has_action('zn_head__bottom_center') || has_action('zn_head__bottom_right') || has_action('zn_head_cart_area_s8') || has_action('zn_head__before__bottom') || has_action('zn_head__after__bottom');

// Add Classes to Main if no Top/Bottom
$main_class = '';
$main_class .= !$check_top ? ' header-no-top':'';
$main_class .= !$check_bottom ? ' header-no-bottom':'';

// Extra classes
$top_extra_class = isset($top_extra_class) ? $top_extra_class : '';
$main_extra_class = isset($main_extra_class) ? $main_extra_class : '';
$bottom_extra_class = isset($bottom_extra_class) ? $bottom_extra_class : '';

// SiteHeader container class
$siteheader_container_class = isset($siteheader_container_class) ? $siteheader_container_class : '';

// Sticky class
$sticky_class = isset($sticky_class) ? $sticky_class : '';

?>
<header id="header" class="site-header <?php echo $hc; ?>" <?php echo $stickyMenuAttrs; ?> <?php echo $style;?>>
    <?php
    // place a hook before markup
    do_action('zn_before_siteheader_inside');
    ?>
    <div class="site-header-wrapper <?php echo $sticky_class; ?>">

        <div class="kl-top-header site-header-main-wrapper clearfix">

            <div class="container siteheader-container <?php echo $siteheader_container_class; ?>">

                <div class="fxb-row fxb-row-col-sm">

                    <?php
                    if(has_action('zn_head__side_left')):
                    ?>
                    <div class='fxb-col fxb fxb-center-x fxb-center-y fxb-basis-auto fxb-grow-0'>
                        <?php do_action( 'zn_head__side_left' ); ?>
                    </div>
                    <?php endif; ?>

                    <div class='fxb-col fxb-basis-auto'>

                        <?php
                            /**
                             * Check for TOP HEADER hooks, to display the header
                             */
                            if ( $check_top ):
                        ?>

                            <?php do_action('zn_head__before__top'); ?>

                            <div class="fxb-row site-header-top <?php echo $top_extra_class; ?>">

                                <div class='fxb-col fxb <?php echo zn_getFlexboxScheme($flexbox_scheme, 'top', 'left'); ?> site-header-col-left site-header-top-left'>
                                    <?php do_action( 'zn_head__top_left' ); ?>
                                    <?php
                                        /**
                                         * OLD HOOKS
                                         * @deprecated - use "zn_head__top_left" instead
                                         * Kept for backwards compatibility
                                         */
                                        do_action( 'zn_head_left_area' );
                                        do_action( 'zn_head_left_area_s7' );
                                        do_action( 'zn_head_left_area_s9' );
                                    ?>
                                </div>

                                <div class='fxb-col fxb <?php echo zn_getFlexboxScheme($flexbox_scheme, 'top', 'right'); ?> site-header-col-right site-header-top-right'>
                                    <?php
                                        /**
                                         * OLD HOOKS
                                         * @deprecated - use "zn_head__top_right" instead
                                         * Kept for backwards compatibility
                                         */
                                        do_action( 'zn_head_right_area_s9' );
                                        do_action( 'zn_head_right_area_s7' );
                                        do_action( 'zn_head_right_area' );
                                    ?>
                                    <?php do_action( 'zn_head__top_right' ); ?>
                                </div>

                            </div><!-- /.site-header-top -->

                            <?php do_action('zn_head__after__top'); ?>

                        <?php endif; ?>

                        <?php do_action('zn_head__before__main'); ?>

                        <div class="fxb-row site-header-main <?php echo $main_class; ?> <?php echo $main_extra_class; ?>">

                            <div class='fxb-col fxb <?php echo zn_getFlexboxScheme($flexbox_scheme, 'main', 'left'); ?> site-header-col-left site-header-main-left'>
                                <?php do_action( 'zn_head__main_left' ); ?>
                            </div>

                            <div class='fxb-col fxb <?php echo zn_getFlexboxScheme($flexbox_scheme, 'main', 'center'); ?> site-header-col-center site-header-main-center'>
                                <?php do_action('zn_head__main_center'); ?>
                            </div>

                            <div class='fxb-col fxb <?php echo zn_getFlexboxScheme($flexbox_scheme, 'main', 'right'); ?> site-header-col-right site-header-main-right'>
                                <div class="fxb-row fxb-row-col fxb-center-y">

                                    <div class='fxb-col fxb <?php echo zn_getFlexboxScheme($flexbox_scheme, 'main', 'right'); ?> site-header-main-right-top'>
                                        <?php
                                            /**
                                             * OLD HOOK
                                             * @deprecated - use "zn_head__main_right" instead
                                             * Kept for backwards compatibility
                                             */
                                            do_action( 'zn_head_cart_area_s7' );
                                            do_action( 'zn_head_cart_area_s9' );
                                            do_action( 'zn_head_right1_area_s8' );
                                        ?>
                                        <?php do_action('zn_head__main_right'); ?>
                                    </div>

                                    <?php if (has_action('zn_head__main_right_ext') || has_action('zn_head_right2_area_s8') || has_action('zn_head_cart_area_s9')): ?>
                                    <div class='fxb-col fxb fxb-end-x fxb-center-y site-header-main-right-ext'>
                                        <?php
                                            /**
                                             * OLD HOOK
                                             * @deprecated - use "zn_head__main_right_ext" instead
                                             * Kept for backwards compatibility
                                             */
                                            do_action( 'zn_head_right2_area_s8' );
                                        ?>
                                        <?php do_action('zn_head__main_right_ext'); ?>
                                    </div>
                                    <?php endif; ?>

                                </div>
                            </div>

                        </div><!-- /.site-header-main -->

                        <?php do_action('zn_head__after__main'); ?>
                    </div>
                </div>
                <?php do_action('zn_head__after__main_wrapper'); ?>
            </div><!-- /.siteheader-container -->
        </div><!-- /.site-header-main-wrapper -->

        <?php
            if( $check_bottom ):
        ?>
        <div class="kl-main-header site-header-bottom-wrapper clearfix">

            <div class="container siteheader-container">

                <?php do_action( 'zn_head__before__bottom' ); ?>

                <?php
                    if(has_action('zn_head__bottom_left') || has_action('zn_head__bottom_center') || has_action('zn_head__bottom_right') || has_action('zn_head_cart_area_s8') ):
                ?>
                <div class="fxb-row site-header-bottom <?php echo $bottom_extra_class; ?>">


                    <div class='fxb-col fxb <?php echo zn_getFlexboxScheme($flexbox_scheme, 'bottom', 'left'); ?> site-header-col-left site-header-bottom-left'>
                        <?php do_action( 'zn_head__bottom_left' ); ?>
                    </div>


                    <div class='fxb-col fxb <?php echo zn_getFlexboxScheme($flexbox_scheme, 'bottom', 'center'); ?> site-header-col-center site-header-bottom-center'>
                        <?php do_action( 'zn_head__bottom_center' ); ?>
                    </div>


                    <div class='fxb-col fxb <?php echo zn_getFlexboxScheme($flexbox_scheme, 'bottom', 'right'); ?> site-header-col-right site-header-bottom-right'>
                        <?php
                            /**
                             * OLD HOOK
                             * @deprecated - use "zn_head__bottom_right" instead
                             * Kept for backwards compatibility
                             */
                            do_action( 'zn_head_cart_area_s8' );
                        ?>
                        <?php do_action( 'zn_head__bottom_right' ); ?>
                    </div>


                </div><!-- /.site-header-bottom -->
                <?php endif; ?>

                <?php do_action( 'zn_head__after__bottom' ); ?>

            </div>
        </div><!-- /.site-header-bottom-wrapper -->
        <?php endif; ?>

    </div><!-- /.site-header-wrapper -->
    <?php
        do_action('zn_after_siteheader_inside');
    ?>
</header>
