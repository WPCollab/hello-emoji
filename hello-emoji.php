<?php
/*
Plugin Name: Hello Emoji
Plugin URI: http://wordpress.org/plugins/hello-emoji/
Description: Hello Emoji is a plugin that converts emoticons to emoji. But it's more than a plugin, it symbolizes the hope and enthusiasm of an entire generation. It's proof of the idea that WordPress represents: that free software can bring people together to accomplish something that they couldn’t do themselves, while adding something of value to the commons for all to share.
Version: 0.1.0
Author: WPCollab Team
Author URI: https://github.com/WPCollab/Hello-Emoji/graphs/contributors
License: GPL2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: hello-emoji
Domain Path: /languages

	Hello Emoji
	Copyright (C) 2014 WPCollab Team (https://github.com/WPCollab/Hello-Emoji/graphs/contributors)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
/**
 * @author		WPCollab Team
 * @copyright	Copyright (c) 2014, WPCollab Team
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @package		WPCollab\HelloEmoji
 * @version		0.1.0
 */

//avoid direct calls to this file
if ( !defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/** Register autoloader */
spl_autoload_register( 'WPCollab_HelloEmoji::autoload' );

/**
 * Main class to run the plugin
 *
 * @since	0.1.0
 */
class WPCollab_HelloEmoji {

	/**
	 * Holds a copy of the object for easy reference.
	 *
	 * @since	0.1.0
	 * @static
	 * @access	private
	 * @var		object	$instance
	 */
	private static $instance;

	/**
	 * Current version of the plugin.
	 *
	 * @since	0.1.0
	 * @static
	 * @access	public
	 * @var		string	$version
	 */
	public static $version = '0.1.0';

	/**
	 * Holds a copy of the main plugin filepath.
	 *
	 * @since	0.1.0
	 * @access	private
	 * @var		string	$file
	 */
	private static $file = __FILE__;

	/**
	 * Constructor. Hooks all interactions to initialize the class.
	 *
	 * @since	0.1.0
	 * @access	public
	 *
	 * @see	add_action()
	 * @see	register_activation_hook()
	 *
	 * @return	void
	 */
	public function __construct() {

		self::$instance = $this;

		if ( is_admin() ) {

			new WPCollab_HelloEmoji_Admin();

		} elseif ( !is_admin() ) {

			new WPCollab_HelloEmoji_Frontend();

		}

		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

		register_activation_hook( __FILE__, array( 'WPCollab_HelloEmoji', 'activate_plugin' ) );

	} // END __construct()

	/**
	 * The default value for the settings.
	 *
	 * @since	0.1.0
	 * @access	public
	 *
	 * @see		apply_filters()
	 *
	 * @return	array
	 */
	public function get_defaults() {

		$defaults = array(
			'comment' => true
		);

		$options = apply_filters( 'wpcollab_hello_emoji_defaults', $defaults );

		return $options;

	} // END get_defaults()

	/**
	 * Autoloader to load classes as needed.
	 *
	 * @since	0.1.0
	 * @static
	 * @access	public
	 *
	 * @param	string	$classname	The name of the class
	 * @return	null	Return early if the class name does not start with the correct prefix
	 */
	public static function autoload( $classname ) {

		if ( stripos( $classname, 'WPCollab_HelloEmoji_' ) !== false ) {

			$class_name = trim( $classname, 'WPCollab_HelloEmoji_' );
			$file_path = __DIR__ . '/classes/class-' . strtolower( $class_name ) . '.php';

			if ( file_exists( $file_path ) ) {
				require_once $file_path;
			}

		} else {

			return;

		}

	} // END autoload()

	/**
	 * Getter method for retrieving the object instance.
	 *
	 * @since	0.1.0
	 * @static
	 * @access	public
	 *
	 * @return	object	WPCollab_HelloEmoji::$instance
	 */
	public static function get_instance() {

		return self::$instance;

	} // END get_instance()

	/**
	 * Getter method for retrieving the main plugin filepath.
	 *
	 * @since	0.1.0
	 * @static
	 * @access	public
	 *
	 * @return	string	self::$file
	 */
	public static function get_file() {

		return self::$file;

	} // END get_file()

	/**
	 * Load the plugin's textdomain hooked to 'plugins_loaded'.
	 *
	 * @since	0.1.0
	 * @access	public
	 *
	 * @see		load_plugin_textdomain()
	 * @see		plugin_basename()
	 * @action	plugins_loaded
	 *
	 * @return	void
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'hello-emoji',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/'
		);

	} // END load_plugin_textdomain()

	/**
	 * Fired when plugin is activated
	 *
	 * @since	0.1.0
	 * @static
	 * @access	public
	 *
	 * @action	register_activation_hook
	 *
	 * @param	bool	$network_wide TRUE if WPMU 'super admin' uses Network Activate option
	 * @return	void
	 */
	public static function activate_plugin( $network_wide ) {

		$defaults = self::get_defaults();

		if ( is_multisite() && ( true == $network_wide ) ) {

			global $wpdb;
			$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );

			if ( $blogs ) {
				foreach( $blogs as $blog ) {
					switch_to_blog( $blog['blog_id'] );
					add_option( 'wpcollab_hello_emoji_settings', $defaults );
				}
				restore_current_blog();
			}

		} else {

			add_option( 'wpcollab_hello_emoji_settings', $defaults );

		}

	} // END activate_plugin()

} // END class WPCollab_HelloEmoji

/**
 * Instantiate the main class
 *
 * @since	0.1.0
 * @access	public
 *
 * @global	object	$wpcollab_helloemoji
 * @var	object	$wpcollab_helloemoji holds the instantiated class {@uses WPCollab_HelloEmoji}
 */
global $wpcollab_helloemoji;
$wpcollab_helloemoji = new WPCollab_HelloEmoji();
