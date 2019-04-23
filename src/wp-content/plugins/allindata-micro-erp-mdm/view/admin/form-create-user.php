<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

/** @var \AllInData\MicroErp\Mdm\Block\Admin\FormCreateUser $block */
?>
<h2><?php _e('Create User', AID_MICRO_ERP_MDM_TEXTDOMAIN); ?></h2>
<div class="row">
    <form method="post" action="<?= $block->getFormUrl() ?>">
        <input type="hidden" name="nonce" value="<?= $block->getFormNonce($block->getCreateUserActionSlug()); ?>"/>
        <input type="hidden" name="action" value="<?= $block->getCreateUserActionSlug(); ?>"/>
        <input type="hidden" name="<?= $block->getFormRedirectKey(); ?>" value="<?= $block->getFormRedirectUrl(); ?>"/>

        <div class="row">
            <div class="col-md-6">
                <label for="firstName"><?php _e('First name', AID_MICRO_ERP_MDM_TEXTDOMAIN) ?>: <input type="text" name="firstName" value=""/></label>
            </div>
            <div class="col-md-6">
                <label for="lastName"><?php _e('Last name', AID_MICRO_ERP_MDM_TEXTDOMAIN) ?>: <input type="text" name="lastName" value=""/></label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label for="login"><?php _e('Login name', AID_MICRO_ERP_MDM_TEXTDOMAIN) ?>: <input type="text" name="login" value=""/></label>
            </div>
            <div class="col-md-6">
                <label for="email"><?php _e('Email', AID_MICRO_ERP_MDM_TEXTDOMAIN) ?>: <input type="email" name="email" value=""/></label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <input class="btn btn-primary" type="submit" value="<?php _e('Create user', AID_MICRO_ERP_MDM_TEXTDOMAIN); ?>"/>
            </div>
        </div>

    </form>
</div>