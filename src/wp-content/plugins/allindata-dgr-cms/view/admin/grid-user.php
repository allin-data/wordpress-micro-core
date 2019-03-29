<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

/** @var \AllInData\Dgr\Cms\Block\Admin\GridUser $block */
?>
<p>User List Admin Template</p>

<ul>
<?php foreach ($block->getUsers() as $user): ?>
    <li><?= $user->getFirstName(); ?></li>
<?php endforeach; ?>
</ul>
