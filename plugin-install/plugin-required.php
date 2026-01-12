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
 * @version    2.6.1
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
 */

require_once get_template_directory() . '/plugin-install/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'woozio_register_required_plugins' );

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
function woozio_register_required_plugins() {
	if ( isset( $_GET['page'] ) && $_GET['page'] === 'verifytheme_settings' ) {
		return;
	} 

	/*
		* Array of plugin arrays. Required keys are name and slug.
		* If the source is NOT from the .org repo, then source is also required.
		*/
	$pathfile = 'https://download.beplusthemes.com/';

  $plugin_includes = array(
    array(
		'name'     		=> __( 'Elementor Website Builder', 'woozio' ),
		'slug'     		=> 'elementor',
		'required'     	=> true,
    ),
    array(
		'name'          => __( 'Elementor Pro', 'woozio' ),
		'slug'          => 'elementor-pro',
		'source'        => $pathfile . 'elementor-pro.zip',
		'required'      => true,
	),
	array(
		'name'          => __( 'Smart Slider 3 Pro', 'woozio' ),
		'slug'          => 'nextend-smart-slider3-pro',
		'source'        => $pathfile . 'nextend-smart-slider3-pro.zip',
		'required'      => true,
	),
	array(
		'name'          => __( 'Advanced Custom Fields PRO', 'woozio' ),
		'slug'          => 'advanced-custom-fields-pro',
		'source'        => $pathfile . 'advanced-custom-fields-pro.zip',
		'required'      => true,
    ),
	array(
		'name'          => __( 'Gravity Forms', 'woozio' ),
		'slug'          => 'gravityforms',
		'source'        => $pathfile . 'gravityforms.zip',
		'required'      => true,
	),
    array(
		'name'          => __( 'WooCommerce', 'woozio' ),
		'slug'          => 'woocommerce',
		'required'      => false,
    ),
	array(
		'name'          => __( 'Worry Proof Backup', 'woozio' ),
		'slug'          => 'worry-proof-backup',
		'required'      => false,
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
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => true,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.

	);

	tgmpa( $plugin_includes, $config );
}

/**
 * Dummy Demo Data
 */

define('WORRPRBA_DUMMY_PACK_CENTER_SUPPORTED', true);
define('WORRPRBA_DUMMY_PACK_CENTER_ENDPOINT', 'https://wpb-dummy-pack-center-neon.vercel.app/api/');
define('WORRPRBA_DUMMY_PACK_CENTER_THEME_SLUG', 'woozio');

add_filter( 'worrprba_dummy_pack_center_license_key', function( $purchase_code ) {
	$verifytheme = get_option( '_verifytheme_settings' );
	if($verifytheme) {
		$verifytheme_ob = json_decode($verifytheme);
		$purchase_code = $verifytheme_ob->purchase_code;

		return $purchase_code;
	}
	return '';
} );


/**
 * Verify purchase code
 */
require get_template_directory() . '/license-manager/VerifyTheme.php';

// Initialize the admin UI and AJAX handlers
add_action( 'after_setup_theme', function() {
    if ( class_exists( 'VerifyTheme_Admin' ) ) {
        VerifyTheme_Admin::init();
    }
} );

add_filter( 'worrprba_dummy_pack_center_submenu_args', function ($submenu_args) {
    return $submenu_args = array(
		'parent_slug' => 'themes.php',
		'page_title'  => __('Demo Import', 'woozio'),
		'menu_title'  => __('Demo Import', 'woozio'),
		'capability'  => 'manage_options',
		'menu_slug'   => 'dummy-pack-center',
		'callback'    => 'worrprba_dummy_pack_center_page'
	);
} );

add_action( 'admin_notices', function() {
	$license_data = get_option( '_verifytheme_settings' );
	

	if ( ! current_user_can( 'manage_options' ) || $license_data ) {
		return;
	}
	?>
	<div class="notice notice-warning settings-error is-dismissible">
        <p>
            <strong><?php esc_html_e('Welcome to Woozio 🎉', 'woozio'); ?></strong><br>
            <?php esc_html_e('Activate your license to unlock updates and demo imports.', 'woozio'); ?> 
            <a href="<?php echo esc_url( admin_url( 'themes.php?page=verifytheme_settings' ) ); ?>">
                <?php esc_html_e('Activate License →', 'woozio'); ?>
            </a>
        </p>
    </div>
	<?php
} );

add_action( 'admin_init', function () {
	$license_data = get_option( '_verifytheme_settings' );

    if ( $license_data ) {
        return;
    } else {
		// Prefer WP uploads dir when available
        if ( function_exists( 'wp_upload_dir' ) ) {
            $up = wp_upload_dir();
            $base = rtrim( $up['basedir'], DIRECTORY_SEPARATOR );
        } else {
            $base = dirname( __FILE__ );
        }

        $dir = $base . DIRECTORY_SEPARATOR . 'verifytheme';
		$upload_dir   = wp_get_upload_dir();
		$license_file = $dir . DIRECTORY_SEPARATOR . 'license_state.txt';

		if ( ! file_exists( $license_file ) ) {
			return;
		}

		$raw = trim( file_get_contents( $license_file ) );

		if ( $raw === '' ) {
			return;
		}

		// If the file contains JSON, keep it as decoded array/object; otherwise store as purchase_code.
		$decoded = json_decode( $raw, true );
		if ( json_last_error() === JSON_ERROR_NONE && is_array( $decoded ) ) {
			$store = $decoded;
		} else {
			$store = array( 'purchase_code' => sanitize_text_field( $raw ) );
		}
		
		// The theme expects _verifytheme_settings to be a JSON string (see existing json_decode usage).
		update_option( '_verifytheme_settings', wp_json_encode( $store ) );

		// Optionally remove the file so it is not processed again
		// @unlink( $license_file );

		return;
	}
});
