<?php

class FilterCondition {

    private $fieldName;
    private $operator;
    private $values;
    private $displayValues;

    public function __construct($fieldName = null, $operator = null, $values = null) {
        $this->fieldName = $fieldName ? $fieldName : '';
        $this->operator = $operator ? $operator : FilterConditionOperator::Equals;

        if (is_null($values)) {
            $this->values = array();
        } elseif (!is_array($values)) {
            $this->values = array($values);
        } else {
            $this->values = $values;
        }

        $this->displayValues = $this->values;
    }

    public function GetFieldName() {
        return $this->fieldName;
    }

    public function SetFieldName($value) {
        $this->fieldName = $value;
    }

    public function GetOperator() {
        return $this->operator;
    }

    public function SetOperator($value) {
        $this->operator = $value;
    }

    public function GetValues() {
        return $this->values;
    }

    public function GetValue($index) {
        if (!array_key_exists($index, $this->values)) {
            return null;
        }

        return $this->values[$index];
    }

    public function SetValues(array $values) {
        $this->values = $values;
    }

    public function GetDisplayValues() {
        return $this->displayValues;
    }

    public function GetDisplayValue($index) {
        if (!array_key_exists($index, $this->displayValues)) {
            return null;
        }

        return $this->displayValues[$index];
    }

    public function LoadFromData($data, $contentEncoding) {
        $this->fieldName = $data->fieldName;
        $this->operator = $data->operator;
        $this->values = StringUtils::getEncodedArray($data->values, 'UTF-8', $contentEncoding);
        $refinedDisplayValues = $this->refineDisplayValuesIfNecessary($data);
        $this->displayValues = StringUtils::getEncodedArray($refinedDisplayValues, 'UTF-8', $contentEncoding);
    }

    /**
     * @param StdClass $data
     * @return array
     */
    private function refineDisplayValuesIfNecessary($data) {
        if (property_exists($data, 'displayValues')) {
            return $data->displayValues;
        }
        elseif (property_exists($data, 'displayValue')) { // Filter was set in the previous version
            return array_fill(0, 1, $data->displayValue);
        }
        else {
            return array();
        }
    }

    public function SaveToArray($contentEncoding) {
        return array(
            'type' => FilterItemType::Condition,
            'fieldName' => $this->GetFieldName(),
            'operator' => $this->GetOperator(),
            'displayValues' => StringUtils::getEncodedArray($this->GetDisplayValues(), $contentEncoding, 'UTF-8'),
            'values' => StringUtils::getEncodedArray($this->GetValues(), $contentEncoding, 'UTF-8')
        );
    }

    public function IsEmpty() {
        return false;
    }

    public final function AsString(Captions $captions) {
        return trim(sprintf(
            '%s %s %s',
            $this->GetFieldName(),
            $this->GetOperatorAsString($captions),
            implode(' ' . $captions->GetMessageString('And') . ' ', $this->GetDisplayValues())
        ));
    }

    private final function GetOperatorAsString(Captions $captions) {
        $mapping = array(
            FilterConditionOperator::Equals => 'FilterOperatorEqualsShort',
            FilterConditionOperator::DoesNotEqual => 'FilterOperatorDoesNotEqualShort',
            FilterConditionOperator::IsGreaterThan => 'FilterOperatorIsGreaterThanShort',
            FilterConditionOperator::IsGreaterThanOrEqualTo => 'FilterOperatorIsGreaterThanOrEqualToShort',
            FilterConditionOperator::IsLessThan => 'FilterOperatorIsLessThanShort',
            FilterConditionOperator::IsLessThanOrEqualTo => 'FilterOperatorIsLessThanOrEqualToShort',
            FilterConditionOperator::IsBetween => 'FilterOperatorIsBetweenShort',
            FilterConditionOperator::IsNotBetween => 'FilterOperatorIsNotBetweenShort',
            FilterConditionOperator::Contains => 'FilterOperatorContainsShort',
            FilterConditionOperator::DoesNotContain => 'FilterOperatorDoesNotContainShort',
            FilterConditionOperator::BeginsWith => 'FilterOperatorBeginsWithShort',
            FilterConditionOperator::EndsWith => 'FilterOperatorEndsWithShort',
            FilterConditionOperator::IsLike => 'FilterOperatorIsLikeShort',
            FilterConditionOperator::IsNotLike => 'FilterOperatorIsNotLikeShort',
            FilterConditionOperator::IsBlank => 'FilterOperatorIsBlankShort',
            FilterConditionOperator::IsNotBlank => 'FilterOperatorIsNotBlankShort',
        );

        return array_key_exists($this->getOperator(), $mapping)
            ? $captions->GetMessageString($mapping[$this->getOperator()])
            : '';
    }
}