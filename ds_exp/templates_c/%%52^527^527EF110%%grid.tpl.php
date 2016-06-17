<div id="pgui-view-grid">

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page-header.tpl", 'smarty_include_vars' => array('pageTitle' => $this->_tpl_vars['Grid']['Title'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

    <div class="form-horizontal">

        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "view/actions.tpl", 'smarty_include_vars' => array('top' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

        <div class="row">
            
            <div class="col-lg-8">
                <?php $_from = $this->_tpl_vars['Grid']['Row']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Cell']):
?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">
                            <?php echo $this->_tpl_vars['Cell']['Caption']; ?>

                        </label>
                        <div class="col-sm-9">
                            <div class="form-control-static">
                                <?php echo $this->_tpl_vars['Cell']['DisplayValue']; ?>

                            </div>
                        </div>
                    </div>
                <?php endforeach; endif; unset($_from); ?>
            </div>
        </div>

        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "view/actions.tpl", 'smarty_include_vars' => array('top' => false)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>


</div>