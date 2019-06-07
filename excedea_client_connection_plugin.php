<?php

/**
 * @link              https://wishdesk.com/
 * @since             1.0.0
 * @package           Excedea_client_connection_plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Excedea client connection plugin
 * Plugin URI:        https://wishdesk.com/
 * Description:       This plugin was made to connect site to main system and push/pull info
 * Version:           1.0.0
 * Author:            MrDrew
 * Author URI:        https://wishdesk.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       excedea_client_connection_plugin
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
define( 'EXCEDEA_CLIENT_CONNECTION_PLUGIN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-excedea_client_connection_plugin-activator.php
 */
function activate_excedea_client_connection_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-excedea_client_connection_plugin-activator.php';
	Excedea_client_connection_plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-excedea_client_connection_plugin-deactivator.php
 */
function deactivate_excedea_client_connection_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-excedea_client_connection_plugin-deactivator.php';
	Excedea_client_connection_plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_excedea_client_connection_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_excedea_client_connection_plugin' );



/**
 * Add plugin settings link
 */
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'add_excedea_page_settings_link');
function add_excedea_page_settings_link( $links ) {
    array_unshift($links, '<a href="' .
        admin_url( 'options-general.php?page=excedea_settings' ) .
        '">' . __('Settings', 'excedea_client_connection_plugin') . '</a>');
    return $links;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-excedea_client_connection_plugin.php';

if (!Excedea_client_connection_plugin::check_connection()) {
    add_action('admin_notices', array('Excedea_client_connection_plugin', 'not_connected_notice'));
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
function run_excedea_client_connection_plugin() {

	$plugin = new Excedea_client_connection_plugin();
	$plugin->run();

}
run_excedea_client_connection_plugin();
