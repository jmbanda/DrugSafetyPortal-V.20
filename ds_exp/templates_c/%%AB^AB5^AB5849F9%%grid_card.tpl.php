<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'list/grid_card.tpl', 5, false),array('modifier', 'json_encode', 'list/grid_card.tpl', 7, false),array('modifier', 'escapeurl', 'list/grid_card.tpl', 8, false),array('function', 'jsbool', 'list/grid_card.tpl', 8, false),)), $this); ?>
<div<?php if ($this->_tpl_vars['DataGrid']['MaxWidth']): ?> style="max-width: <?php echo $this->_tpl_vars['DataGrid']['MaxWidth']; ?>
"<?php endif; ?>
        class="grid grid-card<?php if ($this->_tpl_vars['isMasterGrid']): ?> grid-master<?php endif; ?>"
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

    <div class="<?php echo $this->_tpl_vars['DataGrid']['Classes']; ?>
" <?php echo $this->_tpl_vars['DataGrid']['Attributes']; ?>
>

        <div class="pg-row-list row">

            <div class="grid-card-item <?php echo $this->_tpl_vars['DataGrid']['CardClasses']; ?>
 pg-row js-new-record-row hidden" data-new-row="false">

                <div class="well">

                    <?php if ($this->_tpl_vars['DataGrid']['AllowDeleteSelected']): ?><div class="row-selection pull-left"></div><?php endif; ?>

                    <?php if ($this->_tpl_vars['DataGrid']['ShowLineNumbers'] || $this->_tpl_vars['DataGrid']['AllowDeleteSelected'] || $this->_tpl_vars['DataGrid']['HasDetails'] || $this->_tpl_vars['DataGrid']['Actions']): ?>
                    <div class="grid-card-item-control pull-right">
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['DataGrid']['ShowLineNumbers']): ?>
                            <div class="line-number pull-left"></div>
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['DataGrid']['HasDetails']): ?>
                            <div dir="ltr" class="details pull-left"></div>
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['DataGrid']['Actions']): ?>
                            <div class="operation-column pull-left"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list/action_list_new_record.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['DataGrid']['ShowLineNumbers'] || $this->_tpl_vars['DataGrid']['AllowDeleteSelected'] || $this->_tpl_vars['DataGrid']['HasDetails'] || $this->_tpl_vars['DataGrid']['Actions']): ?>
                    </div>
                    <?php endif; ?>

                    <div class="grid-card-item-data">
                        <table class="table">
                            <?php $_from = $this->_tpl_vars['DataGrid']['Bands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Band']):
?>
                                <?php $_from = $this->_tpl_vars['Band']['Columns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Column']):
?>
                                    <tr>
                                        <th><?php echo $this->_tpl_vars['Column']['Caption']; ?>
</th>
                                        <td data-column-name="<?php echo $this->_tpl_vars['Column']['Name']; ?>
"></td>
                                    </tr>
                                <?php endforeach; endif; unset($_from); ?>
                            <?php endforeach; endif; unset($_from); ?>
                        </table>
                    </div>
                </div>

            </div>

            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['SingleRowTemplate'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

            <div class="empty-grid<?php if (count ( $this->_tpl_vars['DataGrid']['Rows'] ) > 0): ?> hidden<?php endif; ?>">
                <?php echo $this->_tpl_vars['DataGrid']['EmptyGridMessage']; ?>

            </div>

        </div>

        <div>
            <?php if ($this->_tpl_vars['DataGrid']['Totals']): ?>
                <div class="data-summary">
                    <?php $_from = $this->_tpl_vars['DataGrid']['Totals']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Total']):
?>
                        <?php if ($this->_tpl_vars['Total']['Value']): ?>
                            <div>
                                <strong><?php echo $this->_tpl_vars['Total']['Caption']; ?>
</strong>
                                <?php echo $this->_tpl_vars['Total']['Value']; ?>

                            </div>
                        <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list/grid_common.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>