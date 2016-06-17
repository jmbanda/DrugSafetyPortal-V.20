define(function (require, exports) {
    var Class = require('class');
    var events = require('microevent');
    var _ = require('underscore');

    exports.CustomEditor = Class.extend({
        /**
         * @param rootElement jQuery
         */
        init: function(rootElement)
        {
            this.rootElement = rootElement;
            this.fieldName = this.rootElement.attr('data-field-name');
        },

        doChanged: function()
        {
            this.trigger('onChangeEvent', this, 0);
        },

        getValue: function() { return null; },

        setValue: function(value) { },

        onChange: function(callback)
        {
            this.bind('onChangeEvent', callback);
        },

        finalizeEditor: function()
        { },

        getRootElement: function()
        {
            return this.rootElement;
        },

        getFieldName: function()
        {
            return this.fieldName;
        },

        isReadOnly: function()
        {
            return this.readonly();
        },

        getVisible: function() {
            return this.rootElement.closest('.form-group').is(':visible');
        },

        setVisible: function(value) {
            this.rootElement.closest('.form-group').toggle(value);
        },

        visible: function(value) {
            var controlContainer = this.rootElement.closest('.form-group');
            if (controlContainer.length === 0) {
                return;
            }
            if (_.isUndefined(value)) {
                return this.getVisible();
            }

            this.setVisible(value);
        },

        enabled: function(value) {
            if (_.isUndefined(value)) {
                return this.getEnabled();
            }

            if (this.getEnabled() != value) {
                this.setEnabled(value);
            }
        },

        getEnabled: function() {
            return true;
        },

        setEnabled: function(value) {
            // nothing here
        },

        readonly: function(value) {
            if (_.isUndefined(value))
            {
                return this.getReadonly();
            }
            else
            {
                if (this.getReadonly() != value)
                {
                    this.setReadonly(value);
                }
            }
        },

        getReadonly: function() {
            return false;
        },

        setReadonly: function(value) {
            // nothing here
        },

        updateState: function() {
            if (this.rootElement.attr('disabled')) {
                this.setEnabled(false);
            }
            if (this.rootElement.attr('readonly')) {
                this.setReadonly(true);
            }
        }
    });

    events.mixin(exports.CustomEditor);
});