<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'string_format', 'forms/form-actions.tpl', 30, false),)), $this); ?>
<div class="form-actions<?php if ($this->_tpl_vars['top']): ?> form-actions-top<?php endif; ?>">
    <div class="form-group">
        <div class="col-lg-8">
            <div class="row">
                <div class="col-sm-9 col-sm-offset-3">
                    <div class="btn-toolbar">
                        <div class="btn-group">
                            <button <?php if (! $this->_tpl_vars['top']): ?>id="submit-button" <?php endif; ?>class="btn btn-primary submit-button" type="submit"><?php echo $this->_tpl_vars['Captions']->GetMessageString('Save'); ?>
</button>
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#" class="save-button" data-value="save"><?php echo $this->_tpl_vars['Captions']->GetMessageString('SaveAndBackToList'); ?>
</a>
                                </li>
                                <li>
                                    <a href="#" class="saveinsert-button" data-value="saveinsert"><?php echo $this->_tpl_vars['Captions']->GetMessageString('SaveAndInsert'); ?>
</a>
                                </li>
                                <li>
                                    <a href="#" class="saveedit-button" data-value="saveedit">
                                        <?php echo $this->_tpl_vars['Captions']->GetMessageString('SaveAndEdit'); ?>

                                    </a>
                                </li>

                                <?php if (count ( $this->_tpl_vars['Grid']['Details'] ) > 0): ?>
                                    <li class="divider"></li>
                                <?php endif; ?>

                                <?php $_from = $this->_tpl_vars['Grid']['Details']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Detail']):
?>
                                    <li><a class="save-and-open-details" href="#" data-action="<?php echo $this->_tpl_vars['Detail']['Link']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['Detail']['Caption'])) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['Captions']->GetMessageString('SaveAndOpenDetail')) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['Captions']->GetMessageString('SaveAndOpenDetail'))); ?>
</a></li>
                                <?php endforeach; endif; unset($_from); ?>
                            </ul>
                        </div>

                        <div class="btn-group">
                            <button class="btn btn-default" onclick="window.location.href='<?php echo $this->_tpl_vars['Grid']['CancelUrl']; ?>
'; return false;"><?php echo $this->_tpl_vars['Captions']->GetMessageString('Cancel'); ?>
</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>