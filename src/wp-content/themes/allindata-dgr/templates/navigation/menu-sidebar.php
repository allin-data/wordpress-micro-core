<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

/** @var \bitExpert\Disco\BeanFactory $beanFactory */
$beanFactory = \bitExpert\Disco\BeanFactoryRegistry::getInstance();
/** @var \AllInData\Dgr\Theme\Block\Navigation\Menu $block */
$block = $beanFactory->get('BlockNavigationMenu');
?>
<?php if ($block->isShown()): ?>
<div class="col-md-2 d-md-none">
    <?php
    get_template_part('templates/navigation/menu', 'main');
    get_template_part('templates/navigation/menu', 'admin');
    ?>
</div>
<div class="col-md-2 fill-height d-none d-md-block">
    <?php
    get_template_part('templates/navigation/menu', 'main');
    get_template_part('templates/navigation/menu', 'admin');
    ?>
</div>
<?php endif;