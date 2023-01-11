<?php
$locations = json_decode(file_get_contents(WP_PLUGIN_DIR . '/flexyapress/includes/kmd_location.json'));
?>
<form id="set-search-agent-form" method="post" class="row form <?= $settings['one_column_layout'] == 'yes' ? 'one-column' : 'two-column'; ?>">
    <div class="left-col">
        <div class="form-group">
            <!--<label>Navn*</label>-->
            <input type="text" class="form-control" id="name" name="name" placeholder="Navn*" required="required">
        </div>
        <div class="form-group">
            <!--<label>Email*</label>-->
            <input type="email" class="form-control" id="email" name="email" placeholder="Email*" required="required">
        </div>
        <div class="form-group">
            <!--<label>Telefon*</label>-->
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Telefon*" required="required">
        </div>
        <div class="form-group">
            <!--<label>Nuværende boligsituation</label>-->
            <input type="text" class="form-control" id="current-living-status" name="currentLivingStatus" placeholder="Nuværende boligsituation">
        </div>
        <div class="form-group">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="buyingApprovementBank" id="buyingApprovementBank">
                <label class="form-check-label" for="buyingApprovementBank">
                    <?= __('Har du finansieringstilsagn til køb – sæt kryds hvis ja', 'flexyapress'); ?>
                </label>
            </div>
        </div>
        <div class="form-group">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="sellingProperty" id="sellingProperty">
                <label class="form-check-label" for="sellingProperty">
                    <?= __('Ønsker du salgsvurdering af din nuværende ejerbolig?', 'flexyapress'); ?>
                </label>
            </div>
        </div>
        <div class="form-group">
            <!--<label>Besked til ejendomsmægler</label>-->
            <textarea id="message" class="form-control" name="message" placeholder="Besked til ejendomsmægler"></textarea>
        </div>
    </div>

    <div class="col-lg-6 show-labels">
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

        <div class="form-group">
            <label><?= __('Hvilken type bolig søger du?') ?></label>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="propertyTypes" id="HOUSE" value="HOUSE">
                        <label class="form-check-label" for="HOUSE">
                            <?= __('Villa', 'flexyapress'); ?>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="propertyTypes" id="HOUSE_COOPERATIVE" value="HOUSE_COOPERATIVE">
                        <label class="form-check-label" for="HOUSE_COOPERATIVE">
                            <?= __('Andelsbolig/-lejlighed', 'flexyapress'); ?>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="propertyTypes" id="TOWNHOUSE" value="TOWNHOUSE">
                        <label class="form-check-label" for="TOWNHOUSE">
                            <?= __('Rækkehus', 'flexyapress'); ?>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="propertyTypes" id="APARTMENT" value="APARTMENT">
                        <label class="form-check-label" for="APARTMENT">
                            <?= __('Ejerlejlighed', 'flexyapress'); ?>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="propertyTypes" id="SUMMERHOUSE" value="SUMMERHOUSE">
                        <label class="form-check-label" for="SUMMERHOUSE">
                            <?= __('Sommerhus', 'flexyapress'); ?>
                        </label>
                    </div>
                </div>
                <div class="right-col">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="propertyTypes" id="LEISURETIME_FARM" value="LEISURETIME_FARM">
                        <label class="form-check-label" for="LEISURETIME_FARM">
                            <?= __('Nedlagt Landbrug', 'flexyapress'); ?>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="propertyTypes" id="LAND_FOR_HOUSE" value="LAND_FOR_HOUSE">
                        <label class="form-check-label" for="LAND_FOR_HOUSE">
                            <?= __('Helårsgrund', 'flexyapress'); ?>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="propertyTypes" id="LAND_FOR_SUMMERHOUSE" value="LAND_FOR_SUMMERHOUSE">
                        <label class="form-check-label" for="LAND_FOR_SUMMERHOUSE">
                            <?= __('Fritidsgrund', 'flexyapress'); ?>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="propertyTypes" id="HOUSE_APARTMENT" value="HOUSE_APARTMENT">
                        <label class="form-check-label" for="HOUSE_APARTMENT">
                            <?= __('Villalejlighed', 'flexyapress'); ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Pris niveau: <span id="price-show"></span></label>
            <div class="range" id="price-range"></div>
        </div>
        <div class="form-group">
            <label>Boligareal: <span id="area-show"></span></label>
            <div class="range" id="property-size"></div>
        </div>
        <div class="form-group">
            <label>Min. antal værelser: <span id="min-rooms-show"></span></label>
            <div class="range" id="min-rooms-range"></div>
        </div>
        <div class="form-group">
            <label>Grundareal: <span id="land-show"></span></label>
            <div class="range" id="land-size-range"></div>
        </div>

        <input type="hidden" id="min-price" name="minPrice" value="1000000">
        <input type="hidden" id="max-price" name="maxPrice" value="4000000">
        <input type="hidden" id="min-size" name="minSize" value="50">
        <input type="hidden" id="max-size" name="maxSize" value="200">
        <input type="hidden" id="min-rooms" name="minRooms" value="4">
        <input type="hidden" id="min-land-size" name="minLandSize" value="0">
        <input type="hidden" id="max-land-size" name="maxLandSize" value="700">
        <div class="form-group">
            <input type="submit" class="btn btn-orange mt-2" id="submit" value="Tilmeld køberkartotek">
        </div>
        <div class="status-message mt-2"></div>
        <div class="loading-spinner">
            <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        </div>
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