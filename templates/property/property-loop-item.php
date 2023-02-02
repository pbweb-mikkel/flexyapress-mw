<?php
    $case = new Flexyapress_Case(get_the_ID());
    $case->fetch();
	$price = $case->getPrettyPrice();
	$date = $case->getPublishedDate();

?>
<div class="property" data-aos="fade-up">
	<div class="inner">
		<?= $case->isActive() ? '<a href="'.get_the_permalink().'">' : ''; ?>
			<div class="image">
                <?= $case->printFlag(true) ?>
				<?= '<img src="'.$case->getPrimaryPhoto1000().'" alt="'.$case->getSimpleAddress().'" loading="lazy">'; ?>
			</div>
            <?= $case->printOpenHouseFlag() ?>
            <div class="property-title">
                <h3><span class="address"><?= $case->getSimpleAddressWithoutCity() ?></span><br><span class="city"><?= $case->getZipcode().' '.$case->getCity() ?></span></h3>
            </div>
			<div class="content">
                <div class="spec-list">
                    <div><?= __('Boligtype', 'flexyapress') ?></div><div><?= Flexyapress_Helpers::property_type_nice_name($case->getPropertyType()) ?></div>
                    <?php if($case->getSizeArea()){ ?>
                        <div><?= __('Boligareal', 'flexyapress') ?></div><div><?= $case->getPrettySizeArea() ?></div>
                    <?php } ?>
                    <?php if($case->getSizeLand()){ ?>
                        <div><?= __('Grundareal', 'flexyapress') ?></div><div><?= $case->getPrettySizeLand() ?></div>
                    <?php } ?>
                    <?php if(in_array($case->getCaseType(), ['RentalCase', 'BusinessRentalCase'])){ ?>
                        <?php if($case->getMonthlyRent() && $case->getStatus() == 'ACTIVE'){ ?>
                            <div><?= __('Leje pr. md.', 'flexyapress') ?></div><div><?= $case->getPrettyMonthlyRent() ?> kr.</div>
                        <?php } ?>
                    <?php }else if($case->getPrice() && $case->getStatus() == 'ACTIVE'){ ?>
                        <div><?= __('Pris', 'flexyapress') ?></div><div><?= $case->getPrettyPrice() ?> kr.</div>
                    <?php } ?>
                </div>
			</div>
        <?= $case->isActive() ? '</a>' : ''; ?>
	</div>
</div>