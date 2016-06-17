define(function(require, exports, module) {

    var Class = require('class'),
        _ = require('underscore');

    require('bootstrap');

    var PageControl = Class.extend({

        init: function($context) {

            this.container = $context;

            this._initializeElements();
            this._initializeParameters();
            this._initializeEvents();

            this._selectDefaults();
        },

        _initializeElements: function() {
            this.$Dialog = this.container.find('.js-page-settings-dialog');
            this.$PageSizeContainer = this.container.find('.js-page-settings-page-size-container');
            this.$PageSizeControl = this.container.find('.js-page-settings-page-size-control');
            this.$PageSizeCustomContainer = this.container.find('.js-page-settings-custom-page-size-container');
            this.$PageSizeCustomControl = this.container.find('.js-page-settings-custom-page-size-control');
            this.$PageSizeCustomCounter = this.container.find('.js-page-settings-custom-page-size-pager');
            this.$PageSizeSave = this.container.find('.js-page-settings-save');
            this.$PageSizeCancel = this.container.find('.js-page-settings-cancel');
            this.$ViewModeControl = this.container.find('.js-page-settings-viewmode-control');
            this.$CardColumnCount = this.container.find('.js-page-settings-card-column-count');
        },

        _initializeParameters: function() {
            this.totalRecordCount = this.$Dialog.data('total-record-count');
            this.defaultRecordCountPerPage = parseInt(this.$Dialog.data('record-count-per-page'));
            this.pageSizeIsCustom = false;

            this.initValues = {
                viewMode: this.$ViewModeControl.val(),
                cardsCount: this.$CardColumnCount.val(),
                recordPerPage: this.defaultRecordCountPerPage
            };
        },

        _initializeEvents: function() {
            var self = this;

            this.$PageSizeControl.on('change', function() {
                if ($(this).val() == 'custom') {
                    self._selectCustomPageSize();
                }
            });

            this.$PageSizeCustomControl.on('keyup, change', function() {
                self._calcCustomCountPage($(this).val());
            });

            this.$PageSizeSave.on('click', function() {
                self._applyPageSettings();
            });

            this.$ViewModeControl.on('change', function() {
                self._changeModeHandler();
            });

            this.$PageSizeCancel.on('click', function () {
                self.$ViewModeControl.val(self.initValues.viewMode);
                self.$CardColumnCount.val(self.initValues.cardsCount);
                self._selectDefaults();
            });
        },

        _pageSizeValues: [],

        _getPageSizeValues: function() {
            if (this._pageSizeValues.length == 0) {
                var values = [];
                this.$PageSizeControl.find('option').each(function () {
                    values.push(parseInt($(this).val()));
                });
                this._pageSizeValues = values;
            }

            return this._pageSizeValues;
        },

        _selectDefaults: function() {
            this._changeModeHandler();

            if (this._isPageSizeVariable()) {
                var values = this._getPageSizeValues();

                if (values.indexOf(this.defaultRecordCountPerPage) === -1) {
                    this._selectCustomPageSize();
                    this._calcCustomCountPage(this.defaultRecordCountPerPage);
                } else {
                    this._selectPredefinedPageSize();
                    this.$PageSizeControl.val(this.defaultRecordCountPerPage);
                }
            }
        },

        _isPageSizeVariable: function() {
            return this.$PageSizeCustomControl.length > 0 && this.$PageSizeControl.length > 0;
        },

        _getPageCountForPageSize: function (pageSize, rowCount) {
            if (pageSize > 0) {
                return Math.floor(rowCount / pageSize) +
                    ((Math.floor(rowCount / pageSize) == (rowCount / pageSize)) ? 0 : 1);
            } else {
                return 1;
            }
        },

        _calcCustomCountPage: function(val) {
            this.$PageSizeCustomCounter.text(
                this._getPageCountForPageSize(val, this.totalRecordCount)
            );
        },

        _selectCustomPageSize: function() {
            this.$PageSizeContainer.hide();
            this.$PageSizeCustomContainer.show();
            this.pageSizeIsCustom = true;
            this.$PageSizeCustomControl.val(this.defaultRecordCountPerPage);
            this._calcCustomCountPage(this.defaultRecordCountPerPage);
        },

        _selectPredefinedPageSize: function() {
            this.$PageSizeContainer.show();
            this.$PageSizeCustomContainer.hide();
            this.pageSizeIsCustom = false;
        },

        _changeModeHandler: function() {
            var name = this.$ViewModeControl.find('option:selected').data('name');
            this.$CardColumnCount.prop('disabled', name != 'CardViewMode' );
        },

        _applyPageSettings: function() {
            var self = this;
            require(['jquery.query'], function() {
                var query = jQuery.query;
                query = query.set('viewmode', self.$ViewModeControl.val());
                query = query.set('cardcountinrow', self.$CardColumnCount.val());
                if (self._isPageSizeVariable()) {
                    query = query.set('recperpage', self.pageSizeIsCustom ? self.$PageSizeCustomControl.val() : self.$PageSizeControl.val());
                }
                window.location = query;
            });
        }

    });

    return function($context) {
        new PageControl($context);
    }

});