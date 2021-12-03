<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.kri8it.com
 * @since             1.0.0
 * @package           Nextlevel_Sage_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       NEXTLEVEL Sage for Woocommerce
 * Plugin URI:        https://www.kri8it.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Hilton Moore
 * Author URI:        https://www.kri8it.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nextlevel-sage-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'NEXTLEVEL_SAGE_WOOCOMMERCE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-nextlevel-sage-woocommerce-activator.php
 */
function activate_nextlevel_sage_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nextlevel-sage-woocommerce-activator.php';
	Nextlevel_Sage_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-nextlevel-sage-woocommerce-deactivator.php
 */
function deactivate_nextlevel_sage_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nextlevel-sage-woocommerce-deactivator.php';
	Nextlevel_Sage_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_nextlevel_sage_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_nextlevel_sage_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-nextlevel-sage-woocommerce.php';








add_action( 'plugins_loaded', 'check_for_update_nextlevel_sage_woocommerce' );
function check_for_update_nextlevel_sage_woocommerce(){

    require_once plugin_dir_path( __FILE__ ) . 'includes/class-nextlevel-sage-woocommerce-updater.php';


      $config = array(
            'slug'               => plugin_basename( __FILE__ ),
            'proper_folder_name' => 'nextlevel-sage-woocommerce',
            'api_url'            => 'https://api.github.com/repos/kri8itdigital/nextlevel-sage-woocommerce',
            'raw_url'            => 'https://raw.github.com/kri8itdigital/nextlevel-sage-woocommerce/master',
            'github_url'         => 'https://github.com/kri8itdigital/nextlevel-sage-woocommerce',
            'zip_url'            => 'https://github.com/kri8itdigital/nextlevel-sage-woocommerce/archive/master.zip',
            'homepage'           => 'https://github.com/kri8itdigital/nextlevel-sage-woocommerce',
            'sslverify'          => true,
            'requires'           => '5.0',
            'tested'             => '5.7',
            'readme'             => 'README.md',
            'version'            => '1.0.0'
        );

        new nextlevel_sage_woocommerce_updater( $config );

}






/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_nextlevel_sage_woocommerce() {

	$plugin = new Nextlevel_Sage_Woocommerce();
	$plugin->run();

}
run_nextlevel_sage_woocommerce();
