(function ($) {
    $.microErpResourceAttributeType = {

        defaults: {
            createButtonSelector: '.btn-attribute-create-form-save',
            updateButtonSelector: '.btn-attribute-update',
            deleteButtonSelector: '.btn-attribute-delete',
            createTemplateRow: '',
            resourceTypeLabel: ''
        },

        /**
         * Constructor
         * @param {Object} options
         */
        create: function (options) {
            let me = this,
                config;

            // configuration
            config = $.extend({}, this.defaults, options || {});


            $(config.createButtonSelector).click(function () {
                let button = $(this);
                me._createAttribute(button, config);
            });
            $(config.updateButtonSelector).click(function () {
                let button = $(this);
                me._updateAttribute(button, config);
            });
            $(config.deleteButtonSelector).click(function () {
                let button = $(this);
                me._deleteAttribute(button, config);
            });
        },

        /**
         * @param {Object} config
         * @param {Boolean} disable
         * @private
         */
        _toggleButtons: function (config, disable = false) {
            $(config.createButtonSelector).prop("disabled", disable);
            $(config.updateButtonSelector).prop("disabled", disable);
            $(config.deleteButtonSelector).prop("disabled", disable);
        },

        /**
         * @param {Object} button
         * @param {Object} config
         * @private
         */
        _createAttribute: function (button, config) {
            let me = this,
                id,
                payload = {};

            me._toggleButtons(config, true);

            id = button.data('resource-type-id');
            payload.action = button.data('action');
            payload.resourceTypeId = id;
            payload.name = $('#attribute_create_form_' + id + ' input[name="name"]').val();
            payload.type = $('#attribute_create_form_' + id + ' select[name="type"]').val();

            $.ajax({
                type: 'POST',
                url: wp_ajax_action.action_url,
                data: payload,
                success: function (data) {
                    let templateRow;

                    templateRow = config.createTemplateRow;
                    templateRow.replace('%1$s', data.id);
                    templateRow.replace('%2$s', data.name);
                    templateRow.replace('%3$s', data.type); //@TODO mapping to label value
                    templateRow.replace('%4$s', config.resourceTypeLabel);

                    me._toggleButtons(config, false);

                    $('#attribute_table_' + id + ' tr:last').after(templateRow);
                },
                error: function () {
                    me._toggleButtons(config, false);
                }
            });
        },

        /**
         * @param {Object} button
         * @param {Object} config
         * @private
         */
        _updateAttribute: function (button, config) {
            let me = this,
                id,
                resourceTypeId,
                payload = {};

            me._toggleButtons(config, true);

            id = button.data('id');
            resourceTypeId = button.data('resource-type-id');
            payload.action = button.data('action');
            payload.resourceTypeAttributeId = id;
            payload.resourceTypeId = resourceTypeId;
            payload.name = $('#attribute_table_row_' + id + ' input[name="name"]').val();
            payload.type = $('#attribute_table_row_' + id + ' input[name="type"]').val();

            $.ajax({
                type: 'POST',
                url: wp_ajax_action.action_url,
                data: payload,
                success: function (data) {
                    me._toggleButtons(config, false);
                },
                error: function () {
                    me._toggleButtons(config, false);
                }
            });
        },

        /**
         * @param {Object} button
         * @param {Object} config
         * @private
         */
        _deleteAttribute: function (button, config) {
            let me = this,
                id,
                payload = {};

            me._toggleButtons(config, true);

            id = button.data('id');
            payload.action = button.data('action');
            payload.resourceTypeAttributeId = id;

            $.ajax({
                type: 'POST',
                url: wp_ajax_action.action_url,
                data: payload,
                success: function (data) {
                    me._toggleButtons(config, false);
                    $('#attribute_table_row_' + id).remove();
                },
                error: function () {
                    me._toggleButtons(config, false);
                }
            });
        }
    };

    /**
     * Creates new instance
     * @param {Object} options
     * @returns {$.fn}
     */
    $.fn.microErpResourceAttributeType = function (options) {
        $.microErpResourceAttributeType.create(options);
        return this;
    };
}(jQuery));