<?php

// Jonathan Isay Bernal Arriaga
// 2024 Ene-Jun

$server = trim(fgets(STDIN)); // server
$user = trim(fgets(STDIN)); // user
$pass = trim(fgets(STDIN)); // password
$dataBase = trim(fgets(STDIN)); // database name

// connection to db
$connection = mysqli_connect($server, $user, $pass, $dataBase);

// connection successful
if ($connection) {
    // query database tables
    $query = "SHOW TABLES";
    $result = mysqli_query($connection, $query);

    // query successful
    if ($result) {
        // storing table names in an array
        $tables = array();
        while ($row = mysqli_fetch_row($result)) {
            $tables[] = $row[0];
        }

        // descending order
        rsort($tables);

        // separating names
        echo implode(":", $tables);
    }else {
        die("Query error" . mysqli_error($cone));
    }

    // close connection
    mysqli_close($connection);
} else {
    die("Connection error: " . mysqli_connect_error());
}

?>