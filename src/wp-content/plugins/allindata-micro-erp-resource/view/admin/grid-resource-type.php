<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

/** @var \AllInData\MicroErp\Resource\Block\Admin\GridResourceType $block */
?>
<h2><?php _e('Resource Type List', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?></h2>

<?php $block->renderPaginationBlock(true, true); ?>

<table class="table">
    <thead>
    <tr>
        <th scope="col"><?php _e('Resource Type ID', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
        <th scope="col"><?php _e('Name', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
        <th scope="col"><?php _e('Label', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
        <th scope="col"><?php _e('Is Disabled?', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
        <th scope="col"><?php _e('Actions', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
    </tr>
    </thead>
    <tbody>
<?php foreach ($block->getResourceTypes() as $resourceType): ?>
    <tr>
        <form method="post" action="<?= $block->getFormUrl() ?>">
            <input type="hidden" name="nonce" value="<?= $block->getFormNonce($block->getUpdateActionSlug()); ?>"/>
            <input type="hidden" name="action" value="<?= $block->getUpdateActionSlug(); ?>"/>
            <input type="hidden" name="<?= $block->getFormRedirectKey(); ?>" value="<?= $block->getFormRedirectUrl(); ?>"/>
            <input type="hidden" name="resourceTypeId" value="<?= $resourceType->getId(); ?>"/>
            <th scope="row"><?= $resourceType->getId(); ?></th>
            <td><?= $resourceType->getName(); ?></td>
            <td><input type="text" class="form-control" name="label" value="<?= $resourceType->getLabel(); ?>"/></td>
            <td><input type="checkbox" class="form-control" name="is_disabled"
                       value="on" <?php if ($resourceType->getIsDisabled()): ?>checked<?php endif; ?>/></td>
            <td><input class="btn btn-primary" type="submit" value="<?php _e('Update', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?>"/></td>
        </form>
    </tr>
<?php endforeach; ?>
    </tbody>
</table>

<?php $block->renderPaginationBlock(); ?>
