<span class="field-options">
    <?php if ($this->_tpl_vars['Column']['DisplaySetToNullCheckBox']): ?>
        <button tabindex="-1" class="set-to-null btn btn-default btn-xs <?php if ($this->_tpl_vars['Column']['IsValueNull']): ?>active<?php endif; ?>" data-toggle="button" onclick="return false;"><?php echo $this->_tpl_vars['Captions']->GetMessageString('SetToNull'); ?>
</button>
        <input class="set-to-null-input" type="hidden" id="<?php echo $this->_tpl_vars['Column']['SetNullCheckBoxName']; ?>
" name="<?php echo $this->_tpl_vars['Column']['SetNullCheckBoxName']; ?>
" value="<?php if ($this->_tpl_vars['Column']['IsValueNull']): ?>1<?php else: ?>0<?php endif; ?>">
    <?php endif; ?>
    <?php if ($this->_tpl_vars['Column']['DisplaySetToDefaultCheckBox']): ?>
        <button tabindex="-1" class="set-default btn btn-default btn-xs" data-toggle="button" onclick="return false;">set default</button>
        <input class="set-default-input" type="hidden" id="<?php echo $this->_tpl_vars['Column']['SetDefaultCheckBoxName']; ?>
" name="<?php echo $this->_tpl_vars['Column']['SetDefaultCheckBoxName']; ?>
" value="<?php echo $this->_tpl_vars['Column']['IsValueSetToDefault']; ?>
">
    <?php endif; ?>
</span>