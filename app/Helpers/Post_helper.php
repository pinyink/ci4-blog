<?php

function categories($keyword = null) : array
{
    $array = [
        '-' => '-',
        'h1' => 'h1',
        'h2' => 'h2',
        'h3' => 'h3',
        'h4' => 'h4',
        'h5' => 'h5',
        'h6' => 'h6',
        'p' => 'p',
        'img' => 'img',
        'pre' => 'pre',
    ];
    if ($keyword == null) {
        return $array;
    } else {
        if (isset($array[$keyword])) {
            return $array[$keyword];
        } else {
            return $array['-'];
        }
    }
}

function categoriesEncode($key, $content)
{
    if (in_array($key, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p'])) {
        return "<$key>$content</$key>";
    } else {
        return '';
    }
}
?>