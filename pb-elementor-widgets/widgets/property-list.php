<?php
/**
 * Elementor oEmbed Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Elementor_Property_List extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve oEmbed widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'property-list';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve oEmbed widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Boligoversigt', 'flexyapress-widgets' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-th';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'flexya' ];
	}

	/**
	 * Register oEmbed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'search_section',
			[
				'label' => __( 'Søgning', 'flexyapress-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_search',
			[
				'label' => __( 'Vis søgning', 'flexyapress-widgets' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Ja', 'flexyapress-widgets' ),
				'label_off' => __( 'Nej', 'flexyapress-widgets' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'search_text',
			[
				'label' => __( 'Tekst', 'flexyapress-widgets' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'placeholder' => __( 'Beskrivelse der vises over søgefeltet. Kan evt. bruges til overskrift', 'flexyapress-widgets' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Boligoversigt', 'flexyapress-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'dark_color',
            [
                'label' => __( 'Farvetema', 'flexyapress-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Mørk', 'flexyapress-widgets' ),
                'label_off' => __( 'Lys', 'flexyapress-widgets' ),
                'return_value' => 'yes',
            ]
        );

		$this->add_control(
			'max_show',
			[
				'label' => __( 'Antal pr. load', 'flexyapress-widgets' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 12,
			]
		);

		$this->add_control(
			'sale_type',
			[
				'label' => __( 'Salgstype', 'flexyapress-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'all',
				'options' => [
					'all'  => __( 'Alle', 'flexyapress-widgets' ),
					'ACTIVE' => __( 'Til salg', 'flexyapress-widgets' ),
					'SOLD' => __( 'Solgte', 'flexyapress-widgets' ),
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		include WP_PLUGIN_DIR .'/flexyapress-mw/includes/templates/property/property-list.php';


	}

}