define(function (require, exports, module) {

    var Class = require('class'),
        _ = require('underscore'),
        dtp = require('pgui.datetimepicker'),
        select2Filter = require('pgui.select2_filter'),
        pgevents = require('pgui.events'),
        localizer = require('pgui.localizer');

    var FilterCondition = exports.FilterCondition = Class.extend({

        init:function (fieldName, fieldType, operator, values) {
            this.fieldName = fieldName;
            this.fieldType = fieldType;
            this.operator = operator;
            this.values = values || [];
            this.type = FilterItemType.Condition;
            this.displayValues = this.values;
        },

        setFieldName:function (value) {
            this.fieldName = value;
        },

        getValuesCount:function () {
            return OperatorValuesCount[this.operator];
        },

        getOperator:function () {
            return this.operator;
        },

        setOperator:function (value) {
            this.operator = value;
            this.values = this.values.slice(0, this.getValuesCount());
            this.displayValues = this.displayValues.slice(0, this.getValuesCount());
        },

        getValues:function () {
            return this.values;
        },

        setValues:function (values) {
            this.values = values;
        },

        setDisplayValues:function (values) {
            this.displayValues = values;
        },

        getDisplayValues:function (values) {
            return this.displayValues;
        },

        getFieldName:function () {
            return this.fieldName;
        },

        getOperatorAsString:function () {
            return getOperatorAsString(this.getOperator());
        },

        serialize:function () {
            return {
                type:FilterItemType.Condition,
                fieldName:this.getFieldName(),
                operator:this.getOperator(),
                values:this.getValues(),
                displayValues:this.getDisplayValues()
            };
        },

        findItemParent:function (item) {
            return null;
        },

        asString:function () {
            return this.getFieldName() + ' ' + this.getOperatorAsString() + ' ' + this.getValues().join(', ');
        },

        isEmpty:function () {
            return false;
        },

        deserialize:function (data) {
            this.type = FilterItemType.Condition;
            this.setFieldName(data.fieldName);
            this.setOperator(data.operator);
            this.setValues(data.values);
            this.setDisplayValues(data.displayValues);
        }
    });

    var FilterGroup = exports.FilterGroup = Class.extend({

        init:function () {
            this.type = FilterItemType.Group;
            this.items = [];
            this.operator = GroupOperator.And;
        },

        add:function (item) {
            this.items.push(item);
        },

        remove:function (item) {
            for (var i = 0; i < this.items.length; i++) {
                if (item === this.items[i]) {
                    this.items.splice(i, 1);
                }
            }
        },

        getOperatorAsString:function () {
            return getGroupOperatorAsString(this.getOperator());
        },

        getOperator:function () {
            return this.operator;
        },

        setOperator:function (value) {
            this.operator = value;
        },

        getItems:function () {
            return this.items;
        },

        getItem:function (index) {
            return this.items[index];
        },

        deserialize:function (data) {
            this.operator = data.operator;
            this.type = FilterItemType.Group;
            for (var i = 0; i < data.items.length; i++) {
                var item = null;
                if (data.items[i].type == FilterItemType.Condition) {
                    item = new FilterCondition();
                }
                else if (data.items[i].type == FilterItemType.Group) {
                    item = new FilterGroup();
                }
                item.deserialize(data.items[i]);
                this.add(item);
            }
        },

        serialize:function () {
            var items = [];
            for (var i = 0; i < this.getItems().length; i++) {
                var item = this.getItems()[i].serialize();
                if (item.type == FilterItemType.Condition || item.items.length > 0) {
                    items.push(item);
                }
            }

            return {
                type:     FilterItemType.Group,
                operator: this.getOperator(),
                items:    items
            };
        },

        findItemParent:function (item) {
            for (var i = 0; i < this.items.length; i++) {
                if (this.items[i] === item)
                    return this;
            }
            for (i = 0; i < this.items.length; i++) {
                var parentItem = this.items[i].findItemParent(item);
                if (parentItem)
                    return parentItem;
            }
            return null;
        },

        asString:function () {
            var result = '';
            var items = this.getItems();
            for (var i = 0; i < items.length; i++) {
                if (i > 0)
                    result += ' ' + this.getOperatorAsString() + ' ';
                if (items.length > 1)
                    result += '(';
                result += items[i].asString();
                if (items.length > 1)
                    result += ')';
            }
            return result;
        },

        isEmpty:function () {
            var items = this.getItems();
            for (var i = 0; i < items.length; i++)
                if (!items[i].isEmpty())
                    return false;
            return true;
        },

        asJson:function () {
            return JSON.stringify(this.serialize(), '\t', '  ');
        },

        fromJson:function (data) {
            return this.deserialize(data);
        }
    });

    var BaseEdit = Class.extend({

        init:function (container) {
            this.container = container;
            this.$editor = this._initializeEditor();
        },

        destroy:function () {
            this.$editor.remove();
        },

        _initializeEditor:function () {
            return $('<input/>');
        },

        setValue:function (value) {
            this.$editor.val(value);
        },

        getValue:function () {
            return this.$editor.val();
        },

        getDefaultValue:function () {
            return null;
        },

        getDisplayValue:function () {
            return this.$editor.val();
        },

        setDisplayValue:function (value) {
            this.$editor.val(value);
        },

        onChange:function (callback) {
            this.$editor.change(callback);
        }
    });

    exports.DateEdit = exports.DateTimeEdit = BaseEdit.extend({

        init:function (container, options) {
            var self = this;
            this.container = container;
            this.options = options;
            self.datePickerControl = null;
            self.$editor = this._initializeEditor();
        },

        _initializeEditor:function () {
            var $editor =
                $('<input>')
                    .attr('type', 'text')
                    .attr('data-calendar', 'true')
                    .data('vertical', 'bottom')
                    .appendTo(this.container);
            if (this.options && this.options.format)
                $editor.attr('data-picker-format', this.options.format);
            if (this.options && this.options.fdow)
                $editor.attr('data-picker-first-day-of-week', this.options.fdow);
            this.datePickerControl = new dtp.DateTimePicker($editor);

            return $editor;
        },

        getValue:function () {
            var moment = require('moment');
            return moment(this.$editor.val(), this.options.format).format('YYYY-MM-DD HH:mm:SS')
        },

        onChange:function (callback) {
            this.$editor.change(callback);
            this.datePickerControl.onChange(callback);
        }
    });

    exports.TextEdit = BaseEdit.extend({

        _initializeEditor:function () {
            return $('<input>')
                .attr('type', 'text')
                .appendTo(this.container);
        }

    });

    exports.BooleanEdit = BaseEdit.extend({

        _initializeEditor:function () {
            return $('<select />')
                .append('<option value="true">True</option>')
                .append('<option value="false">False</option>')
                .appendTo(this.container);
        },

        getDefaultValue:function () {
            return 'true';
        }

    });

    exports.Select2Filter = BaseEdit.extend({

        init:function (container, options) {
            this.container = container;
            this.options = options;
            this.$editor = this._initializeEditor();
        },

        destroy:function () {
            this.$editor.select2('destroy');
            this.$editor.remove();
        },

        _initializeEditor:function () {
            var $editor =
                $('<input>')
                    .attr('data-pg-select2filter-handler', this.options.handler)
                    .attr('type', 'text')
                    .appendTo(this.container);

            (new select2Filter.Select2Filter($editor));
            pgevents.setupInputEvents($editor);
            return $editor;
        },

        setValue:function (value) {
            this.$editor.val(value);
            this._setDataProp('id', value);
        },

        setDisplayValue:function (value) {
            this._setDataProp('text', value);
        },

        getDisplayValue:function () {
            var data = this.$editor.select2('data') || {id:'',text:''};
            return data.text;
        },

        _setDataProp: function (prop, value) {
            if (value === null) {
                return;
            }

            var data = this.$editor.select2('data') || {id:'',text:''};
            data[prop] = value;

            if (this.$editor.select2) {
                this.$editor.select2('data', {
                    id: data.id,
                    text: data.text
                });
            } else {
                this.$editor.val(data.id);
            }
        },

        onChange:function (callback) {
            this.$editor.change(callback);
            this.$editor.data('pg-events').onChange(callback);
        }
    });

    exports.FilterBuilder = Class.extend({

        init: function (container, filter) {
            this.container = container;
            this.root = filter || (new Filter());
            this.fields = {};
            this.fieldNames = [];

            var self = this;

            this.container
                .on('submit', function (e) {
                    e.preventDefault();
                })
                .on('click', '.js-group-operator-select', function(e) {
                    self._groupChangeOperationHandler($(this).closest('.js-group'), $(this).data('operator'));
                    $(this).blur().closest('.dropdown').find('.dropdown-toggle').dropdown('toggle');
                    return false;
                })
                .on('click', '.js-group-action-select', function(e) {
                    self._groupMenuActionClickHandler($(this).closest('.js-group'), $(this).data('action'));
                    $(this).blur();
                    return false;
                })
                .on('click', '.js-condition-remove', function() {
                    var filterItem = $(this).closest('.js-condition').data('filter-item');
                    self.removeItem(filterItem);
                    return false;
                })
                .on('change', '.js-condition-field-select', function() {
                    var $condition = $(this).closest('.js-condition');
                    var filterItem = $condition.data('filter-item');

                    var currentFieldEditorClass = self.fields[filterItem.getFieldName()].editorClass;
                    var newFieldName = $(this).val();
                    var newFieldEditorClass = self.fields[newFieldName].editorClass;
                    var isEditorClassChanged =
                        [currentFieldEditorClass, newFieldEditorClass].indexOf(exports.Select2Filter) != -1
                        || (currentFieldEditorClass != newFieldEditorClass);

                    filterItem.setFieldName(newFieldName);

                    self._updateOperators($condition, !isEditorClassChanged);
                    self._updateEditors($condition, !isEditorClassChanged);
                })
                .on('change', '.js-condition-operator-select', function() {
                    var $condition = $(this).closest('.js-condition');
                    var filterItem = $condition.data('filter-item');
                    filterItem.setOperator($(this).val());
                    self._updateEditors($condition, true);
                })
            ;
        },

        getFilter:function () {
            return this.root;
        },

        _updateEditors: function($condition, keepValues) {
            keepValues = keepValues || false;

            var self = this;
            var filterItem = $condition.data('filter-item');

            $.each($condition.data('editors'), function (key, editor) {
                editor.destroy();
            });

            var editors = this._createEditorsForField(
                filterItem.getValuesCount(),
                filterItem.getFieldName(),
                $condition.children('.js-value')
            );


            if (keepValues) {
                this._setEditorsValuesFromFilter(editors, filterItem);
            } else {
                var defaultValues = this._getEditorsDefaultValues(editors);
                filterItem.setValues(defaultValues);
                filterItem.setDisplayValues(defaultValues);
            }

            $condition.data('editors', editors);

            this._setEditorsOnChange(editors, function () {
                self._setFilterValuesFromEditors(filterItem, editors);
            });
        },

        _updateOperators: function($condition) {
            var filterItem = $condition.data('filter-item');
            var currentOperator = filterItem.getOperator();
            var availableOperators = this._getAvailableOperatorsForField(filterItem.getFieldName());

            if (availableOperators.indexOf(parseInt(currentOperator)) == -1) {
                filterItem.setOperator(availableOperators[0]);
            }

            this._fillConditionOperatorMenu($condition.find('.js-condition-operator-container'), filterItem);
        },

        _createEditorsForField:function (editorsCount, fieldName, container) {
            var editors = [];
            container.html('');
            container[editorsCount > 1 ? 'addClass' : 'removeClass']('filter-builder-condition-value-multiple');

            for (var i = 0; i < editorsCount; i++) {
                var editorContainer = $('<div />').appendTo(container);
                var editor = new this.fields[fieldName].editorClass(editorContainer, this.fields[fieldName].editorOptions);
                editor.$editor.addClass("form-control");
                editors.push(editor);
                if (i < editorsCount - 1) {
                    $('<div> ' + localizer.getString('And') + ' </div>')
                        .addClass('filter-builder-condition-value-divider')
                        .appendTo(container);
                };
            }

            return editors;
        },

        _addConditionItem: function (itemContainer, conditionItem) {
            var self = this;
            var template = _.template($('#filterBuilderConditionTemplate').html());
            var $el = $(template({
                field: this._getCaptionByFieldName(conditionItem.getFieldName()),
                operator: conditionItem.getOperatorAsString(),
                fieldList: this.fields
            }));

            var editors = this._createEditorsForField(
                conditionItem.getValuesCount(),
                conditionItem.getFieldName(),
                $el.find('.js-value')
            );

            this._setEditorsValuesFromFilter(editors, conditionItem);
            this._setEditorsOnChange(editors, function () {
                self._setFilterValuesFromEditors(conditionItem, editors);
            });

            $el.data({
                'filter-item': conditionItem,
                'editors': editors
            });

            this._fillConditionOperatorMenu($el.find('.js-condition-operator-container'), conditionItem);

            itemContainer.find('.js-group-conditions').append($el);

            $el.find('.form-control').get(0).focus();
        },

        _fillConditionOperatorMenu:function (menu, filterItem) {
            var availableOperators = this._getAvailableOperatorsForField(filterItem.getFieldName());
            menu.html("");

            for (var i = 0; i < availableOperators.length; i++) {
                var $menuItem = $('<option value="'+availableOperators[i]+'">'+conditionOperatorImps[availableOperators[i]].caption+'</option>');

                $menuItem.data({
                    'condition-operator': conditionOperatorImps[availableOperators[i]].operator
                });

                menu.append($menuItem);
            }

            menu.val(filterItem.getOperator());
        },

        _setEditorsValuesFromFilter: function (editors, filter) {
            var self = this;
            var values = filter.getValues();
            var displayValues = filter.getDisplayValues();
            $.each(editors, function (index, editor) {
                editor.setValue(self._getByIndexOrNull(values, index));
                editor.setDisplayValue(self._getByIndexOrNull(displayValues, index));
            });
        },

        _getByIndexOrNull: function (values, index) {
            return index >= values.length ? null : values[index];
        },

        _setFilterValuesFromEditors: function (filter, editors) {
            var values = [];
            var displayValues = [];

            $.each(editors, function (index, editor) {
                values.push(editor.getValue());
                displayValues.push(editor.getDisplayValue());
            });

            filter.setValues(values);
            filter.setDisplayValues(displayValues);
        },

        _setEditorsOnChange: function (editors, onChange) {
            $.each(editors, function (index, editor) {
                editor.onChange(onChange);
            });
        },

        _getEditorsDefaultValues: function (editors) {
            return $.map(editors, function (editor) {
                return editor.getDefaultValue();
            });
        },

        _getAvailableOperatorsByField: function (field) {
            if (field.editorClass === exports.Select2Filter) {
                return [
                    ConditionOperator.Equals,
                    ConditionOperator.DoesNotEqual,
                    ConditionOperator.IsBlank,
                    ConditionOperator.IsNotBlank
                ];
            }

            switch (field.fieldType) {
                case FieldType.Integer:
                    return [
                        ConditionOperator.Equals,
                        ConditionOperator.DoesNotEqual,
                        ConditionOperator.IsGreaterThan,
                        ConditionOperator.IsGreaterThanOrEqualTo,
                        ConditionOperator.IsLessThan,
                        ConditionOperator.IsLessThanOrEqualTo,
                        ConditionOperator.IsBetween,
                        ConditionOperator.IsNotBetween,
                        ConditionOperator.IsLike,
                        ConditionOperator.IsNotLike,
                        ConditionOperator.IsBlank,
                        ConditionOperator.IsNotBlank
                    ];
                case FieldType.String:
                    return [
                        ConditionOperator.Equals,
                        ConditionOperator.DoesNotEqual,
                        ConditionOperator.IsGreaterThan,
                        ConditionOperator.IsGreaterThanOrEqualTo,
                        ConditionOperator.IsLessThan,
                        ConditionOperator.IsLessThanOrEqualTo,
                        ConditionOperator.Contains,
                        ConditionOperator.DoesNotContain,
                        ConditionOperator.BeginsWith,
                        ConditionOperator.EndsWith,
                        ConditionOperator.IsLike,
                        ConditionOperator.IsNotLike,
                        ConditionOperator.IsBlank,
                        ConditionOperator.IsNotBlank
                    ];
                case FieldType.Boolean:
                    return [
                        ConditionOperator.Equals,
                        ConditionOperator.DoesNotEqual
                    ];
                case FieldType.Date:
                    return [
                        ConditionOperator.Equals,
                        ConditionOperator.DoesNotEqual,
                        ConditionOperator.IsGreaterThan,
                        ConditionOperator.IsGreaterThanOrEqualTo,
                        ConditionOperator.IsLessThan,
                        ConditionOperator.IsLessThanOrEqualTo,
                        ConditionOperator.IsBlank,
                        ConditionOperator.IsNotBlank,
                        ConditionOperator.IsBetween,
                        ConditionOperator.IsNotBetween
                    ];
                case FieldType.DateTime:
                    return [
                        ConditionOperator.Equals,
                        ConditionOperator.DoesNotEqual,
                        ConditionOperator.IsGreaterThan,
                        ConditionOperator.IsGreaterThanOrEqualTo,
                        ConditionOperator.IsLessThan,
                        ConditionOperator.IsLessThanOrEqualTo,
                        ConditionOperator.IsBlank,
                        ConditionOperator.IsNotBlank,
                        ConditionOperator.IsBetween,
                        ConditionOperator.IsNotBetween
                    ];
                case FieldType.Time:
                    return [
                        ConditionOperator.Equals,
                        ConditionOperator.DoesNotEqual,
                        ConditionOperator.IsGreaterThan,
                        ConditionOperator.IsGreaterThanOrEqualTo,
                        ConditionOperator.IsLessThan,
                        ConditionOperator.IsLessThanOrEqualTo,
                        ConditionOperator.IsBlank,
                        ConditionOperator.IsNotBlank,
                        ConditionOperator.IsBetween,
                        ConditionOperator.IsNotBetween
                    ];
                case FieldType.Blob:
                    return [
                        ConditionOperator.IsBlank,
                        ConditionOperator.IsNotBlank
                    ];
                default:
                    return [];
            }
        },

        _getAvailableOperatorsForField:function (fieldName) {
            return this._getAvailableOperatorsByField(this.fields[fieldName]);
        },

        _addGroupItem:function (itemContainer, groupItem) {

            var template = _.template($('#filterBuilderGroupTemplate').html());
            var $el = $(template({
                operator: groupItem.getOperatorAsString()
            }));

            $el.data({
                'filter-item': groupItem
            });

            itemContainer.append($el);

            this._addItems($el.find('.js-group-content').first(), groupItem.getItems());
        },

        /**
         * @param {JQuery} group
         * @param {string} actionType
         * @private
         */
        _groupMenuActionClickHandler: function (group, actionType) {
            if (actionType == 'add-condition') {
                this.addSubItem(group,
                    new FilterCondition(
                        this.fields[this.fieldNames[0]].name,
                        this.fields[this.fieldNames[0]].fieldType, ConditionOperator.Equals, null)
                );
            }
            else if (actionType == 'add-group') {
                this.addSubItem(group,
                    new FilterGroup()
                );
                group.find('.js-group-action-select[data-action="add-condition"]:eq(-2)').trigger('click');
            }
            else if (actionType == 'remove') {
                var groupFilterItem = this._getFilterItemByItem(group);
                this.removeItem(groupFilterItem);
            }
        },

        /**
         * @param {JQuery} group
         * @param {string} actionType
         * @private
         */
        _groupChangeOperationHandler: function(group, actionType) {
            var groupFilterItem = this._getFilterItemByItem(group);
            if (typeof(GroupOperator[actionType]) != 'undefined') {
                groupFilterItem.setOperator(GroupOperator[actionType]);
            }

            var $item = this.findItemByFilterItem(groupFilterItem);
            $item.find('.js-group-operator-text').first().html(groupFilterItem.getOperatorAsString());
        },

        _addItem: function (itemContainer, item) {
            if (item.type == FilterItemType.Group) {
                this._addGroupItem(itemContainer, item);
            }
            else if (item.type == FilterItemType.Condition) {
                this._addConditionItem(itemContainer, item);
            }
        },

        _addItems:function (itemContainer, items) {
            for (var i = 0; i < items.length; i++) {
                this._addItem(itemContainer, items[i]);
            }
        },

        /**
         * @param {string} name
         * @return {string}
         * @private
         */
        _getCaptionByFieldName: function(name) {
            return this.fields[name].caption;
        },

        addField:function (name, caption, fieldType, editorClass, editorOptions) {
            this.fieldNames.push(name);
            this.fields[name] = {
                name:name,
                caption:caption,
                fieldType:fieldType,
                editorClass:editorClass,
                editorOptions:editorOptions
            };
        },

        getRootItem:function () {
            return this.container.children('.js-group');
        },

        findItemByFilterItem:function (filterItem) {
            return this.container.find('.js-group, .js-condition').filter(function () {
                return $(this).data('filter-item') == filterItem;
            });
        },

        _getFilterItemByItem:function ($item) {
            return $item.data('filter-item');
        },

        removeItem:function (filterItem) {
            var parent = this.root.findItemParent(filterItem);
            if (filterItem.type == FilterItemType.Condition) {
                if (parent) {
                    var $item = this.findItemByFilterItem(filterItem);
                    $item.remove();
                    parent.remove(filterItem);
                }

                return;
            }

            if (filterItem === this.root) {
                for (var i = this.root.getItems().length - 1; i >= 0; i--) {
                    this.removeItem(this.root.getItems()[i]);
                }

                return;
            }

            if (parent) {
                var $groupItem = this.findItemByFilterItem(filterItem);
                $groupItem.remove();
                parent.remove(filterItem);
            }
        },

        addSubItem:function ($parent, item) {
            var parentFilterItem = this._getFilterItemByItem($parent);
            parentFilterItem.add(item);
            this._addItem($parent.find('.js-group-content').first(), item);
        },

        activate:function () {
            this._addItems(this.container, [this.root]);
        }
    });


    var FilterItemType = {
        Group:1,
        Condition:2
    };

    var FieldType = exports.FieldType = {
        Integer:1,
        String:2,
        Blob:3,
        DateTime:4,
        Date:5,
        Time:6,
        Boolean:7
    };

    var GroupOperator = {
        And:1,
        Or:2,
        None:3
    };

    var GroupOperatorTranslations = {
        And: 'OperatorAnd',
        Or: 'OperatorOr',
        None: 'OperatorNone'
    };

    var ConditionOperator = {
        Equals:1,
        DoesNotEqual:2,
        IsGreaterThan:3,
        IsGreaterThanOrEqualTo:4,
        IsLessThan:5,
        IsLessThanOrEqualTo:6,
        IsBetween:7,
        IsNotBetween:8,
        Contains:9,
        DoesNotContain:10,
        BeginsWith:11,
        EndsWith:12,
        IsLike:13,
        IsNotLike:14,
        IsBlank:15,
        IsNotBlank:16
    };

    var OperatorValuesCount = {};
    $.each(ConditionOperator, function(name, key) {
        OperatorValuesCount[key] = 1;
    });

    OperatorValuesCount[ConditionOperator.IsBetween] = 2;
    OperatorValuesCount[ConditionOperator.IsNotBetween] = 2;
    OperatorValuesCount[ConditionOperator.IsBlank] = 0;
    OperatorValuesCount[ConditionOperator.IsNotBlank] = 0;

    var ConditionOperatorImp = Class.extend({
        init:function (caption, operator) {
            this.caption = caption;
            this.operator = operator;
        }
    });

    var conditionOperatorImps = {};
    $.each(ConditionOperator, function (name, value) {
        conditionOperatorImps[value] = new ConditionOperatorImp(
            localizer.getString('FilterOperator' + name),
            value
        );
    });

    var getGroupOperatorAsString = function (operator) {
        var GroupOperatorByName = _.invert(GroupOperator);
        var string = '';
        if (typeof(GroupOperatorByName[operator]) != 'undefined') {
            string = localizer.getString(GroupOperatorTranslations[GroupOperatorByName[operator]])
        }

        return string;
    };

    var getOperatorAsString = function (operator) {
        return conditionOperatorImps[operator].caption;
    };

});
