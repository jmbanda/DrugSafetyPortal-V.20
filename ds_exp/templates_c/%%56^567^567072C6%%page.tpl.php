<?php ob_start(); ?>
    <?php if ($this->_tpl_vars['Page']->hasCharts()): ?>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript"><?php echo '
            google.load("visualization", "1", {packages:["corechart"]});
        '; ?>
</script>
    <?php endif; ?>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('HeadBlock', ob_get_contents());ob_end_clean(); ?>

<?php ob_start(); ?>

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page-header.tpl", 'smarty_include_vars' => array('pageTitle' => $this->_tpl_vars['Page']->GetTitle())));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list/page_navigator_modal.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_description_block.tpl", 'smarty_include_vars' => array('Description' => $this->_tpl_vars['Page']->GetGridHeader())));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "charts/collection.tpl", 'smarty_include_vars' => array('charts' => $this->_tpl_vars['ChartsBeforeGrid'],'chartsClasses' => $this->_tpl_vars['ChartsBeforeGridClasses'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

    <?php echo $this->_tpl_vars['PageNavigator1']; ?>


    <?php echo $this->_tpl_vars['Grid']; ?>


    <?php echo $this->_tpl_vars['PageNavigator2']; ?>


    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "charts/collection.tpl", 'smarty_include_vars' => array('charts' => $this->_tpl_vars['ChartsAfterGrid'],'chartsClasses' => $this->_tpl_vars['ChartsAfterGridClasses'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('ContentBlock', ob_get_contents());ob_end_clean(); ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/list_page_template.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>