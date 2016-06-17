<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escapeurl', 'login_control.tpl', 32, false),)), $this); ?>
<div class="well pgui-login">

    <p class="text-center"><img class="pgui-login-avatar" src="components/assets/img/login_avatar.png" alt="User avatar" /></p>

    <form method="post">
        <div class="form-group">
            <input placeholder="<?php echo $this->_tpl_vars['Captions']->GetMessageString('Username'); ?>
" type="text" name="username" class="form-control" id="username">
        </div>

        <div class="form-group">
            <input placeholder="<?php echo $this->_tpl_vars['Captions']->GetMessageString('Password'); ?>
" type="password" name="password" class="form-control" id="password">
        </div>

        <div class="form-group">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="saveidentity" id="saveidentity" <?php if ($this->_tpl_vars['LoginControl']->GetLastSaveidentity()): ?> checked="checked"<?php endif; ?>>
                    <?php echo $this->_tpl_vars['Captions']->GetMessageString('RememberMe'); ?>

                </label>
            </div>
        </div>

        <?php if ($this->_tpl_vars['LoginControl']->GetErrorMessage() != ''): ?>
            <div class="alert alert-danger">
                <?php echo $this->_tpl_vars['LoginControl']->GetErrorMessage(); ?>

            </div>
        <?php endif; ?>

        <div class="form-group text-center">
            <button class="btn btn-primary" type="submit"><?php echo $this->_tpl_vars['Captions']->GetMessageString('Login'); ?>
</button>
            <?php if ($this->_tpl_vars['LoginControl']->CanLoginAsGuest()): ?>
                &nbsp;<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['LoginControl']->GetLoginAsGuestLink())) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
" class="btn btn-default"><?php echo $this->_tpl_vars['Captions']->GetMessageString('LoginAsGuest'); ?>
</a>
            <?php endif; ?>
        </div>

    </form>

</div>