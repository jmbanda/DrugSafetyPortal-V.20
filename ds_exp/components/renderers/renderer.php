<?php

// require_once 'components/grid/grid.php';
// require_once 'components/utils/file_utils.php';
// require_once 'components/utils/html_utils.php';

include_once dirname(__FILE__) . '/' . '../grid/grid.php';
include_once dirname(__FILE__) . '/' . '../utils/file_utils.php';
include_once dirname(__FILE__) . '/' . '../utils/html_utils.php';

abstract class EditorsRenderer
{
    abstract public function RenderTimeEdit(TimeEdit $editor);
    abstract public function RenderMaskedEdit(MaskedEdit $editor);
    abstract public function RenderMultiLevelComboBoxEditor(MultiLevelComboBoxEditor $editor);
    abstract public function RenderAutocompleteComboBox(AutocomleteComboBox $comboBox);
    abstract public function RenderCheckBox(CheckBox $checkBox);
    abstract public function RenderColorEdit(ColorEdit $colorEdit);
    abstract public function RenderCheckBoxGroup(CheckBoxGroup $checkBoxGroup);
    abstract public function RenderMultiValueSelect(MultiValueSelect $multiValueSelect);
    abstract public function RenderComboBox(ComboBox $comboBox);
    abstract public function RenderDateTimeEdit(DateTimeEdit $dateTimeEdit);
    abstract public function RenderHtmlWysiwygEditor(HtmlWysiwygEditor $editor);
    abstract public function RenderImageUploader(ImageUploader $imageUploader);
    abstract public function RenderRadioEdit(RadioEdit $radioEdit);
    abstract public function RenderSpinEdit(SpinEdit $spinEdit);
    abstract public function RenderRangeEdit(RangeEdit $rangeEdit);
    abstract public function RenderTextEdit(TextEdit $textEdit);
    abstract public function RenderTextAreaEdit(TextAreaEdit $textArea);
}

abstract class Renderer extends EditorsRenderer
{
    protected $result;
    /** @var Captions */
    private $captions;
    private $renderScripts = true;
    private $renderText = true;
    private $additionalParams = null;

    private $renderingRecordCardView = false;

    protected function DisableCacheControl() {
        // Fixes the IE bug
        // see http://www.alagad.com/blog/post.cfm/error-internet-explorer-cannot-download-filename-from-webserver
        header('Pragma: public');
        header('Cache-Control: max-age=0');
    }

    /**
     * @param Page $page
     */
    protected function SetHTTPContentTypeByPage($page)  {
        $headerString = 'Content-Type: text/html';
        if ($page->GetContentEncoding() != null)
            AddStr($headerString, 'charset=' . $page->GetContentEncoding(), ';');
        header($headerString);
    }

    protected function Captions() {
        return $this->captions;
    }

    /**
     * @return Captions
     */
    public function GetCaptions() {
        return $this->captions;
    }

    private function CreateSmatryObject()  {
        $result = new Smarty();
        $result->template_dir = 'components/templates';

        return $result;
    }

    public function __construct($captions) {
        $this->captions = $captions;
    }

    #region Rendering

    public function DisplayTemplate($TemplateName, $InputObjects, $InputValues) {
        $smarty = $this->CreateSmatryObject();
        foreach($InputObjects as $ObjectName => &$Object)
            $smarty->assign_by_ref($ObjectName, $Object);
        $smarty->assign_by_ref('Renderer', $this);
        $smarty->assign_by_ref('Captions', $this->captions);
        $smarty->assign('RenderScripts', $this->renderScripts);
        $smarty->assign('RenderText', $this->renderText);

        if (isset($this->additionalParams))
        {
            foreach($this->additionalParams as $ValueName => $Value)
            {
                $smarty->assign($ValueName, $Value);
            }
        }

        foreach($InputValues as $ValueName => $Value)
            $smarty->assign($ValueName, $Value);

        $this->result = $smarty->fetch($TemplateName);
    }

