<?php

include_once dirname(__FILE__) . '/' . 'renderer.php';
include_once dirname(__FILE__) . '/' . '../env_variables.php';
include_once dirname(__FILE__) . '/' . '../utils/html_utils.php';
include_once dirname(__FILE__) . '/' . '../utils/system_utils.php';

class ViewAllRenderer extends Renderer
{
    public $renderSingleRow;
    private $renderPageNavigator;

    #region Pages

    private function GetPageNavigator(Page $page) {
        if ($page->GetShowTopPageNavigator() or $page->GetShowBottomPageNavigator()) {
            $pageNavigators = $page->GetPageNavigator()->GetPageNavigators();
            foreach ($pageNavigators as $pnav) {
                if ($pnav->GetName() == 'pnav') {
                    return $pnav;
                }
            }
        }

        return false;
    }

    private function GetPageNavigator1(Page $page) {
        $this->renderPageNavigator = $page->GetShowTopPageNavigator();
        return $this->RenderDef($page->GetPageNavigator());
    }

    private function GetPageNavigator2(Page $page) {
        $this->renderPageNavigator = $page->GetShowBottomPageNavigator();
        if ($page->GetShowBottomPageNavigator()){
            if ($page->GetShowTopPageNavigator())
                return $this->RenderDef($page->GetPageNavigator());
            else
                return $this->RenderDef($page->GetPageNavigator());
        }
        else
            return $this->RenderDef($page->GetPageNavigator());
    }

