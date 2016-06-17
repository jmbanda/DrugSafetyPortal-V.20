<?php

include_once dirname(__FILE__) . '/../renderers/renderer.php';
include_once dirname(__FILE__) . '/../component.php';
include_once dirname(__FILE__) . '/../editors/editors.php';
include_once dirname(__FILE__) . '/../editors/multilevel_selection.php';
include_once dirname(__FILE__) . '/../editors/autocomplete.php';
include_once dirname(__FILE__) . '/../editors/multivalue_select.php';
include_once dirname(__FILE__) . '/../editors/checkboxgroup.php';
include_once dirname(__FILE__) . '/../filter_builder.php';
include_once dirname(__FILE__) . '/../utils/array_utils.php';

include_once dirname(__FILE__) . '/quick_filter.php';
include_once dirname(__FILE__) . '/grid_band.php';
include_once dirname(__FILE__) . '/action_list.php';
include_once dirname(__FILE__) . '/grid_states/grid_states.php';

define('otAscending', 1);
define('otDescending', 2);

function GetOrderTypeAsSQL($orderType) {
    return $orderType == otAscending ? 'ASC' : 'DESC';
}

$orderTypeCaptions = array(
    otAscending => 'a',
    otDescending => 'd');

class SortColumn {

    private $fieldName;

    private $orderType;

    function __construct($fieldName, $orderType) {
        $this->fieldName = $fieldName;
        $this->orderType = $orderType;
    }

    public function getFieldName() {
        return $this->fieldName;
    }

    public function getSQLOrderType() {
        return $this->orderType;
    }

    public function getShortOrderType() {
        return $this->orderType == 'ASC' ? 'a' : 'd';
    }

    public function getOrderType() {
        return $this->orderType;
    }
}

class ViewMode
{
    const TABLE = 0;
    const CARD = 1;

    static function getDefaultMode()
    {
        return self::TABLE;
    }

    static function getList()
    {
        return array(
            self::TABLE => 'TableViewMode',
            self::CARD => 'CardViewMode'
        );
    }
}

class Grid {
    /** @var string */
    private $name;

    /** @var CustomEditColumn[] */
    private $editColumns;

    /** @var AbstractViewColumn[] */
    private $viewColumns;

    /** @var AbstractViewColumn[] */
    private $printColumns;

    /** @var CustomEditColumn[] */
    private $insertColumns;

    /** @var AbstractViewColumn[] */
    private $exportColumns;

    /** @var AbstractViewColumn[] */
    private $singleRecordViewColumns;

    /** @var IDataset */
    private $dataset;

    /** @var GridState */
    private $gridState;

    /** @var Page */
    private $page;

    /** @var bool */
    private $showAddButton;

    /** @var bool */
    private $showInlineAddButton;

    /** @var string */
    private $message;

    /** @var int */
    private $messageDisplayTime = 0;

    /** @var bool */
    private $allowDeleteSelected;
    //
    public $Width;
    public $Margin;

    //
    public $SearchControl;
    public $UseFilter;
    //
    private $orderColumnFieldName;
    private $orderType;

    /** @var SortColumn[] */
    private $sortedColumns;

    /** @var SortColumn[] */
    private $defaultSortedColumns;

    private $highlightRowAtHover;
    private $errorMessage;
    //
    public $OnDisplayText;
    //
    private $defaultOrderColumnFieldName;
    private $defaultOrderType;
    private $useImagesForActions;
    //
    /** @var GridBand[] */
    private $bands;
    /** @var GridBand */
    private $defaultBand;

    /** @var ActionList */
    private $actions;

    //
    private $editClientValidationScript;
    private $insertClientValidationScript;

    private $editClientFormLoadedScript;
    private $insertClientFormLoadedScript;
    private $editClientEditorValueChangedScript;
    private $insertClientEditorValueChangedScript;

    private $enabledInlineEditing;
    private $internalName;
    private $showUpdateLink = true;
    private $useFixedHeader;
    private $showLineNumbers;
    private $showKeyColumnsImagesInHeader;
    private $useModalInserting;
    private $width;

    private $enableRunTimeCustomization = true;
    private $viewMode;
    private $cardCountInRow;

    /** @var FilterBuilderControl */
    private $filterBuilder;

    /** @var QuickFilter */
    private $quickFilter;

    /** @var Aggregate[] */
    private $totals = array();

    /** @var bool */
    private $allowOrdering;

    /** @var bool */
    private $advancedSearchAvailable;

    /** @var Event */
    public $OnCustomRenderColumn;

    /** @var Event */
    public $OnCustomDrawCell;

    /** @var Event */
    public $BeforeShowRecord;

    /** @var Event */
    public $BeforeUpdateRecord;

    /** @var Event */
    public $BeforeInsertRecord;

    /** @var Event */
    public $BeforeDeleteRecord;

    /** @var Event */
    public $AfterUpdateRecord;

    /** @var Event */
    public $AfterInsertRecord;

    /** @var Event */
    public $AfterDeleteRecord;

    /** @var Event */
    public $OnBeforeDataChange;

    /** @var Event */
    public $OnCustomDrawCell_Simple;

    /** @var Event */
    public $OnCustomRenderTotal;

    /** @var Event */
    public $OnCustomRenderPrintColumn;

    /** @var Event */
    public $OnCustomRenderExportColumn;

    /** @var Event */
    public $OnGetCustomTemplate;

    /** @var DetailColumn[] */
    private $details;

    /** @var bool */
    private $showFilterBuilder;

    /** @var bool */
    private $tableBordered;

    /** @var bool */
    private $tableCondensed;

    function __construct($page, $dataset, $name) {
        $this->page = $page;
        $this->dataset = $dataset;
        $this->internalName = $name;
        //
        $this->editColumns = array();
        $this->viewColumns = array();
        $this->printColumns = array();
        $this->insertColumns = array();
        $this->exportColumns = array();
        $this->singleRecordViewColumns = array();
        $this->details = array();
        //
        $this->SearchControl = new NullComponent('Search');
        $this->UseFilter = false;
        //
        $this->showAddButton = false;
        //
        $this->OnCustomRenderTotal = new Event();
        $this->OnCustomDrawCell = new Event();
        $this->BeforeShowRecord = new Event();

        $this->BeforeUpdateRecord = new Event();
        $this->BeforeInsertRecord = new Event();
        $this->BeforeDeleteRecord = new Event();

        $this->AfterUpdateRecord = new Event();
        $this->AfterInsertRecord = new Event();
        $this->AfterDeleteRecord = new Event();


        $this->OnCustomDrawCell_Simple = new Event();
        $this->OnCustomRenderColumn = new Event();
        $this->OnBeforeDataChange = new Event();
        $this->OnDisplayText = new Event();
        $this->OnCustomRenderPrintColumn = new Event();
        $this->OnCustomRenderExportColumn = new Event();
        $this->OnGetCustomTemplate = new Event();

        //
        $this->SetState(OPERATION_VIEWALL);
        $this->allowDeleteSelected = false;
        $this->highlightRowAtHover = false;

        $this->defaultOrderColumnFieldName = null;
        $this->defaultOrderType = null;
        $this->sortedColumns = array();
        $this->defaultSortedColumns = array();

        $this->bands = array();
        $this->defaultBand = new GridBand('defaultBand', 'defaultBand');
        $this->bands[] = $this->defaultBand;
        $this->actions = new ActionList();

        //
        $this->useImagesForActions = true;
        $this->SetWidth(null);
        $this->SetEditClientValidationScript('');
        $this->SetInsertClientValidationScript('');

        $this->name = 'grid';
        $this->enabledInlineEditing = true;
        $this->useFixedHeader = false;
        $this->showLineNumbers = false;
        $this->showKeyColumnsImagesInHeader = true;
        $this->useModalInserting = false;
        $this->allowOrdering = true;
        $this->filterBuilder = new FilterBuilderControl($this, $this->GetPage()->GetLocalizerCaptions());
        $this->quickFilter = new QuickFilter(get_class($this->GetPage()), $this->GetPage(), $this->GetDataset());
        $this->advancedSearchAvailable = true;
        $this->showFilterBuilder = true;

        $this->viewMode = ViewMode::getDefaultMode();
        $this->cardCountInRow = 3;

        $this->tableBordered = false;
        $this->tableCondensed = false;
    }

