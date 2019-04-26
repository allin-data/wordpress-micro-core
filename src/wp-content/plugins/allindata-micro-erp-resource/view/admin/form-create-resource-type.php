<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

/** @var \AllInData\MicroErp\Resource\Block\Admin\FormCreateResourceType $block */
?>
<h2><?php _e('Create Resource Type', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?></h2>
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
            <label for="label"><?php _e('Label', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?>:</label>
            <input type="text" name="label" class="form-control" value=""/>
        </div>
        
        <button class="btn btn-primary" type="submit">
            <?php _e('Create resource type', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?>
        </button>
    </form>
</div>