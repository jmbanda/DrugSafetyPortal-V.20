<?php

include_once dirname(__FILE__) . '/' . '../libs/smartylibs/Smarty.class.php';

include_once dirname(__FILE__) . '/' . '../database_engine/insert_command.php';
include_once dirname(__FILE__) . '/' . '../database_engine/update_command.php';
include_once dirname(__FILE__) . '/' . '../database_engine/select_command.php';
include_once dirname(__FILE__) . '/' . '../database_engine/delete_command.php';

include_once dirname(__FILE__) . '/' . 'captions.php';
include_once dirname(__FILE__) . '/' . 'env_variables.php';

include_once dirname(__FILE__) . '/' . 'charts/chart.php';
include_once dirname(__FILE__) . '/' . 'charts/chart_position.php';

include_once dirname(__FILE__) . '/' . 'grid/grid.php';
include_once dirname(__FILE__) . '/' . 'grid/columns.php';
include_once dirname(__FILE__) . '/' . 'grid/operation_columns.php';
include_once dirname(__FILE__) . '/' . 'grid/edit_columns.php';

include_once dirname(__FILE__) . '/' . 'dataset/dataset.php';
include_once dirname(__FILE__) . '/' . 'dataset/table_dataset.php';
include_once dirname(__FILE__) . '/' . 'dataset/query_dataset.php';

include_once dirname(__FILE__) . '/' . 'renderers/renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/edit_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/list_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/view_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/print_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/insert_renderer.php';

include_once dirname(__FILE__) . '/' . 'renderers/excel_list_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/word_list_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/xml_list_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/csv_list_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/pdf_list_renderer.php';

include_once dirname(__FILE__) . '/' . 'renderers/excel_record_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/word_record_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/xml_record_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/csv_record_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/pdf_record_renderer.php';

include_once dirname(__FILE__) . '/' . 'renderers/inline_operation_renderers.php';
include_once dirname(__FILE__) . '/' . 'renderers/rss_renderer.php';

include_once dirname(__FILE__) . '/' . 'common.php';
include_once dirname(__FILE__) . '/' . 'common_page.php';
include_once dirname(__FILE__) . '/' . 'page_navigator.php';
include_once dirname(__FILE__) . '/' . 'simple_search_control.php';
include_once dirname(__FILE__) . '/' . 'advanced_search_page.php';
include_once dirname(__FILE__) . '/' . 'page_list.php';
include_once dirname(__FILE__) . '/' . 'dataset_rss_generator.php';
include_once dirname(__FILE__) . '/' . 'error_utils.php';
include_once dirname(__FILE__) . '/' . 'superglobal_wrapper.php';
include_once dirname(__FILE__) . '/' . 'utils/array_utils.php';

include_once dirname(__FILE__) . '/' . 'security/security_info.php';

include_once dirname(__FILE__) . '/' . 'application.php';

define('OPERATION_HTTPHANDLER_NAME_PARAMNAME', 'hname');
define('OPERATION_PARAMNAME', 'operation');

define('OPERATION_VIEW', 'view');
define('OPERATION_EDIT', 'edit');
define('OPERATION_INSERT', 'insert');
define('OPERATION_COPY', 'copy');
define('OPERATION_DELETE', 'delete');
define('OPERATION_VIEWALL', 'viewall');
define('OPERATION_COMMIT', 'commit');
define('OPERATION_COMMIT_INSERT', 'commit_new');
define('OPERATION_COMMIT_DELETE', 'commit_delete');
define('OPERATION_PRINT_ALL', 'printall');
define('OPERATION_PRINT_PAGE', 'printpage');
define('OPERATION_PRINT_ONE', 'printrec');
define('OPERATION_DELETE_SELECTED', 'delsel');

define('OPERATION_EXCEL_EXPORT', 'eexcel');
define('OPERATION_WORD_EXPORT', 'eword');
define('OPERATION_XML_EXPORT', 'exml');
define('OPERATION_CSV_EXPORT', 'ecsv');
define('OPERATION_PDF_EXPORT', 'epdf');

define('OPERATION_EXCEL_EXPORT_RECORD', 'eexcel_record');
define('OPERATION_WORD_EXPORT_RECORD', 'eword_record');
define('OPERATION_XML_EXPORT_RECORD', 'exml_record');
define('OPERATION_CSV_EXPORT_RECORD', 'ecsv_record');
define('OPERATION_PDF_EXPORT_RECORD', 'epdf_record');

define('OPERATION_AJAX_REQUERT_INLINE_EDIT', 'arqie');
define('OPERATION_AJAX_REQUERT_INLINE_INSERT', 'arqii');
define('OPERATION_AJAX_REQUERT_INLINE_EDIT_COMMIT', 'arqiec');
define('OPERATION_AJAX_REQUERT_INLINE_INSERT_COMMIT', 'arqiic');
define('OPERATION_ADVANCED_SEARCH', 'advsrch');

define('OPERATION_RSS', 'rss');

define('OPERATION_HTTPHANDLER_REQUEST', 'httphandler');

include_once dirname(__FILE__) . '/' . 'grid/vertical_grid.php';
include_once dirname(__FILE__) . '/' . 'grid/modal_edit_handler.php';

class PagePart {
    const Grid = 'grid';
    const GridRow = 'grid-row';
    const VerticalGrid = 'vertical-grid';
    const PageList = 'page-list';
    const Layout = 'layout';
    const PrintLayout = 'print-layout';
    const RecordCard = 'record-card';
    const LoginPage = 'login';
    const LoginControl = 'login-control';
}

class PageMode {
    const ViewAll = 'view-all';
    const View = 'view';
    const Edit = 'edit';
    const Insert = 'insert';
    const ModalView = 'modal-view';
    const ModalEdit = 'modal-edit';
    const ModalInsert = 'modal-insert';
    const PrintAll = 'print-all';
    const PrintOneRecord = 'print-one-record';
    const PrintDetailPage = 'print-detail-page';
    const ExportPdf = 'export-pdf';
    const ExportCsv = 'export-csv';
    const ExportExcel = 'export-excel';
    const ExportWord = 'export-word';
    const ExportXml = 'export-xml';
}

