<?php
// Jonathan Isay Bernal Arriaga
// 2024 Ene-Jun

$server = trim(fgets(STDIN));
$user = trim(fgets(STDIN));
$password = trim(fgets(STDIN));
$dataBase = trim(fgets(STDIN));

// Create connection
$connection = mysqli_connect($server, $user, $password, $dataBase);
// Check connection
if ($connection) {
    // Query to determinate winner
    $query = "SELECT CONCAT(u.Nombre, ' ', u.Apellidos) AS usuario, SUM(j.puntos) AS puntos
    FROM Usuarios u
    JOIN (
        SELECT id_usuario AS usuario, puntos FROM BD_Domino_Juegos WHERE ganador = id_usuario
        UNION ALL
        SELECT id_invitado AS usuario, puntos FROM BD_Domino_Juegos WHERE ganador = id_invitado
    ) j ON u.Usuario = j.usuario
    GROUP BY 1
    ORDER BY puntos DESC
    LIMIT 1;
    ";
    // Get result
    $result = mysqli_query($connection, $query);
    // Print data
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo $row["usuario"] . " " . $row["puntos"];
    }
    // Close connection
    mysqli_close($connection);
}
