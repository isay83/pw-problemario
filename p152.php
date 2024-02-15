<?php

// Jonathan Isay Bernal Arriaga
// 2024 Ene-Jun
/*
2
10 35 80 67 50 37 30 78 30 55 60
4 80 80 60 80 
*/

// read cases
$cases = trim(fgets(STDIN));

// extract process
for ($iterate=0; $iterate<$cases; $iterate++){
    $process = trim(fgets(STDIN));
    $jar_proc = explode(' ', $process);

    // execute process
    jar_filling($jar_proc);
}

function jar_filling($jar_proc){
    $filled = 1; // counter
    $extra = $jar_proc[1];
    $bills = 0;
    $line = '';

    for ($iterate=2; $iterate<=$jar_proc[0]; $iterate++) {
        $filled++;
        $bills += $extra + $jar_proc[$iterate]; 
        if ($bills >= 100){
            $extra = $bills - 100;
            
            $line .= $filled." ";
            $filled = 0;
            $bills = 0;
        } else{
            $extra = 0;
        }
    }

    echo trim($line)."\n";
}

?>