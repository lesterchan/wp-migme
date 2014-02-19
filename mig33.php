<?php
/*
Plugin Name: mig33
Plugin URI: http://lesterchan.net/portfolio/programming/php/
Description: Share a post to mig33's Miniblog whenever you publish a post in WordPress.
Version: 1.0.0
Author: Lester 'GaMerZ' Chan
Author URI: http://lesterchan.net
Text Domain: mig33
*/

/*
	Copyright 2014  Lester Chan  (email : lesterchan@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


/**
 * Drafts for Friends version
 */
define( 'MIG33', '1.0.0' );


/**
 * Class Mig33
 *
 * @access public
 */
class Mig33
{
	/**
	 * Storing this plugin options
	 *
	 * @access private
	 * @var array Plugin options
	 */
	private $option = null;

	/**
	 * The mig33 user's encrypted session id
	 *
	 * @access private
	 * @var string Encrypted session id
	 */
	private $eid = null;

	/**
	 * Constructor method
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		add_action( 'init', array( $this, 'init' ) );

		register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );
	}

	/**
	 * Init this plugin
	 *
	 * @access public
	 * @return void
	 */
	public function init()
	{
		$this->option = get_option( 'mig33' );

		// Admin
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		// Actions
		add_action( 'new_to_publish', array( $this, 'post_to_miniblog' ), 10, 1 );
		add_action( 'draft_to_publish', array( $this, 'post_to_miniblog' ), 10, 1 );
		add_action( 'pending_to_publish', array( $this, 'post_to_miniblog' ), 10, 1 );
		add_action( 'future_to_publish', array( $this, 'post_to_miniblog' ), 10, 1 );

		// Load Translation
		load_plugin_textdomain( 'mig33', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}


	/**
	 * What to do when the plugin is being activated
	 *
	 * @access public
	 * @param boolean $network_wide Is the plugin being network activated?
	 * @return void
	 */
	public function plugin_activation( $network_wide )
	{
		$option_name = 'mig33';
		$option = array(
			  'username'    => ''
			, 'password'    => ''
			, 'url'         => 'mig33.com'
			, 'template'    => array( 'new_post' => '%POST_TITLE% - %POST_URL%' )
		);

		if ( is_multisite() && $network_wide )
		{
			$ms_sites = wp_get_sites();

			if( 0 < sizeof( $ms_sites ) )
			{
				foreach ( $ms_sites as $ms_site )
				{
					switch_to_blog( $ms_site['blog_id'] );
					add_option( $option_name, $option );
				}
			}

			restore_current_blog();
		}
		else
		{
			add_option( $option_name, $option );
		}
	}

	/**
	 * Add admin menu to WordPress
	 *
	 * @access public
	 * @return void
	 */
	public function admin_menu()
	{
		add_options_page( 'mig33', 'mig33', 'publish_posts', 'mig33', array( $this, 'admin_page' ) );
	}

	/**
	 * Display admin notices
	 *
	 * @access public
	 * @return void
	 */
	public function admin_notices()
	{
		if( isset( $_GET['mig33_message'] ) )
		{
			switch( intval( $_GET['mig33_message'] ) )
			{
				case 0:
					echo '<div class="error"><p>'.__( 'Error posting to mig33\'s Miniblog.', 'mig33' ).'</p></div>';
					break;
				case 1:
					echo '<div class="updated"><p>'.__( 'Successfully posted to mig33\'s Miniblog.', 'mig33' ).'</p></div>';
					break;
			}
		}
	}

	/**
	 * Display admin page
	 *
	 * @access public
	 * @return void
	 */
	public function admin_page()
	{
		if( !empty( $_POST['do'] ) )
		{
			check_admin_referer( 'mig33-options' );

			$option = array(
				  'url'         => sanitize_text_field( $_POST['url'] )
				, 'username'    => sanitize_text_field( $_POST['username'] )
				, 'password'    => sanitize_text_field( $_POST['password'] )
				, 'template'    => array(
					'new_post'  => sanitize_text_field( $_POST['template_new_post'] )
				)
			);

			$update_option = update_option( 'mig33' , $option );

			if( $update_option )
			{
				$this->option = get_option( 'mig33' );
				echo '<div id="message" class="updated fade"><p>'.__( 'Options Updated', 'mig33' ).'</p></div>';
			}
			else
			{
				echo '<div id="message" class="updated fade"><p>'.__( 'No changes have been made', 'mig33' ).'</p></div>';
			}
		}
		?>
		<div class="wrap">
			<h2>mig33</h2>
			<form action="<?php echo admin_url( 'options-general.php?page=mig33' ); ?>" method="post">
				<?php wp_nonce_field( 'mig33-options' ); ?>
				<h3><?php _e( 'Credentials', 'mig33' ); ?></h3>
				<table class="form-table">
					<tr>
						<th scope="row"><?php _e( 'mig33 URL', 'mig33' ); ?></th>
						<td>
							<input type="text" name="url" value="<?php echo ( isset( $this->option['url'] ) ? $this->option['url'] : 'mig33.com' ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Username', 'mig33' ); ?></th>
						<td>
							<input type="text" name="username" value="<?php echo ( isset( $this->option['username'] ) ? $this->option['username'] : '' ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Password', 'mig33' ); ?></th>
						<td>
							<input type="password" name="password" value="<?php echo ( isset( $this->option['password'] ) ? $this->option['password'] : '' ); ?>" />
						</td>
					</tr>
				</table>
				<h3><?php _e( 'Templates', 'mig33' ); ?></h3>
				<table class="form-table">
					<tr>
						<td width="20%" scope="row">
							<strong><?php _e( 'Create Post', 'mig33' ); ?></strong><br /><br />
							<?php _e( 'Supported template variables: ', 'mig33' ); ?>
							<ul>
								<li>%POST_TITLE%</li>
								<li>%POST_EXCERPT%</li>
								<li>%POST_URL%</li>
								<li>%POST_ID%</li>
								<li>%POST_AUTHOR%</li>
								<li>%POST_DATE%</li>
								<li>%POST_TIME%</li>
								<li>%POST_TAGS%</li>
								<li>%POST_CATEGORIES%</li>
							</ul>
						</td>
						<td width="80%" >
							<textarea name="template_new_post" rows="10" cols="30" style="width: 100%;"><?php echo ( isset( $this->option['template']['new_post'] ) ? $this->option['template']['new_post'] : '%POST_TITLE% - %POST_URL%' ); ?></textarea>
						</td>
					</tr>
				</table>
				<p class="submit"><input type="submit" class="button-primary" name="do" value="<?php _e( 'Save Changes', 'mig33' ); ?>" /></p>
			</form>
		</div>
<?php
	}

	/**
	 * Login to mig33 and get the user's encrypted session id
	 *
	 * @access private
	 * @return void
	 */
	private function login_and_get_eid() {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL,'https://login.'.$this->option['url'] );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, array(
			  'mig33-username' => $this->option['username']
			, 'mig33-password' => $this->option['password']
		));
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_HEADER, 1 );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
		preg_match( '/^Set-Cookie: eid=(.*?);/m', curl_exec( $ch ), $cookies );
		curl_close( $ch );

		// Cookie Value
		$this->eid = ( isset( $cookies[1] ) ? $cookies[1] : '' );
	}

	/**
	 * Post to mig33's Miniblog
	 *
	 * @access public
	 * @param object WordPress's post object
	 * @return void
	 */
	public function post_to_miniblog( $post ) {
		// Get eid
		$this->login_and_get_eid();

		// Check if eid is empty
		if( empty( $this->eid ) )
		{
			add_filter( 'redirect_post_location',  array( $this, 'set_post_to_miniblog_error' ) );
			return;
		}

		// Post ID
		$post_id = intval( $post->ID );

		// Tags
		$tags_array = array();
		$tags = get_the_tags( $post_id );
		if( $tags && sizeof( $tags ) > 0 )
		{
			foreach( $tags as $tag )
			{
				$tags_array[] = '#' . $tag->name;
			}
		}

		// Categories
		$categories_array = array();
		$categories = wp_get_post_categories( $post_id );
		if( $categories && sizeof( $categories ) > 0 )
		{
			foreach( $categories as $category )
			{
				$cat = get_category( $category );
				$categories_array[] = $cat->name;
			}
		}

		// Body
		$body = str_replace(
			array(
				  '%POST_TITLE%'
				, '%POST_EXCERPT%'
				, '%POST_URL%'
				, '%POST_ID%'
				, '%POST_AUTHOR%'
				, '%POST_DATE%'
				, '%POST_TIME%'
				, '%POST_TAGS%'
				, '%POST_CATEGORIES%'
			),
			array(
				  get_the_title( $post )
				, $this->snippet_text( wp_strip_all_tags( $post->post_content, true ), 100 )
				, get_permalink( $post )
				, $post_id
				, get_the_author_meta( 'display_name', $post->post_author )
				, mysql2date( get_option( 'date_format' ), $post->post_date )
				, mysql2date( get_option( 'time_format' ), $post->post_date )
				, implode( ' ', $tags_array )
				, implode( ', ', $categories_array )
			),
			$this->option['template']['new_post']
		);

		// Post
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, 'http://www.'.$this->option['url'].'/post/hidden_post' );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, array(
			  'body'                => $body
			, 'originality'         => 1
			, 'reply_permission'    => 0
			, 'privacy'             => 0
		));
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_COOKIE, 'eid='.$this->eid );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
		$results = curl_exec( $ch );
		curl_close( $ch );

		// Success or error?
		if( empty( $results ) )
		{
			add_filter( 'redirect_post_location',  array( $this, 'set_post_to_miniblog_success' ) );
		}
		else
		{
			add_filter( 'redirect_post_location',  array( $this, 'set_post_to_miniblog_error' ) );
		}
	}

	/**
	 * Flag post to miniblog status as success
	 *
	 * @access public
	 * @param string $url URL
	 * @return string New URL query string
	 */
	public function set_post_to_miniblog_success( $url )
	{
		return add_query_arg( 'mig33_message', 1, $url );
	}

	/**
	 * Flag post to miniblog status as error
	 *
	 * @access public
	 * @param string $url URL
	 * @return string New URL query string
	 */
	public function set_post_to_miniblog_error( $url )
	{
		return add_query_arg( 'mig33_message', 0, $url );
	}

	/**
	 * Show only a snippet of a text
	 *
	 * @access private
	 * @param string $text Text to be truncated
	 * @param int $length Optional. Number of characters allowed
	 * @return string Truncated text
	 */
	private function snippet_text( $text, $length = 0 )
	{
		if( defined( 'MB_OVERLOAD_STRING' ) )
		{
			$text = @html_entity_decode( $text, ENT_QUOTES, get_option( 'blog_charset' ) );
			if ( mb_strlen( $text ) > $length )
			{
				return htmlentities( mb_substr( $text, 0, $length ), ENT_COMPAT, get_option( 'blog_charset' ) ).'...';
			}
			else
			{
				return htmlentities( $text, ENT_COMPAT, get_option( 'blog_charset' ) );
			}
		}
		else
		{
			$text = @html_entity_decode( $text, ENT_QUOTES, get_option( 'blog_charset' ) );
			if ( strlen( $text ) > $length )
			{
				return htmlentities( substr( $text, 0, $length ), ENT_COMPAT, get_option( 'blog_charset' ) ).'...';
			}
			else
			{
				return htmlentities( $text, ENT_COMPAT, get_option( 'blog_charset' ) );
			}
		}
	}
}
new Mig33();

