<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    1.0
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 *
 * Depending on your implementation, you may want to change the include call:
 *
 * Parent Theme:
 * require_once get_template_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Child Theme:
 * require_once get_stylesheet_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Plugin:
 * require_once dirname( __FILE__ ) . '/path/to/class-tgm-plugin-activation.php';
 */
require_once get_template_directory() . '/theme_plugin/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'restored_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variables passed to the `tgmpa()` function should be:
 * - an array of plugin arrays;
 * - optionally a configuration array.
 * If you are not changing anything in the configuration array, you can remove the array and remove the
 * variable from the function call: `tgmpa( $plugins );`.
 * In that case, the TGMPA default settings will be used.
 *
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */
function restored_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		array(
            'name' => esc_html__( 'WPBakery Visual Composer','knowing-god' ), // The plugin name.
            'slug' => 'js_composer', // The plugin slug (typically the folder name).
            'source' => get_template_directory() . '/theme_plugin/plugins/js_composer.zip', // The plugin source.
            'required' => false, // If false, the plugin is only 'recommended' instead of required.
            'version' => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
            'force_activation' => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url' => '', // If set, overrides default API URL and points to an external URL.
        ),
		array(
            'name' => esc_html__( 'Knowing God Core','knowing-god' ), // The plugin name.
            'slug' => 'knowing-god-core', // The plugin slug (typically the folder name).
            'source' => get_template_directory() . '/theme_plugin/plugins/knowing-god-core.zip', // The plugin source.
            'required' => false, // If false, the plugin is only 'recommended' instead of required.
            'version' => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
            'force_activation' => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url' => '', // If set, overrides default API URL and points to an external URL.
        ),
		array(
			'name' => esc_html__( 'Slider Revolution','knowing-god' ), // The plugin name.
			'slug' => 'revslider', // The plugin slug (typically the folder name).
			'source' => get_template_directory() . '/theme_plugin/plugins/revslider.zip', // The plugin source.
			'required' => false, // If false, the plugin is only 'recommended' instead of required.
			'version' => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
			'force_activation' => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url' => '', // If set, overrides default API URL and points to an external URL.
			),
		array(
			'name' => esc_html__( 'Contact Form 7','knowing-god' ), // The plugin name.
			'slug' => 'contact-form-7', // The plugin slug
			'required' => false, // If false, the plugin is only 'recommended' instead of required.
		),
		/*
		array(
			'name' => esc_html__( 'One Click Demo Import','knowing-god' ), // The plugin name.
			'slug' => 'one-click-demo-import', // The plugin slug
			'required' => false, // If false, the plugin is only 'recommended' instead of required.
		),
		*/
		array(
			'name' => esc_html__( 'WordPress Social Login','knowing-god' ), // The plugin name.
			'slug' => 'wordpress-social-login', // The plugin slug
			'required' => false, // If false, the plugin is only 'recommended' instead of required.
		),
		array(
			'name' => esc_html__( 'Securimage-WP','knowing-god' ), // The plugin name.
			'slug' => 'securimage-wp', // The plugin slug
			'required' => false, // If false, the plugin is only 'recommended' instead of required.
		),
	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id' => 'knowing-god',   // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',        // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,      // Show admin notices or not.
		'dismissable'  => true,      // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',        // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,     // Automatically activate plugins after installation or not.
		'message'      => '',        // Message to output right before the plugins table.

	);

	tgmpa( $plugins, $config );
}
