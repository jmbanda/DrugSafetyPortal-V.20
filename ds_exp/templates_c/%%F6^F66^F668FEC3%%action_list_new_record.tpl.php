<?php $_from = $this->_tpl_vars['DataGrid']['Actions']['Operations']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Operation']):
?>
    <span data-column-name="<?php echo $this->_tpl_vars['Operation']->GetName(); ?>
" class="operation-item">

        <?php if ($this->_tpl_vars['Operation']->GetName() == 'edit' || $this->_tpl_vars['Operation']->GetName() == 'InlineEdit'): ?>

            <span data-content="inline_insert_controls text-nowrap">

                <a href="#" class="js-inline_insert_cancel link-icon" title="Cancel">
                    <span class="text-lg text-danger"><i class="icon-remove"></i></span>
                </a>

                <a href="#" class="js-inline_insert_commit link-icon" title="Commit">
                    <span class="text-lg text-success"><i class="icon-ok"></i></span>
                </a>

            </span>

        <?php endif; ?>

    </span>
<?php endforeach; endif; unset($_from); ?>