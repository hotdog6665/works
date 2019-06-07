<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wishdesk.com/
 * @since      1.0.0
 *
 * @package    Excedea_client_connection_plugin
 * @subpackage Excedea_client_connection_plugin/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Excedea_client_connection_plugin
 * @subpackage Excedea_client_connection_plugin/includes
 * @author     MrDrew <office@wishdesk.com>
 */
class Excedea_client_connection_plugin
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Excedea_client_connection_plugin_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('EXCEDEA_CLIENT_CONNECTION_PLUGIN_VERSION')) {
            $this->version = EXCEDEA_CLIENT_CONNECTION_PLUGIN_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'excedea_client_connection_plugin';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Excedea_client_connection_plugin_Loader. Orchestrates the hooks of the plugin.
     * - Excedea_client_connection_plugin_i18n. Defines internationalization functionality.
     * - Excedea_client_connection_plugin_Admin. Defines all hooks for the admin area.
     * - Excedea_client_connection_plugin_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-excedea_client_connection_plugin-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-excedea_client_connection_plugin-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-excedea_client_connection_plugin-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-excedea_client_connection_plugin-public.php';

        $this->loader = new Excedea_client_connection_plugin_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Excedea_client_connection_plugin_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Excedea_client_connection_plugin_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Excedea_client_connection_plugin_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new Excedea_client_connection_plugin_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Excedea_client_connection_plugin_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     * Check connection to the main site
     *
     * @return bool
     */
    public static function check_connection()
    {
        $val = get_option('excedea_options');
        $main_site_url = isset($val['main_site_url']) ? $val['main_site_url'] : null;
        $site_url = get_site_url();
        $token = isset($val['token']) ? $val['token'] : null;
        $rest_url = $main_site_url . '/wp-json/excedea/domains/?token=' . $token;
        $result = wp_remote_get( $rest_url );
        $body = wp_remote_retrieve_body( $result );
        $responce_url = str_replace('"', '', nl2br(stripslashes($body)));
        if ($responce_url == $site_url){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Show notice about connection to the main site if not connected
     */
    public static function not_connected_notice()
    {
        ?>
        <div id="message" class="error not_connected_notice">
            <p><?php printf(__('Please make a connection to the main site %shere%s', 'excedea_client_connection_plugin'), '<a href="' . admin_url('options-general.php?page=excedea_settings') . '">', '</a>'); ?></p>
        </div>
        <?php
    }

}
