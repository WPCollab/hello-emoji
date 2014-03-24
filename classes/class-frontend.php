<?php
/**
 * @author WPCollab Team
 * @copyright Copyright (c) 2014, WPCollab Team
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @package WPCollab\HelloEmoji\Frontend
 */

//avoid direct calls to this file
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * @since 0.1.0
 */
class WPCollab_HelloEmoji_Frontend {

	/**
	 * Holds a copy of the object for easy reference.
	 *
	 * @since 0.1.0
	 * @static
	 * @access private
	 * @var object $instance
	 */
	private static $instance;

	/**
	 * Getter method for retrieving the object instance.
	 *
	 * @since 0.1.0
	 * @static
	 * @access public
	 *
	 * @return object WPCollab_HelloEmoji_Frontend::$instance
	 */
	public static function get_instance() {

		return self::$instance;

	} // END get_instance()

	/**
	 * Constructor. Hooks all interactions to initialize the class.
	 *
	 * @since 0.1.0
	 * @access public
	 *
	 * @return void
	 */
	public function __construct() {

		self::$instance = $this;

		// Filters to add css classes that are targeted by the JS
		$filters = $this->output_filters();
		foreach ( $filters as $filter ) {
			add_filter( $filter, array( 'WPCollab_HelloEmoji_Frontend', 'wrap_content' ) );
		}

		// Load admin JavaScript
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );

		// Set global $wpsmiliestrans
		add_action( 'init', array( $this, 'smilies_init' ), 4 );

	} // END __construct()

	/**
	 * Add class to content and comment.
	 *
	 * @since 0.1.0
	 * @static
	 * @access public
	 *
	 * @param string $content Post content or comment text
	 * @return string Content
	 */
	public static function wrap_content( $content ) {

		if ( ! empty( $content ) ) {

			$settings = get_option( 'wpcollab_hello_emoji_settings' );
			// Check if comment is enabled
			if ( 'comment_text' == current_filter() && empty( $settings['comment'] ) ) {
				return $content;
			}

			$classes = apply_filters( 'wpcollab_hello_emoji_css_classes', array( 'wpcollab-hello-emoji' ) );
			$open_tag = '<div class="' . join( ' ', $classes ) . '">';
			$close_tag = '</div>';

			return $open_tag . $content . $close_tag;
		}

		return $content;

	} // END wrap_content()

	/**
	 * Enqueue frontend scripts.
	 *
	 * @since 0.1.0
	 * @access public
	 *
	 * @see get_option()
	 * @see is_singular()
	 * @see comments_open()
	 * @see wp_enqueue_script()
	 * @see plugins_url()
	 * @see wp_enqueue_style()
	 *
	 * @return void
	 */
	public function enqueue_frontend_scripts() {

		$dev = apply_filters( 'wpcollab_hello_emoji_debug_mode', WP_DEBUG ) ? '' : '.min';

		wp_register_script( 'jquery-textcomplete-script', plugins_url( "lib/jquery-textcomplete/jquery.textcomplete{$dev}.js", WPCollab_HelloEmoji::get_file() ), array( 'jquery' ), WPCollab_HelloEmoji::$version, true );

		wp_enqueue_script( 'hello-emoji-script', plugins_url( "js/hello-emoji{$dev}.js", WPCollab_HelloEmoji::get_file() ), array( 'jquery-textcomplete-script' ), WPCollab_HelloEmoji::$version, true );

		wp_enqueue_style( 'hello-emoji-style', plugins_url( 'css/hello-emoji.css', WPCollab_HelloEmoji::get_file() ), array(), WPCollab_HelloEmoji::$version );

		wp_localize_script( 'hello-emoji-script', 'hello_emoji', array( 'images_src' => plugins_url( 'images/emoji', WPCollab_HelloEmoji::get_file() ) ) );

	} // END enqueue_frontend_scripts()

	/**
	 * Change global $wpsmiliestrans in WordPress.
	 *
	 * @since 0.1.0
	 * @access public
	 *
	 * @return void
	 */
	public function smilies_init() {

		global $wpsmiliestrans;

		if ( !isset( $wpsmiliestrans ) ) {
			$wpsmiliestrans = array(
				':mrgreen:' => 'icon_mrgreen.gif',
				':neutral:' => 'icon_neutral.gif',
				':twisted:' => 'icon_twisted.gif',
				  ':arrow:' => 'icon_arrow.gif',
				  ':shock:' => 'icon_eek.gif',
				    ':???:' => 'icon_confused.gif',
				   ':evil:' => 'icon_evil.gif',
				   ':idea:' => 'icon_idea.gif',
				   ':oops:' => 'icon_redface.gif',
				   ':razz:' => 'icon_razz.gif',
				   ':roll:' => 'icon_rolleyes.gif',
				    ':eek:' => 'icon_surprised.gif',
				    ':lol:' => 'icon_lol.gif',
				    ':mad:' => 'icon_mad.gif',
				    ':sad:' => 'icon_sad.gif',
				      '8-)' => 'icon_cool.gif',
				      '8-O' => 'icon_eek.gif',
				      ':-(' => 'icon_sad.gif',
				      ':-)' => 'icon_smile.gif',
				      ':-?' => 'icon_confused.gif',
				      ':-D' => 'icon_biggrin.gif',
				      ':-P' => 'icon_razz.gif',
				      ':-o' => 'icon_surprised.gif',
				      ':-x' => 'icon_mad.gif',
				      ':-|' => 'icon_neutral.gif',
				      ';-)' => 'icon_wink.gif',
				// This one transformation breaks regular text with frequency.
				//     '8)' => 'icon_cool.gif',
				       '8O' => 'icon_eek.gif',
				       ':(' => 'icon_sad.gif',
				       ':)' => 'icon_smile.gif',
				       ':?' => 'icon_confused.gif',
				       ':D' => 'icon_biggrin.gif',
				       ':P' => 'icon_razz.gif',
				       ':o' => 'icon_surprised.gif',
				       ':x' => 'icon_mad.gif',
				       ':|' => 'icon_neutral.gif',
				       ';)' => 'icon_wink.gif',
				      ':!:' => 'icon_exclaim.gif',
				      ':?:' => 'icon_question.gif',
			);
		}

	} // END smilies_init()

	/**
	* Sets the filters hooked to wrap_content()
	*
	* @since 0.2.0
	* @access public
	*
	* @see wrap_content()
	* @see _construct()
	*
	* @return array
	*/
	public function output_filters() {
		$filters = array( 'the_content', 'comment_text' );

		/**
		* Add or replace filters used to wrap in div for emoji processing.
		*
		* @param array $filters A list of filters to hook wrap_content to.
		*
		* @since 0.2.0
		*/
		$filters = apply_filters( 'wpcollab_hello_emoji_output_filters', $filters );
		return $filters;

	} //end output_filters()

} // END class WPCollab_HelloEmoji_Frontend
