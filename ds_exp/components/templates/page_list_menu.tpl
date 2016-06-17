<ul class="nav navbar-nav">
    {foreach item=Group from=$List.Groups}
        {if $Group != 'Default'}
            <li class="dropdown">

                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    {$Group}
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
        {/if}

        {foreach item=PageListPage from=$List.Pages}

            {if $PageListPage.GroupName == $Group}
                {if $PageListPage.BeginNewGroup}
                    <li role="separator" class="divider"></li>
                {/if}
                <li><a href="{$PageListPage.Href|escapeurl}" title="{$PageListPage.Hint}">
                    {$PageListPage.Caption}
                </a></li>
            {/if}
        {/foreach}

        {if $Group != 'Default'}
            </ul>
        {/if}
    {/foreach}
</ul>