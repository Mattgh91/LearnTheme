<?php if(! defined('ABSPATH')){ return; }

// widget function
class tp_widget_recent_tweets extends WP_Widget
{

	public function __construct()
	{
		parent::__construct( 'tp_widget_recent_tweets', // Base ID
			__( '[ Kallyas ] Twitter Widget', 'zn_framework' ), // Name
			array ( 'description' => __( 'Display recent tweets', 'zn_framework' ), ) // Args
		);

		add_action('wp_enqueue_scripts', array($this, 'loadCarouFredSel'));
	}

	public function loadCarouFredSel(){
		if(wp_script_is('caroufredsel', 'registered')) {
			wp_enqueue_script( 'caroufredsel' );
		}
		else {
			wp_enqueue_script( 'caroufredsel', THEME_BASE_URI . '/addons/caroufredsel/jquery.carouFredSel-packed.js',
				array ( 'jquery' ), ZN_FW_VERSION, true );
		}
	}

    public function getConnectionWithAccessToken( $cons_key, $cons_secret, $oauth_token, $oauth_token_secret )
    {
        if ( ! class_exists( 'TwitterOAuth' ) ) {
            if ( ! require_once( THEME_BASE . '/template_helpers/widgets/twitter/twitteroauth.php' ) ) {
				if(defined('WP_DEBUG') && WP_DEBUG) {
					error_log(__METHOD__. "() Error: Couldn't find TwitterOAuth.php here: " . THEME_BASE .
								  '/template_helpers/widgets/twitter/twitteroauth.php' );
				}
                return null;
            }
        }
        $connection = new TwitterOAuth( $cons_key, $cons_secret, $oauth_token, $oauth_token_secret );
        return $connection;
    }

	//widget output
	public function widget( $args, $instance )
	{

		// Check if Curl is installed on the server and show an error message if it is not
		if( ! function_exists('curl_init') ){
			echo __('It seems that the curl is not activated on your hosting. This widget requires this function in order to work. Please contact your server administrator and ask them to enable curl for your account.', 'zn_framework');
			return;
		}

		$before_widget = $before_title = $after_title =  $after_widget = '';

		extract( $args );
		if ( ! empty( $instance['title'] ) ) {
			$title = apply_filters( 'widget_title', $instance['title'] );
		}

		echo $before_widget;
		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}

   		// Fixes the error when the widget was not displayed when the "cachetime" field was not set in the widget
		if(!isset($instance['cachetime']) || empty($instance['cachetime'])){
			$instance['cachetime'] = 1;
		}
        else {
            $instance['cachetime'] = intval($instance['cachetime']);
        }

		//check settings and die if not set
		if ( empty( $instance['consumerkey'] ) ||
             empty( $instance['consumersecret'] ) ||
             empty( $instance['accesstoken'] ) ||
             empty( $instance['accesstokensecret'] ) ||
             empty( $instance['username'] ) )
        {
			echo '<strong>' . __( 'Please fill all widget settings!', 'zn_framework' ) . '</strong>' . $after_widget;
			return;
		}

		//check if cache needs update
		$tp_twitter_plugin_last_cache_time = get_option( 'tp_twitter_plugin_last_cache_time' );
		$diff                              = time() - $tp_twitter_plugin_last_cache_time;
		$crt                               = $instance['cachetime'] * 3600;
		$tp_twitter_plugin_tweets = maybe_unserialize( get_option( 'tp_twitter_plugin_tweets' ) );


		//	Cache expired or no tweets found
		if ( $diff >= $crt || empty( $tp_twitter_plugin_last_cache_time ) || empty($tp_twitter_plugin_tweets) )
		{
			$connection = $this->getConnectionWithAccessToken(
                $instance['consumerkey'],
                $instance['consumersecret'],
                $instance['accesstoken'],
                $instance['accesstokensecret']
            );
            if(empty($connection) || !($connection instanceof TwitterOAuth)){
                exit(__( "Couldn't retrieve tweets! Wrong username?", 'zn_framework' ));
            }
			$tweets = $connection->get( "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name="
                                        .$instance['username'] . "&count=10" );

			if ( ! empty( $tweets->errors ) ) {
				if ( $tweets->errors[0]->message == 'Invalid or expired token' ) {
					$url = 'https://dev.twitter.com/apps';
					echo '<strong>' . $tweets->errors[0]->message . '!</strong><br />' .
                         __( "You'll need to regenerate it", 'zn_framework' ) .
                         ' <a href="'.$url.'" target="_blank">' . __( 'here', 'zn_framework' ) . '</a>!'
                         . $after_widget;
				}
				else {
					echo '<strong>' . $tweets->errors[0]->message . '</strong>' . $after_widget;
				}
				return;
			}

            $tweets_array = array();
			for ( $i = 0; $i <= count( $tweets ); $i ++ ) {
				if ( ! empty( $tweets[ $i ] ) ) {
					$tweets_array[ $i ]['created_at'] = $tweets[ $i ]->created_at;
					$tweets_array[ $i ]['text']       = $tweets[ $i ]->text;
					$tweets_array[ $i ]['status_id']  = $tweets[ $i ]->id_str;
				}
			}

			//save tweets to wp option
			update_option( 'tp_twitter_plugin_tweets', serialize( $tweets_array ) );
			update_option( 'tp_twitter_plugin_last_cache_time', time() );
		}

