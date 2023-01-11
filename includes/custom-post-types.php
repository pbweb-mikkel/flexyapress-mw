<?php

if ( ! function_exists('flexyapress_cases') ) {

	// Register Cases
	function flexyapress_cases() {
	
		$labels = array(
			'name'                  => _x( 'Sager', 'Post Type General Name', 'flexyapress' ),
			'singular_name'         => _x( 'Sag', 'Post Type Singular Name', 'flexyapress' ),
			'menu_name'             => __( 'Sager', 'flexyapress' ),
			'name_admin_bar'        => __( 'Sag', 'flexyapress' ),
			'archives'              => __( 'Sag Archives', 'flexyapress' ),
			'attributes'            => __( 'Sag Attributes', 'flexyapress' ),
			'parent_item_colon'     => __( 'Parent Sag:', 'flexyapress' ),
			'all_items'             => __( 'Alle Sager', 'flexyapress' ),
			'add_new_item'          => __( 'Add New Sag', 'flexyapress' ),
			'add_new'               => __( 'Add New', 'flexyapress' ),
			'new_item'              => __( 'New Sag', 'flexyapress' ),
			'edit_item'             => __( 'Edit Sag', 'flexyapress' ),
			'update_item'           => __( 'Update Sag', 'flexyapress' ),
			'view_item'             => __( 'View Sag', 'flexyapress' ),
			'view_items'            => __( 'View Sager', 'flexyapress' ),
			'search_items'          => __( 'Search Sag', 'flexyapress' ),
			'not_found'             => __( 'Not found', 'flexyapress' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'flexyapress' ),
			'featured_image'        => __( 'Primary image', 'flexyapress' ),
			'set_featured_image'    => __( 'Set primary image', 'flexyapress' ),
			'remove_featured_image' => __( 'Remove primary image', 'flexyapress' ),
			'use_featured_image'    => __( 'Use as Primary image', 'flexyapress' ),
			'insert_into_item'      => __( 'Insert into Sag', 'flexyapress' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Sag', 'flexyapress' ),
			'items_list'            => __( 'Sagsliste', 'flexyapress' ),
			'items_list_navigation' => __( 'Sagsliste navigation', 'flexyapress' ),
			'filter_items_list'     => __( 'Filter items list', 'flexyapress' ),
		);
		$args = array(
			'label'                 => __( 'Sag', 'flexyapress' ),
			'description'           => __( 'A list of cases from Flexya', 'flexyapress' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', 'page-attributes', ),
			'taxonomies'            => array( 'post_tag' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-admin-multisite',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'rewrite'				=> array('slug' => get_option('flexyapress')['case-slug']),
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
		);
		register_post_type( 'sag', $args );

	}
	add_action( 'init', 'flexyapress_cases', 0 );
	
}

if ( ! function_exists('pb_flex_casetypes') ) {
	
	function pb_flex_casetypes() {
	     $labels = array(
	         'name'              => _x( 'Typer', 'flexyapress' ),
	         'singular_name'     => _x( 'Type', 'flexyapress' ),
	         'add_new_item'     => _x( 'Tilføj Type', 'flexyapress' ),
	     );

	     $args = array(
	         'labels' => $labels,
	         'hierarchical' => false,
	     );
	     register_taxonomy( 'type', 'sag', $args );
	 }

	 add_action( 'init', 'pb_flex_casetypes', 0 );

}

if ( ! function_exists('pb_flex_offices') ) {
	
	function pb_flex_offices() {
	     $labels = array(
	         'name'              => _x( 'Filialer', 'flexyapress' ),
	         'singular_name'     => _x( 'Filial', 'flexyapress' ),
	         'add_new_item'     => _x( 'Tilføj Filial', 'flexyapress' ),
	     );

	     $args = array(
	         'labels' => $labels,
	         'hierarchical' => false,
	     );
	     register_taxonomy( 'office', 'sag', $args );
	 }

	 add_action( 'init', 'pb_flex_offices', 0 );

}


if ( ! function_exists('pb_flex_saleTypes') ) {
	
	function pb_flex_saleTypes() {
	     $labels = array(
	         'name'              => _x( 'Salgstyper', 'flexyapress' ),
	         'singular_name'     => _x( 'Salgstype', 'flexyapress' ),
	         'add_new_item'     => _x( 'Tilføj Type', 'flexyapress' ),
	     );

	     $args = array(
	         'labels' => $labels,
	         'hierarchical' => true,
	     );
	     register_taxonomy( 'saletype', 'sag', $args );
	 }

	 add_action( 'init', 'pb_flex_saleTypes', 0 );

}

if ( ! function_exists('pb_flex_zipcodes') ) {
	
	function pb_flex_zipcodes() {
	     $labels = array(
	         'name'              => _x( 'Postnumre', 'flexyapress' ),
	         'singular_name'     => _x( 'Postnummer', 'flexyapress' ),
	         'add_new_item'     => _x( 'Tilføj postnummer', 'flexyapress' ),
	     );

	     $args = array(
	         'labels' => $labels,
	         'hierarchical' => false,
	     );
	     register_taxonomy( 'zipcode', 'sag', $args );
	 }

	 add_action( 'init', 'pb_flex_zipcodes', 0 );

}

if ( ! function_exists('pb_flex_realtors') ) {

	// Register Cases
	function pb_flex_realtors() {
	
		$labels = array(
			'name'                  => _x( 'Mæglere', 'Post Type General Name', 'flexyapress' ),
			'singular_name'         => _x( 'Mægler', 'Post Type Singular Name', 'flexyapress' ),
			'menu_name'             => __( 'Mæglere', 'flexyapress' ),
			'name_admin_bar'        => __( 'Mægler', 'flexyapress' ),
			'archives'              => __( 'Mægler Archives', 'flexyapress' ),
			'attributes'            => __( 'Mægler Attributes', 'flexyapress' ),
			'parent_item_colon'     => __( 'Parent Mægler:', 'flexyapress' ),
			'all_items'             => __( 'Alle Mæglere', 'flexyapress' ),
			'add_new_item'          => __( 'Add New Mægler', 'flexyapress' ),
			'add_new'               => __( 'Add New', 'flexyapress' ),
			'new_item'              => __( 'New Mægler', 'flexyapress' ),
			'edit_item'             => __( 'Edit Mægler', 'flexyapress' ),
			'update_item'           => __( 'Update Mægler', 'flexyapress' ),
			'view_item'             => __( 'View Mægler', 'flexyapress' ),
			'view_items'            => __( 'View Mæglere', 'flexyapress' ),
			'search_items'          => __( 'Search Mægler', 'flexyapress' ),
			'not_found'             => __( 'Not found', 'flexyapress' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'flexyapress' ),
			'featured_image'        => __( 'Primary image', 'flexyapress' ),
			'set_featured_image'    => __( 'Set primary image', 'flexyapress' ),
			'remove_featured_image' => __( 'Remove primary image', 'flexyapress' ),
			'use_featured_image'    => __( 'Use as Primary image', 'flexyapress' ),
			'insert_into_item'      => __( 'Insert into Mægler', 'flexyapress' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Mægler', 'flexyapress' ),
			'items_list'            => __( 'Mæglerliste', 'flexyapress' ),
			'items_list_navigation' => __( 'Mæglerliste navigation', 'flexyapress' ),
			'filter_items_list'     => __( 'Filter items list', 'flexyapress' ),
		);
		$args = array(
			'label'                 => __( 'Mægler', 'flexyapress' ),
			'description'           => __( 'A list of realtors from Flexya', 'flexyapress' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', 'page-attributes', ),
			'taxonomies'            => array( 'post_tag' ),
			'hierarchical'          => false,
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-admin-users',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => false,
			'rewrite'				=> array('slug' => 'realtor'),		
			'exclude_from_search'   => true,
			'publicly_queryable'    => false,
			'capability_type'       => 'post',
		);
		register_post_type( 'realtor', $args );

	}
	add_action( 'init', 'pb_flex_realtors', 0 );
	
}

if ( ! function_exists('pb_flex_offices_cpt') ) {
	// Register Offices
	function pb_flex_offices_cpt() {

		$labels = array(
			'name'                  => _x( 'Afdelinger', 'Post Type General Name', 'flexyapress' ),
			'singular_name'         => _x( 'Afdeling', 'Post Type Singular Name', 'flexyapress' ),
			'menu_name'             => __( 'Afdelinger', 'flexyapress' ),
			'name_admin_bar'        => __( 'Afdelinger', 'flexyapress' ),
			'archives'              => __( 'Afdeling Archives', 'flexyapress' ),
			'attributes'            => __( 'Afdeling Attributes', 'flexyapress' ),
			'parent_item_colon'     => __( 'Parent Afdeling:', 'flexyapress' ),
			'all_items'             => __( 'Alle Afdelinger', 'flexyapress' ),
			'add_new_item'          => __( 'Add New Afdeling', 'flexyapress' ),
			'add_new'               => __( 'Add New', 'flexyapress' ),
			'new_item'              => __( 'New Afdeling', 'flexyapress' ),
			'edit_item'             => __( 'Edit Afdeling', 'flexyapress' ),
			'update_item'           => __( 'Update Afdeling', 'flexyapress' ),
			'view_item'             => __( 'View Afdeling', 'flexyapress' ),
			'view_items'            => __( 'View Mæglere', 'flexyapress' ),
			'search_items'          => __( 'Search Afdeling', 'flexyapress' ),
			'not_found'             => __( 'Not found', 'flexyapress' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'flexyapress' ),
			'featured_image'        => __( 'Primary image', 'flexyapress' ),
			'set_featured_image'    => __( 'Set primary image', 'flexyapress' ),
			'remove_featured_image' => __( 'Remove primary image', 'flexyapress' ),
			'use_featured_image'    => __( 'Use as Primary image', 'flexyapress' ),
			'insert_into_item'      => __( 'Insert into Afdeling', 'flexyapress' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Afdeling', 'flexyapress' ),
			'items_list'            => __( 'Afdelinger', 'flexyapress' ),
			'items_list_navigation' => __( 'Afdelinger navigation', 'flexyapress' ),
			'filter_items_list'     => __( 'Filter items list', 'flexyapress' ),
		);
		$args = array(
			'label'                 => __( 'Afdelinger', 'flexyapress' ),
			'description'           => __( 'A list of offices from Flexya', 'flexyapress' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', 'page-attributes', ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
            'show_in_rest'          => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-store',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => false,
			'rewrite'				=> array('slug' => 'afdeling'),
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
		);
		register_post_type( 'office', $args );
	}
	add_action( 'init', 'pb_flex_offices_cpt');

}