    /**
     * @param string $columnName
     * @return \AbstractViewColumn|null
     */
    private function FindViewColumnByName($columnName) {
        $columns = $this->GetViewColumns();
        foreach ($columns as $column) {
            if ($this->GetColumnName($column) == $columnName) {
                return $column;
            }
        }
        return null;
    }

    public function UpdateSearchControls() {
        /** @var AdvancedSearchControl $advancedSearch  */
        $advancedSearch = $this->GetPage()->AdvancedSearchControl;
        if ($advancedSearch != null) {

            foreach ($advancedSearch->GetSearchColumns() as $searchColumn) {
                $columnName = $searchColumn->GetFieldName();
                $column = $this->FindViewColumnByName($columnName);
                /** @var Field $field */
                if (!StringUtils::IsNullOrEmpty($column))
                    $field = $this->dataset->GetFieldByName($column->GetName());
                else
                    $field = $this->dataset->GetFieldByName($columnName);

                if ($field && $searchColumn) {
                    if ($searchColumn instanceof LookupSearchColumn) {
                        $this->filterBuilder->AddField(
                            $searchColumn,
                            $searchColumn->GetFieldName(),
                            $searchColumn->GetCaption(),
                            $field->GetEngFieldType(),
                            'Select2Filter',
                            array(
                                'handler' => $searchColumn->GetHandlerName()
                            ));

                        $searchColumnViewData['Value'] = $searchColumn->GetDisplayValue();
                    } else if ($field instanceof DateTimeField || $field instanceof DateField) {

                        $formatOptions = array('fdow' => GetFirstDayOfWeek());
                        if (!StringUtils::IsNullOrEmpty($column) && ($column instanceof DateTimeViewColumn))
                            $formatOptions['format'] = $column->GetOSDateTimeFormat();

                        $this->filterBuilder->AddField(
                            $searchColumn,
                            $searchColumn->GetFieldName(),
                            $searchColumn->GetCaption(),
                            $field->GetEngFieldType(), null,
                            $formatOptions
                        );
                    } else {
                        $this->filterBuilder->AddField(
                            $searchColumn,
                            $searchColumn->GetFieldName(),
                            $searchColumn->GetCaption(),
                            $field->GetEngFieldType(), null, null);
                    }
                }

            }
        }
    }

    public function GetQuickFilter() {
        return $this->quickFilter;
    }

    public function GetFilterBuilder() {
        return $this->filterBuilder;
    }

    public function GetTemplate($mode, $defaultTemplate) {
        $template = '';
        $this->OnGetCustomTemplate->Fire(
            array($mode, &$template)
        );
        return ($template != '') ? $template : $defaultTemplate;
    }

    #region Options

    public function GetUseModalInserting() {
        return $this->useModalInserting;
    }

    public function SetUseModalInserting($value) {
        $this->useModalInserting = $value;
    }

    public function GetShowLineNumbers() {
        return $this->showLineNumbers;
    }

    public function SetShowLineNumbers($showLineNumbers) {
        $this->showLineNumbers = $showLineNumbers;
    }

    public function GetShowKeyColumnsImagesInHeader() {
        return $this->showKeyColumnsImagesInHeader;
    }

    public function SetShowKeyColumnsImagesInHeader($showKeyColumnsImagesInHeader) {
        $this->showKeyColumnsImagesInHeader = $showKeyColumnsImagesInHeader;
    }

    public function GetUseFixedHeader() {
        return $this->useFixedHeader;
    }

    public function SetUseFixedHeader($useFixedHeader) {
        $this->useFixedHeader = $useFixedHeader;
    }

    public function GetHighlightRowAtHover() {
        return $this->highlightRowAtHover;
    }

    public function SetHighlightRowAtHover($value) {
        $this->highlightRowAtHover = $value;
    }

    public function GetUseImagesForActions() {
        return $this->useImagesForActions;
    }

    public function SetUseImagesForActions($value) {
        $this->useImagesForActions = $value;
    }

    public function UseAutoWidth() {
        return !isset($this->width);
    }

    public function GetWidth() {
        return $this->width;
    }

    public function SetWidth($value) {
        $this->width = $value;
    }

    public function GetEditClientValidationScript() {
        return $this->editClientValidationScript;
    }

    public function GetInsertClientValidationScript() {
        return $this->insertClientValidationScript;
    }

    public function SetEditClientValidationScript($value) {
        $this->editClientValidationScript = $value;
    }

    public function SetInsertClientValidationScript($value) {
        $this->insertClientValidationScript = $value;
    }

    #endregion

    #region Session variables

    private function SetSessionVariable($name, $value) {
        GetApplication()->SetSessionVariable($this->GetName() . '_' . $name, $value);
    }

    private function UnSetSessionVariable($name) {
        GetApplication()->UnSetSessionVariable($this->GetName() . '_' . $name);
    }

    private function IsSessionVariableSet($name) {
        return GetApplication()->IsSessionVariableSet($this->GetName() . '_' . $name);
    }

    private function GetSessionVariable($name) {
        return GetApplication()->GetSessionVariable($this->GetName() . '_' . $name);
    }

    #endregion

    public function SetErrorMessage($value) {
        $this->errorMessage = $value;
    }

    public function GetErrorMessage() {
        return $this->errorMessage;
    }

    public function SetGridMessage($value) {
        $this->message = $value;
    }

    public function GetGridMessage() {
        return $this->message;
    }

    public function SetGridMessageDisplayTime($messageDisplayTime) {
        $this->messageDisplayTime = max(0, $messageDisplayTime);
    }

    public function GetGridMessageDisplayTime() {
        return $this->messageDisplayTime;
    }

    /**
     * @return Page
     */
    function GetPage() {
        return $this->page;
    }

    /**
     * @return IDataset
     */
    function GetDataset() {
        return $this->dataset;
    }

    function GetSingleRecordViewColumns() {
        return $this->singleRecordViewColumns;
    }

    #region Bands

    public function AddBand($bandName, $caption, $useConsolidatedHeader = false)
    {
        $band = new GridBand($bandName, $caption, $useConsolidatedHeader);
        $this->bands[] = $band;

        return $band;
    }

    public function AddBandToBegin($bandName, $caption, $useConsolidatedHeader = false)
    {
        $band = new GridBand($bandName, $caption, $useConsolidatedHeader);
        $this->bands = array_merge(array($band), $this->bands);

        return $band;
    }

    public function GetBandByName($name) {
        foreach ($this->bands as $band) {
            if ($band->GetName() == $name) {
                return $band;
            }
        }

        return null;
    }

    public function GetDefaultBand() {
        return $this->defaultBand;
    }

    public function GetViewBands() {
        return $this->bands;
    }

    /**
     * @return ActionList
     */
    public function getActions()
    {
        return $this->actions;
    }

    #endregion

    function CreateLinkBuilder() {
        return $this->GetPage()->CreateLinkBuilder();
    }

    function AddSingleRecordViewColumn($column) {
        $this->singleRecordViewColumns[] = $column;
        $this->DoAddColumn($column);
        return $column;
    }

    #region Columns

