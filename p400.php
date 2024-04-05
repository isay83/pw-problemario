<?php
// Jonathan Isay Bernal Arriaga
// 2024 Ene-Jun



// Example input
/*
$tableName = 'Empleados';
$numRecords = 2;
$numFields = 6;
*/
/*$fields = [
    ['name' => 'Nombre', 'type' => 'char', 'extraData' => 'nombre'],
    ['name' => 'Telefono', 'type' => 'char', 'extraData' => 'telefono'],
    ['name' => 'Prueba', 'type' => 'date'],
    ['name' => 'Edad', 'type' => 'int', 'extraData' => '18 34'],
    ['name' => 'Fecha', 'type' => 'date', 'extraData' => '2020-01-01 2024-12-31'],
    ['name' => 'Referencias', 'type' => 'char']
];*/

$entry = trim(fgets(STDIN));

$table = explode(' ', $entry);
// Table name
$tableName = $table[0];
// Records
$numRecords = $table[1];
// Fields
$numFields = $table[2];

$fields = generateFields($numFields);

function generateFields($numFields)
{
    $fields = [];
    for ($i = 0; $i < $numFields; $i++) {
        $field = trim(fgets(STDIN));
        $fieldData = explode(' ', $field);

        // Verificar si hay más de dos elementos en $fieldData
        if ($fieldData[1] !== 'char') {
            if (count($fieldData) > 2) {
                // Si hay más de dos elementos, combinar los elementos 2 y posteriores en 'extraData'
                $extraData = implode(' ', array_slice($fieldData, 2));
            } else {
                // Si no, establecer 'extraData' como una cadena vacía
                $extraData = '';
            }
        } else {
            if (count($fieldData) > 2) {
                if ($fieldData[2] == 'telefono') {
                    $extraData = implode(' ', array_slice($fieldData, 2));
                } else {
                    $extraData = $fieldData[2];
                }
            } else {
                $extraData = '';
            }
        }

        $fields[] = [
            'name' => $fieldData[0],
            'type' => $fieldData[1],
            'extraData' => $extraData
        ];
    }
    return $fields;
}


// Generate the SQL script
$sqlScript = generateSQLScript($tableName, $numRecords, $fields);

echo $sqlScript;

// Function to generate random data based on field type
function generateRandomData($type, $extraData = '')
{
    switch ($type) {
        case 'int':
            $range = explode(' ', $extraData);
            return rand($range[0], $range[1]);
        case 'date':
            $range = explode(' ', $extraData);
            $startDate = strtotime($range[0]);
            $endDate = strtotime($range[1]);
            $randomDate = rand($startDate, $endDate);
            return date('Y-m-d', $randomDate);
        case 'char':
            $charData = explode(' ', $extraData)[0];
            switch ($charData) {
                case 'nombre':
                    $nombres = ['Juan', 'Ana', 'Maria', 'Luisa', 'Luis', 'Pedro', 'Angel', 'Carla', 'Alicia', 'Josefina', 'Fernando'];
                    return $nombres[array_rand($nombres)];
                case 'apellido':
                    $apellidos = ['Lopez', 'Perez', 'Martinez', 'Jimenez', 'Gutierrez', 'Vera', 'Ortega', 'Castillo', 'Mireles', 'Frias', 'Morales', 'Mejia', 'Garcia'];
                    return $apellidos[array_rand($apellidos)];
                case 'telefono':
                    $areaCode = explode(' ', $extraData)[1];
                    $telefono = generateNumber(3) . ' ' . generateNumber(2) . ' ' . generateNumber(2);
                    return $areaCode . ' ' . $telefono;
                default:
                    return null;
            }
        default:
            return null;
    }
}

// Function to generate the SQL script
function generateSQLScript($tableName, $numRecords, $fields)
{
    $sql = "DROP TABLE IF EXISTS `$tableName`;\n";
    $sql .= "CREATE TABLE `$tableName` (\n";
    $sql .= "`id` int AUTO_INCREMENT,\n";

    foreach ($fields as $field) {
        $fieldName = $field['name'];
        $fieldType = $field['type'];
        $extraData = isset($field['extraData']) ? $field['extraData'] : '';

        $sql .= "`$fieldName` $fieldType";
        if ($fieldType === 'char') {
            $sql .= '(255)';
        }

        if ($extraData === '') {
            $sql .= " DEFAULT NULL";
        } else {
            $sql .= " NOT NULL";
        }
        $sql .= ",\n";
    }

    $sql .= "PRIMARY KEY (`id`)\n";
    $sql .= ") AUTO_INCREMENT = 1;\n";
    $sql .= "INSERT INTO `$tableName` (";

    //$fieldNames = array_column($fields, 'name');
    $fieldNames = [];
    // Iterar sobre cada elemento en $fields
    foreach ($fields as $field) {
        // Verificar si el campo tiene 'extraData'
        if ($field['extraData'] !== '') {
            // Si tiene 'extraData', agregar su nombre al arreglo $fieldNames
            $fieldNames[] = $field['name'];
            echo $field['name'] . "\n";
        }
    }
    $sql .= "`" . implode("`,`", $fieldNames) . "`";
    $sql .= ")\nVALUES\n";

    for ($i = 0; $i < $numRecords; $i++) {
        $values = [];
        foreach ($fields as $field) {
            $fieldName = $field['name'];
            $fieldType = $field['type'];
            $extraData = isset($field['extraData']) ? $field['extraData'] : '';

            if ($extraData === '') {
                //$values[] = "NULL";
            } elseif ($fieldType === 'int') {
                $values[] = generateRandomData($fieldType, $extraData);
            } else {
                $values[] = "'" . generateRandomData($fieldType, $extraData) . "'";
            }
        }
        $sql .= "(" . implode(",", $values) . ")";
        if ($i < $numRecords - 1) {
            $sql .= ",\n";
        }
    }

    $sql .= ";\n";

    return $sql;
}

function generateNumber($digits)
{
    // Generar un número aleatorio con la cantidad de dígitos especificada
    $number = rand(pow(10, $digits - 1), pow(10, $digits) - 1);

    // Formatear el número con ceros a la izquierda si es necesario
    return str_pad($number, $digits, '0', STR_PAD_LEFT);
}
