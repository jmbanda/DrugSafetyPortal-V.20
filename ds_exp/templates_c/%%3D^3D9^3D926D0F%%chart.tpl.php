<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'json_encode', 'charts/chart.tpl', 11, false),)), $this); ?>
<div id="pgui-chart-<?php echo $this->_tpl_vars['chart']['id']; ?>
" class="pgui-chart" style="height: <?php echo $this->_tpl_vars['chart']['height']; ?>
px">
    <img class="pgui-chart-loading" src="components/assets/img/loading.gif">
</div>

<?php echo '
<script type="text/javascript">
    require([\'pgui.charts\'], function (initializeChart) {
        initializeChart({
            id: \''; ?>
<?php echo $this->_tpl_vars['chart']['id']; ?>
<?php echo '\',
            type: \''; ?>
<?php echo $this->_tpl_vars['type']; ?>
<?php echo '\',
            options: '; ?>
<?php echo json_encode($this->_tpl_vars['chart']['options']); ?>
<?php echo ',
            data: '; ?>
<?php echo json_encode($this->_tpl_vars['chart']['data']); ?>
<?php echo '
        });
    });
</script>
'; ?>