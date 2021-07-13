<?php

if (!function_exists('similar_song')) {
    function similar_song(string $title1, string $title2)
    {
        // remove all parentheses and contents
        $title1 = preg_replace('/\([^\)]+\)/', '', $title1);
        $title2 = preg_replace('/\([^\)]+\)/', '', $title2);

        // remove non-letters
        $title1 = preg_replace('/[\W]/', '', $title1);
        $title2 = preg_replace('/[\W]/', '', $title2);

        return similar_text($title1, $title2);
    }
}