    /**
     * @param CustomEditColumn|AbstractViewColumn $column
     * @return void
     */
    private function DoAddColumn($column) {
        $column->SetGrid($this);
    }

    public function AddDetail($column) {
        $this->details[] = $column;
    }

    public function AddViewColumn($column, $bandName = null) {
        if ($column instanceof DetailColumn) {
            $this->AddDetail($column);
            $this->DoAddColumn($column);
            return $column;
        }

        $this->viewColumns[] = $column;
        $this->DoAddColumn($column);

        $band = $this->GetBandByName($bandName);
        if (!isset($band))
            $band = $this->GetDefaultBand();
        $band->AddColumn($column);

        return $column;
    }

    public function AddEditColumn($column) {
        $this->editColumns[] = $column;
        $this->DoAddColumn($column);
        return $column;
    }

    public function AddPrintColumn($column) {
        $this->printColumns[] = $column;
        $this->DoAddColumn($column);
        return $column;
    }

    public function AddInsertColumn($column) {
        $this->insertColumns[] = $column;
        $this->DoAddColumn($column);
        return $column;
    }

    public function AddExportColumn($column) {
        $this->exportColumns[] = $column;
        $this->DoAddColumn($column);
        return $column;
    }

    /**
     * @return CustomEditColumn[]
     */
    public function GetEditColumns() {
        return $this->editColumns;
    }

    /**
     * @return AbstractViewColumn[]
     */
    public function GetViewColumns() {
        return $this->viewColumns;
    }

    /**
     * @return array|AbstractViewColumn[]
     */
    public function GetPrintColumns() {
        return $this->printColumns;
    }

    /**
     * @return CustomEditColumn[]
     */
    public function GetInsertColumns() {
        return $this->insertColumns;
    }

    public function GetExportColumns() {
        return $this->exportColumns;
    }

    #endregion

    /**
     * @param \Renderer $renderer
     * @return void
     */
    public function Accept(Renderer $renderer) {
        $renderer->RenderGrid($this);
    }

    public function SetState($name)
    {
        $map = array(
            OPERATION_VIEW => 'SingleRecordGridState',
            OPERATION_PRINT_ONE => 'SingleRecordGridState',
            OPERATION_EDIT => 'EditGridState',
            OPERATION_VIEWALL => 'ViewAllGridState',
            OPERATION_COMMIT => 'CommitEditedValuesGridState',
            OPERATION_INSERT => 'InsertGridState',
            OPERATION_COPY => 'CopyGridState',
            OPERATION_COMMIT_INSERT => 'CommitInsertedValuesGridState',
            OPERATION_DELETE => 'SingleRecordGridState',
            OPERATION_COMMIT_DELETE => 'CommitDeleteGridState',
            OPERATION_DELETE_SELECTED => 'DeleteSelectedGridState',
            OPERATION_AJAX_REQUERT_INLINE_EDIT => 'OpenInlineEditorsGridState',
            OPERATION_AJAX_REQUERT_INLINE_EDIT_COMMIT => 'CommitInlineEditedValuesGridState',
            OPERATION_AJAX_REQUERT_INLINE_INSERT => 'OpenInlineInsertEditorsGridState',
            OPERATION_AJAX_REQUERT_INLINE_INSERT_COMMIT => 'CommitInlineInsertedValuesGridState',
            OPERATION_EXCEL_EXPORT_RECORD => 'SingleRecordGridState',
            OPERATION_WORD_EXPORT_RECORD => 'SingleRecordGridState',
            OPERATION_XML_EXPORT_RECORD => 'SingleRecordGridState',
            OPERATION_CSV_EXPORT_RECORD => 'SingleRecordGridState',
            OPERATION_PDF_EXPORT_RECORD => 'SingleRecordGridState',
        );

        if (isset($map[$name])) {
            $className = $map[$name];
            $this->gridState = new $className($this);
        }
    }

    /**
     * @return GridState
     */
    public function GetState() {
        return $this->gridState;
    }

    public function GetEditPageAction() {
        $linkBuilder = $this->CreateLinkBuilder();
        return $linkBuilder->GetLink();
    }

    public function GetOpenInsertModalDialogLink() {
        $linkBuilder = $this->CreateLinkBuilder();
        $linkBuilder->AddParameter(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->GetPage()->GetModalGridEditingHandler());
        $linkBuilder->AddParameter(ModalOperation::Param, ModalOperation::OpenModalInsertDialog);
        return $linkBuilder->GetLink();
    }

    public function GetModalInsertPageAction() {
        $linkBuilder = $this->CreateLinkBuilder();
        $linkBuilder->AddParameter(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->GetPage()->GetModalGridEditingHandler());
        return $linkBuilder->GetLink();
    }

    public function GetModalEditPageAction() {
        $linkBuilder = $this->CreateLinkBuilder();
        $linkBuilder->AddParameter(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->GetPage()->GetModalGridEditingHandler());
        return $linkBuilder->GetLink();
    }

    public function GetReturnUrl() {
        $linkBuilder = $this->CreateLinkBuilder();
        $linkBuilder->AddParameter(OPERATION_PARAMNAME, 'return');
        return $linkBuilder->GetLink();
    }

    #region Ordering

    public function GetOrderType() {
        return $this->orderType;
    }

    public function SetOrderType($value) {
        $this->orderType = $value;
    }

    public function setOrderBy($sortedColumns) {
        $this->sortedColumns = $sortedColumns;
    }

    public function setOrderByParameter($sortedColumns) {
        $newSortedColumns = array();
        foreach ($sortedColumns as $value) {
            $fieldName = urldecode(substr($value, 1, strlen($value) - 1));
            $orderType = $value[0] == 'a' ? 'ASC' : 'DESC';
            $newSortedColumns[] = new SortColumn($fieldName, $orderType);
        }
        $this->setOrderBy($newSortedColumns);
    }

    public function setDefaultOrdering($sortedColumns) {
        $this->defaultSortedColumns = $sortedColumns;
    }

    public function getSortedColumns() {
        return $this->sortedColumns;
    }

    private function ApplyDefaultOrder() {
        $this->setOrderBy($this->defaultSortedColumns);
    }

    /*
     * @param string $fieldName
     * return null|string
     */
    public function GetOrderTypeByFieldName($fieldName) {
        foreach ($this->sortedColumns as $value) {
            if ($value->getFieldName() == $fieldName) {
                return $value->getOrderType();
            }
        }

        return null;
    }

    public function getSortIndexByFieldName($fieldName)
    {
        foreach ($this->sortedColumns as $key => $value) {
            if ($value->getFieldName() == $fieldName) {
                return $key;
            }
        }

        return null;
    }

    public function getSortOrderTypeByFieldName($fieldName)
    {
        foreach ($this->sortedColumns as $key => $value) {
            if ($value->getFieldName() == $fieldName) {
                return $value->getOrderType();
            }
        }

        return null;
    }

    public function SetOrderColumnFieldName($value) {
        $this->orderColumnFieldName = $value;
    }

