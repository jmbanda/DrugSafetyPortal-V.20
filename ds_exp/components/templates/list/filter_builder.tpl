<script type="text/html" id="filterBuilderGroupTemplate">
    <div class="filter-builder-group js-group">
        <div class="filter-builder-group-operator">
            {$Captions->getMessageString('FilterBuilderGroupConditionBeforeRules')}
            <div class="dropdown">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="text-underline js-group-operator-text"><%= operator %></span> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#" class="js-group-operator-select" data-operator="And" data-translate="{$Captions->GetMessageString('OperatorAnd')}">{$Captions->GetMessageString('OperatorAnd')}</a></li>
                    <li><a href="#" class="js-group-operator-select" data-operator="Or" data-translate="{$Captions->GetMessageString('OperatorOr')}">{$Captions->GetMessageString('OperatorOr')}</a></li>
                    <li><a href="#" class="js-group-operator-select" data-operator="None" data-translate="{$Captions->GetMessageString('OperatorNone')}">{$Captions->GetMessageString('OperatorNone')}</a></li>
                </ul>
            </div>
            {$Captions->getMessageString('FilterBuilderGroupConditionAfterRules')}

            <div class="btn-group pull-right">
                <a href="#" class="btn btn-default js-group-action-select" data-action="add-group" title="{$Captions->GetMessageString('AddGroup')}"><span class="icon-add-group"></span></a>
                <a href="#" class="btn btn-default js-group-action-select" data-action="remove" title="{$Captions->GetMessageString('RemoveGroup')}"><span class="icon-remove"></span></a>
            </div>
        </div>
        <div class="filter-builder-group-content js-group-content">
            <div class="filter-builder-group-conditions js-group-conditions"></div>
        </div>
        <div class="filter-builder-group-footer">
            <a href="#" class="btn btn-default js-group-action-select" data-action="add-condition"><span class="icon-add-condition"></span>{$Captions->GetMessageString('AddCondition')}</a>
        </div>
    </div>
</script>

<script type="text/html" id="filterBuilderConditionTemplate">
    <div class="filter-builder-condition js-condition">
        <div class="filter-builder-condition-field">
            <select class="form-control js-condition-field-select">
                {literal}<% _.each(fieldList, function(item, key) { %>
                    <option value="<%= key %>"<% if (item.caption == field) { %> selected="selected"<% } %>><%= item.caption %></option>
                <% }) %>{/literal}
            </select>
        </div>
        <div class="filter-builder-condition-operator">
            <select class="form-control js-condition-operator-select js-condition-operator-container">
                <option><%= operator %></option>
            </select>
        </div>
        <div class="filter-builder-condition-value js-value"></div>
        <div class="filter-builder-condition-remove">
            <a href="#" class="btn btn-default js-condition-remove" data-action="remove" title="{$Captions->GetMessageString('RemoveCondition')}"><span class="icon-remove"></span>
            </a>
        </div>
    </div>
</script>

<div class="modal fade filter-builder modal-top js-filter-builder-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{$Captions->GetMessageString('FilterBuilder')}</h4>
            </div>
            <div class="modal-body">
                <div class="js-filter-builder-container">

                </div>
            </div>
            <div class="modal-footer">
                <div class="checkbox pull-left">
                    <label>
                        <input type="checkbox" class="js-filter-builder-disable"{if not $DataGrid.FilterBuilder.IsEnabled} checked{/if}> {$Captions->GetMessageString('DisableFilter')}
                    </label>
                </div>
                <button type="button" class="btn btn-default" data-dismiss="modal">{$Captions->GetMessageString('Cancel')}</button>
                <button type="button" class="btn btn-primary js-filter-builder-commit">{$Captions->GetMessageString('ApplyAdvancedFilter')}</button>
            </div>
        </div>
    </div>
</div>