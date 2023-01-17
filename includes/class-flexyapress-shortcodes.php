<?php
/**
 * Shortcodes to display properties
 *
 * @package Flexya Pco
 * @author James Bonham & Rasmus Taarnby @Peytz & Co
 */

class Flexyapress_Shortcodes {

	function __construct() {
        add_shortcode( 'property_list', [ $this, 'property_list' ] );
        add_shortcode( 'property_slider', [ $this, 'property_slider' ] );
        add_shortcode( 'valuation_form', [ $this, 'valuation_form' ] );
        add_shortcode( 'valuation_form_compact', [ $this, 'valuation_form_compact' ] );
        add_shortcode( 'search_agent_form', [ $this, 'search_agent_form' ] );
	}

    /**
     * Shortcode for latest properties
     */
    public function property_list( $atts ) {
        $atts = shortcode_atts( [
            'show_search' => 'true',
            'sale_type' => 'all',
            'show_only' => 'all',
            'max' => 99999,
            'cta' => 'Se alle boliger'
        ], $atts );

        ob_start();
        if(file_exists(get_stylesheet_directory() .'/mw/property-list.php')){
            include get_stylesheet_directory() .'/mw/property-list.php';
        }else{
            include WP_PLUGIN_DIR .'/flexyapress-mw/templates/property/property-list.php';
        }
        return ob_get_clean();
    }

    /**
     * Shortcode for latest properties
     */
    public function property_slider( $atts ) {
        $atts = shortcode_atts( [], $atts );

        ob_start();
        if(file_exists(get_stylesheet_directory() .'/mw/property-slider.php')){
            include get_stylesheet_directory() .'/mw/property-slider.php';
        }else{
            include WP_PLUGIN_DIR .'/flexyapress-mw/templates/property/property-slider.php';
        }
        return ob_get_clean();
    }

    public function valuation_form( $atts ) {
        $atts = shortcode_atts( [
            'with_date' => false
        ], $atts );
        ob_start();
        if(file_exists(get_stylesheet_directory() .'/mw/form-valuation.php')){
            include get_stylesheet_directory() .'/mw/form-valuation.php';
        }else {
            include WP_PLUGIN_DIR . '/flexyapress-mw/templates/forms/form-valuation.php';
        }
        return ob_get_clean();
    }

    public function valuation_form_compact( $atts ) {
        $atts = shortcode_atts( [
        ], $atts );
        ob_start();
        if(file_exists(get_stylesheet_directory() .'/mw/form-valuation-compact.php')){
            include get_stylesheet_directory() .'/mw/form-valuation-compact.php';
        }else {
            include WP_PLUGIN_DIR . '/flexyapress-mw/templates/forms/form-valuation-compact.php';
        }
        return ob_get_clean();
    }

    public function search_agent_form( $atts ) {
        $atts = shortcode_atts( [
        ], $atts );
        ob_start();
            if(file_exists(get_stylesheet_directory() .'/mw/form-search-agent.php')){
                include get_stylesheet_directory() .'/mw/form-search-agent.php';
            }else {
                include WP_PLUGIN_DIR . '/flexyapress-mw/templates/forms/form-search-agent.php';
            }
        return ob_get_clean();
    }


}
