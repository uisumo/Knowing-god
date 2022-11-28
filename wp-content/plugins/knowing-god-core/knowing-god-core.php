<?php
/**
 * Plugin Name: Knowing God Core
 * Plugin URI:https://digisamaritan.com/
 * Description: This plugin will provide short codes and widgets for the Knowing God theme.
 * Version: 1.0.0
 * Author: Digisamaritan
 * Author URI: https://digisamaritan.com/
 * Text Domain: knowing-god
*/

define( 'KNOWING_GOD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

// Plugin URL.
define( 'KNOWING_GOD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// LMS Folder Name
define( 'LMS_FOLDER', 'lmscontent' );

define( 'IMAGE_PATH_UPLOAD_LMS_SERIES', site_url() . '/' . LMS_FOLDER . '/public/uploads/lms/series/' );

// Plugin Root File.
if ( ! defined( 'KNOWING_GOD_PLUGIN_FILE' ) ) {
	define( 'KNOWING_GOD_PLUGIN_FILE', __FILE__ );
}

define( 'ROLE_OWNER', 1 );
define( 'ROLE_ADMIN', 2 );
define( 'ROLE_SUBSCRIBER', 5 );

// LMS Constants
define( 'ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
$folder = explode( '/', $_SERVER[ 'SCRIPT_NAME' ] );
$folder_name = '';
if ( ! empty( $folder ) ) {
	foreach ( $folder as $f ) {
		if ( ! empty( $f ) ) {
			$folder_name = $f . '/'; break;
		}
	}
}
$folder_name = LMS_FOLDER . '/';
define( 'UPLOADS_PATH', ROOT_PATH . $folder_name . 'public/uploads/' );
define( 'IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH', UPLOADS_PATH . 'lms/content/' );

$base1 = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
$base1 .= '://'.$_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']) . $folder_name;
define('PREFIX1', $base1.'public/');

$base = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
$base .= '://'.$_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']) . $folder_name;
define('PREFIX', $base);

$base3 = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
$base3 .= '://'.$_SERVER['HTTP_HOST'] . '/';
define('LMS_HOST', $base3);

define('LMS_UPLOADS_URL', PREFIX1.'uploads/');
define('IMAGE_PATH_UPLOAD_LMS_CONTENTS', LMS_UPLOADS_URL.'lms/content/');
define('URL_STUDENT_LMS_SERIES_VIEW', PREFIX.'learning-management/series/');
define('IMAGES', PREFIX1.'images/');
define('URL_FRONTEND_LMSSINGLELESSON', PREFIX . 'single-lesson/');

add_action( 'admin_notices', 'knowing_god_licence_notice' );
function knowing_god_licence_notice() {
	_e( '<div class="notice notice-success is-dismissible">
	<p><strong> <i> Knowing God : </i> </strong> Would you like to see the LMS site click <a href="' . knowing_god_urls( 'lms-login' ) . '" title="LMS" target="_blank">here</a></p>
	
	<p><strong> <i> Knowing God : </i> </strong> Would you like to see the WP Document? click <a href="' . knowing_god_urls( 'wp-document' ) . '" title="LMS" target="_blank">here</a></p>
	
	<p><strong> <i> Knowing God : </i> </strong> Would you like to see the LMS Document? click <a href="' . knowing_god_urls( 'lms-document' ) . '" title="LMS" target="_blank">here</a></p>
	
	</div>', 'knowing-god' );
}

/**
 * Enqueue scripts and styles.
 */
function knowing_god_lms_scripts() {
	wp_enqueue_script( 'jquery-popup', KNOWING_GOD_PLUGIN_URL . '/js/jquery-popup.js', array( 'jquery' ), '1.0.0' );
	
	// Add Profile Page CSS.
	wp_enqueue_style( 'knowing-god-profile', KNOWING_GOD_PLUGIN_URL . '/css/profile.css' );
	wp_enqueue_style( 'knowing-god-hunterPopup', KNOWING_GOD_PLUGIN_URL . '/css/hunterPopup.css' );
	
	wp_enqueue_style( 'knowing-god-fonts', KNOWING_GOD_PLUGIN_URL . '/css/fonts.css' );
}
add_action( 'wp_enqueue_scripts', 'knowing_god_lms_scripts' );

require KNOWING_GOD_PLUGIN_PATH . 'includes/class-knowing-god.php';
// Get Knowing God Running.
KG();

add_action( 'init', 'knowing_god_taxonomies', 0 );
function knowing_god_taxonomies() {
	knowing_god_faqcategory_taxonomy();
}
