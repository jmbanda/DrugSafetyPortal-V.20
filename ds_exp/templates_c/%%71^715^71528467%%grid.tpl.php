<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'list/grid.tpl', 5, false),array('modifier', 'json_encode', 'list/grid.tpl', 7, false),array('modifier', 'escapeurl', 'list/grid.tpl', 8, false),array('function', 'jsbool', 'list/grid.tpl', 8, false),)), $this); ?>
<div<?php if ($this->_tpl_vars['DataGrid']['MaxWidth']): ?> style="max-width: <?php echo $this->_tpl_vars['DataGrid']['MaxWidth']; ?>
"<?php endif; ?>
        class="grid grid-table<?php if ($this->_tpl_vars['isMasterGrid']): ?> grid-master<?php endif; ?>"
        id="<?php echo $this->_tpl_vars['DataGrid']['Id']; ?>
"
        data-is-master="<?php echo $this->_tpl_vars['isMasterGrid']; ?>
"
        data-grid-hidden-values="<?php echo ((is_array($_tmp=$this->_tpl_vars['DataGrid']['HiddenValuesJson'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"
        data-sortable-columns="<?php echo ((is_array($_tmp=$this->_tpl_vars['DataGrid']['SortableColumnsJSON'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"
        data-quickfilter-fields="<?php echo ((is_array($_tmp=json_encode($this->_tpl_vars['DataGrid']['QuickFilter']['FieldsNames']))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"
        data-inline-edit="{ &quot;enabled&quot;:&quot;<?php echo smarty_function_jsbool(array('value' => $this->_tpl_vars['DataGrid']['UseInlineEdit']), $this);?>
&quot;, &quot;request&quot;:&quot;<?php echo ((is_array($_tmp=$this->_tpl_vars['DataGrid']['Links']['InlineEditRequest'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
&quot;}" <?php echo $this->_tpl_vars['DataGrid']['Attributes']; ?>
>

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list/grid_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

    <table class="table text-center <?php echo $this->_tpl_vars['DataGrid']['Classes']; ?>
<?php if ($this->_tpl_vars['DataGrid']['TableIsBordered']): ?> table-bordered<?php endif; ?><?php if ($this->_tpl_vars['DataGrid']['TableIsCondensed']): ?> table-condensed<?php endif; ?>">
        <thead>

            <tr class="header">

                <?php if ($this->_tpl_vars['DataGrid']['AllowDeleteSelected']): ?>
                    <th style="width:1%;">
                        <div class="row-selection">
                            <input type="checkbox">
                        </div>
                    </th>
                <?php endif; ?>

                <?php if ($this->_tpl_vars['DataGrid']['HasDetails']): ?>
                    <th class="details">
                        <a class="expand-all-details js-expand-all-details collapsed link-icon" href="#" title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('ToggleAllDetails'); ?>
">
                            <i class="icon-detail-plus"></i>
                            <i class="icon-detail-minus"></i>
                        </a>
                    </th>
                <?php endif; ?>

                <?php if ($this->_tpl_vars['DataGrid']['ShowLineNumbers']): ?>
                    <th style="width:1%;">#</th>
                <?php endif; ?>

                <?php if ($this->_tpl_vars['DataGrid']['Actions'] && $this->_tpl_vars['DataGrid']['Actions']['PositionIsLeft']): ?>
                    <th style="width:1%;">
                        <?php echo $this->_tpl_vars['DataGrid']['Actions']['Caption']; ?>

                    </th>
                <?php endif; ?>

                <?php $_from = $this->_tpl_vars['DataGrid']['Bands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Band']):
?>
                    <?php if ($this->_tpl_vars['Band']['ConsolidateHeader'] && $this->_tpl_vars['Band']['ColumnCount'] > 0): ?>
                        <th colspan="<?php echo $this->_tpl_vars['Band']['ColumnCount']; ?>
" style="width:1%;">
                            <?php echo $this->_tpl_vars['Band']['Caption']; ?>

                        </th>
                    <?php else: ?>
                        <?php $_from = $this->_tpl_vars['Band']['Columns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Column']):
?>
                            <th class="<?php echo $this->_tpl_vars['Column']['Classes']; ?>
<?php if ($this->_tpl_vars['Column']['Sortable']): ?> sortable<?php endif; ?>"
                                <?php if ($this->_tpl_vars['Column']['Width']): ?>
                                    style="width: <?php echo $this->_tpl_vars['Column']['Width']; ?>
;"
                                <?php endif; ?>
                                data-field-caption="<?php echo $this->_tpl_vars['Column']['Caption']; ?>
"
                                data-field-name="<?php echo $this->_tpl_vars['Column']['Name']; ?>
"
                                data-sort-index="<?php echo $this->_tpl_vars['Column']['SortIndex']; ?>
"
                                <?php if ($this->_tpl_vars['Column']['SortOrderType'] == 'ASC'): ?>
                                    data-sort-order="asc"
                                <?php elseif ($this->_tpl_vars['Column']['SortOrderType'] == 'DESC'): ?>
                                    data-sort-order="desc"
                                <?php endif; ?>
                                data-comment="<?php echo $this->_tpl_vars['Column']['Comment']; ?>
">
                                <?php if ($this->_tpl_vars['Column']['Keys']['Primary'] && $this->_tpl_vars['Column']['Keys']['Foreign']): ?>
                                    <i class="icon-keys-pk-fk"></i>
                                <?php else: ?>
                                    <?php if ($this->_tpl_vars['Column']['Keys']['Primary']): ?>
                                        <i class="icon-keys-pk"></i>
                                    <?php endif; ?>
                                    <?php if ($this->_tpl_vars['Column']['Keys']['Foreign']): ?>
                                        <i class="icon-keys-fk"></i>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <span<?php if ($this->_tpl_vars['Column']['Comment']): ?> class="commented"<?php endif; ?>><?php echo $this->_tpl_vars['Column']['Caption']; ?>
</span>
                                <?php if ($this->_tpl_vars['Column']['SortOrderType'] == 'ASC'): ?>
                                    <i class="icon-sort-asc"></i>
                                <?php elseif ($this->_tpl_vars['Column']['SortOrderType'] == 'DESC'): ?>
                                    <i class="icon-sort-desc"></i>
                                <?php endif; ?>
                            </th>
                        <?php endforeach; endif; unset($_from); ?>
                    <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?>

                <?php if ($this->_tpl_vars['DataGrid']['Actions'] && $this->_tpl_vars['DataGrid']['Actions']['PositionIsRight']): ?>
                    <th style="width:1%;">
                        <?php echo $this->_tpl_vars['DataGrid']['Actions']['Caption']; ?>

                    </th>
                <?php endif; ?>
            </tr>

        </thead>
        <tbody class="pg-row-list">
            <tr class="pg-row js-new-record-row hidden" data-new-row="false">
                <?php if ($this->_tpl_vars['DataGrid']['AllowDeleteSelected']): ?>
                    <td data-column-name="sm_multi_delete_column"></td>
                <?php endif; ?>

                <?php if ($this->_tpl_vars['DataGrid']['HasDetails']): ?>
                    <td dir="ltr" data-column-name="details" class="details">
                    </td>
                <?php endif; ?>

                <?php if ($this->_tpl_vars['DataGrid']['ShowLineNumbers']): ?>
                    <td class="line-number"></td>
                <?php endif; ?>

                <?php if ($this->_tpl_vars['DataGrid']['Actions'] && $this->_tpl_vars['DataGrid']['Actions']['PositionIsLeft']): ?>
                    <td class="operation-column"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list/action_list_new_record.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
                <?php endif; ?>

                <?php $_from = $this->_tpl_vars['DataGrid']['Bands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Band']):
