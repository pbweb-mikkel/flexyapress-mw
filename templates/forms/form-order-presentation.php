<?php $consent = $case->getConsents('Presentation'); ?>
<div id="order-presentation" class="pb-popup-container">
    <div class="pb-popup-wrapper">
        <div class="pb-popup">
            <div class="close"><i class="fa fa-times"></i></div>
            <div class="inner">
                <h3 class="popup-title with-line"><?= __('Bestil fremvisning', 'flexyapress') ?></h3>
                <form class="flexya-order-form form">
                    <div class="group">
                        <div class="datepicker"></div>
                        <div class="date-error alert alert-danger d-none"><?= __('Vælg venligst en dato', 'flexyapress'); ?></div>
                    </div>
                    <div class="group">
                        <div class="form-group form-control-wrap">
                            <label for="time" class="placeholder"><?= __('Ønsket tidspunkt', 'pb') ?></label>
                            <select name="time" data-minimum-results-for-search="9999">
                                <option value="08:00">08:00</option>
                                <option>08:30</option>
                                <option>09:00</option>
                                <option>09:30</option>
                                <option>10:00</option>
                                <option>10:30</option>
                                <option>11:00</option>
                                <option>11:30</option>
                                <option>12:00</option>
                                <option>12:30</option>
                                <option>13:00</option>
                                <option>13:30</option>
                                <option>14:00</option>
                                <option>14:30</option>
                                <option>15:00</option>
                                <option>15:30</option>
                                <option>16:00</option>
                                <option>16:30</option>
                                <option>17:00</option>
                                <option>17:30</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" placeholder="<?= __('Navn', 'flexyapress'); ?>*" required>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" placeholder="<?= __('Email', 'flexyapress'); ?>*" required>
                        </div>
                        <div class="form-group">
                            <input type="tel" class="form-control" name="phone" placeholder="<?= __('Telefon', 'flexyapress'); ?>*" required>
                        </div>
                        <input type="hidden" name="date" value="">
                        <input type="hidden" name="buyerActionType" value="PRESENTATION_ORDER">
                        <input type="hidden" name="action" value="submit_flexya_form">
                        <input type="hidden" name="consentIdGlobal" value="<?= $consent['id'] ?>">
                        <input type="hidden" name="shopNo" value="<?= $case->getShopNo() ?>">
                        <input type="hidden" name="caseNo" value="<?= $case->getCaseNumber(); ?>">
                        <?php wp_nonce_field( 'submit-flexya-form', 'nonce' ); ?>
                        <div class="submit-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="consent">
                                    <span><?= $consent['text'] ?></span>
                                </label>
                            </div>
                            <input type="submit" value="Bestil fremvisning">
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
                </form>
                <div class="success-message"><?= __('Mange tak for din besked, vi kontakter dig for at aftale nærmere', 'flexyapress') ?></div>
            </div>
        </div>
    </div>
</div>