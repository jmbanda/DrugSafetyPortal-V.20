{capture assign="HeadBlock"}
    {if $Page->hasCharts()}
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript">{literal}
            google.load("visualization", "1", {packages:["corechart"]});
        {/literal}</script>
    {/if}
{/capture}

{capture assign="ContentBlock"}

    {include file="page-header.tpl" pageTitle=$PageTitle}

    {include file="page_description_block.tpl" Description=$Page->GetGridHeader()}

    <p>{$Captions->GetMessageString('MasterRecord')}
        (<a href="{$Page->GetParentPageLink()|escapeurl}">{$Captions->GetMessageString('ReturnFromDetailToMaster')}</a>)
    </p>

    {$MasterGrid}

    {if count($SiblingDetails) > 1}
        <ul class="nav nav-tabs">
            {foreach from=$SiblingDetails item=SiblingDetail name=SiblingDetailsSection}
                <li class="{if $DetailPageName == $SiblingDetail.Name}active{/if}">
                    <a href="{$SiblingDetail.Link|escapeurl}">
                        {$SiblingDetail.Caption}
                    </a>
                </li>
            {/foreach}
        </ul>
    {/if}

    {include file="charts/collection.tpl" charts=$ChartsBeforeGrid chartsClasses=$ChartsBeforeGridClasses}

    {$PageNavigator1}

    {$Grid}

    {$PageNavigator2}

    {include file="charts/collection.tpl" charts=$ChartsAfterGrid chartsClasses=$ChartsAfterGridClasses}

    {include file="list/page_navigator_modal.tpl"}
{/capture}

{* Base template *}
{include file="common/list_page_template.tpl"}