<?php $consent = $case->getConsents('ContactEmployee'); ?>
<div id="order-case-contact" class="pb-popup-container">
    <div class="pb-popup-wrapper">
        <div class="pb-popup">
            <div class="close"><i class="fa fa-times"></i></div>
            <div class="inner">
                <h3 class="popup-title with-line"><?= __('Kontakt mægler', 'flexyapress') ?></h3>
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
        </div>
    </div>
</div>