    private function ExtractOrderValues() {
        if (GetApplication()->IsGETValueSet('order')) {
            $orderValue = GetApplication()->GetGETValue('order');
            if (!is_array($orderValue)) {
                $orderValue = array($orderValue);
            }
            $this->setOrderByParameter($orderValue);
            $this->SetSessionVariable($this->internalName . '_sorting', $orderValue);
        } elseif (GetOperation() == 'resetorder') {
            $this->UnSetSessionVariable($this->internalName . '_sorting');
            $this->ApplyDefaultOrder();
        } elseif ($this->IsSessionVariableSet($this->internalName . '_orderColumnFieldName')) {
            // TODO: this condition was added to support version 14.10.0.7 where sorting was realized by one column only.
            // In that version field name and order type of sorted column saved to session in parameters .._orderColumnFieldName and.. _orderType respectively
            // if these parameters were set we use it for sorting one time, deleted it from session and saved sorted columns by a new way.
            $orderColumnFieldName = $this->GetSessionVariable($this->internalName . '_orderColumnFieldName');
            $orderType = $this->GetSessionVariable($this->internalName . '_orderType');

            $this->UnSetSessionVariable($this->internalName . '_orderColumnFieldName');
            $this->UnSetSessionVariable($this->internalName . '_orderType');

            $orderValue = array(substr($orderType, 0, 1) . $orderColumnFieldName);
            $this->setOrderByParameter($orderValue);
            $this->SetSessionVariable($this->internalName . '_sorting', $orderValue);
        } elseif ($this->IsSessionVariableSet($this->internalName . '_sorting')) {
            $sessionValue = $this->GetSessionVariable($this->internalName . '_sorting');
            $this->setOrderByParameter($sessionValue);
        } else {
            $this->ApplyDefaultOrder();
        }
    }

    #endregion

    public function ExtractViewMode() {
        $sessionVariableKey = $this->GetId() . 'viewmode';
        if (GetApplication()->IsGETValueSet('viewmode')) {
            $this->viewMode = GetApplication()->GetGETValue('viewmode') == ViewMode::CARD ? ViewMode::CARD : ViewMode::TABLE;
            GetApplication()->SetSessionVariable($sessionVariableKey, $this->viewMode);
        } elseif (GetApplication()->IsSessionVariableSet($sessionVariableKey)) {
            $this->viewMode = GetApplication()->GetSessionVariable($sessionVariableKey);
        }

        $sessionVariableKey = $this->GetId() . 'cardcountinrow';
        if (GetApplication()->IsGETValueSet('cardcountinrow')) {
            $this->cardCountInRow = (int) GetApplication()->GetGETValue('cardcountinrow');
            GetApplication()->SetSessionVariable($sessionVariableKey, $this->cardCountInRow);
        } elseif (GetApplication()->IsSessionVariableSet($sessionVariableKey)) {
            $this->cardCountInRow = GetApplication()->GetSessionVariable($sessionVariableKey);
        }
    }

    #region Buttons

    public function SetShowAddButton($value) {
        $this->showAddButton = $value;
    }

    public function GetShowAddButton() {
        return $this->showAddButton;
    }

    public function SetShowInlineAddButton($value) {
        $this->showInlineAddButton = $value;
    }

    public function GetShowInlineAddButton() {
        return $this->showInlineAddButton;
    }

    function GetPrintRecordLink() {
        $result = $this->CreateLinkBuilder();
        return $result->GetLink();
    }

    function GetInlineEditRequestsAddress() {
        $result = $this->CreateLinkBuilder();
        return $result->GetLink();
    }

    function GetDeleteSelectedLink() {
        $result = $this->CreateLinkBuilder();
        return $result->GetLink();
    }

    public function GetAddRecordLink() {
        $result = $this->CreateLinkBuilder();
        $result->AddParameter(OPERATION_PARAMNAME, OPERATION_INSERT);
        return $result->GetLink();
    }

    function GetUpdateLink() {
        return $this->CreateLinkBuilder()->GetLink();
    }

    function GetShowUpdateLink() {
        return $this->showUpdateLink;
    }

    function SetShowUpdateLink($value) {
        $this->showUpdateLink = $value;
    }

    function SetAllowDeleteSelected($value) {
        $this->allowDeleteSelected = $value;
    }

    function GetAllowDeleteSelected() {
        return $this->allowDeleteSelected;
    }

    #endregion

    function ProcessMessages() {
        $this->ExtractOrderValues();
        $this->ExtractViewMode();
        $this->SearchControl->ProcessMessages();
        $filterApplied = $this->filterBuilder->ProcessMessages();
        $this->quickFilter->ProcessMessages();
        $this->gridState->ProcessMessages();
        $this->restoreFlashMessageFromSession();

        if ($filterApplied) {
            $link = $this->GetPage()->CreateLinkBuilder();
            header('Location: ' . $link->GetLink());
            exit();
        }
    }

    private function restoreFlashMessageFromSession() {
        $session = ArrayWrapper::createSessionWrapperForDirectory();
        $id = get_class($this->getPage()) . '_message';

        if ($session->isValueSet($id)) {
            $this->SetGridMessage($session->getValue($id));
            $session->unsetValue($id);
        }

        if ($session->isValueSet($id . '_display_time')) {
            $this->SetGridMessageDisplayTime($session->getValue($id . '_display_time'));
            $session->unsetValue($id . '_display_time');
        }
    }

    public function setFlashMessage($message) {
        $session = ArrayWrapper::createSessionWrapperForDirectory();
        $session->setValue(get_class($this->getPage()) . '_message', $message);
        $session->setValue(get_class($this->getPage()) . '_message_display_time', $this->messageDisplayTime);
    }

    #region Utils

    private $internalStateSwitch = false;
    private $internalStateSwitchPrimaryKeys = array();

    function SetInternalStateSwitch($primaryKeys) {
        $this->internalStateSwitch = true;
        $this->internalStateSwitchPrimaryKeys = $primaryKeys;
    }

    function GetPrimaryKeyValuesFromGet() {
        if ($this->internalStateSwitch) {
            return $this->internalStateSwitchPrimaryKeys;
        } else {
            $primaryKeyValues = array();
            ExtractPrimaryKeyValues($primaryKeyValues, METHOD_GET);
            return $primaryKeyValues;
        }
    }

    #endregion

    public function GetName() {
        return $this->name;
    }

    public function SetName($value) {
        $this->name = $value;
    }

    public function SetEnabledInlineEditing($value) {
        $this->enabledInlineEditing = $value;
    }

    public function GetEnabledInlineEditing() {
        return $this->enabledInlineEditing;
    }

    #region Totals

    public function HasTotals() {
        return count($this->totals) > 0;
    }

    public function SetTotal(AbstractViewColumn $column, Aggregate $aggregate) {
        $this->totals[$column->GetName()] = $aggregate;
    }

    /**
     * @param AbstractViewColumn $column
     * @return Aggregate
     */
    public function GetAggregateFor(AbstractViewColumn $column) {
        return ArrayUtils::GetArrayValueDef($this->totals, $column->GetName());
    }

    public function GetTotalValues() {
        $command = new AggregationValuesQuery(
            $this->GetDataset()->GetSelectCommand(),
            $this->GetDataset()->GetCommandImp()
        );
        foreach ($this->totals as $columnName => $aggregate)
            $command->AddAggregate($columnName, $aggregate, $columnName);

        $result = array();
        $this->GetDataset()->GetConnection()->ExecQueryToArray(
            $command->GetSQL(), $result
        );
        return $result[0];
    }

    public function GetAllowOrdering() {
        return $this->allowOrdering;
    }

    public function SetAllowOrdering($value) {
        $this->allowOrdering = $value;
    }

    public function GetEditClientFormLoadedScript() {
        return $this->editClientFormLoadedScript;
    }

    public function SetEditClientFormLoadedScript($editClientFormLoadedScript) {
        $this->editClientFormLoadedScript = $editClientFormLoadedScript;
    }

    public function GetInsertClientFormLoadedScript() {
        return $this->insertClientFormLoadedScript;
    }

    public function SetInsertClientFormLoadedScript($insertClientFormLoadedScript) {
        $this->insertClientFormLoadedScript = $insertClientFormLoadedScript;
    }

    public function GetEditClientEditorValueChangedScript() {
        return $this->editClientEditorValueChangedScript;
    }

    public function SetEditClientEditorValueChangedScript($editClientEditorValueChangedScript) {
        $this->editClientEditorValueChangedScript = $editClientEditorValueChangedScript;
    }

