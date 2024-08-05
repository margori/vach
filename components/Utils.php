<?php

namespace app\components;

class Utils {

    // Function to calculate square of value - mean
    public static function square($x, $mean) {
        return pow($x - $mean, 2);
    }

    // Function to calculate standard deviation (uses square)
    public static function standard_deviation($array) {
        // square root of sum of squares devided by N-1
        return sqrt(array_sum(array_map("self::square", $array, array_fill(0, count($array), (array_sum($array) / count($array))))) / (count($array) - 1));
    }

    // Function to calculate variance (uses square)
    public static function variance($array) {
        // square root of sum of squares devided by N-1
        return sqrt(array_sum(array_map("self::square", $array, array_fill(0, count($array), (array_sum($array) / count($array))))) / count($array));
    }

    public static function absolute_mean($array) {
        if (count($array) == 0) {
            return 0;
        }

        $sum = 0;
        foreach ($array as $element) {
            $sum += abs($element);
        }

        return $sum / count($array);
    }

    public static function productivityText($productivity, $meanProductivity, $deltaProductivity) {
        if ($productivity < $meanProductivity) {
            if ($productivity < $meanProductivity - $deltaProductivity) {
                return 'Baja';
            } else {
                return 'Media baja';
            }
        } else {
            if ($productivity <= $meanProductivity + $deltaProductivity) {
                return 'Media alta';
            } else {
                return 'Alta';
            }
        }
    }

}

?>
