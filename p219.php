<?php
// Jonathan Isay Bernal Arriaga
// 2024 Ene-Jun

$server = trim(fgets(STDIN)); // server (localhost)
$user = trim(fgets(STDIN)); // user (root)
$pass = trim(fgets(STDIN)); // password ()
$database = trim(fgets(STDIN)); // database name (p219)

// connection to db
$connection = mysqli_connect($server, $user, $pass, $database);

if ($connection) {

    // Function to execute a query and return the answer
    function executeQuery($conn, $query)
    {
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Error en la consulta: " . mysqli_error($conn));
        }

        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }

        return $rows;
    }

    // Query to obtain primary key
    $primaryKeyQuery = "SELECT COLUMN_NAME, COLUMN_TYPE
                        FROM INFORMATION_SCHEMA.COLUMNS
                        WHERE TABLE_SCHEMA = '$database'
                        AND TABLE_NAME = 'BD_PagoServ_Facturas'
                        AND COLUMN_KEY = 'PRI'";

    $primaryKeyResult = executeQuery($connection, $primaryKeyQuery);

    echo "Nombre de llave primaria: ";
    if (!empty($primaryKeyResult)) {
        $row = $primaryKeyResult[0];
        echo $row['COLUMN_NAME'] . " [" . $row['COLUMN_TYPE'] . "]";
    } else {
        echo "No se encontró una llave primaria";
    }
    echo "\n";

    // Query to obtain the foreign keys
    $foreignKeysQuery = "SELECT kcu.COLUMN_NAME, kcu.REFERENCED_TABLE_NAME, kcu.REFERENCED_COLUMN_NAME, col.COLUMN_TYPE
                         FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
                         JOIN INFORMATION_SCHEMA.COLUMNS col ON kcu.TABLE_SCHEMA = col.TABLE_SCHEMA
                            AND kcu.TABLE_NAME = col.TABLE_NAME
                            AND kcu.COLUMN_NAME = col.COLUMN_NAME
                         WHERE kcu.TABLE_SCHEMA = '$database'
                            AND kcu.TABLE_NAME = 'BD_PagoServ_Facturas'
                            AND kcu.REFERENCED_TABLE_NAME IS NOT NULL
                         ORDER BY kcu.COLUMN_NAME";

    $foreignKeysResult = executeQuery($connection, $foreignKeysQuery);

    echo "Foraneas:\n";
    if (!empty($foreignKeysResult)) {
        foreach ($foreignKeysResult as $row) {
            echo "Nombre:" . $row['COLUMN_NAME'] . " <=> Tabla Referenciada:" . $row['REFERENCED_TABLE_NAME'] . " <=> CampoForaneo:" . $row['REFERENCED_COLUMN_NAME'] . " <=> [" . $row['COLUMN_TYPE'] . "]\n";
        }
    } else {
        echo "No se encontraron llaves foráneas";
    }
    // close connection
    mysqli_close($connection);
}
