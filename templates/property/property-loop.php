<?php
$business = false;
$qargs = array(
	'post_type' => 'sag',
	'posts_per_page' => -1,
	'meta_key' => 'status',
	'meta_query' => array(
		'relation' => 'AND'
	),
	'orderby' => array('meta_value' => 'ASC', 'date' => 'DESC'),
);

if(isset($atts) && $atts['sale_type'] != 'all'){
	$qargs['meta_query'][] = array(
		'key' => 'status',
		'value' => $atts['sale_type']
	);
}else if($_GET){
	$qargs['meta_query'][] = array(
		'key' => 'status',
		'value' => 'ACTIVE'
	);
}

if((!empty($atts['show_only']) && $atts['show_only'] == 'business') || (isset($_GET['show_only']) && $_GET['show_only'] == 'business')){
    $business = true;
    $qargs['meta_query'][] = array(
        'key' => 'caseType',
        'value' => ['BusinessSalesCase', 'BusinessRentalCase'],
        'compare' => 'IN'
    );
}elseif ((!empty($atts['show_only']) && $atts['show_only'] == 'private') || (isset($_GET['show_only']) && $_GET['show_only'] == 'private')){
    $qargs['meta_query'][] = array(
        'key' => 'caseType',
        'value' => ['SalesCase', 'RentalCase', 'LoanCase'],
        'compare' => 'IN'
    );
}

if(!empty($_GET['minPrice'])){
	$qargs['meta_query'][] = [
		'key' => 'price',
		'value' => $_GET['minPrice'],
		'compare' => '>=',
		'type' => 'NUMERIC',
	];
}


if(!empty($_GET['maxPrice'])){
	$qargs['meta_query'][] = [
		'key' => 'price',
		'value' => $_GET['maxPrice'],
		'compare' => '<=',
		'type' => 'NUMERIC',
	];
}

if(!empty($_GET['q'])){

	$qargs['meta_query'][] = [
		'relation' => 'OR',
		[
			'key' => 'propertyType',
			'value' => $_GET['q'],
			'compare' => 'LIKE'
		],
		[
			'key' => 'placename',
			'value' => $_GET['q'],
			'compare' => 'LIKE'
		],
		[
			'key' => 'city',
			'value' => $_GET['q'],
			'compare' => 'LIKE'
		],
		[
			'key' => 'zipcode',
			'value' => $_GET['q'],
			'compare' => '='
		],
		[
			'key' => 'municipality',
			'value' => $_GET['q'],
			'compare' => 'LIKE'
		],
		[
			'key' => 'address',
			'value' => $_GET['q'],
			'compare' => 'LIKE'
		]
	];

}

if(isset($_GET['sortBy'])){
	$qargs['meta_key'] = $_GET['sortBy'];
	$qargs['order'] = $_GET['sort'];
	$qargs['orderby'] = 'meta_value_num';
}

$query = new WP_Query($qargs);
if($query->have_posts()) {
    while ($query->have_posts()) : $query->the_post();
        if(file_exists(get_stylesheet_directory() .'/mw/property-loop-item.php')){
            include get_stylesheet_directory() .'/mw/property-loop-item.php';
        }else{
            include WP_PLUGIN_DIR .'/flexyapress-mw/templates/property/property-loop-item.php';
        }
    endwhile;
}else {
    echo '<div class="no-properties">'.($business ? __('Vi har lige nu ingen ejendomme at vise', 'flexyapress') : __('Vi har lige nu ingen boliger at vise', 'flexyapress')).'</div>';
}
?>