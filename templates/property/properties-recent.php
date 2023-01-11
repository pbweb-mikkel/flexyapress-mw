<?php

$qargs = array(
	'post_type' => 'sag',
	'posts_per_page' => 6,
	/*'meta_query' => [
        'relation' => 'AND',
        [
            'key' => 'status',
            'value' => 'ACTIVE'
        ]
    ],*/
	'meta_key' => 'publishedDateEpoch',
    'orderby' => 'meta_value_num',
    'order' => 'DESC'
);


$query = new WP_Query($qargs);
?>
<div class="properties-recent-container">
	<?php

	if($h = $settings['heading']){
		echo '<h2 class="with-line">'.$h.'</h2>';
	}
	echo '<div class="recent-properties">';
		while ( $query->have_posts() ) : $query->the_post();
			include WP_PLUGIN_DIR .'/flexyapress/includes/templates/property/property-loop-item.php';
		endwhile;
	echo '</div>';
	?>
</div>
