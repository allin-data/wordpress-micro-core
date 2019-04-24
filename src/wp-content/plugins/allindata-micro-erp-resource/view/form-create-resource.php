<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

/** @var \AllInData\MicroErp\Resource\Block\FormCreateResource $block */
?>
<h2><?= $block->getTitle(); ?></h2>
<div class="row">
    <form method="post" action="<?= $block->getFormUrl() ?>">
        <input type="hidden" name="nonce" value="<?= $block->getFormNonce($block->getCreateActionSlug()); ?>"/>
        <input type="hidden" name="action" value="<?= $block->getCreateActionSlug(); ?>"/>
        <input type="hidden" name="<?= $block->getFormRedirectKey(); ?>" value="<?= $block->getFormRedirectUrl(); ?>"/>

        <div class="form-group">
            <label for="name"><?php _e('Name', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?>:</label>
            <input type="text" name="name" class="form-control" value=""/>
        </div>
        <div class="form-group">
            <label for="typeId"><?php _e('Type', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?>:</label>
            <select class="custom-select" name="typeId">
                <option selected>
                    <?= sprintf(
                            __('Select %1$s type', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN),
                            $block->getResourceLabel()
                    ); ?>
                </option>
                <?php foreach ($block->getResourceTypeSet() as $resourceType): ?>
                    <option value="<?= $resourceType->getId(); ?>"><?= $resourceType->getLabel(); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <input class="btn btn-success" type="submit"
               value="<?= sprintf(__('Create %1$s', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN), $block->getResourceLabel()); ?>"/>

    </form>
</div>