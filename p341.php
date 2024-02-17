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
  // 1
  echo getMaxBetDate($connection) . PHP_EOL;
  // 2
  echo getMaxLoser($connection) . PHP_EOL;
  // 3
  echo getTopBet($connection) . PHP_EOL;
  // 4
  echo getCompanyProfit($connection) . PHP_EOL;
  // 5
  echo getWeekendBets($connection) . PHP_EOL;
  // 6
  printWrongBankAccounts($connection);
  // 7
  echo getTopDebtor($connection);

  // close connection
  mysqli_close($connection);
}

// 1. highest betting day
function getMaxBetDate($connection)
{
  $sql = "SELECT DATE_FORMAT(Fecha, '%d/%b/%Y') AS Fecha, SUM(Apuesta) AS Total 
          FROM BD_Apuesta  
          GROUP BY Fecha
          ORDER BY Total DESC
          LIMIT 1";

  $result = mysqli_query($connection, $sql);
  $row = mysqli_fetch_assoc($result);

  return $row['Fecha'] . " $" . formatMoney($row['Total']);
}
// 2. user with the highest net loss 
function getMaxLoser($connection)
{
  $sql = "SELECT CONCAT(Usuarios.Nombre, ' ', Usuarios.Apellidos) AS Nombre, SUM(
                CASE
                  WHEN BD_Apuesta.IdGanador = Usuarios.Usuario THEN 0.9*BD_Apuesta.Apuesta
                  ELSE -0.5*BD_Apuesta.Apuesta 
                END) AS Neto
          FROM BD_Apuesta
          INNER JOIN Usuarios
            ON BD_Apuesta.Retador = Usuarios.Usuario
               OR BD_Apuesta.Invitado = Usuarios.Usuario
          GROUP BY Nombre
          ORDER BY Neto ASC
          LIMIT 1";

  $result = mysqli_query($connection, $sql);
  $row = mysqli_fetch_assoc($result);

  return $row['Nombre'] . " $" . formatMoney($row['Neto']);
}
// 3. major bet, who and winner
function getTopBet($connection)
{
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

  $result = mysqli_query($connection, $sql);
  $row = mysqli_fetch_assoc($result);

  return $row['Retador'] . " vs " . $row['Invitado'] . " gano " . $row['Ganador'];
}
// 4. earnings
function getCompanyProfit($connection)
{
  $sql = "SELECT SUM(BD_Apuesta.Apuesta) * 0.1 AS Ganancia
          FROM BD_Apuesta";

  $result = mysqli_query($connection, $sql);
  $row = mysqli_fetch_assoc($result);

  return "$" . formatMoney($row['Ganancia']);
}
// 5. money wagered on weekends
function getWeekendBets($connection)
{
  $sql = "SELECT SUM(BD_Apuesta.Apuesta) AS Total
          FROM BD_Apuesta
          WHERE DAYOFWEEK(BD_Apuesta.Fecha) IN (1,7)";

  $result = mysqli_query($connection, $sql);
  $row = mysqli_fetch_assoc($result);

  return "$" . formatMoney($row['Total']);
}
// 6. bank accounts with error
function printWrongBankAccounts($connection)
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

  $result = mysqli_query($connection, $sql);

  $output = '';

  while ($row = mysqli_fetch_assoc($result)) {

    $output .= $row['IdRegistro'] . " " . $row['Banco'] . " " . $row['Nombre'] . " " . $row['Longitud'];

    $output .= " : ";
  }

  $output = trim($output, " :");

  echo $output;
}
// 7. user with the highest bank debta
function getTopDebtor($connection)
{

  $sql = "SELECT CONCAT(U.Nombre, ' ', U.Apellidos) AS Nombre,  
  GROUP_CONCAT(B.Banco SEPARATOR ' ') AS Bancos,
  SUM(CASE WHEN CB.Saldo < 0 THEN CB.Saldo ELSE 0 END) AS DeudaTotal   
FROM Cuentas_Bancarias CB
JOIN Usuarios U ON CB.IdUser = U.Usuario
JOIN Bancos B ON CB.IdBanco = B.Id
GROUP BY Nombre
ORDER BY DeudaTotal DESC
LIMIT 1;";

  $result = mysqli_query($connection, $sql);

  $row = mysqli_fetch_assoc($result);

  if ($row) {
    return $row['Nombre'] . " " . $row['Bancos'] . " $" . formatMoney($row['DeudaTotal']);
  } else {
    return "";
  }
}

function formatMoney($amount)
{
  return number_format($amount, 2, '.', ',');
}

?>
```

```php