    /**
     * @param Page $Page
     */
    public function RenderPage(Page $Page) {

        $this->SetHTTPContentTypeByPage($Page);
        $Page->BeforePageRender->Fire(array(&$Page));
        $Grid = $this->Render($Page->GetGrid());

        $customParams = array();
        $layoutTemplate = $Page->GetCustomTemplate(PagePart::Layout, PageMode::ViewAll, 'common/layout.tpl', $customParams);

        $this->DisplayTemplate('list/page.tpl',
            array(
                'Page' => $Page
            ),
            array_merge($customParams, $this->getChartsParams($Page),
                array(
                    // Template override
                    'LayoutTemplateName' => $layoutTemplate,
                    // View data
                    'Authentication' => $Page->GetAuthenticationViewData(),
                    'common' => $Page->GetListViewData(),
                    // Rendered controls
                    'Grid' => $Grid,
                    'PageList' => $this->RenderDef($Page->GetReadyPageList()),
                    'HideSideBarByDefault' => $Page->GetHidePageListByDefault(),
                    'Variables' => $this->GetPageVariables($Page),
                    // Page navigators
                    'PageNavigator' => $this->GetPageNavigator($Page),
                    'PageNavigator1' => $this->GetPageNavigator1($Page),
                    'PageNavigator2' => $this->GetPageNavigator2($Page),
                    'EnableRunTimeCustomization' => $Page->GetGrid()->getEnableRunTimeCustomization(),
                    'ViewModes' => ViewMode::getList()

                )
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function RenderDetailPageEdit($DetailPage)  {
        $this->SetHTTPContentTypeByPage($DetailPage);

        $Grid = $this->Render($DetailPage->GetGrid());

        if ($DetailPage->GetReadyPageList() != null)
            $pageList = $this->Render($DetailPage->GetReadyPageList());
        else
            $pageList = null;

        $isAdvancedSearchActive = false;
        $userFriendlySearchCondition = '';
        if (isset($DetailPage->AdvancedSearchControl)) {
            $isAdvancedSearchActive = $DetailPage->AdvancedSearchControl->IsActive();
            $userFriendlySearchCondition = $DetailPage->AdvancedSearchControl->GetUserFriendlySearchConditions();
            $linkBuilder = $DetailPage->CreateLinkBuilder();
            $linkBuilder->AddParameter(OPERATION_PARAMNAME, OPERATION_ADVANCED_SEARCH);
            $DetailPage->AdvancedSearchControl->SetOpenInNewWindowLink($linkBuilder->GetLink());
        }

        $customParams = array();
        $layoutTemplate = $DetailPage->GetCustomTemplate(PagePart::Layout, PageMode::ViewAll, 'common/layout.tpl',
            $customParams);

        $parentPage = $DetailPage->GetParentPage();

        $this->DisplayTemplate('list/detail_page_edit.tpl',
            array(
                'common' => $DetailPage->GetListViewData(),
                'Page' => $DetailPage,
                'DetailPage' => $DetailPage,
                'SiblingDetails' => $parentPage->GetGrid()->GetDetailLinksViewData(),
                'DetailPageName' => $DetailPage->GetHttpHandlerName(),
                'PageList' => $pageList,
                'PageNavigator' => $this->GetPageNavigator($DetailPage),
                'PageNavigator1' => $this->GetPageNavigator1($DetailPage),
                'PageNavigator2' => $this->GetPageNavigator2($DetailPage),

                'ViewModes' => ViewMode::getList()
            ),
            array_merge($customParams, $this->getChartsParams($DetailPage),
                array(
                    'PageTitle' => $DetailPage->GetTitle(),
                    'LayoutTemplateName' => $layoutTemplate,
                    'Grid' => $Grid,
                    'Authentication' => $DetailPage->GetAuthenticationViewData(),
                    'AdvancedSearch' => isset($DetailPage->AdvancedSearchControl) ? $this->Render($DetailPage->AdvancedSearchControl) : '',
                    'IsAdvancedSearchActive' => $isAdvancedSearchActive,
                    'FriendlyAdvancedSearchCondition' => $userFriendlySearchCondition,
                    'HideSideBarByDefault' => $DetailPage->GetHidePageListByDefault(),
                    'EnableRunTimeCustomization' => $DetailPage->GetGrid()->getEnableRunTimeCustomization(),
                    'MasterGrid' => $this->Render($DetailPage->GetMasterGrid()),
                    'Variables' => $this->GetPageVariables($DetailPage)
                )
            )
        );
    }

    private function getChartsParams($page)
    {
        $renderedCharts = array(
            ChartPosition::BEFORE_GRID => array(),
            ChartPosition::AFTER_GRID => array(),
        );
        $chartsClasses = array(
            ChartPosition::BEFORE_GRID => '',
            ChartPosition::AFTER_GRID => '',
        );

        $chartsClasses = array();
        foreach ($page->getCharts() as $position => $charts) {
            ksort($charts);
            $chartsClasses[$position] = array();
            foreach ($charts as $chart) {
                $page->OnPrepareChart->Fire(array($chart['chart']));
                $renderedCharts[$position][] = $this->Render($chart['chart']);
                $chartsClasses[$position][] = 'col-md-' . $chart['cols'];
            }
        }

        return array(
            'ChartsBeforeGrid' => $renderedCharts[ChartPosition::BEFORE_GRID],
            'ChartsBeforeGridClasses' => $chartsClasses[ChartPosition::BEFORE_GRID],
            'ChartsAfterGrid' => $renderedCharts[ChartPosition::AFTER_GRID],
            'ChartsAfterGridClasses' => $chartsClasses[ChartPosition::AFTER_GRID],
        );
    }

    #endregion

    #region Page parts

    public function RenderGrid(Grid $Grid) {
        $page = $Grid->GetPage();

        // Remove!!!
        if (isset($page->AdvancedSearchControl))
        {
            $linkBuilder = $page->CreateLinkBuilder();
            $linkBuilder->AddParameter(OPERATION_PARAMNAME, OPERATION_ADVANCED_SEARCH);
            $page->AdvancedSearchControl->SetOpenInNewWindowLink($linkBuilder->GetLink());
        }

        $templates = array(
            ViewMode::TABLE => array(
                'grid'   => 'list/grid.tpl',
                'single' => 'list/single_row.tpl'
            ),
            ViewMode::CARD => array(
                'grid'   => 'list/grid_card.tpl',
                'single' => 'list/single_row_card.tpl'
            )
        );
        $selectedTemplates = $templates[$Grid->GetViewMode()];

        $template = $this->renderSingleRow ? $selectedTemplates['single'] : $selectedTemplates['grid'];
        $customParams = array();

        if (!$this->renderSingleRow)
            $template = $page->GetCustomTemplate(PagePart::Grid, PageMode::ViewAll, $template, $customParams);

        $this->DisplayTemplate(
            $template,
            array(
                'Grid' => $Grid,
                'Page' => $Grid->GetPage(),
                'DataGrid' => $Grid->GetViewData($this)
            ),
            array_merge($customParams,
                array(
                    'isMasterGrid' => $Grid->GetName() == 'master_grid',
                    'SingleRowTemplate' => $page->GetCustomTemplate(PagePart::GridRow, PageMode::ViewAll, $selectedTemplates['single']),
                    'AdvancedSearchControl' => $page->AdvancedSearchControl,

                    // Remove!!!
                    'HiddenValues' => $Grid->GetHiddenValues(),
                    'TextsForHighlight' =>
                        $page->AdvancedSearchControl ?
                            array_map(Q::L('($v) => StringUtils::JSStringLiteral($v)'),
                                $page->AdvancedSearchControl->GetHighlightedFieldText()
                            ):
                            array(),
                    'HighlightOptions' =>
                        $page->AdvancedSearchControl ?
                            $page->AdvancedSearchControl->GetHighlightedFieldOptions() :
                            array(),

                    'Authentication' => $page->GetAuthenticationViewData(),

                    'Columns' => $Grid->GetViewColumns(),
                    'Bands' => $Grid->GetViewBands(),
                    'FilterBuilder' => $Grid->GetFilterBuilder()->GetViewData(),
                    'ActiveFilterBuilderJson' => $Grid->GetFilterBuilder()->GetActiveFilterAsJson(),
                    'ActiveFilterBuilderAsString' => $Grid->GetFilterBuilder()->GetActiveFilterAsString(),
                    'IsActiveFilterEmpty' => $Grid->GetFilterBuilder()->IsEmpty(),

                    'EnableRunTimeCustomization' => $Grid->getEnableRunTimeCustomization(),
                    'CurrentViewMode' => $Grid->getViewMode(),
                    'ViewModes' => ViewMode::getList(),
                )
            )
        );
    }

    public function RenderCustomPageNavigator(CustomPageNavigator $pageNavigator) {
        if ($this->renderPageNavigator) {
            $templateName = 'custom_page_navigator.tpl'; // here $pageNavigator->GetNavigationStyle() == NS_LIST
            if ($pageNavigator->GetNavigationStyle() == NS_COMBOBOX)
                $templateName = 'combo_box_custom_page_navigator.tpl';

            $this->DisplayTemplate('list/'.$templateName,
                array(
                        'PageNavigator' => $pageNavigator,
                        'PageNavigatorPages' => $pageNavigator->GetPages()),
                 array()
            );
        }
         else {
            $this->result = '';
        }
    }

    public function RenderCompositePageNavigator($PageNavigator) {
        $this->DisplayTemplate('list/composite_page_navigator.tpl',
            array(
                'PageNavigator' => $PageNavigator
            ),
            array()
        );
    }

    public function RenderPageNavigator($PageNavigator) {
        if ($this->renderPageNavigator) {
            $this->DisplayTemplate('list/page_navigator.tpl',
                array(
                    'PageNavigator' => $PageNavigator,
                    'PageNavigatorPages' => $PageNavigator->GetPages()),
                array()
            );
        }
        else {
            $this->result = '';
        }
    }

    #endregion

    #region Column rendering options

    protected function ShowHtmlNullValue()
    {
        return true;
    }

    #endregion
}

class ErrorStateRenderer extends ViewAllRenderer
{
    /** @var  Exception */
    private $exception;

    public function  __construct($captions, $exception)
    {
        parent::__construct($captions);
        $this->exception = $exception;
    }

    function RenderPage(Page $page)
    {
        $this->SetHTTPContentTypeByPage($page);

        $pageList = $page->GetReadyPageList();
        $pageList = isset($pageList) ? $this->Render($pageList) : '';

        $displayDebugInfo = DebugUtils::GetDebugLevel();

        $inputValues = array(
            'PageList' => $pageList,
            'common' => $page->getCommonViewData(),
            'ErrorMessage' => $this->exception->getMessage(),
            'DisplayDebugInfo' => $displayDebugInfo
        );

        if ($displayDebugInfo == 1) {
            $inputValues['File'] = $this->exception->getFile();
            $inputValues['Line'] = $this->exception->getLine();
            $inputValues['Trace'] = $this->exception->getTraceAsString();
        }

        $this->DisplayTemplate('list/error_page.tpl',
            array('Page' => $page),
            $inputValues
        );
    }
}

