<?php

require_once WP_PLUGIN_DIR .'/flexyapress-mw/includes/class-flexyapress-api.php';

/**
 * Class Sendy_Action_After_Submit
 * @see https://developers.elementor.com/custom-form-action/
 * Custom elementor form action after submit to add a subsciber to
 * Sendy list via API
 */
class Flexya_Valuation_Action_After_Submit extends \ElementorPro\Modules\Forms\Classes\Action_Base {
    /**
     * Get Name
     *
     * Return the action name
     *
     * @access public
     * @return string
     */
    public function get_name() {
        return 'flexya-valuation';
    }

    /**
     * Get Label
     *
     * Returns the action label
     *
     * @access public
     * @return string
     */
    public function get_label() {
        return __( 'Flexya Salgsvurdering', 'text-domain' );
    }

    /**
     * Run
     *
     * Runs the action after submit
     *
     * @access public
     * @param \ElementorPro\Modules\Forms\Classes\Form_Record $record
     * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
     */
    public function run( $record, $ajax_handler ) {
        $settings = $record->get( 'form_settings' );


        // Get sumitetd Form data
        $raw_fields = $record->get( 'fields' );

        // Normalize the Form Data
        $fields = [];
        foreach ( $raw_fields as $id => $field ) {
            $fields[ $id ] = $field['value'];
        }

        $fields['buyerActionType'] = 'VALUATION_ORDER';

        $api = new Flexyapress_API();
        $api->order($fields);


    }

    /**
     * Register Settings Section
     *
     * Registers the Action controls
     *
     * @access public
     * @param \Elementor\Widget_Base $widget
     */
    public function register_settings_section( $widget ) {
        /*$widget->start_controls_section(
            'section_sendy',
            [
                'label' => __( 'Sendy', 'text-domain' ),
                'condition' => [
                    'submit_actions' => $this->get_name(),
                ],
            ]
        );

        $widget->add_control(
            'sendy_url',
            [
                'label' => __( 'Sendy URL', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'http://your_sendy_installation/',
                'label_block' => true,
                'separator' => 'before',
                'description' => __( 'Enter the URL where you have Sendy installed', 'text-domain' ),
            ]
        );

        $widget->add_control(
            'sendy_list',
            [
                'label' => __( 'Sendy List ID', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'separator' => 'before',
                'description' => __( 'the list id you want to subscribe a user to. This encrypted & hashed id can be found under View all lists section named ID.', 'text-domain' ),
            ]
        );

        $widget->add_control(
            'sendy_email_field',
            [
                'label' => __( 'Email Field ID', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );

        $widget->add_control(
            'sendy_name_field',
            [
                'label' => __( 'Name Field ID', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );

        $widget->end_controls_section();
        */
    }

    /**
     * On Export
     *
     * Clears form settings on export
     * @access Public
     * @param array $element
     */
    public function on_export( $element ) {
        /*unset(
            $element['sendy_url'],
            $element['sendy_list'],
            $element['sendy_name_field'],
            $element['sendy_email_field']
        );*/
    }
}