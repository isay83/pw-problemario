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

// Read user input from the command line
$entry = trim(fgets(STDIN));
// Split the input into an array
$table = explode(' ', $entry);
// Get the table name
$tableName = $table[0];
// Get the number of records
$numRecords = $table[1];
// Get the number of fields
$numFields = $table[2];
// Generate fields based on the number of fields specified
$fields = generateFields($numFields);

/**
 * Generates an array of fields based on the number of fields specified.
 *
 * @param int $numFields The number of fields to generate.
 * @return array An array of fields, each containing a name, type, and extraData.
 */
function generateFields($numFields)
{
    $fields = [];
    for ($i = 0; $i < $numFields; $i++) {
        $field = trim(fgets(STDIN));
        $fieldData = explode(' ', $field);

        // Check if there are more than two elements in $fieldData
        if ($fieldData[1] !== 'char') {
            if (count($fieldData) > 2) {
                // If there are more than two elements, combine elements 2 and onwards into 'extraData'
                $extraData = implode(' ', array_slice($fieldData, 2));
            } else {
                // If not, set 'extraData' as an empty string
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

        /**
         * Adds a new field to the $fields array.
         *
         * $fieldData[0] The name of the field.
         * $fieldData[1] The type of the field.
         * $extraData Additional data for the field.
         */
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
// Output the SQL script
echo $sqlScript;

/**
 * Generates random data based on the field type and extraData.
 *
 * @param string $type The field type.
 * @param string $extraData The extra data associated with the field.
 * @return mixed The generated random data.
 */
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

/**
 * Generates an SQL script based on the table name, number of records, and fields.
 *
 * @param string $tableName The name of the table.
 * @param int $numRecords The number of records to generate.
 * @param array $fields An array of fields.
 * @return string The generated SQL script.
 */
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

    $fieldNames = [];
    foreach ($fields as $field) {
        if ($field['extraData'] !== '') {
            $fieldNames[] = $field['name'];
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

/**
 * Generates a random number with the specified number of digits.
 *
 * @param int $digits The number of digits for the generated number.
 * @return string The generated random number.
 */
function generateNumber($digits)
{
    $number = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
    return str_pad($number, $digits, '0', STR_PAD_LEFT);
}
