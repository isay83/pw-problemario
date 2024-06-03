<?php
// Jonathan Isay Bernal Arriaga
// 2024 Ene-Jun

// Database connection details
$server = trim(fgets(STDIN)); // Server (localhost)
$user = trim(fgets(STDIN)); // User (root)
$pass = trim(fgets(STDIN)); // Password (password)
$database = trim(fgets(STDIN)); // Database name (p217)

// Establish database connection
$connection = mysqli_connect($server, $user, $pass, $database);

if ($connection) {
    // Function to execute a query and return the result
    function executeQuery($conn, $query)
    {
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Query error: " . mysqli_error($conn));
        }

        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }

        return $rows;
    }

    // Function to normalize a domino tile
    function normalizeDomino($domino)
    {
        $values = explode(':', $domino);
        if (count($values) !== 2) {
            return false; // Invalid domino tile format
        }
        sort($values);
        return implode(':', $values);
    }

    // Function to check for duplicate tiles
    function hasDuplicateTiles($sequence)
    {
        $tiles = explode(" ", $sequence);
        $normalizedTiles = [];

        foreach ($tiles as $tile) {
            $normalizedTile = normalizeDomino($tile);
            if ($normalizedTile === false) {
                return true; // Invalid domino tile format
            }
            if (in_array($normalizedTile, $normalizedTiles)) {
                return true; // Duplicate tile found
            }
            $normalizedTiles[] = $normalizedTile;
        }
        return false;
    }

    // Function to check for sequence errors
    function hasSequenceError($sequence)
    {
        $tiles = explode(" ", $sequence);
        $prevValue = null;

        foreach ($tiles as $tile) {
            $values = explode(":", $tile);
            if (count($values) !== 2) {
                return true; // Invalid domino tile format
            }
            list($left, $right) = $values;
            if ($prevValue !== null && $prevValue !== $left) {
                return true; // Sequence error
            }
            $prevValue = $right;
        }
        return false;
    }

    // Query to get game details
    $query = "SELECT j.id AS game_id, 
               CONCAT(u1.Nombre, ' ', u1.Apellidos) AS inviter_name, 
               CONCAT(u2.Nombre, ' ', u2.Apellidos) AS invitee_name, 
               j.secuencia, 
               j.id_estatus 
    FROM Juegos j
    INNER JOIN Usuarios u1 ON j.id_usuario = u1.Usuario
    INNER JOIN Usuarios u2 ON j.id_invitado = u2.Usuario
    ORDER BY j.id;";

    $games = executeQuery($connection, $query);

    // Process each game and check for errors
    foreach ($games as $game) {
        $game_id = $game['game_id'];
        $inviter_name = $game['inviter_name'];
        $invitee_name = $game['invitee_name'];
        $sequence = $game['secuencia'];
        $status_id = $game['id_estatus'];

        // Only check games that are not completed
        if ($status_id != 1) {
            $duplicateTiles = hasDuplicateTiles($sequence);
            $sequenceError = hasSequenceError($sequence);

            if ($duplicateTiles) {
                echo "$game_id:$inviter_name:$invitee_name:Ficha Duplicada\n";
            } elseif ($sequenceError) {
                echo "$game_id:$inviter_name:$invitee_name:Secuencia Mal\n";
            }
        }
    }

    // Close the database connection
    mysqli_close($connection);
}
