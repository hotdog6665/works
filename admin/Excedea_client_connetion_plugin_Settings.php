<?php

/**
 * Class Excedea_client_connetion_plugin_Settings
 */

class Excedea_client_connetion_plugin_Settings {
	/**
	 * Excedea_client_connetion_plugin_Settings constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_settings_page' ) );
		add_action( 'admin_init', array( $this, 'excedea_settings' ) );
		add_action( 'wp_ajax_connection_checker', array( $this, 'connection_checker' ) );
		add_action( 'wp_ajax_connection_checker', array( $this, 'connection_checker' ) );
	}

	/**
	 * Create settings plugin page
	 */
	function add_plugin_settings_page() {
		add_options_page( __( 'Excedea settings', 'excedea_client_connection_plugin' ), 'Excedea', 'manage_options', 'excedea_settings', array(
			$this,
			'excedea_options_page_output'
		) );
	}

	/**
	 * Output of setting page
	 */
	function excedea_options_page_output() {
		?>
        <div class="wrap">
            <h2><?php echo get_admin_page_title() ?></h2>

            <form action="options.php" method="POST" id="connection_checker">
				<?php
				settings_fields( 'option_group' );     // hidden protected fields
				do_settings_sections( 'excedea_page' );
				submit_button();
				?>
            </form>
        </div>
		<?php
	}

	/**
	 * Register settings. Setting will be saved as array, not like one setting - one option
	 */
	function excedea_settings() {
		register_setting( 'option_group', 'excedea_options', array( $this, 'sanitize_callback' ) );

		add_settings_section( 'excedea_settings_page', __( 'Main plugin setting', 'excedea_client_connection_plugin' ), '', 'excedea_page' );

		add_settings_field( 'primer_field1', 'Main site URL', array(
			$this,
			'excedea_field1'
		), 'excedea_page', 'excedea_settings_page' );
		add_settings_field( 'primer_field2', 'Token', array(
			$this,
			'excedea_field2'
		), 'excedea_page', 'excedea_settings_page' );
	}

	/**
	 * Fill first option
	 */
	function excedea_field1() {
		$val           = get_option( 'excedea_options' );
		$main_site_url = isset( $val['main_site_url'] ) ? $val['main_site_url'] : null;
		$site_url      = get_site_url();
		$token         = isset( $val['token'] ) ? $val['token'] : null;
		$rest_url      = $main_site_url . '/wp-json/excedea/domains/?token=' . $token;
		$result        = wp_remote_get( $rest_url );
		$body          = wp_remote_retrieve_body( $result );
		$responce_url  = str_replace( '"', '', nl2br( stripslashes( $body ) ) );
		if ( $responce_url == $site_url ) {
			$connection = 1;
		} else {
			$connection = 0;
		}

		?>
        <input type="text" class="main_site_url" name="excedea_options[main_site_url]"
               value="<?php echo esc_attr( $main_site_url ) ?>"/>
		<?php
		if ( $connection ) {
			echo '<span class="connected">Connected</span>';
		} else {
			echo '<span class="not_connected">Not connected</span>';
		}
	}

	/**
	 * Fill second option
	 */
	function excedea_field2() {
		$val = get_option( 'excedea_options' );
		$val = isset( $val['token'] ) ? $val['token'] : null;
		?>
        <input type="text" class="token" name="excedea_options[token]" value="<?php echo esc_attr( $val ) ?>"/>
		<?php
	}

	/**
	 * Clear data
	 *
	 * @param $options
	 *
	 * @return mixed
	 */
	function sanitize_callback( $options ) {
		foreach ( $options as $name => & $val ) {
			if ( $name == 'input' ) {
				$val = strip_tags( $val );
			}

			if ( $name == 'checkbox' ) {
				$val = intval( $val );
			}
		}

		return $options;
	}

	/**
	 * Check domain via Rest Api and Ajax
	 */
	function connection_checker() {
		$main_site_url = $_POST['main_site_url'];
		$token         = $_POST['token'];


		$rest_url     = $main_site_url . '/wp-json/excedea/domains/?token=' . $token;
		$result       = wp_remote_get( $rest_url );
		$body         = wp_remote_retrieve_body( $result );
		$responce_url = str_replace( '"', '', nl2br( stripslashes( $body ) ) );
		$site_url     = get_site_url();
		if ( $responce_url == $site_url ) {
			$connection = 1;
			echo 'Success';
		} else {
			$connection = 0;
			echo 'Fail';
		}

		$excedea_options = [
			'main_site_url'        => $main_site_url,
			'token'                => $token,
			'main_site_connection' => $connection
		];

		update_option( 'excedea_options', $excedea_options );

		wp_die();
	}

}