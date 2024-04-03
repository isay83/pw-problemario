<?php
// Jonathan Isay Bernal Arriaga
// 2024 Ene-Jun

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
            switch ($extraData) {
                case 'nombre':
                    $nombres = ['Juan', 'Ana', 'Maria', 'Luisa', 'Luis', 'Pedro', 'Angel', 'Carla', 'Alicia', 'Josefina', 'Fernando'];
                    return $nombres[array_rand($nombres)];
                case 'apellido':
                    $apellidos = ['Lopez', 'Perez', 'Martinez', 'Jimenez', 'Gutierrez', 'Vera', 'Ortega', 'Castillo', 'Mireles', 'Frias', 'Morales', 'Mejia', 'Garcia'];
                    return $apellidos[array_rand($apellidos)];
                case 'telefono':
                    $lada = '464';
                    $telefono = rand(100, 999) . ' ' . rand(10, 99) . ' ' . rand(10, 99);
                    return $lada . ' ' . $telefono;
                default:
                    return null;
            }
        default:
            return null;
    }
}

// Function to generate the SQL script
function generateSQLScript($tableName, $numRecords, $numFields, $fields)
{
    $sql = "DROP TABLE IF EXISTS `$tableName`;\n";
    $sql .= "CREATE TABLE `$tableName` (\n";
    $sql .= "`id` int auto_increment,\n";

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
    $sql .= ") AUTO_INCREMENT=1;\n";
    $sql .= "INSERT INTO `$tableName` (";

    $fieldNames = array_column($fields, 'name');
    $sql .= "`" . implode("`,`", $fieldNames) . "`";
    $sql .= ")\nVALUES\n";

    for ($i = 0; $i < $numRecords; $i++) {
        $values = [];
        foreach ($fields as $field) {
            $fieldName = $field['name'];
            $fieldType = $field['type'];
            $extraData = isset($field['extraData']) ? $field['extraData'] : '';

            if ($extraData === '') {
                $values[] = "NULL";
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

// Example input
$tableName = 'Empleados';
$numRecords = 2;
$numFields = 5;
$fields = [
    ['name' => 'Nombre', 'type' => 'char', 'extraData' => 'nombre'],
    ['name' => 'Telefono', 'type' => 'char', 'extraData' => 'telefono'],
    ['name' => 'Referencias', 'type' => 'char'],
    ['name' => 'Edad', 'type' => 'int', 'extraData' => '18 34'],
    ['name' => 'Fecha', 'type' => 'date', 'extraData' => '2020-01-01 2024-12-31']
];

// Generate the SQL script
$sqlScript = generateSQLScript($tableName, $numRecords, $numFields, $fields);

echo $sqlScript;
