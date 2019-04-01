<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

/** @var \AllInData\Dgr\Cms\Block\Admin\GridUser $block */
?>
<h2>User List</h2>
<p>Total: <?= $block->getUserTotalCount(); ?></p>

<table class="table">
    <thead>
    <tr>
        <th scope="col"><?php _e('User ID', AID_DGR_CMS_TEXTDOMAIN) ?></th>
        <th scope="col"><?php _e('First name', AID_DGR_CMS_TEXTDOMAIN) ?></th>
        <th scope="col"><?php _e('Last name', AID_DGR_CMS_TEXTDOMAIN) ?></th>
        <th scope="col"><?php _e('Login name', AID_DGR_CMS_TEXTDOMAIN) ?></th>
        <th scope="col"><?php _e('Email', AID_DGR_CMS_TEXTDOMAIN) ?></th>
        <th scope="col"><?php _e('Actions', AID_DGR_CMS_TEXTDOMAIN) ?></th>
    </tr>
    </thead>
    <tbody>
<?php foreach ($block->getUsers() as $user): ?>
    <tr>
        <form method="post" action="<?= $block->getFormUrl() ?>">
            <input type="hidden" name="nonce" value="<?= $block->getFormNonce($block->getUpdateUserActionSlug()); ?>"/>
            <input type="hidden" name="action" value="<?= $block->getUpdateUserActionSlug(); ?>"/>
            <input type="hidden" name="<?= $block->getFormRedirectKey(); ?>" value="<?= $block->getFormRedirectUrl(); ?>"/>
            <input type="hidden" name="userId" value="<?= $user->getId(); ?>"/>
            <th scope="row"><?= $user->getId(); ?></th>
            <td><input type="text" name="firstName" value="<?= $user->getFirstName(); ?>"/></td>
            <td><input type="text" name="lastName" value="<?= $user->getLastName(); ?>"/></td>
            <td><?= $user->getUserLogin(); ?></td>
            <td><input type="email" name="email" value="<?= $user->getUserEmail(); ?>"/></td>
            <td><input type="submit" value="<?php _e('Update', AID_DGR_CMS_TEXTDOMAIN); ?>"/></td>
        </form>
    </tr>
<?php endforeach; ?>
    </tbody>
</table>

<h2>New User</h2>
<div class="row">
    <form method="post" action="<?= $block->getFormUrl() ?>">
        <input type="hidden" name="nonce" value="<?= $block->getFormNonce($block->getCreateUserActionSlug()); ?>"/>
        <input type="hidden" name="action" value="<?= $block->getCreateUserActionSlug(); ?>"/>
        <input type="hidden" name="<?= $block->getFormRedirectKey(); ?>" value="<?= $block->getFormRedirectUrl(); ?>"/>

        <div class="row">
            <div class="col-md-6">
                <label for="firstName"><?php _e('First name', AID_DGR_CMS_TEXTDOMAIN) ?>: <input type="text" name="firstName" value=""/></label>
            </div>
            <div class="col-md-6">
                <label for="lastName"><?php _e('Last name', AID_DGR_CMS_TEXTDOMAIN) ?>: <input type="text" name="lastName" value=""/></label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label for="login"><?php _e('Login name', AID_DGR_CMS_TEXTDOMAIN) ?>: <input type="text" name="login" value=""/></label>
            </div>
            <div class="col-md-6">
                <label for="email"><?php _e('Email', AID_DGR_CMS_TEXTDOMAIN) ?>: <input type="email" name="email" value=""/></label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <input type="submit" value="<?php _e('Create user', AID_DGR_CMS_TEXTDOMAIN); ?>"/>
            </div>
        </div>

    </form>
</div>