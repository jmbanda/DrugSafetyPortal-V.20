<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', 'list/single_row_card.tpl', 7, false),array('modifier', 'escapeurl', 'list/single_row_card.tpl', 20, false),)), $this); ?>
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

        <div class="grid-card-item <?php if ($this->_tpl_vars['isMasterGrid']): ?>col-md-12<?php else: ?><?php echo $this->_tpl_vars['DataGrid']['CardClasses']; ?>
<?php endif; ?> <?php echo $this->_tpl_vars['rowClasses']; ?>
">

            <div class="well" style="<?php echo $this->_tpl_vars['Row']['Style']; ?>
">

                <?php if ($this->_tpl_vars['DataGrid']['AllowDeleteSelected']): ?>
                    <div class="row-selection pull-left">
                        <input type="checkbox" name="rec<?php echo ($this->_foreach['RowsGrid']['iteration']-1); ?>
" >
                        <?php $_from = $this->_tpl_vars['Row']['PrimaryKeys']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['CPkValues'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['CPkValues']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['PkValue']):
        $this->_foreach['CPkValues']['iteration']++;
?>
                            <input type="hidden" name="rec<?php echo ($this->_foreach['RowsGrid']['iteration']-1); ?>
_pk<?php echo ($this->_foreach['CPkValues']['iteration']-1); ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['PkValue'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
" />
                        <?php endforeach; endif; unset($_from); ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->_tpl_vars['DataGrid']['ShowLineNumbers'] || $this->_tpl_vars['DataGrid']['AllowDeleteSelected'] || $this->_tpl_vars['DataGrid']['HasDetails'] || $this->_tpl_vars['DataGrid']['Actions']): ?>
                <div class="grid-card-item-control pull-right">
                <?php endif; ?>

                    <?php if ($this->_tpl_vars['DataGrid']['ShowLineNumbers']): ?>
                        <div class="line-number pull-left" style="<?php echo $this->_tpl_vars['Row']['Style']; ?>
">#<?php echo $this->_tpl_vars['Row']['LineNumber']; ?>
</div>
                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['DataGrid']['HasDetails']): ?>
                        <div dir="ltr" class="details pull-left" style="<?php echo $this->_tpl_vars['Row']['Style']; ?>
">
                            <div class="btn-group text-nowrap">
                                <a class="link-icon" data-toggle="dropdown" href="#" title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('GoToMasterDetailPage'); ?>
"><span class="icon-list"></span> <i class="icon-detail-additional"></i></a>
                                <ul class="dropdown-menu">
                                    <?php $_from = $this->_tpl_vars['Row']['Details']['Items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Detail']):
?>
                                        <li><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['Detail']['SeperatedPageLink'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
"><?php echo $this->_tpl_vars['Detail']['caption']; ?>
</a></li>
                                    <?php endforeach; endif; unset($_from); ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['DataGrid']['Actions']): ?>
                        <div class="operation-column pull-left"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list/action_list.tpl", 'smarty_include_vars' => array('Actions' => $this->_tpl_vars['Row']['ActionsDataCells'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
                    <?php endif; ?>

                <?php if ($this->_tpl_vars['DataGrid']['ShowLineNumbers'] || $this->_tpl_vars['DataGrid']['AllowDeleteSelected'] || $this->_tpl_vars['DataGrid']['HasDetails'] || $this->_tpl_vars['DataGrid']['Actions']): ?>
                </div>
                <?php endif; ?>

                <div class="grid-card-item-data">
                    <table class="table">
                        <?php $_from = $this->_tpl_vars['Row']['DataCells']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['Cells'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['Cells']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['Cell']):
        $this->_foreach['Cells']['iteration']++;
?>
                            <tr>
                                <th><?php echo $this->_tpl_vars['Cell']['ColumnCaption']; ?>
</th>
                                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list/data_cell.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                            </tr>
                        <?php endforeach; endif; unset($_from); ?>
                    </table>
                </div>
            </div>

        </div>

    <?php endforeach; endif; unset($_from); ?>

<?php endif; ?>