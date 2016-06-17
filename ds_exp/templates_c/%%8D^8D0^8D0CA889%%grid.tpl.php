 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page-header.tpl", 'smarty_include_vars' => array('pageTitle' => $this->_tpl_vars['Grid']['Title'],'pageWithForm' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<form class="js-pgui-edit-form form-horizontal" enctype="multipart/form-data" method="POST" action="<?php echo $this->_tpl_vars['Grid']['FormAction']; ?>
" data-type="insert">

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'forms/form-actions.tpl', 'smarty_include_vars' => array('top' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

    <?php if (! $this->_tpl_vars['Grid']['ErrorMessage'] == ''): ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'common/message.tpl', 'smarty_include_vars' => array('type' => 'danger','dismissable' => true,'caption' => $this->_tpl_vars['Captions']->GetMessageString('ErrorsDuringInsertProcess'),'content' => $this->_tpl_vars['Grid']['ErrorMessage'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>

    <?php if (! $this->_tpl_vars['Grid']['Message'] == ''): ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'common/message.tpl', 'smarty_include_vars' => array('type' => 'success','dismissable' => true,'content' => $this->_tpl_vars['Grid']['Message'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <fieldset>
                <input id="submit-action" name="submit1" type="hidden" value="save">
                <?php $_from = $this->_tpl_vars['HiddenValues']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['HiddenValueName'] => $this->_tpl_vars['HiddenValue']):
?>
                    <input type="hidden" name="<?php echo $this->_tpl_vars['HiddenValueName']; ?>
" value="<?php echo $this->_tpl_vars['HiddenValue']; ?>
">
                <?php endforeach; endif; unset($_from); ?>

                <?php $_from = $this->_tpl_vars['Grid']['Columns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['EditColumns'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['EditColumns']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['Column']):
        $this->_foreach['EditColumns']['iteration']++;
?>
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'forms/form-group.tpl', 'smarty_include_vars' => array('Column' => $this->_tpl_vars['Column'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <?php endforeach; endif; unset($_from); ?>

                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'forms/form-required.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            </fieldset>
        </div>
    </div>

    <div class="error-container"></div>

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'forms/form-actions.tpl', 'smarty_include_vars' => array('top' => false)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</form>

<script type="text/javascript">
    <?php echo '
        function InsertForm_initd(editors) {
            '; ?>
<?php echo $this->_tpl_vars['Grid']['OnLoadScript']; ?>
<?php echo ';
        }
    '; ?>

</script>