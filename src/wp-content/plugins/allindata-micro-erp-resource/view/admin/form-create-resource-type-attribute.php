<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

/** @var \AllInData\MicroErp\Resource\Block\Admin\FormEditResourceTypeAttributes $block */

$resourceTypeAttributes = $block->getResourceTypeAttributes();
$sortOrderMap = [];
foreach ($resourceTypeAttributes as $attribute) {
    $sortOrderMap[$attribute->getId()] = $attribute->getSortOrder();
}

$templateRow = '<tr>' .
    '<th scope="row">{{id}}</th>' .
    '<td>{{name}}</td>' .
    '<td>{{type}}</td>' .
    '<td>{{resourceTypeLabel}}</td>' .
    '<td><span class="badge badge-secondary">'.__('New', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN).'</span></td>' .
    '</tr>';

?>
<div id="resource_attribute_<?= $block->getResourceType()->getId() ?>" class="resource-type-attribute">
    <h2><?php _e('Manage Fields', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?></h2>
    <table id="attribute_table_<?= $block->getResourceType()->getId() ?>" class="table sortable">
        <thead>
        <tr>
            <th scope="col"><?php _e('Field ID', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?></th>
            <th scope="col"><?php _e('Name', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
            <th scope="col"><?php _e('Type', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
            <th scope="col"><?php _e('Shown in Grid?', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
            <th scope="col"><?php _e('Resource Type', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
            <th scope="col"><?php _e('Actions', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN) ?></th>
            <th scope="col"><?php _e('Order', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($resourceTypeAttributes as $attribute): ?>
            <tr id="attribute_table_row_<?= $attribute->getId(); ?>" data-item-selector="attribute_table_row_<?= $attribute->getId(); ?>">
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
                    <input name="is_shown_in_grid"
                           type="checkbox"
                           class="form-control checkboxchecker"
                           value="on" <?php if ($attribute->getIsShownInGrid()): ?>checked<?php endif; ?>/>
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
                <td>
                    <input type="hidden" class="form-control" name="sort_order" value="<?= $attribute->getSortOrder(); ?>"/>
                    <button class="btn btn-info btn-sortable-order btn-sortable-order-up"
                            data-attribute-id="<?= $attribute->getId(); ?>"
                            data-order-value="<?= $attribute->getSortOrder(); ?>"
                            data-item-selector="#attribute_table_row_<?= $attribute->getId() ?>"
                            data-item-container="#attribute_table_<?= $block->getResourceType()->getId() ?> tr">
                        <i class="fa fa-arrow-up"></i>
                    </button>
                    <button class="btn btn-info btn-sortable-order btn-sortable-order-down"
                            data-attribute-id="<?= $attribute->getId(); ?>"
                            data-order-value="<?= $attribute->getSortOrder(); ?>"
                            data-item-selector="#attribute_table_row_<?= $attribute->getId() ?>"
                            data-item-container="#attribute_table_<?= $block->getResourceType()->getId() ?> tr">
                        <i class="fa fa-arrow-down"></i>
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
            <div class="input-group form-group">
                <label for="is_shown_in_grid"><?php _e('Is shown in grid?', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN); ?></label>
                <input name="is_shown_in_grid"
                       type="checkbox"
                       class="form-control checkboxchecker"
                       value="on" />
            </div>
            <input type="hidden" class="form-control" name="sort_order" value="999"/>
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

        /*
         * Sortable
         */
        $('#resource_attribute_<?= $block->getResourceType()->getId() ?>').microErpResourceSortable({
            itemUpSelector: '#resource_attribute_<?= $block->getResourceType()->getId() ?> .btn-sortable-order-up',
            itemUpOnClickCallback: function (item, config) {
                $('#resource_attribute_<?= $block->getResourceType()->getId() ?> .btn-sortable-order-up').prop('disabled', true);
                $('#resource_attribute_<?= $block->getResourceType()->getId() ?> .btn-sortable-order-down').prop('disabled', true);
            },
            itemUpAfterSortCallback: function (item, itemContainerSelectorSet, config) {
                itemContainerSelectorSet.forEach(function (itemSelector) {
                    $(itemSelector + ' button[name="attribute_update"]').trigger('click');
                });
                $('#resource_attribute_<?= $block->getResourceType()->getId() ?> .btn-sortable-order-up').prop('disabled', false);
                $('#resource_attribute_<?= $block->getResourceType()->getId() ?> .btn-sortable-order-down').prop('disabled', false);
            },
            itemDownSelector: '#resource_attribute_<?= $block->getResourceType()->getId() ?> .btn-sortable-order-down',
            itemDownOnClickCallback: function (item, config) {
                $('#resource_attribute_<?= $block->getResourceType()->getId() ?> .btn-sortable-order-up').prop('disabled', true);
                $('#resource_attribute_<?= $block->getResourceType()->getId() ?> .btn-sortable-order-down').prop('disabled', true);
            },
            itemDownAfterSortCallback: function (item, itemContainerSelectorSet, config) {
                itemContainerSelectorSet.forEach(function (itemSelector) {
                    $(itemSelector + ' button[name="attribute_update"]').trigger('click');
                });
                $('#resource_attribute_<?= $block->getResourceType()->getId() ?> .btn-sortable-order-up').prop('disabled', false);
                $('#resource_attribute_<?= $block->getResourceType()->getId() ?> .btn-sortable-order-down').prop('disabled', false);
            },
            sortOrderMap: <?= json_encode($sortOrderMap) ?>
        });
    });
</script>