<?php

//exsample :: do_shortcode("[supsystic-tables-cell id=3 row=2 col=B]")
//do_shortcodeの擬似プログラム
function do_shortcode($content) {
    $results = [];
    preg_match("/^.*\s(id=[\w]*)\s(row=[\w]*)\s(col=[\w]*)/", $content, $matches);
    if(count($matches) === 4) {
        foreach(array_slice($matches, 1) as $value) {
            $result = explode("=", $value);
            $results[ $result[0] ] = $result[1];
        }
    }
    return $results;
}

?>
