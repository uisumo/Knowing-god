<?php
/**
 * Knowing God
 *
 * This is a wrapper class for WP_Session / PHP $_SESSION and handles the storage of session data between pages, errors, etc
 *
 * @author   Digisamaritan
 * @package  Knowing God
 * @since    1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Knowing_God' ) ) :

/**
 * Main Knowing_God Class.
 *
 * @since 1.0.0
 */
final class Knowing_God {
	/** Singleton *************************************************************/

	/**
	 * @var Knowing_God The one true Knowing_God
	 * @since 1.0.0
	 */
	private static $instance;

	/**
	 * Main Knowing_God Instance.
	 *
	 * Insures that only one instance of Knowing_God exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 2.0.0
	 * @static
	 * @staticvar array $instance
	 * @uses Knowing_God::setup_constants() Setup the constants needed.
	 * @uses Knowing_God::includes() Include the required files.
	 * @uses Knowing_God::load_textdomain() load the language files.
	 * @see EDD()
	 * @return object|Knowing_God The one true Knowing_God
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Knowing_God ) ) {
			self::$instance = new Knowing_God;

			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

			self::$instance->includes();
			// self::$instance->session = new Simontaxi_Session();
		}

		return self::$instance;
	}

	/**
	 * File inclues.
	 *
	 * @access private
	 * @since 2.0.0
	 * @return void
	 */
	private function includes() {
		include_once( KNOWING_GOD_PLUGIN_PATH . 'includes/functions.php' );
		require KNOWING_GOD_PLUGIN_PATH . 'includes/metabox.php';
		require KNOWING_GOD_PLUGIN_PATH . 'includes/aq-resizer.php';
		require KNOWING_GOD_PLUGIN_PATH . 'includes/aq-lms-resizer.php';
		require KNOWING_GOD_PLUGIN_PATH . 'includes/shortcodes.php';
		include_once( KNOWING_GOD_PLUGIN_PATH . '/includes/activate-user-by-email.php' );		
		require KNOWING_GOD_PLUGIN_PATH . 'includes/install.php';
		require KNOWING_GOD_PLUGIN_PATH . 'includes/taxonomy-faqcategory.php';
		// require KNOWING_GOD_PLUGIN_PATH . 'includes/taxonomies/taxonomy-faq_category.php';
	}

	/**
	 * Loads the plugin language files.
	 *
	 * @access public
	 * @since 2.0.0
	 * @return void
	 */
	public function load_textdomain() {

		// Set filter for plugin's languages directory.
		$knowing_god_lang_dir  = dirname( plugin_basename( KNOWING_GOD_PLUGIN_FILE ) ) . '/languages/';
		$knowing_god_lang_dir  = apply_filters( 'knowing_god_languages_directory', $knowing_god_lang_dir );

		// Load the default language files.
		load_plugin_textdomain( 'knowing-god', false, $knowing_god_lang_dir );
	}
}

endif;

/**
 * The main function for that returns Vehicle Booking
 *
 * The main function responsible for returning the one true Vehicle Booking
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $knowing_god = KG(); ?>
 *
 * @since 1.0.0
* @return object|Easy_Digital_Downloads The one true Easy_Digital_Downloads Instance.
 */
function KG() {
	return Knowing_God::instance();
}