    public function GetInsertClientEditorValueChangedScript() {
        return $this->insertClientEditorValueChangedScript;
    }

    public function SetInsertClientEditorValueChangedScript($insertClientEditorValueChangedScript) {
        $this->insertClientEditorValueChangedScript = $insertClientEditorValueChangedScript;
    }

    #endregion

    public function GetId() {
        return $this->internalName;
    }

    public function SetId($value) {
        $this->internalName = $value;
    }

    public function GetHiddenValues() {
        return $this->GetPage()->GetHiddenGetParameters();
    }

    public function GetHasDetails() {
        return count($this->details) > 0;
    }

    private function IsShowCurrentRecord() {
        $show = true;
        $this->BeforeShowRecord->Fire(array(&$show));
        return $show;
    }

    public function GetColumnName(AbstractViewColumn $column) {
        $dataset = $this->GetDataset();
        return $dataset->IsLookupField($column->GetName()) ?
            $dataset->IsLookupFieldNameByDisplayFieldName($column->GetName()) :
            $column->GetName();
    }

    public function RenderColumn(Renderer $renderer, AbstractViewColumn $column) {
        return $this->renderCell($renderer, $column, $this->GetDataset()->GetFieldValues());
    }

    public function renderCell(Renderer $renderer, AbstractViewColumn $column, $rowValues) {
        $handled = false;
        $defaultRenderingResult = $renderer->Render($column);
        $result = $defaultRenderingResult;
        $this->OnCustomRenderColumn->Fire(array(
            $this->GetColumnName($column),
            $column->GetValue(),
            $rowValues, &$result, &$handled));
        $result = $handled ? $result : $defaultRenderingResult;
        return $result;
    }

    public function renderExportCell(Renderer $renderer, AbstractViewColumn $column, $rowValues, $exportType) {
        $handled = false;
        $defaultRenderingResult = $renderer->Render($column);
        $result = $defaultRenderingResult;
        $this->OnCustomRenderExportColumn->Fire(array(
            $exportType,
            $this->GetColumnName($column),
            $column->GetValue(),
            $rowValues, &$result, &$handled));
        $result = $handled ? $result : $defaultRenderingResult;
        return $result;
    }

    private function GetStylesForColumn(Grid $grid, $rowData) {
        $cellFontColor = array();
        $cellFontSize = array();
        $cellBgColor = array();
        $cellItalicAttr = array();
        $cellBoldAttr = array();

        $grid->OnCustomDrawCell_Simple->Fire(array($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr));

        $result = array();
        $fieldNames = array_unique(array_merge(
            array_keys($cellFontColor),
            array_keys($cellFontSize),
            array_keys($cellBgColor),
            array_keys($cellItalicAttr),
            array_keys($cellBoldAttr)));

        $fieldStylesBuilder = new StyleBuilder();
        foreach ($fieldNames as $fieldName) {
            $fieldStylesBuilder->Clear();
            if (array_key_exists($fieldName, $cellFontColor))
                $fieldStylesBuilder->Add('color', $cellFontColor[$fieldName]);
            if (array_key_exists($fieldName, $cellFontSize))
                $fieldStylesBuilder->Add('font-size', $cellFontSize[$fieldName]);
            if (array_key_exists($fieldName, $cellBgColor))
                $fieldStylesBuilder->Add('background-color', $cellBgColor[$fieldName]);
            if (array_key_exists($fieldName, $cellItalicAttr))
                $fieldStylesBuilder->Add('font-style',
                    $cellItalicAttr[$fieldName] ? 'italic' : 'normal');
            if (array_key_exists($fieldName, $cellBoldAttr)) {
                $fieldStylesBuilder->Add('font-weight',
                    $cellBoldAttr[$fieldName] ? 'bold' : 'normal');
            }
            $result[$fieldName] = $fieldStylesBuilder->GetStyleString();
        }

        return $result;
    }

    public function GetRowStylesByColumn($rowValues, &$cellClasses) {
        $result = array();
        $cellCssStyles = array();
        $rowCssStyle = '';
        $rowClasses = '';
        $this->OnCustomDrawCell->Fire(array($rowValues, &$cellCssStyles, &$rowCssStyle, &$rowClasses, &$cellClasses));
        $cellCssStyles_Simple = $this->GetStylesForColumn($this, $rowValues);
        $cellCssStyles = array_merge($cellCssStyles_Simple, $cellCssStyles);

        foreach ($this->GetViewBands() as $band) {
            foreach ($band->GetColumns() as $column) {
                $columnName = $this->GetColumnName($column);

                if (array_key_exists($columnName, $cellCssStyles)) {
                    $styleBuilder = new StyleBuilder();
                    $styleBuilder->AddStyleString($rowCssStyle);
                    $styleBuilder->AddStyleString($cellCssStyles[$columnName]);
                    $result[$columnName] = $styleBuilder->GetStyleString();
                } else
                    $result[$columnName] = $rowCssStyle;
                if (!(array_key_exists($columnName, $cellClasses))) {
                   $cellClasses[$columnName] = '';
                }
            }
        }
        return $result;
    }

    private function GetRowStyles($rowValues, &$rowStyle, &$rowClasses) {
        $cellCssStyles = '';
        $cellClasses = array();
        $this->OnCustomDrawCell->Fire(array($rowValues, &$cellCssStyles, &$rowStyle, &$rowClasses, &$cellClasses));
    }

    private function GetRowsViewData(Renderer $renderer) {
        $result = array();
        $dataset = $this->GetDataset();

        $dataset->Open();
        $lineNumber = $this->GetStartLineNumber();
        while ($dataset->Next()) {
            if (!$this->IsShowCurrentRecord())
                continue;

            $rowViewData = array();

            $rowStyle = '';
            $rowClasses = '';
            $this->GetRowStyles($this->GetDataset()->GetFieldValues(), $rowStyle, $rowClasses);

            $cellClasses = array();
            $rowStyleByColumns = $this->GetRowStylesByColumn($this->GetDataset()->GetFieldValues(), $cellClasses);

            foreach ($this->GetViewBands() as $band) {
                foreach ($band->GetColumns() as $column) {

                    $columnName = $dataset->IsLookupField($column->GetName()) ?
                        $dataset->IsLookupFieldNameByDisplayFieldName($column->GetName()) :
                        $column->GetName();

                    $columnRenderResult = $this->RenderColumn($renderer, $column);

                    $rowViewData[$columnName] = array(
                        'ColumnName' => $column->GetName(),
                        'ColumnCaption' => $column->GetCaption(),
                        'Data' => $columnRenderResult,
                        'Value' => $column->getValue(),
                        'FieldName' => $columnName,
                        'Classes' => $column->GetGridColumnClass(),
                        'CellClasses' => $this->getEffectiveCellClasses($column->GetGridColumnClass(), $cellClasses[$columnName]),
                        'Style' => $rowStyleByColumns[$columnName]
                    );
                }
            }

            $actionsRowViewData = array();

            foreach ($this->getActions()->getOperations() as $operation) {
                $operationName = $dataset->IsLookupField($operation->GetName()) ?
                    $dataset->IsLookupFieldNameByDisplayFieldName($operation->GetName()) :
                    $operation->GetName();

                $actionsRowViewData[$operationName] = array(
                    'IconClass' => $operation->GetIconClassByOperationName(),
                    'OperationName' => $operationName,
                    'Data' => $operation->GetValue()
                );
            }

            $detailsViewData = array();
            foreach ($this->details as $detail) {
                $detailsViewData[] = $detail->GetViewData();
            }

            $result[] = array(
                'DataCells' => $rowViewData,
                'ActionsDataCells' => $actionsRowViewData,
                'LineNumber' => $lineNumber,
                'PrimaryKeys' => $dataset->GetPrimaryKeyValues(),
                'Style' => $rowStyle,
                'Classes' => $rowClasses,
                'Details' => array(
                    'Items' => $detailsViewData,
                    'JSON' => htmlspecialchars(SystemUtils::ToJSON($detailsViewData))
                )
            );
            $lineNumber++;
        }
        return $result;
    }