    public function Render($Object, $renderScripts = true, $renderText = true, $additionalParams = null) {
        $oldRenderScripts = $this->renderScripts;
        $oldRenderText = $this->renderText;
        $oldAdditionalParams = $this->additionalParams;

        $this->renderScripts = $renderScripts;
        $this->renderText = $renderText;
        $this->additionalParams = array();
        if (isset($additionalParams))
            $this->additionalParams = $additionalParams;

        if (defined('SHOW_VARIABLES') && ($Object instanceof IVariableContainer)) {
            $this->additionalParams['Variables'] = $this->RenderVariableContainer($Object);
        }

        $Object->Accept($this);

        $this->renderScripts = $oldRenderScripts;
        $this->renderText = $oldRenderText;
        $this->additionalParams = $oldAdditionalParams;
        return $this->result;
    }

    public function RenderDef($object, $default = '', $additionalParams = null) {
        if (isset($object))
            return $this->Render($object, true, true, $additionalParams);
        else
            return $default;
    }

    #endregion

    #region Editors

    private function RenderEditor(CustomEditor $editor, $nameInTemplate, $templateFile, $additionalParams = array()) {
        $validatorsInfo = array();
        $validatorsInfo['InputAttributes'] = $editor->GetValidationAttributes();
        $validatorsInfo['InputAttributes'] .= StringUtils::Format(
            ' data-legacy-field-name="%s" data-pgui-legacy-validate="true"',
            $editor->GetFieldName()
        );

        $this->DisplayTemplate(
            'editors/wrap_editor.tpl',
            array(
                $nameInTemplate => $editor
            ),
            array_merge(
                array(
                    'MaxWidth' => $editor->getMaxWidth(),
                    'Validators' => $validatorsInfo,
                    'EditorTemplate' => 'editors/'.$templateFile
                ),
                $additionalParams
            ));
    }

    public final function RenderTimeEdit(TimeEdit $editor)
    {
        $this->RenderEditor($editor, 'TimeEdit', 'time_edit.tpl');
    }

    public final function RenderMaskedEdit(MaskedEdit $editor)
    {
        $this->RenderEditor($editor, 'MaskedEdit', 'masked_edit.tpl');
    }

    public final function RenderMultiLevelComboBoxEditor(MultiLevelComboBoxEditor $editor)
    {
        $this->RenderEditor($editor, 'MultilevelEditor', 'multilevel_selection.tpl');
    }

    public final function RenderAutocompleteComboBox(AutocomleteComboBox $comboBox)
    {
        $this->RenderEditor($comboBox, 'AutocompleteComboBox', 'autocomplete_combo_box.tpl');
    }

    public final function RenderCheckBox(CheckBox $checkBox)
    {
        $this->RenderEditor($checkBox, 'CheckBox', 'check_box.tpl');
    }

    public final function RenderColorEdit(ColorEdit $colorEdit)
    {
        $this->RenderEditor($colorEdit, 'ColorEdit', 'color_edit.tpl');
    }

    public final function RenderCheckBoxGroup(CheckBoxGroup $checkBoxGroup)
    {
        $this->RenderEditor($checkBoxGroup, 'CheckBoxGroup', 'check_box_group.tpl');
    }

    public final function RenderMultiValueSelect(MultiValueSelect $multiValueSelect)
    {
        $this->RenderEditor($multiValueSelect, 'MultiValueSelect', 'multivalue_select.tpl');
    }

    public final function RenderComboBox(ComboBox $comboBox)
    {
        $this->RenderEditor($comboBox, 'ComboBox', 'combo_box.tpl');
    }

    public final function RenderDateTimeEdit(DateTimeEdit $dateTimeEdit)
    {
        $this->RenderEditor($dateTimeEdit, 'DateTimeEdit', 'datetime_edit.tpl');
    }

    public final function RenderHtmlWysiwygEditor(HtmlWysiwygEditor $editor)
    {
        $this->RenderEditor($editor, 'HTMLWysiwygEditor', 'html_wysiwyg_editor.tpl');
    }

    protected function ForceHideImageUploaderImage()
    {
        return false;
    }

    public final function RenderImageUploader(ImageUploader $imageUploader)
    {
        $this->RenderEditor($imageUploader, 'Uploader', 'image_uploader.tpl',
            array('HideImage' => $this->ForceHideImageUploaderImage()));
    }

    public final function RenderRadioEdit(RadioEdit $radioEdit)
    {
        $this->RenderEditor($radioEdit, 'RadioEdit', 'radio_edit.tpl');
    }

    public final function RenderSpinEdit(SpinEdit $spinEdit)
    {
        $this->RenderEditor($spinEdit, 'SpinEdit', 'spin_edit.tpl');
    }

