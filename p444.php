<?php
// Jonathan Isay Bernal Arriaga
// 2024 Ene-Jun

// Here is your key: 88d5934e
$queries = trim(fgets(STDIN));
getData($queries);

function getData($queries)
{
    for ($i = 0; $i < $queries; $i++) {
        $entry = trim(fgets(STDIN));
        list($search, $text) = explode(' ', $entry, 2);

        print_r($search);
        print_r($text);
    }
}

function searchMovie($search, $text)
{
}
