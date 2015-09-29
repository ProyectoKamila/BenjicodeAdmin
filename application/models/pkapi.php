<?php

class Pkapi extends CI_Model {

    public function __construct() {
        parent::__construct();
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
        $direccion = "http://pkclick.com/pkapi/";
        if ($accion) {
            if (($accion == 'categorias')) {
                $ch = curl_init($direccion . 'categoriasapp');
            } elseif (($accion == 'datosapp')) {
                $ch = curl_init($direccion . 'app');
            } elseif (($accion == 'productosapp')) {
                $ch = curl_init($direccion . 'prodapp');
            } elseif (($accion == 'prodcat')) {
                $ch = curl_init($direccion . 'prodappcat');
            } elseif (($accion == 'carrito')) {
                $ch = curl_init($direccion . 'carritoapp');
            } elseif (($accion == 'formulario')) {
                $ch = curl_init($direccion . 'formpay');
            } elseif (($accion == 'pedido')) {
                $ch = curl_init($direccion . 'detallepedido');
            } elseif (($accion == 'allcategory')) {
                $ch = curl_init($direccion . 'category');
            } else {
                $url = $direccion . '' . $accion;
                $h = $this->url_exists($url);
                if ($h) {
                    $ch = curl_init($direccion . '' . $accion);
                } else {
                    $retr = array('success' => false, 'error' => 'La funcion indicada no existe');
                    return $retr;
                }
            }
//            debug($accion, false);
            curl_setopt($ch, CURLOPT_POST, 1);
            if ($parametros) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $parametros);
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $respuesta = curl_exec($ch);
//            echo $respuesta;
//            debug($respuesta, false);
            $error = curl_error($ch);
//            debug($error, false);
            $decoded = json_decode($respuesta, true);
//            debug($decoded, false);
            if ($decoded) {
                return $decoded;
            } elseif ($error) {
                $error = array('success' => false, 'error' => $error);
                return $error;
            }
            curl_close($ch);
        } else {
            return null;
        }
    }

}