class Modal {
    const SIZE_SM = 'modal-sm';
    const SIZE_MD = 'modal-md';
    const SIZE_LG = 'modal-lg';
}

function GetOperation()
{
    return GetApplication()->GetOperation();
}

abstract class Page extends CommonPage implements IVariableContainer
{
    private $pageFileName;
    /** @var Renderer */
    protected $renderer;
    private $httpHandlerName;
    /** @var IDataSourceSecurityInfo */
    private $securityInfo;

    /**
     * @var IRecordPermissions
     */
    private $recordPermission;

    private $message;
    private $errorMessage;
    private $columnVariableContainer;

    #region Page parts
    /** @var Dataset */
    protected $dataset;

    /** @var Grid */
    private $grid;

    /** @var AbstractPageNavigator */
    private $pageNavigator;
    /** @var DatasetRssGenerator */
    private $rssGenerator;

    /** @var AdvancedSearchControl */
    public $AdvancedSearchControl;
    private $pageNavigatorStack;
    #endregion

    #region Option fields
    private $gridHeader;

    private $menuLabel;
    private $editFormTitle;
    private $viewFormTitle;
    private $insertFormTitle;
    private $showUserAuthBar = false;
    private $showTopPageNavigator = true;
    private $showBottomPageNavigator = false;
    private $showPageList;
    private $hidePageListByDefault;

    private $printListAvailable = true;
    private $printListRecordAvailable = false;
    private $printOneRecordAvailable = true;

    private $exportListAvailable = array('excel', 'pdf', 'csv', 'xml', 'word');
    private $exportListRecordAvailable = array();
    private $exportOneRecordAvailable = array('excel', 'pdf', 'csv', 'xml', 'word');

    private $simpleSearchAvailable;
    private $advancedSearchAvailable;
    private $visualEffectsEnabled;
    public $Margin;
    public $Padding;
    private $detailedDescription;

    private $modalFormSize = Modal::SIZE_MD;
    private $modalViewSize = Modal::SIZE_MD;
    #endregion
    #
    private $charts;

    #region Events
    public $BeforePageRender;
    public $OnCustomHTMLHeader;
    public $OnPageLoadedClientScript;
    public $OnGetCustomTemplate;
    public $OnPrepareChart;
    #endregion

    #region IVariableContainer implementation
    private $variableFuncs = array(
        'PAGE_SHORT_CAPTION'    => 'return $page->GetMenuLabel();',
        'PAGE_CAPTION'          => 'return $page->GetTitle();',
        'PAGE_CSV_EXPORT_LINK'  => 'return $page->GetExportToCsvLink();',
        'PAGE_XLS_EXPORT_LINK'  => 'return $page->GetExportToExcelLink();',
        'PAGE_PDF_EXPORT_LINK'  => 'return $page->GetExportToPdfLink();',
        'PAGE_XML_EXPORT_LINK'  => 'return $page->GetExportToXmlLink();',
        'PAGE_WORD_EXPORT_LINK' => 'return $page->GetExportToWordLink();'
        );

    #region ViewData


    public function GetSeparatedEditViewData() {
        return $this->GetCommonViewData()
            ->setMainScript('pgui.form-page-main');
    }

    public function GetSeparatedInsertViewData() {
        return $this->GetCommonViewData()
            ->setMainScript('pgui.form-page-main');
    }

    public function GetSingleRecordViewData() {
        return $this->GetCommonViewData()
            ->setMainScript('pgui.view-page-main');
    }

    public function GetListViewData() {
        return $this->GetCommonViewData()
            ->setMainScript('pgui.list-page-main');
    }

    #endregion

    public function FillVariablesValues(&$values)
    {
        $values = array();
        foreach($this->variableFuncs as $name => $code)
        {
            $function = create_function('$page', $code);
            $values[$name] = $function($this);
        }
    }

    public function GetValidationScripts()
    {
        return StringUtils::Format(
            "function EditValidation(fieldValues, errorInfo) { %s; return true; } " .
            " function InsertValidation(fieldValues, errorInfo) { %s; return true; }".
            " function EditForm_EditorValuesChanged(sender, editors) { %s; return true; }".
            " function InsertForm_EditorValuesChanged(sender, editors) { %s; return true; }".
            " function EditForm_initd(editors) { %s; return true; }".
            " function InsertForm_initd(editors) { %s; return true; }",

            $this->GetGrid()->GetEditClientValidationScript(),
            $this->GetGrid()->GetInsertClientValidationScript(),

            $this->GetGrid()->GetEditClientEditorValueChangedScript(),
            $this->GetGrid()->GetInsertClientEditorValueChangedScript(),
            $this->GetGrid()->GetEditClientFormLoadedScript(),
            $this->GetGrid()->GetInsertClientFormLoadedScript()
        );

    }

    public function GetAuthenticationViewData() {
        return array(
            'Enabled' => $this->GetShowUserAuthBar(),
            'LoggedIn' => $this->IsCurrentUserLoggedIn(),
            'CurrentUser' => array(
                'Name' => $this->GetCurrentUserName(),
                'Id' => $this->GetCurrentUserId(),
            ),
            'CanChangeOwnPassword' => GetApplication()->GetUserManager()->CanChangeUserPassword() &&
                    GetApplication()->CanUserChangeOwnPassword(),
            'isAdminPanelVisible' => HasAdminPage() && GetApplication()->HasAdminGrantForCurrentUser()
        );
    }

    public function FillAvailableVariables(&$variables)
    {
        return array_keys($this->variableFuncs);
    }

    #endregion

