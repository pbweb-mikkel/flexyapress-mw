<?php
    $consent = $case->getConsents('OpenHouse');
    $oh = $case->getNextOpenHouse();
    date_default_timezone_set('Europe/Copenhagen');
    $date = date('d-m-Y', $oh['dateStartUnix']);
    $time = date('H:i', $oh['dateStartUnix']);
?>
<div id="order-openhouse-signup" class="pb-popup-container">
    <div class="pb-popup-wrapper">
        <div class="pb-popup">
            <div class="close"><i class="fa fa-times"></i></div>
            <div class="inner">
                <h3 class="popup-title with-line"><?= __('Tilmeld åbent Hus', 'flexyapress') ?> d. <?= $date ?> kl. <?= $time ?></h3>
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
                            <div class="form-group checkbox">
                                <label>
                                    <input class="form-check-input" type="checkbox" name="consent" >
                                    <span class="consent-text">
                                        <?= $consent['text'] ?>
                                    </span>
                                </label>
                            </div>
                            <input type="hidden" name="buyerActionType" value="OPENHOUSE_SIGNUP">
                            <input type="hidden" name="consentIdGlobal" value="<?= $consent['id'] ?>">
                            <input type="hidden" name="shopNo" value="<?= $case->getShopNo() ?>">
                            <input type="hidden" name="caseNo" value="<?= $case->getCaseNumber(); ?>">
                            <input type="hidden" name="openHouseId" value="<?= $oh['id']; ?>">
                            <input type="hidden" name="openHouseStartTime" value="<?= $oh['dateStart']; ?>">
                            <?php wp_nonce_field('submit-flexya-form', 'nonce') ?>
                            <input type="submit" class="btn-green" value="<?= __('Tilmeld', 'flexyapress') ?>">
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
                <div class="success-message"><?= __('Mange tak for din tilmelding', 'flexyapress') ?></div>
            </div>
        </div>
    </div>
</div>