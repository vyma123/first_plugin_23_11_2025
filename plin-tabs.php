<?php
/*
 * Plugin Name:       Plin tab
 * Plugin URI:        https://villatheme.com
 * Description:       Hello tab
 * Version:           1.1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Vy Ma Van
 * Author URI:        https://villatheme.com
 * License:           GPL v2 or later
 * Domain Path:       /languages
 * Requires Plugins:  woocommerce
 */

/*
Plin tab is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Plin tab is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Plin tab. If not, see {URI to Plugin License}.
*/

defined( 'ABSPATH' ) || exit;


define( 'PLIN_TABS_VERSION', '1.0.0' );




/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_plin_tabs() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plin-tabs-activator.php';
	Plugin_Name_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_plin_tabs() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plin-tabs-deactivator.php';
	Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plin_tabs' );
register_deactivation_hook( __FILE__, 'deactivate_plin_tabs' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-plin-tabs.php';

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

	$plugin = new Plugin_Name();
	$plugin->run();

}
run_plugin_name();
?>