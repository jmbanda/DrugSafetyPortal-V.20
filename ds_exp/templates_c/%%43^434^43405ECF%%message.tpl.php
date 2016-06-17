<div class="alert alert-<?php echo $this->_tpl_vars['type']; ?>
<?php if (! isset ( $this->_tpl_vars['dismissable'] ) || $this->_tpl_vars['dismissable']): ?> alert-dismissable<?php endif; ?>"<?php if (isset ( $this->_tpl_vars['displayTime'] )): ?> data-display-time="<?php echo $this->_tpl_vars['displayTime']; ?>
"<?php endif; ?>>

    <?php if (! isset ( $this->_tpl_vars['dismissable'] ) || $this->_tpl_vars['dismissable']): ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <?php endif; ?>

    <?php if (isset ( $this->_tpl_vars['caption'] ) && $this->_tpl_vars['caption']): ?>
        <strong><?php echo $this->_tpl_vars['caption']; ?>
</strong><br>
    <?php endif; ?>

    <div class="js-content"><?php echo $this->_tpl_vars['content']; ?>
</div>

</div>