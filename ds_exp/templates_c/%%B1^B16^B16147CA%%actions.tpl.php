<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escapeurl', 'view/actions.tpl', 11, false),array('modifier', 'string_format', 'view/actions.tpl', 39, false),)), $this); ?>
<div class="form-actions<?php if ($this->_tpl_vars['top']): ?> form-actions-top<?php endif; ?>">
    <div class="form-group">
        <div class="col-lg-8">
            <div class="row">
                <div class="col-sm-9 col-sm-offset-3">
                    <div class="btn-toolbar">

                        <div class="btn-group">
                            <a class="btn btn-primary"
                               title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('BackToList'); ?>
"
                               href="<?php echo ((is_array($_tmp=$this->_tpl_vars['Grid']['CancelUrl'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
">
                                <i class="icon-arrow-left"></i>
                                <span class="hidden-sm hidden-xs"><?php echo $this->_tpl_vars['Captions']->GetMessageString('BackToList'); ?>
</span>
                            </a>
                        </div>

                        <?php if ($this->_tpl_vars['Grid']['HasEditGrant']): ?>
                            <div class="btn-group">
                                <a class="btn btn-default"
                                   title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('Edit'); ?>
"
                                   href="<?php echo ((is_array($_tmp=$this->_tpl_vars['Grid']['EditUrl'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
">
                                    <i class="icon-edit"></i>
                                    <span class="hidden-sm hidden-xs"><?php echo $this->_tpl_vars['Captions']->GetMessageString('Edit'); ?>
</span>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if (count ( $this->_tpl_vars['Grid']['Details'] ) > 0): ?>
                            <div class="btn-group">
                                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#"
                                    title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('ManageDetails'); ?>
">
                                    <span class="icon-list"></span>
                                    <span class="hidden-sm hidden-xs"><?php echo $this->_tpl_vars['Captions']->GetMessageString('ManageDetails'); ?>
</span>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <?php $_from = $this->_tpl_vars['Grid']['Details']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Detail']):
?>
                                        <li>
                                            <a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['Detail']['Link'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['Detail']['Caption'])) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['Captions']->GetMessageString('ManageDetail')) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['Captions']->GetMessageString('ManageDetail'))); ?>
</a>
                                        </li>
                                    <?php endforeach; endif; unset($_from); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <div class="btn-group">
                            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "view/export_buttons.tpl", 'smarty_include_vars' => array('buttons' => $this->_tpl_vars['Grid']['ExportButtons'],'spanClasses' => "hidden-sm hidden-xs")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                            <?php if ($this->_tpl_vars['Grid']['PrintOneRecord']): ?>
                                <div class="btn-group">
                                    <a class="btn btn-default"
                                       href="<?php echo ((is_array($_tmp=$this->_tpl_vars['Grid']['PrintRecordLink'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
"
                                       title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('PrintOneRecord'); ?>
">
                                        <i class="icon-print-page"></i>
                                        <span class="hidden-sm hidden-xs"><?php echo $this->_tpl_vars['Captions']->GetMessageString('PrintOneRecord'); ?>
</span>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>