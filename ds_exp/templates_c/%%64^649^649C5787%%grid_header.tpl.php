<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escapeurl', 'list/grid_header.tpl', 16, false),array('modifier', 'escape', 'list/grid_header.tpl', 122, false),)), $this); ?>
<?php if ($this->_tpl_vars['DataGrid']['ActionsPanelAvailable']): ?>
    <div class="addition-block">
        <div class="btn-toolbar addition-block-left pull-left">
            <div class="btn-group">
                <?php if ($this->_tpl_vars['DataGrid']['ActionsPanel']['InlineAdd']): ?>
                    <button class="btn btn-default inline_add_button pgui-add" title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('AddNewRecord'); ?>
">
                        <i class="icon-plus"></i>
                        <span class="visible-lg-inline"><?php echo $this->_tpl_vars['Captions']->GetMessageString('AddNewRecord'); ?>
</span>
                    </button>
                <?php endif; ?>

                <?php if ($this->_tpl_vars['DataGrid']['ActionsPanel']['AddNewButton']): ?>
                    <?php if ($this->_tpl_vars['DataGrid']['ActionsPanel']['AddNewButton'] == 'modal'): ?>
                        <button class="btn btn-default pgui-add"
                                data-dialog-title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('AddNewRecord'); ?>
"
                                data-content-link="<?php echo ((is_array($_tmp=$this->_tpl_vars['DataGrid']['Links']['ModalInsertDialog'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
"
                                data-modal-insert="true"
                                title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('AddNewRecord'); ?>
">
                            <i class="icon-plus"></i>
                            <span class="visible-lg-inline"><?php echo $this->_tpl_vars['Captions']->GetMessageString('AddNewRecord'); ?>
</span>
                        </button>
                    <?php else: ?>
                        <a class="btn btn-default pgui-add" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['DataGrid']['Links']['SimpleAddNewRow'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
"
                           title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('AddNewRecord'); ?>
">
                            <i class="icon-plus"></i>
                            <span class="visible-lg-inline"><?php echo $this->_tpl_vars['Captions']->GetMessageString('AddNewRecord'); ?>
</span>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($this->_tpl_vars['DataGrid']['ActionsPanel']['DeleteSelectedButton']): ?>
                    <button class="btn btn-default js-delete-selected" title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('DeleteSelected'); ?>
">
                        <i class="icon-delete-selected"></i>
                        <span class="visible-lg-inline"><?php echo $this->_tpl_vars['Captions']->GetMessageString('DeleteSelected'); ?>
</span>
                    </button>
                <?php endif; ?>

                <?php if ($this->_tpl_vars['DataGrid']['ActionsPanel']['RefreshButton']): ?>
                    <a class="btn btn-default" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['DataGrid']['Links']['Refresh'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
" title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('Refresh'); ?>
">
                        <i class="icon-page-refresh"></i>
                        <span class="visible-lg-inline"><?php echo $this->_tpl_vars['Captions']->GetMessageString('Refresh'); ?>
</span>
                    </a>
                <?php endif; ?>
            </div>

            <?php $this->assign('pageTitleButtons', $this->_tpl_vars['Page']->GetExportListButtonsViewData()); ?>

            <?php if ($this->_tpl_vars['pageTitleButtons']): ?>
                <div class="btn-group export-button">

                    <?php if ($this->_tpl_vars['Page']->getExportListAvailable()): ?>
                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "view/export_buttons.tpl", 'smarty_include_vars' => array('buttons' => $this->_tpl_vars['pageTitleButtons'],'spanClasses' => "visible-lg-inline")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['Page']->getPrintListAvailable()): ?>
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "view/print_buttons.tpl", 'smarty_include_vars' => array('buttons' => $this->_tpl_vars['pageTitleButtons'],'spanClasses' => "visible-lg-inline")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['Page']->GetRssLink()): ?>
                        <a href="<?php echo $this->_tpl_vars['Page']->GetRssLink(); ?>
" class="btn btn-default" title="RSS">
                            <i class="icon-rss"></i>
                            <span class="visible-lg-inline">RSS</span>
                        </a>
                    <?php endif; ?>

                </div>

            <?php endif; ?>

        </div>

        <div class="addition-block-right pull-right">

            <?php if ($this->_tpl_vars['DataGrid']['FilterBuilder']): ?>
                <div class="btn-group">
                    <button type="button" class="btn btn-default js-filter-builder-open" title="<?php if ($this->_tpl_vars['IsActiveFilterEmpty']): ?><?php echo $this->_tpl_vars['Captions']->GetMessageString('CreateFilter'); ?>
<?php else: ?><?php echo $this->_tpl_vars['Captions']->GetMessageString('EditFilter'); ?>
<?php endif; ?>">
                        <i class="icon-filter<?php if ($this->_tpl_vars['IsActiveFilterEmpty']): ?>-new<?php endif; ?>"></i>
                    </button>
                </div>
            <?php endif; ?>

            <div class="btn-group">
                <button id="multi-sort-<?php echo $this->_tpl_vars['DataGrid']['Id']; ?>
