<?php

class Bc_api {

    public function __construct() {
//        parent::__construct();
    }

    private function url_exists($url) {
        $ch = @curl_init($url);
        @curl_setopt($ch, CURLOPT_HEADER, TRUE);
        @curl_setopt($ch, CURLOPT_NOBODY, TRUE);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $status = array();
        preg_match('/HTTP\/.* ([0-9]+) .*/', @curl_exec($ch), $status);
        return ($status[1] == 200);
    }

    function post($accion, $parametros = null) {
        $direccion = "https://benjicode-pkadmin.c9.io/api/";
        if ($accion) {

                $url = $direccion . $accion;
                //debug($url);
                $h = $this->url_exists($url);
                if ($h) {
                    $ch = curl_init($direccion . '' . $accion);
                } else {
                    $retr = array('success' => false, 'error' => 'La funcion indicada no existe');
                    return $retr;
                }
            
//            debug($accion, false);
            curl_setopt($ch, CURLOPT_POST, 1);
            if ($parametros) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $parametros);
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $respuesta = curl_exec($ch);
          //  echo '<div style="font-size: 12px;"><strong>' . $accion . ': </strong>' . $respuesta . '</div><br><br><br>';
           // debug($respuesta, false);
            $error = curl_error($ch);
           // debug($error, false);
            $decoded = json_decode($respuesta, true);
            //debug($decoded, false);
            if ($decoded) {
                return $decoded;
            } elseif ($error) {
                $error = array('success' => false, 'error' => $error);
                return $error;
            } elseif ($respuesta) {
                return $respuesta;
            }
            curl_close($ch);
        } else {
            return null;
        }
    }
}