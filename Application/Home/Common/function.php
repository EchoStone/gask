<?php


if (!function_exists('p')) {
    function p($data = [], $isdie = false)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        if ($isdie) {
            die;
        }
    }
}
