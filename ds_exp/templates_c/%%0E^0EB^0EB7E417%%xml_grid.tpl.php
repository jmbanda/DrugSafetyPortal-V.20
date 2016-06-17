<Table name="<?php echo $this->_tpl_vars['TableName']; ?>
">
<?php $_from = $this->_tpl_vars['Rows']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['RowsGrid'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['RowsGrid']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['Row']):
        $this->_foreach['RowsGrid']['iteration']++;
?>
    <Row>
<?php $_from = $this->_tpl_vars['Row']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['FieldName'] => $this->_tpl_vars['RowColumn']):
?>
        <<?php echo $this->_tpl_vars['FieldName']; ?>
><?php echo $this->_tpl_vars['RowColumn']; ?>
</<?php echo $this->_tpl_vars['FieldName']; ?>
>
<?php endforeach; endif; unset($_from); ?>
    </Row>
<?php endforeach; endif; unset($_from); ?>
</Table>