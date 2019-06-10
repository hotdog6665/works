<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wishdesk.com/
 * @since      1.0.0
 *
 * @package    Excedea_client_connection_plugin
 * @subpackage Excedea_client_connection_plugin/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Excedea_client_connection_plugin
 * @subpackage Excedea_client_connection_plugin/includes
 * @author     MrDrew <office@wishdesk.com>
 */
class Excedea_client_connection_plugin_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'excedea_client_connection_plugin',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}


}
