<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

/** @var \AllInData\MicroErp\Mdm\Block\UserProfile $block */
$user = $block->getUser();
?>
<h2><?php _e('User Profile', AID_MICRO_ERP_MDM_TEXTDOMAIN); ?></h2>
<div class="row">
    <form method="post" action="#">
        <input type="hidden" name="action" value="<?= $block->getUpdateUserActionSlug(); ?>"/>

        <div class="form-group">
            <label for="firstName"><?php _e('First name', AID_MICRO_ERP_MDM_TEXTDOMAIN) ?>:</label>
            <input type="text" name="firstName" class="form-control" value="<?= $user->getFirstName() ?>"/>
        </div>
        <div class="form-group">
            <label for="lastName"><?php _e('Last name', AID_MICRO_ERP_MDM_TEXTDOMAIN) ?>:</label>
            <input type="text" name="lastName" class="form-control" value="<?= $user->getLastName() ?>"/>
        </div>
        <button class="btn btn-primary" id="submit">
            <?php _e('Update profile', AID_MICRO_ERP_MDM_TEXTDOMAIN); ?>
        </button>
    </form>
</div>

<script>
    jQuery(document).ready(function ($) {
        $('#submit').click(function () {
            let button = $(this),
                payload = {};

            button.prop("disabled", true);

            payload.action = $('input[name="action"]').val();
            payload.firstName = $('input[name="firstName"]').val();
            payload.lastName = $('input[name="lastName"]').val();

            $.ajax({
                type: 'POST',
                url: wp_ajax_action.action_url,
                data: payload,
                success: function (data) {
                    button.prop("disabled", false);
                },
                error: function () {
                    button.prop("disabled", false);
                }
            });
        });
    });
</script>