    /**
     * @return IVariableContainer
     */
    public function GetColumnVariableContainer()
    {
        if (!isset($this->columnVariableContainer))
            $this->columnVariableContainer = new CompositeVariableContainer(
                $this, GetApplication(),
                new ServerVariablesContainer(),
                new SystemFunctionsVariablesContainer()
                );
        return $this->columnVariableContainer;
    }

    #region RSS

    public function HasRss()
    {
        $rssGenerator = $this->GetRssGenerator();
        return isset($rssGenerator) && (get_class($rssGenerator) != 'NullRssGenerator');
    }

    public function GetRssLink()
    {
        if ($this->HasRss())
        {
            $linkBuilder = $this->CreateLinkBuilder();
            $linkBuilder->AddParameter(OPERATION_PARAMNAME, 'rss');
            return $linkBuilder->GetLink();
        }
        return null;
    }

    public function GetRssGenerator()
    {
        return $this->rssGenerator;
    }

    protected function CreateRssGenerator()
    {
        return null;
    }

    #endregion

    public function GetEnvVar($name)
    {
        $vars = array();
        $this->GetColumnVariableContainer()->FillVariablesValues($vars);
        return $vars[$name];
    }

    public function GetCustomPageHeader()
    {
        $result = '';
        $this->OnCustomHTMLHeader->Fire(array(&$this, &$result));
        return $result;
    }

    /**
     * @return Grid
     */
    protected abstract function CreateGrid();

    protected function CreatePageNavigator()
    {
        return null;
    }

    protected function AddPageNavigatorToStack($pageNavigator)
    {
        $this->pageNavigatorStack[] = $pageNavigator;
    }

    protected function FillPageNavigatorStack()
    { }

    protected function DoBeforeCreate()
    { }

    protected function DoPrepare()
    {}

    protected function CreateComponents()
    {
        $this->grid = $this->CreateGrid();

        // remove: search controls must be configured from the generated code
        $this->grid->UpdateSearchControls();

        $this->pageNavigator = $this->CreatePageNavigator();
        $this->httpHandlerName = null;
        $this->FillPageNavigatorStack();
        $this->RegisterHandlers();
    }

    protected function GetEnableModalGridDelete()
    {
        return false;
    }

    public function GetModalGridDeleteHandler()
    {
        return 'inline_grid';
    }

    public function GetHttpHandlerName() {
        return $this->httpHandlerName;
    }

    private function RegisterHandlers()
    {
        if ($this->GetEnableModalSingleRecordView())
        {
            $handler = new InlineGridViewHandler($this->GetModalGridViewHandler(), new RecordCardView($this->GetGrid()));
            GetApplication()->RegisterHTTPHandler($handler);
        }
        if ($this->GetEnableModalGridCopy())
        {
            $handler = new InlineGridHandler($this->GetModalGridCopyHandler(), new VerticalGrid($this->GetGrid()));
            GetApplication()->RegisterHTTPHandler($handler);
        }
        if ($this->GetEnableModalGridEditing())
        {
            $handler = new InlineGridHandler($this->GetModalGridEditingHandler(), new VerticalGrid($this->GetGrid()));
            GetApplication()->RegisterHTTPHandler($handler);
        }
        if ($this->GetEnableModalGridDelete())
        {
            $handler = new ModalDeleteHandler($this->GetModalGridDeleteHandler(), $this->GetGrid());
            GetApplication()->RegisterHTTPHandler($handler);
        }
    }

    public function GetModalGridCopyHandler()
    {
        return 'modal_copy';
    }

    public function GetModalGridViewHandler()
    {
        return 'inline_grid_view';
    }

    public function GetModalGridEditingHandler()
    {
        return 'inline_grid';
    }

    protected function GetEnableModalGridCopy()
    {
        return true;
    }

    protected function GetEnableModalGridEditing()
    {
        return false;
    }

    protected function GetEnableModalSingleRecordView()
    {
        return true;
    }

    function __construct($pageFileName, $title = null, $dataSourceSecurityInfo = null, $contentEncoding=null)
    {
        parent::__construct($title, $contentEncoding);
        $this->BeforePageRender = new Event();
        $this->OnCustomHTMLHeader = new Event();
        $this->OnGetCustomTemplate = new Event();
        $this->OnPrepareChart = new Event();
        $this->OnPrepareChart->AddListener('OnPrepareChart', $this);
        $this->OnGetCustomExportOptions = new Event();
        $this->OnGetCustomExportOptions->AddListener('OnGetCustomExportOptions', $this);

        $this->securityInfo = $dataSourceSecurityInfo;
        $this->pageFileName = $pageFileName;
        $this->menuLabel = $title;
        $this->showPageList = true;
        $this->simpleSearchAvailable = true;
        $this->advancedSearchAvailable = true;
        $this->visualEffectsEnabled = true;
        $this->rssGenerator = null;
        $this->detailedDescription = null;

        $this->charts = array(
            ChartPosition::BEFORE_GRID => array(),
            ChartPosition::AFTER_GRID => array(),
        );

        $this->gridHeader = '';
        $this->recordPermission = null;
        $this->message = null;
        $this->pageNavigatorStack = array();

        $this->BeforeCreate();
        $this->CreateComponents();

        $this->Prepare();

        $this->setupCharts();
    }

    protected function setupCharts()
    {
    }

    protected function addChart(Chart $chart, $index = 0, $position = ChartPosition::BEFORE_GRID, $cols = 6)
    {
        $this->charts[$position][$index] = array(
            'chart' => $chart,
            'cols' => $cols,
        );
    }

    public function getCharts()
    {
        return $this->charts;
    }

    public function hasCharts()
    {
        return 0 < count($this->charts[ChartPosition::BEFORE_GRID])
            || 0 < count($this->charts[ChartPosition::AFTER_GRID]);
    }

    public function OnGetCustomExportOptions(Page $page, $exportType, $rowData, &$options)
    {
    }

