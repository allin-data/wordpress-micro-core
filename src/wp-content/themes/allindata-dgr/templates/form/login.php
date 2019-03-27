<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/
?>
<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="true">
            <?= _e('Login', AID_DGR_THEME_TEXTDOMAIN) ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="register-tab" data-toggle="tab" href="#register" role="tab" aria-controls="register" aria-selected="false">
            <?= _e('Register', AID_DGR_THEME_TEXTDOMAIN) ?>
        </a>
    </li>
</ul>


<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
        <form id="login_form" method="post">
            <input type="text" name="username" id="username" />
            <input type="password" name="password" id="password" />
            <input type="hidden" name="action" value="login" />
            <input type="submit" id="login_submit" />
        </form>
    </div>
    <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
        <p>Register Form</p>
    </div>
</div>

