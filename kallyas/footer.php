<?php if(! defined('ABSPATH')){ return; }

$style = "";
$show_footer = zget_option( 'footer_show', 'general_options', false, 'yes' );
if( is_singular() && get_post_meta( get_the_ID() , 'show_footer', true ) === 'zn_dummy_value') {
    $show_footer = 'no';
    if ( ZNPB()->is_active_editor ){
    	$show_footer = 'yes';
    	$style = ' style="display:none" ';
    }
}

/* Should we display a template ? */
$config = zn_get_pb_template_config( 'footer' );
if( $config['template'] !== 'no_template' ){
    // We have a subheader template... let's get it's possition
    $pb_data = get_post_meta( $config['template'], 'zn_page_builder_els', true );

    if( $config['location'] === 'before' ){
        ZNPB()->zn_render_uneditable_content( $pb_data );
    }
    elseif( $config['location'] === 'replace' ){
        ZNPB()->zn_render_uneditable_content( $pb_data );
        $show_footer = 'no';
    }

}

if ( $show_footer == 'yes' ) { ?>
	<footer id="footer" class="site-footer" <?php echo $style;?>>

	<div class="static_mailchimp_wrapper">
		<div id="static_mailchimp" class="container">
			<div class="wrapper">
				<div class="inside">
					<link href="//cdn-images.mailchimp.com/embedcode/slim-10_7.css" rel="stylesheet" type="text/css">
					<style type="text/css">
						#mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
						/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
						   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
					</style>
					<div id="mc_embed_signup">
						<form action="//learntechnique.us15.list-manage.com/subscribe/post-json?u=3574759aff3eaef872f610742&amp;id=b2fa03406f&c=?" method="get" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
						    <div id="mc_embed_signup_scroll">
								<label for="mce-EMAIL">Subscribe to our newsletter</label>
								<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="Your Email Address" required>
							    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
							    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_3574759aff3eaef872f610742_b2fa03406f" tabindex="-1" value=""></div>
							    <div class="clear"><input type="submit" value="SIGN UP" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
						    </div>
							</form>
						</div>
						<!--End mc_embed_signup-->
						<div class="staticSuccessMessage">
							<p>Thanks for signing up to our newsletter.<br />Please check your emails for sign up confirmation!</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="container">
			<?php

				if ( zget_option( 'footer_row1_show', 'general_options', false, 'yes' ) == 'yes' ) {

					echo '<div class="row">';

					$footer_row1_widget_positions = zget_option( 'footer_row1_widget_positions', 'general_options', false, '{"3":[["4","4","4"]]}' );
					$columns_array = json_decode( stripslashes( $footer_row1_widget_positions ), true );
					$number_of_columns = is_array( $columns_array ) ? key( $columns_array ) : 1;

					for ( $i = 1; $i <= $number_of_columns; $i ++ ) {
						echo '<div class="col-sm-' . $columns_array[ $number_of_columns ][0][ $i - 1 ] . '">';
						if ( ! dynamic_sidebar( 'Footer row 1 - widget ' . $i . '' ) ) : endif;
						echo '</div>';
					}

					echo '</div><!-- end row -->';
				}


				if ( zget_option( 'footer_row2_show', 'general_options', false, 'yes' ) == 'yes' ) {

					echo '<div class="row">';

					$footer_row2_widget_positions = zget_option( 'footer_row2_widget_positions', 'general_options', false, '{"3":[["4","4","4"]]}' );
					$columns_array = json_decode( stripslashes( $footer_row2_widget_positions ), true );
					$number_of_columns = is_array( $columns_array ) ? key( $columns_array ) : 1;

					for ( $i = 1; $i <= $number_of_columns; $i ++ ) {
						echo '<div class="col-sm-' . $columns_array[ $number_of_columns ][0][ $i - 1 ] . '">';
						if ( ! dynamic_sidebar( 'Footer row 2 - widget ' . $i . '' ) ) : endif;
						echo '</div>';
					}

					echo '</div><!-- end row -->';
				}

			?>

			<div class="row">
				<div class="col-sm-12">
					<div class="bottom site-footer-bottom clearfix">

						<?php
						// Footer menu
						if ( has_nav_menu( 'footer_navigation' ) ) {
							echo '<div class="zn_footer_nav-wrapper">';
								zn_show_nav( 'footer_navigation', 'footer_nav', array( 'depth' => '2' ) );
							echo '</div>';
						}
						?>

						<?php

						if ( zget_option( 'footer_social_icons_enable', 'general_options', false, 'yes' ) == 'yes' )
        				{
							$footer_social_icons = zget_option( 'footer_social_icons', 'general_options', false, array() );
							if ( ! empty ( $footer_social_icons ) ) {

								$icon_class = zget_option( 'footer_which_icons_set', 'general_options', false, 'normal' );

								echo '<ul class="social-icons sc--' . $icon_class . ' clearfix">';
									echo '<li class="social-icons-li title">' . __( 'GET SOCIAL', 'zn_framework' ) . '</li>'; // Translate

									foreach ( $footer_social_icons as $key => $icon ) {

										$link   = '';
										$target = '';

										if ( isset ( $icon['footer_social_link'] ) && is_array( $icon['footer_social_link'] ) ) {
											$link   = $icon['footer_social_link']['url'];
											$target = 'target="' . $icon['footer_social_link']['target'] . '"';
										}
										$icon_color = '';
										if($icon_class != 'normal' && $icon_class != 'clean'){
											$icon_color = isset($icon['footer_social_color']) && !empty($icon['footer_social_color']) ? $icon['footer_social_icon']['unicode'] : 'nocolor';
										}
					                    $social_icon = !empty( $icon['footer_social_icon'] )  ? '<a '.zn_generate_icon( $icon['footer_social_icon'] ).' href="' . $link . '" ' . $target . ' title="' . $icon['footer_social_title'] . '" class="social-icons-item scfooter-icon-'.$icon_color.'"></a>' : '';
					                    echo '<li class="social-icons-li">'.$social_icon.'</li>';
										//echo '<li><a class="sc-icon-' . str_replace('social-', '', $icon['footer_social_icon']) . '" href="' . $link . '" ' . $target . ' title="' . $icon['footer_social_title'] . '"></a></li>';
									}

								echo '</ul>';
							}
						}
						?>

						<?php
						$copyright_text = zget_option( 'copyright_text', 'general_options' );
						$footer_logo = zget_option( 'footer_logo', 'general_options' );
						if ( !empty( $copyright_text ) || !empty( $footer_logo ) ) { ?>

							<div class="copyright footer-copyright">
								<?php
									if ( !empty( $footer_logo ) ) {
										echo '<a href="' . home_url() . '" class="footer-copyright-link"><img class="footer-copyright-img" src="' . $footer_logo . '" alt="' . get_bloginfo( 'name' ) . '" /></a>';
									}

									if ( !empty( $copyright_text ) ) {
										echo '<p class="footer-copyright-text">' . do_shortcode(stripslashes( $copyright_text )) . '</p>';
									}
								?>
							</div><!-- end copyright -->
						<?php } ?>
					</div>
					<!-- end bottom -->
				</div>
			</div>
			<!-- end row -->
		</div>
	</footer>
<?php
}

