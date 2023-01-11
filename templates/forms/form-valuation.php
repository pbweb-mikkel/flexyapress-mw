<?php
$consent = Flexyapress_API::getConsents('SalesValuation');
?>
<h3><?= __('Bestil Salgsvurdering', 'flexyapress'); ?></h3>
<form class="flexya-order-form form">
    <p>Udfyld formularen med den ønskede dato, så vender vi tilbage for at aftale nærmere</p>
    <div class="row">
        <?php if($atts['with_date']){ ?>
        <div class="col-12">
            <div class="datepicker"></div>
            <div class="date-error alert alert-danger d-none"><?= __('Vælg venligst en dato', 'flexyapress'); ?></div>
        </div>
        <?php } ?>
        <div class="col-12">
            <?php if($atts['with_date']){ ?>
            <div class="form-group form-control-wrap">
                <label for="time" class="placeholder"><?= __('Ønsket tidspunkt', 'pb') ?></label>
                <input type="text" class="form-control" name="time" required>
            </div>
            <?php } ?>
            <div class="form-group form-control-wrap">
                <label for="address" class="placeholder"><?= __('Adresse', 'pb') ?>*</label>
                <input type="text" class="form-control" name="address" id="address" required autocomplete="off">
                <input type="hidden" class="form-control" name="dawa-address-id" id="dawa-address-id" autocomplete="off">
            </div>
            <div class="form-group check-group">
                <label>
                    <input type="checkbox" name="livesOnAddress">
                    <span><?= __('Jeg bor på adressen', 'flexyapress') ?></span>
                </label>
            </div>
            <div class="form-group form-control-wrap">
                <label for="name" class="placeholder"><?= __('Navn', 'pb') ?>*</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="form-group form-control-wrap">
                <label for="email" class="placeholder"><?= __('Email', 'pb') ?>*</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group form-control-wrap">
                <label for="phone" class="placeholder"><?= __('Telefon', 'pb') ?>*</label>
                <input type="tel" class="form-control" name="phone" required>
            </div>
            <?php if($atts['with_date']){ ?>
            <input type="hidden" name="date" value="<?= date('d-m-Y', strtotime('now')); ?>">
            <?php } ?>
            <div class="form-group checkbox">
                <label>
                    <input class="form-check-input" type="checkbox" name="consent" >
                    <span class="consent-text">
                                        <?= $consent['text'] ?>
                                    </span>
                </label>
            </div>
            <input type="hidden" name="buyerActionType" value="VALUATION_ORDER">
            <input type="hidden" name="consentIdGlobal" value="<?= $consent['id'] ?>">
            <input type="hidden" name="action" value="submit_flexya_form">
            <?php wp_nonce_field( 'submit-flexya-form', 'nonce' ); ?>
            <input type="submit" value="Bestil salgsvurdering">
            <div class="loading-spinner">
                <div class="lds-ellipsis">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
            <div class="status-message mt-2"></div>
        </div>
    </div>
</form>
<div class="success-message"><?= __('Vi har modtaget din forespørgsel, vi vender tilbage hurtigst muligt', 'flexyapress'); ?></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fetch/2.0.3/fetch.min.js"></script>
<script src='<?php echo WP_PLUGIN_URL ?>/flexyapress-mw/public/inc/dawa-autocomplete2/dist/dawa-autocomplete2.min.js'></script>
<script>
    "use strict"
    dawaAutocomplete.dawaAutocomplete(document.getElementById("address"), {
        select: function(selected) {
            console.log(selected.data.id);
            document.getElementById("dawa-address-id").setAttribute('value', selected.data.id);
        }});
</script>