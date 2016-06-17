<?php

class FilterGroup {

    /** @var int */
    private $operator;

    /** @var FilterGroup[]|FilterCondition[] */
    private $items;

    /**
     * @param int|null $operator
     */
    public function __construct($operator = null) {
        $this->operator = $operator ? $operator : FilterGroupOperator::LogicAnd;
        $this->items = array();
    }

    public function GetOperator() {
        return $this->operator;
    }

    /**
     * @param FilterGroup|FilterCondition $item
     */
    public function AddItem($item) {
        $this->items[] = $item;
    }

    /**
     * @param mixed $data
     * @param string $contentEncoding
     */
    public function LoadFromData($data, $contentEncoding) {
        $this->items = array();

        $this->operator = intval($data->operator);
        foreach ($data->items as $itemData) {
            $item = null;
            if (intval($itemData->type) == FilterItemType::Condition) {
                $item = new FilterCondition();
            } else {
                if (intval($itemData->type) == FilterItemType::Group) {
                    $item = new FilterGroup();
                }
            }
            $item->LoadFromData($itemData, $contentEncoding);
            $this->AddItem($item);
        }
    }

    /**
     * @param string $contentEncoding
     * @return array
     */
    public function SaveToArray($contentEncoding) {

        $itemsData = array();
        $items = $this->GetItems();
        for ($i = 0; $i < count($items); $i++) {
            $itemsData[] = $items[$i]->SaveToArray($contentEncoding);
        }

        return array(
            'type' => FilterItemType::Group,
            'operator' => $this->GetOperator(),
            'items' => $itemsData
        );
    }

    /**
     * @return FilterGroup[]|FilterCondition[]
     */
    public function GetItems() {
        return $this->items;
    }

    /**
     * @return int
     */
    public function GetItemCount() {
        return count($this->items);
    }

    /**
     * @param int $index
     * @return FilterCondition|FilterGroup
     */
    public function GetItem($index) {
        return $this->items[$index];
    }

    /**
     * @param Captions $captions
     * @return string
     */
    public final function GetOperatorAsString(Captions $captions) {
        switch ($this->GetOperator()) {
            case FilterGroupOperator::LogicAnd:
                return $captions->GetMessageString('OperatorStringAnd');
                break;
            case FilterGroupOperator::LogicOr:
                return $captions->GetMessageString('OperatorStringOr');
                break;
            case FilterGroupOperator::LogicNone:
                return $captions->GetMessageString('OperatorStringNone');
                break;
        }

        return '';
    }

    /**
     * @param Captions $captions
     * @return string
     */
    public function AsString(Captions $captions) {
        $items = $this->GetItems();
        $isNone = $this->GetOperator() == FilterGroupOperator::LogicNone;
        $operator = $isNone ? $captions->GetMessageString('OperatorStringAnd') : $this->GetOperatorAsString($captions);

        $conditionList = array();
        for ($i = 0; $i < count($items); $i++) {
            $condition = $items[$i]->AsString($captions);
            if (count($items) > 1) {
                $condition = '('.$condition.')';
            }
            $conditionList[] = $condition;
        }

        $result = implode(' '.$operator.' ', $conditionList);

        return $isNone ? $captions->GetMessageString('OperatorStringNone').' ('.$result.')' : $result;
    }

    /**
     * @param int $value
     */
    public function SetOperator($value) {
        $this->operator = $value;
    }

    public function IsEmpty() {
        $items = $this->GetItems();
        for ($i = 0; $i < count($items); $i++) {
            if (!$items[$i]->IsEmpty()) {
                return false;
            }
        }

        return true;
    }
}