    public function GetCustomExportOptions($exportType, $rowData, &$options)
    {
        $this->OnGetCustomExportOptions->Fire(array($this, $exportType, $rowData, &$options));
    }

    public function OnPrepareChart(Chart $chart)
    {
    }

    public function UpdateValuesFromUrl()
    { }

    /**
     * @return null|PageNavigator
     */
    public function GetPaginationControl()
    {
        $pageNavigators = $this->GetPageNavigator();
        if (SMReflection::ClassName($pageNavigators) == 'CompositePageNavigator')
        {
            /** @var CompositePageNavigator $pageNavigators */
            foreach($pageNavigators->GetPageNavigators() as $pageNavigator)
                if (SMReflection::ClassName($pageNavigator) == 'PageNavigator')
                    return $pageNavigator;

        }
        return null;
    }

    public function GetExportListButtonsViewData()
    {
        return $this->GetExportButtonsViewData(
            $this->getExportListAvailable(),
            $this->getPrintListAvailable()
        );
    }

    public function getExportOneRecordButtonsViewData($primaryKeyValues)
    {
        return $this->GetExportButtonsViewData(
            $this->getExportOneRecordAvailable(),
            $this->getPrintOneRecordAvailable(),
            $primaryKeyValues
        );
    }

    private function GetExportButtonsViewData($exportsAvailable, $printAvailable, $primaryKeyValues = array())
    {
        $result = array();

        foreach ($exportsAvailable as $export) {
            $result[$export] = array(
                'Caption' =>    $this->GetLocalizerCaptions()->GetMessageString('ExportTo' . ucfirst($export)),
                'IconClass' => 'icon-export-' . $export,
                'Href' =>       $this->getExportLink($export, $primaryKeyValues),
            );
        }

        if ($printAvailable) {
            $result['print_page'] = array(
                'Caption' =>   $this->GetLocalizerCaptions()->GetMessageString('PrintCurrentPage'),
                'IconClass' => 'icon-print-page',
                'Href' =>      $this->GetPrintCurrentPageLink()
            );

            $result['print_all'] = array(
                'Caption' =>   $this->GetLocalizerCaptions()->GetMessageString('PrintAllPages'),
                'IconClass' => 'icon-print-page',
                'Href' =>      $this->GetPrintAllLink(),
                'BeginNewGroup' => true
            );
        }
        return $result;
    }

    private final function GetPageList()
    {
        return PageList::createForPage($this);
    }

    public function GetReadyPageList() {
        $pageList = $this->GetPageList();

        if (!$pageList) {
            return null;
        }

        $pageList->AddRssLinkForCurrentPage($this->GetRssLink());

        return $pageList;
    }

    public function GetForeingKeyFields()
    {
        return array();
    }

    public function BeforeCreate()
    {
        try
        {
            $this->DoBeforeCreate();
            $this->rssGenerator = $this->CreateRssGenerator();
        }
        catch(Exception $e)
        {
            $message = $this->GetLocalizerCaptions()->GetMessageString('GuestAccessDenied');
            ShowSecurityErrorPage($this, $message);
            die();
        }
    }

    public function Prepare()
    {
        $this->DoPrepare();

        if ($this->GetSecurityInfo()->HasViewGrant()) {
            $this->addExportOperationsColumns();
            $this->addPrintOperationsColumns();
        }
    }

    private function addExportOperationsColumns()
    {
        $actions = $this->grid->getActions();
        foreach ($this->getExportListRecordAvailable() as $export) {
            $operation = new LinkOperation(
                $this->GetLocalizerCaptions()->GetMessageString('ExportTo' . ucfirst($export)),
                constant('OPERATION_'.strtoupper($export).'_EXPORT_RECORD'),
                $this->dataset,
                $this->grid
            );

            $operation->setUseImage(true);
            $actions->addOperation($operation);
        }
    }

    private function addPrintOperationsColumns()
    {
        if (!$this->getPrintListRecordAvailable()) {
            return;
        }

        $actions = $this->grid->getActions();
        $operation = new LinkOperation(
            $this->GetLocalizerCaptions()->GetMessageString('PrintOneRecord'),
            OPERATION_PRINT_ONE,
            $this->dataset,
            $this->grid
        );

        $operation->setUseImage(true);
        $actions->addOperation($operation);
    }

    public function GetConnection()
    {
        $this->dataset->Connect();
        return $this->dataset->GetConnection();
    }

    public function PrepareTextForSQL($text)
    {
        return ConvertTextToEncoding($text, GetAnsiEncoding(), $this->GetContentEncoding());
    }

    public function SetErrorMessage($value)
    { $this->errorMessage = $value; }
    public function GetErrorMessage()
    { return $this->errorMessage; }

    public function SetMessage($value)
    { $this->message = $value; }
    public function GetMessage()
    { return $this->RenderText($this->message); }

    #region Options

    protected function DoGetGridHeader()
    {
        return '';
    }

    public function GetGridHeader()
    {
        return $this->RenderText($this->DoGetGridHeader());
    }

    public function GetShowUserAuthBar()
    {
        return $this->showUserAuthBar;
    }

    public function SetShowUserAuthBar($value)
    {
        $this->showUserAuthBar = $value;
    }

    public function GetMenuLabel()
    {
        return $this->RenderText($this->menuLabel);
    }

    public function SetMenuLabel($value)
    {
        $this->menuLabel = $value;
    }

    public function GetEditFormTitle()
    {
        return $this->editFormTitle;
    }

    public function SetEditFormTitle($editFormTitle)
    {
        $this->editFormTitle = $editFormTitle;
    }

    public function GetInsertFormTitle()
    {
        return $this->insertFormTitle;
    }

    public function SetInsertFormTitle($insertFormTitle)
    {
        $this->insertFormTitle = $insertFormTitle;
    }

    public function GetViewFormTitle()
    {
        return $this->viewFormTitle;
    }

    public function SetViewFormTitle($viewFormTitle)
    {
        $this->viewFormTitle = $viewFormTitle;
    }

