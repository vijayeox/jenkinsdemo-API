<?php

namespace Oxzion\Utils;

class ArrayUtils
{

    /**
     * Get the difference between two array of one-dimensional matrix. It checks the size of the array
     * and does the difference based on the bigger array
     * @Utils
     * @Ref: UserService/getAppsWithoutAccessForUser
     * @method checkDiffMultiArray
     * @param $array1 and $array2
     * @return Array with the unique values from bigger array
     */
    public static function checkDiffMultiArray($array1, $array2)
    {
        $len1 = sizeof($array1);
        $len2 = sizeof($array2);
        if ($len1 < $len2) {
            $result = array_diff($array2, $array1); //If the length of second array is bigger than the first
        } else {
            $result = array_diff($array1, $array2); //If the length of first array is bigger than the second
        }
        return $result;
    }

    public static function multiDimensionalSearch($array, $field, $value)
    {
        foreach ($array as $key => $item) {
            if ($item[$field] === $value) {
                return $item;
            }

        }
        return false;
    }

    public static function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
