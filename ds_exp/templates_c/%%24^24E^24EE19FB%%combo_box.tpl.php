<select
    class="form-control"
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "editors/editor_options.tpl", 'smarty_include_vars' => array('Editor' => $this->_tpl_vars['ComboBox'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>>
    <?php if ($this->_tpl_vars['ComboBox']->ShowEmptyValue()): ?>
        <option value=""><?php echo $this->_tpl_vars['ComboBox']->GetEmptyValue(); ?>
</option>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['ComboBox']->HasMFUValues()): ?>
        <?php $_from = $this->_tpl_vars['ComboBox']->GetMFUValues(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Value'] => $this->_tpl_vars['Name']):
?>
            <option value="<?php echo $this->_tpl_vars['Value']; ?>
"><?php echo $this->_tpl_vars['Name']; ?>
</option>
        <?php endforeach; endif; unset($_from); ?>
        <option value="----------" disabled="disabled">----------</option>
    <?php endif; ?>
    <?php $_from = $this->_tpl_vars['ComboBox']->GetDisplayValues(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Value'] => $this->_tpl_vars['Name']):
?>
        <option value="<?php echo $this->_tpl_vars['Value']; ?>
"<?php if (( $this->_tpl_vars['ComboBox']->IsSelectedValue($this->_tpl_vars['Value']) )): ?> selected<?php endif; ?>><?php echo $this->_tpl_vars['Name']; ?>
</option>
    <?php endforeach; endif; unset($_from); ?>
</select>