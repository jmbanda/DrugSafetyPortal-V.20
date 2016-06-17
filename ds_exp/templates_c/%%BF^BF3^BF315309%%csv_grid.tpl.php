<?php $_from = $this->_tpl_vars['HeaderCaptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['HeaderIterator'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['HeaderIterator']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['HeaderCaption']):
        $this->_foreach['HeaderIterator']['iteration']++;
?>
<?php echo $this->_tpl_vars['HeaderCaption']; ?>
<?php if (! ($this->_foreach['HeaderIterator']['iteration'] == $this->_foreach['HeaderIterator']['total'])): ?>,<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>

<?php $_from = $this->_tpl_vars['Rows']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['RowsGrid'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['RowsGrid']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['Row']):
        $this->_foreach['RowsGrid']['iteration']++;
?>
<?php $_from = $this->_tpl_vars['Row']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['DataFieldIterator'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['DataFieldIterator']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['RowColumn']):
        $this->_foreach['DataFieldIterator']['iteration']++;
?>"<?php echo $this->_tpl_vars['RowColumn']; ?>
"<?php if (! ($this->_foreach['DataFieldIterator']['iteration'] == $this->_foreach['DataFieldIterator']['total'])): ?>,<?php endif; ?><?php endforeach; endif; unset($_from); ?>

<?php endforeach; endif; unset($_from); ?>