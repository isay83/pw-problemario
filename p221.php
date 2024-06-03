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
    // function to execute a query and return the answer
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

    $query = "SELECT s.Nombre AS nombreServicio, 
               COALESCE(f.cantidad, 0) AS facturas,
               COALESCE(f.monto, 0) AS monto
        FROM BD_PagoServ_Servicios s
        LEFT JOIN (
            SELECT id_Servicio, 
                   COUNT(*) AS cantidad, 
                   SUM(Monto) AS monto
            FROM BD_PagoServ_Facturas
            WHERE (fecha_Pago NOT IN ('0000-00-00', '0000-00-00') OR fecha_Pago IS NULL) AND fecha_Pago <= fecha_Vencimiento
            GROUP BY id_Servicio
        ) f ON s.id = f.id_Servicio
        ORDER BY s.Nombre;";


    $result = executeQuery($connection, $query);
    // format
    foreach ($result as $row) {
        echo $row['nombreServicio'] . ':' . $row['facturas'] . ':$' . number_format($row['monto'], 2, '.', '') . "\n";
    }


    // close connection
    mysqli_close($connection);
}
