define(function (require) {

    require('components/js/pgui.change_password_dialog.js');
    var PhpGenUserManagementApi = require('components/js/pgui.user_management_api.js');

    PhpGenChangePasswordDialog.initialize(new PhpGenChangePasswordDialogUserStrategy(
        function(currentPassword, newPassword) {
          return PhpGenUserManagementApi.selfChangePassword(currentPassword, newPassword);
        }
    ));

    $('#self-change-password').click(function() {PhpGenChangePasswordDialog.open();});

});

