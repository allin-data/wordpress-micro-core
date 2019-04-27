<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

/** @var \AllInData\MicroErp\Resource\Block\Admin\FormEditResourceTypeAttributes $block */

$templateRow = '<tr>' .
    '<th scope="row">%1$s</th>' .
    '<td>%2$s</td>' .
    '<td>%3$s</td>' .
    '<td>%4$s</td>' .
    '<td><span class="badge badge-secondary">'.__('New', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN).'</span></td>' .
    '</tr>';

?>
<div id="resource_attribute_<?= $block->getResourceType()->getId() ?>">
    <h2><?php _e('Manage Fields', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?></h2>
    <table id="attribute_table_<?= $block->getResourceType()->getId() ?>" class="table">
        <thead>
        <tr>
            <th scope="col"><?php _e('Field ID', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?></th>
            <th scope="col"><?php _e('Name', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
            <th scope="col"><?php _e('Type', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
            <th scope="col"><?php _e('Resource Type', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
            <th scope="col"><?php _e('Actions', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($block->getResourceTypeAttributes() as $attribute): ?>
            <tr id="attribute_table_row_<?= $attribute->getId(); ?>">
                <th scope="row"><?= $attribute->getId(); ?></th>
                <td><input type="text" class="form-control" name="name" value="<?= $attribute->getName(); ?>"/></td>
                <td>
                    <?php foreach ($block->getAttributeTypes() as $attributeType): ?>
                        <select class="custom-select" name="type">
                            <option value="<?= $attributeType->getType() ?>"
                                    <?php if ($attribute->getType() == $attributeType->getType()): ?>selected<?php endif; ?>>
                                <?= $attributeType->getTypeLabel() ?>
                            </option>
                        </select>
                    <?php endforeach; ?>
                </td>
                <td>
                    <?= $block->getResourceType()->getLabel() ?>
                </td>
                <td>
                    <button name="attribute_update"
                            data-id="<?= $attribute->getId(); ?>"
                            data-action="<?= $block->getUpdateActionSlug(); ?>"
                            class="btn btn-info btn-attribute-update">
                        <?php _e('Update', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?>
                    </button>
                    <button name="attribute_delete"
                            data-id="<?= $attribute->getId(); ?>"
                            data-action="<?= $block->getDeleteActionSlug(); ?>"
                            class="btn btn-danger btn-attribute-delete">
                        <?php _e('Delete', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="row" id="attribute_create_form_<?= $block->getResourceType()->getId() ?>">
        <div class="col">
            <h3>
                <?php _e('Create Field', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?>
            </h3>
            <div class="input-group form-group">
                <label for="name"><?php _e('Name', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?></label>
                <input type="text" class="form-control" name="name"/>
            </div>
            <div class="input-group form-group">
                <label for="type"><?php _e('Type', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?></label>
                <?php $isFirst = true; foreach ($block->getAttributeTypes() as $attributeType): ?>
                    <select class="custom-select" name="type">
                        <option value="<?= $attributeType->getType() ?>" <?php if($isFirst): ?>selected<?php $isFirst = false; endif;?>>
                            <?= $attributeType->getTypeLabel() ?>
                        </option>
                    </select>
                <?php endforeach; ?>
            </div>
            <button type="button"
                    class="btn btn-primary btn-attribute-create-form-save"
                    data-resource-type-id="<?= $block->getResourceType()->getId(); ?>"
                    data-action="<?= $block->getCreateActionSlug(); ?>">
                <?php _e('Save', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?>
            </button>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function ($) {
        $('#resource_attribute_<?= $block->getResourceType()->getId() ?>').microErpResourceAttributeType({
            createButtonSelector: '#resource_attribute_<?= $block->getResourceType()->getId() ?> .btn-attribute-create-form-save',
            updateButtonSelector: '#resource_attribute_<?= $block->getResourceType()->getId() ?> .btn-attribute-update',
            deleteButtonSelector: '#resource_attribute_<?= $block->getResourceType()->getId() ?> .btn-attribute-delete',
            createTemplateRow: '<?= $templateRow ?>',
            resourceTypeLabel: '<?= $block->getResourceType()->getLabel() ?>'
        });
    });
</script>