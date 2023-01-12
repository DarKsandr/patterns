<?php

function dump(...$args){
    foreach($args as $arg){
        echo "<pre>";
        print_r($arg);
        echo "</pre>";
    }
}

function dd(...$args){
    dump($args);
    exit;
}