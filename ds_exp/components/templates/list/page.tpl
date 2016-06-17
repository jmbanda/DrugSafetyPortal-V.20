{capture assign="HeadBlock"}
    {if $Page->hasCharts()}
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript">{literal}
            google.load("visualization", "1", {packages:["corechart"]});
        {/literal}</script>
    {/if}
{/capture}

{capture assign="ContentBlock"}

    {include file="page-header.tpl" pageTitle=$Page->GetTitle()}
    {include file="list/page_navigator_modal.tpl"}

    {include file="page_description_block.tpl" Description=$Page->GetGridHeader()}

    {include file="charts/collection.tpl" charts=$ChartsBeforeGrid chartsClasses=$ChartsBeforeGridClasses}

    {$PageNavigator1}

    {$Grid}

    {$PageNavigator2}

    {include file="charts/collection.tpl" charts=$ChartsAfterGrid chartsClasses=$ChartsAfterGridClasses}

{/capture}

{* Base template *}
{include file="common/list_page_template.tpl"}