    public final function RenderRangeEdit(RangeEdit $rangeEdit)
    {
        $this->RenderEditor($rangeEdit, 'RangeEdit', 'range_edit.tpl');
    }

    public final function RenderTextEdit(TextEdit $textEdit)
    {
        $this->RenderEditor($textEdit, 'TextEdit', 'text_edit.tpl');
    }

    public final function RenderTextAreaEdit(TextAreaEdit $textArea)
    {
        $this->RenderEditor($textArea, 'TextArea', 'textarea.tpl');
    }

    #endregion

    #region HTML Components

    public function RenderComponent($Component)  {
        $this->result = '';
    }


    /**
     * @param TextBox $textBox
     */
    public function RenderTextBox($textBox)  {
        $this->result = $textBox->GetCaption();
    }

    public function RenderImage($Image)  {
        $this->DisplayTemplate('image.tpl',
            array('Image' => $Image),
            array());
    }

    /**
     * @param CustomHtmlControl $control
     */
    public function RenderCustomHtmlControl($control)  {
        $this->result = $control->GetHtml();
    }

    /**
     * @param Hyperlink $hyperLink
     */
    public function RenderHyperLink($hyperLink)  {
        $this->result = sprintf('<a href="%s">%s</a>%s', $hyperLink->GetLink(), $hyperLink->GetInnerText(), $hyperLink->GetAfterLinkText());
    }

    public function RenderHintedTextBox($textBox)  {
        $this->DisplayTemplate('hinted_text_box.tpl',
            array('TextBox' => $textBox),
            array());
    }

    #endregion

    #region Variables

    public function GetPageVariables(Page $page) {
        if (defined('SHOW_VARIABLES'))
        {
            $this->RenderVariableContainer(
                $page->GetColumnVariableContainer()
                );
            $variables = $this->result;
        }
        else
        {
            $variables = '';
        }
        return $variables;
    }

    public function RenderVariableContainer(IVariableContainer $variableContainer) {
        $values = array();
        $variableContainer->FillVariablesValues($values);
        $this->DisplayTemplate('variables_container.tpl',
            array(),
            array('Variables' => $values)
            );
    }

    #endregion

    #region Columns

    private function GetNullValuePresentation($column)  {
        if ($this->ShowHtmlNullValue()) {
            $nullLabel = $column->getNullLabel();
            if (is_null($nullLabel)) {
                $nullLabel = $this->GetCaptions()->GetMessageString('NullAsString');
            }
            return sprintf('<em class="pgui-null-value">%s</em>', $nullLabel);
        }

        return '';
    }

    protected function GetFriendlyColumnName(AbstractViewColumn  $column) {
        return $column->GetGrid()->GetDataset()->IsLookupField($column->GetName()) ?
            $column->GetGrid()->GetDataset()->IsLookupFieldNameByDisplayFieldName($column->GetName()) :
            $column->GetName();
    }

    /**
     * @param AbstractViewColumn $column
     * @param array $rowValues
     * @return string
     */
    protected function GetCustomRenderedViewColumn(AbstractViewColumn $column, $rowValues) {
        return null;
    }

    /**
     * @param \AbstractViewColumn $column
     * @param array $rowValues
     * @return string
     */
    public final function RenderViewColumn(AbstractViewColumn $column, $rowValues)
    {
        $customValue = $this->GetCustomRenderedViewColumn($column, $rowValues);
        if (isset($customValue)) {
            return $column->GetGrid()->GetPage()->RenderText($customValue);
        }

        return $this->Render($column);
    }

    /**
     * @param AbstractDatasetFieldViewColumn $column
     */
    public final function RenderDatasetFieldViewColumn(AbstractDatasetFieldViewColumn $column)
    {
        $value = $column->GetValue();
        if (!isset($value)) {
            $this->result = $this->GetNullValuePresentation($column);
        } else {
            $this->result = $this->getWrappedViewColumnValue($column, $column->getValue());
        }
    }

    private function getWrappedViewColumnValue($column, $value)
    {
        return $this->viewColumnRenderStyleProperties(
            $column,
            $this->viewColumnRenderHyperlinkProperties($column, $value)
        );
    }

