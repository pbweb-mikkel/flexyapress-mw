<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://pbweb.dk
 * @since      1.0.0
 *
 * @package    Flexyapress
 * @subpackage Flexyapress/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Flexyapress
 * @subpackage Flexyapress/admin
 * @author     PB Web <kontakt@pbweb.dk>
 */
class Flexyapress_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Flexyapress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Flexyapress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/flexyapress-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Flexyapress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Flexyapress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/flexyapress-admin.js', array( 'jquery', 'wp-color-picker' ), $this->version, false );

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */

	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 */
		add_options_page( 'Mindworking', 'Mindworking', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page'));
        add_menu_page(__( 'Log', 'mw' ), __( 'Log', 'mw' ), 'manage_options', $this->plugin_name.'_log', array($this, 'flexyapress_log_page_contents'), 'dashicons-schedule', 3);

	}

    function save_office($post){

        if(!isset($_POST['acf']['field_630da39f61ff9']) && !$_POST['acf']['field_6311c5a90ea1a']){
            return;
        }


        $address = trim($_POST['acf']['field_630da39f61ff9'] .' '.$_POST['acf']['field_6311c5a90ea1a']);


        $codes = $this->get_geocodes_from_address($address);

        if(!empty($codes) && is_object($codes)){
            $_POST['acf']['field_630da3da2cf60'] = $codes->lat;
            $_POST['acf']['field_630da3e52cf61'] = $codes->lng;
            update_post_meta($post, 'latitude', $codes->lat);
            update_post_meta($post, 'longitude', $codes->lng);
        }

    }

    // add a link to the WP Toolbar
    function pb_toolbar_links($wp_admin_bar) {
        $args = array(
            'id' => 'flexyapress-mw',
            'title' => 'Opdatér sager',
            'href' => get_home_url().'?flexya_update=1&key=sd5d2rf16&force&debug',
            'meta' => array(
                'class' => 'flexyapress-mw',
                'title' => 'Opdatér sager'
            )
        );
        $wp_admin_bar->add_menu($args);
    }


    function pb_case_delete_images(){
        $id = $_POST['post_id'];

        if(!$id || !wp_verify_nonce($_POST['_wpnonce'], 'pb-delete-case-images')){
            die('not allowed');
        }

        $case = new Flexyapress_Case($id);
        $case->deleteImages();
        header('Location: '.$_POST['_wp_http_referer']);
        die();
    }

    function pb_case_delete_images_button(){
        if(get_post_type() !== 'sag'){
            return false;
        }
        $html  = '<form action="'.admin_url( 'admin-post.php' ).'" method="post">';
        $html  .= '<input type="hidden" value="'.get_the_ID().'" name="post_id" ">';
        $html  .= '<input type="hidden" value="pb_case_delete_images" name="action" ">';
        $html .= wp_nonce_field('pb-delete-case-images', '_wpnonce', true, false);
        $html .= '<input type="submit" accesskey="p" tabindex="5" value="Slet sagsbilleder" class="button-primary" id="custom" name="publish">';
        $html .= '</form>';
        echo $html;
    }

    function get_geocodes_from_address($address){
        $api_key =  'AIzaSyDSVppkvzeTPzZWGBrTZcHqBMWgBn9dzsg';
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?key='.$api_key.'&address='.urlencode($address);
        $response = wp_remote_get($url);

        if ( is_array( $response ) && ! is_wp_error( $response ) ) {
            $body = json_decode($response['body']); // use the content
            if(!empty($body->results[0]->geometry->location)){
                return $body->results[0]->geometry->location;
            }

        }
        return false;
    }

    function flexyapress_log_page_contents() {
        global $wpdb;
        $logs = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}flexyapress_log ORDER BY id DESC Limit 100");

        ?>
        <h1>
            <?php esc_html_e( 'Log', 'flexyapress' ); ?>

        </h1>
        <table id="log-table">
            <thead>
            <tr>
                <th align="left">ID</th>
                <th align="left">Type</th>
                <th align="left">Input</th>
                <th align="left">Output</th>
                <th align="rigth">Time</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($logs as $log){
                ?>
                <tr>
                    <td align="left"><?= $log->id ?></td>
                    <td align="left"><?= $log->type ?></td>
                    <td align="left"><?= $log->input ?></td>
                    <td align="left"><?= esc_html($log->response) ?></td>
                    <td align="right"><?= $log->time ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>

        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.js"></script>
        <script>
            jQuery(document).ready(function($) {
                $('#log-table').DataTable({
                    "order": [0, 'desc'],
                    "pageLength": 100,
                });
            } );
        </script>
        <style>
            tr.even{
                background:#f1f1f1 !important;
            }
        </style>
        <?php
    }

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */

	public function add_action_links( $links ) {
		/*
		*  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
		*/
		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
		);
		return array_merge(  $settings_link, $links );

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */

	public function display_plugin_setup_page() {
		include_once( 'partials/flexyapress-admin-display.php' );
	}

	public function options_update() {
		register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
        set_transient('flexyapress_auth_token', '', 1);
	}

	public function validate($input) {
		// All checkboxes inputs
		$valid = array();
		$valid['base-url'] = (isset($input['base-url']) && !empty($input['base-url'])) ? sanitize_text_field( $input['base-url']) : '';
		$valid['auth-url'] = (isset($input['auth-url']) && !empty($input['auth-url'])) ? sanitize_text_field( $input['auth-url']) : '';
		$valid['client-id'] = (isset($input['client-id']) && !empty($input['client-id'])) ? sanitize_text_field( $input['client-id']) : '';
		$valid['client-secret'] = (isset($input['client-secret']) && !empty($input['client-secret'])) ? sanitize_text_field( $input['client-secret']) : '';
		$valid['client-realm'] = (isset($input['client-realm']) && !empty($input['client-realm'])) ? sanitize_text_field( $input['client-realm']) : '';
        $valid['shop-no'] = (isset($input['shop-no']) && !empty($input['shop-no'])) ? sanitize_text_field( $input['shop-no']) : '';
		//$valid['token'] = (isset($input['token']) && !empty($input['token'])) ? sanitize_text_field( $input['token']) : '';
		//$valid['org-id'] = (isset($input['org-id']) && !empty($input['org-id'])) ? sanitize_text_field( $input['org-id']) : '';
		//$valid['office-id'] = (isset($input['office-id']) && !empty($input['office-id'])) ? sanitize_text_field( $input['office-id']) : '';
		$valid['case-slug'] = (isset($input['case-slug']) && !empty($input['case-slug'])) ? sanitize_text_field( $input['case-slug']) : '';
        $valid['save-images-locally'] = (isset($input['save-images-locally']) && !empty($input['save-images-locally'])) ? 1 : 0;
        $valid['no-styling'] = (isset($input['no-styling']) && !empty($input['no-styling'])) ? 1 : 0;
        $valid['business-enabled'] = (isset($input['business-enabled']) && !empty($input['business-enabled'])) ? 1 : 0;
		$valid['base-url-business'] = (isset($input['base-url-business']) && !empty($input['base-url-business'])) ? sanitize_text_field( $input['base-url-business']) : '';
        $valid['token-business'] = (isset($input['token-business']) && !empty($input['token-business'])) ? sanitize_text_field( $input['token-business']) : '';
        $valid['maps-api-key'] = (isset($input['maps-api-key']) && !empty($input['maps-api-key'])) ? sanitize_text_field( $input['maps-api-key']) : '';
        $valid['captcha-site-key'] = (isset($input['captcha-site-key']) && !empty($input['captcha-site-key'])) ? sanitize_text_field( $input['captcha-site-key']) : '';
        $valid['captcha-secret-key'] = (isset($input['captcha-secret-key']) && !empty($input['captcha-secret-key'])) ? sanitize_text_field( $input['captcha-secret-key']) : '';
        $valid['policy-url'] = (isset($input['policy-url']) && !empty($input['policy-url'])) ? sanitize_text_field( $input['policy-url']) : '';
        $valid['primary-color'] = (isset($input['primary-color']) && !empty($input['primary-color'])) ? sanitize_text_field( $input['primary-color']) : '';


        return $valid;
	}

	public function child_plugin_has_parent_plugin() {
		if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
			add_action( 'admin_notices', array($this, 'child_plugin_notice' ));
		}
	}

	public function child_plugin_notice(){
		?><div class="error"><p><?php _e('Sorry, but PB Web Flexya Integration requires the Advanced Custom Fields PRO to be installed and active.', 'pbweb-flexya');?></p></div><?php
	}

    public function pb_wpquery_where( $where ){
        global $current_user;

        if( is_user_logged_in() && empty($_GET['pb-show']) ){
            // logged in user, but are we viewing the library?
            if(basename($_SERVER['PHP_SELF']) == 'upload.php' || (isset( $_POST['action'] ) && ( $_POST['action'] == 'query-attachments' )) ){
                // here you can add some extra logic if you'd want to.
                $where .= ' AND post_author!=0';
            }
        }

        return $where;
    }

    public function elementor_valuation_form_action(){
        // Here its safe to include our action class file
        include_once( plugin_dir_path( __FILE__ ) . 'pb-elementor-valuation-form-action.php' );

        // Instantiate the action class
        $flexya_action = new Flexya_Valuation_Action_After_Submit();

        // Register the action with form widget
        \ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->add_form_action( $flexya_action->get_name(), $flexya_action );
    }

}