		if ( ! empty( $tp_twitter_plugin_tweets ) )
        {
			echo '<script type="text/javascript">jQuery(window).load(function () {
jQuery.getScript("//platform.twitter.com/widgets.js"); });</script>';

			echo   '<div class="twitter-feed">';
			$numTweets = intval($instance['tweetstoshow']);
			if(empty($numTweets)){
				$numTweets = 1;
			}
			echo '<div class="twitterFeed-wrapper twitter-feed-wrapper">';
				echo   '<div class="tweets twitterFeed twitter-feed-tweets" id="twitterFeed" data-entries="'.$numTweets.'">';
				$fctr = '1';
				if ( is_array( $tp_twitter_plugin_tweets ) ) {
					foreach ( $tp_twitter_plugin_tweets as $tweet ) {
						echo '<div class="kl-tweet twitter-feed-tweet">
								<a class="twTime twitter-feed-tweet-time" target="_blank" href="http://twitter.com/' . $instance['username'] .
	                         '/statuses/' . $tweet['status_id'] . '"><span>' . $this->_relative_time($tweet['created_at'] ) .
	                         '</span></a>' . $this->_convert_links( $tweet['text'] ) . '</div>';
						if ( $fctr == $numTweets ) {
							break;
						}
						$fctr ++;
					}
				}

				echo '</div>';
			echo '</div>'; //.twitterFeed-wrapper

			echo '<a href="https://twitter.com/' . $instance['username'] .
                 '" class="twitter-follow-button" data-show-count="false">' . __( 'Follow', 'zn_framework' ) . ' @' .
                 $instance['username'] . '</a>';
			echo '</div><!-- end twitter-feed -->';
		}
		echo $after_widget;
	}

	//convert dates to readable format
	// @internal
	//@see $this->widget()
	function _relative_time( $a )
	{
		//get current timestampt
		$b = strtotime( "now" );
		//get timestamp when tweet created
		$c = strtotime( $a );
		//get difference
		$d = $b - $c;
		//calculate different time values
		$minute = 60;
		$hour   = $minute * 60;
		$day    = $hour * 24;
		$week   = $day * 7;

		if ( is_numeric( $d ) && $d > 0 ) {
			//if less then 3 seconds
			if ( $d < 3 ) {
				return __( "right now", 'zn_framework' );
			}
			//if less then minute
			if ( $d < $minute ) {
				return floor( $d ) . __( " seconds ago", 'zn_framework' );
			}
			//if less then 2 minutes
			if ( $d < $minute * 2 ) {
				return __( "about 1 minute ago", 'zn_framework' );
			}
			//if less then hour
			if ( $d < $hour ) {
				return floor( $d / $minute ) . __( " minutes ago", 'zn_framework' );
			}
			//if less then 2 hours
			if ( $d < $hour * 2 ) {
				return __( "about 1 hour ago", 'zn_framework' );
			}
			//if less then day
			if ( $d < $day ) {
				return floor( $d / $hour ) . __( " hours ago", 'zn_framework' );
			}
			//if more then day, but less then 2 days
			if ( $d > $day && $d < $day * 2 ) {
				return __( "yesterday", 'zn_framework' );
			}
			//if less then year
			if ( $d < $day * 365 ) {
				return floor( $d / $day ) . __( " days ago", 'zn_framework' );
			}
			//else return more than a year
			return __( "over a year ago", 'zn_framework' );
		}
	}

	//convert links to clickable format
	// @internal
	//@see $this->widget()
	function _convert_links( $status, $targetBlank = true, $linkMaxLen = 250 )
	{
		// the target
		$target = $targetBlank ? " target=\"_blank\" " : "";

		// convert link to url
		$pattern = "/((http:\/\/|https:\/\/)[^ )]+)/";
		// $status = preg_replace( "/((http:\/\/|https:\/\/)[^ )]+)/e", "'<a href=\"$1\" title=\"$1\" $target >'. ((strlen('$1')>=$linkMaxLen ? substr('$1',0,$linkMaxLen).'...':'$1')).'</a>'", $status );

		$status = preg_replace_callback( $pattern, array($this,'_replace_link'), $status );

		// convert @ to follow
		$status = preg_replace( "/(@([_a-z0-9\-]+))/i", "<a href=\"http://twitter.com/$2\" title=\"Follow $2\" $target >$1</a>", $status );

		// convert # to search
		$status = preg_replace( "/(#([_a-z0-9\-]+))/i", "<a href=\"https://twitter.com/search?q=$2\" title=\"Search $1\" $target >$1</a>", $status );

		// return the status
		return $status;
	}

	// Internal
	//@see $this->widget()
	public function _replace_link( $matches ){
		$linkMaxLen = 250;
		$text = strlen($matches[1]) >= $linkMaxLen ? substr($matches[1],0,$linkMaxLen).'...': $matches[1];
		$return = '<a href="'.$matches[1].'" title="'.$matches[1].'" target="_blank">'. $text .'</a>';
		return $return;
	}


	//save widget settings
	public function update( $new_instance, $old_instance )
	{
		$instance = array (
            'title' => (isset($new_instance['title']) ? strip_tags( $new_instance['title'] ) : ''),
            'consumerkey' => (isset($new_instance['consumerkey']) ? strip_tags( $new_instance['consumerkey'] ) : ''),
            'consumersecret' => (isset($new_instance['consumersecret']) ? strip_tags($new_instance['consumersecret'] ) : ''),
            'accesstoken' => (isset($new_instance['accesstoken']) ? strip_tags( $new_instance['accesstoken'] ) : ''),
            'accesstokensecret' => (isset($new_instance['accesstokensecret']) ? strip_tags($new_instance['accesstokensecret'] ) : ''),
            'cachetime' => (isset($new_instance['cachetime']) ? strip_tags( $new_instance['cachetime'] ) : ''),
            'username' => (isset($new_instance['username']) ? strip_tags( $new_instance['username'] ) : ''),
            'tweetstoshow' => (isset($new_instance['tweetstoshow']) ? strip_tags( $new_instance['tweetstoshow'] ) : ''),
        );
		if (isset($old_instance['username']) && $old_instance['username'] != $new_instance['username'] ) {
			delete_option( 'tp_twitter_plugin_last_cache_time' );
		}

		return $instance;
	}

	//widget settings form
	public function form( $instance )
	{
		$defaults = array ( 'title'             => '',
							'consumerkey'       => '',
							'consumersecret'    => '',
							'accesstoken'       => '',
							'accesstokensecret' => '',
							'cachetime'         => '',
							'username'          => '',
							'tweetstoshow'      => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		echo '
			<p><label>' . __( 'Title:', 'zn_framework' ) . '</label>
				<input type="text" name="' . $this->get_field_name( 'title' ) . '" id="' . $this->get_field_id( 'title' ) . '" value="' . esc_attr( $instance['title'] ) . '" class="widefat" /></p>
			<p><label>' . __( 'Consumer Key:', 'zn_framework' ) . '</label>
				<input type="text" name="' . $this->get_field_name( 'consumerkey' ) . '" id="' . $this->get_field_id( 'consumerkey' ) . '" value="' . esc_attr( $instance['consumerkey'] ) . '" class="widefat" /></p>
			<p><label>' . __( 'Consumer Secret:', 'zn_framework' ) . '</label>
				<input type="text" name="' . $this->get_field_name( 'consumersecret' ) . '" id="' . $this->get_field_id( 'consumersecret' ) . '" value="' . esc_attr( $instance['consumersecret'] ) . '" class="widefat" /></p>
			<p><label>' . __( 'Access Token:', 'zn_framework' ) . '</label>
				<input type="text" name="' . $this->get_field_name( 'accesstoken' ) . '" id="' . $this->get_field_id( 'accesstoken' ) . '" value="' . esc_attr( $instance['accesstoken'] ) . '" class="widefat" /></p>
			<p><label>' . __( 'Access Token Secret:', 'zn_framework' ) . '</label>
				<input type="text" name="' . $this->get_field_name( 'accesstokensecret' ) . '" id="' . $this->get_field_id( 'accesstokensecret' ) . '" value="' . esc_attr( $instance['accesstokensecret'] ) . '" class="widefat" /></p>
			<p><label>' . __( 'Cache Tweets in every:', 'zn_framework' ) . '</label>
				<input type="text" name="' . $this->get_field_name( 'cachetime' ) . '" id="' . $this->get_field_id( 'cachetime' ) . '" value="' . esc_attr( $instance['cachetime'] ) . '" class="small-text" /> hours</p>
			<p><label>' . __( 'Twitter Username:', 'zn_framework' ) . '</label>
				<input type="text" name="' . $this->get_field_name( 'username' ) . '" id="' . $this->get_field_id( 'username' ) . '" value="' . esc_attr( $instance['username'] ) . '" class="widefat" /></p>
			<p><label>' . __( 'Tweets to display:', 'zn_framework' ) . '</label>
				<select type="text" name="' . $this->get_field_name( 'tweetstoshow' ) . '" id="' . $this->get_field_id( 'tweetstoshow' ) . '">';

		$num = intval($instance['tweetstoshow']);
		for ( $i = 1; $i <= 10; $i ++ ) {
			echo '<option value="' . $i . '"' . selected($num, $i) . '>' . $i . '</option>';
		}
		echo '</select></p>';
	}
}


// register	widget
function register_tp_twitter_widget() {
	register_widget( 'tp_widget_recent_tweets' );
}
add_action( 'widgets_init', 'register_tp_twitter_widget' );