    function GetShowTopPageNavigator()
    { return $this->showTopPageNavigator; }
    function SetShowTopPageNavigator($value)
    { $this->showTopPageNavigator = $value; }
    function GetShowBottomPageNavigator()
    { return $this->showBottomPageNavigator; }
    function SetShowBottomPageNavigator($value)
    { $this->showBottomPageNavigator = $value; }
    function GetShowPageList()
    { return $this->showPageList; }
    function GetHidePageListByDefault()
    { return $this->hidePageListByDefault; }
    function SetShowPageList($value)
    { $this->showPageList = $value; }
    function SetHidePageListByDefault($value)
    { $this->hidePageListByDefault = $value; }
    function SetSimpleSearchAvailable($value)
    { $this->simpleSearchAvailable = $value; }
    function SetAdvancedSearchAvailable($value)
    { $this->advancedSearchAvailable = $value; }
    function GetAdvancedSearchAvailable()
    { return $this->advancedSearchAvailable; }
    function GetSimpleSearchAvailable()
    { return $this->simpleSearchAvailable; }

    function SetVisualEffectsEnabled($value)
    { $this->visualEffectsEnabled = $value; }

    public function setExportListAvailable(array $exportListAvailable)
    {
        $this->exportListAvailable = $exportListAvailable;
    }

    public function getExportListAvailable()
    {
        return $this->exportListAvailable;
    }

    public function setExportListRecordAvailable(array $exportListRecordAvailable)
    {
        $this->exportListRecordAvailable = $exportListRecordAvailable;
    }

    public function getExportListRecordAvailable()
    {
        return $this->exportListRecordAvailable;
    }

    public function setExportOneRecordAvailable(array $exportOneRecordAvailable)
    {
        $this->exportOneRecordAvailable = $exportOneRecordAvailable;
    }

    public function getExportOneRecordAvailable()
    {
        return $this->exportOneRecordAvailable;
    }

    public function getPrintListAvailable()
    {
        return $this->printListAvailable;
    }

    public function setPrintListAvailable($printListAvailable)
    {
        $this->printListAvailable = $printListAvailable;
    }

    public function getPrintListRecordAvailable()
    {
        return $this->printListRecordAvailable;
    }

    public function setPrintListRecordAvailable($printListRecordAvailable)
    {
        $this->printListRecordAvailable = $printListRecordAvailable;
    }

    public function setPrintOneRecordAvailable($printOneRecordAvailable)
    {
        $this->printOneRecordAvailable = $printOneRecordAvailable;
    }

    public function getPrintOneRecordAvailable()
    {
        return $this->printOneRecordAvailable;
    }

    #endregion

    function IsCurrentUserLoggedIn()
    {
        return GetApplication()->IsCurrentUserLoggedIn();
    }

    function GetCurrentUserId() {
        return GetApplication()->GetCurrentUserId();
    }

    function GetCurrentUserName()
    {
        return GetApplication()->GetCurrentUser();
    }

    public function GetSecurityInfo()
    { return $this->securityInfo; }

    /**
     * @return IRecordPermissions|null
     */
    public function GetRecordPermission()
    { return $this->recordPermission; }

    public function SetRecordPermission(IRecordPermissions $value = null)
    { $this->recordPermission = $value; }

    function RaiseSecurityError($condition, $operation)
    {
        if ($condition)
        {
            if ($operation === OPERATION_EDIT)
                $message = $this->GetLocalizerCaptions()->GetMessageString('EditOperationNotPermitted');
            elseif ($operation === OPERATION_VIEW)
                $message = $this->GetLocalizerCaptions()->GetMessageString('ViewOperationNotPermitted');
            elseif ($operation === OPERATION_DELETE)
                $message = $this->GetLocalizerCaptions()->GetMessageString('DeleteOperationNotPermitted');
            elseif ($operation === OPERATION_INSERT)
                $message = $this->GetLocalizerCaptions()->GetMessageString('InsertOperationNotPermitted');
            else
                $message = $this->GetLocalizerCaptions()->GetMessageString('OperationNotPermitted');
            ShowSecurityErrorPage($this, $message);
            exit;
        }
    }

    function CheckOperationPermitted()
    {
        $operation = GetOperation();
        if ($this->securityInfo->AdminGrant())
            return true;
        switch ($operation)
        {
            case OPERATION_EDIT:
                $this->RaiseSecurityError(!$this->securityInfo->HasEditGrant(), OPERATION_EDIT);
                break;
            case OPERATION_VIEW:
            case OPERATION_PRINT_ONE:
            case OPERATION_PRINT_ALL:
            case OPERATION_PRINT_PAGE:
            case OPERATION_EXCEL_EXPORT:
            case OPERATION_WORD_EXPORT:
            case OPERATION_XML_EXPORT:
            case OPERATION_CSV_EXPORT:
            case OPERATION_PDF_EXPORT:
                $this->RaiseSecurityError(!$this->securityInfo->HasViewGrant(), OPERATION_VIEW);
                break;
            case OPERATION_DELETE:
            case OPERATION_DELETE_SELECTED:
                $this->RaiseSecurityError(!$this->securityInfo->HasDeleteGrant(), OPERATION_DELETE);
                break;
            case OPERATION_INSERT:
            case OPERATION_COPY:
                $this->RaiseSecurityError(!$this->securityInfo->HasAddGrant(), OPERATION_INSERT);
                break;
            default:
                $this->RaiseSecurityError(!$this->securityInfo->HasViewGrant(), OPERATION_VIEW);
                break;
        }
        return true;
    }

