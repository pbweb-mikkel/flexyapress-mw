<?php
$locations = json_decode(file_get_contents(WP_PLUGIN_DIR . '/flexyapress/includes/kmd_location.json'));
?>
<form id="set-search-agent-form" method="post" class="flexya-order-form form">
    <div class="left-col">
        <label>Oplysninger om dig</label>
        <div class="form-group form-control-wrap">
            <input type="text" class="form-control" id="name" name="name" required="required">
            <label for="name" class="placeholder"><?= __('Navn', 'pb') ?>*</label>
        </div>
        <div class="form-group form-control-wrap">
            <!--<label>Email*</label>-->
            <input type="email" class="form-control" id="email" name="email" required="required">
            <label for="name" class="placeholder"><?= __('Email', 'pb') ?>*</label>
        </div>
        <div class="form-group form-control-wrap">
            <!--<label>Telefon*</label>-->
            <input type="text" class="form-control" id="phone" name="phone" required="required">
            <label for="name" class="placeholder"><?= __('Telefon', 'pb') ?>*</label>
        </div>
        <div class="form-group form-control-wrap">
            <!--<label>Nuværende boligsituation</label>-->
            <input type="text" class="form-control" id="current-living-status" name="currentLivingStatus">
            <label for="name" class="placeholder"><?= __('Nuværende boligsituation', 'pb') ?></label>
        </div>
        <div class="form-group">
            <div class="check-group">
                <label>
                    <input type="checkbox" name="buyingApprovementBank" id="buyingApprovementBank">
                    <span><?= __('Har du finansieringstilsagn til køb – sæt kryds hvis ja', 'flexyapress'); ?></span>
                </label>
            </div>
        </div>
        <div class="form-group">
            <div class="check-group">
                <label>
                    <input type="checkbox" name="sellingProperty" id="sellingProperty">
                    <span><?= __('Ønsker du salgsvurdering af din nuværende ejerbolig?', 'flexyapress'); ?></span>
                </label>
            </div>
        </div>
    </div>

    <div class="col-lg-12 show-labels">
        <div class="mt-2">
            <div class="form-group">
                <label>Hvor vil du gerne bo?</label>
                <div id="location-select">
                    <select id="locations" multiple="multiple" name="locations" required="required">
                        <?php foreach ($locations as $loc) { ?>
                            <option value="<?php echo $loc->id; ?>"><?php echo $loc->label; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="mt-2">
            <div class="form-group">
                <label><?= __('Hvilken type bolig søger du?') ?></label>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="check-group">
                            <label>
                                <input type="checkbox" name="propertyTypes" id="HOUSE" value="HOUSE">
                                <span><?= __('Villa', 'flexyapress'); ?></span>
                            </label>
                        </div>
                        <div class="check-group">
                            <label>
                                <input type="checkbox" name="propertyTypes" id="HOUSE_COOPERATIVE" value="HOUSE_COOPERATIVE">
                                <span><?= __('Andelsbolig/-lejlighed', 'flexyapress'); ?></span>
                            </label>
                        </div>
                        <div class="check-group">
                            <label>
                            <input type="checkbox" name="propertyTypes" id="TOWNHOUSE" value="TOWNHOUSE">
                            <span>
                                <?= __('Rækkehus', 'flexyapress'); ?>
                            </span>
                            </label>
                        </div>
                        <div class="check-group">
                            <label>
                            <input type="checkbox" name="propertyTypes" id="APARTMENT" value="APARTMENT">
                            <span>
                                <?= __('Ejerlejlighed', 'flexyapress'); ?>
                            </span>
                            </label>
                        </div>
                        <div class="check-group">
                            <label>
                            <input type="checkbox" name="propertyTypes" id="SUMMERHOUSE" value="SUMMERHOUSE">
                            <span>
                                <?= __('Sommerhus', 'flexyapress'); ?>
                            </span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="check-group">
                            <label>
                            <input type="checkbox" name="propertyTypes" id="LEISURETIME_FARM" value="LEISURETIME_FARM">
                            <span>
                                <?= __('Nedlagt Landbrug', 'flexyapress'); ?>
                            </span>
                            </label>
                        </div>
                        <div class="check-group">
                            <label>
                            <input type="checkbox" name="propertyTypes" id="LAND_FOR_HOUSE" value="LAND_FOR_HOUSE">
                            <span>
                                <?= __('Helårsgrund', 'flexyapress'); ?>
                            </span>
                            </label>
                        </div>
                        <div class="check-group">
                            <label>
                            <input type="checkbox" name="propertyTypes" id="LAND_FOR_SUMMERHOUSE" value="LAND_FOR_SUMMERHOUSE">
                            <span>
                                <?= __('Fritidsgrund', 'flexyapress'); ?>
                            </span>
                            </label>
                        </div>
                        <div class="check-group">
                            <label>
                            <input type="checkbox" name="propertyTypes" id="HOUSE_APARTMENT" value="HOUSE_APARTMENT">
                            <span>
                                <?= __('Villalejlighed', 'flexyapress'); ?>
                            </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-2">
            <div class="form-group">
                <label>Pris niveau: <span id="price-show"></span></label>
                <div class="range" id="price-range"></div>
            </div>
        </div>
        <div class="mt-2">
            <div class="form-group">
                <label>Boligareal: <span id="area-show"></span></label>
                <div class="range" id="property-size"></div>
            </div>
        </div>
        <div class="mt-2">
            <div class="form-group">
                <label>Min. antal værelser: <span id="min-rooms-show"></span></label>
                <div class="range" id="min-rooms-range"></div>
            </div>
        </div>
        <div class="mt-2">
            <div class="form-group">
                <label>Grundareal: <span id="land-show"></span></label>
                <div class="range" id="land-size-range"></div>
            </div>
        </div>
        <div class="mt-3">
            <div class="form-group form-control-wrap">
                <!--<label>Besked til ejendomsmægler</label>-->
                <textarea id="message" class="form-control" name="message"></textarea>
                <label for="message" class="placeholder"><?= __('Evt. besked til ejendomsmægler', 'pb') ?></label>
            </div>
        </div>
        <input type="hidden" id="min-price" name="minPrice" value="1000000">
        <input type="hidden" id="max-price" name="maxPrice" value="4000000">
        <input type="hidden" id="min-size" name="minSize" value="50">
        <input type="hidden" id="max-size" name="maxSize" value="200">
        <input type="hidden" id="min-rooms" name="minRooms" value="4">
        <input type="hidden" id="min-land-size" name="minLandSize" value="0">
        <input type="hidden" id="max-land-size" name="maxLandSize" value="700">
        <?php wp_nonce_field('submit-flexya-form', 'nonce') ?>
        <div class="form-group">
            <input type="submit" class="btn btn-orange mt-2" id="submit" value="Tilmeld køberkartotek">
            <div class="loading-spinner">
                <div class="lds-ellipsis">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
        <div class="status-message mt-2"></div>
        <div class="consent-text mt-2">
            <?php
            if (get_field('betingelser_search_agent', 'option')) {
                echo get_field('betingelser_search_agent', 'option');
            } else {
                echo get_field('betingelser_standard', 'option');
            }
            ?>
        </div>
    </div>


</form>
<div class="success-message"><?= __('Du er nu gemt i vores køberkartotek. Vi kontakter dig når vi har fundet din drømmebolig.', 'flexyapress'); ?></div>