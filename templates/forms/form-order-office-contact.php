<?php $consent = Flexyapress_API::getConsents('Contact'); ?>
<div id="order-contact">
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
                <input type="hidden" name="buyerActionType" value="CONTACT_ORDER">
                <input type="hidden" name="consentIdGlobal" value="<?= $consent['id'] ?>">
                <input type="hidden" name="shopNo" value="<?= Flexyapress_API::getShopNo() ?>">
                <?php wp_nonce_field('submit-flexya-form', 'nonce') ?>
                <div class="form-group">
                    <input type="submit" class="btn-green" value="<?= __('Send', 'flexyapress') ?>">
                </div>
                <div class="form-group">
                    <span class="consent-text">
                        <?= $consent['text'] ?>
                    </span>
                </div>
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