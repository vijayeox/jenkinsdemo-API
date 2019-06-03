<?php

namespace Oxzion\Utils;

class FilterUtils{
	
	static public function filterArray($filterList, $filterlogic, $fieldMap = array()){
		$where = "";
		for($x=0;$x<sizeof($filterList);$x++){
            $operator = $filterList[$x]['operator'];
            $field = $filterList[$x]['field'];
            $field = isset($fieldMap[$field]) ? $fieldMap[$field] : $field;
            $value = $filterList[$x]['value'];
            if($operator == 'startswith'){
                        $operatorp1 = '';
                        $operatorp2 = '%';
                        $operation = ' like ';
                    } else if($operator == 'endswith'){
                        $operatorp1 = '%';
                        $operatorp2 = '';
                        $operation = ' like ';
                    } else if($operator == 'eq'){
                        $operation = ' = ';
                        $operatorp1 = '';
                        $operatorp2 = '';
                    } else if($operator == 'neq'){
                        $operation = ' <> ';
                        $operatorp1 = '';
                        $operatorp2 = '';
                    } else if($operator == 'contains'){
                        $operatorp1 = '%';
                        $operatorp2 = '%';
                        $operation = ' like ';
                    } else if ($operator == 'doesnotcontain'){
                        $operatorp1 = '%';
                        $operatorp2 = '%';
                        $operation = ' NOT LIKE ';
                    } else if($operator == 'isnull' || $operator == 'isempty'){
                        $value='';
                        $operation = ' = ';
                    } else if($operator == 'isnotnull' || $operator == 'isnotempty'){
                        $value='';
                        $operation = ' <> ';
                    } else if($operator == 'lte'){
                        $operation = ' <= ';
                    } else if($operator == 'lt'){
                        $operation = ' < ';
                    }  else if($operator == 'gt'){
                        $operation = ' > ';
                    } else if($operator == 'gte'){
                        $operation = ' >= ';
                    } else {
                        $operatorp1 = '%';
                        $operatorp2 = '%';
                        $operation = ' like ';
                    }
                    if($value == 'null'||$value==null||$value==''&&$operator){
                        if($operator == 'isnotnull' || $operator == 'isnotempty'){
                            $where .= strlen($where) == 0  ? $field." is NOT NULL" : " ".$filterlogic." ".$field." is NOT NULL";
                        } else {
                            $where .= strlen($where) == 0 ? $field." is NULL" : " ".$filterlogic." ".$field." is NULL";
                        }
                    } else {
                        $where .= strlen($where) == 0 ? $field." ".$operation."'".$operatorp1.$value.$operatorp2."'" : " ".$filterlogic." ".$field." ".$operation."'".$operatorp1.$value.$operatorp2."'";
                    }
                    if (substr($where, 0, strlen(" ".$filterlogic)) == " ".$filterlogic){
                        $where=substr_replace($where,'',1,strlen(" ".$filterlogic));
                    }
            }
            return $where;
	}

	static public function sortArray($sort,$fieldMap = array()){
		 $sSort = "";
                    foreach ($sort as $key => $value) { 
                        if($value['dir'] == 'dsc'){
                            $value['dir'] = 'desc';
                        }
                        $value['field'] = isset($fieldMap[$value['field']]) ? $fieldMap[$value['field']] : $value['field'];
                        $sSort .= strlen($sSort) == 0 ? $value['field']." ". $value['dir'] : " ," .$value['field']." ". $value['dir'];
                    }

         return $sSort;
	}


}

?>