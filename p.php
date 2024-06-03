<?php
class p216
{
    var $a_server, $a_user, $a_password, $a_database, $a_conection, $a_queryResult;
    function m_getData()
    {
        $this->a_server = trim(fgets(STDIN));
        $this->a_user = trim(fgets(STDIN));
        $this->a_password = trim(fgets(STDIN));
        $this->a_database = trim(fgets(STDIN));
    }
    function m_open()
    {
        $this->a_conection = mysqli_connect($this->a_server, $this->a_user, $this->a_password, $this->a_database);
    }
    function m_query()
    {
        $v_query = "select j.id, concat(u1.Nombre, ' ', u1.Apellidos) as host, concat(u2.Nombre, ' ', u2.Apellidos) as invitado, j.secuencia
                  from BD_Domino_Juegos j join Usuarios u1 on j.id_usuario = u1.Usuario join Usuarios u2 on j.id_invitado = u2.Usuario order by j.id;";
        $this->a_queryResult = mysqli_query($this->a_conection, $v_query);
    }
    function m_checkDominoes()
    {
        while ($row = mysqli_fetch_assoc($this->a_queryResult)) {
            $id = $row['id'];
            $host = $row['host'];
            $invitado = $row['invitado'];
            $secuencia = $row['secuencia'];
            echo $this->m_validateCases($secuencia, $id, $host, $invitado);
        }
    }
    function m_validateCases($p_sequence, $p_id, $p_host, $p_invitado)
    {
        $v_dominoes = explode(" ", $p_sequence);
        $normalizedArray = [];
        foreach ($v_dominoes as $v_domino) {
            $v_normalizedDomino = $this->normalizeDomino($v_domino);
            $normalizedArray[] = $v_normalizedDomino;
        }
        $v_uniqueNormalizedDominoes = array_unique($normalizedArray);
        if (count($v_dominoes) !== count($v_uniqueNormalizedDominoes))
            return  "$p_id:$p_host:$p_invitado:Ficha Duplicada\n";
        if (!$this->m_checkSequence($v_dominoes))
            return "$p_id:$p_host:$p_invitado:Secuencia Mal\n";
    }
    function normalizeDomino($p_domino)
    {
        $v_values = explode(':', $p_domino);
        sort($v_values);
        return implode(':', $v_values);
    }

    function m_checkSequence($p_dominoes)
    {
        $total_dominoes = count($p_dominoes);
        for ($v_meter = 0; $v_meter < $total_dominoes - 1; $v_meter++) {
            if (strpos($p_dominoes[$v_meter], ':') !== false && strpos($p_dominoes[$v_meter + 1], ':') !== false) {
                list($v_left1, $v_right1) = explode(":", $p_dominoes[$v_meter]);
                list($v_left2, $v_right2) = explode(":", $p_dominoes[$v_meter + 1]);
                if ($v_right1 != $v_left2)
                    return false;
            } else {
                return false;
            }
        }
        return true;
    }
    function m_close()
    {
        mysqli_close($this->a_conection);
    }
    function m_showResults()
    {
        $this->m_getData();
        $this->m_open();
        $this->m_query();
        $this->m_checkDominoes();
        $this->m_close();
    }
}
$Object = new p216();
$Object->m_showResults();
