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

    // Base date for cash cut-off
    $cutoffDate = '2019-01-20';

    // Query for the accumulated amount of all debts
    $totalAdeudosQuery = "
        SELECT SUM(Monto) AS totalAdeudos
        FROM BD_PagoServ_Facturas
        WHERE fecha_Vencimiento <= '$cutoffDate'
          AND (fecha_Pago IS NULL OR fecha_Pago = '0000-00-00')
    ";

    $totalAdeudosResult = executeQuery($connection, $totalAdeudosQuery);
    $totalAdeudos = $totalAdeudosResult[0]['totalAdeudos'];

    echo "Total de Adeudos: $" . number_format($totalAdeudos, 2) . "\n";

    // Inquiry to obtain the breakdown of overdue invoices by customer
    $clientesQuery = "
        SELECT u.Usuario, CONCAT(u.Apellidos, ' ', u.Nombre) AS nombreCompleto, SUM(f.Monto) AS totalAdeudo
        FROM Usuarios u
        JOIN BD_PagoServ_Facturas f ON u.Usuario = f.id_Cliente
        WHERE f.fecha_Vencimiento <= '$cutoffDate'
          AND (f.fecha_Pago IS NULL OR f.fecha_Pago = '0000-00-00')
        GROUP BY u.Usuario
        ORDER BY nombreCompleto ASC
    ";

    $clientesResult = executeQuery($connection, $clientesQuery);

    foreach ($clientesResult as $cliente) {
        echo "Cliente: " . $cliente['nombreCompleto'] . " Total de Adeudo: $" . number_format($cliente['totalAdeudo'], 2) . "\n";

        // Inquiry to obtain the breakdown of overdue invoices for each customer
        $facturasQuery = "
            SELECT s.Nombre AS descripcion, f.Monto AS total, f.fecha_Vencimiento AS fechaVenc
            FROM BD_PagoServ_Facturas f
            JOIN BD_PagoServ_Servicios s ON f.id_Servicio = s.id
            WHERE f.id_Cliente = '" . $cliente['Usuario'] . "'
              AND f.fecha_Vencimiento <= '$cutoffDate'
              AND (f.fecha_Pago IS NULL OR f.fecha_Pago = '0000-00-00')
            ORDER BY f.fecha_Vencimiento ASC
        ";

        $facturasResult = executeQuery($connection, $facturasQuery);

        foreach ($facturasResult as $factura) {
            echo "Servicio: " . $factura['descripcion'] . " Total: $" . number_format($factura['total'], 2) . " Fecha Venc.: " . $factura['fechaVenc'] . "\n";
        }
    }

    // close connection
    mysqli_close($connection);
}
