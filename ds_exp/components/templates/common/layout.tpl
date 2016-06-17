<!DOCTYPE html>
<html{if $common->getDirection()} dir="{$common->getDirection()}"{/if}>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1">
    {if $common->getContentEncoding()}
        <meta charset="{$common->getContentEncoding()}">
    {/if}
    {$common->getCustomHead()}
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    {if $common}
        <title>{$common->getTitle()}</title>
    {else}
        <title>Error</title>
    {/if}

    <link rel="stylesheet" type="text/css" href="{$StyleFile|default:'components/assets/css/main.css'}" />
    <script type="text/javascript" src="components/js/require-config.js"></script>
    {if $common->getMainScript()}
        <script type="text/javascript" data-main="{$common->getMainScript()}" src="components/js/libs/require.js"></script>
    {else}
        <script type="text/javascript" src="components/js/libs/require.js"></script>
    {/if}
    {$HeadBlock}
</head>

{if $Page}
    {assign var="PageListObj" value=$Page->GetReadyPageList()}
    {if $PageListObj and $Page->GetShowPageList()}
        {if $PageListObj->isTypeSidebar()}
            {capture assign="SideBar"}
                {$PageList}
            {/capture}
        {/if}

        {if $PageListObj->isTypeMenu()}
            {capture assign="Menu"}
                {$PageList}
            {/capture}
        {/if}
    {/if}
{/if}

<body{if $Page} id="pgpage-{$Page->GetPageName()}"{/if}{if $SideBar and not $HideSideBarByDefault}class="sidebar-desktop-active"{/if}>
<nav id="navbar" class="navbar navbar-default navbar-fixed-top">

    {if $SideBar}
        <div class="toggle-sidebar pull-left" title="{$Captions->GetMessageString('SidebarToggle')}">
            <button class="icon-toggle-sidebar"></button>
        </div>
    {/if}

    <div class="container-fluid">
        <div class="navbar-header">
            {if $common}
                {$common->getHeader()}
            {/if}
            {if $Menu or $Authentication.Enabled}
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navnav" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            {/if}
        </div>

        <div class="navbar-collapse collapse" id="navnav">

            {if $Authentication.Enabled}
                <ul id="nav-menu" class="nav navbar-nav navbar-right">
                    {if $Authentication.LoggedIn}
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-user"></i>
                                {if $Authentication.CurrentUser.Name == 'guest'}
                                    {$Captions->GetMessageString('Guest')}
                                {else}
                                    {$Authentication.CurrentUser.Name}
                                {/if}
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                {if $Authentication.isAdminPanelVisible}
                                    <li><a href="phpgen_admin.php" title="{$Captions->GetMessageString('AdminPage')}">{$Captions->GetMessageString('AdminPage')}</a></li>
                                    <li role="separator" class="divider"></li>
                                {/if}
                                {if $Authentication.CanChangeOwnPassword}
                                    <li><a id="self-change-password" href="#" title="{$Captions->GetMessageString('ChangePassword')}">
                                            {$Captions->GetMessageString('ChangePassword')}
                                        </a>
                                    </li>
                                {/if}
                                <li><a href="login.php?operation=logout">{$Captions->GetMessageString('Logout')}</a></li>
                            </ul>
                        </li>
                    {else}
                        <li><a href="login.php">{$Captions->GetMessageString('Login')}</a></li>
                    {/if}
                </ul>
            {/if}

            {if $Menu}
                {$Menu}
            {/if}
        </div>
    </div>
</nav>


{if !isset($HideSideBarByDefault)}
    {assign var="HideSideBarByDefault" value=false}
{/if}


<div class="container-fluid">

    <div class="row{if $SideBar} sidebar-owner{/if}">

        {if $SideBar}

            <div class="sidebar">
                <div class="content">
                    {$SideBar}
                </div>
            </div>
            <div class="sidebar-backdrop"></div>
        {/if}

        <div class="{if isset($ContentBlockClass)}{$ContentBlockClass}{else}col-md-12{/if}">
            {if $SideBar}<div class="sidebar-outer">{/if}
                <div class="container-padding">
                    {$ContentBlock}
                    {$Variables}
                    <hr>
                    <footer>{$common->getFooter()}</footer>
                </div>
            {if $SideBar}</div>{/if}
        </div>

    </div>
</div>

{include file='common/change_password_dialog.tpl'}

{if $common}
    <script>
        {$common->getValidationScripts()}
        require(['jquery', 'bootstrap'], function() {literal}{{/literal}
            {$common->getClientSideScript('OnBeforeLoadEvent')}
            {literal}
                $(function () {
            {/literal}
                {$common->getClientSideScript('OnAfterLoadEvent')}
            {literal}
                });
            {/literal}
        {literal}}{/literal});
    </script>
{/if}

<script type="text/javascript">{literal}
    require([
        'components/js/pgui.user_management_api.js',
        'components/js/pgui.change_password_dialog.js',
        'components/js/pgui.password_dialog_utils.js',
        'components/js/pgui.self_change_password.js'
    ], function() {});
{/literal}</script>

</body>
</html>