<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/
/** @var \AllInData\MicroErp\Auth\Block\Login $block */
?>
<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="true">
            <?php _e('Login', AID_MICRO_ERP_PLANNING_TEXTDOMAIN) ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="register-tab" data-toggle="tab" href="#register" role="tab" aria-controls="register" aria-selected="false">
            <?php _e('Register', AID_MICRO_ERP_PLANNING_TEXTDOMAIN) ?>
        </a>
    </li>
</ul>


<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
        <form id="login_form" method="post" action="<?= $block->getFormUrl() ?>">
            <input type="hidden" name="nonce" value="<?= $block->getFormNonce($block->getCreateUserActionSlug()); ?>"/>
            <input type="hidden" name="action" value="<?= $block->getCreateUserActionSlug(); ?>"/>
            <input type="hidden" name="<?= $block->getFormRedirectKey(); ?>" value="<?= $block->getFormRedirectUrl(); ?>"/>
            <input type="text" name="username" id="username" />
            <input type="password" name="password" id="password" />
            <input class="btn btn-primary" type="submit" id="login_submit" />
        </form>
    </div>
    <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
        <p>Register Form</p>
    </div>
</div>

