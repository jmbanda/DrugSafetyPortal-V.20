<?php

class GenericFilterSQLGenerator {

    /** @var EngCommandImp */
    private $commandImp;

    public function __construct($commandImp) {
        $this->commandImp = $commandImp;
        $this->fields = array();
        $this->legacyGenerator = new FilterConditionGenerator($this->commandImp);
    }

    public final function AddField($name, $fieldType) {
        $this->fields[$name] = $fieldType;
    }

    private function CreateLegacyFilter(FilterCondition $filterCondition) {
        switch ($filterCondition->GetOperator()) {
            case FilterConditionOperator::IsBetween:
                return new BetweenFieldFilter(
                    $filterCondition->getValue(0),
                    $filterCondition->getValue(1)
                );
            case FilterConditionOperator::IsNotBetween:
                return new NotPredicateFilter(new BetweenFieldFilter(
                    $filterCondition->getValue(0),
                    $filterCondition->getValue(1)
                ));
            case FilterConditionOperator::Contains:
                return new FieldFilter(
                    '%'.$filterCondition->GetValue(0).'%',
                    'ILIKE'
                );
            case FilterConditionOperator::DoesNotContain:
                return
                    new NotPredicateFilter(
                        new FieldFilter(
                            '%'.$filterCondition->GetValue(0).'%',
                            'ILIKE'
                        )
                    );
            case FilterConditionOperator::BeginsWith:
                return new FieldFilter(
                    $filterCondition->GetValue(0).'%',
                    'ILIKE'
                );
            case FilterConditionOperator::EndsWith:
                return new FieldFilter(
                    '%'.$filterCondition->GetValue(0),
                    'ILIKE'
                );
            case FilterConditionOperator::IsNotLike:
                return
                    new NotPredicateFilter(
                        new FieldFilter(
                            $filterCondition->GetValue(0),
                            'ILIKE'
                        )
                    );
            case FilterConditionOperator::IsBlank:
                return new IsBlankFieldFilter();
            case FilterConditionOperator::IsNotBlank:
                return new NotPredicateFilter(new IsBlankFieldFilter());
            default:
                return new FieldFilter(
                    $filterCondition->GetValue(0),
                    $this->GetConditionOperatorName($filterCondition->GetOperator())
                );
        }
    }

    private function GetConditionOperatorName($operator) {
        switch ($operator) {
            case FilterConditionOperator::Equals:
                return '=';
                break;
            case FilterConditionOperator::DoesNotEqual:
                return '<>';
                break;
            case FilterConditionOperator::IsGreaterThan:
                return '>';
                break;
            case FilterConditionOperator::IsGreaterThanOrEqualTo:
                return '>=';
                break;
            case FilterConditionOperator::IsLessThan:
                return '<';
                break;
            case FilterConditionOperator::IsLessThanOrEqualTo:
                return '<=';
                break;
            case FilterConditionOperator::IsBetween:
                throw new Exception('');
                break;
            case FilterConditionOperator::IsNotBetween:
                throw new Exception('');
                break;
            case FilterConditionOperator::IsLike:
                return 'LIKE';
                break;
            default:
                throw new Exception('GetConditionOperatorName: Unknown operator ' . $operator);
        }
    }

    private function GetFieldValueAsSQL($fieldName, $value) {
        $fieldType = $this->fields[$fieldName];

        return $this->commandImp->GetFieldValueAsSQL(new FieldInfo('', $fieldName, $fieldType, ''), $value);
    }

    private function GetGroupOperatorAsSQL($groupOperator) {
        switch ($groupOperator) {
            case FilterGroupOperator::LogicAnd:
                return 'AND';
                break;
            case FilterGroupOperator::LogicOr:
                return 'OR';
                break;
            case FilterGroupOperator::LogicNone:
                return 'NONE';
                break;
        }

        return '';
    }

    private function GetFilterConditionAsSQL(Dataset $dataset, FilterCondition $filter) {
        if (count($filter->GetValues()) == 0 && count($filter->GetDisplayValues()) > 0) {
            $newFilter = new FilterCondition(
                $dataset->IsLookupFieldByPrimaryName($filter->GetFieldName()),
                $filter->GetOperator(),
                $filter->GetDisplayValue(0)
            );

            $legacyFilter = $this->CreateLegacyFilter($newFilter);
        } else {
            $legacyFilter = $this->CreateLegacyFilter($filter);
        }

        return $this->legacyGenerator->CreateCondition(
            $legacyFilter,
            $dataset->GetFieldInfoByName($filter->GetFieldName())
        );
    }

    /**
     * @param Dataset $dataset
     * @param FilterCondition|FilterGroup $filter
     * @return null|string
     */
    public final function Generate(Dataset $dataset, $filter) {
        if ($filter instanceof FilterCondition) {
            /** @var FilterCondition $filter */
            return $this->GetFilterConditionAsSQL($dataset, $filter);
        } else {
            if ($filter instanceof FilterGroup) {
                $operator = $filter->GetOperator();
                $isNone = $operator == FilterGroupOperator::LogicNone;
                if ($isNone) {
                    $operator = FilterGroupOperator::LogicAnd;
                }
                $conditions = array();
                /** @var FilterGroup $filter */
                for ($i = 0; $i < $filter->GetItemCount(); $i++) {
                    $conditions[] = $this->Generate(
                        $dataset,
                        $filter->GetItem($i)
                    );
                }
                $result = implode(' '.$this->GetGroupOperatorAsSQL($operator).' ', $conditions);

                return ($isNone ? ' NOT ' : '').'('.$result.')';
            }
        }

        return null;
    }
}