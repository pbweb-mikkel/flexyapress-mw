    <?php
    $flexOptions = get_option('flexyapress');
    ?>
    <?php get_header(); ?>
    <?php do_action('flexyapress_before_case'); ?>
    <article id="main-content" class="property-single">
        <?php
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                $case = new Flexyapress_Case(get_the_ID());
                $case->fetch();

                if($case->get_save_images_locally()){
                    $photos = $case->getUnserializedImageOrder();
                    $drawings = $case->getUnserializedDrawingOrder();
                }else{
                    $photos = $case->getUnserializedPhotos();
                    $drawings = $case->getUnserializedDrawings();
                    $photos1000 = $case->getUnserializedPhotos1000();
                    $photosThumb = $case->getUnserializedThumbnails();
                }

                $videos = $case->getUnserializedVideos();
                $realtor = $case->getRealtorInfo();
                $slide_count = 0;
                $image_count = 0;
                $drawing_count = 0;
                $video_count = 0;
                $tour_enabled = false;
        ?>
                <?php

                ?>

                    <div class="property-images-container">
                        <div id="property-images">
                            <?php if($photos){
                                $count = 1;
                                foreach ($photos as $p){
                                    echo '<div class="slide image image-'.$count.'">';
                                    if($case->get_save_images_locally()){
                                        echo wp_get_attachment_image($p, 'full');
                                    }else{
                                        echo '<img src="'.$p.'">';
                                    }
                                    echo '</div>';
                                    $count++;
                                }
                            }else {?>
                                <div class="slide image image-1">
                                    <?php the_post_thumbnail('full'); ?>
                                </div>
                            <?php } ?>
                            <?php

                            if($drawings){
                                $count = 1;
                                foreach ($drawings as $p){
                                    echo '<div class="slide drawing drawing-'.$count.'">';
                                    if($case->get_save_images_locally()){
                                        echo wp_get_attachment_image($p, 'full');
                                    }else{
                                        echo '<img src="'.$p.'">';
                                    }
                                    echo '</div>';
                                }
                                $count++;
                            }

                            ?>
                            <div class="slide slide-map">
                                <?= $case->getMapIframe(); ?>
                            </div>
                        </div>
                        <div class="flags">
                            <?= $case->printFlag(true) ?>
                            <?= $case->printOpenHouseFlag(true) ?>
                        </div>
                        <div class="fullscreen"><img width="40" height="40" src="<?= WP_PLUGIN_URL ?>/flexyapress-mw/public/img/icons/fullscreen.svg" loading="lazy"><img width="40" height="40" src="<?= WP_PLUGIN_URL ?>/flexyapress-mw/public/img/icons/exit_fullscreen.svg" loading="lazy" style="display: none;"></div>
                    </div>

                    <div class="property-media-actions">
                        <?php if($photos){ ?>
                            <div id="go-to-images" class="action">
                                <div class="icon"><img width="40" height="40" src="<?= WP_PLUGIN_URL ?>/flexyapress-mw/public/img/icons/image.svg"></div>
                                <div class="name"><?= __('Billeder', 'flexyapress') ?></div>
                            </div>
                        <?php } ?>
                        <?php if($drawings){ ?>
                            <div id="go-to-drawings" class="action">
                                <div class="icon"><img width="40" height="40" src="<?= WP_PLUGIN_URL ?>/flexyapress-mw/public/img/icons/floorplan.svg"></div>
                                <div class="name"><?= __('Plantegning', 'flexyapress') ?></div>
                            </div>
                        <?php } ?>
                        <?php if($videos){ ?>
                            <div id="open-videos" class="action">
                                <div class="icon"><img width="40" height="40" src="<?= WP_PLUGIN_URL ?>/flexyapress-mw/public/img/icons/video.svg"></div>
                                <div class="name"><?= __('Video', 'flexyapress') ?></div>
                            </div>
                        <?php } ?>
                        <div id="go-to-map" class="action">
                            <div class="icon"><img width="40" height="40" src="<?= WP_PLUGIN_URL ?>/flexyapress-mw/public/img/icons/marker.svg"></div>
                            <div class="name"><?= __('Kort', 'flexyapress') ?></div>
                        </div>
                    </div>
                <div class="property-content">
                    <div class="inner">
                        <div class="property-description">
                            <div class="property-title"><h1><?= $case->getSimpleAddress() ?></h1> </div>
                            <?php the_content(); ?>
                        </div>
                        <div class="property-info">
                            <div class="spec-list">
                                <?php if($case->getPropertyType()){ ?><div><?= __('Boligtype', 'flexyapress') ?></div><div><?= Flexyapress_Helpers::property_type_nice_name($case->getPropertyType()) ?></div><?php } ?>
                                <?php if(in_array($case->getCaseType(), ['RentalCase', 'BusinessRentalCase'])){ ?>
                                    <?php if($case->getMonthlyRent()){ ?>
                                        <div><?= __('Leje pr. md.', 'flexyapress') ?></div><div><?= $case->getPrettyMonthlyRent() ?> kr.</div>
                                    <?php } ?>
                                <?php }elseif ($case->getPrice()) { ?>
                                    <div><?= __('Kontantpris', 'flexyapress') ?></div><div><?= $case->getPrettyPrice() ?> kr.<br><a href="https://www.raadtilpenge.dk/Gode-raad/boliglaan/landingsside">Tjek boliglån</a></div>
                                <?php } ?>
                                <?php if($case->getSizeArea()){ ?><div><?= __('Boligareal', 'flexyapress') ?></div><div><?= $case->getPrettySizeArea() ?></div><?php } ?>
                                <?php if($case->getSizeLand()){ ?><div><?= __('Grundareal', 'flexyapress') ?></div><div><?= $case->getPrettySizeLand() ?></div><?php } ?>
                                <?php if($case->getNumberRooms()){ ?><div><?= __('Antal rum', 'flexyapress') ?></div><div><?= $case->getNumberRooms() ?></div><?php } ?>
                                <?php if($case->getConstructionYear()){ ?><div><?= __('Byggeår', 'flexyapress') ?></div><div><?= $case->getPrettyConstructionYear() ?></div><?php } ?>
                                <?php if($case->getMonthlyOwnerExpenses() && in_array($case->getCaseType(), ['SalesCase', 'BusinessSalesCase'])){ ?><div><?= __('Ejerudgift pr. md.', 'flexyapress') ?></div><div><?= $case->getPrettyMonthlyOwnerExpenses() ?> kr.</div><?php } ?>
                                <?php if($case->getEnergyBrand()){ ?><div><?= __('Energimærke', 'flexyapress') ?></div><div><?= $case->getPrettyEnergyBrand() ?></div><?php } ?>
                                <?php if($case->getCaseNumber()){ ?><div><?= __('Sagsnummer', 'flexyapress') ?></div><div><?= $case->getCaseNumber() ?></div><?php } ?>
                            </div>
                            <?php if($case->getOpenHouseActive() && $case->getOpenhouseSignupRequired()) { ?>
                                <div class="popup-btn" data-target="order-openhouse-signup">Tilmeld åbent hus</div>
                            <?php } ?>
                            <div class="popup-btn" data-target="order-presentation">Bestil fremvisning</div>
                            <div class="popup-btn" data-target="order-documents">Hent salgsmateriale</div>
                            <div class="popup-btn" data-target="order-make-bid">Giv et bud</div>
                            <div class="popup-btn" data-target="order-case-contact">Kontakt mægler</div>
                        </div>
                </div>
            </div>
                <div class="contact-section">
                    <div class="contact-form">
                        <?php $consent = $case->getConsents('ContactEmployee'); ?>
                        <h2>Kontakt os</h2>
                        <form class="flexya-order-form form">
                            <div class="row">
                                <div class="order-2 order-lg-0">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="name" placeholder="<?= __('Navn', 'flexyapress'); ?>*" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="email" placeholder="<?= __('Email', 'flexyapress'); ?>*" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="tel" class="form-control" name="phone" placeholder="<?= __('Telefon', 'flexyapress'); ?>*" required>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" name="message" rows="6" placeholder="<?= __('Besked', 'flexyapress'); ?>*" required></textarea>
                                    </div>
                                    <div class="form-group checkbox">
                                        <label>
                                            <input class="form-check-input" type="checkbox" name="consent" >
                                            <span class="consent-text">
                                                <?= $consent['text'] ?>
                                            </span>
                                        </label>
                                    </div>
                                    <input type="hidden" name="buyerActionType" value="CASE_CONTACT_ORDER">
                                    <input type="hidden" name="consentIdGlobal" value="<?= $consent['id'] ?>">
                                    <input type="hidden" name="shopNo" value="<?= $case->getShopNo() ?>">
                                    <input type="hidden" name="caseNo" value="<?= $case->getCaseNumber(); ?>">
                                    <?php wp_nonce_field('submit-flexya-form', 'nonce') ?>
                                    <input type="submit" class="btn-green" value="<?= __('Send', 'flexyapress') ?>">
                                    <div class="status-message mt-2"></div>
                                    <div class="loading-spinner">
                                        <div class="lds-ellipsis">
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="success-message"><?= __('Tak for din besked, vi vender tilbage hurtigst muligt.', 'flexyapress') ?></div>
                    </div>
                    <div class="realtor">
                        <?php
                            if($case->getRealtorImage()){ ?>
                                <div class="realtor-image">
                                    <img src="<?= $case->getRealtorImage() ?>" alt="<?= $case->getRealtorName() ?>">
                                </div>
                            <?php }else{ ?>
                                <div class="realtor-image">
                                    <?= apply_filters('flexyapress_realtor_image', $case->getRealtorName()) ?>
                                </div>
                            <?php } ?>
                        <div class="realtor-name">
                            <?= $case->getRealtorName() ?>
                        </div>
                        <?php if($case->getRealtorEmail()){ ?>
                            <div class="realtor-email">
                                <a href="mailto:<?= $case->getRealtorEmail() ?>"><?= $case->getRealtorEmail() ?></a>
                            </div>
                        <?php } ?>
                        <?php if($case->getRealtorPhone()){ ?>
                            <div class="realtor-phone">
                                <a href="tel:<?= $case->getRealtorPhone() ?>"><?= $case->getRealtorPhone() ?></a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
        <?php
            } // end while
        } // end if
        ?>
    </article>
    <?php
        if(file_exists(get_stylesheet_directory().'/mw/form-order-presentation.php')){
            include(get_stylesheet_directory().'/mw/form-order-presentation.php');
        }else{
            include(WP_PLUGIN_DIR.'/flexyapress-mw/templates/forms/form-order-presentation.php');
        }

        if(file_exists(get_stylesheet_directory().'/mw/form-order-documents.php')){
            include(get_stylesheet_directory().'/mw/form-order-documents.php');
        }else{
            include(WP_PLUGIN_DIR.'/flexyapress-mw/templates/forms/form-order-documents.php');
        }

        if(file_exists(get_stylesheet_directory().'/mw/form-order-make-bid.php')){
            include(get_stylesheet_directory().'/mw/form-order-make-bid.php');
        }else{
            include(WP_PLUGIN_DIR.'/flexyapress-mw/templates/forms/form-order-make-bid.php');
        }

        if(file_exists(get_stylesheet_directory().'/mw/form-order-case-contact.php')){
            include(get_stylesheet_directory().'/mw/form-order-case-contact.php');
        }else{
            include(WP_PLUGIN_DIR.'/flexyapress-mw/templates/forms/form-order-case-contact.php');
        }

        if ($case->getOpenHouseActive() && $case->getOpenhouseSignupRequired()) {
            if(file_exists(get_stylesheet_directory().'/mw/form-openhouse-signup.php')){
                include(get_stylesheet_directory().'/mw/form-openhouse-signup.php');
            }else{
                include(WP_PLUGIN_DIR.'/flexyapress-mw/templates/forms/form-openhouse-signup.php');
            }
        }
    ?>
    <?php if($videos){ ?>
        <div id="vidBox">
            <div id="videCont">
                <video id="property-video" controls>
                    <source src="<?= $videos[0]['url'] ?>" type="<?= $videos[0]['mimeType'] ?>">
                </video>
            </div>
        </div>
    <?php } ?>
    <?php do_action('flexyapress_after_case'); ?>
    <?php get_footer(); ?>