    private function getEffectiveCellClasses($columnClasses, $cellClasses) {
        $result = '';
        if ($this->GetViewMode() === ViewMode::TABLE) {
            StringUtils::AddStr($result, $columnClasses, ' ');
        }
        StringUtils::AddStr($result, $cellClasses, ' ');
        return $result;
    }

    private function GetTotalDataForColumn(AbstractViewColumn $column, $totalValues) {

        if (isset($totalValues[$column->GetName()])) {
            $aggregate = $this->GetAggregateFor($column)->AsString();

            $totalValue = $totalValues[$column->GetName()];
            $totalValue = is_numeric($totalValue) ? (float) $totalValue : $totalValue;

            $customTotalValue = '';
            $handled = false;
            $this->OnCustomRenderTotal->Fire(array($totalValue, $aggregate, $column->GetName(), &$customTotalValue, &$handled));

            if ($handled) {
                return $customTotalValue;
            }

            if (is_numeric($totalValue)) {
                $totalValue = number_format($totalValue, 2);
            }

            return StringUtils::Format('%s = %s', $aggregate, $totalValue);
        }
        return '';
    }

    private function GetTotalsViewData() {
        if ($this->HasTotals()) {
            $result = array();
            $totalValues = $this->GetTotalValues();
            foreach ($this->GetViewBands() as $band) {
                foreach ($band->GetColumns() as $column) {
                    $result[] = array(
                        'Classes' => $column->GetGridColumnClass(),
                        'Caption' => $column->getCaption(),
                        'Value' => $this->GetTotalDataForColumn($column, $totalValues)
                    );
                }
            }
            return $result;
        }
        return null;
    }

    private function GetStartLineNumber() {
        $startLineNumber = 1;
        $paginationControl = $this->GetPage()->GetPaginationControl();
        if (isset($paginationControl)) {
            $startLineNumber =
                ($paginationControl->CurrentPageNumber() - 1) * ($paginationControl->GetRowsPerPage()) + 1;
        }
        return $startLineNumber;
    }

    private function GetAdditionalAttributes() {
        $result = new AttributesBuilder();
        if ($this->GetShowLineNumbers()) {
            $result->AddAttrValue('data-start-line-number', $this->GetStartLineNumber());
        }
        $result->AddAttrValue('data-delete-selected-action', $this->GetDeleteSelectedLink(), true);
        $result->AddAttrValue('data-grid-quick-filter-value', $this->GetQuickFilter()->GetValue(), true);
        return $result->GetAsString();
    }

    public function GetAdvancedSearchAvailable() {
        return $this->advancedSearchAvailable;
    }

    public function SetAdvancedSearchAvailable($value) {
        $this->advancedSearchAvailable = $value;
    }

    private function RequestFilterFromUser() {
        return
            $this->GetPage()->OpenAdvancedSearchByDefault() &&
            (
                ($this->GetPage()->AdvancedSearchControl && !$this->GetPage()->AdvancedSearchControl->HasCondition()) &&
                ($this->GetFilterBuilder() && ($this->GetFilterBuilder()->IsEmpty() || !$this->GetFilterBuilder()->isEnabled()))
            );
    }

    private function GetHiddenValuesJson() {
        return SystemUtils::ToJSON($this->GetHiddenValues());
    }

    public function GetViewData(Renderer $renderer) {
        $bandsViewData = array();
        foreach ($this->GetViewBands() as $band) {
            $bandsViewData[] = $band->GetViewData();
        }
        $actionsViewData = $this->getActions()->getViewData();

        $rows = array();
        $emptyGridMessage = $this->GetPage()->GetLocalizerCaptions()->GetMessageString('NoDataToDisplay');
        if ($this->RequestFilterFromUser()) {
            $emptyGridMessage = $this->GetPage()->GetLocalizerCaptions()->GetMessageString('CreateFilterConditionFirst');
        } else {
            $rows = $this->GetRowsViewData($renderer);
        }

        $sortableColumns = array();
        $sortableColumnsForJSON = array();
        foreach($bandsViewData as $band) {
            foreach($band['Columns'] as $column) {
                if ($column['Sortable']) {
                    $sortableColumn = array(
                        'name' => $column['Name'],
                        'index' => $column['SortIndex'],
                        'caption' => $column['Caption']
                    );
                    $sortableColumns[$column['Name']] = $sortableColumn;
                    $sortableColumnsForJSON[$column['Name']] = array_merge($sortableColumn, array(
                        'caption' => StringUtils::ConvertTextToEncoding($column['Caption'], $this->getPage()->getContentEncoding(), 'UTF-8')
                    ));
                }
            }
        }

        return array(
            'SortableColumns' => $sortableColumns,
            'SortableColumnsJSON' => SystemUtils::ToJSON($sortableColumnsForJSON),

            'Id' => $this->GetId(),
            'MaxWidth' => $this->GetWidth(),
            'Classes' => $this->GetGridClasses(),
            'Attributes' => $this->GetAdditionalAttributes(),

            'HiddenValuesJson' => $this->GetHiddenValuesJson(),

            'EmptyGridMessage' => $emptyGridMessage,

            // Filter builder
            'FilterBuilder' => $this->GetShowFilterBuilder() ?
                $this->GetFilterBuilder()->GetViewData() :
                null,

            // Quick filter
            'QuickFilter' => $this->GetQuickFilter()->GetViewData(),
            'AllowQuickFilter' => $this->GetPage()->GetSimpleSearchAvailable() && $this->UseFilter,

            // Action panel
            'ActionsPanelAvailable' =>
            ($this->GetPage()->GetSimpleSearchAvailable() && $this->UseFilter) ||
                ($this->GetShowAddButton()) ||
                ($this->GetShowInlineAddButton()) ||
                ($this->GetAllowDeleteSelected()) ||
                ($this->GetShowUpdateLink()),

            'Links' => array(
                'ModalInsertDialog' => $this->GetOpenInsertModalDialogLink(),
                'InlineEditRequest' => $this->GetInlineEditRequestsAddress(),
                'SimpleAddNewRow' => $this->GetAddRecordLink(),
                'Refresh' => $this->GetUpdateLink()
            ),

            'ActionsPanel' => array(
                'InlineAdd' => $this->GetShowInlineAddButton(),
                'AddNewButton' => $this->GetShowAddButton() ? ($this->GetUseModalInserting() ? 'modal' : 'simple') : null,
                'RefreshButton' => $this->GetShowUpdateLink(),
                'DeleteSelectedButton' => $this->GetAllowDeleteSelected()
            ),

            'ColumnCount' => count($this->GetViewColumns()) +
                ($this->GetAllowDeleteSelected() ? 1 : 0) +
                ($this->GetShowLineNumbers() ? 1 : 0) +
                ($this->GetHasDetails() ? 1 : 0) +
                ($actionsViewData ? 1 : 0),
            'Bands' => $bandsViewData,
            'Actions' => $actionsViewData,

            'HasDetails' => $this->GetHasDetails(),
            'UseInlineEdit' => $this->GetEnabledInlineEditing(),
            'HighlightRowAtHover' => $this->GetHighlightRowAtHover(),

            'AllowDeleteSelected' => $this->GetAllowDeleteSelected(),

            'ShowLineNumbers' => $this->GetShowLineNumbers(),

            'Rows' => $rows,
            'Totals' => $this->GetTotalsViewData(),

            'GridMessage' => $this->GetGridMessage() == '' ? null : $this->GetGridMessage(),
            'ErrorMessage' => $this->GetErrorMessage() == '' ? null : $this->GetErrorMessage(),
            'MessageDisplayTime' => $this->GetGridMessageDisplayTime(),

            'DataSortPriority' => $this->getSortedColumns(),

            'EnableRunTimeCustomization' => $this->getEnableRunTimeCustomization(),
            'ViewModeList' => ViewMode::getList(),
            'ViewMode' => $this->GetViewMode(),
            'CardCountInRow' => $this->GetCardCountInRow(),
            'CardClasses' => $this->getCardClasses(),

            'TableIsBordered' => $this->isTableBordered(),
            'TableIsCondensed' => $this->isTableCondensed()
        );
    }

