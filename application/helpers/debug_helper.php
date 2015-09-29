<?php

/** Muestra el contenido de una variable para depurar y, opcionalmente, detiene la ejecucion del script. <br/>
 * @param mixed $var Variable a depurar.
 * @param bool $stop Si es verdadero detiene la ejecución del script.
 */
function debug($var, $stop = true) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    if ($stop)
        exit;
}

function max_charlength($text, $charlength, $pad = '[...]', $strict = false) {
    $text = strip_tags($text);
    if (mb_strlen($text) > $charlength) {
        $subex = mb_substr($text, 0, $charlength - mb_strlen($pad));

        if ($strict) {
            $charlength++;
            $result = $subex;
        } else {

            $exwords = explode(' ', $subex);
            $excut = - ( mb_strlen($exwords[count($exwords) - 1]) );
            if ($excut < 0) {
                $result = mb_substr($subex, 0, $excut);
            } else {
                $result = $subex;
            }
        }
        $result .= $pad;
    } else {
        $result = $text;
    }
    return $result;
}

function hace_tiempo($valor) {

// FORMATOS:
// segundos    desde 1970 (función time())        hace_tiempo('12313214');
// defecto (variable $formato_defecto)        hace_tiempo('12:01:02 04-12-1999');
// tu propio formato                        hace_tiempo('04-12-1999 12:01:02 [n.j.Y H:i:s]');

    $formato_defecto = "H:i:s Y-m-d";
    date_default_timezone_set("America/Caracas");

// j,d = día
// n,m = mes
// Y = año
// G,H = hora
// i = minutos
// s = segundos

    if (stristr($valor, '-') || stristr($valor, ':') || stristr($valor, '.') || stristr($valor, ',')) {

        if (stristr($valor, '[')) {
            $explotar_valor = explode('[', $valor);
            $valor = trim($explotar_valor[0]);
            $formato = str_replace(']', '', $explotar_valor[1]);
        } else {
            $formato = $formato_defecto;
        }

        $valor = str_replace("-", " ", $valor);
        $valor = str_replace(":", " ", $valor);
        $valor = str_replace(".", " ", $valor);
        $valor = str_replace(",", " ", $valor);

        $numero = explode(" ", $valor);

        $formato = str_replace("-", " ", $formato);
        $formato = str_replace(":", " ", $formato);
        $formato = str_replace(".", " ", $formato);
        $formato = str_replace(",", " ", $formato);

        $formato = str_replace("d", "j", $formato);
        $formato = str_replace("m", "n", $formato);
        $formato = str_replace("G", "H", $formato);

        $letra = explode(" ", $formato);

        $relacion[$letra[0]] = $numero[0];
        $relacion[$letra[1]] = $numero[1];
        $relacion[$letra[2]] = $numero[2];
        $relacion[$letra[3]] = $numero[3];
        $relacion[$letra[4]] = $numero[4];
        $relacion[$letra[5]] = $numero[5];

        $valor = mktime($relacion['H'], $relacion['i'], $relacion['s'], $relacion['n'], $relacion['j'], $relacion['Y']);
    }

    $ht = time() - $valor;
    if ($ht >= 2116800) {
        $dia = date('d', $valor);
        $mes = date('n', $valor);
        $año = date('Y', $valor);
        $hora = date('H', $valor);
        $minuto = date('i', $valor);
        $mesarray = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $fecha = "el $dia de $mesarray[$mes] del $año";
    }
    $s = '';
    if ($ht < 30242054.045) {
        $hc = round($ht / 2629743.83);
        if ($hc > 1) {
            $s = "es";
        }$fecha = "hace $hc mes" . $s;
    }
    if ($ht < 2116800) {
        $hc = round($ht / 604800);
        if ($hc > 1) {
            $s = "s";
        }$fecha = "hace $hc semana" . $s;
    }
    if ($ht < 561600) {
        $hc = round($ht / 86400);
        if ($hc == 1) {
            $fecha = "ayer";
        }if ($hc == 2) {
            $fecha = "antes de ayer";
        }if ($hc > 2)
            $fecha = "hace $hc días";
    }
    if ($ht < 84600) {
        $hc = round($ht / 3600);
        if ($hc > 1) {
            $s = "s";
        }$fecha = "hace $hc hora" . $s;
        if ($ht > 4200 && $ht < 5400) {
            $fecha = "hace más de una hora";
        }
    }
    if ($ht < 3570) {
        $hc = round($ht / 60);
        if ($hc > 1) {
            $s = "s";
        }$fecha = "hace $hc minuto" . $s;
    }
    if ($ht < 60) {
        $fecha = "hace $ht segundos";
    }
    if ($ht <= 3) {
        $fecha = "ahora";
    }
    return $fecha;
}

function add_href_url($texto) {
    $texto = preg_replace("/((http|https|www)[^\s]+)/", '<a target="_blank" rel="noreferrer" title="$0" href="$1">$0</a>', $texto);
    $texto = preg_replace("/href=\"www/", 'href="http://www', $texto);
    return $texto;
}

?>
