<?php
// Jonathan Isay Bernal Arriaga
// 2024 Ene-Jun

// Función para enviar los datos del formulario y obtener la respuesta
function enviarDatos($one, $two, $three, $four)
{
    // Crear un handle cURL
    $ch = curl_init();

    // Establecer la URL a la que se va a enviar la solicitud
    $url = 'https://tigger.celaya.tecnm.mx/conacad/recursos/juez/validPlasticCard.php';
    curl_setopt($ch, CURLOPT_URL, $url);

    // Configurar el agente de usuario (opcional)
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');


    // Datos del formulario que se van a enviar
    $postData = http_build_query([
        'one' => $one,
        'two' => $two,
        'tree' => $three,
        'four' => $four
    ]);

    // Configurar cURL para que use el método POST
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    // Establecer la opción para devolver la transferencia como una cadena
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la solicitud
    $response = curl_exec($ch);

    // Verificar si hubo algún error
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    // Cerrar el handle cURL
    curl_close($ch);

    // Devolver la respuesta
    return $response;
}

// Función para extraer el nombre del banco de la respuesta HTML
function obtenerNombreBanco($html)
{
    // Usar DOMDocument para analizar el HTML
    $dom = new DOMDocument;
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    libxml_clear_errors();

    // Buscar la etiqueta <img> con la clase card__logo
    $xpath = new DOMXPath($dom);
    $images = $xpath->query('//img[@class="card__logo"]');

    foreach ($images as $image) {
        $src = $image->getAttribute('src');
        if (strpos($src, 'master.svg') !== false) {
            return 'MASTER CARD';
        } elseif (strpos($src, 'visa.svg') !== false) {
            return 'VISA';
        } elseif (strpos($src, 'amex.svg') !== false) {
            return 'AMERICAN EXPRESS';
        } elseif (strpos($src, 'jcb.svg') !== false) {
            return 'JCB';
        } elseif (strpos($src, 'discover.svg') !== false) {
            return 'DISCOVER';
        } elseif (strpos($src, 'diners.svg') !== false) {
            return 'DINERS';
        }
    }

    return 'INCORRECTO';
}

// Leer los números de las tarjetas desde la consola
$tarjetas = [];

while (true) {
    $tarjeta = trim(fgets(STDIN));
    if (empty($tarjeta)) {
        break;
    }
    $tarjetas[] = $tarjeta;
}

// Procesar cada tarjeta
foreach ($tarjetas as $tarjeta) {
    // Validar que el número tenga entre 13 y 19 dígitos (longitud típica de tarjetas)
    if (!preg_match('/^\d{13,19}$/', $tarjeta)) {
        echo "INCORRECTO\n";
        continue;
    }

    // Dividir el número de tarjeta en partes (cada parte puede tener diferente longitud)
    $parts = str_split($tarjeta, 4);
    $one = isset($parts[0]) ? $parts[0] : '';
    $two = isset($parts[1]) ? $parts[1] : '';
    $three = isset($parts[2]) ? $parts[2] : '';
    $four = isset($parts[3]) ? $parts[3] : '';

    // Enviar datos y obtener la respuesta
    $response = enviarDatos($one, $two, $three, $four);
    $banco = obtenerNombreBanco($response);

    // Imprimir el resultado
    echo "$banco\n";
}
