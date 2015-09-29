<?php

Class User {

    public $information = null;
    public $conect = false;

//    public $array = null;

    public function __construct($array = null) {

        if (isset($_GET['ip']) && $_GET['ip'] != 1) {

//            $try = 1;
            $ip = $_GET['ip'];
                 if (isset($_COOKIE['ip'])) {
            $pastdate = mktime (0,0,0,1,1,1970);
            setcookie ("ip", "", $pastdate);
        }
            setcookie("ip", $ip, 0, '/');
          
//            redirect("./network");
//            debug($ip);
        } elseif (isset($_COOKIE['ip']) && !isset($_GET['ip'])) {
//            $try = 1;
            
            $ip = $_COOKIE['ip'];
//            debug($ip.'AQUISD');
        } elseif (isset($_GET['ip']) && $_GET['ip'] == 1) {
            $ip = 0;
        } else {
            $ip = 1;
        }
        if ($ip != 0 || $ip != 1) {
//        require_once('../config/config.php');
            $tokken = $array['tokken'];

//$user = "krondon";
//$pass = "40d89a135e0e68cc8896dd301b65352a";

            $action = "access";
            $tag = $array['tag'];
//siempre enviar false para no hacer bucle infinito
//$return=false;
            $url = 'http://pkaccount.com/p/login.php';

//$urlreturn=1;
//for login//
//$myvars = 'tokken=' . $tokken . '&user=' . $user . '&pass=' . $pass . '&ip=' . $ip.'&action='.$action.'&tag='.$tag.'&return='.$return.'&url='.$urlreturn;
//for access//
            $myvars = 'tokken=' . $tokken . '&ip=' . $ip . '&action=' . $action . '&tag=' . $tag;
//debug($myvars);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            $user = json_decode($response);
        }

//      Kevis mostrar el error de account segun el caso debido a que no lo lanza
        if (isset($user) && $user) {
            $this->conect = true;
            $this->information = $user;
            if (isset($this->information->errornumber) && $this->information->errornumber == "1") {
                var_dump($user);
                exit();
            }
        } else {

            $this->conect = false;
//            debug($ip);
            if ($ip != 0) {

                $this->relogin();
                exit();
            }
        }
    }

    public function permition($id = null) {
        if ($id) {
            $permition = explode(',', $this->user->information->rol);
            foreach ($permition as $value) {
                if ($value == $id) {
                    $bol = true;
                } else {
                    $bol = false;
                }
            }
            return $bol;
        }
    }

    public function relogin() {
        if (isset($_COOKIE['ip'])) {
            $pastdate = mktime (0,0,0,1,1,1970);
            setcookie ("ip", "", $pastdate);
        }
        $http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http";
        $url = $http . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//       ob_start();
        header("location:http://pkaccount.com/p/relogin.php?callback=" . $url);
//    ob_end_flush(); 
        exit();
    }

}

?>
