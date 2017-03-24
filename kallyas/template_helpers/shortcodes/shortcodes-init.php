<?php if(! defined('ABSPATH')){ return; }

//<editor-fold desc=">>> REGISTER THE SHORTCODES BUTTON IN WP-EDITOR">
add_action( 'admin_init', 'zn_sc_button' );
function zn_sc_button() {
	if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
		return;
	}
	if ( get_user_option( 'rich_editing' ) == 'true' ) {
		add_filter( 'mce_external_plugins', 'add_plugin' );
		add_filter( 'mce_buttons', 'register_button' );
	}
}

function register_button( $buttons ) {
	array_push( $buttons, "|", "zn_button" );
	return $buttons;
}

function add_plugin( $plugin_array ) {
	$plugin_array['zn_button'] = THEME_BASE_URI . '/template_helpers/shortcodes/zn_sc_button.js';
	return $plugin_array;
}

function zn_sc_dialog() {
	$categories = null;
	include( THEME_BASE . '/template_helpers/shortcodes/helper-shortcodes.php' );

	$page = '';
	$i    = '0';
	?>
	<div class="zn_shortcodes_wrapper zn_hidden">
		<div class="zn_shortcodes_inner">
			<div id="zn_sidebar">
				<div id="zn-nav">
					<ul class="zn_activate_nav">
						<?php
							if ( ! empty( $categories ) ) {
								foreach ( $categories as $name => $shortcodes ) {
									$cls = $tabstyle = '';
									if ( $i == '0' ) {
										$cls = 'active';
										$tabstyle = 'style="display:block;"';
									}
									echo '<li><a rel="" href="#zn_page_' . $name . '" class="normal ' . $cls . '">' . $name . '</a></li>';

									$page .= '<div id="zn_page_' . $name . '" class="zn_page" '.$tabstyle.'>';
									$page .= '<h4 class="heading">' . $name . '</h4>';
									foreach ( $shortcodes as $shortcode_name => $shortcode_value ) {
										$page .= '<div class="zn_sc_container"><div class="zn_sc_title">' . $shortcode_name .
												 '</div><div class="zn_shortcode_text">' . $shortcode_value . '</div></div>';
									}
									$page .= '</div>';

									$i ++;
								}
							}
						?>
					</ul>
				</div>
			</div>
			<div id="content" class="site-content" >
				<?php echo $page; ?>
			</div>
		</div>
	</div>
<?php
}
//</editor-fold desc=">>> REGISTER THE SHORTCODES BUTTON IN WP-EDITOR">


/** Load the shortcodes dialog **/
add_action( 'admin_init', 'zn_add_shortcodes_dialog' );
function zn_add_shortcodes_dialog(){

	global $pagenow;
	if( $pagenow === 'post.php' || $pagenow === 'post-new.php' ) {
		add_action( 'admin_footer', 'zn_sc_dialog' );
		add_action( 'admin_head', 'zn_shortcodes_styles' );
	}
}

function zn_shortcodes_styles(){
	echo '<style>

		#zn-nav ul {
			margin:0px;
		}
		#zn-nav ul li {
			margin:0px;
		}
		#zn-nav li.parent ul {
			display:none;
		}
		#zn-nav li.parent>a {
			background:url("../images/plus_menu.png") no-repeat scroll 160px 12px #f7f7f7;
		}
		#zn-nav li.parent>a:hover {
			background:none repeat scroll 0 0 #F7F7F7;
			border-left:5px solid #F7F7F7;
			border-right:1px solid #EBEBEB;
		}
		#zn-nav li.parent>a:hover {
			background:url("../images/plus_menu2.png") no-repeat scroll 160px 12px #F7F7F7;
			border-right:1px solid #F7F7F7;
		}

.zn_page {
	display:none;
	padding:20px 0;
}

		#zn_sidebar {
			width:189px;
			z-index:9999;
			float:left;
			background:#f7f7f7;
		}
#zn-nav ul li a {
	padding:10px 10px;
	display:block;
	-webkit-box-shadow: 0 0 0 0;
	-moz-box-shadow: 0 0 0 0;
	box-shadow: 0 0 0 0;
}
#zn-nav * a:hover,
#zn-nav * a:active,
#zn-nav * a:focus,
#zn-nav #zn-nav ul li a:hover,
#zn-nav #zn-nav ul li a:active,
#zn-nav #zn-nav ul li a:focus{
	-webkit-box-shadow: 0 0 0 0 !important;
	-moz-box-shadow: 0 0 0 0 !important;
	box-shadow: 0 0 0 0 !important;
}
#zn-nav ul li {
	border-bottom:1px solid #ebebeb;
	border-top:1px solid #fff;
}
#zn-nav ul li a {
	background:none repeat scroll 0 0 #F0F0F0;
	color:#5C666C;
	text-decoration:none;
	border-right:1px solid #EBEBEB;
	border-left:5px solid #F7F7F7;
	margin-right: -1px;
}
#zn-nav ul li a:hover {
	background:#fff;
	border-left:5px solid #5EB4F1;
}
#zn-nav ul li a.active {
	background:#fff;
	color:#555;
	border-right:none;
	margin-right:0;
	position:relative;
	z-index:999;
	border-left:5px solid #5EB4F1;
}
#zn-nav ul li ul li a {
	padding-left:10px;
	color:#999999;
	border-left:5px solid #fff;
	background:none repeat scroll 0 0 #fff;
}
#zn-nav ul li ul li a:hover {
	border-left:5px solid #5EB4F1;
}
#zn-nav ul li ul li a.active {
	border-left:5px solid #5EB4F1;
}



		.zn_sc_dialog {
			display:none;
		}
		.zn_shortcode_text {
			display:none;
		}
		.zn_sc_container {
			padding:10px 20px;
			cursor:pointer;
		}
		.zn_sc_container:hover {
			background:#F7F7F7;
		}

.zn_page .zn_accordion.zn_dynamic_accordion ul li {
	width:auto;
}

.zn-modal-inner-content  #content .zn_page {
	padding-top:0px;
}

.zn-modal-inner-content  #content {
	border-left:1px solid #EBEBEB;
	margin-left:189px;
	padding-left:10px;
	padding-right:10px;
}

.zn-modal-inner-content .heading{
    margin-top: 0;
    padding-top: 20px;
}
	</style>';
}
