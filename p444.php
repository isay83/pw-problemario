<?php
// Jonathan Isay Bernal Arriaga
// 2024 Ene-Jun

// Here is your key: 88d5934e
$queries = trim(fgets(STDIN));

for ($i = 0; $i < $queries; $i++) {
    $entry = trim(fgets(STDIN));
    list($search, $text) = explode(' ', $entry, 2);

    searchMovie($search, $text);
    if ($i < $queries - 1) {
        echo "******\n";
    }
}


function searchMovie($search, $text)
{
    $apiKey = '88d5934e'; // Clave de API de OMDb
    $movies = [];

    if ($search == 't') { // Búsqueda por título
        $url = "http://www.omdbapi.com/?t=" . urlencode($text) . "&apikey=$apiKey";
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        if ($data['Response'] == 'True') {
            $movies[] = ['title' => $data['Title'], 'year' => $data['Year']];
        }
    } elseif ($search == 's') { // Búsqueda por palabra clave
        $url = "http://www.omdbapi.com/?s=" . urlencode($text) . "&apikey=$apiKey";
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        if ($data['Response'] == 'True') {
            foreach ($data['Search'] as $movie) {
                $movies[] = ['title' => $movie['Title'], 'year' => $movie['Year']];
            }
        }
    }

    if (empty($movies)) {
        echo "No Match Picture\n";
    } else {
        usort($movies, function ($a, $b) {
            if ($a['year'] == $b['year']) {
                return strcasecmp($a['title'], $b['title']);
            }
            return $a['year'] - $b['year'];
        });
        foreach ($movies as $movie) {
            echo $movie['title'] . ' ' . $movie['year'] . "\n";
        }
    }
}
