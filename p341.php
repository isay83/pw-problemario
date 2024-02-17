<?php
// Jonathan Isay Bernal Arriaga
// 2024 Ene-Jun

$server = trim(fgets(STDIN)); // server (localhost)
$user = trim(fgets(STDIN)); // user (root)
$pass = trim(fgets(STDIN)); // password ()
$dataBase = trim(fgets(STDIN)); // database name (p341)

// connection to db
$connection = mysqli_connect($server, $user, $pass, $dataBase);

if ($connection) {

  echo getMaxBetDate($conn) . PHP_EOL;

  // close connection
  mysqli_close($connection);
}

// 1. Día con mayor dinero apostado
function getMaxBetDate($conn)
{
  $sql = "SELECT DATE_FORMAT(Fecha, '%d/%b/%Y') AS Fecha, SUM(Apuesta) AS Total 
          FROM BD_Apuesta  
          GROUP BY Fecha
          ORDER BY Total DESC
          LIMIT 1";

  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);

  return $row['Fecha'] . " $" . $row['Total'];
}




// 2. Usuario con mayor pérdida neta 
function getMaxLoser($conn)
{
  $sql = "SELECT Usuarios.Nombre, SUM(
                CASE
                  WHEN BD_Apuesta.IdGanador = Usuarios.Usuario THEN 0.9*BD_Apuesta.Apuesta
                  ELSE -0.5*BD_Apuesta.Apuesta 
                END) AS Neto
          FROM BD_Apuesta
          INNER JOIN Usuarios
            ON BD_Apuesta.Retador = Usuarios.Usuario
               OR BD_Apuesta.Invitado = Usuarios.Usuario
          GROUP BY Usuarios.Nombre
          ORDER BY Neto ASC
          LIMIT 1";

  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);

  return $row['Nombre'] . " $" . $row['Neto'];
}

echo getMaxLoser($conn) . PHP_EOL;


// 3. Apuesta mayor, quienes y ganador
function getTopBet($conn)
{

  // SQL query   
  $sql = "SELECT CONCAT(U1.Nombre, ' ', U1.Apellidos) AS Retador, 
                CONCAT(U2.Nombre, ' ', U2.Apellidos) AS Invitado,
                BD_Apuesta.Apuesta AS Monto,
                CONCAT(UG.Nombre, ' ', UG.Apellidos) AS Ganador
           FROM BD_Apuesta
           INNER JOIN Usuarios U1
             ON BD_Apuesta.Retador = U1.Usuario 
           INNER JOIN Usuarios U2
             ON BD_Apuesta.Invitado = U2.Usuario
           INNER JOIN Usuarios UG
             ON BD_Apuesta.IdGanador = UG.Usuario
           ORDER BY BD_Apuesta.Apuesta DESC
           LIMIT 1";

  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);

  return $row['Retador'] . " vs " . $row['Invitado'] . " gano " . $row['Ganador'];
}

echo getTopBet($conn) . PHP_EOL;


// 4. Ganancias de la empresa
function getCompanyProfit($conn)
{
  $sql = "SELECT SUM(BD_Apuesta.Apuesta) * 0.1 AS Ganancia
          FROM BD_Apuesta";

  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);

  return "$" . $row['Ganancia'];
}

echo getCompanyProfit($conn) . PHP_EOL;


// 5. Dinero apostado fines de semana
function getWeekendBets($conn)
{
  $sql = "SELECT SUM(BD_Apuesta.Apuesta) AS Total
          FROM BD_Apuesta
          WHERE DAYOFWEEK(BD_Apuesta.Fecha) IN (1,7)";

  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);

  return $row['Total'];
}

echo getWeekendBets($conn) . PHP_EOL;


// 6. Cuentas bancarias con error
function printWrongBankAccounts($conn)
{
  $sql = "SELECT CB.Id AS IdRegistro, B.Banco, U.Nombre, 
                CASE
                  WHEN CHAR_LENGTH(CB.CLABE) <> 18 THEN CHAR_LENGTH(CB.CLABE)
                  ELSE 16 
                END AS Longitud  
           FROM Cuentas_Bancarias CB
           INNER JOIN Bancos B
              ON B.Id = CB.IdBanco
           INNER JOIN Usuarios U
              ON U.Usuario = CB.IdUser
           WHERE CHAR_LENGTH(CB.CLABE) NOT IN (18,16)";

  $result = mysqli_query($conn, $sql);

  while ($row = mysqli_fetch_assoc($result)) {
    echo $row['IdRegistro'] . " " . $row['Banco'] . " " . $row['Nombre'] . " " . $row['Longitud'] . PHP_EOL;
  }
}

printWrongBankAccounts($conn);


// 7. Usuario con mayor deuda bancaria
function getTopDebtor($conn)
{

  $sql = "SELECT CONCAT(U.Nombre, ' ', U.Apellidos) AS Nombre,
                 SUM(CASE WHEN CB.Saldo < 0 THEN CB.Saldo ELSE 0 END) AS Deuda
          FROM Cuentas_Bancarias CB
          INNER JOIN Usuarios U
            ON CB.IdUser = U.Usuario
          GROUP BY U.Nombre
          ORDER BY Deuda ASC
          LIMIT 1";

  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);

  return $row['Nombre'] . " $" . $row['Deuda'];
}

echo getTopDebtor($conn);
