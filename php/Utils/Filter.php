<?php
namespace SuperHeroList\Utils;

class Filter
{
    public function filter(array $data) {
        
        $data = array_map(function ($val) {
            $args = [
                'id'                    => ['filter' => FILTER_VALIDATE_INT,
                                            'flags' => FILTER_REQUIRE_SCALAR],
                'name'                  => FILTER_SANITIZE_STRING,
                'surname'               => FILTER_SANITIZE_STRING,
                'fathername'            => FILTER_SANITIZE_STRING,
                'registration_number'   => FILTER_VALIDATE_INT,
                'power_description'     => FILTER_SANITIZE_STRING,
                'category'              => ['filter' => FILTER_VALIDATE_INT,
                                            'options' => ['min_range' => 1, 'max_range' => 3],
                                            'flags' => FILTER_REQUIRE_SCALAR],
                'subtype'               => FILTER_SANITIZE_STRING
            ];
            return filter_var_array($val, $args);
        }, $data);
           
        return $data;
    }
}