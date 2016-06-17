<div id="pgui-view-grid">

    {include file="page-header.tpl" pageTitle=$Grid.Title}

    <div class="form-horizontal">

        {include file="view/actions.tpl" top=true}

        <div class="row">
            {* TODO: Дублируется с record_card_view.tpl *}

            <div class="col-lg-8">
                {foreach from=$Grid.Row item=Cell}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">
                            {$Cell.Caption}
                        </label>
                        <div class="col-sm-9">
                            <div class="form-control-static">
                                {$Cell.DisplayValue}
                            </div>
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>

        {include file="view/actions.tpl" top=false}
    </div>


</div>