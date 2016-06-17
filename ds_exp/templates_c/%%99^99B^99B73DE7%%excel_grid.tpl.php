<table border=1><tr><?php $_from = $this->_tpl_vars['HeaderCaptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Caption']):
?><td x:str><?php echo $this->_tpl_vars['Caption']; ?>
</td><?php endforeach; endif; unset($_from); ?></tr><?php $_from = $this->_tpl_vars['Rows']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['RowsGrid'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['RowsGrid']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['Row']):
        $this->_foreach['RowsGrid']['iteration']++;
?><tr><?php $_from = $this->_tpl_vars['Row']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['RowColumn']):
?><td<?php if ($this->_tpl_vars['RowColumn']['Align'] != null): ?> align="<?php echo $this->_tpl_vars['RowColumn']['Align']; ?>
"<?php endif; ?>><?php echo $this->_tpl_vars['RowColumn']['Value']; ?>
</td><?php endforeach; endif; unset($_from); ?></tr><?php endforeach; endif; unset($_from); ?></table>