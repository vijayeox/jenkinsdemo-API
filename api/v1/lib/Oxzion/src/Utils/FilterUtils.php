<?php

namespace Oxzion\Utils;

class FilterUtils
{
    public static function filterArray($filterList, $filterlogic, $fieldMap = array())
    {
        $where = "";
        for ($x=0;$x<sizeof($filterList);$x++) {
            $operator = $filterList[$x]['operator'];
            $field = $filterList[$x]['field'];
            $field = isset($fieldMap[$field]) ? $fieldMap[$field] : $field;
            $value = $filterList[$x]['value'];
            $operatorp1 = '';
            $operatorp2 = '';
            if ($operator == 'startswith') {
                $operatorp2 = '%';
                $operation = ' like ';
            } elseif ($operator == 'endswith') {
                $operatorp1 = '%';
                $operation = ' like ';
            } elseif ($operator == 'eq') {
                $operation = ' = ';
            } elseif ($operator == 'neq') {
                $operation = ' <> ';
            } elseif ($operator == 'contains') {
                $operatorp1 = '%';
                $operatorp2 = '%';
                $operation = ' like ';
            } elseif ($operator == 'doesnotcontain') {
                $operatorp1 = '%';
                $operatorp2 = '%';
                $operation = ' NOT LIKE ';
            } elseif ($operator == 'isnull' || $operator == 'isempty') {
                $value='';
                $operation = ' = ';
            } elseif ($operator == 'isnotnull' || $operator == 'isnotempty') {
                $value='';
                $operation = ' <> ';
            } elseif ($operator == 'lte') {
                $operation = ' <= ';
            } elseif ($operator == 'lt') {
                $operation = ' < ';
            } elseif ($operator == 'gt') {
                $operation = ' > ';
            } elseif ($operator == 'gte') {
                $operation = ' >= ';
            } else {
                $operatorp1 = '%';
                $operatorp2 = '%';
                $operation = ' like ';
            }
            if ($value == 'null'||$value==null||$value==''&&$operator) {
                if ($operator == 'isnotnull' || $operator == 'isnotempty') {
                    $where .= strlen($where) == 0  ? $field." is NOT NULL" : " ".$filterlogic." ".$field." is NOT NULL";
                } else {
                    $where .= strlen($where) == 0 ? $field." is NULL" : " ".$filterlogic." ".$field." is NULL";
                }
            } else {
                $where .= strlen($where) == 0 ? $field." ".$operation."'".$operatorp1.$value.$operatorp2."'" : " ".$filterlogic." ".$field." ".$operation."'".$operatorp1.$value.$operatorp2."'";
            }
            if (substr($where, 0, strlen(" ".$filterlogic)) == " ".$filterlogic) {
                $where=substr_replace($where, '', 1, strlen(" ".$filterlogic));
            }
        }
        return $where;
    }

    public static function sortArray($sort, $fieldMap = array())
    {
        $sSort = "";
        foreach ($sort as $key => $value) {
            if ($value['dir'] == 'dsc') {
                $value['dir'] = 'desc';
            }
            $value['field'] = isset($fieldMap[$value['field']]) ? $fieldMap[$value['field']] : $value['field'];
            $sSort .= strlen($sSort) == 0 ? $value['field']." ". $value['dir'] : " ," .$value['field']." ". $value['dir'];
        }

        return $sSort;
    }
}