    private function GetColumnViewData(CustomEditColumn $column, Renderer $renderer)
    {
        return array(
            'FieldName' => $column->GetFieldName(),
            'Id' => $column->GetEditControl()->GetName(),
            'Editor' => $renderer->Render($column),
            'Caption' => $column->GetCaption(),
            'Required' => $column->DisplayAsRequired(),
            'DisplaySetToNullCheckBox' => $column->GetDisplaySetToNullCheckBox(),
            'DisplaySetToDefaultCheckBox' => $column->GetDisplaySetToDefaultCheckBox(),
            'IsValueNull' => $column->IsValueNull(),
            'IsValueSetToDefault' => $column->IsValueSetToDefault(),
            'SetNullCheckBoxName' => $column->GetFieldName() . '_null',
            'SetDefaultCheckBoxName' => $column->GetFieldName() . '_def'
        );
    }

    private function GetEditColumnViewData(Renderer $renderer) {
        $result = array();
        foreach ($this->GetEditColumns() as $column) {
            $result[$column->GetFieldName()] = $this->GetColumnViewData($column, $renderer);
            $result[$column->GetFieldName()]['Value'] = $column->GetValue();
        }
        return $result;
    }

    public function GetInsertColumnViewData(Renderer $renderer) {
        $result = array();
        foreach ($this->GetInsertColumns() as $column) {
            $result[$column->GetFieldName()] = $this->GetColumnViewData($column, $renderer);
        }
        return $result;
    }

    public function GetInsertViewData(Renderer $renderer) {

        $detailViewData = array();
        foreach ($this->details as $detail) {
            $linkBuilder = $this->CreateLinkBuilder();
            $detail->DecorateLinkForPostMasterRecord($linkBuilder);

            $detailViewData[] = array(
                'Link' => $linkBuilder->GetLink(),
                'SeperatedPageLink' => $detail->GetSeparateViewLink(),
                'Caption' => $detail->GetCaption()
            );
        }

        return array(
            'OnLoadScript' => $this->GetInsertClientFormLoadedScript(),
            'Details' => $detailViewData,
            'Message' => $this->GetGridMessage(),
            'ErrorMessage' => $this->GetErrorMessage(),
            'MessageDisplayTime' => $this->GetGridMessageDisplayTime(),
            'FormAction' => $this->GetEditPageAction(),
            'Title' => $this->resolveFormTitle(
                $this->GetPage()->GetTitle(),
                $this->GetPage()->GetInsertFormTitle(),
                $this->getFormColumnsReplacements($this->GetInsertColumns())
            ),
            'Columns' => $this->GetInsertColumnViewData($renderer),
            'CancelUrl' => $this->GetReturnUrl(),
            'ClientValidationScript' => $this->GetInsertClientValidationScript()
        );
    }

    public function GetModalInsertViewData(Renderer $renderer) {
        $result = $this->GetInsertViewData($renderer);
        $result['FormAction'] = $this->GetModalInsertPageAction();
        $result['Title'] = $this->resolveFormTitle(
            $this->GetPage()->GetLocalizerCaptions()->GetMessageString('AddNewRecord'),
            $this->GetPage()->GetInsertFormTitle(),
            $this->getFormColumnsReplacements($this->GetInsertColumns())
        );
        return $result;
    }

    public function GetEditViewData(Renderer $renderer) {
        $detailViewData = array();
        foreach ($this->details as $detail) {
            $linkBuilder = $this->CreateLinkBuilder();
            $detail->DecorateLinkForPostMasterRecord($linkBuilder);

            $detailViewData[] = array(
                'Link' => $linkBuilder->GetLink(),
                'SeperatedPageLink' => $detail->GetSeparateViewLink(),
                'Caption' => $detail->GetCaption()
            );
        }

        return array(
            'OnLoadScript' => $this->GetEditClientFormLoadedScript(),
            'Details' => $detailViewData,
            'Title' => $this->resolveFormTitle(
                $this->GetPage()->GetTitle(),
                $this->GetPage()->GetEditFormTitle(),
                $this->getFormColumnsReplacements($this->GetEditColumns())
            ),
            'FormAction' => $this->GetEditPageAction(),
            'Message' => $this->GetGridMessage(),
            'ErrorMessage' => $this->GetErrorMessage(),
            'MessageDisplayTime' => $this->GetGridMessageDisplayTime(),
            'CancelUrl' => $this->GetReturnUrl(),
            'Columns' => $this->GetEditColumnViewData($renderer)
        );
    }


    public function GetModalEditViewData(Renderer $renderer) {
        $result = $this->GetEditViewData($renderer);
        $result['FormAction'] = $this->GetModalEditPageAction();
        $result['Title'] = $this->resolveFormTitle(
            $this->GetPage()->GetLocalizerCaptions()->GetMessageString('Edit'),
            $this->GetPage()->GetEditFormTitle(),
            $this->getFormColumnsReplacements($this->GetInsertColumns())
        );
        return $result;
    }

    public function GetViewSingleRowColumnViewData(Renderer $renderer) {
        $Row = array();
        $rowValues = $this->GetDataset()->GetFieldValues();
        foreach ($this->GetSingleRecordViewColumns() as $Column) {
            $columnName = $this->GetColumnName($Column);
            $columnRenderResult = $this->renderCell($renderer, $Column, $rowValues);

            $Row[$columnName] = array(
                'Caption' => $Column->GetCaption(),
                'Value' => $Column->getValue(),
                'DisplayValue' => $columnRenderResult,
            );
        }
        return $Row;
    }

    public function GetExportSingleRowColumnViewData(Renderer $renderer, $exportType) {
        $Row = array();
        $rowValues = $this->GetDataset()->GetFieldValues();
        foreach ($this->GetExportColumns() as $Column) {
            $columnName = $this->GetColumnName($Column);
            $columnRenderResult = $this->renderExportCell($renderer, $Column, $rowValues, $exportType);

            $Row[$columnName] = array(
                'Caption' => $Column->GetCaption(),
                'Value' => $Column->getValue(),
                'DisplayValue' => $columnRenderResult
            );
        }

        return $Row;
    }

    public function GetExportSingleRowViewData(Renderer $renderer, $exportType)
    {
        $this->GetDataset()->Open();

        if ($this->GetDataset()->Next()) {
            $primaryKeyMap = $this->GetDataset()->GetPrimaryKeyValuesMap();
            $titleReplacements = array();
            foreach ($this->GetExportColumns() as $column) {
                $titleReplacements['%' . $this->GetColumnName($column) . '%'] = $column->getValue();
            }

            return array(
                'Title' => $this->resolveFormTitle(
                    $this->GetPage()->GetTitle(),
                    $this->GetPage()->GetViewFormTitle(),
                    $titleReplacements
                ),
                'PrimaryKeyMap' => $primaryKeyMap,
                'Row' => $this->GetExportSingleRowColumnViewData($renderer, $exportType)
            );

        } else {
            RaiseCannotRetrieveSingleRecordError();
            return null;
        }
    }

