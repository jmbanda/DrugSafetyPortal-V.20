<div class="modal-dialog {$modalSizeClass}">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">{$Grid.Title}</h4>
        </div>
        <div class="modal-body">
            <div class="form-horizontal">
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
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>