    private function viewColumnRenderHyperlinkProperties($column, $value)
    {
        if ($this->HtmlMarkupAvailable() && !is_null($column->getHrefTemplate())) {
            $href = FormatDatasetFieldsTemplate(
                $column->getDataset(),
                $column->getHrefTemplate()
            );

            return sprintf('<a href="%s" target="%s">%s</a>',
                $href,
                $column->GetTarget(),
                $value
            );
        }

        return $value;
    }

    private function viewColumnRenderStyleProperties($column, $value)
    {
        if (is_null($column->getValue())) {
            return $this->GetNullValuePresentation($column);
        }

        if ($this->HtmlMarkupAvailable()) {
            $style = $this->getColumnStyle($column);

            $customAttributes = '';
            if (!is_null($column->getCustomAttributes())) {
                $customAttributes = ' ' . trim($column->getCustomAttributes());
            }

            if (!empty($style) || !empty($customAttributes)) {
                return sprintf(
                    '<div%s%s>%s</div>',
                    $style,
                    $customAttributes,
                    $value
                );
            }
        }

        return $value;
    }

    private function getColumnStyle($column)
    {
        $styleBuilder = new StyleBuilder();

        if ($column->getBold()) {
            $styleBuilder->Add('font-weight', 'bold');
        }

        if ($column->getItalic()) {
            $styleBuilder->Add('font-style', 'italic');
        }

        if (!is_null($column->getAlign())) {
            $styleBuilder->Add('text-align', $column->getAlign());
        }

        $style = '';
        if (!$styleBuilder->isEmpty() || $column->getInlineStyles()) {
            $style = sprintf(' style="%s%s"', $styleBuilder->GetStyleString(), $column->getInlineStyles());
        }

        return $style;
    }

    /**
     * @param TextViewColumn $column
     */
    public function RenderTextViewColumn(TextViewColumn $column)
    {
        $value = $column->GetValue();
        $dataset = $column->GetDataset();

        $column->BeforeColumnRender->Fire(array(&$value, &$dataset));

        if (!isset($value)) {
            $this->result = $this->GetNullValuePresentation($column);
            return;
        }

        if ($column->GetEscapeHTMLSpecialChars()) {
            $value = htmlspecialchars($value);
        }

        $columnMaxLength = $column->GetMaxLength();

        if ($this->handleLongValuedTextFields() &&
            $this->HttpHandlersAvailable() &&
            $this->ChildPagesAvailable() &&
            isset($columnMaxLength) &&
            isset($value) &&
            StringUtils::StringLength($value, $column->GetGrid()->GetPage()->GetContentEncoding()) > $columnMaxLength)
        {
            $originalValue = $value;
            if ($this->HtmlMarkupAvailable() && $column->GetReplaceLFByBR()) {
                $originalValue = str_replace("\n", "<br/>", $originalValue);
            }

            $value = StringUtils::SubString($value, 0, $columnMaxLength, $column->GetGrid()->GetPage()->GetContentEncoding());

            $value = $this->getWrappedViewColumnValue(
                $column,
                $value
                . '... <a class="js-more-hint" href="' . $column->GetMoreLink() . '">'
                . $this->captions->GetMessageString('more') . '</a>'
                . '<div class="js-more-box hide">' . $originalValue . '</div>'
            );


        } elseif ($this->HtmlMarkupAvailable()) {
            $value = $this->getWrappedViewColumnValue($column, $value);
        }

        if ($this->HtmlMarkupAvailable() && $column->GetReplaceLFByBR()) {
            $value = str_replace("\n", "<br/>", $value);
        }

        $this->result = $value;
    }

    protected function handleLongValuedTextFields() {
        return !$this->renderingRecordCardView;
    }

    /**
     * @param CheckboxViewColumn $column
     */
    public function RenderCheckboxViewColumn(CheckboxViewColumn $column)
    {
        $value = $column->GetValue();

        if (empty($value)) {
            if ($this->HtmlMarkupAvailable()) {
                $this->result = $column->GetFalseValue();
            } else {
                $this->result = 'false';
            }
        } else {
            if ($this->HtmlMarkupAvailable()) {
                $this->result = $column->GetTrueValue();
            } else {
                $this->result = 'true';
            }
        }
    }

