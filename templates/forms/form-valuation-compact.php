<?php
$consent = Flexyapress_API::getConsents('SalesValuation');
?>
<h3><?= __('Bestil Salgsvurdering', 'flexyapress'); ?></h3>
<form class="flexya-order-form form">
    <div class="row">
        <div class="col-12">
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
            <input type="hidden" name="shopNo" value="<?= get_option('flexyapress')['shop-no'] ?: ''; ?>">
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