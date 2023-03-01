<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://pbweb.dk
 * @since      1.0.0
 *
 * @package    Pbweb_Flexya
 * @subpackage Pbweb_Flexya/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Pbweb_Flexya
 * @subpackage Pbweb_Flexya/public
 * @author     PB Web <mikkel@pbweb.dk>
 */
class Flexyapress_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

        add_action( 'init', [ $this, 'rewrite_properties' ] );
        add_filter( 'query_vars', [ $this, 'query_vars' ] );

        /*if ( get_query_var( 'caseNo' ) != false ) {
            die('test');
        }*/
/*
        add_action('wp_head', function() {
            global $wp_rewrite;
            echo '</pre>', print_r( $wp_rewrite, 1 ), '</pre>';
        });
*/
	}

	public function init(){

		if(isset($_GET['flexya_update']) && $_GET['key'] == 'sd5d2rf16'){
			$force = (isset($_GET['force'])) ?: false;
			$debug = (isset($_GET['debug'])) ?: false;

            echo '<pre>';
            /*
            $api = new Flexyapress_API();
            //Statuses: ForSale | Sold
            $cases = $api->get_cases(null,null,"ForSale");
            $case = array_shift($cases);
            $case = $api->get_case($case->id);
            */

			$import = new Flexyapress_Import();
			$import->import_cases($force, $debug);
            die();
		}

        if(isset($_GET['generate_facebook_catalog']) && $_GET['key'] == 'sd5d2rf16'){
            $api = new Flexyapress_API();
            $api->generate_facebook_ad_catalog();
            die();
        }

	}

    /**
     * Adds rewrite rules to pages assigned to the property page template
     *
     * @param object $wp_rewrite The preexisting rewrite object
     */
    public function rewrite_properties() {
    /*
        $wp_rewrite->rules =
            array_merge( [
                '^sag/([^/]*)/?' => 'index.php?caseNo=$matches[1]'
            ], $wp_rewrite->rules);
*/
        //die('test');
        add_rewrite_rule('sag/([^/]*)/?$', 'index.php?caseNo=$matches[1]', 'top');

    }

    /**
     * Adds query var for individual properties
     *
     * @param array $qvars The preexisting accepted query vars
     */
    public function query_vars( $qvars ) {
        $qvars[] = 'caseNo';
        return $qvars;
    }

	public function case_single($single) {
        global $post, $wp_query;

        /* Checks for single template by post type */
        if ( $post->post_type == 'sag' ) {

            if(get_post_meta($post->ID, 'status', true) == 'SOLD'){
                $wp_query->set_404();
                status_header( 404 );
                get_template_part( 404 );
                return;
            }

            if(file_exists(get_stylesheet_directory().'/mw/single-sag.php')){
                $single = get_stylesheet_directory().'/mw/single-sag.php';
            }else if(file_exists(get_stylesheet_directory().'/single-sag.php')){
                $single = get_stylesheet_directory().'/single-sag.php';
            }else{
                $single = WP_PLUGIN_DIR . '/flexyapress-mw/templates/single-sag.php';
            }

        }

        return $single;

    }

    public function template_include($single){
        global $post, $wp_query;

        if ( get_query_var( 'caseNo' ) != false ) {

            $caseNo = get_query_var( 'caseNo' );
            $match = get_posts([
                'post_type' => 'sag',
                'numberposts' => 1,
                'fields' => 'ids',
                'meta_key' => 'caseNumber',
                'meta_value' => $caseNo
            ]);

            if($match){
                $match = array_shift($match);
                wp_redirect(get_the_permalink($match));
                die();
            }else{
                $wp_query->set_404();
                status_header(404);
                nocache_headers();
                include( get_query_template( '404' ) );
                die();
            }

        }

        /* Checks for single template by post type */
        if (!empty($post->post_type) && $post->post_type == 'sag' ) {
            if(file_exists(get_stylesheet_directory().'/mw/single-sag.php')){
                $single = get_stylesheet_directory().'/mw/single-sag.php';
            }else if(file_exists(get_stylesheet_directory().'/single-sag.php')){
                $single = get_stylesheet_directory().'/single-sag.php';
            }else{
                $single = WP_PLUGIN_DIR . '/flexyapress-mw/templates/single-sag.php';
            }

        }
        return $single;
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


    public function set_default_featured_image($html, $post_id, $thumb_id, $size, $attr){
        if(is_object($post_id)){
            $post_id = $post_id->ID;
        }
        if((!$thumb_id || $thumb_id === 1) && get_post_type($post_id) == 'sag'){
            $html = '<img src="'.WP_PLUGIN_URL.'/flexyapress-mw/public/img/billede-mangler.png" alt="billede mangler">';
        }
        return $html;
    }

    public function add_case_og_image(){
        global $post;
        if(get_post_type() != 'sag'){
            return;
        }


        if($thumb = get_field('primaryPhoto1000')){
            echo '<meta property="og:image:secure_url" content="'. esc_attr( $thumb ) .'" /> ';
        }else if(has_post_thumbnail()) {
            $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' );
//          echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>';
            echo '<meta property="og:image:secure_url" content="'. esc_attr( $thumbnail_src[0] ) .'" /> ';
        }

        if($title = get_field('title')){
            echo '<meta property="og:description" content="'. $title .'" /> ';
        }else if($excerpt = get_the_excerpt()){
            echo '<meta property="og:description" content="'. $excerpt .'" /> ';
        }


    }

    public function set_default_featured_image_url($url, $post_id, $size){
        if(is_object($post_id)){
            $post_id = $post_id->ID;
        }
        if(!$url && get_post_type($post_id) == 'sag'){
            $url = WP_PLUGIN_URL.'/flexyapress-mw/public/img/billede-mangler.png';
        }
        return $url;
    }

    public function set_default_featured_image_id($id, $post_id){
        if(is_object($post_id)){
            $post_id = $post_id->ID;
        }
        if(!$id && get_post_type($post_id) == 'sag'){
            $id = 1;
        }
        return $id;
    }

    public function flexyapress_realtor_image($name){
        return '<img src="'.WP_PLUGIN_URL.'/flexyapress-mw/public/img/billede-mangler.png" alt="'.$name.'">';
    }

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pbweb_Flexya_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pbweb_Flexya_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        wp_enqueue_style( 'dawa-css', plugin_dir_url( __FILE__ ) . 'inc/dawa-autocomplete2/css/dawa-autocomplete2.css', array(), $this->version, 'all' );
        if(empty(get_option('flexyapress')['no-styling']) || get_option('flexyapress')['no-styling'] != true){
            wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/flexyapress-public.css', array(), $this->version, 'all' );
        }
		wp_enqueue_style( 'jquery-ui-css', plugin_dir_url( __FILE__ ) . 'inc/jquery-ui/jquery-ui.min.css', array(), $this->version, 'all' );
        wp_enqueue_style( 'jquery-ui-theme-css', plugin_dir_url( __FILE__ ) . 'inc/jquery-ui/jquery-ui.theme.min.css', array(), $this->version, 'all' );
        wp_enqueue_style( 'slick', plugin_dir_url( __FILE__ ) . 'inc/slick/slick.css', array(), $this->version, 'all' );
        wp_enqueue_style( 'slick-theme', plugin_dir_url( __FILE__ ) . 'inc/slick/slick-theme.css', array(), $this->version, 'all' );
        wp_enqueue_style( 'aos', plugin_dir_url( __FILE__ ) . 'inc/aos/aos.css', array(), $this->version, 'all' );
        wp_enqueue_style( 'lightbox', plugin_dir_url( __FILE__ ) . 'inc/lightbox/css/lightbox.min.css', array(), $this->version, 'all' );

    }

    public function mw_head(){
        if($color = get_option('flexyapress')['primary-color']){
            echo '<style>:root{--pb-primary: '.$color.'}</style>';
        }
    }

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pbweb_Flexya_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pbweb_Flexya_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        if(!wp_script_is('google-recaptcha') && !empty(get_option('flexyapress')['captcha-site-key'])){
            wp_enqueue_script( 'google-recaptcha', 'https://google.com/recaptcha/api.js?render='.get_option('flexyapress')['captcha-site-key']);
        }
        wp_enqueue_script( 'jquery-ui', plugin_dir_url( __FILE__ ) . 'inc/jquery-ui/jquery-ui.min.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( 'aos', plugin_dir_url( __FILE__ ) . 'inc/aos/aos.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( 'slick', plugin_dir_url( __FILE__ ) . 'inc/slick/slick.min.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( 'lightbox', plugin_dir_url( __FILE__ ) . 'inc/lightbox/js/lightbox.min.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/flexyapress.js', array( 'jquery', 'aos', 'jquery-ui', 'slick' ), $this->version, true );

        wp_localize_script( $this->plugin_name, 'ajax_object',
            array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'google_captcha_site_key' => get_option('flexyapress')['captcha-site-key']
            )
        );

	}

    public function pb_allow_oh_on_list($val){
        return $val;
    }

}