    /**
     * @param EmbeddedVideoViewColumn $column
     */
    public function RenderEmbeddedVideoViewColumn(EmbeddedVideoViewColumn $column)
    {
        $value = $column->GetValue();
        if ($value == null) {
            $this->result = $this->GetNullValuePresentation($column);
            return;
        }

        if ($this->HtmlMarkupAvailable() && $this->InteractionAvailable()) {

            $isYoutube = preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $value, $matches);
            if ($isYoutube) {
                $this->result = sprintf(
                    '<div class="pgui-field-embedded-video" data-url="%s">'
                    . '<img class="pgui-field-embedded-video-thumb" src="https://i.ytimg.com/vi/%s/default.jpg">'
                    . '<span class="pgui-field-embedded-video-icon icon-play"></span>'
                    . '</div>',
                    $value,
                    $matches[1]
                );
            }
            else {
                $this->result = sprintf(
                    '<div class="pgui-field-embedded-video" data-url="%s">'
                    . '<img class="pgui-field-embedded-video-preloader" src="components/assets/img/loading.gif">'
                    . '</div>',
                    $value
                );
            }
            return;
        }

        $this->result = $value;
    }

    /**
     * @param ExternalImageColumn $column
     */
    public function RenderExternalImageViewColumn(ExternalImageColumn $column)
    {
        if (is_null($column->GetValue())) {
            $this->result = $this->GetNullValuePresentation($column);
            return;
        }

        if ($this->HtmlMarkupAvailable()) {
            $style = $this->getColumnStyle($column);
            $customAttributes = '';
            if (!is_null($column->getCustomAttributes())) {
                $customAttributes = ' ' . trim($column->getCustomAttributes());
            }

            $this->result = $this->viewColumnRenderHyperlinkProperties($column, sprintf(
                '<img src="%s" alt="%s"%s%s>',
                $column->getWrappedValue(),
                FormatDatasetFieldsTemplate($column->getDataset(), $column->getHintTemplate()),
                $customAttributes,
                $style
            ));
            return;
        }

        $this->result = $column->getWrappedValue();
    }

    /**
     * @param CurrencyViewColumn $column
     */
    public function RenderCurrencyViewColumn(CurrencyViewColumn $column)
    {
        $this->RenderNumberViewColumn($column, $column->getCurrencySign());
    }

    /**
     * @param PercentViewColumn $column
     */
    public function RenderPercentViewColumn(PercentViewColumn $column)
    {
        $this->RenderNumberViewColumn($column, null, '%');
    }

    /**
     * @param StringTransformViewColumn $column
     */
    public function RenderStringTransformViewColumn(StringTransformViewColumn $column)
    {
        if (is_null($column->GetValue())) {
            $this->result = $this->GetNullValuePresentation($column);
            return;
        }

        $this->result = $this->getWrappedViewColumnValue($column, call_user_func(
            $column->getStringTransformFunction(),
            $column->getValue()
        ));
    }

    /**
     * @param NumberViewColumn $column
     */
    public function RenderNumberViewColumn(NumberViewColumn $column, $prefix = null, $suffix = null)
    {
        if (is_null($column->GetValue())) {
            $this->result = $this->GetNullValuePresentation($column);
            return;
        }

        $this->result = $this->getWrappedViewColumnValue($column, sprintf(
            '%s%s%s',
            $prefix,
            number_format(
                (double) $column->GetValue(),
                $column->GetNumberAfterDecimal(),
                $column->GetDecimalSeparator(),
                $column->GetThousandsSeparator()
            ),
            $suffix
        ));
    }

    /**
     * @param ExternalAudioFileColumn $column
     */
    public function RenderExternalAudioViewColumn(ExternalAudioFileColumn $column)
    {
        if (is_null($column->GetValue())) {
            $this->result = $this->GetNullValuePresentation($column);
            return;
        }

        if ($this->HtmlMarkupAvailable() && $this->InteractionAvailable()) {
            $this->result = sprintf(
                '<audio controls><source src="%s" type="audio/mpeg">Your browser does not support the audio element.</audio>',
                $column->getWrappedValue()
            );
            return;
        }

        $this->result = $column->getWrappedValue();
    }

    /**
     * @param DownloadDataColumn $column
     */
    public function RenderDownloadDataViewColumn(DownloadDataColumn $column)
    {
        if (is_null($column->GetValue())) {
            $this->result = $this->GetNullValuePresentation($column);
            return;
        }

        if ($this->HtmlMarkupAvailable() && $this->HttpHandlersAvailable() && $this->InteractionAvailable()) {
            $this->result = sprintf(
                '<i class="icon-download"></i>&nbsp;<a target="_blank" title="download" href="%s">%s</a>',
                $column->GetDownloadLink(),
                $column->GetLinkInnerHtml()
            );
            return;
        }

        $this->result = $this->Captions()->GetMessageString('BinaryDataCanNotBeExportedToXls');
    }

    /**
     * @param DownloadExternalDataColumn $column
     */
    public function RenderDownloadExternalDataViewColumn(DownloadExternalDataColumn $column)
    {
        if (is_null($column->GetValue())) {
            $this->result = $this->GetNullValuePresentation($column);
            return;
        }

        if ($this->HtmlMarkupAvailable() && $this->HttpHandlersAvailable() && $this->InteractionAvailable()) {
            $this->result = StringUtils::Format(
                '<i class="icon-download"></i>&nbsp;<a target="_blank" title="%s" href="%s">%s</a>',
                FormatDatasetFieldsTemplate($column->getDataset(), $column->getDownloadLinkHintTemplate()),
                $column->getWrappedValue(),
                $this->captions->GetMessageString('Download')
            );
            return;
        }

        $this->result = $column->getWrappedValue();
    }

    /**
     * @param ImageViewColumn $column
     */
    public function RenderImageViewColumn(ImageViewColumn $column)
    {
        if (is_null($column->GetValue())) {
            $this->result = $this->GetNullValuePresentation($column);
            return;
        }

        if ($this->HtmlMarkupAvailable() && $this->HttpHandlersAvailable()) {
            if($column->GetEnablePictureZoom()) {
                $this->result = sprintf(
                    '<a class="image gallery-item" href="%s" title="%s"><img data-image-column="true" src="%s" alt="%s"></a>',
                    $column->GetFullImageLink(),
                    $column->GetImageHint(),
                    $column->GetImageLink(),
                    $column->GetImageHint()
                );
                return;
            }

            $this->result = $this->getWrappedViewColumnValue($column, sprintf(
                '<img data-image-column="true" src="%s" alt="%s">',
                $column->GetImageLink(),
                $column->GetImageHint()
            ));
            return;
        }

        $this->result = $this->Captions()->GetMessageString('BinaryDataCanNotBeExportedToXls');
    }

    #endregion

    #region Pages

    /**
     * @param PageNavigator $PageNavigator
     */
    public function RenderPageNavigator($PageNavigator) { }

    public abstract function RenderPage(Page $Page);

    /**
     * @param CustomErrorPage $errorPage
     */
    public function RenderCustomErrorPage($errorPage)  {
        $this->DisplayTemplate('security_error_page.tpl',
            array(
                'Page' => $errorPage
            ),
            array(
                'JavaScriptMain' => '',
                'Authentication' => $errorPage->GetAuthenticationViewData(),
                'common' => $errorPage->GetCommonViewData(),

                'Message' => $errorPage->GetMessage(),
                'Description' => $errorPage->GetDescription()
            )
        );
    }

    public function RenderDetailPage(DetailPage $detailPage)  {
        $this->SetHTTPContentTypeByPage($detailPage);
        $customParams = array();
        $layoutTemplate = $detailPage->GetCustomTemplate(PagePart::Layout, PageMode::ViewAll, 'common/layout.tpl',
            $customParams);

        $Grid = $this->Render($detailPage->GetGrid());
        $this->DisplayTemplate('list/detail_page.tpl',
            array(
                'common' => $detailPage->GetListViewData(),
                'LayoutTemplateName' => $layoutTemplate,
                'Page' => $detailPage,
                'DetailPage' => $detailPage
            ),
            array_merge($customParams,
                array(
                    'Authentication' => $detailPage->GetAuthenticationViewData(),
                    'Grid' => $Grid
                )
            )
        );
    }

    /**
     * @param DetailPageEdit $DetailPage
     */
    public function RenderDetailPageEdit($DetailPage) { }


    //TODO: introduce ILoginPage and change the generated code accordingly
    /**
     * @param LoginPage $loginPage
     */
    public function RenderLoginPage($loginPage)  {
        $this->SetHTTPContentTypeByPage($loginPage);

        $customParams = array();
        $template = $loginPage->GetCustomTemplate(PagePart::LoginPage, 'login_page.tpl', $customParams);

        $this->DisplayTemplate($template,
            array(
                'common' => $loginPage->getCommonViewData(),
                'Page' => $loginPage,
                'LoginControl' => $loginPage->GetLoginControl()),
            array_merge($customParams,
                array(
                    'Title' => $loginPage->GetTitle()
                )
            )
        );
    }

    #endregion

    #region Page parts

    /**
     * @param ShowTextBlobHandler $textBlobViewer
     */
    public function RenderTextBlobViewer($textBlobViewer)  {
        $this->DisplayTemplate('text_blob_viewer.tpl',
            array(
                'Viewer' => $textBlobViewer,
                'Page' => $textBlobViewer->GetParentPage()),
            array());
    }

    public abstract function RenderGrid(Grid $Grid);

    public function RenderVerticalGrid(VerticalGrid $grid) {
        $this->SetHTTPContentTypeByPage($grid->GetGrid()->GetPage());
        $modalFormSize = $grid->GetGrid()->GetPage()->getModalFormSize();

        if ($grid->GetState() == VerticalGridState::JSONResponse) {
            $this->result = SystemUtils::ToXML($grid->GetResponse());
        }
        else if ($grid->GetState() == VerticalGridState::DisplayGrid) {
            $hiddenValues = array(OPERATION_PARAMNAME => OPERATION_COMMIT);
            AddPrimaryKeyParametersToArray($hiddenValues, $grid->GetGrid()->GetDataset()->GetPrimaryKeyValues());


            $customParams = array();
            $this->DisplayTemplate(
                $grid->GetGrid()->GetPage()->GetCustomTemplate(PagePart::VerticalGrid, PageMode::ModalEdit,
                    'edit/vertical_grid.tpl', $customParams),
                array('Grid' => $grid->GetGrid()->GetModalEditViewData($this)),
                array_merge($customParams, array(
                    'HiddenValues' => $hiddenValues,
                    'modalSizeClass' => $this->getModalSizeClass($modalFormSize),
                ))
            );
        }
        else if ($grid->GetState() == VerticalGridState::DisplayInsertGrid) {
            $hiddenValues = array(OPERATION_PARAMNAME => OPERATION_COMMIT);

            $customParams = array();
            $this->DisplayTemplate(
                $grid->GetGrid()->GetPage()->GetCustomTemplate(PagePart::VerticalGrid, PageMode::ModalInsert,
                    'insert/vertical_grid.tpl', $customParams),
                array('Grid' => $grid->GetGrid()->GetModalInsertViewData($this)),
                array_merge($customParams, array(
                    'HiddenValues' => $hiddenValues,
                    'modalSizeClass' => $this->getModalSizeClass($modalFormSize),
                ))
            );
        }
        else if ($grid->GetState() == VerticalGridState::DisplayCopyGrid) {
            $hiddenValues = array(OPERATION_PARAMNAME => OPERATION_COMMIT);

            $customParams = array();
            $this->DisplayTemplate(
                $grid->GetGrid()->GetPage()->GetCustomTemplate(PagePart::VerticalGrid, PageMode::ModalInsert,
                    'insert/vertical_grid.tpl', $customParams),
                array('Grid' => $grid->GetGrid()->GetModalInsertViewData($this)),
                array_merge($customParams, array(
                    'HiddenValues' => $hiddenValues,
                    'modalSizeClass' => $this->getModalSizeClass($modalFormSize),
                ))
            );
        }
    }

    public function RenderRecordCardView(RecordCardView $recordCardView) {
        $Grid = $recordCardView->GetGrid();

        $this->renderingRecordCardView = true;

        try {

            $customParams = array();
            $this->DisplayTemplate(
                $Grid->GetPage()->GetCustomTemplate(PagePart::VerticalGrid, PageMode::ModalView,
                    'view/record_card_view.tpl', $customParams),
                array(),
                array_merge($customParams,
                    array(
                        'Grid' => $Grid->getModalViewSingleRowViewData($this),
                        'modalSizeClass' => $this->getModalSizeClass($Grid->GetPage()->getModalViewSize()),
                    )
                )
            );

        } catch (Exception $e) {
            $this->renderingRecordCardView = false;
            throw $e;
        }

        $this->renderingRecordCardView = false;
    }

    private function getModalSizeClass($modalSize)
    {
        $map = array(
            Modal::SIZE_SM => 'modal-sm',
            Modal::SIZE_MD => '',
            Modal::SIZE_LG => 'modal-lg',
        );

        return $map[$modalSize];
    }

    public function RenderPageList(PageList $pageList) {

        $customParams = array();
        $defaultTemplate = $pageList->isTypeSidebar() ? 'page_list_sidebar.tpl' : 'page_list_menu.tpl';
        $template = $pageList->GetParentPage()->GetCustomTemplate(PagePart::PageList, null, $defaultTemplate,
            $customParams);
        $this->DisplayTemplate($template,
            array(
                'PageList' => $pageList),
            array_merge($customParams,
                array(
                    'Authentication' => $pageList->GetParentPage()->GetAuthenticationViewData(),
                    'List' => $pageList->GetViewData()
                )
            )
        );
    }

    /**
     * @param LoginControl $loginControl
     */
    public function RenderLoginControl($loginControl)  {
        $customParams = array();
        $template = $loginControl->GetCustomTemplate(PagePart::LoginControl, 'login_control.tpl', $customParams);
        $this->DisplayTemplate($template,
            array('LoginControl' => $loginControl),
            $customParams);
    }

    public function RenderSimpleSearch($searchControl)  {
        // TODO: remove simple search control
    }

    public function RenderAdvancedSearchControl($advancedSearchControl) {
        //TODO: remove advance search control
    }

    #endregion

    /**
     * @param Chart $chart
     */
    public function renderChart(Chart $chart)
    {
        $this->DisplayTemplate('charts/chart.tpl', array(), array(
            'type' => $chart->getChartType(),
            'chart' => $chart->getViewData(),
        ));
    }

    #region Column rendering options

    protected function ShowHtmlNullValue()  {
        return false;
    }

    protected function HttpHandlersAvailable()
    {
        return true;
    }

    protected function HtmlMarkupAvailable()
    {
        return true;
    }

    protected function ChildPagesAvailable()
    {
        return true;
    }

    protected function InteractionAvailable()
    {
        return true;
    }

    #endregion

}

