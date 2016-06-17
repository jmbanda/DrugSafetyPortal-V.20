define(function(require, exports) {

    var Class               = require('class'),
        fb                  = require('pgui.advanced_filter'),
        localizer           = require('pgui.localizer'),
        events              = require('microevent'),
        overlay             = require('pgui.overlay'),
        async               = require('async'),
        _                   = require('underscore'),
        MultipleSorting     = require('multiple_sorting').MultipleSorting,
        Sorter              = require('sorter').Sorter,
        shortcuts           = require('pgui.shortcuts'),
        utils               = require('pgui.utils'),
        showFieldEmbeddedVideo = require('pgui.field-embedded-video'),
        autoHideMessage     = require('pgui.autohide-message');

    var DetailPanel = Class.extend({
        init: function(container, detailsInfo) {
            this.container = container;
            this.detailsInfo = detailsInfo;
            this.container.data('DetailPanel-class', this);
            this.$loadingPanel = $('<div class="detail-loading">')
                .hide()
                .appendTo(this.container)
        },

        showLoadingPanel: function(callback) {
            this.$loadingPanel.show();
            callback();
        },

        hideLoadingPanel: function(callback) {
            callback = callback || function() { };
            this.$loadingPanel.hide();
            callback();
        },

        showDetailsPanel: function(callback) {
            callback = callback || function() { };
            this.$list.show();
            this.$content.show();
            callback();
        },

        hideDetails: function() {
            this.container.hide();
        },

        showDetails: function() {
            this.container.show();
        },

        loadDetails: function(callback) {
            async.forEach(this.$content.find('.tab-pane').get(),
                function(detailContent, callback) {
                    $.get($(detailContent).attr('url'),
                        function(data) {
                            $(detailContent).append(data);
                            callback();
                        },
                        'html'
                    ).error(function(jqXHR, textStatus) { callback(textStatus); });
                },
                callback
            );
        },

        constructPanel: function(callback) {
            var self = this;
            this.$list = $('<ul>')
                .hide()
                .addClass('nav nav-tabs')
                .appendTo(this.container);
            this.$content = $('<div>')
                .hide()
                .addClass('tab-content')
                .appendTo(this.container);

            async.forEach(this.detailsInfo, function(detailInfo, callback) {
                    async.series([
                        function(callback) {
                            callback();
                        },
                        function(callback) {
                            var $item = $('<li>')
                                .appendTo(self.$list);
                            var $link = $('<a>')
                                .attr('href', '#' + detailInfo.detailId)
                                .html(detailInfo.caption)
                                .appendTo($item);

                            var $detailContent = $('<div class="tab-pane">')
                                .attr('id', detailInfo.detailId)
                                .attr('url', detailInfo.gridLink)
                                .appendTo(self.$content);

                            $link.tab();

                            $link.click(function(e) {
                                e.preventDefault();
                                $(this).tab('show');
                            });
                            callback();
                        }
                    ], callback);
                },
                function() {
                    self.$list.find('a').first().tab('show');
                    callback();
                });
        }
    });

    var smParseJSON = function(jsonText) {

        if (!JSON) {
            return eval('(' + jsonText + ')');
        }
        else {
            return JSON.parse(jsonText);
        }

    };

    exports.Grid = Class.extend({

        /**
         * @param {jQuery} container $(table#<table_name>Grid) See grid.tpl, Grid::GetId
         * @param options
         */
        init: function(container, options) {
            var self = this;
            this.container = container;
            this.container.data('grid-class', this);

            var inlineEditJson = smParseJSON(this.container.attr('data-inline-edit'));
            this.options = _.defaults(options ? options : {}, {
                inlineEdit: inlineEditJson.enabled,
                inlineEditRequestsAddress: inlineEditJson.request
            });

            this.$filterBuilderModal = this.container.find('.js-filter-builder-modal');
            this.$filterBuilderButton = this.container.find('.js-filter-builder-open');
            this.$resetFilterButton = this.container.find('.js-reset-filter');
            this.$toggleFilterButton = this.container.find('.js-toggle-filter');
            this.$disableFilterCheckbox = this.$filterBuilderModal.find('.js-filter-builder-disable');
            this.$quickFilterInput = this.container.find('.js-quick-filter-text');
            this.$quickFilterGoButton = this.container.find('.quick-filter-go');
            this.$modalInsertLink = this.container.find('[data-modal-insert=true]').first();
            this.$quickFilterResetButton = this.container.find('.quick-filter-reset');
            this.$headerRow = this.container.find('thead > tr.header');
            this.$deleteSelectedButton = this.container.find('.js-delete-selected');

            this.$selectAllCheckBox = this.$headerRow.find('.row-selection input[type=checkbox]');
            this.$rowCheckBoxes = this.container.find('.pg-row .row-selection input[type=checkbox]');

            showFieldEmbeddedVideo(this.container);

            this.currentFilter = new fb.FilterGroup();
            this.lastFilterBuilderJson = null;
            this.filterBuilderModalIsCommitted = null;
            this._clearFilterBuilderCommitted();
            this.configureFilterBuilderCallback = function() { };

            this._bindHandlers();
            this.hiddenValues = {};
            this._processHiddenValues();

            this._initContainer();
            this.integrateRows(this.container.find('.pg-row'));
            this._recalculateRowNumbers();
            this._highLightQuickFilterValue();

            if (!this.container.data('is-master')) {
                var sortableColumns = this.container.data('sortable-columns');
                var sorter = new Sorter(sortableColumns);

                var $sortDialog = $('#multiple-sorting-' + this.container.attr('id'));
                new MultipleSorting(sorter, $sortDialog);
            }

            autoHideMessage(this.container.find('.alert').first());
        },

        _highLightQuickFilterValue: function() {
            var self = this;

            var quickFilterValue = this.container.data('grid-quick-filter-value');
            if (quickFilterValue && quickFilterValue != '') {
                var fields = this.container.data('quickfilter-fields');
                require(['pgui.text_highlight'], function(textHighlight) {
                    textHighlight.HighlightTextInAllGrid(
                        self.container.find($.map(fields, function (f) {
                            return '[data-column-name="'+f+'"]';
                        }).join(',')),
                        quickFilterValue,
                        'ALL'
                    );

                });
            }
        },

        removeRow: function($row) {
            var nextRow = $row.nextAll('.pg-row').first();
            if (nextRow.is('.detail')) {
                nextRow.remove();
            }
            $row.remove();
            this.updateEmptyGridMessage();
        },

        _processHiddenValues: function() {
            var self = this;
            var hiddenValuesJson = JSON.parse(this.container.attr('data-grid-hidden-values'));
            _.each(hiddenValuesJson, function(value, name) {
                self.addHiddenValue(name, value);
            });
        },

        updateEmptyGridMessage: function() {
            var rows = this.container.find('.pg-row');
            if (rows.length == 0) {
                this.container.find('tr.empty-grid').removeClass('hide');
            }
            else {
                this.container.find('tr.empty-grid').addClass('hide');
            }
        },

        _padNumber: function (number, length) {
            var str = '' + number;
            while (str.length < length) {
                str = '0' + str;
            }
            return str;
        },

        _recalculateRowNumbers: function() {
            var self = this;
            var startNumber = parseInt(this.container.data('start-line-number'));
            var padCount = 0;
            var lineNumberCells = this.container.find('tbody > tr:not(.js-new-record-row) > td.line-number');

            var maxNumber = startNumber + lineNumberCells.length;
            padCount = maxNumber.toString().length;

            lineNumberCells.each(function(index){
                $(this).html(self._padNumber(index + startNumber, padCount) );
            });
        },

        insertRowAtBegin: function($row) {
            var row = this.container.find('.pg-row-list:first > .pg-row').first();
            var emptyGrid = this.container.find('.pg-row-list:first .empty-grid').closest('tr');
            if (row.length == 0) {
                row = emptyGrid;
            }
            row.before($row);
            emptyGrid.remove();
            this.integrateRows($row);
        },

        /**
         * @param {jQuery} $rows .pg-row
         */
        integrateRows: function($rows) {
            var self = this;

            // See Renderer::RenderImageViewColumn
            require(['jquery.magnific-popup'], function() {
                $rows.find('a.gallery-item').magnificPopup({
                    type: 'image',
                    gallery:{
                        enabled: true,
                        preload: [0,1]
                    },
                    image:{
                        titleSrc: function(item) {
                            return item.el.attr('title');
                        }
                    }
                });
            });

            $rows.find('[data-modal-operation=edit]').each(function (index, item) {
                var $item = $(item);
                if (!$item.data('modal-edit')) {
                    require(['pgui.modal_edit'], function (modalEdit) {
                        var modalEditLink = new modalEdit.ModalEditLink($item, self);
                        $item.data('modal-edit', modalEditLink);
                    });
                }
            });

            var modalDeleteLinks = $rows.find('[data-modal-operation=delete]');
            if (modalDeleteLinks.length > 0) {
                require(['pgui.modal_editing'], function(m) {
                    m.setupModalEditors($rows, self);
                });
            }

            $rows.find('[data-modal-operation=copy]').each(function (index, item) {
                var $item = $(item);
                if (!$item.data('modal-copy')) {
                    require(['pgui.modal_copy'], function (modalCopy) {
                        var modalCopyLink = new modalCopy.ModalCopyLink($item, self);
                        $item.data('modal-copy', modalCopyLink);
                    });
                }
            });

            $rows.find('[data-modal-operation=view]').each(function (index, item) {
                var $item = $(item);
                if (!$item.data('modal-view')) {
                    require(['pgui.modal_view'], function (modalView) {
                        var modalViewLink = new modalView.ModalViewLink($item, self);
                        $item.data('modal-view', modalViewLink);
                    });
                }
            });

            $rows.find(".js-expand-details").off('click').click(function (e) {
                e.preventDefault();
                self._toggleDetailClickHandler($(this));
            });

            utils.updatePopupHints($rows);
        },

        _initContainer: function() {
            var self = this;

            if (!this.$modalInsertLink.data('modal-insert-object')) {
                require(['pgui.modal_insert'], function (modalInsert) {
                    var modalInsertLink = new modalInsert.ModalInsertLink(self.$modalInsertLink, self);
                    self.$modalInsertLink.data('modal-insert-object', modalInsertLink);
                });
            }

            this.container.find('.js-expand-all-details').off('click').click(function(e) {
                e.preventDefault();
                self._toggleAllDetails($(this));
            });

            if (this.options.inlineEdit) {
                require(['pgui.inline_grid_edit'], function() {
                    self.container.sm_inline_grid_edit({
                        cancelButtonHint: localizer.getString('Cancel'),
                        commitButtonHint: localizer.getString('Commit'),
                        requestAddress: self.options.inlineEditRequestsAddress,
                        useBlockGUI: true,
                        useImagesForActions: true,
                        editingErrorMessageHeader: localizer.getString('ErrorsDuringUpdateProcess'),
                        grid: self
                    });
                });
            }
        },

        _toggleAllDetails: function(button) {
            var self = this;
            if (button.hasClass('collapsed')) {
                this.container.find('.pg-row-list:first > .pg-row').each(function () {
                    self._expandDetail($(this));
                });
            }
            else {
                this.container.find('.pg-row-list:first > .pg-row').each(function () {
                    self._collapseDetails($(this));
                });
            }
        },

        expandDetails: function($row) {
            this._expandDetail($row);
        },

        addHiddenValue: function(name, value) {
            this.hiddenValues[name] = value;
        },

        /**
         * See DeleteSelectedGridState::ProcessMessages
         */
        deleteSelectRows: function() {
            var rowsToDelete = this.container
                .find('.pg-row')
                .filter(function() {
                    return $(this).find('.row-selection input[type=checkbox]').prop('checked') ? true : false;
                });

            var $form = $('<form>')
                .addClass('hide')
                .attr('method', 'post')
                .attr('action', this.getDeleteSelectedAction())
                .append($('<input name="operation" value="delsel">'))
                .append(
                    $('<input name="recordCount">')
                        .attr('value', this.container.find('.pg-row').length))
                .appendTo($('body'));

            rowsToDelete.each(function() {
                $(this).find('.row-selection input').clone().appendTo($form);
            });

            $form.submit();
        },

        getDeleteSelectedAction: function() {
            return this.container.attr('data-delete-selected-action');
        },

        _bindHandlers: function() {
            var self = this;

            self.$headerRow.find('>th').each(function() {
                if ($(this).attr('data-comment')) {
                    $(this).popover({
                        placement: 'top',
                        container: 'body',
                        trigger: 'hover',
                        title: $(this).attr('data-field-caption'),
                        content: $(this).attr('data-comment')
                    });
                }
            });

            this.$deleteSelectedButton.click(function() {
                require(['bootbox.min'], function() {
                    bootbox.confirm(
                        localizer.getString('DeleteSelectedRecordsQuestion'),
                        function(confirmed) {
                            if (confirmed) {
                                self.deleteSelectRows();
                            }
                        }
                    );

                });
            });

            this.$selectAllCheckBox.change(function() {
                if ($(this).prop('checked')) {
                    utils.setCheckBoxStateOn(self.$rowCheckBoxes);
                }
                else {
                    utils.setCheckBoxStateOff(self.$rowCheckBoxes);
                }
            });

            this.$rowCheckBoxes.change(function() {
                var $checkedControls =  self.$rowCheckBoxes.filter(":checked");
                var $uncheckedControls = self.$rowCheckBoxes.filter(":not(:checked)");

                if ($checkedControls.length === 0) {
                    utils.setCheckBoxStateOff(self.$selectAllCheckBox);
                }
                else if ($uncheckedControls.length === 0) {
                    utils.setCheckBoxStateOn(self.$selectAllCheckBox);
                }
                else {
                    utils.setCheckBoxStateIndeterminate(self.$selectAllCheckBox);
                }
            });

            this.$quickFilterResetButton.click(function(e) {
                e.preventDefault();
                self.$quickFilterInput.val('');
                self._resetQuickFilter();
            });

            this.$quickFilterInput.keyup(function(e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    self._quickFilterGoButtonClickHandler();
                }
            });

            this.$quickFilterGoButton.click(function(e) {
                e.preventDefault();
                self._quickFilterGoButtonClickHandler();
            });
            this.$resetFilterButton.click(function(e) {
                e.preventDefault();
                self._resetFilterClickHandler(e);
            });
            this.$toggleFilterButton.click(this._toggleFilterClickHandler);

            this.$filterBuilderButton
                .on('click', function() {
                    self.$filterBuilderModal.modal('show');
                    return false;
                });

            this.$filterBuilderModal
                .on('show.bs.modal', function() {
                    self._openFilterBuilderHandler();
                })
                .on('shown.bs.modal', function() {
                    shortcuts.push(['filter_builder']);
                })
                .on('click', '.js-filter-builder-commit', function(e) {
                    e.preventDefault();
                    self._commitFilterBuilderHandler();
                })
                .on('hidden.bs.modal', function(e) {
                    self._hideFilterBuilderHandler();
                    shortcuts.pop();
                })
            ;
        },

        _getColumnCount: function() {
            var result = 0;
            this.$headerRow.children('th').each(function(){
                result += $(this).attr('colspan') ? parseInt($(this).attr('colspan')) : 1;
            });
            return result;
        },

        _expandDetail: function($row) {
            var button = $row.find('.js-expand-details');

            if (!button.hasClass('collapsed')) {
                return;
            }
            button.removeClass('collapsed');
            button.addClass('expanded');
            this._updateToggleAllDetailsButton();
            if (button.data('DetailsPanel-class')) {
                button.data('DetailsPanel-class').showDetails();
                return;
            }


            var $detailsRow = $('<tr>')
                .addClass('detail');
            var $detailsCell = $('<td>')
                .appendTo($detailsRow);
            $row.after($detailsRow);
            $detailsCell.attr('colspan', this._getColumnCount());
            var detailsInfo = eval(button.attr('data-info'));
            var detailsPanel = new DetailPanel($detailsCell, detailsInfo);
            button.data('DetailsPanel-class', detailsPanel);

            async.series([
                function(callback) {
                    detailsPanel.showLoadingPanel(callback);
                },
                function(callback) {
                    detailsPanel.constructPanel(callback);
                },
                function(callback) {
                    detailsPanel.loadDetails(callback);
                },
                function(callback) {
                    detailsPanel.showDetailsPanel(callback);
                }
            ],
                function(err) {
                    detailsPanel.hideLoadingPanel();
                });
        },

        _collapseDetails: function($row) {
            var button = $row.find('.js-expand-details');

            if (button.hasClass('collapsed')) {
                return;
            }

            if (button.data('DetailsPanel-class')) {
                button.addClass('collapsed');
                button.removeClass('expanded');
                button.data('DetailsPanel-class').hideDetails();
                this._updateToggleAllDetailsButton();
            }
        },

        _updateToggleAllDetailsButton: function() {
            var expandAllButton = this.container.find('.js-expand-all-details:first');

            if (this.container.find('.pg-row-list:first > .pg-row .js-expand-details.collapsed').length == 0) {
                expandAllButton.removeClass('collapsed');
                expandAllButton.addClass('expanded');
            }
            if (this.container.find('.pg-row-list:first > .pg-row .js-expand-details.expanded').length == 0) {
                expandAllButton.removeClass('expanded');
                expandAllButton.addClass('collapsed');
            }

        },

        _toggleDetailClickHandler: function(button) {
            if (button.hasClass('collapsed')) {
                this._expandDetail(button.closest('.pg-row'));
            }
            else {
                this._collapseDetails(button.closest('.pg-row'));
            }
        },

        _quickFilterGoButtonClickHandler: function() {
            var text = this.$quickFilterInput.val();
            this._postQuickFilter(text);
        },

        _resetQuickFilter: function() {
            var $form = $('<form>')
                .attr('method', 'GET')
                .appendTo($('body'));
            $form.append(
                $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'quick-filter-reset')
            );
            for(var valueName in this.hiddenValues) {
                if (this.hiddenValues.hasOwnProperty(valueName)) {
                    $form.append(
                        $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', valueName)
                            .val(this.hiddenValues[valueName])
                    );
                }
            }
            $form.submit();
        },

        _postQuickFilter: function(filterText) {
            var $form = $('<form>')
                .attr('method', 'GET');
            $form.append(
                    $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'quick-filter')
                        .val(filterText)
                    )
                .appendTo($('body'));
            for(var valueName in this.hiddenValues) {
                if (this.hiddenValues.hasOwnProperty(valueName)) {
                    $form.append(
                            $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', valueName)
                            .val(this.hiddenValues[valueName])
                        );
                }
            }
            $form.submit();
        },

        _postFilter: function(filter, isEnabled) {
            overlay.showOverlay('', localizer.getString('Loading') + '...');
            var $form = $('<form>')
                .attr('method', 'POST')
                .attr('action', window.location.href)
                .appendTo($('body'));
            $form
                .append(
                    $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'filter_json')
                        .val(filter.asJson())
                )
                .append(
                    $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'filter_json_enabled')
                        .val(isEnabled)
                );
            $form.submit();
        },

        _resetFilterClickHandler: function(event) {
            var self = this;
            var emptyFilter = new fb.FilterGroup();
            self._postFilter(emptyFilter);
        },

        _toggleFilterClickHandler: function (event) {
            event.preventDefault();
            var nextValue = $(this).data('enabled') ? 0 : 1;
            overlay.showOverlay('', localizer.getString('Loading') + '...');
            var $form = $('<form>')
                .attr('method', 'POST')
                .attr('action', window.location.href)
                .appendTo($('body'));
            $form
                .append(
                    $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'filter_json_enabled')
                        .val(nextValue)
                );
            $form.submit();
        },

        setFilter: function(filter) {
            this.currentFilter = filter;

            var $el = $('<form/>');
            $('.js-filter-builder-container').html($el);
            var filterBuilder = new fb.FilterBuilder($el, this.currentFilter);
            this.configureFilterBuilderCallback(filterBuilder);
            filterBuilder.activate();

            if (filterBuilder.root.isEmpty()) {
                this.$disableFilterCheckbox.prop('checked', false);
                $el.find('.js-group-action-select[data-action="add-condition"]:first').trigger('click');
            }
        },

        onConfigureFilterBuilder: function(callback) {
            this.configureFilterBuilderCallback = callback;
        },

        _isFilterBuilderCommitted: function() {
            return this.filterBuilderModalIsCommitted;
        },

        _setFilterBuilderIsCommitted: function() {
            this.filterBuilderModalIsCommitted = true;
        },

        _clearFilterBuilderCommitted: function() {
            this.filterBuilderModalIsCommitted = false;
        },

        _openFilterBuilderHandler: function() {
            this.lastFilterBuilderJson = this.currentFilter.asJson();
        },

        _commitFilterBuilderHandler: function() {
            this._setFilterBuilderIsCommitted();
            this.$filterBuilderModal.modal('hide');
            this.updateFilterBuilderStringStatus();

            var isEnabled = this.$disableFilterCheckbox.is(':checked') ? 0 : 1;

            this._postFilter(this.currentFilter, isEnabled);
        },

        _hideFilterBuilderHandler: function() {
            if (this._isFilterBuilderCommitted()) {
                this._clearFilterBuilderCommitted();
            } else {
                this._rollbackFilterBuilder(this.lastFilterBuilderJson);
            }
        },

        _rollbackFilterBuilder: function(lastFilterJson) {
            var activeFilter = new fb.FilterGroup();
            activeFilter.fromJson(JSON.parse(lastFilterJson));
            this.setFilter(activeFilter);
        },

        updateFilterBuilderStringStatus: function() {
            //$('.js-filter-builder-status-string').html(this.currentFilter.asString());
        },

        showMessage: function (message, displayTime) {
            displayTime = displayTime || 0;
            var $messageContainer = this.container.find('.js-grid-message-container');
            $messageTemplate = $($messageContainer.data('template'));
            $messageTemplate.find('.js-content').html(message);
            $messageContainer.html($messageTemplate);
            autoHideMessage($messageTemplate, displayTime);
        }

    });
});