<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', 'list/single_row.tpl', 5, false),array('modifier', 'escapeurl', 'list/single_row.tpl', 18, false),)), $this); ?>
<?php if (count ( $this->_tpl_vars['DataGrid']['Rows'] ) > 0): ?>
    <?php $_from = $this->_tpl_vars['DataGrid']['Rows']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['RowsGrid'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['RowsGrid']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['Row']):
        $this->_foreach['RowsGrid']['iteration']++;
?>

        <?php if ($this->_tpl_vars['Row']['Classes']): ?>
            <?php $this->assign('rowClasses', ((is_array($_tmp="pg-row ")) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['Row']['Classes']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['Row']['Classes']))); ?>
        <?php else: ?>
            <?php $this->assign('rowClasses', "pg-row"); ?>
        <?php endif; ?>

        <tr class="<?php echo $this->_tpl_vars['rowClasses']; ?>
" style="<?php echo $this->_tpl_vars['Row']['Style']; ?>
">
            <?php if ($this->_tpl_vars['DataGrid']['AllowDeleteSelected']): ?>
                <td style="<?php echo $this->_tpl_vars['Row']['Style']; ?>
">
                    <div class="row-selection">
                        <input type="checkbox" name="rec<?php echo ($this->_foreach['RowsGrid']['iteration']-1); ?>
">
                        <?php $_from = $this->_tpl_vars['Row']['PrimaryKeys']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['CPkValues'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['CPkValues']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['PkValue']):
        $this->_foreach['CPkValues']['iteration']++;
?>
                            <input type="hidden"
                                   name="rec<?php echo ($this->_foreach['RowsGrid']['iteration']-1); ?>
_pk<?php echo ($this->_foreach['CPkValues']['iteration']-1); ?>
"
                                   value="<?php echo ((is_array($_tmp=$this->_tpl_vars['PkValue'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
"/>
                        <?php endforeach; endif; unset($_from); ?>
                    </div>
                </td>
            <?php endif; ?>

            <?php if ($this->_tpl_vars['DataGrid']['HasDetails']): ?>
                <td dir="ltr" class="details" style="width: 40px;<?php echo $this->_tpl_vars['Row']['Style']; ?>
">
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list/details_icon.tpl", 'smarty_include_vars' => array('Details' => $this->_tpl_vars['Row']['Details'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
            <?php endif; ?>

            <?php if ($this->_tpl_vars['DataGrid']['ShowLineNumbers']): ?>
                <td class="line-number" style="<?php echo $this->_tpl_vars['Row']['Style']; ?>
"><?php echo $this->_tpl_vars['Row']['LineNumber']; ?>
</td>
            <?php endif; ?>

            <?php if ($this->_tpl_vars['DataGrid']['Actions'] && $this->_tpl_vars['DataGrid']['Actions']['PositionIsLeft']): ?>
                <td class="operation-column">
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list/action_list.tpl", 'smarty_include_vars' => array('Actions' => $this->_tpl_vars['Row']['ActionsDataCells'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
            <?php endif; ?>

            <?php $_from = $this->_tpl_vars['Row']['DataCells']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['Cells'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['Cells']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['Cell']):
        $this->_foreach['Cells']['iteration']++;
?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list/data_cell.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <?php endforeach; endif; unset($_from); ?>

            <?php if ($this->_tpl_vars['DataGrid']['Actions'] && $this->_tpl_vars['DataGrid']['Actions']['PositionIsRight']): ?>
                <td class="operation-column">
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list/action_list.tpl", 'smarty_include_vars' => array('Actions' => $this->_tpl_vars['Row']['ActionsDataCells'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; endif; unset($_from); ?>
<?php endif; ?>