class SingleAdvancedSearchRenderer extends Renderer
{
    function RenderPage(Page $Page)
    {
        $this->SetHTTPContentTypeByPage($Page);
        $Page->BeforePageRender->Fire(array(&$Page));

        if (isset($Page->AdvancedSearchControl))
        {
            $Page->AdvancedSearchControl->SetHidden(false);
            $Page->AdvancedSearchControl->SetAllowOpenInNewWindow(false);
            $linkBuilder = $Page->CreateLinkBuilder();
            $Page->AdvancedSearchControl->SetTarget($linkBuilder->GetLink());
        }
        $this->DisplayTemplate('common/single_advanced_search_page.tpl',
            array(
                    'Page' => $Page
                    ),
                array(
                    'AdvancedSearch' => $this->RenderDef($Page->AdvancedSearchControl),
                    'PageList' => $this->RenderDef($Page->GetReadyPageList())
                    )
                );
    }

    public function RenderDetailPageEdit($Page)
    {
        $this->SetHTTPContentTypeByPage($Page);
        $Page->BeforePageRender->Fire(array(&$Page));

        if (isset($Page->AdvancedSearchControl))
        {
            $Page->AdvancedSearchControl->SetHidden(false);
            $Page->AdvancedSearchControl->SetAllowOpenInNewWindow(false);
            $linkBuilder = $Page->CreateLinkBuilder();
            $Page->AdvancedSearchControl->SetTarget($linkBuilder->GetLink());
        }
        $this->DisplayTemplate('common/single_advanced_search_page.tpl',
            array(
                    'Page' => $Page
                    ),
                array(
                    'AdvancedSearch' => $this->RenderDef($Page->AdvancedSearchControl),
                    'PageList' => $this->RenderDef($Page->GetReadyPageList())
                    )
                );
    }

    function RenderGrid(Grid $Grid)
    {
        $this->result = '';
    }

    public function RenderFilterBuilderControl(FilterBuilderControl $filterBuilderControl) {

    }
}

?>