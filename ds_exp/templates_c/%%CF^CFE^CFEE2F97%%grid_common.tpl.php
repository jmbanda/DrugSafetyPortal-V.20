<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsstring', 'list/grid_common.tpl', 41, false),)), $this); ?>
<?php if (! $this->_tpl_vars['isMasterGrid']): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list/multiple_sorting.tpl", 'smarty_include_vars' => array('GridId' => $this->_tpl_vars['DataGrid']['Id'],'Levels' => $this->_tpl_vars['DataGrid']['DataSortPriority'],'SortableHeaders' => $this->_tpl_vars['DataGrid']['SortableColumns'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['DataGrid']['FilterBuilder']): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list/filter_builder.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<script type="text/javascript">

    <?php if ($this->_tpl_vars['AdvancedSearchControl']): ?>
    <?php echo '
    require([\'pgui.text_highlight\'], function(textHighlight) {
        '; ?>

        <?php $_from = $this->_tpl_vars['AdvancedSearchControl']->GetHighlightedFields(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['HighlightFields'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['HighlightFields']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['HighlightFieldName']):
        $this->_foreach['HighlightFields']['iteration']++;
?>
        textHighlight.HighlightTextInGrid(
                '#<?php echo $this->_tpl_vars['DataGrid']['Id']; ?>
', '<?php echo $this->_tpl_vars['HighlightFieldName']; ?>
',
                <?php echo $this->_tpl_vars['TextsForHighlight'][($this->_foreach['HighlightFields']['iteration']-1)]; ?>
,
                '<?php echo $this->_tpl_vars['HighlightOptions'][($this->_foreach['HighlightFields']['iteration']-1)]; ?>
');
        <?php endforeach; endif; unset($_from); ?>
        <?php echo '
    });
    '; ?>

    <?php endif; ?>


    <?php echo '
    require([\'pgui.grid\', \'pgui.advanced_filter\', \'jquery\'], function(pggrid, fb) {

        var gridId = \''; ?>
<?php echo $this->_tpl_vars['DataGrid']['Id']; ?>
<?php echo '\';
        var $gridContainer = $(\'#\' + gridId);
        var grid = new pggrid.Grid($gridContainer);

        '; ?>

        <?php if ($this->_tpl_vars['DataGrid']['FilterBuilder']): ?>
        <?php echo '
        grid.onConfigureFilterBuilder(function(filterBuilder) {
            '; ?>

            <?php $_from = $this->_tpl_vars['FilterBuilder']['Fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['FilterBuilderField']):
?>
            filterBuilder.addField(
                    <?php echo smarty_function_jsstring(array('value' => $this->_tpl_vars['FilterBuilderField']['Name'],'charset' => $this->_tpl_vars['Page']->GetContentEncoding()), $this);?>
,
                    <?php echo smarty_function_jsstring(array('value' => $this->_tpl_vars['FilterBuilderField']['Caption'],'charset' => $this->_tpl_vars['Page']->GetContentEncoding()), $this);?>
,
                    fb.FieldType.<?php echo $this->_tpl_vars['FilterBuilderField']['Type']; ?>
,
                    fb.<?php echo $this->_tpl_vars['FilterBuilderField']['EditorClass']; ?>
,
                    <?php echo $this->_tpl_vars['FilterBuilderField']['EditorOptions']; ?>
);
            <?php endforeach; endif; unset($_from); ?>
            <?php echo '
        });

        var activeFilterJson = '; ?>
<?php echo $this->_tpl_vars['ActiveFilterBuilderJson']; ?>
<?php echo ';
        var activeFilter = new fb.FilterGroup();
        activeFilter.fromJson(activeFilterJson);
        grid.setFilter(activeFilter);
        '; ?>

        <?php endif; ?>
        <?php echo '
    });
    '; ?>

</script>