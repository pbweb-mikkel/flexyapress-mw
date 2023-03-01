<div class="property-list-container">
<?php

if($atts['show_search'] == 'true'){
    if(file_exists(get_stylesheet_directory() .'/mw/property-search.php')){
        include get_stylesheet_directory() .'/mw/property-search.php';
    }else{
        include WP_PLUGIN_DIR .'/flexyapress-mw/templates/property/property-search.php';
    }
}

?>
<div class="property-list">
    <div class="inner">
        <div class="title-bar">
            <div class="left-col">
                <h2 class="with-line"><span class="search-count"></span> <?= $atts['show_only'] == 'business' ? __('ejendomme matchede din søgning', 'flexyapress') : __('boliger matchede din søgning', 'flexyapress') ?></h2>
            </div>
            <div class="right-col">
                <div class="sortbox">
                    <div class="selected">
                        <span class="selected-text"><?= __('Nyeste', 'flexyapress'); ?></span> <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="selects">
                        <div class="select" data-sort="DESC" data-sort-by="publishedDateEpoch">
                            <?= __('Nyeste', 'flexyapress'); ?>
                        </div>
                        <div class="select" data-sort="ASC" data-sort-by="publishedDateEpoch">
                            <?= __('Ældste', 'flexyapress'); ?>
                        </div>
                        <div class="select" data-sort="DESC" data-sort-by="sizeArea">
                            <?= __('Boligareal - faldende', 'flexyapress'); ?>
                        </div>
                        <div class="select" data-sort="ASC" data-sort-by="sizeArea">
                            <?= __('Boligareal - stigende', 'flexyapress'); ?>
                        </div>
                        <div class="select" data-sort="ASC" data-sort-by="price">
                            <?= __('Pris - stigende', 'flexyapress'); ?>
                        </div>
                        <div class="select" data-sort="DESC" data-sort-by="price">
                            <?= __('Pris - faldende', 'flexyapress'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="properties">
            <?php
                if(file_exists(get_stylesheet_directory() .'/mw/property-loop.php')){
                    include get_stylesheet_directory() .'/mw/property-loop.php';
                }else{
                    include WP_PLUGIN_DIR .'/flexyapress-mw/templates/property/property-loop.php';
                }
            ?>
        </div>
    </div>
</div>
</div>
<?php
wp_reset_postdata();