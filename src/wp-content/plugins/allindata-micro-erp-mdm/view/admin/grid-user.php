<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

/** @var \AllInData\MicroErp\Mdm\Block\Admin\GridUser $block */
?>
<h2><?php _e('User List', AID_MICRO_ERP_MDM_TEXTDOMAIN); ?></h2>
<p><?php _e('Total', AID_MICRO_ERP_MDM_TEXTDOMAIN); ?>: <?= $block->getUserTotalCount(); ?></p>

<table class="table">
    <thead>
    <tr>
        <th scope="col"><?php _e('User ID', AID_MICRO_ERP_MDM_TEXTDOMAIN) ?></th>
        <th scope="col"><?php _e('First name', AID_MICRO_ERP_MDM_TEXTDOMAIN) ?></th>
        <th scope="col"><?php _e('Last name', AID_MICRO_ERP_MDM_TEXTDOMAIN) ?></th>
        <th scope="col"><?php _e('Login name', AID_MICRO_ERP_MDM_TEXTDOMAIN) ?></th>
        <th scope="col"><?php _e('Email', AID_MICRO_ERP_MDM_TEXTDOMAIN) ?></th>
        <th scope="col"><?php _e('Actions', AID_MICRO_ERP_MDM_TEXTDOMAIN) ?></th>
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
            <td><input type="submit" value="<?php _e('Update', AID_MICRO_ERP_MDM_TEXTDOMAIN); ?>"/></td>
        </form>
    </tr>
<?php endforeach; ?>
    </tbody>
</table>