/**
 * Embed mig33's Follow Button
 *
 * @access public
 * @param string $username Optional. mig33's username
 * @return string Print out an iFrame that displays the mig33's Follow Button
 */
function mig33_follow_button( $username = '' )
{
	$options = get_option( 'mig33' );
	$params = array(
		'account' => ( !empty( $username ) ? $username : $options['username'] )
	);
	echo '<iframe allowtransparency="true" frameborder="0" scrolling="no" src="http://www.'.$options['url'].'/share_to_mig33/button/follow?'.http_build_query( $params ).'" style="width:150px; height:20px;"></iframe>';
}

/**
 * Embed mig33's Share Button
 *
 * @access public
 * @param string $body Optional. Post title
 * @param string $link Optional. Link to the post
 * @param string $username Optional. mig33's username
 * @return string Print out an iFrame that displays the mig33's Share Button
 */
function mig33_share_button( $body  = '', $link = '', $username = '' )
{
	$options = get_option( 'mig33' );
	$link = ( !empty( $link ) ? $link : get_permalink() );
	$params = array(
		  'body'      => ( !empty( $body ) ? $body : get_the_title() ). ' - '.$link
		, 'link'      => $link
		, 'account'   => ( !empty( $username ) ? $username : $options['username'] )
	);
	echo '<iframe allowtransparency="true" frameborder="0" scrolling="no" src="http://www.'.$options['url'].'/share_to_mig33/button/share?'.http_build_query( $params ).'" style="width:80px; height:18px;"></iframe>';
}