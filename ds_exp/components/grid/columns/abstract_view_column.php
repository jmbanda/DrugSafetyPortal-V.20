<?php

abstract class AbstractViewColumn
{
    /** @var string */
    private $caption;

    /** @var null|string */
    private $fixedWidth = null;

    /** @var string */
    private $description;

    /** @var Component */
    public $headerControl;

    /** @var Grid */
    private $grid;

    /** @var CustomEditColumn */
    private $editOperationColumn;

    /** @var CustomEditColumn */
    private $insertOperationColumn;

    /** @var bool */
    private $wordWrap;

    /** @var int */
    private $minimalVisibility;

    /**
     * @param string $caption
     */
    public function __construct($caption)
    {
        $this->caption = $caption;
        $this->fixedWidth = null;
        $this->insertOperationColumn = null;
        $this->wordWrap = true;
        $this->minimalVisibility = ColumnVisibility::PHONE;
        $this->nullLabel = function_exists('GetNullLabel') ? GetNullLabel() : null;
    }

    /**
     * @return int
     */
    public function getMinimalVisibility()
    {
        return $this->minimalVisibility;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setMinimalVisibility($value)
    {
        $this->minimalVisibility = $value;

        return $this;
    }

    public function GetGridColumnClass()
    {
        $classes = array();

        $minimalVisibility = $this->getMinimalVisibility();
        if ($minimalVisibility > ColumnVisibility::PHONE) {
            $classes[] = 'hidden-xs';
        }
        if ($minimalVisibility > ColumnVisibility::TABLET) {
            $classes[] = 'hidden-sm';
        }
        if ($minimalVisibility > ColumnVisibility::DESKTOP) {
            $classes[] = 'hidden-md';
        }

        return implode(' ', $classes);
    }

    public function getUserClasses()
    {
        return "";
    }

    public function GetDescription()
    {
        return $this->description;
    }

    public function SetDescription($value)
    {
        $this->description = $value;
    }

    public function GetWordWrap()
    {
        return $this->wordWrap;
    }

    public function SetWordWrap($value)
    {
        $this->wordWrap = $value;
    }

    protected function CreateHeaderControl()
    {
        $result = new HintedTextBox('HeaderControl', $this->GetCaption());
        $result->SetHint($this->GetDescription());

        return $result;
    }

    public function GetName()
    {
        return null;
    }

    public function GetCaption()
    {
        return $this->caption;
    }

    public function SetGrid($value)
    {
        $this->grid = $value;
        $this->caption = $this->grid->GetPage()->RenderText($this->caption);
        if ($this->GetEditOperationColumn() != null) {
            $this->GetEditOperationColumn()->SetGrid($this->grid);
        }
        if ($this->GetInsertOperationColumn() != null) {
            $this->GetInsertOperationColumn()->SetGrid($this->grid);
        }
    }

    /**
     * @return Grid
     */
    public function GetGrid()
    {
        return $this->grid;
    }

    abstract public function GetValue();

    /**
     * @param Renderer $renderer
     * @return void
     */
    abstract public function Accept($renderer);

    public function ProcessMessages()
    {
        if (GetOperation() == OPERATION_AJAX_REQUERT_INLINE_EDIT) {
            if (isset($this->editOperationColumn)) {
                $this->editOperationColumn->ProcessMessages();
            }
        } elseif (GetOperation() == OPERATION_AJAX_REQUERT_INLINE_INSERT) {
            if (isset($this->insertOperationColumn)) {
                $this->insertOperationColumn->ProcessMessages();
            }
        }
    }

    public function GetHeaderControl()
    {
        if (!isset($this->headerControl)) {
            $this->headerControl = $this->CreateHeaderControl();
        }

        return $this->headerControl;
    }

    public function GetAfterRowControl()
    {
        return new NullComponent('');
    }

    public function SetFixedWidth($value)
    {
        $this->fixedWidth = $value;
    }

    public function GetFixedWidth()
    {
        return $this->fixedWidth;
    }

    public function IsDataColumn()
    {
        return false;
    }

    public function GetAlign()
    {
        return null;
    }

    #region Edit operation
    public function SetEditOperationColumn(CustomEditColumn $value)
    {
        $this->editOperationColumn = $value;
    }

    /**
     * @return CustomEditColumn
     */
    public function GetEditOperationColumn()
    {
        return $this->editOperationColumn;
    }

    public function GetEditOperationEditor()
    {
        if (isset($this->editOperationColumn)) {
            return $this->editOperationColumn->GetEditControl();
        } else {
            return null;
        }
    }
    #endregion

    #region Insert operation
    public function SetInsertOperationColumn(CustomEditColumn $value)
    {
        $this->insertOperationColumn = $value;
    }

    /**
     * @return CustomEditColumn
     */
    public function GetInsertOperationColumn()
    {
        return $this->insertOperationColumn;
    }

    public function GetInsertOperationEditor()
    {
        if (isset($this->insertOperationColumn)) {
            return $this->insertOperationColumn->GetEditControl();
        } else {
            return null;
        }
    }

    #endregion

    private function GetTotalValueAsHtml($value)
    {
        $result = $value;
        if (is_numeric($value)) {
            $result = number_format((double)$value, 2);
        }

        return $result;
    }

    private function GetCustomTotalPresentation($originalValue)
    {
        $aggregate = $this->GetGrid()->GetAggregateFor($this)->AsString();
        $result = '';
        $handled = false;
        $this->GetGrid()->OnCustomRenderTotal->Fire(
            array($originalValue, $aggregate, $this->GetName(), &$result, &$handled)
        );
        if ($handled) {
            return $result;
        } else {
            return null;
        }
    }

    public function GetTotalPresentationData($totalValue)
    {
        $result = array();
        $result['IsEmpty'] = !isset($totalValue);

        if (isset($totalValue)) {
            $result['Value'] = $this->GetTotalValueAsHtml($totalValue);
            $result['Aggregate'] = $this->GetGrid()->GetAggregateFor($this)->AsString();
            $result['UserHTML'] = $this->GetCustomTotalPresentation($totalValue);
            $result['CustomValue'] = $result['UserHTML'] != null;
        }

        return $result;
    }

    protected function IsNull()
    {
        return false;
    }

    public function ShowOrderingControl()
    {
        return false;
    }

    protected function GetActualKeys()
    {
        return array(
            'Primary' => false,
            'Foreign' => false
        );
    }

    protected function getSortIndex()
    {
        return null;
    }

    protected function getSortOrderType()
    {
        return null;
    }

    public function GetViewData()
    {
        $result = array(
            'Name' => $this->GetName(),
            'Caption' => $this->GetCaption(),
            'Classes' => $this->GetGridColumnClass(),
            'Sortable' => $this->ShowOrderingControl(),
            'Keys' => $this->GetActualKeys(),
            'Comment' => $this->GetDescription(),
            'Width' => $this->GetFixedWidth(),
            'MinimalVisibility' => $this->getMinimalVisibility(),
            'SortIndex' => $this->getSortIndex(),
            'SortOrderType' => $this->getSortOrderType()
        );

        return $result;
    }

    public function getNullLabel()
    {
        return $this->nullLabel;
    }

    public function setNullLabel($nullLabel)
    {
        $this->nullLabel = $nullLabel;
    }
}