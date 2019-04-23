<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/
/** @var \AllInData\MicroErp\Auth\Block\Login $block */
?>
<div class="d-flex justify-content-center h-100">
    <div class="card">
        <div class="card-header">
            <h3><?php _e('Sign In', AID_MICRO_ERP_AUTH_TEXTDOMAIN) ?></h3>
        </div>
        <div class="card-body">
            <form id="login_form" method="post" action="<?= $block->getFormUrl() ?>">
                <input type="hidden" name="nonce" value="<?= $block->getFormNonce($block->getCreateUserActionSlug()); ?>"/>
                <input type="hidden" name="action" value="<?= $block->getCreateUserActionSlug(); ?>"/>
                <input type="hidden" name="<?= $block->getFormRedirectKey(); ?>"
                       value="<?= $block->getFormRedirectUrl(); ?>"/>

                <div class="input-group form-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input type="text" class="form-control" placeholder="username" name="username" id="username"/>

                </div>
                <div class="input-group form-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                    </div>
                    <input type="password" class="form-control" placeholder="password" name="password" id="password"/>
                </div>
                <div class="row align-items-center remember">
                    <input type="checkbox"/>&nbsp;Remember Me
                </div>
                <div class="form-group">
                    <button type="submit" class="btn float-right login_btn" id="login_submit">
                        <?php _e('Login', AID_MICRO_ERP_AUTH_TEXTDOMAIN) ?>
                    </button>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-center links">
                <?php _e('Don\'t have an account?', AID_MICRO_ERP_AUTH_TEXTDOMAIN) ?>&nbsp;
                <a href="#"><?php _e('Sign Up', AID_MICRO_ERP_AUTH_TEXTDOMAIN) ?></a>
            </div>
            <div class="d-flex justify-content-center">
                <a href="#"><?php _e('Forgot your password?', AID_MICRO_ERP_AUTH_TEXTDOMAIN) ?></a>
            </div>
        </div>
    </div>
</div>

