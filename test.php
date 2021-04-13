<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
$data = [
    'name' => 'имя',
    'surname' => 'фамилия', 
    'fathername' => 'отчество',
    'registration_number' => '123456',
    'power_description' => 'описание супер силы', 
    'category' => '2', 
    'subtype' => 'подтип'
];
$args = [
    'id'                    => FILTER_VALIDATE_INT,
    'name'                  => FILTER_SANITIZE_STRING,
    'surname'               => FILTER_SANITIZE_STRING,
    'fathername'            => FILTER_SANITIZE_STRING,
    'registration_number'   => FILTER_SANITIZE_STRING,
    'power_description'     => FILTER_SANITIZE_STRING,
    'category'              => ['filter' => FILTER_VALIDATE_INT,
                                'options' => ['min_range' => 1, 'max_range' => 2]],
    'subtype'               => FILTER_SANITIZE_STRING
];

var_dump(filter_var_array($data, $args));