<?php

// require_once 'components/grid/columns.php';
// require_once 'components/utils/html_utils.php';

include_once dirname(__FILE__) . '/' . 'columns.php';
include_once dirname(__FILE__) . '/' . '../utils/html_utils.php';

include_once(dirname(__FILE__) . '/operations/base_row_operation.php');
include_once(dirname(__FILE__) . '/operations/link_operation.php');
include_once(dirname(__FILE__) . '/operations/modal_row_operation.php');
include_once(dirname(__FILE__) . '/operations/modal_view_operation.php');
include_once(dirname(__FILE__) . '/operations/modal_edit_operation.php');
include_once(dirname(__FILE__) . '/operations/modal_copy_operation.php');
include_once(dirname(__FILE__) . '/operations/inline_edit_operation.php');
