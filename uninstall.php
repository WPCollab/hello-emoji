<?php
/**
 * @author		WPCollab Team
 * @copyright	Copyright (c) 2014, WPCollab Team
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @package		WPCollab\HelloEmoji\Uninstaller
 * @version		0.1-alpha
 */

if ( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

if ( is_multisite() ) {
	
    global $wpdb;
    $blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
	
    if ( $blogs ) {
        foreach( $blogs as $blog ) {
            switch_to_blog( $blog['blog_id'] );
            delete_option( 'wpcollab_hello_emoji_settings' );
        }
        restore_current_blog();
    }
	
} else {
	
    delete_option( 'wpcollab_hello_emoji_settings' );
	
}
