<?php

require_once 'phpgen_settings.php';
require_once 'components/security/security_info.php';
require_once 'components/security/datasource_security_info.php';
require_once 'components/security/tablebased_auth.php';
require_once 'components/security/user_grants_manager.php';
require_once 'components/security/table_based_user_grants_manager.php';

include_once 'components/security/user_identity_storage/user_identity_session_storage.php';

require_once 'database_engine/mysql_engine.php';

$grants = array('guest' => 
        array()
    ,
    'defaultUser' => 
        array('dataset' => new DataSourceSecurityInfo(false, false, false, false),
        'published_unique_ddi' => new DataSourceSecurityInfo(false, false, false, false),
        'submission' => new DataSourceSecurityInfo(false, false, false, false),
        'unique_ddi_submission' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'juan' => 
        array('dataset' => new DataSourceSecurityInfo(false, false, false, false),
        'published_unique_ddi' => new DataSourceSecurityInfo(false, false, false, false),
        'submission' => new DataSourceSecurityInfo(false, false, false, false),
        'unique_ddi_submission' => new DataSourceSecurityInfo(false, false, false, false))
    );

$appGrants = array('guest' => new DataSourceSecurityInfo(false, false, false, false),
    'defaultUser' => new DataSourceSecurityInfo(true, false, false, false),
    'juan' => new DataSourceSecurityInfo(true, true, true, true));

$dataSourceRecordPermissions = array();

$tableCaptions = array('dataset' => 'Published Dataset Upload',
'published_unique_ddi' => 'Register published single drug-drug interaction',
'submission' => 'Register Dataset for Submission',
'unique_ddi_submission' => 'Register unpublished single drug-durg interaction');

function CreateTableBasedGrantsManager()
{
    return null;
}

function SetUpUserAuthorization()
{
    global $grants;
    global $appGrants;
    global $dataSourceRecordPermissions;
    $hardCodedGrantsManager = new HardCodedUserGrantsManager($grants, $appGrants);
    $tableBasedGrantsManager = CreateTableBasedGrantsManager();
    $grantsManager = new CompositeGrantsManager();
    $grantsManager->AddGrantsManager($hardCodedGrantsManager);
    if (!is_null($tableBasedGrantsManager)) {
        $grantsManager->AddGrantsManager($tableBasedGrantsManager);
        GetApplication()->SetUserManager($tableBasedGrantsManager);
    }
    $userAuthorizationStrategy = new TableBasedUserAuthorization(new UserIdentitySessionStorage(GetIdentityCheckStrategy()), new MyPDOConnectionFactory(), GetGlobalConnectionOptions(), 'users', 'user_name', 'user_id', $grantsManager);
    GetApplication()->SetUserAuthorizationStrategy($userAuthorizationStrategy);

    GetApplication()->SetDataSourceRecordPermissionRetrieveStrategy(
        new HardCodedDataSourceRecordPermissionRetrieveStrategy($dataSourceRecordPermissions));
}

function GetIdentityCheckStrategy()
{
    return new TableBasedIdentityCheckStrategy(new MyPDOConnectionFactory(), GetGlobalConnectionOptions(), 'users', 'user_name', 'user_password', 'MD5');
}

function CanUserChangeOwnPassword()
{
    return true;
}

?>