    public function GetViewSingleRowViewData(Renderer $renderer) {

        $detailViewData = array();
        $this->GetDataset()->Open();
        $linkBuilder = null;
        if ($this->GetDataset()->Next()) {
            $linkBuilder = $this->CreateLinkBuilder();
            $linkBuilder->AddParameter(OPERATION_PARAMNAME, OPERATION_PRINT_ONE);

            $keyValues = $this->GetDataset()->GetPrimaryKeyValues();
            for ($i = 0; $i < count($keyValues); $i++)
                $linkBuilder->AddParameter("pk$i", $keyValues[$i]);

            $primaryKeyMap = $this->GetDataset()->GetPrimaryKeyValuesMap();

            foreach ($this->details as $detail) {

                $detailViewData[] = array(
                    'Link' => $detail->GetSeparateViewLink(),
                    'Caption' => $detail->GetCaption()
                );
            }

            $titleReplacements = array();
            foreach ($this->GetSingleRecordViewColumns() as $column) {
                $titleReplacements['%' . $this->GetColumnName($column) . '%'] = $column->getValue();
            }

            return array(
                'Details' => $detailViewData,
                'HasEditGrant' => $this->allowDisplayEditButtonOnViewForm(),
                'CancelUrl' => $this->GetReturnUrl(),
                'EditUrl' => $this->GetEditCurrentRecordLink($keyValues),
                'PrintOneRecord' => $this->GetPage()->GetPrintOneRecordAvailable(),
                'PrintRecordLink' => $linkBuilder->GetLink(),
                'ExportButtons' => $this->GetPage()->getExportOneRecordButtonsViewData($keyValues),
                'Title' => $this->resolveFormTitle(
                    $this->GetPage()->GetTitle(),
                    $this->GetPage()->GetViewFormTitle(),
                    $titleReplacements
                ),
                'PrimaryKeyMap' => $primaryKeyMap,
                'Row' => $this->GetViewSingleRowColumnViewData($renderer),
            );

        } else {
            RaiseCannotRetrieveSingleRecordError();
            return null;
        }
    }

    public function getModalViewSingleRowViewData(Renderer $renderer)
    {
        return array_merge($this->GetViewSingleRowViewData($renderer), array(
            'Title' => $this->resolveFormTitle(
                $this->GetPage()->GetLocalizerCaptions()->GetMessageString('View'),
                $this->GetPage()->GetViewFormTitle(),
                $this->getViewColumnsReplacements($this->GetSingleRecordViewColumns())
            ),
        ));
    }

    private function getViewColumnsReplacements($columns)
    {
        $replacements = array();

        foreach ($columns as $column) {
            $replacements['%' . $this->GetColumnName($column) . '%'] = $column->getValue();
        }

        return $replacements;
    }

    private function getFormColumnsReplacements($columns)
    {
        $replacements = array();

        foreach ($columns as $column) {
            $column->SetControlValuesFromDataset();
            $replacements['%' . $column->GetFieldName() . '%'] = $column->GetEditControl()->GetDisplayValue();
        }

        return $replacements;
    }

    private function resolveFormTitle($defaultTitle, $title, $replacements)
    {
        if (is_null($title)) {
            $title = $defaultTitle;
        }

        return str_replace(array_keys($replacements), array_values($replacements), $title);
    }

    /**
     * @return boolean
     */
    private function allowDisplayEditButtonOnViewForm() {
        return
            $this->actions->hasEditOperation() &&
            $this->hasEditColumns() &&
            $this->hasEditPermission();
    }

    private function hasEditColumns() {
        return count($this->editColumns) > 0;
    }

    private function hasEditPermission() {
        return
            $this->GetPage()->GetSecurityInfo()->HasEditGrant() &&
            (is_null($this->GetPage()->GetRecordPermission()) || $this->GetPage()->GetRecordPermission()->HasEditGrant($this->GetDataset()));
    }

    public function SetShowFilterBuilder($value) {
        return $this->showFilterBuilder = $value;
    }

    public function GetShowFilterBuilder() {
        return $this->showFilterBuilder && $this->GetAdvancedSearchAvailable() && $this->GetPage()->GetAdvancedSearchAvailable();
    }

    public function GetDetailLinksViewData() {
        $result = array();
        foreach ($this->details as $detail) {
            $result[] = array(
                'Caption' => $detail->GetCaption(),
                'Link' => $detail->GetSeparateViewLink(),
                'Name' => $detail->GetSeparatePageHandlerName(),
            );
        }
        return $result;
    }

    public function GetDetailsViewData()
    {
        $result = array();
        foreach ($this->details as $detail) {
            $result[] = $detail->GetViewData();
        }

        return $result;
    }

    public function FindDetail($detailEditHandlerName) {
        foreach ($this->details as $detail) {
            if ($detail->GetSeparatePageHandlerName() == $detailEditHandlerName)
                return $detail;
        }
        return null;
    }

    private function GetLinkParametersForPrimaryKey($primaryKeyValues) {
        $result = array();
        $keyValues = $primaryKeyValues;
        for ($i = 0; $i < count($keyValues); $i++)
            $result["pk$i"] = $keyValues[$i];
        return $result;
    }

    public function GetEditCurrentRecordLink($primaryKeyValues) {
        $linkBuilder = $this->CreateLinkBuilder();
        $linkBuilder->AddParameter(OPERATION_PARAMNAME, OPERATION_EDIT);
        $linkBuilder->AddParameters($this->GetLinkParametersForPrimaryKey($primaryKeyValues));
        return $linkBuilder->GetLink();
    }

    private function GetGridClasses() {
        $result = '';

        StringUtils::AddStr($result, 'table-striped', ' ');

        if ($this->GetHighlightRowAtHover()) {
            StringUtils::AddStr($result, 'table-hover', ' ');
        }

        if ($this->GetUseFixedHeader()) {
            StringUtils::AddStr($result, 'fixed-header', ' ');
        }

        return $result;
    }

    public function SetViewMode($value) {
        $this->viewMode = $value;
    }

    public function GetViewMode() {
        return $this->viewMode;
    }

    public function setEnableRunTimeCustomization($value)
    {
        $this->enableRunTimeCustomization = (bool) $value;
        return $this;
    }

    public function getEnableRunTimeCustomization()
    {
        return $this->enableRunTimeCustomization;
    }

    public function SetCardCountInRow($value) {
        $this->cardCountInRow = $value;
    }

    public function GetCardCountInRow() {
        return $this->cardCountInRow;
    }

    public function GetAvailableCardCountInRow() {
        return array('1', '2', '3', '4', '6');
    }

    private function getCardClasses() {
        $threeCardClasses = 'col-md-4 col-sm-6 col-xs-12';
        switch ($this->cardCountInRow) {
            case 1: return 'col-xs-12';
            case 2: return 'col-sm-6 col-xs-12';
            case 3: return $threeCardClasses;
            case 4: return 'col-lg-3 col-md-4 col-sm-6 col-xs-12';
            case 6: return 'col-lg-2 col-md-4 col-sm-6 col-xs-12';
            default: return $threeCardClasses;
        }
    }

    /**
     * @return boolean
     */
    public function isTableBordered()
    {
        return $this->tableBordered;
    }

    /**
     * @param boolean $value
     */
    public function setTableBordered($value)
    {
        $this->tableBordered = $value;
    }

    /**
     * @return boolean
     */
    public function isTableCondensed()
    {
        return $this->tableCondensed;
    }

    /**
     * @param boolean $value
     */
    public function setTableCondensed($value)
    {
        $this->tableCondensed = $value;
    }
}

