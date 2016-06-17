<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'style_block', 'editors/image_uploader.tpl', 27, false),array('modifier', 'escapeurl', 'editors/image_uploader.tpl', 36, false),)), $this); ?>
<?php if (! $this->_tpl_vars['Uploader']->GetReadOnly()): ?>

    <?php if ($this->_tpl_vars['RenderText']): ?>
        <?php if ($this->_tpl_vars['Uploader']->GetShowImage() && ! $this->_tpl_vars['HideImage']): ?>
            <img src="<?php echo $this->_tpl_vars['Uploader']->GetLink(); ?>
" style="max-width: 100%;">
            <br/>
        <?php endif; ?>

        <div style="margin: 1em 0;">
            <div class="btn-group" data-toggle-name="<?php echo $this->_tpl_vars['Uploader']->GetName(); ?>
_action" data-toggle="buttons-radio">
                <button type="button" value="Keep" class="active btn btn-default" data-toggle="button"><?php echo $this->_tpl_vars['Captions']->GetMessageString('KeepImage'); ?>
</button>
                <button type="button" value="Remove" class="btn btn-default" data-toggle="button"><?php echo $this->_tpl_vars['Captions']->GetMessageString('RemoveImage'); ?>
</button>
                <button id="<?php echo $this->_tpl_vars['Uploader']->GetName(); ?>
-replace-image-button" type="button" value="Replace" class="btn btn-default" data-toggle="button"><?php echo $this->_tpl_vars['Captions']->GetMessageString('ReplaceImage'); ?>
</button>
            </div>
        </div>
        <input type="hidden" name="<?php echo $this->_tpl_vars['Uploader']->GetName(); ?>
_action" value="Keep" />

        <div class="file-upload-control">
            <input
                <?php echo $this->_tpl_vars['Validators']['InputAttributes']; ?>

                <?php if ($this->_tpl_vars['Uploader']->GetLink()): ?>data-has-file="true"<?php endif; ?>
                data-editor="true"
                data-editor-class="ImageUploaderEditor"
                data-field-name="<?php echo $this->_tpl_vars['Uploader']->GetName(); ?>
"
                type="file"
                name="<?php echo $this->_tpl_vars['Uploader']->GetName(); ?>
_filename"
                <?php $this->_tag_stack[] = array('style_block', array()); $_block_repeat=true;smarty_block_style_block($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?> <?php echo $this->_tpl_vars['Uploader']->GetCustomAttributes(); ?>
 <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_style_block($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>>
        </div>
<?php endif; ?>

<?php else: ?>
<?php if ($this->_tpl_vars['RenderText']): ?>
<?php if ($this->_tpl_vars['Uploader']->GetShowImage() && ! $this->_tpl_vars['HideImage']): ?>
    <img src="<?php echo $this->_tpl_vars['Uploader']->GetLink(); ?>
"><br/>
<?php else: ?>
    <a class="image" target="_blank" title="download" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['Uploader']->GetLink())) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
">Download file</a>
<?php endif; ?>
<?php endif; ?>

<?php endif; ?>