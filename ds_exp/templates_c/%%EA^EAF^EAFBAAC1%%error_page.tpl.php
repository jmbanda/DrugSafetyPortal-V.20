<?php $this->assign('ContentBlockClass', "col-md-8 col-md-offset-2"); ?>

<?php ob_start(); ?>

<div class="alert alert-danger">
    <h3><?php echo $this->_tpl_vars['Captions']->GetMessageString('Error'); ?>
</h3>

    <?php echo $this->_tpl_vars['Captions']->GetMessageString('CriticalErrorSuggestions'); ?>


    <br /><br />

    <h4><?php echo $this->_tpl_vars['Captions']->GetMessageString('ErrorDetails'); ?>
:</h4>

    <div style="padding-left: 20px;">
        <?php echo $this->_tpl_vars['Message']; ?>

    </div>
</div>

<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('ContentBlock', ob_get_contents());ob_end_clean(); ?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/layout.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>