<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

/** @var \AllInData\MicroErp\Resource\Block\FormCreateResource $block */
$resourceType = $block->getResourceType();
?>
    <h2><?= $block->getTitle(); ?></h2>
<?php if ($block->isCreationAllowed()): ?>
    <div class="row">
        <form method="post" action="<?= $block->getFormUrl() ?>">
            <input type="hidden" name="nonce" value="<?= $block->getFormNonce($block->getCreateActionSlug()); ?>"/>
            <input type="hidden" name="typeId" value="<?= $block->getResourceTypeId(); ?>"/>
            <input type="hidden" name="action" value="<?= $block->getCreateActionSlug(); ?>"/>
            <input type="hidden" name="<?= $block->getFormRedirectKey(); ?>"
                   value="<?= $block->getFormRedirectUrl(); ?>"/>

            <?php foreach ($block->getResourceTypeAttributes($resourceType) as $resourceTypeAttribute): ?>
                <?php $attributeType = $block->getResourceAttributeType($resourceTypeAttribute); ?>
                <div class="form-group">
                    <label for="<?= $attributeType->renderFormLabelName($resourceTypeAttribute) ?>"><?= $resourceTypeAttribute->getName() ?>
                        :</label>
                    <?= $attributeType->renderFormPart($resourceTypeAttribute) ?>
                </div>
            <?php endforeach; ?>

            <input class="btn btn-success" type="submit"
                   value="<?= sprintf(__('Create %1$s', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN),
                       $block->getResourceLabel()); ?>"/>

        </form>
    </div>
<?php else: ?>
    <div class="row">
        <p class="justify-content-center">
            <?= sprintf(
                __('The creation of %1$s is currently disabled.', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN),
                $block->getResourceLabel()
            ); ?>
        </p>
    </div>
<?php endif;