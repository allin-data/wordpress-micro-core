(function ($) {
    $.microErpResourceSortable = {

        defaults: {
            itemUpSelector: '.btn-sortable-order-up',
            itemUpOnClickCallback: function () {},
            itemUpAfterSortCallback: function () {},
            itemDownSelector: '.btn-sortable-order-down',
            itemDownClickCallback: function () {},
            itemDownAfterSortCallback: function () {},
            itemSortOrderSelector: 'input[name="sort_order"]',
            sortOrderMap: {}
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

            $(config.itemUpSelector).click(function () {
                let item = $(this);
                config.itemUpOnClickCallback.call(this, item, config);
                me._handleSortOrderUp(item, config, config.itemUpAfterSortCallback);
            });

            $(config.itemDownSelector).click(function () {
                let item = $(this);
                config.itemDownClickCallback.call(this, item, config);
                me._handleSortOrderDown(item, config, config.itemDownAfterSortCallback);
            });
        },

        /**
         * @param {Object} item
         * @param {Object} config
         * @param {Function} callback
         * @private
         */
        _handleSortOrderUp: function (item, config, callback) {
            let itemContainer = item.data('item-container'),
                itemContainerSelectorSet = [];

            this._reorderPrevious(item, config);

            $(itemContainer).each(function (index, itemSelector) {
                let item = $(itemSelector);
                itemContainerSelectorSet.push('#' + item.data('item-selector'));
            });

            callback.call(this, item, itemContainerSelectorSet, config);
        },

        /**
         * @param {Object} item
         * @param {Object} config
         * @param {Function} callback
         * @private
         */
        _handleSortOrderDown: function (item, config, callback) {
            let itemContainer = item.data('item-container'),
                itemContainerSelectorSet = [];

            this._reorderNext(item, config);

            $(itemContainer).each(function (index, itemSelector) {
                let item = $(itemSelector);
                itemContainerSelectorSet.push('#' + item.data('item-selector'));
            });

            callback.call(this, item, itemContainerSelectorSet, config);
        },

        /**
         * @param {Object} item
         * @param {Object} config
         * @private
         */
        _reorderPrevious: function (item, config) {
            let itemContainer = item.data('item-container'),
                itemElement = $(item.data('item-selector'));

            itemElement.insertBefore(itemElement.prev());
            this._resetOrder(itemContainer, config);
        },

        /**
         * @param {Object} item
         * @param {Object} config
         * @private
         */
        _reorderNext: function (item, config) {
            let itemContainer = item.data('item-container'),
                itemElement = $(item.data('item-selector'));

            itemElement.insertAfter(itemElement.next());
            this._resetOrder(itemContainer, config);
        },

        /**
         * @param {String} itemContainer
         * @param {Object} config
         * @private
         */
        _resetOrder: function (itemContainer, config) {
            let sortOrder = 0;
            $(itemContainer).each(function (index, itemSelector) {
                let item = $(itemSelector);
                $('#' + item.data('item-selector') + ' ' + config.itemSortOrderSelector).val(sortOrder++);
            });
        }
    };

    /**
     * Creates new instance
     * @param {Object} options
     * @returns {$.fn}
     */
    $.fn.microErpResourceSortable = function (options) {
        $.microErpResourceSortable.create(options);
        return this;
    };
}(jQuery));