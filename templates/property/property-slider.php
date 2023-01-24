<?php
    $properties = new WP_Query([
       'post_type' => 'sag',
       'posts_per_page' => 5,
       'fields' => 'ids',
       'meta_query' => [
           [
               'key' => 'status',
               'value' => 'ACTIVE'
           ],
           [
               'key' => '_thumbnail_id'
           ]
       ]
    ]);
?>
<div class="property-slider-wrapper">
    <div class="flexyapress-property-slider">
        <?php if($properties->posts){ ?>
            <?php foreach ($properties->posts as $id){ ?>
                <?php $prop = new Flexyapress_Case($id); ?>
                <div class="slide">
                    <?= get_the_post_thumbnail($id, 'full') ?>
                    <div class="container">
                        <div class="text">
                            <?php if($prop->getPrice()){ ?>
                                <div class="price"><?= $prop->getPrettyPrice() ?> kr.</div>
                            <?php } ?>
                            <?php if($prop->getTitle()){ ?>
                                <h3><?= $prop->getTitle() ?></h3>
                                <div class="address"><?= $prop->getAddress() ?></div>
                            <?php }else{ ?>
                                <h3><?= $prop->getAddress() ?></h3>
                            <?php } ?>
                            <ul class="details">
                                <?php if($prop->getSizeArea()){ ?>
                                    <li>
                                        <span>Areal: </span>
                                        <span><?= $prop->getPrettySizeArea() ?></span>
                                    </li>
                                <?php } ?>
                                <?php if($prop->getNumberRooms()){ ?>
                                    <li>
                                        <span>Værelser: </span>
                                        <span><?= $prop->getNumberRooms() ?></span>
                                    </li>
                                <?php } ?>
                                <?php if($prop->getNumberBathrooms()){ ?>
                                <li>
                                    <span>Bad: </span>
                                    <span><?= $prop->getNumberBathrooms() ?></span>
                                </li>
                                <?php } ?>
                            </ul>
                            <a href="<?= get_the_permalink($id); ?>">Se detaljer</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>