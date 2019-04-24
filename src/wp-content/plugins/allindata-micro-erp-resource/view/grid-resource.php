<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

/** @var \AllInData\MicroErp\Resource\Block\GridResource $block */
?>
<h2><?= $block->getTitle(); ?></h2>

<?php $block->renderPaginationBlock(true, true); ?>

<table class="table">
    <thead>
    <tr>
        <th scope="col"><?= sprintf(__('%1$s ID', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN), $block->getResourceLabel()); ?></th>
        <th scope="col"><?php _e('Name', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
        <th scope="col"><?php _e('Type', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
        <th scope="col"><?php _e('Actions', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
    </tr>
    </thead>
    <tbody>
<?php foreach ($block->getResources() as $resource): ?>
    <tr>
        <input type="hidden" name="action" value="<?= $block->getUpdateActionSlug(); ?>"/>

        <th scope="row"><?= $resource->getId(); ?></th>
        <td><input type="text" class="form-control" name="name" value="<?= $resource->getName(); ?>"/></td>
        <td>
            <?= $block->getResourceType()->getLabel() ?>
        </td>
        <td>
            <button id="update"
                    data-id="<?= $resource->getId(); ?>"
                    data-action="<?= $block->getUpdateActionSlug(); ?>"
                    class="btn btn-info">
                <?php _e('Update', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?>
            </button>
            <button id="delete"
                    data-id="<?= $resource->getId(); ?>"
                    data-action="<?= $block->getDeleteActionSlug(); ?>"
                    class="btn btn-danger">
                <?php _e('Delete', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?>
            </button>
        </td>
    </tr>
<?php endforeach; ?>
    </tbody>
</table>

<?php $block->renderPaginationBlock(); ?>

<script>
    jQuery(document).ready(function ($) {
        let updateButton = $('#update'),
            deleteButton = $('#delete');

        updateButton.click(function () {
            let button = $(this),
                payload = {};

            updateButton.prop("disabled", true);
            deleteButton.prop("disabled", true);

            payload.action = button.data('action');
            payload.resourceId = button.data('id');
            payload.name = $('input[name="name"]').val();

            $.ajax({
                type: 'POST',
                url: wp_ajax_action.action_url,
                data: payload,
                success: function (data) {
                    updateButton.prop("disabled", false);
                    deleteButton.prop("disabled", false);
                },
                error: function () {
                    updateButton.prop("disabled", false);
                    deleteButton.prop("disabled", false);
                }
            });
        });

        deleteButton.click(function () {
            let button = $(this),
                payload = {};

            updateButton.prop("disabled", true);
            deleteButton.prop("disabled", true);

            payload.action = button.data('action');
            payload.resourceId = button.data('id');

            $.ajax({
                type: 'POST',
                url: wp_ajax_action.action_url,
                data: payload,
                success: function (data) {
                    updateButton.prop("disabled", false);
                    deleteButton.prop("disabled", false);
                    deleteButton.closest("tr").remove();
                    location.reload();
                },
                error: function () {
                    updateButton.prop("disabled", false);
                    deleteButton.prop("disabled", false);
                }
            });
        });
    });
</script>