<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

/** @var \AllInData\MicroErp\Resource\Block\Admin\GridResourceType $block */
?>
<h2><?php _e('Resource Type List', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?></h2>

<?php $block->renderPaginationBlock(true, true); ?>

<table class="table">
    <thead>
    <tr>
        <th scope="col"><?php _e('Resource Type ID', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
        <th scope="col"><?php _e('Name', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
        <th scope="col"><?php _e('Label', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
        <th scope="col"><?php _e('Is Disabled?', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
        <th scope="col"><?php _e('Actions', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($block->getResourceTypes() as $resourceType): ?>
        <tr>
            <th scope="row"><?= $resourceType->getId(); ?></th>
            <td><?= $resourceType->getName(); ?></td>
            <td><input id="label_<?= $resourceType->getId(); ?>"
                       type="text" class="form-control"
                       value="<?= $resourceType->getLabel(); ?>"/>
            </td>
            <td><input id="is_disabled_<?= $resourceType->getId(); ?>"
                       type="checkbox"
                       class="form-control checkboxchecker"
                       value="on" <?php if ($resourceType->getIsDisabled()): ?>checked<?php endif; ?>/></td>
            <td>
                <button class="btn btn-success btn-update"
                        data-id="<?= $resourceType->getId(); ?>"
                        data-action="<?= $block->getUpdateActionSlug(); ?>">
                    <?php _e('Update', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?>
                </button>
                <button class="btn btn-info btn-attributes"
                        data-toggle="modal"
                        data-target="#attribute_form_<?= $resourceType->getId() ?>"
                        data-attribute-form-id="attribute_form_<?= $resourceType->getId() ?>">
                    <?php _e('Edit Attributes', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?>
                </button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php foreach ($block->getResourceTypes() as $resourceType): ?>

    <div id="attribute_form_<?= $resourceType->getId() ?>" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= $resourceType->getLabel(); ?></h5>
                    <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="<?php _e('Close', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    echo do_shortcode(
                        sprintf(
                            '[micro_erp_resource_admin_form_create_resource_type_attribute resource_type_id="%s"]',
                            $resourceType->getId()
                        )
                    );
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">
                        <?php _e('Close', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

<?php endforeach; ?>

<?php $block->renderPaginationBlock(); ?>


<script>
    jQuery(document).ready(function ($) {
        let updateButton = $('.btn-update'),
            editAttributesButton = $('.btn-attributes');

        updateButton.click(function () {
            let button = $(this),
                id,
                payload = {};

            updateButton.prop("disabled", true);
            editAttributesButton.prop("disabled", true);

            id = button.data('id');
            payload.action = button.data('action');
            payload.resourceTypeId = id;
            payload.label = $('#label_' + id).val();
            payload.is_disabled = $('#is_disabled_' + id).is(':checked') ? 'on' : null;

            $.ajax({
                type: 'POST',
                url: wp_ajax_action.action_url,
                data: payload,
                success: function (data) {
                    updateButton.prop("disabled", false);
                    editAttributesButton.prop("disabled", false);
                },
                error: function () {
                    updateButton.prop("disabled", false);
                    editAttributesButton.prop("disabled", false);
                }
            });
        });
    });
</script>
