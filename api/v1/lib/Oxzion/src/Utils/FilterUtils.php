<?php

namespace Oxzion\Utils;

class FilterUtils
{
    static public function paginate($params)
    {
        $pageSize = 20;
        $offset = 0;
        $sort = "id";
        $where = "";

        if(!empty($params))
        {
            if(!empty($params['limit']))
                $pageSize = $params['limit'];
            if(!empty($params['skip']))
                $offset = $params['skip'];
            if(isset($params['sort'])){
                $sortArray = json_decode($params['sort'],true);
                $sort = FilterUtils::sortArray($sortArray);
            }
            if(isset($params['filter'])){
                $filterArray = call_user_func_array('array_merge',json_decode($params['filter'],true));
                $filterlogic = isset($filterArray['logic']) ? $filterArray['logic'] : "AND" ;
                $filterList = $filterArray['filters'];
                $where = " WHERE ".FilterUtils::filterArray($filterList,$filterlogic);
            }
        }
        return $paginate = array('pageSize' => $pageSize, 'offset' => $offset, 'sort' => $sort, 'where' => $where);
    }

    public static function filterArray($filterList, $filterlogic, $fieldMap = array())
    {
        $where = "";
        for ($x=0;$x<sizeof($filterList);$x++) {
            $operator = $filterList[$x]['operator'];
            $field = $filterList[$x]['field'];
            $field = isset($fieldMap[$field]) ? $fieldMap[$field] : $field;
            $value = $filterList[$x]['value'];
            if ($operator == 'startswith') {
                $operatorp1 = '';
                $operatorp2 = '%';
                $operation = ' like ';
            } elseif ($operator == 'endswith') {
                $operatorp1 = '%';
                $operatorp2 = '';
                $operation = ' like ';
            } elseif ($operator == 'eq') {
                $operation = ' = ';
                $operatorp1 = '';
                $operatorp2 = '';
            } elseif ($operator == 'neq') {
                $operation = ' <> ';
                $operatorp1 = '';
                $operatorp2 = '';
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
