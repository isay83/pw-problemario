<?php
// Jonathan Isay Bernal Arriaga
// 2024 Ene-Jun

// Entry connection
$entry = trim(fgets(STDIN));

list($server, $user, $password, $dbname) = explode(' ', $entry, 4);
// localhost root root p399
// Create connection
$conn = mysqli_connect($server, $user, $password, $dbname);

// Verify connectivity
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error() . "\n");
}

// 1: El número de citas que cumplen con la restricción de que los participantes de la cita coincidan con por lo menos el 50% de las preferencias (Que tengan los mismos gustos).
$sql = "SELECT COUNT(*) AS numero_citas
FROM (
    SELECT c.Id
    FROM citas_citas c
    INNER JOIN citas_gustos g1 ON c.Id_Invita = g1.Id_Usuario
    INNER JOIN citas_gustos g2 ON c.Id_Acepta = g2.Id_Usuario AND g1.Id_Gusto = g2.Id_Gusto
    GROUP BY c.Id
    HAVING COUNT(DISTINCT g1.Id_Gusto) >= COUNT(DISTINCT g2.Id_Gusto) * 0.5
) t;";

$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo $row["numero_citas"] . "\n";
} else {
    echo "0\n";
}


// 2: Cuantos usuarios no tienen citas registradas.
$sql = "SELECT COUNT(*) AS usuarios_sin_citas
FROM citas_usuarios u
LEFT JOIN citas_citas c ON c.Id_Invita = u.Id OR c.Id_Acepta = u.Id
WHERE c.Id IS NULL;";

$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo $row["usuarios_sin_citas"] . "\n";
} else {
    echo "0\n";
}


// 3: Cuantas citas se concretaron (estatus de Hecho), por tendencia.
$sql = "SELECT t.Nombre AS Tendencia, COUNT(*) AS Citas_Hechas
FROM citas_citas c
JOIN citas_usuarios u ON c.Id_Invita = u.Id OR c.Id_Acepta = u.Id
JOIN citas_tendencia t ON u.Id_Tendencia = t.Id
WHERE c.Id_Estatus = 2
GROUP BY t.Nombre
ORDER BY Tendencia;";

$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    // Storing results in an array
    $rows = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row["Tendencia"] . " " . $row["Citas_Hechas"];
    }

    // Print results with format
    echo implode(", ", $rows);
    echo "\n";
} else {
    echo "0\n";
}


// 4: Qué preferencias destacan por Tendencia.
$sql = "SELECT 
    Tendencia,
    Preferencia
FROM (
    SELECT 
        ct.Nombre AS Tendencia, 
        ccg.Nombre AS Preferencia,
        COUNT(*) AS Ocurrencias,
        ROW_NUMBER() OVER (PARTITION BY ct.Nombre ORDER BY COUNT(*) DESC) AS rn
    FROM 
        citas_cata_gustos ccg
    INNER JOIN 
        citas_gustos cg ON ccg.Id = cg.Id_Gusto
    INNER JOIN 
        citas_usuarios cu ON cg.Id_Usuario = cu.Id
    INNER JOIN 
        citas_tendencia ct ON cu.Id_Tendencia = ct.Id
    GROUP BY 
        ct.Nombre, ccg.Nombre
) AS subquery
WHERE rn = 1;";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Storing results in an array
    $rows = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row["Tendencia"] . " " . $row["Preferencia"];
    }

    // Print results with a format
    echo implode(", ", $rows);
    echo "\n";
} else {
    echo "0\n";
}


// 5: Cuantas citas se registraron mal? Los heteros son muy reservados y pueden tener citas con gente Bi pero no con la otra categoria.
$sql = "SELECT COUNT(*) AS citas_registradas_mal
FROM citas_citas c
JOIN citas_usuarios u1 ON c.Id_Invita = u1.Id
JOIN citas_usuarios u2 ON c.Id_Acepta = u2.Id
WHERE (u1.Id_Tendencia = 1 AND u2.Id_Tendencia = 3) OR (u1.Id_Tendencia = 3 AND u2.Id_Tendencia = 1);";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo $row["citas_registradas_mal"] . "\n";
} else {
    echo "0\n";
}


// 6: Cuantas Citas fueron exitosas, esto resulta de la calificación de ambas personas si ambos calificaron 6 o más la cita fue un éxito.
$sql = "SELECT COUNT(*) AS citas_exitosas
FROM citas_citas
WHERE Calif1 >= 6 AND Calif2 >= 6;";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo $row["citas_exitosas"];
} else {
    echo "0\n";
}

mysqli_close($conn);
