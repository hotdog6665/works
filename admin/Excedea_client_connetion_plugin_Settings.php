<?php

/**
 * Class Excedea_client_connetion_plugin_Settings
 */

class Excedea_client_connetion_plugin_Settings
{
    /**
     * Excedea_client_connetion_plugin_Settings constructor.
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_plugin_settings_page'));
        add_action('admin_init', array($this, 'excedea_settings'));
    }

    /**
     * Create settings plugin page
     */
    function add_plugin_settings_page()
    {
        add_options_page(__('Excedea settings', 'excedea_client_connection_plugin'), 'Excedea', 'manage_options', 'excedea_settings', array($this, 'excedea_options_page_output'));
    }

    /**
     * Output of setting page
     */
    function excedea_options_page_output(){
        ?>
        <div class="wrap">
            <h2><?php echo get_admin_page_title() ?></h2>

            <form action="options.php" method="POST">
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
    function excedea_settings(){
        // параметры: $option_group, $excedea_options, $sanitize_callback
        register_setting( 'option_group', 'excedea_options', 'sanitize_callback' );

        // параметры: $id, $title, $callback, $page
        add_settings_section( 'excedea_settings_page', __('Main plugin setting', 'excedea_client_connection_plugin'), '', 'excedea_page' );

        // параметры: $id, $title, $callback, $page, $section, $args
        add_settings_field('primer_field1', 'Main site URL', array($this, 'excedea_field1'), 'excedea_page', 'excedea_settings_page' );
        add_settings_field('primer_field2', 'Token', array($this, 'excedea_field2'), 'excedea_page', 'excedea_settings_page' );
    }

    /**
     * Fill first option
     */
    function excedea_field1(){
        $val = get_option('excedea_options');
        $val = isset($val['main_site_url']) ? $val['main_site_url'] : null;
        ?>
        <input type="text" name="excedea_options[main_site_url]" value="<?php echo esc_attr( $val ) ?>" />
        <?php
    }

    /**
     * Fill second option
     */
    function excedea_field2(){
        $val = get_option('excedea_options');
        $val = isset($val['token']) ? $val['token'] : null;
        ?>
        <input type="text" name="excedea_options[token]" value="<?php echo esc_attr( $val ) ?>" />
        <?php
    }

    /**
     * Clear data
     *
     * @param $options
     * @return mixed
     */
    function sanitize_callback( $options ){
        // очищаем
        foreach( $options as $name => & $val ){
            if( $name == 'input' )
                $val = strip_tags( $val );

            if( $name == 'checkbox' )
                $val = intval( $val );
        }

        //die(print_r( $options )); // Array ( [input] => aaaa [checkbox] => 1 )

        return $options;
    }

}