    function SelectRenderer()
    {
        switch (GetOperation())
        {
            case OPERATION_EDIT:
                $this->renderer = new EditRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_VIEW:
                $this->renderer = new ViewRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_PRINT_ONE:
                $this->renderer = new PrintOneRecordRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_DELETE:
                $this->renderer = new DeleteRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_INSERT:
                $this->renderer = new InsertRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_COPY:
                $this->renderer = new InsertRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_PRINT_ALL:
                $this->renderer = new PrintRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_PRINT_PAGE:
                $this->renderer = new PrintRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_EXCEL_EXPORT:
                $this->renderer = new ExcelListRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_EXCEL_EXPORT_RECORD:
                $this->renderer = new ExcelRecordRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_WORD_EXPORT:
                $this->renderer = new WordListRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_WORD_EXPORT_RECORD:
                $this->renderer = new WordRecordRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_XML_EXPORT:
                $this->renderer = new XmlListRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_XML_EXPORT_RECORD:
                $this->renderer = new XmlRecordRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_CSV_EXPORT:
                $this->renderer = new CsvListRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_CSV_EXPORT_RECORD:
                $this->renderer = new CsvRecordRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_PDF_EXPORT:
                $this->renderer = new PdfListRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_PDF_EXPORT_RECORD:
                $this->renderer = new PdfRecordRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_DELETE_SELECTED:
                $this->renderer = new ViewAllRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_AJAX_REQUERT_INLINE_EDIT:
                $this->renderer = new InlineEditRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_AJAX_REQUERT_INLINE_EDIT_COMMIT:
                $this->renderer = new CommitInlineEditRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_AJAX_REQUERT_INLINE_INSERT:
                $this->renderer = new InlineInsertRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_AJAX_REQUERT_INLINE_INSERT_COMMIT:
                $this->renderer = new CommitInlineInsertRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_ADVANCED_SEARCH:
                $this->renderer = new SingleAdvancedSearchRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_RSS:
                $this->renderer = new RssRenderer($this->GetLocalizerCaptions());
                break;
            default:
                $this->renderer = new ViewAllRenderer($this->GetLocalizerCaptions());
                break;
        }
    }

     function OpenAdvancedSearchByDefault()
    {
        return false;
    }

    function DoProcessMessages()
    {
        if (GetOperation() != OPERATION_RSS)
        {
            //if (isset($this->AdvancedSearchControl) && $this->OpenAdvancedSearchByDefault())
                //if (!$this->AdvancedSearchControl->HasCondition())
                //    GetApplication()->SetOperation(OPERATION_ADVANCED_SEARCH);

            $this->grid->SetState(GetOperation());
            $advancedSearchApplied = false;
            if (isset($this->AdvancedSearchControl)) {
                $advancedSearchApplied = $this->AdvancedSearchControl->ProcessMessages();
            }

            $this->grid->ProcessMessages();

            if (isset($this->pageNavigator))
                $this->pageNavigator->ProcessMessages();

            if ($advancedSearchApplied) {
                $link = $this->CreateLinkBuilder();
                header('Location: ' . $link->GetLink());
                exit();
            }
        }
    }

    function ProcessMessages()
    {
        try
        {
            $this->DoProcessMessages();
        }
        catch(Exception $e)
        {
            $this->DisplayErrorPage($e);
            die();
        }
    }

    function BeginRender()
    {
        $this->BeforeBeginRenderPage();
        $this->ProcessMessages();
    }

    function EndRender()
    {
        try
        {
            $this->CheckOperationPermitted();
            $this->SelectRenderer();
            $this->BeforeRenderPageRender();
            echo $this->renderer->Render($this);
        }
        catch(Exception $e)
        {
            $this->DisplayErrorPage($e);
            die();
        }
    }

    function BeforeBeginRenderPage()
    { }

    function BeforeRenderPageRender()
    { }

    function DisplayErrorPage($exception)
    {
        $errorStateRenderer = new ErrorStateRenderer($this->GetLocalizerCaptions(), $exception);
        echo $errorStateRenderer->Render($this);
    }

    /**
     * @param Renderer $visitor
     */
    function Accept($visitor)
    {
        $visitor->RenderPage($this);
    }

    #region Page parts

    /**
     * @return Dataset
     */
    function GetDataset()
    {
        return $this->dataset;
    }

    /**
     * @return Grid
     */
    function GetGrid()
    {
        return $this->grid;
    }

    /**
     * @return PageNavigator
     */
    function GetPageNavigator()
    {
        return $this->pageNavigator;
    }

    function GetPageNavigatorStack()
    {
        return $this->pageNavigatorStack;
    }

    #endregion

    function GetPageFileName()
    {
        return $this->pageFileName;
    }

    function SetHttpHandlerName($name)
    {
        $this->httpHandlerName = $name;
    }

    public function GetHiddenGetParameters()
    {
        $result = array();
        if (isset($this->httpHandlerName))
            $result['hname'] = $this->httpHandlerName;
        return $result;
    }

    function CreateLinkBuilder()
    {
        $result = new LinkBuilder($this->GetPageFileName());

        if (isset($this->httpHandlerName))
            $result->AddParameter('hname', $this->httpHandlerName);

        return $result;
    }

    #region Export links

    function GetOperationLink($operationName, $operationForAllPages = false, $primaryKeyValues = array())
    {
        $result = $this->CreateLinkBuilder();
        $result->AddParameter(OPERATION_PARAMNAME, $operationName);
        if ($operationForAllPages) {
            if (isset($this->pageNavigator)) {
                $this->pageNavigator->AddCurrentPageParameters($result);
            }
        }

        if ($primaryKeyValues) {
            foreach ($primaryKeyValues as $i => $value) {
                $result->addParameter("pk$i", $value);
            }
        }

        return $result->GetLink();
    }

    function GetPrintAllLink()
    {
        return $this->GetOperationLink(OPERATION_PRINT_ALL);
    }

    function GetPrintCurrentPageLink()
    {
        return $this->GetOperationLink(OPERATION_PRINT_PAGE, true);
    }

    function GetExportToExcelLink()
    {
        return $this->getExportLink('excel');
    }

    function GetExportToWordLink()
    {
        return $this->getExportLink('word');
    }

