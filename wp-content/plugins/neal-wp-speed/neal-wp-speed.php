<?php
/*
Plugin Name: Neal WP Speed
Plugin URI: http://superzw.com
Description: Speed Up Backend Admin
Version: 1.0
Author: Neal
Author URI: http://superzw.com
License: GPL
*/

//Disable XML RPC
add_filter( 'xmlrpc_enabled', '__return_false' );

//Gravatar头像不显示完美解决方案
/*function get_ssl_avatar($avatar) {
	$avatar = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com"), "cn.gravatar.com", $avatar);
	return $avatar;
}

add_filter('get_avatar', 'get_ssl_avatar');
*/
function disable_plugin_request( $a, $b, $c ){
	if( isset( $b['body']['plugins']) || isset( $b['body']['themes'] ) )
	return array( 'response' => array( 'code' => 404 ) );
}

add_filter("pre_http_request", 'disable_plugin_request',10,3);


//replace googlapis to 360 usueso.com

function hc_cdn_callback( $buffer ) {
	
	$find_these = array( "s.wordpress.org", "googleapis.com", "s.w.org" , "twitter.com", "facebook.com", '0.gravatar.com' );
	$replace_with = array( "useso.com", "css.network", "useso.com", "useso.com", "useso.com",'secure.gravatar.com' );

	return str_replace( $find_these, $replace_with, $buffer );
}

function hc_buffer_start() {
	ob_start( "hc_cdn_callback" );
}

function izt_buffer_end() {
	ob_end_flush();
}

add_action('init','hc_buffer_start');
//add_action('shutdown','izt_buffer_end');


/**
 * 隐藏核心更新提示 WP 3.0+
 * 来自 http://wordpress.org/plugins/disable-wordpress-core-update/
 */
add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );


/**
 * 隐藏插件更新提示 WP 3.0+
 * 来自 http://wordpress.org/plugins/disable-wordpress-plugin-updates/
 */
remove_action( 'load-update-core.php', 'wp_update_plugins' );
add_filter( 'pre_site_transient_update_plugins', create_function( '$b', "return null;" ) );


/**
 * 隐藏主题更新提示 WP 3.0+
 * 来自 http://wordpress.org/plugins/disable-wordpress-theme-updates/
 */
remove_action( 'load-update-core.php', 'wp_update_themes' );
add_filter( 'pre_site_transient_update_themes', create_function( '$c', "return null;" ) );

// Disable All Automatic Updates
add_filter( 'automatic_updater_disabled', '__return_true' );
// Disable Automatic Update Result Emails
add_filter( 'auto_core_update_send_email', '__return_false' );


// 禁止 WordPress 检查更新
remove_action('admin_init', '_maybe_update_core');    

// 禁止 WordPress 更新插件
remove_action('admin_init', '_maybe_update_plugins'); 

// 禁止 WordPress 更新主题
remove_action('admin_init', '_maybe_update_themes');  

/*
add_action('init', create_function('$a', "remove_action( 'init', 'wp_version_check' );"), 2);
add_filter('pre_option_update_core', '__return_null');
add_filter( 'auto_update_core', '__return_false' );
add_filter('pre_site_transient_update_core', '__return_null');
add_filter( 'auto_update_translation', '__return_false' );
add_filter( 'allow_minor_auto_core_updates', '__return_false' );
add_filter( 'allow_major_auto_core_updates', '__return_false' );
add_filter( 'allow_dev_auto_core_updates', '__return_false' );
add_filter( 'wp_auto_update_core', '__return_false' );
add_filter( 'send_core_update_notification_email', '__return_false' );
add_filter( 'auto_update_plugin', '__return_false' );
add_filter( 'auto_update_theme', '__return_false' );
add_filter( 'automatic_updates_send_debug_email', '__return_false' );
add_filter( 'automatic_updates_is_vcs_checkout', '__return_true' );
add_filter('pre_site_transient_update_plugins','__return_null');
remove_action('load-update-core.php','wp_update_plugins');
*/


//移除wordpress自带的widget
function neal_remove_dashboard_widgets() {
    global $wp_meta_boxes;

    // unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    // unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    // unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    // unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    // unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
    // unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    // unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    // unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);

    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
}
add_action('wp_dashboard_setup', 'neal_remove_dashboard_widgets',11 );

//移除自带的小工具代码
// function Yusi_remove_meta_widget() {
//      unregister_widget('WP_Widget_Pages');
//      unregister_widget('WP_Widget_Calendar');
//      //unregister_widget('WP_Widget_Archives');
//      unregister_widget('WP_Widget_Links');
//      unregister_widget('WP_Widget_Meta');
//     // unregister_widget('WP_Widget_Search');
//     unregister_widget('WP_Widget_Text');
//     //  unregister_widget('WP_Widget_Categories');
//      unregister_widget('WP_Widget_Recent_Posts');
//      unregister_widget('WP_Widget_Recent_Comments');
//      unregister_widget('WP_Widget_RSS');
//      unregister_widget('WP_Widget_Tag_Cloud');
//      //unregister_widget('WP_Nav_Menu_Widget');
//     /*register my custom widget*/
//     register_widget('WP_Widget_Meta_Mod');
// }
// add_action( 'widgets_init', 'Yusi_remove_meta_widget',11 );