<?php $consent_with_contact = $case->getConsents('SalesMaterialWithContact'); ?>
<?php $consent_without_contact = $case->getConsents('SalesMaterial'); ?>
<div id="order-documents" class="pb-popup-container">
    <div class="pb-popup-wrapper">
        <div class="pb-popup">
            <div class="close"><i class="fa fa-times"></i></div>
            <div class="inner">
                <h3 class="popup-title with-line"><?= __('Hent salgsmateriale', 'flexyapress') ?></h3>
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
                                <label class="form-check-label" for="contactAccepted">
                                    <input class="form-check-input" type="checkbox" name="contactAccepted" id="contactAccepted">
                                    <span class="consent-text">Jeg ønsker at blive kontaktet</span>
                                </label>
                            </div>
                            <!--<div class="form-group checkbox">
                                <label>
                                    <input class="form-check-input" type="checkbox" name="consent" >
                                    <span class="consent-text">
                                        <?= __('Når du benytter vores formular, har vi brug for dit samtykke. Dine data behandles fortroligt. Du kan læse hele <a href="/privatlivspolitik">vores privatlivspolitik her</a>.', 'flexyapress') ?>
                                    </span>
                                </label>
                            </div>-->
                            <input type="hidden" name="buyerActionType" value="DOCUMENTS_ORDER">
                            <input type="hidden" name="consentIdGlobalWithContact" value="<?= $consent_with_contact['id'] ?>">
                            <input type="hidden" name="consentIdGlobalWithoutContact" value="<?= $consent_without_contact['id'] ?>">
                            <input type="hidden" name="shopNo" value="<?= $case->getShopNo() ?>">
                            <input type="hidden" name="caseNo" value="<?= $case->getCaseNumber(); ?>">
                            <?php wp_nonce_field('submit-flexya-form', 'nonce') ?>
                            <input type="submit" class="btn-green" value="<?= __('Næste', 'flexyapress') ?>">
                            <div class="consent" style="margin-top:10px;">
                                <span id="consent_with_contact" style="display:none">
                                    <?= $consent_with_contact['text'] ?>
                                </span>
                                <span id="consent_without_contact">
                                    <?= $consent_without_contact['text'] ?>
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
                <div class="success-message"><?= __('Tak for din bestilling. Du modtager dokumenterne på din mail såfremt du har bedt om det', 'flexyapress') ?></div>
            </div>
        </div>
    </div>
</div>