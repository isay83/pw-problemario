<?php
// Jonathan Isay Bernal Arriaga
// 2024 Ene-Jun
/* 
2
conacad@tigger.itc.mx
alumno@gmail.@com
*/

$cases = trim(fgets(STDIN));
$iterate = 0;

for ($iterate; $iterate<$cases; $iterate++){
    validate_address();
}


function validate_address(){
    $address = trim(fgets(STDIN));

    // split email address
    $parts = explode('@', $address, 2);

    // verify parts
    if (count($parts) < 2) {
        echo "USUARIO INCORRECTO\n";
        return;
    }

    list($user, $domain) = $parts;

    // validate
    if (!validate_user($user)) {
        echo "USUARIO INCORRECTO\n";
    } elseif (!validate_domain($domain)) {
        echo "DOMINO INCORRECTO\n";
    } else {
        echo $domain."\n";
    }
}

function validate_user($user) {
    // validate user
    return !empty($user) && !consecutive_dots($user);
}

function validate_domain($domain) {
    // validate domain
    $pattern = '/^[a-zA-Z0-9\._-]+$/';

    return preg_match($pattern, $domain);
}

function consecutive_dots($validate){
    // match with consecutive dots
    return (preg_match('/\.\./', $validate));
}

?>