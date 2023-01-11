<?php
    if($settings){
        $settings['sale_type'] = 'SOLD';
    }
?>
<div class="property-list-container <?= $settings['dark_color'] == 'yes' ? 'dark' : 'light'; ?>">
    <div class="property-list">
        <div class="inner">
            <div class="properties">
            <?php
                include WP_PLUGIN_DIR .'/flexyapress/includes/templates/property/property-loop.php';
            ?>
            </div>
        </div>
    </div>
</div>
<?php
wp_reset_postdata();