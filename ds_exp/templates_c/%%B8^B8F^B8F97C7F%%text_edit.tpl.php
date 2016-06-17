<?php if (( $this->_tpl_vars['TextEdit']->getPrefix() || $this->_tpl_vars['TextEdit']->getSuffix() )): ?>
    <div class="input-group">
<?php endif; ?>
<?php if ($this->_tpl_vars['TextEdit']->getPrefix()): ?>
    <span class="input-group-addon"><?php echo $this->_tpl_vars['TextEdit']->getPrefix(); ?>
</span>
<?php endif; ?>
<input
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "editors/editor_options.tpl", 'smarty_include_vars' => array('Editor' => $this->_tpl_vars['TextEdit'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    class="form-control"
    value="<?php echo $this->_tpl_vars['TextEdit']->GetHTMLValue(); ?>
"
    <?php if ($this->_tpl_vars['TextEdit']->getPlaceholder()): ?>
        placeholder="<?php echo $this->_tpl_vars['TextEdit']->getPlaceholder(); ?>
"
    <?php endif; ?>
    <?php if ($this->_tpl_vars['TextEdit']->GetPasswordMode()): ?>
        type="password"
    <?php else: ?>
        type="text"
    <?php endif; ?>
    <?php if ($this->_tpl_vars['TextEdit']->GetMaxLength()): ?>
        maxlength="<?php echo $this->_tpl_vars['TextEdit']->GetMaxLength(); ?>
"
    <?php endif; ?>
>
<?php if ($this->_tpl_vars['TextEdit']->getSuffix()): ?>
    <span class="input-group-addon"><?php echo $this->_tpl_vars['TextEdit']->getSuffix(); ?>
</span>
<?php endif; ?>
<?php if ($this->_tpl_vars['TextEdit']->getPrefix() || $this->_tpl_vars['TextEdit']->getSuffix()): ?>
    </div>
<?php endif; ?>