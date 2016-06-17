<?php
set_include_path('../..' . PATH_SEPARATOR . get_include_path());
include_once 'components/captions.php';

header('Content-Type: application/javascript');

$captions = GetCaptions('UTF-8');
$codes = array(
    'DeleteUserConfirmation',
    'And',
    'Ok',
    'CalendarMonths',
    'CalendarMonthsShort',
    'CalendarWeekdays',
    'CalendarWeekdaysShort',
    'CalendarWeekdaysMin',
    'Cancel',
    'Commit',
    'ErrorsDuringUpdateProcess',
    'PasswordChanged',
    'Equals',
    'DoesNotEquals',
    'IsLessThan',
    'IsLessThanOrEqualsTo',
    'IsGreaterThan',
    'IsGreaterThanOrEqualsTo',
    'Like',
    'IsBlank',
    'IsNotBlank',
    'IsLike',
    'IsNotLike',
    'Contains',
    'DoesNotContain',
    'BeginsWith',
    'EndsWith',
    'OperatorAnd',
    'OperatorOr',
    'OperatorNone',
    'Loading',
    'FilterBuilder',
    'DeleteSelectedRecordsQuestion',
    'DeleteRecordQuestion',
    'FilterOperatorEquals',
    'FilterOperatorDoesNotEqual',
    'FilterOperatorIsGreaterThan',
    'FilterOperatorIsGreaterThanOrEqualTo',
    'FilterOperatorIsLessThan',
    'FilterOperatorIsLessThanOrEqualTo',
    'FilterOperatorIsBetween',
    'FilterOperatorIsNotBetween',
    'FilterOperatorContains',
    'FilterOperatorDoesNotContain',
    'FilterOperatorBeginsWith',
    'FilterOperatorEndsWith',
    'FilterOperatorIsLike',
    'FilterOperatorIsNotLike',
    'FilterOperatorIsBlank',
    'FilterOperatorIsNotBlank',
    'Select2MatchesOne',
    'Select2MatchesMoreOne',
    'Select2NoMatches',
    'Select2AjaxError',
    'Select2InputTooShort',
    'Select2InputTooLong',
    'Select2SelectionTooBig',
    'Select2LoadMore',
    'Select2Searching',
    'SaveAndInsert',
    'SaveAndBackToList',
    'SaveAndEdit',
    'Save',
    'MultipleColumnSorting',
    'Column',
    'Order',
    'Sort',
    'AddLevel',
    'DeleteLevel',
    'Ascending',
    'SortBy',
    'ThenBy',
    'Descending',
    'Close',
);

$resource = array();
foreach ($codes as $code) {
    $resource[$code] = $captions->GetMessageString($code);
}

$resourceJSON = SystemUtils::ToJSON($resource);
$firstDayOfWeek = GetFirstDayOfWeek();

echo "define(function(require, exports) {";
echo "exports.translations = $resourceJSON;";
echo "exports.firstDayOfWeek = $firstDayOfWeek;";
echo "});";
