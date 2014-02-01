<?php
/**
 * @author		WPCollab Team
 * @copyright	Copyright (c) 2014, WPCollab Team
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @package		WPCollab\HelloEmoji\Admin
 */

//avoid direct calls to this file
if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * @todo Description
 * 
 * @since	1.0.0
 */
class WPCollab_HelloEmoji_Admin {
	
	/**
	 * Holds a copy of the object for easy reference.
	 * 
	 * @since	1.0.0
	 * @static
	 * @access	private
	 * @var		object	$instance
	 */
	private static $instance;

	/**
	 * Getter method for retrieving the object instance.
	 * 
	 * @since	1.0.0
	 * @static
	 * @access	public
	 * 
	 * @return	object	WPCollab_HelloEmoji_Admin::$instance
	 */
	public static function get_instance() {

		return self::$instance;
		
	} // END get_instance()

	/**
	 * Constructor. Hooks all interactions to initialize the class.
	 * 
	 * @since	1.0.0
	 * @access	public
	 * 
	 * @return	void
	 */
	public function __construct() {
		
		self::$instance = $this;
		
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'load-options-writing.php', array( $this, 'help_tabs' ) );
		
	} // END __construct()
	
	/**
	 * @todo description
	 * 
	 * @since	1.0.0
	 * @access	public
	 * 
	 * @see __()
	 * @see	add_settings_section()
	 * @see	add_settings_field()
	 * 
	 * @return	void
	 */
	public function register_settings() {
		
		add_settings_section(
			'emoji-settings',
			__( 'Emoji Settings', 'hallo-emoji' ),
			array( $this, 'defaults_desc'),
			'writing'
		);
		
		add_settings_field(
			'defaults-output',
			__( 'Defaults Field', 'hallo-emoji' ),
			array( $this, 'render_field' ), // @TODO
			'writing',
			'emoji-settings',
			array()
		);
		
	} // END register_settings()
	
	/**
	 * @todo
	 *
	 * @since	1.0.0
	 * @access	public
	 *
	 * @see		get_current_screen()
	 *
	 * @return	string
 	 */
	public function help_tabs() {
		
		/** @todo move to the end of the tabs */
		$screen = get_current_screen();
		$screen->add_help_tab(
			array(
				'id'        => 'wpcollab-hello-emoji_options',
				'title'     => __( 'Emoji Help', 'hello-emoji' ),
				'callback'  => '__return_empty_string' // array( $this, 'option_tab')
			)
		);
		
	} // END help_tabs()
	
	/**
	 * @todo
	 *
	 * @since 1.0
	 */		
	function defaults_desc() {
		echo '<p>' . __( 'Some Description', 'hallo-emoji' ) . '</p>';
	}
	
	/**
	 * @todo
	 *
	 * @since 1.0
	 */		
	function render_field() {
		echo 'some-output';
	}
		
} // END class WPCollab_HelloEmoji_Admin
