<div class="form-group">
    <label class="col-sm-3 control-label" for="<?php echo $this->_tpl_vars['Column']['Id']; ?>
">
        <?php echo $this->_tpl_vars['Column']['Caption']; ?>

        <?php if ($this->_tpl_vars['Column']['Required']): ?>
            <span class="required-mark">*</span>
        <?php endif; ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "edit_field_options.tpl", 'smarty_include_vars' => array('Column' => $this->_tpl_vars['Column'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </label>
    <div class="col-sm-9 col-input">
        <?php echo $this->_tpl_vars['Column']['Editor']; ?>

    </div>
</div>