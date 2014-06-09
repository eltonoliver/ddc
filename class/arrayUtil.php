<?php
    class ArrayUtil {
        public static function ForEachFn(Array &$array, $fn,$chave) {
            $newArray = array();
            foreach ($array as $key => $value) {
                $fn($key, $value);
                $newArray[$key] = c2sdecrypt($value,$chave);
            }
            $array = $newArray;
        }

        public static function arrayDecript(Array &$array, $fn,$chave) {
            foreach ($array as $key => $value) {
                $fn($key, $value);
                $array[$key] = c2sdecrypt($value,$chave);
            }
        }
    }
?>