<h1><?php echo $this->_tpl_vars['Grid']['Title']; ?>
</h1>
<table border="1" cellpadding="0" cellspacing="0" width="100%">
    <?php $_from = $this->_tpl_vars['Grid']['Row']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Column']):
?>
        <tr>
            <td style="background-color:#ccc;width:100px;"><?php echo $this->_tpl_vars['Column']['Caption']; ?>
</td>
            <td style="text-align:left"><?php echo $this->_tpl_vars['Column']['DisplayValue']; ?>
</td>
        </tr>
    <?php endforeach; endif; unset($_from); ?>
</table>