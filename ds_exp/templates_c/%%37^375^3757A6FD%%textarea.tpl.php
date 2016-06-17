<textarea
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "editors/editor_options.tpl", 'smarty_include_vars' => array('Editor' => $this->_tpl_vars['TextArea'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    class="form-control"
    <?php if ($this->_tpl_vars['TextArea']->getPlaceholder()): ?>
        placeholder="<?php echo $this->_tpl_vars['TextArea']->getPlaceholder(); ?>
"
    <?php endif; ?>
    <?php if ($this->_tpl_vars['TextArea']->GetColumnCount()): ?>
        cols="<?php echo $this->_tpl_vars['TextArea']->GetColumnCount(); ?>
"
    <?php endif; ?>
    <?php if ($this->_tpl_vars['TextArea']->GetRowCount()): ?>
        rows="<?php echo $this->_tpl_vars['TextArea']->GetRowCount(); ?>
"
    <?php endif; ?>><?php echo $this->_tpl_vars['TextArea']->GetValue(); ?>
</textarea>