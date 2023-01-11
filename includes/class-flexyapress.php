<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://pbweb.dk
 * @since      1.0.0
 *
 * @package    Flexyapress
 * @subpackage Flexyapress/includes
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
 * @package    Flexyapress
 * @subpackage Flexyapress/includes
 * @author     PB Web <kontakt@pbweb.dk>
 */
class Flexyapress {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Flexyapress_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
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
	public function __construct() {
		if ( defined( 'FLEXYAPRESS_VERSION' ) ) {
			$this->version = FLEXYAPRESS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'flexyapress';

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
	 * - Flexyapress_Loader. Orchestrates the hooks of the plugin.
	 * - Flexyapress_i18n. Defines internationalization functionality.
	 * - Flexyapress_Admin. Defines all hooks for the admin area.
	 * - Flexyapress_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-flexyapress-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-flexyapress-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-flexyapress-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-flexyapress-public.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/custom-post-types.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/acf/acf-realtor.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/acf/acf-sag.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-flexyapress-helpers.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-flexyapress-case.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-flexyapress-realtor.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-flexyapress-office.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-flexyapress-media-queue.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-flexyapress-api.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-flexyapress-log.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-flexyapress-import.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-flexyapress-shortcodes.php';
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'pb-elementor-widgets/pb-elementor-widgets.php';


		$this->loader = new Flexyapress_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Flexyapress_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Flexyapress_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Flexyapress_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'child_plugin_has_parent_plugin' );

		// Add menu item
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

		// Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

		// Save/Update our plugin options
		$this->loader->add_action('admin_init', $plugin_admin, 'options_update');
		$this->loader->add_action('save_post_office', $plugin_admin, 'save_office');
        $this->loader->add_filter('posts_where', $plugin_admin, 'pb_wpquery_where');
        $this->loader->add_action( 'in_admin_footer', $plugin_admin, 'pb_case_delete_images_button' );
        $this->loader->add_action( 'admin_post_pb_case_delete_images', $plugin_admin, 'pb_case_delete_images' );
        $this->loader->add_action('elementor_pro/init', $plugin_admin, 'elementor_valuation_form_action');


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Flexyapress_Public( $this->get_plugin_name(), $this->get_version() );
		new Flexyapress_Shortcodes();
		$api = new Flexyapress_API();

		$this->loader->add_action( 'wp', $plugin_public, 'init' );
		$this->loader->add_action('wp_ajax_nopriv_submit_flexya_form', $api, 'submit_flexya_form');
		$this->loader->add_action('wp_ajax_submit_flexya_form', $api, 'submit_flexya_form');
		$this->loader->add_action('wp_ajax_nopriv_submit_search_agent_form', $api, 'submit_search_agent_form');
		$this->loader->add_action('wp_ajax_submit_search_agent_form', $api, 'submit_search_agent_form');
        $this->loader->add_action('wp_ajax_nopriv_search_properties', $api, 'search_properties');
        $this->loader->add_action('wp_ajax_search_properties', $api, 'search_properties');
        $this->loader->add_action('flexyapress_after_case', $api, 'enqueue_case_scripts');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
		$this->loader->add_action('wp_head', $plugin_public, 'mw_head');
        //$this->loader->add_action('wpcf7_before_send_mail', $api, 'pb_wpcf7_before_send_mail');

		$this->loader->add_filter('single_template', $plugin_public, 'case_single');
		$this->loader->add_filter('template_include', $plugin_public, 'template_include');
		$this->loader->add_filter('post_thumbnail_html', $plugin_public, 'set_default_featured_image', 20, 5);
		$this->loader->add_filter('post_thumbnail_url', $plugin_public, 'set_default_featured_image_url', 20, 3);
		$this->loader->add_filter('post_thumbnail_id', $plugin_public, 'set_default_featured_image_id', 20, 2);
		$this->loader->add_filter('flexyapress_realtor_image', $plugin_public, 'flexyapress_realtor_image', 10, 1);
        //$this->loader->add_filter( 'wpcf7_form_tag', $api, 'load_wpc7_fields' );

	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Flexyapress_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
