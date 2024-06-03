<?php
// Jonathan Isay Bernal Arriaga
// 2024 Ene-Jun

function getData()
{
    while ($date = trim(fgets(STDIN))) {
        curl($date);
    }
}

function curl($date)
{
    // Create a cURL handle
    $ch = curl_init();

    // Set the URL to fetch
    $url = "https://tigger.celaya.tecnm.mx/conacad/recursos/juez/acceConacad.php?fecha=$date";
    curl_setopt($ch, CURLOPT_URL, $url);

    // Set the user agent (optional)
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');

    // Set the option to return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the request
    $response = curl_exec($ch);

    checkResponse($response);

    // Close the cURL handle
    curl_close($ch);
}

function checkResponse($response)
{
    // Check if the request was successful
    if ($response !== false) {
        echo "Success\n";

        // Patterns to extract the required information
        $patternTotal = '/<span class="fs-2hx fw-bolder text-dark me-2 lh-1">\s*([\d,\.]+)\s*<\/span>/';
        $patternM = '/<div class="d-flex justify-content-between w-100 mt-auto mb-2">\s*<span class="fw-boldest fs-6 text-dark">\s*Men<\/span>\s*<span class="fw-bolder fs-6 text-gray-400">([\d]+)%<\/span>\s*<\/div>/';
        $patternH = '/<div class="d-flex justify-content-between w-100 mt-auto mb-2">\s*<span class="fw-boldest fs-6 text-dark">\s*Women<\/span>\s*<span class="fw-bolder fs-6 text-gray-400">([\d]+)%<\/span>\s*<\/div>/';
        $patternO = '/<div class="d-flex justify-content-between w-100 mt-auto mb-2">\s*<span class="fw-boldest fs-6 text-dark">\s*Others<\/span>\s*<span class="fw-bolder fs-6 text-gray-400">([\d]+)%<\/span>\s*<\/div>/';

        // Patterns for hours and values
        $patternHour = '/<div class="text-gray-500 flex-grow-1 me-4">(.*?)<\/div>/';
        $patternValue = '/<div class="fw-boldest text-gray-700 text-xxl-end">(\d+)<\/div>/';

        // Extract data
        $totalAccess = getStats($response, $patternTotal);
        $menPercentage = getStats($response, $patternM);
        $womenPercentage = getStats($response, $patternH);
        $othersPercentage = getStats($response, $patternO);
        $hours = array_slice(getHours($response, $patternHour), 2);
        $values = array_slice(getValues($response, $patternValue), 2);

        // Calculate gender access numbers
        $menAccess = round(($menPercentage / 100) * $totalAccess);
        $womenAccess = round(($womenPercentage / 100) * $totalAccess);
        $othersAccess = round(($othersPercentage / 100) * $totalAccess);

        // Determine peak hour and value
        $peakIndex = array_search(max($values), $values);
        $peakHour = $hours[$peakIndex];
        $peakValue = $values[$peakIndex];

        // Output the results
        echo "$totalAccess {$menAccess}H {$womenAccess}M {$othersAccess}O {$peakHour} {$peakValue}\n";
    }
}

function getStats($response, $pattern)
{
    if (preg_match($pattern, $response, $matches)) {
        return str_replace(',', '', $matches[1]); // Remove commas from the number
    }
    return '0';
}

function getHours($response, $pattern)
{
    if (preg_match_all($pattern, $response, $matches)) {
        return $matches[1];
    }
    return [];
}

function getValues($response, $pattern)
{
    if (preg_match_all($pattern, $response, $matches)) {
        return $matches[1];
    }
    return [];
}

getData();