    function GetExportToXmlLink()
    {
        return $this->getExportLink('xml');
    }

    function GetExportToCsvLink()
    {
        return $this->getExportLink('csv');
    }

    function GetExportToPdfLink()
    {
        return $this->getExportLink('pdf');
    }

    private function getExportLink($type, $primaryKeyValues = array())
    {
        $exportMap = $primaryKeyValues ? array(
            'excel' => OPERATION_EXCEL_EXPORT_RECORD,
            'pdf' => OPERATION_PDF_EXPORT_RECORD,
            'csv' => OPERATION_CSV_EXPORT_RECORD,
            'xml' => OPERATION_XML_EXPORT_RECORD,
            'word' => OPERATION_WORD_EXPORT_RECORD,
        ) : array(
            'excel' => OPERATION_EXCEL_EXPORT,
            'pdf' => OPERATION_PDF_EXPORT,
            'csv' => OPERATION_CSV_EXPORT,
            'xml' => OPERATION_XML_EXPORT,
            'word' => OPERATION_WORD_EXPORT,
        );

        return $this->GetOperationLink($exportMap[$type], false, $primaryKeyValues);
    }

    #endregion

    private function GetCurrentPageMode() {
        switch (GetApplication()->GetOperation()) {
            case OPERATION_VIEWALL:
                return PageMode::ViewAll;
        }
        return null;
    }

    public function GetCustomTemplate($part, $mode, $defaultValue, &$params = null) {
        $result = null;

        if (!$mode) // for PageList
            $mode = $this->GetCurrentPageMode();
        if (!$params)
            $params = array();

        $this->OnGetCustomTemplate->Fire(array($part, $mode, &$result, &$params, $this));
        if ($result)
            return Path::Combine('custom_templates', $result);
        else
            return $defaultValue;
    }

    public function GetBackFromAdvancedSearchAddress()
    {
        $result = $this->CreateLinkBuilder();
        return $result->GetLink();
    }

    /**
     * @return string
     */
    public function getDetailedDescription() {
        return $this->detailedDescription;
    }

    /**
     * @param string $value
     */
    public function setDetailedDescription($value) {
        $this->detailedDescription = $value;
    }

    /**
     * @param bool $modalViewSize
     *
     * @return $this
     */
    public function setModalViewSize($modalViewSize)
    {
        $this->modalViewSize = $modalViewSize;
    }

    /**
     * @return bool
     */
    public function getModalViewSize()
    {
        return $this->modalViewSize;
    }

    /**
     * @param bool $modalFormSize
     *
     * @return $this
     */
    public function setModalFormSize($modalFormSize)
    {
        $this->modalFormSize = $modalFormSize;
    }

    /**
     * @return bool
     */
    public function getModalFormSize()
    {
        return $this->modalFormSize;

    }

    /**
     * @param string $fieldName
     * @param mixed  &$value
     */
    public function OnGetFieldValue($fieldName, &$value)
    {
    }
}

abstract class DetailPage extends Page
{
    private $foreingKeyValues;
    private $foreingKeyFields;
    private $recordLimit;
    private $totalRowCount;
    private $fullViewHandlerName;
    /** @var Page */
    private $parentPage;

    public $DetailRowNumber;

    public function __construct($parentPage, $title, $menuLabel, $foreingKeyFields, $dataSourceSecurityInfo, $contentEncoding = null, $recordLimit = 0, $fullViewHandlerName)
    {
        $this->foreingKeyFields = $foreingKeyFields;
        $this->parentPage = $parentPage;
        parent::__construct('', $title, $dataSourceSecurityInfo, $contentEncoding);
        $this->SetMenuLabel($menuLabel);
        $this->recordLimit = $recordLimit;
        $this->fullViewHandlerName = $fullViewHandlerName;
    }

    public function GetPageFileName() {
        return $this->parentPage->GetPageFileName();
    }

    public function GetForeingKeyFields()
    { return $this->foreingKeyFields; }

    public function GetParentPage() {
        return $this->parentPage;
    }

    public function ProcessMessages()
    {
        if ($this->recordLimit)
        {
            $this->dataset->SetUpLimit(0);
            $this->dataset->SetLimit($this->recordLimit);
        }
        $this->DetailRowNumber = $_GET['detailrow'];
        $this->GetGrid()->SetId($this->DetailRowNumber . $this->GetGrid()->GetId());
        $this->GetGrid()->ProcessMessages();

        $this->renderer = new ViewAllRenderer($this->GetLocalizerCaptions());
        for($i = 0; $i < count($this->foreingKeyFields); $i++)
        {
            $this->dataset->AddFieldFilter($this->foreingKeyFields[$i], new FieldFilter($_GET['fk' . $i], '='));
            $this->foreingKeyValues[] = $_GET['fk' . $i];
        }
        $this->totalRowCount = $this->dataset->GetTotalRowCount();
        $this->GetGrid()->SetShowUpdateLink(false);
        $this->GetGrid()->SetShowFilterBuilder(false);
    }

    protected function CreatePageNavigator()
    { }

    public function GetHiddenGetParameters()
    {
        $result = parent::GetHiddenGetParameters();
        for($i = 0; $i < count($this->foreingKeyValues); $i++)
            $result['fk' . $i] = $this->foreingKeyValues[$i];
        return $result;
    }

    protected function CreateComponents() {
        parent::CreateComponents();
        $this->GetGrid()->SetShowFilterBuilder(false);
    }

    function CreateLinkBuilder()
    {
        $result = parent::CreateLinkBuilder();
        $result->AddParameter('hname', $this->fullViewHandlerName);
        for($i = 0; $i < count($this->foreingKeyValues); $i++)
            $result->AddParameter('fk' . $i, $this->foreingKeyValues[$i]);
        return $result;
    }

