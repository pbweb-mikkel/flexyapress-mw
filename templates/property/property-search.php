<?php
	$text = $settings['search_text'] ?: '';
    $prices = [];

    $args = array(
        'post_type' => 'sag',
        'posts_per_page' => -1,
    );

    $squery = new WP_Query($args);

    foreach ($squery->posts as $p){
        $price = get_post_meta($p->ID, 'price', true);
        if($price){
	        $prices[] = (int) $price;
        }
    }

    sort($prices);

    $min_price = $prices[0];
    $max_price = end($prices);

?>
<div id="property-search">
    <?php
        if($text){
            echo '<div class="search-text">'.$text.'</div>';
        }
    ?>
    <form id="property-search-form" action="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>">
        <div class="inner">
            <div class="left-col">
                <div class="price-range-container">
                    <div id="price-min"></div>
                    <div id="price-max"></div>
                    <div id="price-range" data-price-min="<?= $min_price ?>" data-price-max="<?= $max_price ?>" data-price-min-selected="<?= $_GET['minPrice'] ?: $min_price ?>" data-price-max-selected="<?= $_GET['maxPrice'] ?: $max_price ?>"></div>
                </div>
            </div>
            <div class="right-col">
                <div class="field-button">
                    <input type="text" id="search-text" name="q" placeholder="Søg på f.eks. Villa, by, postnummer" <?= $_GET['q'] ? 'value="'.$_GET['q'].'"' : ''; ?> >
                    <input type="hidden" name="minPrice">
                    <input type="hidden" name="maxPrice">
                    <input type="hidden" name="sort">
                    <input type="hidden" name="sortBy">
                    <input class="submit-btn" type="submit" value="Søg">
                </div>
            </div>
        </div>
    </form>
    <div id="toggle-search"><i class="fas fa-chevron-down"></i></div>
</div>