if( $config['template'] !== 'no_template' && $config['location'] === 'after' ){
    ZNPB()->zn_render_uneditable_content( $pb_data );
}

?>
</div><!-- end page_wrapper -->

<a href="#" id="totop" class="u-trans-all-2s js-scroll-event" data-forch="300" data-visibleclass="on--totop" data-hiddenclass="off--totop" ><?php echo __( 'TOP', 'zn_framework' ); ?></a>
<?php zn_footer(); ?>
<?php wp_footer(); ?> 
<script src="https://use.fontawesome.com/583b8ed0d6.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$(window).load(function(){

			var $staticForm = $('#static_mailchimp form');

			$('#static_mailchimp form input[type="submit"]').bind('click', function ( event ) {
				if ( event ) event.preventDefault();
				registerStat($staticForm)
			});

			function registerStat($staticForm) {
			    $.ajax({
			        type: $staticForm.attr('method'),
			        url: $staticForm.attr('action'),
			        data: $staticForm.serialize(),
			        cache       : false,
			        dataType    : 'jsonp',
			        contentType: "application/json; charset=utf-8",
			        error       : function(err) { alert("Could not connect to the registration server. Please try again later."); },
			        success     : function(data) {
			            if (data.result != "success") {
			                // Something went wrong, do something to notify the user. maybe alert(data.msg);
							alert(data['msg']);
			            } else {
			                // It worked, carry on...
			                $('#static_mailchimp #mc_embed_signup form').fadeOut('slow')
			                $('.staticSuccessMessage').delay(1000).fadeIn('slow');
			            }
			        }
			    });
			}

		});

		$('.chesterfieldColumn').click(function() {
			var $anchor = $('#chesterfieldRow').offset();
			$('html, body').animate({ scrollTop: $anchor.top - 50 }, 1000);
		});
		$('.watfordColumn').click(function() {
			var $anchor = $('#watfordRow').offset();
			$('html, body').animate({ scrollTop: $anchor.top - 50 }, 1000);
		});
		$('.aberdeenColumn').click(function() {
			var $anchor = $('#aberdeenRow').offset();
			$('html, body').animate({ scrollTop: $anchor.top - 50 }, 1000);
		});
		$('.stirlingColumn').click(function() {
			var $anchor = $('#stirlingRow').offset();
			$('html, body').animate({ scrollTop: $anchor.top - 50 }, 1000);
		});

		$(window).on("load resize",function(e){
			var windowWidth = $(window).width();
			if(windowWidth < 613 && windowWidth > 420) {
				var menuWidth = $('#menu-item-1964').width() + $('#menu-item-1965').width() + $('#menu-item-1638').width() + $('#menu-item-1635').width();
				var someWidth = windowWidth - menuWidth;
				var cssMargin = (someWidth / 2) - 50;
				$('#menu-item-1964').css({marginLeft: cssMargin+'px'});
			}
			else if(windowWidth > 613 || windowWidth < 420) {
				$('#menu-item-1964').css({marginLeft: '10px'});
			}
		});

		$(window).scroll(function(){
		  var sticky = $('.kl-main-header'),
		      scroll = $(window).scrollTop();
		      var $headerHeight = $('.kl-top-header').height();
		  if (scroll >= $headerHeight) sticky.addClass('fixed');
		  else sticky.removeClass('fixed');
		});

		$( document ).ajaxComplete(function() {
			$('#payment_method_cod').click(function(){
				alert($('.payment_method_cod p').text());
			});
		});

	});
</script>
</body>
</html>