    public function GetFullRecordCount()
    { return $this->totalRowCount; }
    public function GetRecordLimit()
    { return $this->recordLimit; }
    public function GetFullViewLink()
    {
        $result = $this->CreateLinkBuilder();
        $result->AddParameter('hname', $this->fullViewHandlerName);
        return $result->GetLink();
    }

    /** @inheritdoc */
    function Accept($visitor)
    {
        $visitor->RenderDetailPage($this);
    }

    function EndRender()
    {
        echo $this->renderer->Render($this);
    }
}

abstract class DetailPageEdit extends Page
{
    private $foreingKeyValues;
    private $foreingKeyFields;
    private $masterKeyFields;
    /** @var Dataset */
    private $masterDataset;
    /** @var Grid */
    private $masterGrid;
    /** @var Page */
    private $parentPage;
    private $parentMasterKeyFields;
    private $parentMasterKeyValues;

    public function __construct($parentPage, $foreingKeyFields, $masterKeyFields, $parentMasterKeyFields, $masterGrid, $masterDataset, $dataSourceSecurityInfo, $contentEncoding = null)
    {
        $this->foreingKeyFields = $foreingKeyFields;
        $this->parentPage = $parentPage;
        parent::__construct('', '', $dataSourceSecurityInfo, $contentEncoding);
        $this->masterKeyFields = $masterKeyFields;
        $this->masterGrid = $masterGrid;
        $this->masterDataset = $masterDataset;
        $this->foreingKeyValues = array();
        $this->parentMasterKeyFields = $parentMasterKeyFields;
    }

    public function getTitle()
    {
        if ($this->masterDataset) {
            return StringUtils::ReplaceVariables(parent::getTitle(), $this->masterDataset->getCurrentFieldValues());
        }

        return parent::getTitle();
    }

    public function GetReadyPageList() {
        return $this->parentPage->GetReadyPageList();
    }

    public function GetParentPage() {
        return $this->parentPage;
    }

    public function GetForeingKeyFields()
    { return $this->foreingKeyFields; }

    public function GetMasterGrid()
    { return $this->masterGrid; }

    public function ProcessMessages()
    {
        $this->UpdateValuesFromUrl();
        parent::ProcessMessages();

        $masterGrid = $this->GetMasterGrid();
        if ($masterGrid) {
            $masterGrid->ProcessMessages();
        }

    }

    public function UpdateValuesFromUrl()
    {
        for($i = 0; $i < count($this->foreingKeyFields); $i++)
        {
            if (GetApplication()->GetSuperGlobals()->IsGetValueSet('fk' . $i))
            {
                $this->dataset->AddFieldFilter($this->foreingKeyFields[$i], new FieldFilter($_GET['fk' . $i], '='));
                $this->dataset->SetMasterFieldValue($this->foreingKeyFields[$i], $_GET['fk' . $i]);
                $this->foreingKeyValues[] = $_GET['fk' . $i];
            }
        }

        $this->RetrieveMasterDatasetValues();
    }


    private function RetrieveMasterDatasetValues()
    {
        $hasForeignKeyValues = true;

        for($i = 0; $i < count($this->masterKeyFields); $i++)
        {
            if (GetApplication()->GetSuperGlobals()->IsGetValueSet('fk' . $i))
            {
                $this->masterDataset->AddFieldFilter($this->masterKeyFields[$i], new FieldFilter($_GET['fk' . $i], '='));
            }
            else
            {
                $hasForeignKeyValues = false;
            }
        }

        if ($hasForeignKeyValues)
        {
            $this->masterDataset->Open();
            if ($this->masterDataset->Next())
            {
                for($i = 0; $i < count($this->parentMasterKeyFields); $i++)
                    $this->parentMasterKeyValues[] = $this->masterDataset->GetFieldValueByName($this->parentMasterKeyFields[$i]);
            }
            $this->masterDataset->Close();
        }
    }

    /** @inheritdoc */
    function Accept($visitor)
    {
        $visitor->RenderDetailPageEdit($this);
    }

    public function GetHiddenGetParameters()
    {
        $result = parent::GetHiddenGetParameters();
        for($i = 0; $i < count($this->foreingKeyValues); $i++)
            $result['fk' . $i] = $this->foreingKeyValues[$i];
        return $result;
    }

    function GetParentPageLink()
    {
        $result = $this->parentPage->CreateLinkBuilder();

        for($i = 0; $i < count($this->parentMasterKeyFields); $i++)
            $result->AddParameter('fk'.$i, $this->parentMasterKeyValues[$i]);

        return $result->GetLink();
    }

    function CreateLinkBuilder()
    {
        $result = parent::CreateLinkBuilder();
        for($i = 0; $i < count($this->foreingKeyValues); $i++)
            $result->AddParameter('fk' . $i, $this->foreingKeyValues[$i]);
        return $result;
    }

    function GetOperationLink($operationName, $operationForAllPages = false, $primaryKeyValues = array())
    {
        $result = $this->CreateLinkBuilder();
        $result->AddParameter(OPERATION_PARAMNAME, $operationName);

        for($i = 0; $i < count($this->foreingKeyValues); $i++)
            $result->AddParameter('fk' . $i, $this->foreingKeyValues[$i]);

        $pageNavigator = $this->GetPageNavigator();
        if ($operationForAllPages && isset($pageNavigator))
            $pageNavigator->AddCurrentPageParameters($result);
        return $result->GetLink();

    }
}

class CustomLoginPage extends CommonPage
{
    public $OnGetCustomTemplate;

    public function __construct()
    {
        parent::__construct('Login', 'UTF-8');
        $this->OnGetCustomTemplate = new Event();
    }

    public function GetPageFileName() {
        return basename(__FILE__);
    }

    public function GetCustomTemplate($part, $defaultValue, &$params = null) {
        $result = null;

        if (!$params)
            $params = array();

        $this->OnGetCustomTemplate->Fire(array($part, null, &$result, &$params));
        if ($result)
            return Path::Combine('custom_templates', $result);
        else
            return $defaultValue;
    }
}
