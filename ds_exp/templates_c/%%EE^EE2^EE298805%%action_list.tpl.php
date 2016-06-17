<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'list/action_list.tpl', 5, false),)), $this); ?>
<?php 
    $this->assign("IconByOperationMap", array('view' => 'icon-view', 'edit' => 'icon-edit', 'delete' => 'icon-remove', 'copy' => 'icon-copy' ));
 ?>

<?php $this->assign('UseOperationContainer', ((is_array($_tmp=@$this->_tpl_vars['UseOperationContainer'])) ? $this->_run_mod_handler('default', true, $_tmp, true) : smarty_modifier_default($_tmp, true))); ?>

<?php $_from = $this->_tpl_vars['Actions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Cell']):
?>

    <?php if ($this->_tpl_vars['Cell']['Data']): ?>
        <?php if ($this->_tpl_vars['UseOperationContainer']): ?><span data-column-name="<?php echo $this->_tpl_vars['Cell']['OperationName']; ?>
" class="operation-item"><?php endif; ?>

            <?php if ($this->_tpl_vars['Cell']['Data']['type'] == 'link'): ?>

                <a href="<?php echo $this->_tpl_vars['Cell']['Data']['link']; ?>
" title="<?php echo $this->_tpl_vars['Cell']['Data']['caption']; ?>
"<?php if ($this->_tpl_vars['Cell']['Data']['useImage']): ?> class="link-icon"<?php endif; ?>
                    <?php $_from = $this->_tpl_vars['Cell']['Data']['additionalAttributes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
?> <?php echo $this->_tpl_vars['key']; ?>
="<?php echo $this->_tpl_vars['value']; ?>
"<?php endforeach; endif; unset($_from); ?>>

                    <?php if ($this->_tpl_vars['Cell']['Data']['useImage']): ?>
                        <i class="<?php echo $this->_tpl_vars['Cell']['IconClass']; ?>
"></i>
                    <?php else: ?>
                        <?php echo $this->_tpl_vars['Cell']['Data']['caption']; ?>

                    <?php endif; ?>

                </a>

            <?php elseif ($this->_tpl_vars['Cell']['Data']['type'] == 'modal'): ?>

                <a href="#" title="<?php echo $this->_tpl_vars['Cell']['Data']['dialogTitle']; ?>
"<?php if ($this->_tpl_vars['Cell']['Data']['useImage']): ?> class="link-icon"<?php endif; ?>
                    data-dialog-title="<?php echo $this->_tpl_vars['Cell']['Data']['dialogTitle']; ?>
" data-modal-operation="<?php echo $this->_tpl_vars['Cell']['Data']['name']; ?>
" data-content-link="<?php echo $this->_tpl_vars['Cell']['Data']['link']; ?>
">

                    <?php if ($this->_tpl_vars['Cell']['Data']['useImage']): ?>
                        <i class="<?php echo $this->_tpl_vars['IconByOperationMap'][$this->_tpl_vars['Cell']['OperationName']]; ?>
"></i>
                    <?php else: ?>
                        <?php echo $this->_tpl_vars['Cell']['Data']['caption']; ?>

                    <?php endif; ?>

                </a>

            <?php elseif ($this->_tpl_vars['Cell']['Data']['type'] == 'inline_edit'): ?>

                <span class="inline_edit_controls<?php if ($this->_tpl_vars['Cell']['Data']['useImage']): ?> default-fade-in fade-out-on-hover<?php endif; ?> text-nowrap">

                    <a href="#" class="js-inline_edit_init<?php if ($this->_tpl_vars['Cell']['Data']['useImage']): ?> link-icon<?php endif; ?>" title="<?php echo $this->_tpl_vars['Cell']['Data']['editCaption']; ?>
">
                        <?php if ($this->_tpl_vars['Cell']['Data']['useImage']): ?><i class="icon-edit"></i><?php else: ?><?php echo $this->_tpl_vars['Cell']['Data']['editCaption']; ?>
<?php endif; ?>
                    </a>

                    <a href="#" style="display: none;" class="js-inline_edit_cancel<?php if ($this->_tpl_vars['Cell']['Data']['useImage']): ?> link-icon<?php endif; ?>" title="<?php echo $this->_tpl_vars['Cell']['Data']['cancelCaption']; ?>
">
                        <span class="text-danger text-lg"><?php if ($this->_tpl_vars['Cell']['Data']['useImage']): ?><i class="icon-remove"></i><?php else: ?><?php echo $this->_tpl_vars['Cell']['Data']['cancelCaption']; ?>
<?php endif; ?></span>
                    </a>

                    <a href="#" style="display: none;" class="js-inline_edit_commit<?php if ($this->_tpl_vars['Cell']['Data']['useImage']): ?> link-icon<?php endif; ?>" title="<?php echo $this->_tpl_vars['Cell']['Data']['commitCaption']; ?>
">
                        <span class="text-success text-lg"><?php if ($this->_tpl_vars['Cell']['Data']['useImage']): ?><i class="icon-ok"></i><?php else: ?><?php echo $this->_tpl_vars['Cell']['Data']['commitCaption']; ?>
<?php endif; ?></span>
                    </a>

                    <?php $_from = $this->_tpl_vars['Cell']['Data']['keys']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['value']):
?>
                        <input type="hidden" name="<?php echo $this->_tpl_vars['name']; ?>
" value="<?php echo $this->_tpl_vars['value']; ?>
" />
                    <?php endforeach; endif; unset($_from); ?>

                </span>

            <?php else: ?>

                <?php echo $this->_tpl_vars['Cell']['Data']; ?>


            <?php endif; ?>

        <?php if ($this->_tpl_vars['UseOperationContainer']): ?></span><?php endif; ?>
    <?php endif; ?>

<?php endforeach; endif; unset($_from); ?>