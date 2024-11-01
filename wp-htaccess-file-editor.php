<?php
/*
Plugin Name: WP Htaccess File Editor – Safely Edit Htaccess File
Plugin URI: https://bellathemes.com/plugin/wp-htaccess-file-editor/
Description: Safe & simple WordPress htaccess file editor with automatic backup.
Version: 1.0.1
Text Domain: wphfe
Domain Path: /lang/
Author: Bellathemes
Author URI: https://bellathemes.com/
License: GPLv2 or later
*/

/*
WP Htaccess File Editor is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

WP Htaccess File Editor is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Regenerate Thumbnails. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if (!defined('ABSPATH')) die('Silence is golden!');


	/* 
	 * Path and url configuration
	 */
if( is_admin() ){

	define( 'WPHFE_VERSION' , '1.0' ) ;

	if( ! defined( 'WP_SITEURL' ) ){
		
			define( 'WP_SITEURL', get_site_url().'/' );
		
	}

	if( ! defined( 'WP_CONTENT_URL' )){
		
		define( 'WP_CONTENT_URL', WP_SITEURL.'wp-content' );
	}

	if( ! defined( 'WP_PLUGIN_URL' ) ){

		define( 'WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins' );
	}

	$WPHFE_ROOT = trailingslashit( plugin_dir_path( __FILE__ ) );
	$WPHFE_INC = $WPHFE_ROOT.'inc/';
	$WPHFE_DIR = str_replace('\\', '/', dirname(plugin_basename(__FILE__)));
	$WPHFE_URI = WP_PLUGIN_URL.'/'.$WPHFE_DIR.'/';

	/* 
	 * Load translation files 
	*/
	$WPHFE_Locale = get_locale();

	if( ! empty( $WPHFE_Locale ) ){

		$WPHFE_moFile = dirname(__FILE__) . '/lang/'.$WPHFE_Locale.'.mo';

		if( @file_exists( $WPHFE_moFile ) && is_readable( $WPHFE_moFile ) ){

			load_textdomain( 'wphfe', $WPHFE_moFile );
		}

		unset( $WPHFE_moFile );
	}

	unset( $WPHFE_Locale );



	/* 
	*Load required plugin/wp files 
	*/
	
	if( ! function_exists( 'wp_get_current_user' ) ){

		if( file_exists( ABSPATH.'wp-includes/pluggable.php' )){

			require_once ABSPATH.'wp-includes/pluggable.php';

		}else{

			wp_die( wphfe_wp_error_message() );
		}
	}

	if( ! function_exists( 'current_user_can' ) ){

		if( file_exists( ABSPATH.'wp-includes/capabilities.php' ) ){

			require_once ABSPATH.'wp-includes/capabilities.php';

		}else{

			wp_die( wphfe_wp_error_message() );
		}
	}


	if( file_exists( $WPHFE_INC.'functions.php' ) ){

		require $WPHFE_INC.'functions.php';

	}else{ 

		wp_die( wphfe_error_message() ); 

	 }
 


	/*
	 * Add pages to the menu 
	 */
	function wphfe_admin_menu(){

	    global $WPHFE_DIR, $WPHFE_URI;

	    if( current_user_can( 'activate_plugins' ) ){

			add_menu_page( 'WP Htaccess File Editor', 'Htaccess', 'activate_plugins', $WPHFE_DIR, 'wphfe_view_page', '' );
			wphfe_add_page( 'Edit Htaccess','Edit Htaccess', 'activate_plugins', $WPHFE_DIR, 'wphfe_view_page' );
			wphfe_add_page( __('Backup', 'wphfe'),__('Backup', 'wphfe'), 'activate_plugins', $WPHFE_DIR.'_backup', 'wphfe_view_page' );

			wp_enqueue_style('wphfe-style', $WPHFE_URI.'style/wphfe-style.css' );
		}
		unset($WPHFE_DIR);
		unset($WPHFE_URI);
	}


	/*
	 * Output page
	 */

	function wphfe_view_page()
	{
		global $WPHFE_DIR, $WPHFE_ROOT, $WPHFE_URI, $WPHFE_VERSION;

	    switch ( strip_tags(addslashes( sanitize_text_field( $_GET['page'] ) ) ) ){

			case $WPHFE_DIR:

				require $WPHFE_ROOT.'pages/wphfe-dashboard.php';

			break;

			case $WPHFE_DIR.'_backup': 

				require $WPHFE_ROOT.'pages/wphfe-backup.php';

			break;

			default:

			    $WPHFE_ROOT.'pages/wphfe-dashboard.php';

			break;
		}

		unset( $WPHFE_DIR );
		unset( $WPHFE_ROOT );
		unset( $WPHFE_URI );
		unset( $WPHFE_version );
	}


	/*
	 *Create menus in admin panel 
	 */
	add_action( 'admin_menu', 'wphfe_admin_menu' );


	/*
	 * Help function to create menus
	 */
	function wphfe_add_page( $page_title, $menu_title, $access_level, $file, $function = '' ){

		global $WPHFE_DIR;

		add_submenu_page( $WPHFE_DIR, $page_title, $menu_title, $access_level, $file, $function );

		unset($WPHFE_DIR);
		unset($page_title);
		unset($menu_title);
		unset($access_level);
		unset($file);
		unset($function);
	}


	/*
	 * Returns wp file error
	 */
	function wphfe_wp_error_message(){

		return __( 'Fatal Error: One or more WordPress core files are not found', 'wphfe');
	}

	
	/*
	 * Returns plugin error
	 */
	function wphfe_error_message(){

		return __('Fatal error: Plugin <strong>WP Htaccess File Editor</strong> is corrupted', 'wphfe');
	}


}else{

	return;
}