?>
                    <?php $_from = $this->_tpl_vars['Band']['Columns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Column']):
?>
                        <td data-column-name="<?php echo $this->_tpl_vars['Column']['Name']; ?>
"></td>
                    <?php endforeach; endif; unset($_from); ?>
                <?php endforeach; endif; unset($_from); ?>

                <?php if ($this->_tpl_vars['DataGrid']['Actions'] && $this->_tpl_vars['DataGrid']['Actions']['PositionIsRight']): ?>
                    <td class="operation-column"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list/action_list_new_record.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
                <?php endif; ?>
            </tr>

            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['SingleRowTemplate'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

            <tr class="empty-grid<?php if (count ( $this->_tpl_vars['DataGrid']['Rows'] ) > 0): ?> hidden<?php endif; ?>">
                <td colspan="<?php echo $this->_tpl_vars['DataGrid']['ColumnCount']; ?>
" class="empty-grid">
                    <?php echo $this->_tpl_vars['DataGrid']['EmptyGridMessage']; ?>

                </td>
            </tr>

        </tbody>

        <tfoot>
            <?php if ($this->_tpl_vars['DataGrid']['Totals']): ?>
                <tr class="data-summary">
                    <?php if ($this->_tpl_vars['DataGrid']['AllowDeleteSelected']): ?>
                        <td></td>
                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['DataGrid']['HasDetails']): ?>
                        <td></td>
                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['DataGrid']['ShowLineNumbers']): ?>
                        <td></td>
                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['DataGrid']['Actions'] && $this->_tpl_vars['DataGrid']['Actions']['PositionIsLeft']): ?>
                        <td></td>
                    <?php endif; ?>

                    <?php $_from = $this->_tpl_vars['DataGrid']['Totals']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Total']):
?>
                        <td class="<?php echo $this->_tpl_vars['Total']['Classes']; ?>
"><?php echo $this->_tpl_vars['Total']['Value']; ?>
</td>
                    <?php endforeach; endif; unset($_from); ?>

                    <?php if ($this->_tpl_vars['DataGrid']['Actions'] && $this->_tpl_vars['DataGrid']['Actions']['PositionIsRight']): ?>
                        <td></td>
                    <?php endif; ?>
                </tr>
            <?php endif; ?>
        </tfoot>
    </table>

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list/grid_common.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>