" class="btn btn-default" title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('Sort'); ?>
" data-toggle="modal" data-target="#multiple-sorting-<?php echo $this->_tpl_vars['DataGrid']['Id']; ?>
">
                    <i class="icon-sort"></i>
                </button>
            </div>

            <?php if ($this->_tpl_vars['PageNavigator'] || $this->_tpl_vars['EnableRunTimeCustomization']): ?>
                <div class="btn-group">
                    <button class="btn btn-default" title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('PageSettings'); ?>
" data-toggle="modal" data-target="#page-settings">
                        <i class="icon-settings"></i>
                    </button>
                </div>
            <?php endif; ?>

            <?php if ($this->_tpl_vars['Page']->getDetailedDescription()): ?>
                <div class="btn-group">
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#detailedDescriptionModal" title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('PageDescription'); ?>
"><i class="icon-question"></i></button>
                </div>

                <div class="modal fade" id="detailedDescriptionModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo $this->_tpl_vars['Page']->getDetailedDescription(); ?>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->_tpl_vars['Captions']->GetMessageString('Close'); ?>
</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <div class="addition-block-quick-fitler pull-right">
            <?php if ($this->_tpl_vars['DataGrid']['AllowQuickFilter']): ?>
                <div class="quick-filter-toolbar btn-group" id="quick-filter-toolbar">
                    <div class="input-group js-filter-control">
                        <input placeholder="<?php echo $this->_tpl_vars['Captions']->GetMessageString('QuickSearch'); ?>
" type="text" size="16" class="js-quick-filter-text form-control" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['DataGrid']['QuickFilter']['Value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
                        <div class="input-group-btn">
                            <button type="button" id="quick-filter-go" class="btn btn-default quick-filter-go" title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('QuickSearchApply'); ?>
"><i class="icon-search"></i></button>
                            <button type="button" class="btn btn-default quick-filter-reset" title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('QuickSearchClear'); ?>
"><i class="icon-filter-reset"></i></button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            &thinsp;
        </div>

    </div>
<?php endif; ?>

<?php if ($this->_tpl_vars['DataGrid']['FilterBuilder'] && ! $this->_tpl_vars['IsActiveFilterEmpty']): ?>
    <div class="filter-builder-status js-filter-builder-status-string">
        <div class="btn-group filter-builder-status-btn-group pull-right">
            <button type="button" class="btn btn-primary btn-sm js-filter-builder-open" title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('EditFilter'); ?>
">
                <i class="icon-edit"></i>
            </button>
            <button type="button" class="btn btn-default btn-sm js-reset-filter text-nowrap" title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('ResetFilter'); ?>
">
                <i class="icon-remove"></i>
            </button>
            <button type="button" class="btn btn-default btn-sm js-toggle-filter" data-enabled="<?php if ($this->_tpl_vars['DataGrid']['FilterBuilder']['IsEnabled']): ?>true<?php else: ?>false<?php endif; ?>" title="<?php if ($this->_tpl_vars['DataGrid']['FilterBuilder']['IsEnabled']): ?><?php echo $this->_tpl_vars['Captions']->GetMessageString('DisableFilter'); ?>
<?php else: ?><?php echo $this->_tpl_vars['Captions']->GetMessageString('EnableFilter'); ?>
<?php endif; ?>">
                <?php if ($this->_tpl_vars['DataGrid']['FilterBuilder']['IsEnabled']): ?>
                    <i class="icon-disable"></i>
                <?php else: ?>
                    <i class="icon-enable"></i>
                <?php endif; ?>
            </button>
        </div>
        <div class="filter-builder-status-container <?php if (! $this->_tpl_vars['DataGrid']['FilterBuilder']['IsEnabled']): ?> filter-builder-status-disabled<?php endif; ?>">
            <i class="filter-builder-status-icon icon-filter"></i>
            <span class="filter-builder-status-query"><?php echo $this->_tpl_vars['ActiveFilterBuilderAsString']; ?>
</span>
        </div>
    </div>
<?php endif; ?>

<?php if ($this->_tpl_vars['DataGrid']['ErrorMessage']): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'common/message.tpl', 'smarty_include_vars' => array('type' => 'danger','dismissable' => true,'content' => $this->_tpl_vars['DataGrid']['ErrorMessage'],'displayTime' => $this->_tpl_vars['DataGrid']['MessageDisplayTime'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['DataGrid']['GridMessage']): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'common/message.tpl', 'smarty_include_vars' => array('type' => 'success','dismissable' => true,'content' => $this->_tpl_vars['DataGrid']['GridMessage'],'displayTime' => $this->_tpl_vars['DataGrid']['MessageDisplayTime'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<div class="js-grid-message-container" data-template='<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/message.tpl", 'smarty_include_vars' => array('type' => 'success','dismissable' => 'true')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>'>
</div>