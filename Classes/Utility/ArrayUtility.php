<?php
namespace Cjel\TemplatesAide\Utility;

/***
 *
 * This file is part of the "" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2021 Philipp Dieter
 *
 ***/

/**
 *
 */
class ArrayUtility
{
    /**
     * function arrayTobject
     */
    public static function toObject($array) {
        if ($array === []) {
            return (object)$array;
        }
        if (is_array($array)) {
            if (self::isAssoc($array)) {
                return (object) array_map([__CLASS__, __METHOD__], $array);
            } else {
                return array_map([__CLASS__, __METHOD__], $array);
            }
        } else {
            return $array;
        }
    }

    /**
     * remove empty strings
     */
    public static function removeEmptyStrings($array)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = self::removeEmptyStrings($value);
            } else {
                if (is_string($value) && !strlen($value)) {
                    if (is_array($array)) {
                        unset($array[$key]);
                    } else {
                        unset($array->$key);
                    }
                }
            }
        }
        unset($value);
        return $array;
    }

    /**
     *
     */
    public static function isAssoc(array $arr) {
        if (array() === $arr) {
            return false;
        }
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Returns the depth of an array
     */
    function depth(array $array) {
        $depthMax = 1;
        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = self::depth($value) + 1;
                if ($depth > $depthMax) {
                    $depthMax = $depth;
                }
            }
        }
        return $depthMax;
    }

}
