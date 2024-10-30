<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * this starts the plugin.
 *
 * @link              http://edeneye.com
 * @since             1.0.0
 * @package           Breaking_Bar
 *
 * @wordpress-plugin
 * Plugin Name:       Breaking Bar
 * Plugin URI:        http://edeneye.com/
 * Description:       A simple breaking news bar that monitors a chosen category
 * Version:           1.0.0
 * Author:            Edeneye
 * Author URI:        http://edeneye.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       breaking-bar
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-breaking-bar.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_name() {

	$plugin = new Breaking_Bar();
	$plugin->run();

}
run_plugin_name();
