<?php

class pkapi extends CI_Controller {

    public $data = null;
    public $idcliente = null;

    public function __construct() {
        parent::__construct();
//        $config['tokken'] = 'a8affc088cbca89fa20dbd98c91362e4';
//        $config['tag'] = true;
//        $this->load->library('user', $config);
//        $this->load->library('form_validation');
        $this->load->model('modelo_universal');
    }

    public function app($tokkenapp = null) {
        if ($tokkenapp) {
            $this->db = $this->load->database('pkaccount', true);
            $app = $this->modelo_universal->query('SELECT * FROM tokken WHERE tokken ="' . $tokkenapp . '"');
            $this->db = $this->load->database('default', true);
            if ($app) {
                $empresa = $this->modelo_universal->query('SELECT * FROM empresa WHERE idempresa ="' . $app[0]['idempresa'] . '"');
                if ($empresa) {
                    $empresa[0]['url'] = $app[0]['id'];
                    $empresa[0]['analitycs'] = $app[0]['analitycs'];
                    $empresa[0]['estatus_app'] = $app[0]['estatus'];
                    $return = array('success' => TRUE, "datos" => $empresa[0]);
//                    header('Content-type: text/json');
//                    header('Content-type: application/json');
                    return json_encode($return);
                }
            }
        } else {
            if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
                if ($this->input->post('tokkenapp') || isset($_GET['tokkenapp'])) {
                    $tokkenapp = $this->input->post('tokkenapp');
                    if (isset($_GET['tokkenapp'])) {
                        $tokkenapp = $_GET['tokkenapp'];
                    }
                    $this->db = $this->load->database('pkaccount', true);
                    $app = $this->modelo_universal->query('SELECT * FROM tokken WHERE tokken ="' . $tokkenapp . '"');

                    $this->db = $this->load->database('default', true);
                    if ($app) {
                        $empresa = $this->modelo_universal->query('SELECT * FROM empresa WHERE idempresa ="' . $app[0]['idempresa'] . '"');
                        if ($empresa) {
                            $empresa[0]['url'] = $app[0]['id'];
                            $empresa[0]['analitycs'] = $app[0]['analitycs'];
                            $empresa[0]['estatus_app'] = $app[0]['estatus'];
                            $empresa[0]['view'] = $app[0]['view'];
                            $return = array('success' => TRUE, "datos" => $empresa[0]);
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        } else {
                            $return = array('success' => false, "error" => "empresa del tokken no existe");
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => "tokken de aplicación invalido");
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => "no se han enviado tokken de la aplicacion");
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "no se han enviado datos");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        }
    }

    public function adminapp($empresa = null, $user = null) {
        if (($empresa) && ($user)) {
            $empresa = empresa($empresa);
            if ($empresa) {
                $this->db = $this->load->database('pkaccount', true);
                $check = $this->modelo_universal->check('application', array('user' => $empresa[0]['user'], 'invited' => $user, 'acepted' => 1));
                $this->db = $this->load->database('default', true);
                if ($check) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
                if ($this->input->post('tokkenuser') || isset($_GET['tokkenuser'])) {
                    $user = $this->authorization();
                    if ($user) {
                        if ($this->input->post('empresa') || isset($_GET['empresa'])) {
                            $empresa = $this->input->post('empresa');
                            if (isset($_GET['empresa'])) {
                                $empresa = $_GET['empresa'];
                            }
                            $this->db = $this->load->database('default', true);
                            $empresa = $this->empresa($empresa);
                            if ($empresa) {
                                $this->db = $this->load->database('pkaccount', true);
                                $check = $this->modelo_universal->check('application', array('user' => $empresa[0]['user'], 'invited' => $user, 'acepted' => 1));
                                if ($check) {
                                    $return = array('success' => true);
                                } else {
                                    $return = array('success' => false, "error" => "Usuario no es administrador de esta empresa");
                                }
                            } else {
                                $return = array('success' => false, "error" => "Empresa no existe", "errornumber" => 6);
                            }
                        } else {
                            $return = array('success' => false, "error" => "no se han enviado empresa", "errornumber" => 0);
                        }
                    } else {
                        $return = array('success' => false, "error" => "Usuario Invalido");
                    }
                } else {
                    $return = array('success' => false, "error" => "No se ha enviado");
                }
            } else {
                $return = array('success' => false, "error" => "no se han enviado datos", "errornumber" => 0);
            }
        }
        header('Content-type: text/json');
        header('Content-type: application/json');
        echo json_encode($return);
    }

    public function authorizationapp() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('empresa') || isset($_GET['empresa'])) {
                $empresa = $this->input->post('empresa');
                if (isset($_GET['empresa'])) {
                    $empresa = $_GET['empresa'];
                }
                $this->db = $this->load->database('default', true);
                $empresa = $this->empresa($empresa);
                if ($empresa) {
                    $this->db = $this->load->database('pkaccount', true);
                    $idempresa = $empresa[0]['idempresa'];
                    $app = $this->modelo_universal->query('SELECT * FROM tokken WHERE idempresa=' . $idempresa);
                    if ($this->input->post('imagen')) {
                        $url_origen = $this->input->post('imagen');
                        $arhivo = basename($url_origen);
                        $r = explode('.', $arhivo);
                        $arhivo = md5($idempresa . '' . $r[0]) . '.' . $r[1];
                        $archivo_destino = "/home/kamilahosting/public_html/imagenescarrito/" . $arhivo;
                        $arhivo2 = $arhivo;
                        $this->recibe_imagen($url_origen, $archivo_destino);
                    } else {
                        $arhivo = $empresa[0]['logo'];
                    }
                    if ($app) {
                        if ($this->input->post('urltrue') || isset($_GET['urltrue'])) {
                            $urltrue = $this->input->post('urltrue');
                            if (isset($_GET['urltrue'])) {
                                $urltrue = $_GET['urltrue'];
                            }
                        } else {
                            $urltrue = $app[0]['urltrue'];
                        }
                        if ($this->input->post('urlfalse') || isset($_GET['urlfalse'])) {
                            $urlfalse = $this->input->post('urlfalse');
                            if (isset($_GET['urlfalse'])) {
                                $urlfalse = $_GET['urlfalse'];
                            }
                        } else {
                            $urlfalse = $app[0]['urlfalse'];
                        }
                        if (isset($arhivo2)) {
                            $update = $this->modelo_universal->update('tokken', array('urlfalse' => $urlfalse, 'redirect' => $urltrue, 'logo' => $arhivo2), array('idempresa' => $idempresa));
                        } else {
                            $update = $this->modelo_universal->update('tokken', array('urlfalse' => $urlfalse, 'redirect' => $urltrue), array('idempresa' => $idempresa));
                        }
                        if ($update) {
                            $return = array('success' => true, "tokkenapp" => $app[0]['tokken']);
                        } else {
                            $return = array('success' => true, "tokkenapp" => $app[0]['tokken']);
                        }
                    } else {
                        if ($this->input->post('urltrue') || isset($_GET['urltrue'])) {
                            $urltrue = $this->input->post('urltrue');
                            if (isset($_GET['urltrue'])) {
                                $urltrue = $_GET['urltrue'];
                            }
                            $tokkenapp = $idempresa . '-' . $_SERVER['REMOTE_ADDR'];
                            $tokkenapp = md5($tokkenapp);
//                            $tokkenapp = base64_encode($tokkenapp);
                            if ($this->input->post('urlfalse') || isset($_GET['urlfalse'])) {
                                $urlfalse = $this->input->post('urlfalse');
                                if (isset($_GET['urlfalse'])) {
                                    $urlfalse = $_GET['urlfalse'];
                                }
                            } else {
                                $urlfalse = null;
                            }
                            $insert = $this->modelo_universal->insert('tokken', array('url' => $_SERVER['REMOTE_ADDR'], 'activate' => 1, 'redirect' => $urltrue, 'urlfalse' => $urlfalse, 'tokken' => $tokkenapp, 'idempresa' => $idempresa, 'logo' => $arhivo));
                            if ($insert) {
                                $return = array('success' => true, "tokkenapp" => $tokkenapp);
                            } else {
                                $return = array('success' => false, "error" => "Error al autorizar", "errornumber" => 15);
                            }
                        } else {
                            $return = array('success' => false, "error" => "urltrue no enviada", "errornumber" => 14);
                        }
                    }
                } else {
                    $return = array('success' => false, "error" => "Empresa no existe", "errornumber" => 6);
                }
            } else {
                $return = array('success' => false, "error" => "no se han enviado empresa", "errornumber" => 0);
            }
        } else {
            $return = array('success' => false, "error" => "no se han enviado datos", "errornumber" => 0);
        }
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
    }

    //PENDIENTE POR TERMINAR ->
    public function authorizationappuser() {
        if ($this->input->post('tokkenapp') || isset($_GET['tokkenapp'])) {
            $tokkenapp = $this->input->post('tokkenapp');
            if (isset($_GET['tokkenapp'])) {
                $tokkenapp = $this->input->post('tokkenapp');
            }
            $this->db = $this->load->database('pkaccount', true);
            $app = $this->modelo_universal->query('SELECT * FROM app WHERE tokkenapp=' . $tokkenapp);
            if ($app) {
                $idapp = $app[0]['id'];
                if ($this->input->post('tokkenuser') || isset($_GET['tokkenuser'])) {
                    $tokkenuser = $this->input->post('tokkenuser');
                    if (isset($_GET['tokkenuser'])) {
                        $tokkenuser = $this->input->post('tokkenuser');
                        $user = $this->authorization();
                        if ($user) {
                            $tokkenuser = $user . '-' . $idapp . '-' . $_SERVER['REMOTE_ADDR'];
                            $tokkenuser = base64_encode(md5($tokkenuser));
                            $insert = $this->modelo_universal->insert('appuser', array('idapp' => $idapp, 'tokkenuser' => $tokkenuser, 'iduser' => $user));
                            if ($insert) {
                                
                            } else {
                                
                            }
                        }
                    }
                }
            }
        }
    }

    public function authorization() {

        if ($this->input->post('tokkenuser') || isset($_GET['tokkenuser'])) {
            $ip = $this->input->post('tokkenuser');
            if (isset($_GET['tokkenuser'])) {
                $ip = $_GET['tokkenuser'];
            }
//            debug($this->input->post('tokkenuser'), false);
            $this->db = $this->load->database('pkaccount', true);
            $userid = $this->modelo_universal->select('session', 'user', array('ip' => $ip, 'flag' => 1));
//            debug($this->db->last_query(), false);
            if ($userid) {
                return (int) $userid[0]['user'];
            } else {
                return false;
            }
        }
    }

    function neopagoEndpoint($username, $password, $cid, $orderId, $tt, $product_list, $cc_rif, $cc_name, $cc_email, $rifasociado, $cc_number, $cc_exp_month, $cc_exp_year, $cc_cvc) {
        $data = array(
            "cid" => $cid,
            "orderId" => $orderId,
            "tt" => $tt,
            "product_list" => $product_list,
            "cc_rif" => $cc_rif,
            "cc_name" => $cc_name,
            "cc_email" => $cc_email,
            "rifasociado" => $rifasociado,
            "cc_number" => $cc_number,
            "cc_exp_month" => $cc_exp_month,
            "cc_exp_year" => $cc_exp_year,
            "cc_cvc" => $cc_cvc
        );

        $ch = curl_init('https://www.neopago.com/pagos/endpoint');
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_USERAGENT, 'NEOPAGO_API_CLIENT');
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        /* Decomente las siguientes 2 lineas si no funciona la verificacion del certificado ssl */
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec($ch);
        /* Para obtener informaciÃƒÂ³n del resultado de la llamada, decomentar las siguientes 2 lÃƒÂ­neas */
// $info = curl_getinfo($ch);
// print_r($info);
        $decoded = json_decode($result, true);
        curl_close($ch);

        return $decoded;
    }

    public function configapp() {
        $email = null;
        $password = null;
        $tokkenapp = null;
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            $email = $this->input->post('email');
//            debug($_GET);
            if (isset($_GET['email'])) {
                $email = $_GET['email'];
            }
            $password = $this->input->post('password');
            if (isset($_GET['password'])) {
                $password = $_GET['password'];
            }
            $tokkenapp = $this->input->post('tokkenapp');
            if (isset($_GET['tokkenapp'])) {
                $tokkenapp = $_GET['tokkenapp'];
            }
            if ((!empty($email)) || (!empty($password)) || (!empty($tokkenapp))) {
                $this->db = $this->load->database('pkaccount', true);
                $chek = $this->modelo_universal->query('SELECT user.* FROM user, password WHERE user.email="' . $email . '" AND password.password="' . md5($password) . '" AND user.id=password.user AND password.flag=1');
//                debug($chek, FALSE);
//                debug($this->db->last_query());
                if ($chek) {
                    $respuesta = $this->app($tokkenapp);
                    $decoded = json_decode($respuesta, true);
//                    debug($decoded);
                    if ($decoded['success']) {
//                        $chek[0]['id'];
//                        $decoded['datos']['user'];
                        $this->db = $this->load->database('pkaccount', true);
                        $permisos = $this->modelo_universal->check('application', array('user' => $decoded['datos']['user'], 'invited' => $chek[0]['id']));
                        if ($permisos) {
                            $return = array('success' => true);
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        } else {
                            $return = array('success' => false, "error" => "No tienes permisos para utilizar esta aplicaciÃ³n");
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => "Tokken Invalido");
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => "Usuario o Clave Incorrecto");
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
//                $this->db = $this->load->database('default', true);
            } else {
                $return = array('success' => false, "error" => "no se han enviado todos los datos");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "no se han enviado datos", "errornumber" => 0);
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function index() {
        if ($this->input->post()) {
            $prueba = $this->prueba($this->input->post('img'));
            debug($prueba, false);
            if ($prueba) {
                $archivo = basename($this->input->post('img'));
                debug($archivo, false);
                $s = $this->recibe_imagen($this->input->post('img'), '/home/kamilahosting/public_html/prueba/' . $archivo);
                debug('aqui: ' . $s);
            }
//            debug($_POST, false);
        } else {
            $this->db = $this->load->database('benjicode', true);
            $trues = $this->modelo_universal->select('usuarios', '*');
            debug($trues);
            $tokkenuser = "ZjRjMTA1MmY2ZTQyYmE5OTMxM2I3OGMyMjdhZTk2ODY=";
            $empresa = "proyecto-kamila-12";
//            $prueba = $this->prueba($tokkenuser, $empresa);
            echo '<form action="" method="POST">';
//            echo $prueba['formulario'];
            echo '<input type="text" id="img" name="img" class="button" required="">';
            echo '<input type="submit" value="Procesar Pago" id="button-quote" class="button">';
            echo '</form>';
        }
        $ss = "nombre de prueba";
        debug($ss, false);
        $ss2 = str_replace(' ', '-', $ss);
        debug($ss2);
    }

    public function datosempresa($id_empresa = null) {
        if (!$id_empresa) {
            if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
                if ($this->input->post('id_empresa') || isset($_GET['id_empresa'])) {
                    $id_empresa = $this->input->post('id_empresa');
                    if (isset($_GET['id_empresa'])) {
                        $id_empresa = $_GET['id_empresa'];
                        $_GET = NULL;
                        $_POST = NULL;
                        $this->datosempresa($id_empresa);
                    }
                }
            }
        } else {
//            $this->db = $this->load->database('pkaccount', true);
            $this->db = $this->load->database('default', true);
            $datosempresa = $this->modelo_universal->select('empresa', '*', array('idempresa' => $id_empresa));
            if ($datosempresa) {
                return $datosempresa[0];
            } else {
                return FALSE;
            }
        }
    }

    public function datosuser($id_user = null) {
        if (!$id_user) {
            if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
                if ($this->input->post('id_user') || isset($_GET['id_user'])) {
                    $id_user = $this->input->post('id_user');
                    if (isset($_GET['id_user'])) {
                        $id_user = $_GET['id_user'];
                        $_GET = NULL;
                        $_POST = NULL;
                        $this->datosuser($id_user);
                    }
                }
            }
        } else {
            $this->db = $this->load->database('pkaccount', true);
            $datosuser = $this->modelo_universal->select('user', 'id,user,name,last_name,picture,email', array('id' => $id_user));
            $this->db = $this->load->database('default', true);
            if ($datosuser) {
                return $datosuser[0];
            } else {
                return FALSE;
            }
        }
    }

    public function checkpedidoapp() {
        if ($this->input->post('empresa') || isset($_GET['empresa'])) {
            $empresa = $this->input->post('empresa');
            if (isset($_GET['empresa'])) {
                $empresa = $_GET['empresa'];
            }
            $empresa = $this->empresa($empresa);
            if ($empresa) {
                if ($this->input->post('idpedido') || isset($_GET['idpedido'])) {
                    $idpedido = $this->input->post('idpedido');
                    if (isset($_GET['idpedido'])) {
                        $idpedido = $_GET['idpedido'];
                    }
                    $pedido = $this->modelo_universal->select('carro_compra', '*', array('id' => $idpedido, 'estatus' => 0));
                    if ($pedido) {
                        $pedido = $this->modelo_universal->update('carro_compra', array('estatus' => 1), array('id' => $idpedido));
//                        $pedido = true;
                        if ($pedido) {
                            $return = array('success' => TRUE);
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        } else {
                            $return = array('success' => TRUE);
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => "no xiste el pedido");
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => "No se ha enviado el pedido");
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "La empresa no existe");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "No se ha enviado la emrpesa");
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function checkpedido() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('tokkenuser') || isset($_GET['tokkenuser'])) {
                $user = $this->authorization();
                if ($user) {
                    $this->db = $this->load->database('default', true);
                    if ($this->input->post('idpedido') || isset($_GET['idpedido'])) {
                        $idpedido = $this->input->post('idpedido');
                        if (isset($_GET['idpedido'])) {
                            $idpedido = $_GET['idpedido'];
                        }
                        $pedido = $this->modelo_universal->select('carro_compra', '*', array('id' => $idpedido, 'estatus' => 0));
                        if ($pedido) {
                            if ($pedido[0]['id_user'] == $user) {
                                $datos = $this->datosuser($user);
                                $empresa = $this->datosempresa($pedido[0]['id_empresa']);
                                $productos = $this->modelo_universal->query('SELECT productos.empresa_idempresa as idempresa, productos.imagen, productos.nombre, carro_compra_pago.cantidad FROM productos, carro_compra_pago WHERE productos.idproductos=carro_compra_pago.id_producto AND carro_compra_pago.id_pedido=' . $idpedido);
//                                debug($datos, false);
//                                debug($empresa, false);
                                $bancos = $this->modelo_universal->select('bancos', '*', array('id_empresa' => $productos[0]['idempresa']));
                                $bank = '';
                                if (isset($bancos) && ($bancos)) {
                                    $bank .= '<div class="bancos">
            <table>
                <thead>
                    <tr style="height: 40px;">
                        <td colspan="5"><strong>Cuentas Bancarias</strong></td>
                    </tr>
                    <tr style="height: 40px;">
                        <td style="width: 150px;"><strong>Banco</strong></td>
                        <td style="width: 150px;"><strong>Beneficiario</strong></td>
                        <td style="width: 150px;"><strong>Identificaci&oacute;n</strong></td>
                        <td style="width: 165px;"><strong>Cuenta</strong></td>
                        <!--<td><strong>Opciones</strong></td>-->
                    </tr>
                </thead>
                <tbody>';
                                    foreach ($bancos as $banco) {
                                        $bank.='<tr style="height: 40px;">
                <td>' . $banco['banco'] . '</td>
                <td>' . $banco['beneficiario'] . '</td>
                <td>' . $banco['tipo'] . '-' . $banco['identificacion'] . '</td>
                <td>' . $banco['cuenta'] . '</td>
            </tr>';
                                    }
                                    $bank.='</tbody>
                <tfoot>
                    <tr style="height: 40px;">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <ul>
            </ul>
        </div>';
                                }
                                $mail = "<html>
                                            <head>
                                                <title>Pedido #" . $idpedido . " PkClick</title>
                                                <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
                                                <meta content='width=device-width, initial-scale=1.0' name='viewport'>
                                                <link type='text/css' rel='stylesheet' href='http://fonts.googleapis.com/css?family=Open+Sans'>
                                            </head>
                                            <body>
                                                <div>
                                                    <p>Empresa: <strong>" . $empresa['nombre2'] . "</strong> ha recibido el siguiente pedido:</p>
                                                    <p> Usuario: " . $datos['user'] . " <img style='height: 25px; width: 25px;' src='http://www.pkclick.com/pknetmarketing.com/images/Profile-256.png'></p>
                                                    <p>Correo: " . $datos['email'] . "</p>
                                                    <p>Nombre: " . $datos['name'] . " " . $datos['last_name'] . "</p>
                                                    <p>Total: " . number_format($pedido[0]['total'], 2, ',', '.') . "</p>
                                                </div>
                                                <div>
                                                    <div style='overflow: hidden;' class='productos'>
                                                        <p style='text-align: center;font-size: 30;margin: 10 0; '>
                                                            Lista de Productos
                                                        </p>
                                                        <p style='display: inline-flex;width: 100%;text-align: center;font-size: 23px;margin: 0px; border-bottom: 1px solid;'>
                                                            <a style='width: 50%; display: block;'>Producto</a>
                                                            <a style='width: 50%; display: block;'>Cantidad</a>
                                                        </p>                                                
                                                ";
                                foreach ($productos as $producto) {
                                    $mail .="<p style='display: inline-flex;width: 100%;text-align: center;margin: 0px;font-size: 20px; border-bottom: 1px solid;'>
                                    <a style='width: 50%;display: block;'>
                                        <img style='height: 20px; width: 20px;' src='http://www.pkclick.com/imagenescarrito/small/" . $producto['imagen'] . "' alt=''>
                                        " . $producto['nombre'] . "
                                    </a>
                                    <a style='width: 50%;display: block;'>" . $producto['cantidad'] . "</a>
                                </p>";
                                }
                                $mail .="</div>
                                        </div>
                                        " . $bank . "
                                    </body>
                                </html>";
//                                debug($mail);
                                $para = $empresa['email'] . ', '; // atención a la coma
                                $para .= $datos['email'];
                                $titulo = "Pedido #" . $idpedido . " PkClick";
                                $cabeceras = 'MIME-Version: 1.0' . "\r\n";
                                $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                                $cabeceras .= 'From: PkClick (no-replay) <pkpclick@pkclick.com>' . "\r\n";
                                $cabeceras .= "co: " . $datos['name'] . " " . $datos['last_name'] . " <" . $datos['email'] . ">" . "\r\n";
                                //        $cabeceras .= 'Cc: birthdayarchive@example.com' . "\r\n";
                                //        $cabeceras .= 'Bcc: birthdaycheck@example.com' . "\r\n";
                                // Enviarlo
                                $s = mail($para, $titulo, $mail, $cabeceras);
                                if ($s) {
                                    $update = $this->modelo_universal->update('carro_compra', array('estatus' => 1), array('id' => $idpedido));
                                    if ($update) {
                                        $return = array('success' => TRUE);
                                        header('Content-type: text/json');
                                        header('Content-type: application/json');
                                        echo json_encode($return);
                                    } else {
                                        $return = array('success' => false, "error" => "No se ha realizado el pedido");
                                        header('Content-type: text/json');
                                        header('Content-type: application/json');
                                        echo json_encode($return);
                                    }
                                } else {
                                    $return = array('success' => false, "error" => "No se ha enviado el correo");
                                    header('Content-type: text/json');
                                    header('Content-type: application/json');
                                    echo json_encode($return);
                                }
//                                echo $mail;
                            } else {
                                $return = array('success' => false, "error" => "El Pedido no pertenece a este usuario");
                                header('Content-type: text/json');
                                header('Content-type: application/json');
                                echo json_encode($return);
                            }
                        } else {
                            $return = array('success' => false, "error" => "El Pedido no Existe");
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => "ID de pedido no enviado");
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => "Tokken de usuario Invalido", "errornumber" => 2);
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "Tokken de usuario no enviado", "errornumber" => 1);
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "no se han enviado datos", "errornumber" => 0);
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function mispedidosapp() {
        if ($this->input->post('tokkenuser') || isset($_GET['tokkenuser'])) {
            $user = $this->authorization();
            if ($user) {
                if ($this->input->post('empresa') || isset($_GET['empresa'])) {
                    $empresa = $this->input->post('empresa');
                    if (isset($_GET['empresa'])) {
                        $empresa = $_GET['empresa'];
                    }
                    $this->db = $this->load->database('default', true);
                    $empresa = $this->modelo_universal->query('SELECT idempresa, nombre, logo FROM empresa WHERE idempresa="' . $empresa . '" OR nombre2="' . $empresa . '" OR nombre="' . $empresa . '" AND empresa.estatus="activa"');
                    if ($empresa) {
                        $idempresa = $empresa[0]['idempresa'];
                        $pedidos = $this->modelo_universal->select('carro_compra', '*', array('id_user' => $user, 'id_empresa' => $idempresa, 'estatus' => 1), null, null, 'id', 'DESC');
                        if ($pedidos) {
                            $return = array('success' => TRUE, "pedidos" => $pedidos);
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        } else {
                            $return = array('success' => false, "error" => "No se han realizado pedidos");
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => "Empresa no existe");
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => "Empresa no enviada");
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "Tokken de usuario Invalido", "errornumber" => 2);
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "Tokken de usuario no enviado", "errornumber" => 1);
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function pedidosapp() {
        if ($this->input->post('empresa') || isset($_GET['empresa'])) {
            $empresa = $this->input->post('empresa');
            if (isset($_GET['empresa'])) {
                $empresa = $_GET['empresa'];
            }
            $this->db = $this->load->database('default', true);
            $empresa = $this->empresa($empresa);
            if ($empresa) {
                $idempresa = $empresa[0]['idempresa'];
                $this->db = $this->load->database('default', true);
                $pedidos = $this->modelo_universal->select('carro_compra', '*', array('id_empresa' => $idempresa, 'estatus' => 1), null, null, 'id', 'DESC');
                if ($pedidos) {
                    foreach ($pedidos as $p => $h) {
                        $pedidos[$p]['datosuser'] = $this->datosuser($h['id_user']);
                    }
//                    debug($pedidos);
                    $return = array('success' => TRUE, 'cantpedidos' => count($pedidos), "pedidos" => $pedidos);
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                } else {
                    $return = array('success' => false, "error" => "No se han realizado pedidos", 'cantpedidos' => 0);
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "Empresa no existe");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "Empresa no enviada");
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function mispedidos() {
        if ($this->input->post('tokkenuser') || isset($_GET['tokkenuser'])) {
            $user = $this->authorization();
            if ($user) {
                $my_store = $this->modelo_universal->select('application', 'user', array('invited' => $user, 'tokken' => 3));
                if ($my_store) {
                    $this->db = $this->load->database('default', true);
                    $n = 0;
                    foreach ($my_store as $store) {
                        $emp = $this->modelo_universal->query('SELECT carro_compra.id as id_pedido, empresa.logo, empresa.nombre, empresa.nombre2, empresa.eslogan FROM empresa, carro_compra WHERE carro_compra.id_empresa=empresa.idempresa AND empresa.estatus="activa" AND empresa.user=' . $store['user'] . ';');
                        if ($emp) {
                            $my_store[$n] = $emp[0];
                        } else {
                            unset($my_store[$n]);
                        }
                        $n++;
                    }
                    if ($my_store) {
                        $return = array('success' => TRUE, "pedidos" => $my_store);
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    } else {
                        $return = array('success' => false, "error" => 'No posee pedidos en sus empresas', "errornumber" => 11);
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => "No hay empresas agregados", "errornumber" => 10);
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "Tokken de usuario Invalido", "errornumber" => 2);
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "Tokken de usuario no enviado", "errornumber" => 1);
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function miscompras() {
        if ($this->input->post('tokkenuser') || isset($_GET['tokkenuser'])) {
            $user = $this->authorization();
            $this->db = $this->load->database('default', true);
            if ($user) {
                if ($this->input->post('desde') || $this->input->post('cantidad')) {
                    if ($this->input->post('desde')) {
                        $desde = $this->input->post('desde');
                    } else {
                        $desde = 0;
                    }
                    if ($this->input->post('cantidad')) {
                        $cantidad = $this->input->post('cantidad');
                    } else {
                        $cantidad = 100;
                    }
                    $emp = $this->modelo_universal->query('SELECT carro_compra.id as id_pedido, empresa.logo, empresa.nombre, empresa.nombre2, empresa.eslogan FROM carro_compra, empresa WHERE carro_compra.id_user=' . $user . ' AND carro_compra.id_empresa=empresa.idempresa AND empresa.estatus="activa" AND carro_compra.estatus=1 LIMIT ' . $desde . ',' . $cantidad);
                } else {
                    $emp = $this->modelo_universal->query('SELECT carro_compra.id as id_pedido, empresa.logo, empresa.nombre, empresa.nombre2, empresa.eslogan FROM carro_compra, empresa WHERE carro_compra.id_user=' . $user . ' AND carro_compra.id_empresa=empresa.idempresa AND empresa.estatus="activa" AND carro_compra.estatus=1;');
                }
//                debug($this->db->last_query(), FALSE);
                if ($emp) {
                    $n = 0;
                    foreach ($emp as $item) {
                        $count = $this->modelo_universal->count('carro_compra_pago', array('id_pedido' => $item['id_pedido']));
                        $emp[$n]['repetid'] = $count;
                        $n++;
                    }
                    $return = array('success' => TRUE, "compras" => $emp);
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                } else {
                    $return = array('success' => false, "error" => "No hay productos agregados", "errornumber" => 9);
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "Tokken de usuario Invalido", "errornumber" => 2);
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "Tokken de usuario no enviado", "errornumber" => 1);
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function carrocompra() {
        if ($this->input->post('tokkenuser') || isset($_GET['tokkenuser'])) {
            $user = $this->authorization();
            $this->db = $this->load->database('default', true);
            if ($user) {
                if ($this->input->post('desde') || $this->input->post('cantidad')) {
                    if ($this->input->post('desde')) {
                        $desde = $this->input->post('desde');
                    } else {
                        $desde = 0;
                    }
                    if ($this->input->post('cantidad')) {
                        $cantidad = $this->input->post('cantidad');
                    } else {
                        $cantidad = 100;
                    }
                    $emp = $this->modelo_universal->query('SELECT carro_compra.id as id_pedido, empresa.logo, empresa.nombre, empresa.nombre2, empresa.eslogan FROM carro_compra, empresa WHERE carro_compra.id_user=' . $user . ' AND carro_compra.id_empresa=empresa.idempresa AND empresa.estatus="activa" AND carro_compra.estatus=0 LIMIT ' . $desde . ',' . $cantidad);
                } else {
                    $emp = $this->modelo_universal->query('SELECT carro_compra.id as id_pedido, empresa.logo, empresa.nombre, empresa.nombre2, empresa.eslogan FROM carro_compra, empresa WHERE carro_compra.id_user=' . $user . ' AND carro_compra.id_empresa=empresa.idempresa AND empresa.estatus="activa" AND carro_compra.estatus=0;');
                }
                if ($emp) {
                    $n = 0;
                    foreach ($emp as $item) {
                        $count = $this->modelo_universal->count('carro_compra_pago', array('id_pedido' => $item['id_pedido']));
                        $emp[$n]['repetid'] = $count;
                        $n++;
                    }
                    $return = array('success' => TRUE, "cantidad" => count($emp), "carro" => $emp);
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                } else {
                    $return = array('success' => false, "error" => "No hay productos agregados", "errornumber" => 9);
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "Tokken de usuario Invalido", "errornumber" => 2);
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "Tokken de usuario no enviado", "errornumber" => 1);
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    private function duplicado($array) {
        $n = 0;
        foreach ($array as $name => $val) {
            $array[$name] = json_encode($val);
        }
        $array = array_unique($array);
        $n = 0;
        foreach ($array as $name => $val) {
            $array[$name] = json_decode($val);
            $array2[$n] = $array[$name];
            $n++;
        }
        return $array2;
    }

    public function empresa($empresa) {
        if ($empresa) {
            $empresa = $this->modelo_universal->query('SELECT idempresa, nombre, logo, nombre2, moneda, user FROM empresa WHERE idempresa="' . $empresa . '" OR nombre2="' . $empresa . '" OR nombre="' . $empresa . '" AND empresa.estatus="activa"');
//            debug($empresa);
            if ($empresa) {
                return $empresa;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function categoria($categoria) {
        if ($categoria) {
            $categoria = $this->modelo_universal->query('SELECT idcategoria, categoria FROM categorias WHERE idcategoria="' . $categoria . '" OR categoria="' . $categoria . '"');
            if ($categoria) {
                return $categoria;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function produc($produc) {
        if ($produc) {
            $produc = $this->modelo_universal->query('SELECT * FROM productos WHERE idproductos="' . $produc . '" OR slug="' . $produc . '"');
            if ($produc) {
                return $produc;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function addcat() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('empresa') || isset($_GET['empresa'])) {
                $empresa = $this->input->post('empresa');
                if (isset($_GET['empresa'])) {
                    $empresa = $_GET['empresa'];
                }
                $empresa = $this->empresa($empresa);
                if ($empresa) {
                    if ($this->input->post('categoria') || isset($_GET['categoria'])) {
                        $categoria = $this->input->post('categoria');
                        if (isset($_GET['categoria'])) {
                            $categoria = $_GET['categoria'];
                        }
                        $cate = $this->categoria($categoria);
                        if (!$cate) {
                            if ($this->input->post('catdad') || isset($_GET['catdad'])) {
                                $catdad = $this->input->post('catdad');
                                if (isset($_GET['catdad'])) {
                                    $catdad = $_GET['catdad'];
                                }
                                $catdad = $this->categoria($catdad);
                                if ($catdad) {
                                    $inset = $this->modelo_universal->insert('categorias', array('categoria' => $categoria, 'id_empresa' => $empresa[0]['idempresa'], 'id_dad' => $catdad[0]['idcategoria']));
                                    if ($inset) {
                                        $return = array('success' => true, 'id' => mysql_insert_id());
                                        header('Content-type: text/json');
                                        header('Content-type: application/json');
                                        echo json_encode($return);
                                    } else {
                                        $return = array('success' => false, "error" => 'Error al insertar Categproa');
                                        header('Content-type: text/json');
                                        header('Content-type: application/json');
                                        echo json_encode($return);
                                    }
                                } else {
                                    $return = array('success' => false, "error" => 'La categoria padre no existe');
                                    header('Content-type: text/json');
                                    header('Content-type: application/json');
                                    echo json_encode($return);
                                }
                            } else {
                                $inset = $this->modelo_universal->insert('categorias', array('categoria' => $categoria, 'id_empresa' => $empresa[0]['idempresa']));
                                if ($inset) {
                                    $return = array('success' => true, 'id' => mysql_insert_id());
                                    header('Content-type: text/json');
                                    header('Content-type: application/json');
                                    echo json_encode($return);
                                } else {
                                    $return = array('success' => false, "error" => 'Error al insertar Categproa');
                                    header('Content-type: text/json');
                                    header('Content-type: application/json');
                                    echo json_encode($return);
                                }
                            }
                        } else {
                            $return = array('success' => false, "error" => 'La categoria ya existe');
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
//                        debug($categoria);
                    } else {
                        $return = array('success' => false, "error" => 'No se ha enviado categoria');
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => 'Empresa no existe');
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => 'No se ha enviado la empresa');
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => 'no se han enviado datos');
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function delprod() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('empresa') || isset($_GET['empresa'])) {
                $empresa = $this->input->post('empresa');
                if (isset($_GET['empresa'])) {
                    $empresa = $_GET['empresa'];
                }
                $empresa = $this->empresa($empresa);
                if ($empresa) {
                    if ($this->input->post('producto') || isset($_GET['producto'])) {
                        $produc = $this->input->post('producto');
                        if (isset($_GET['producto'])) {
                            $produc = $_GET['producto'];
                        }
                        $produc = $this->produc($produc);
                        if ($produc) {
                            if ($empresa[0]['idempresa'] == $produc[0]['empresa_idempresa']) {
                                $delete = $this->modelo_universal->delete('productos', array('idproductos' => $produc[0]['idproductos']));
//                                $delete = false;
                                if ($delete) {
                                    unlink('./imagenescarrito/' . $produc[0]['imagen']);
                                    unlink('./imagenescarrito/small/' . $produc[0]['imagen']);
                                    $return = array('success' => true, 'id_prod' => $produc[0]['idproductos']);
                                    header('Content-type: text/json');
                                    header('Content-type: application/json');
                                    echo json_encode($return);
                                } else {
                                    $return = array('success' => false, "error" => 'Error al eliminar producto');
                                    header('Content-type: text/json');
                                    header('Content-type: application/json');
                                    echo json_encode($return);
                                }
                            } else {
                                $return = array('success' => false, "error" => 'Producto no pertenece a esta empresa');
                                header('Content-type: text/json');
                                header('Content-type: application/json');
                                echo json_encode($return);
                            }
                        } else {
                            $return = array('success' => false, "error" => 'Producto no existe');
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => 'No se ha enviado el producto');
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => 'Empresa no existe');
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => 'No se ha enviado la empresa');
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => 'no se han enviado datos');
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function addprod() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('empresa') || isset($_GET['empresa'])) {
                $empresa = $this->input->post('empresa');
                if (isset($_GET['empresa'])) {
                    $empresa = $_GET['empresa'];
                }
                $empresa = $this->empresa($empresa);
                if ($empresa) {
                    if ($this->input->post('categoria') || isset($_GET['categoria'])) {
                        $categoria = $this->input->post('categoria');
                        if (isset($_GET['categoria'])) {
                            $categoria = $_GET['categoria'];
                        }
                        $categoria = $this->categoria($categoria);
                        if ($categoria) {
                            if ($this->input->post('imagen') || isset($_GET['imagen'])) {
                                $imagen = $this->input->post('imagen');
                                if (isset($_GET['imagen'])) {
                                    $imagen = $_GET['imagen'];
                                }
                                $val = $this->val_img($imagen);
                                if ($val) {
                                    $archivo = basename($imagen);
                                    $arc = explode('.', $archivo);
                                    $archivo = md5($empresa[0]['nombre'] . '' . $arc[0]) . '.' . $arc[1];
                                    $precio = $this->input->post('precio');
                                    if (isset($_GET['precio'])) {
                                        $precio = $_GET['precio'];
                                    }
                                    $cantidad = $this->input->post('cantidad');
                                    if (isset($_GET['cantidad'])) {
                                        $cantidad = $_GET['cantidad'];
                                    }
                                    $nombre = $this->input->post('nombre');
                                    if (isset($_GET['nombre'])) {
                                        $nombre = $_GET['nombre'];
                                    }
                                    $descripcion = $this->input->post('descripcion');
                                    if (isset($_GET['descripcion'])) {
                                        $descripcion = $_GET['descripcion'];
                                    }
                                    $moneda = $this->input->post('moneda');
                                    if (isset($_GET['moneda'])) {
                                        $moneda = $_GET['moneda'];
                                    }
                                    if (!$moneda) {
                                        $moneda = $empresa[0]['moneda'];
                                    }
                                    $terminos = $this->input->post('terminos');
                                    if (isset($_GET['terminos'])) {
                                        $terminos = $_GET['terminos'];
                                    }
                                    if (!$terminos) {
                                        $terminos = '';
                                    }
                                    if (($precio) && ($cantidad) && ($nombre) && ($descripcion)) {
                                        $s = $this->recibe_imagen($imagen, '/home/kamilahosting/public_html/imagenescarrito/' . $archivo);
                                        if ($s) {
                                            $insert = $this->modelo_universal->insert('productos', array('categoria' => $categoria[0]['categoria'], 'precio' => $precio, 'cantidad' => $cantidad, 'imagen' => $archivo, 'empresa_idempresa' => $empresa[0]['idempresa'], 'nombre' => $nombre, 'descripcion' => $descripcion, 'activo' => 'si', 'activo_pk' => 'si', 'moneda' => $moneda, 'terminos' => $terminos));
                                            if ($insert) {
                                                $newid = mysql_insert_id();
                                                $slug = str_replace(' ', '-', $nombre) . '' . $newid;
                                                $update = $this->modelo_universal->update('productos', array('slug' => $slug), array('idproductos' => $newid));
                                                if ($update) {
                                                    $return = array('success' => true, 'idproducto' => $newid, 'slug' => $slug);
                                                    header('Content-type: text/json');
                                                    header('Content-type: application/json');
                                                    echo json_encode($return);
                                                } else {
                                                    $return = array('success' => false, "error" => 'Error al crear Slug');
                                                    header('Content-type: text/json');
                                                    header('Content-type: application/json');
                                                    echo json_encode($return);
                                                }
                                            } else {
                                                $return = array('success' => false, "error" => 'Error al agregar producto');
                                                header('Content-type: text/json');
                                                header('Content-type: application/json');
                                                echo json_encode($return);
                                            }
                                        } else {
                                            $return = array('success' => false, "error" => 'Error al copiar imagen');
                                            header('Content-type: text/json');
                                            header('Content-type: application/json');
                                            echo json_encode($return);
                                        }
                                    } else {
                                        $return = array('success' => false, "error" => 'No se enviaron todos los campos');
                                        header('Content-type: text/json');
                                        header('Content-type: application/json');
                                        echo json_encode($return);
                                    }
                                } else {
                                    $return = array('success' => false, "error" => 'No se encontro url de imagen');
                                    header('Content-type: text/json');
                                    header('Content-type: application/json');
                                    echo json_encode($return);
                                }
                            } else {
                                $return = array('success' => false, "error" => 'No se ha enviado url imagen');
                                header('Content-type: text/json');
                                header('Content-type: application/json');
                                echo json_encode($return);
                            }
                        } else {
                            $return = array('success' => false, "error" => 'Categoria no existe');
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => 'Categoria no enviada');
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => 'Empresa no existe');
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => 'No se ha enviado la empresa');
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => 'no se han enviado datos');
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function delprodgal() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('producto') || isset($_GET['producto'])) {
                $produc = $this->input->post('producto');
                if (isset($_GET['producto'])) {
                    $produc = $_GET['producto'];
                }
                $produc = $this->produc($produc);
                if ($produc) {
                    if ($this->input->post('imagen') || isset($_GET['imagen'])) {
                        $imagen = $this->input->post('imagen');
                        if (isset($_GET['imagen'])) {
                            $imagen = $_GET['imagen'];
                        }
                        if ($imagen) {
                            $update = $this->modelo_universal->delete('meta_img_producto', array('nombre_img' => $imagen, 'productos_idproductos' => $produc[0]['idproductos']));
                            if ($update) {
                                $return = array('success' => true);
                                header('Content-type: text/json');
                                header('Content-type: application/json');
                                echo json_encode($return);
                            } else {
                                $return = array('success' => false, 'error' => 'Error al Eliminar Imagen');
                                header('Content-type: text/json');
                                header('Content-type: application/json');
                                echo json_encode($return);
                            }
                        } else {
                            $return = array('success' => false, "error" => 'No se encontro url de imagen');
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => 'No se ha enviado URL de imagen');
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => 'producto enviado no existe');
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => 'no se ha enviado producto');
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => 'no se han enviado datos');
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function addprodgal() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('producto') || isset($_GET['producto'])) {
                $produc = $this->input->post('producto');
                if (isset($_GET['producto'])) {
                    $produc = $_GET['producto'];
                }
                $produc = $this->produc($produc);
                if ($produc) {
//                    debug($produc,FALSE);
                    if ($this->input->post('imagen') || isset($_GET['imagen'])) {
                        $imagen = $this->input->post('imagen');
                        if (isset($_GET['imagen'])) {
                            $imagen = $_GET['imagen'];
                        }
                        $val = $this->val_img($imagen);
                        if ($val) {
                            $archivo = basename($imagen);
                            $arc = explode('.', $archivo);
                            $archivo = md5($produc[0]['idproductos'] . '' . $arc[0]) . '.' . $arc[1];
                            $s = $this->recibe_imagen($imagen, '/home/kamilahosting/public_html/imagenescarrito/' . $archivo);
                            if ($s) {
                                $insert = $this->modelo_universal->insert('meta_img_producto', array('nombre_img' => $archivo, 'productos_idproductos' => $produc[0]['idproductos']));
                                if ($insert) {
                                    $return = array('success' => true);
                                    header('Content-type: text/json');
                                    header('Content-type: application/json');
                                    echo json_encode($return);
                                } else {
                                    $return = array('success' => false, 'error' => 'no se ha podido insertar imagen');
                                    header('Content-type: text/json');
                                    header('Content-type: application/json');
                                    echo json_encode($return);
                                }
                            } else {
                                $return = array('success' => false, "error" => 'Error al copiar imagen');
                                header('Content-type: text/json');
                                header('Content-type: application/json');
                                echo json_encode($return);
                            }
                        } else {
                            $return = array('success' => false, "error" => 'No se encontro url de imagen');
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => 'No se ha enviado URL de imagen');
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => 'producto enviado no existe');
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => 'no se ha enviado producto');
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => 'no se han enviado datos');
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function editprod() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('empresa') || isset($_GET['empresa'])) {
                $empresa = $this->input->post('empresa');
                if (isset($_GET['empresa'])) {
                    $empresa = $_GET['empresa'];
                }
                $empresa = $this->empresa($empresa);
                if ($empresa) {
                    if ($this->input->post('categoria') || isset($_GET['categoria'])) {
                        $categoria = $this->input->post('categoria');
                        if (isset($_GET['categoria'])) {
                            $categoria = $_GET['categoria'];
                        }
                        $categoria = $this->categoria($categoria);
                        if ($categoria) {
                            if ($this->input->post('producto') || isset($_GET['producto'])) {
                                $produc = $this->input->post('producto');
                                if (isset($_GET['producto'])) {
                                    $produc = $_GET['producto'];
                                }
                                $produc = $this->produc($produc);
                                if ($produc) {
//                                    debug($produc);
                                    if ($this->input->post('imagen') || isset($_GET['imagen'])) {
                                        $imagen = $this->input->post('imagen');
                                        if (isset($_GET['imagen'])) {
                                            $imagen = $_GET['imagen'];
                                        }
                                        $val = $this->val_img($imagen);
                                        if ($val) {
                                            $archivo = basename($imagen);
                                            $arc = explode('.', $archivo);
                                            $archivo = md5($empresa[0]['nombre'] . '' . $arc[0]) . '.' . $arc[1];
                                        } else {
                                            $return = array('success' => false, "error" => 'No se encontro url de imagen');
                                            header('Content-type: text/json');
                                            header('Content-type: application/json');
                                            echo json_encode($return);
                                        }
                                    } else {

                                        $archivo = $produc[0]['imagen'];
                                    }
                                    $precio = $this->input->post('precio');
                                    if (isset($_GET['precio'])) {
                                        $precio = $_GET['precio'];
                                    }
                                    if (!$precio) {
                                        $precio = $produc[0]['precio'];
                                    }
                                    $cantidad = $this->input->post('cantidad');
                                    if (isset($_GET['cantidad'])) {
                                        $cantidad = $_GET['cantidad'];
                                    }
                                    if (!$precio) {
                                        $cantidad = $produc[0]['cantidad'];
                                    }
                                    $nombre = $this->input->post('nombre');
                                    if (isset($_GET['nombre'])) {
                                        $nombre = $_GET['nombre'];
                                    }
                                    if (!$nombre) {
                                        $nombre = $produc[0]['nombre'];
                                    }
                                    $descripcion = $this->input->post('descripcion');
                                    if (isset($_GET['descripcion'])) {
                                        $descripcion = $_GET['descripcion'];
                                    }
                                    if (!$descripcion) {
                                        $descripcion = $produc[0]['descripcion'];
                                    }
                                    $moneda = $this->input->post('moneda');
                                    if (isset($_GET['moneda'])) {
                                        $moneda = $_GET['moneda'];
                                    }
                                    if (!$moneda) {
                                        $moneda = $empresa[0]['moneda'];
                                    }
                                    $terminos = $this->input->post('terminos');
                                    if (isset($_GET['terminos'])) {
                                        $terminos = $_GET['terminos'];
                                    }
                                    if (!$terminos) {
                                        $terminos = $produc[0]['terminos'];
                                    }
                                    if (($precio) && ($cantidad) && ($nombre) && ($descripcion)) {
                                        if ($archivo != $produc[0]['imagen']) {
                                            $s = $this->recibe_imagen($imagen, '/home/kamilahosting/public_html/imagenescarrito/' . $archivo);
                                            if (!$s) {
                                                $return = array('success' => false, "error" => 'Error al copiar imagen');
                                                header('Content-type: text/json');
                                                header('Content-type: application/json');
                                                echo json_encode($return);
                                            }
                                        }
                                        $insert = $this->modelo_universal->update('productos', array('categoria' => $categoria[0]['categoria'], 'precio' => $precio, 'cantidad' => $cantidad, 'imagen' => $archivo, 'empresa_idempresa' => $empresa[0]['idempresa'], 'nombre' => $nombre, 'descripcion' => $descripcion, 'activo' => 'si', 'activo_pk' => 'si', 'moneda' => $moneda, 'terminos' => $terminos), array('idproductos' => $produc[0]['idproductos']));
                                        if ($insert) {
                                            $return = array('success' => true, 'idproducto' => $produc[0]['idproductos'], 'slug' => $produc[0]['slug']);
                                            header('Content-type: text/json');
                                            header('Content-type: application/json');
                                            echo json_encode($return);
                                        } else {
                                            $return = array('success' => true, 'idproducto' => $produc[0]['idproductos'], 'slug' => $produc[0]['slug']);
                                            header('Content-type: text/json');
                                            header('Content-type: application/json');
                                            echo json_encode($return);
                                        }
                                    } else {
                                        $return = array('success' => false, "error" => 'No se enviaron todos los campos');
                                        header('Content-type: text/json');
                                        header('Content-type: application/json');
                                        echo json_encode($return);
                                    }
                                } else {
                                    $return = array('success' => false, "error" => 'Producto no existe');
                                    header('Content-type: text/json');
                                    header('Content-type: application/json');
                                    echo json_encode($return);
                                }
                            } else {
                                $return = array('success' => false, "error" => 'No se ha enviado el producto');
                                header('Content-type: text/json');
                                header('Content-type: application/json');
                                echo json_encode($return);
                            }
                        } else {
                            $return = array('success' => false, "error" => 'Categoria no existe');
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => 'Categoria no enviada');
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => 'Empresa no existe');
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => 'No se ha enviado la empresa');
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => 'no se han enviado datos');
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function category() {
        if ($this->input->post('empresa') || isset($_GET['empresa'])) {
            $empresa = $this->input->post('empresa');
            if (isset($_GET['empresa'])) {
                $empresa = $_GET['empresa'];
            }
            $empresa = $this->empresa($empresa);
            if ($empresa) {
                $id_dad = $this->modelo_universal->query('SELECT idcategoria, categoria FROM categorias WHERE (very=1 OR id_empresa=' . $empresa[0]['idempresa'] . ') AND id_dad=0 ORDER BY categoria');
            } else {
                $id_dad = $this->modelo_universal->query('SELECT idcategoria, categoria FROM categorias WHERE very=1 AND id_dad=0 ORDER BY categoria');
            }
        } else {
            $id_dad = $this->modelo_universal->query('SELECT idcategoria, categoria FROM categorias WHERE very=1 AND id_dad=0 ORDER BY categoria');
        }
        foreach ($id_dad as $n => $dad) {
            $id_dad[$n]['cat_son'] = $this->cat_herencia($dad['idcategoria']);
        }
        if ($id_dad) {
            $return = array('success' => true, "categorias" => $id_dad);
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        } else {
            $return = array('success' => false, "error" => 'No hay categorias');
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function categoryaddapp() {
        if ($this->input->post('empresa') || isset($_GET['empresa'])) {
            $empresa = $this->input->post('empresa');
            if (isset($_GET['empresa'])) {
                $empresa = $_GET['empresa'];
            }
            $empresa = $this->empresa($empresa);
            if ($empresa) {
                $id_dad = $this->modelo_universal->query('SELECT idcategoria, categoria FROM categorias WHERE id_empresa=' . $empresa[0]['idempresa'] . ' AND id_dad=0 ORDER BY categoria');
                foreach ($id_dad as $n => $dad) {
                    $id_dad[$n]['cat_son'] = $this->cat_herencia($dad['idcategoria']);
                }
                if ($id_dad) {
                    $return = array('success' => true, "categorias" => $id_dad);
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                } else {
                    $return = array('success' => false, "error" => 'No hay categorias agregadas');
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => 'la empresa no existe');
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => 'no se ha enviado empresa');
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function cat_herencia($id_dad) {
        $id_son = $this->modelo_universal->query('SELECT idcategoria, categoria FROM categorias WHERE id_dad=' . $id_dad . ' ORDER BY categoria');
        if ($id_son) {
            return $id_son;
        } else {
            return null;
        }
    }

    public function categoriasapp() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('empresa') || isset($_GET['empresa'])) {
                $empresa = $this->input->post('empresa');
                if (isset($_GET['empresa'])) {
                    $empresa = $_GET['empresa'];
                }
                $this->db = $this->load->database('default', true);
                $empresa = $this->modelo_universal->query('SELECT idempresa, nombre, logo FROM empresa WHERE idempresa="' . $empresa . '" OR nombre2="' . $empresa . '" OR nombre="' . $empresa . '" AND empresa.estatus="activa"');
                if ($empresa) {
                    $query = "SELECT DISTINCT categorias.idcategoria, productos.categoria FROM productos, categorias WHERE activo='si' AND productos.activo_pk='si' AND productos.categoria=categorias.categoria AND productos.empresa_idempresa=" . $empresa[0]['idempresa'] . " AND (categorias.id_empresa=0 OR categorias.id_empresa=" . $empresa[0]['idempresa'] . ")";
                    $categorias = $this->modelo_universal->query($query);
                    if ($categorias) {
                        $n = 0;
                        foreach ($categorias as $categoria) {
                            $categorias[$n]['cat_son'] = $this->cat_herencia($categoria['idcategoria']);
                            $n++;
                        }
                        $return = array('success' => TRUE, "categorias" => $categorias);
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    } else {
                        $return = array('success' => false, "error" => 'No hay categorias');
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => 'Empresa no existe');
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => 'No se ha enviado la empresa');
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => 'no se han enviado datos');
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function prodappcat() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('empresa') || isset($_GET['empresa'])) {
                $empresa = $this->input->post('empresa');
                if (isset($_GET['empresa'])) {
                    $empresa = $_GET['empresa'];
                }
                $this->db = $this->load->database('default', true);
                $empresa = $this->modelo_universal->query('SELECT idempresa, nombre, logo FROM empresa WHERE idempresa="' . $empresa . '" OR nombre2="' . $empresa . '" OR nombre="' . $empresa . '" AND empresa.estatus="activa"');
//                debug($empresa,false);
                if ($empresa) {
//                debug($_GET,false);
                    if ($this->input->post('categoria') || isset($_GET['categoria'])) {
                        $categoria = $this->input->post('categoria');
                        if (isset($_GET['categoria'])) {
                            $categoria = $_GET['categoria'];
                        }
//                        debug($categoria,false);
                        $categoria = $this->categoria($categoria);
                        if ($categoria) {
                            $query = "SELECT productos.* FROM productos WHERE cantidad>0 AND activo='si' AND productos.activo_pk='si' AND productos.empresa_idempresa=" . $empresa[0]['idempresa'] . " AND categoria='" . $categoria[0]['categoria'] . "'";
                            $productos = $this->modelo_universal->query($query);
                            if ($productos) {
                                $return = array('success' => TRUE, "categoria" => $categoria[0]['categoria'], "productos" => $productos);
                                header('Content-type: text/json');
                                header('Content-type: application/json');
                                echo json_encode($return);
                            } else {
                                $return = array('success' => false, "error" => 'No hay productos en esta categorias');
                                header('Content-type: text/json');
                                header('Content-type: application/json');
                                echo json_encode($return);
                            }
                        } else {
                            $return = array('success' => false, "error" => 'la categoria no existe');
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => 'No se ha enviado la categoria');
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => 'La empresa no existe');
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => 'No se ha enviado la empresa');
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => 'No se ha enviado los datos');
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function prodapp() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('empresa') || isset($_GET['empresa'])) {
                $empresa = $this->input->post('empresa');
                if (isset($_GET['empresa'])) {
                    $empresa = $_GET['empresa'];
                }
                $empresa = $this->input->post('empresa');
                if (isset($_GET['empresa'])) {
                    $empresa = $_GET['empresa'];
                }
                if (isset($_GET['limite'])) {
                    $limite = $_GET['limite'];
                } else {
                    $limite = $this->input->post('limite');
                }
                if (isset($_GET['desde'])) {
                    $desde = $_GET['desde'];
                } else {
                    $desde = $this->input->post('desde');
                }

                $this->db = $this->load->database('default', true);
                $empresa = $this->empresa($empresa);
                if ($empresa) {
//                    $query = "SELECT productos.* FROM productos WHERE cantidad>0 AND activo='si' AND productos.activo_pk='si' AND productos.empresa_idempresa=" . $empresa[0]['idempresa'] . " ORDER BY idproductos DESC";
//                    $productos = $this->modelo_universal->query($query);
                    $cantidad = $this->modelo_universal->count('productos', array('cantidad >' => 0, 'activo' => 'si', "activo_pk" => 'si', "empresa_idempresa" => $empresa[0]['idempresa']));
                    $productos = $this->modelo_universal->select('productos', '*', array('cantidad >' => 0, 'activo' => 'si', "activo_pk" => 'si', "empresa_idempresa" => $empresa[0]['idempresa']), $limite, $desde, 'idproductos', 'desc');
                    if ($productos) {
                        $return = array('success' => TRUE, "cantidad" => $cantidad, "Productos" => $productos);
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    } else {
                        $return = array('success' => false, "error" => 'No hay productos');
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => 'la empresa enviada no existe');
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => 'No se ha enviado la empresa');
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => 'No se ha enviado los datos');
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function userapp() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('empresa') || isset($_GET['empresa'])) {
                $empresa = $this->input->post('empresa');
                if (isset($_GET['empresa'])) {
                    $empresa = $_GET['empresa'];
                }
                $this->db = $this->load->database('default', true);
                $empresa = $this->empresa($empresa);
                if ($empresa) {
                    $this->db = $this->load->database('pkaccount', true);

                    $query = "SELECT id FROM tokken WHERE activate=1 AND idempresa=" . $empresa[0]['idempresa'];
                    $tokken = $this->modelo_universal->query($query);
                    if ($tokken) {
                        $query = "SELECT id, user, name, last_name, email, picture FROM user WHERE tokken=" . $tokken[0]['id'];
                        $user = $this->modelo_universal->query($query);
                        if ($user) {
                            $return = array('success' => true, "users" => $user);
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        } else {
                            $return = array('success' => false, "error" => 'No hay usuarios registrados en esta app');
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => 'No existe aplicacion para esta empresa');
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => 'la empresa enviada no existe');
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => 'No se ha enviado la empresa');
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => 'No se ha enviado los datos');
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function userappdelete() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('empresa') || isset($_GET['empresa'])) {
                $empresa = $this->input->post('empresa');
                if (isset($_GET['empresa'])) {
                    $empresa = $_GET['empresa'];
                }
                $this->db = $this->load->database('default', true);
                $empresa = $this->empresa($empresa);
                if ($empresa) {
                    if ($this->input->post('id_user') || isset($_GET['id_user'])) {
                        $id_user = $this->input->post('id_user');
                        if (isset($_GET['id_user'])) {
                            $id_user = $_GET['id_user'];
                        }
                        $user = $this->datosuser($id_user);
                        if ($user) {
                            $this->db = $this->load->database('pkaccount', true);
                            $check = $this->modelo_universal->query('SELECT user.* FROM user, tokken WHERE user.id=' . $id_user . ' AND user.tokken=tokken.id AND tokken.idempresa=' . $empresa[0]['idempresa']);
                            if ($check) {
                                $delete = $this->modelo_universal->delete('user', array('id' => $id_user));
//                                $delete = FALSE;
                                if ($delete) {
                                    $return = array('success' => true);
                                    header('Content-type: text/json');
                                    header('Content-type: application/json');
                                    echo json_encode($return);
                                } else {
                                    $return = array('success' => false, "error" => 'Error al eliminar usuario');
                                    header('Content-type: text/json');
                                    header('Content-type: application/json');
                                    echo json_encode($return);
                                }
                            } else {
                                $return = array('success' => false, "error" => 'No tiene los permisos para eliminar este usuario');
                                header('Content-type: text/json');
                                header('Content-type: application/json');
                                echo json_encode($return);
                            }
                        } else {
                            $return = array('success' => false, "error" => 'identificador de usuario invalido');
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => 'la empresa enviada no existe');
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => 'la empresa enviada no existe');
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => 'No se ha enviado la empresa');
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => 'No se ha enviado los datos');
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function buscar() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('buscar') || isset($_GET['buscar'])) {
                $buscar = $this->input->post('buscar');
                if (isset($_GET['buscar'])) {
                    $buscar = $_GET['buscar'];
                }
                $query = "SELECT * FROM productos WHERE cantidad>0 AND (nombre LIKE '%" . $buscar . "%'";
                $empresa = $this->input->post('empresa');
                if (isset($_GET['empresa'])) {
                    $empresa = $_GET['empresa'];
                }
                $empresa = $this->empresa($empresa);
                if ($empresa) {
                    $empresa = (int) $empresa[0]['idempresa'];
                    $query = "SELECT * FROM productos WHERE cantidad>0 AND empresa_idempresa=" . $empresa . " AND (nombre LIKE '%" . $buscar . "%'";
//                        $query = "SELECT * FROM productos WHERE empresa_idempresa=" . $empresa;
                }
                if ($this->input->post('descripcion') || isset($_GET['descripcion'])) {
                    $descripcion = $this->input->post('descripcion');
                    if (isset($_GET['descripcion'])) {
                        $descripcion = $_GET['descripcion'];
                    }
                    if ($descripcion == 1) {
                        $query.=" OR descripcion LIKE '%" . $buscar . "%'";
                    }
                }
                $query.=") AND activo='si' AND activo_pk='si';";
                $productos = $this->modelo_universal->query($query);
                if ($productos) {
                    $return = array('success' => TRUE, "buscar" => $buscar, "productos" => $productos);
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                } else {
                    $return = array('success' => false, "error" => 'No se han encontrado productos', "buscar" => $buscar);
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => 'No se ha enviado que buscar');
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => 'No se han enviado datos');
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function producto() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('producto') || isset($_GET['producto'])) {
                $producto = $this->input->post('producto');
                if (isset($_GET['producto'])) {
                    $producto = $_GET['producto'];
                }
                $this->db = $this->load->database('default', true);
                $producto = $this->modelo_universal->query('SELECT * FROM productos WHERE idproductos = "' . $producto . '" OR slug = "' . $producto . '" OR nombre = "' . $producto . '" AND activo = "si" AND activo_pk = "si"');
                if ($producto) {
                    $meta_imagen = $this->modelo_universal->select('meta_img_producto', '*', array('productos_idproductos' => $producto[0]['idproductos']));
                    if ($meta_imagen) {
                        $return = array('success' => TRUE, "producto" => $producto[0], "imagenes" => $meta_imagen);
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    } else {
                        $return = array('success' => TRUE, "producto" => $producto[0], "imagenes" => $meta_imagen);
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => 'El producto no existe');
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => 'No se ha enviado producto');
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => 'No se han eviado datos');
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function categorias() {
        $query = "SELECT categorias.* FROM categorias WHERE very=1";
        $categorias = $this->modelo_universal->query($query);
        if ($categorias) {
            $return = array('success' => TRUE, "categorias" => $categorias);
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        } else {
            $return = array('success' => false, "error" => 'No hay categorias');
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function detallepedido() {
        if ($this->input->post('idpedido') || isset($_GET['idpedido'])) {
            $idpedido = $this->input->post('idpedido');
            if (isset($_GET['idpedido'])) {
                $idpedido = $_GET['idpedido'];
            }
            if ($this->input->post('desde') || $this->input->post('cantidad')) {
                if ($this->input->post('desde')) {
                    $desde = $this->input->post('desde');
                } else {
                    $desde = 0;
                }
                if ($this->input->post('cantidad')) {
                    $cantidad = $this->input->post('cantidad');
                } else {
                    $cantidad = 100;
                }
                $detailpedido = $this->modelo_universal->query('SELECT productos.idproductos, productos.nombre, productos.categoria, productos.precio, productos.cantidad as disponible, productos.imagen, productos.descripcion, productos.slug, carro_compra_pago.cantidad as pedido FROM carro_compra_pago, productos WHERE carro_compra_pago.id_producto = productos.idproductos AND productos.activo = "si" AND productos.activo_pk = "si" AND carro_compra_pago.id_pedido = ' . $idpedido . ' LIMIT ' . $desde . ', ' . $cantidad);
            } else {
                $detailpedido = $this->modelo_universal->query('SELECT productos.idproductos, productos.nombre, productos.categoria, productos.precio, productos.cantidad as disponible, productos.imagen, productos.descripcion, productos.slug, carro_compra_pago.cantidad as pedido FROM carro_compra_pago, productos WHERE carro_compra_pago.id_producto = productos.idproductos AND productos.activo = "si" AND productos.activo_pk = "si" AND carro_compra_pago.id_pedido = ' . $idpedido);
            }
            if ($detailpedido) {
                $return = array('success' => TRUE, "pedidodetail" => $detailpedido);
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            } else {
                $return = array('success' => false, "error" => "El pedido no se ha encontrado", "errornumber" => 13);
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "ID del pedido no enviado", "errornumber" => 12);
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function carritoapp() {
        if ($this->input->post('tokkenuser') || isset($_GET['tokkenuser'])) {
            $user = $this->authorization();
            $this->db = $this->load->database('default', true);
            if ($user) {
                if ($this->input->post('empresa') || isset($_GET['empresa'])) {
                    $empresa = $this->input->post('empresa');
                    if (isset($_GET['empresa'])) {
                        $empresa = $_GET['empresa'];
                    }
                    $this->db = $this->load->database('default', true);
                    $empresa = $this->empresa($empresa);

                    if ($empresa) {
                        $idempresa = $empresa[0]['idempresa'];
                        $pedido = $this->modelo_universal->select('carro_compra', '*', array('id_user' => $user, 'id_empresa' => $idempresa, 'estatus' => '0'));
                        if (isset($pedido[0]['id'])) {
                            $id = $pedido[0]['id'];
                        } else {
                            $id = null;
                        }
                        if ($id) {
                            $idpedido = $id;
                            $detailpedido = $this->modelo_universal->query('SELECT productos.idproductos, productos.nombre, productos.categoria, productos.precio, productos.cantidad as disponible, productos.imagen, productos.descripcion, productos.slug, carro_compra_pago.cantidad as pedido FROM carro_compra_pago, productos WHERE carro_compra_pago.id_producto = productos.idproductos AND productos.activo = "si" AND productos.activo_pk = "si" AND carro_compra_pago.id_pedido = ' . $idpedido);
                            if ($detailpedido) {
                                $return = array('success' => TRUE, "pedido" => $idpedido, "productos" => $detailpedido, 'total' => $pedido[0]['total']);
                                header('Content-type: text/json');
                                header('Content-type: application/json');
                                echo json_encode($return);
                            } else {
                                $return = array('success' => false, "error" => "El pedido no es valido", "errornumber" => 13);
                                header('Content-type: text/json');
                                header('Content-type: application/json');
                                echo json_encode($return);
                            }
                        } else {
                            $return = array('success' => false, "error" => "Usuario no tiene carro de compra");
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => "Empresa enviada no existe");
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => "Empresa no enviada");
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "Token de usuario invalido0");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "Token de usuario no enviado");
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function delcarrito() {
        if ($this->input->post('tokkenuser') || isset($_GET['tokkenuser'])) {
            $user = $this->authorization();
            $this->db = $this->load->database('default', true);
            if ($user) {
                if ($this->input->post('producto') || isset($_GET['producto'])) {
                    $producto = $this->input->post('producto');
                    if (isset($_GET['producto'])) {
                        $producto = $_GET['producto'];
                    }
                    $empresa = $this->produc($producto);
                    if ($empresa) {
                        $idproducto = $empresa[0]['idproductos'];
                        $empresa = $empresa[0]['empresa_idempresa'];
                        $pedido = $this->modelo_universal->select('carro_compra', 'id', array('id_user' => $user, 'id_empresa' => $empresa, 'estatus' => '0'));
                        if ($pedido) {
                            $count = $this->modelo_universal->count('carro_compra_pago', array('id_pedido' => $pedido[0]['id']));
                            if ($count > 1) {
                                $delete = $this->modelo_universal->delete('carro_compra_pago', array('id_pedido' => $pedido[0]['id'], 'id_producto' => $idproducto));
                            } else {
                                $delete = $this->modelo_universal->delete('carro_compra_pago', array('id_pedido' => $pedido[0]['id'], 'id_producto' => $idproducto));
                                $delete2 = $this->modelo_universal->delete('carro_compra', array('id' => $pedido[0]['id']));
                            }
                            $this->saldopedido($pedido[0]['id']);
                            if ($delete) {
                                $return = array('success' => true);
                                header('Content-type: text/json');
                                header('Content-type: application/json');
                                echo json_encode($return);
                            } else {
                                $return = array('success' => false, "error" => "Producto no eliminado");
                                header('Content-type: text/json');
                                header('Content-type: application/json');
                                echo json_encode($return);
                            }
                        } else {
                            $return = array('success' => false, "error" => "Pedido no existe");
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => "Producto no existe", "errornumber" => 4);
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => "Producto no enviado", "errornumber" => 3);
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "Tokken de usuario Invalido", "errornumber" => 2);
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "Tokken de usuario no enviado", "errornumber" => 1);
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function addcarrito() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('tokkenuser') || isset($_GET['tokkenuser'])) {
                $user = $this->authorization();
                $this->db = $this->load->database('default', true);
                if ($user) {
                    if ($this->input->post('producto') || isset($_GET['producto'])) {
                        $producto = $this->input->post('producto');
                        if (isset($_GET['producto'])) {
                            $producto = $_GET['producto'];
                        }
                        $empresa = $this->produc($producto);
                        if ($empresa) {
//                            debug($empresa,false);
                            $prec = $empresa[0]['precio'];
                            $idproducto = $empresa[0]['idproductos'];
                            $maxcant = $empresa[0]['cantidad'];
                            $empresa = $empresa[0]['empresa_idempresa'];
                            $pedido = $this->modelo_universal->select('carro_compra', 'id', array('id_user' => $user, 'id_empresa' => $empresa, 'estatus' => '0'));
                            if (isset($pedido[0]['id'])) {
                                $id = $pedido[0]['id'];
                            } else {
                                $id = NULL;
                            }
                            if ($id) {
                                $prod = $this->modelo_universal->select('carro_compra_pago', '*', array('id_pedido' => $id, 'id_producto' => $idproducto));
                                if ($prod) {
                                    $prod = $prod[0];
                                    $cantidad = $prod['cantidad'] + 1;
                                    $post = $this->input->post();
                                    if ($this->input->post('cantidad')) {
                                        $cantidad = $this->input->post('cantidad');
                                    }
                                    if ($cantidad > $maxcant) {
                                        $cantidad = $maxcant;
                                    }
                                    $update = $this->modelo_universal->update('carro_compra_pago', array('cantidad' => $cantidad), array('id' => $prod['id']));
                                    if ($update) {
                                        $return = array('success' => true, 'id' => $id, 'idprod' => $prod['id_producto'], 'cantidad' => $cantidad, 'precio' => $prec);
                                        header('Content-type: text/json');
                                        header('Content-type: application/json');
                                        echo json_encode($return);
                                    } else {
                                        $return = array('success' => true, 'id' => $id, 'idprod' => $prod['id_producto'], 'cantidad' => $cantidad, 'precio' => $prec);
                                        header('Content-type: text/json');
                                        header('Content-type: application/json');
                                        echo json_encode($return);
                                    }
                                } else {
                                    $cantidad = 1;
                                    if ($this->input->post('cantidad')) {
                                        $cantidad = $this->input->post('cantidad');
                                    }
                                    if ($cantidad > $maxcant) {
                                        $cantidad = $maxcant;
                                    }
                                    $insert = $this->modelo_universal->insert('carro_compra_pago', array('id_pedido' => $id, 'id_producto' => $idproducto, 'cantidad' => $cantidad));
                                    if ($insert) {
                                        $return = array('success' => true, 'id' => $id, 'idprod' => $idproducto, 'cantidad' => $cantidad, 'precio' => $prec);
                                        header('Content-type: text/json');
                                        header('Content-type: application/json');
                                        echo json_encode($return);
                                    } else {
                                        $return = array('success' => false, "error" => "Error al agregar los datos, envie nuevamente");
                                        header('Content-type: text/json');
                                        header('Content-type: application/json');
                                        echo json_encode($return);
                                    }
                                }
                                $this->saldopedido($id);
                            } else {
                                $this->modelo_universal->insert('carro_compra', array('id_user' => $user, 'id_empresa' => $empresa));
                                $this->addcarrito();
                            }
                        } else {
                            $return = array('success' => false, "error" => "Producto no existe", "errornumber" => 4);
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => "Producto no enviado", "errornumber" => 3);
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => "Tokken de usuario Invalido", "errornumber" => 2);
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "Tokken de usuario no enviado", "errornumber" => 1);
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "No se han enviado datos");
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function saldopedido($id) {
        $saldo = 0;
        $productos = $this->modelo_universal->query('SELECT productos.precio, carro_compra_pago.cantidad FROM productos, carro_compra_pago WHERE carro_compra_pago.id_pedido = ' . $id . ' AND carro_compra_pago.id_producto = productos.idproductos');
        foreach ($productos as $prod) {
            $n = $prod['precio'] * $prod['cantidad'];
            $saldo = $saldo + $n;
        }
        $update = $this->modelo_universal->update('carro_compra', array('total' => $saldo), array('id' => $id));
    }

    public function formpay() {
        $hoy = getdate();

        if ($this->input->post('tokkenuser') || isset($_GET['tokkenuser'])) {
            $user = $this->authorization();
            if ($user) {
                $this->db = $this->load->database('default', true);
                if ($this->input->post('empresa') || isset($_GET['empresa'])) {
                    $empresa = $this->input->post('empresa');
                    if (isset($_GET['empresa'])) {
                        $empresa = $_GET['empresa'];
                    }
                    $empresa = $this->modelo_universal->query('SELECT idempresa, nombre, logo FROM empresa WHERE idempresa = "' . $empresa . '" OR nombre2 = "' . $empresa . '" OR nombre = "' . $empresa . '" AND empresa.estatus = "activa"');
                    if ($empresa) {
                        $idempresa = $empresa[0]['idempresa'];
                        $user;
                        $pedido = $this->modelo_universal->query('SELECT * FROM carro_compra WHERE id_empresa = ' . $idempresa . ' AND id_user = ' . $user . ' AND carro_compra.estatus = 0');
                        if ($pedido) {
                            $producto = $this->modelo_universal->query('SELECT productos.precio, productos.nombre, carro_compra_pago.cantidad, productos.precio*carro_compra_pago.cantidad as saldo FROM productos, carro_compra_pago WHERE carro_compra_pago.id_pedido = ' . $pedido[0]['id'] . ' AND carro_compra_pago.id_producto = productos.idproductos AND productos.activo = "si" AND productos.activo_pk = "si" AND carro_compra_pago . cantidad > 0;
                            ');
                            $form = " 
                                <script>
                                       function justNumberspk(e) {
                                           var keynum = window.event ? window.event.keyCode : e.which;
                                           if ((keynum == 8) || (keynum == 46))
                                               return true;
                                           return /\d/.test(String.fromCharCode(keynum));
                                       }         
                               </script>   
                                <div class='pago'>
                                   <input name='orderId' type='hidden' value='" . $pedido[0]['id'] . "' >
                                       <div class='datos-comprador'>
                                       <h3>Datos del Cliente</h3>

                                       <label><stong>Cedula/Rif: </stong><input name='cc_rif' type='text' onkeypress='return justNumberspk(event);
                            ' placeholder='12345678' required=''></label><br>

                                       <label><stong>Nombre: </stong><input name='cc_name' type='text' placeholder='Nombre de la Tarjeta' required=''></label><br>

                                       <label><stong>Correo: </stong><input name='cc_email' type='email' placeholder='ejemplo@example.com' required=''></label>
                                   </div>
                                   <div class='datos-tarjeta'>
                                       <h3>Datos de Tarjeta</h3>
                                       <label><stong>Numero de Tarjeta: </stong>
                                           <input name='cc_number' onkeypress='return justNumberspk(event);
                            ' type='text' required=''>
                                       </label><br>
                                       <label><stong>Fecha de vencimiento: </stong>
                                           <select name='cc_exp_month' id='cc_exp_month' style='height: 30;
                            ' required=''>
                                               <option value='01'>01</option>
                                               <option value='02'>02</option>
                                               <option value='03'>03</option>
                                               <option value='04'>04</option>
                                               <option value='05'>05</option>
                                               <option value='06'>06</option>
                                               <option value='07'>07</option>
                                               <option value='08'>08</option>
                                               <option value='09'>09</option>
                                               <option value='10'>10</option>
                                               <option value='11'>11</option>
                                               <option value='12'>12</option>
                                           </select>
                                           <select name='cc_exp_year' id='cc_exp_year' style='height: 30;
                            ' required=''>";
                            for ($i = 0; $i < 11; $i++) {
                                $form.= "<option value='" . ($hoy['year'] + $i - 2000) . "'>" . ($hoy['year'] + $i) . "</option>";
                            }
                            $form.= "</select>
                                       </label><br>
                                       <label><stong>Codigo Seguridad: </stong>
                                           <input name='cc_cvc' onkeypress='return justNumberspk(event);
                            ' type='password' maxlength='3' placeholder='565' required=''>
                                       </label><br>
                                  </div>
                           </div>
                           ";
                            $logo = "<img class='empr-img' src='http://www.pkclick.com/imagenescarrito/" . $empresa[0]['logo'] . "' alt=''>";
                            $return = array('success' => TRUE, 'empresa' => $empresa[0]['nombre'], 'logo' => $logo, 'total' => $pedido[0]['total'], 'productos' => $producto, 'formulario' => $form);
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        } else {
                            $return = array('success' => false, "error" => "Usuario no ha Realizado un pedido", "errornumber" => 7);
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => "Empresa no existe", "errornumber" => 6);
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => "Empresa no enviada", "errornumber" => 5);
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "Tokken de usuario Invalido", "errornumber" => 2);
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "Tokken de usuario no enviado", "errornumber" => 1);
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function mailpedido($id_pedido, $type, $neopago, $email = null) {
        $hoy = getdate();
//        debug($hoy);
//        $this->mailpedido($pago['idcompra'], 1, $mail, $cc_email);
        $pedido = $this->modelo_universal->query('SELECT * FROM carro_compra WHERE id=' . $id_pedido);
        if ($pedido) {
            $pedido = $pedido[0];

            $empresa = $this->modelo_universal->query('SELECT empresa.idempresa, empresa.logo as imagen, empresa.nombre2 as nombre, empresa.email  FROM empresa, carro_compra WHERE empresa.idempresa=carro_compra.id_empresa AND carro_compra.id=' . $id_pedido);
//            debug($this->db->last_query());
            $productos = $this->modelo_universal->query('SELECT carro_compra_pago.id_producto as id, productos.imagen, productos.nombre, carro_compra_pago.cantidad, productos.cantidad as prevcant FROM productos, carro_compra_pago WHERE productos.idproductos=carro_compra_pago.id_producto AND carro_compra_pago.id_pedido=' . $id_pedido);
            foreach ($productos as $p) {
                $cant = $p['prevcant'] - $p['cantidad'];
                $this->modelo_universal->update('productos', array('cantidad' => $cant), array('idproductos' => $p['id']));
            }
            $bancos = $this->modelo_universal->select('bancos', '*', array('id_empresa' => $empresa[0]['idempresa']));
            $this->db = $this->load->database('pkaccount', true);
            $user = $this->modelo_universal->query('SELECT name as nombre, last_name, picture as imagen FROM user WHERE id=' . $pedido['id_user']);
            $bank = '';
            if (isset($bancos) && ($bancos)) {
                $bank .= '<div class="bancos">
            <table>
                <thead>
                    <tr style="height: 40px;">
                        <td colspan="5"><strong>Cuentas Bancarias</strong></td>
                    </tr>
                    <tr style="height: 40px;">
                        <td style="width: 150px;"><strong>Banco</strong></td>
                        <td style="width: 150px;"><strong>Beneficiario</strong></td>
                        <td style="width: 150px;"><strong>Identificaci&oacute;n</strong></td>
                        <td style="width: 165px;"><strong>Cuenta</strong></td>
                        <!--<td><strong>Opciones</strong></td>-->
                    </tr>
                </thead>
                <tbody>';
                foreach ($bancos as $banco) {
                    $bank.='<tr style="height: 40px;">
                <td>' . $banco['banco'] . '</td>
                <td>' . $banco['beneficiario'] . '</td>
                <td>' . $banco['tipo'] . '-' . $banco['identificacion'] . '</td>
                <td>' . $banco['cuenta'] . '</td>
            </tr>';
                }
                $bank.='</tbody>
                <tfoot>
                    <tr style="height: 40px;">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <ul>
            </ul>
        </div>';
            }
            $mail = "
            <html>
                <head>
                    <title>Compra PkClick</title>
                    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
                    <meta content='width=device-width, initial-scale=1.0' name='viewport'>
                    <link type='text/css' rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
                </head>
                ";
            $mail.="
                <body style='padding: 0px;margin: 0px;font-family:";
            $mail.='"Open Sans" ';
            $mail.=",sans-serif; background: #F6E9D9;'>
                    <div class='content' style='width: 100%;max-width: 800px;margin: 0 auto;overflow: hidden;background: #F6E9D9;'>
                        <div class='header' style='width: 100%;max-height: 275px;margin-bottom: 80px;'>
                            <div class='img' style='overflow: hidden;'>
                                <img src='http://www.pkclick.com/mailing/arriba.png' style='width: 100%'>
                            </div>
                            <div class='ul' style='margin-top: -80px;z-index: -1;'>
                                <ul style='padding: 0px;margin: 0px;width: 100%;float: left;'>
                                    <li style='float: left;display: block;width: 20%;text-align: center;padding: 0px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;'>s</li>
                                    <li style='float: left;display: block;width: 20%;text-align: left;padding: 0px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;'>Registrate</li>
                                    <li style='float: left;display: block;width: 20%;text-align: left;padding: 0px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;'>Crea tu tienda</li>
                                    <li style='float: left;display: block;width: 40%;text-align: left;padding: 0px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;'>Comienza a vender tus productos y servicios</li>
                                </ul>
                                <div style='background: none repeat scroll 0 0 #E39F1F;height: 20px; width: 100%;float: left;'></div>

                            </div>
                        </div>
                        <div class='mail' style='width: 100%;overflow: hidden;'>
                            <div class='empresa' style='width: calc(100% - 20px);overflow: hidden;margin-left: 20PX;height: 40px;display: inline-flex;'>";
            if ($type == 1) {
                $mail .="<img src='http://www.pkclick.com/imagenescarrito/" . $empresa[0]['imagen'] . " ' alt='' class='' style='height: 40px; max-height: 40px; width: 40px; max-width: 40px;  background: white;'>";
            }
            if ($type == 0) {
                $empresa = $user;
                $mail .="<img src='http://www.pkclick.com/pknetmarketing.com/images/" . $empresa[0]['imagen'] . "' alt='' class='' style='height: 40px; width: 40px;  background: white;'>";
            }
            $mail .="<a style=' font-weight: bold;font-size: 35;text-overflow: ellipsis;overflow: hidden;white-space: nowrap;margin-left: 10px;'>" . $empresa[0]['nombre'];
            if (isset($empresa[0]['last_name'])) {
                $mail.=' ' . $empresa[0]['last_name'];
            } $mail.="</a>
                            </div>
                            <div class='productos' style='overflow: hidden;'>
                                <p style='text-align: center;font-size: 30;margin: 10 0; '>
                                    Lista de Productos
                                </p>
                                <p style='display: inline-flex;width: 100%;text-align: center;font-size: 23px;margin: 0px; border-bottom: 1px solid;'>
                                    <a style='width: 50%; display: block;'>Producto</a>
                                    <a style='width: 50%; display: block;'>Cantidad</a>
                                </p>";
            foreach ($productos as $producto) {
                $mail .="<p style='display: inline-flex;width: 100%;text-align: center;margin: 0px;font-size: 20px; border-bottom: 1px solid;'>
                                    <a style='width: 50%;display: block;'>
                                        <img style='height: 20px; width: 20px;' src='http://www.pkclick.com/imagenescarrito/small/" . $producto['imagen'] . "' alt=''>
                                        " . $producto['nombre'] . "
                                    </a>
                                    <a style='width: 50%;display: block;'>" . $producto['cantidad'] . "</a>
                                </p>";
            }
            $mail .="</div>
                            <p style='text-align: center;font-size: 30;font-weight: bold;margin: 15 0;'>Datos de Pago</p>
                            <div class='neopago' style='min-height: 270px;overflow: hidden;'>
                                <div class='logoneopago' style='max-width: 50%;float: left;display: table;table-layout: fixed;vertical-align: middle;margin: 10% 0;'>
                                    <div>
                                        <img src='http://www.pkclick.com/mailing/logo.png'>
                                    </div>
                                </div>
                                " . $neopago . "
                            </div>
                        </div>
                        " . $bank . "
                        <div class='footer' style='width: 100%;'>
                            <img src='http://www.pkclick.com/mailing/redes.jpg' style='width: 100%;margin-top: 8%;'>
                            <img src='http://www.pkclick.com/mailing/pregunta.png' style='width: 25%;margin-top: -16.5%;z-index: 99;'>
                        </div>
                    </div>
                </body>
            </html>
            ";
            if ($email) {
                $para = $empresa[0]['email'];
                $para = $para . "," . $email;
            } else {
                $para = $empresa[0]['email'];
            }
            $titulo = 'Pago Neopago Pedido #' . $id_pedido;
            $cabeceras = 'MIME-Version: 1.0' . "\r\n";
            $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Cabeceras adicionales
            $cabeceras .= 'From: ProyectoKamila <contacto@pkaccount.com>' . "\r\n";
//        $cabeceras .= 'co: Recordatorio <cumples@example.com>' . "\r\n";
//        $cabeceras .= 'Cc: birthdayarchive@example.com' . "\r\n";
//        $cabeceras .= 'Bcc: birthdaycheck@example.com' . "\r\n";
// Enviarlo
            mail($para, $titulo, $mail, $cabeceras);
//            echo $mail;
        }
//        $return = array('empresa' => $empresa, 'productos' => $productos, 'user' => $user);
//        return $return;
    }

    public function procesarpago() {
        $hoy = getdate();
        $orderId = (int) $this->input->post('orderId');
        if (isset($_GET['orderId'])) {
            $orderId = $_GET['orderId'];
        }
        $cc_rif = $this->input->post('cc_rif');
        $cc_name = $this->input->post('cc_name');
        $cc_email = $this->input->post('cc_email');
        $cc_number = $this->input->post('cc_number');
        $cc_exp_month = $this->input->post('cc_exp_month');
        $cc_exp_year = $this->input->post('cc_exp_year');
        $cc_cvc = $this->input->post('cc_cvc');
//        debug($cc_email);
        $neopago = $this->modelo_universal->query('SELECT * FROM neopago, carro_compra WHERE carro_compra.id=' . $orderId . ' AND carro_compra.id_empresa = neopago.id_empresa');
        if ($neopago) {
            if ($neopago[0]['act_neo'] == 1) {

                $rifasociado = $neopago[0]['user_neo'];
                $username = $neopago[0]['user_neo'];
                $password = $neopago[0]['pass_neo'];
                $tt = $neopago[0]['total'];
                $cid = "148-PuJWImPn7wJK-1";
                $pago = $this->neopagoEndpoint($username, $password, $cid, $orderId, $tt, NULL, $cc_rif, $cc_name, $cc_email, $rifasociado, $cc_number, $cc_exp_month, $cc_exp_year, $cc_cvc);
//                $pago = array(
//                    "success" => true,
//                    "idcompra" => $orderId,
////                    "idcompra" => "93",
//                    "numeroreferencia" => "000757",
//                    "numeroconfirmacion" => "893705",
//                    "estatusoperacion" => "APPROVED",
//                    "totalpago" => "5.00",
//                    "rif" => "4854999",
//                    "cliente" => "luisa l martinez p",
//                    "correo" => $cc_email,
//                    "voucher" => "__
//                _______________BANPLUS_
//                __________RIF_J-00042303-2_
//                __________RECIBO_DE_COMPRA_
//                ________________VISA_
//                _
//                NEOPAGO_SISTEMA_DE_MEDIOS_DE_PAGO,_C.A._
//                COLINAS_DE_BELLO_MONTE_
//                RIF:J-29760964-4_
//                AFILIADO:0076582947_
//                TERMINAL:00004002_LOTE:1_
//                496638******5722_
//                FECHA:30/09/2014_HORA:13:45:38_
//                APROB:893705_REF:000757_
//                TRACE:893_
//                CAJA_:NEOPAGO01_
//                SECUENCIA:903_
//                ______**_DUPLICADO_**_
//                _
//                ___MONTO______BS._5,00_
//                _
//                _
//                ME_OBLIGO_A_PAGAR_AL_BANCO_EMISOR_
//                DE_ESTA_TARJETA_EL_MONTO_DE_ESTA__
//                NOTA_DE_CONSUMO_
//                _
//                ", "product_list" => ""
//                );
//            debug($pago);
                if ($pago) {
                    if ($pago['success']) {
                        $mail = "  <div class='datospago' style='float: left;width: calc(100% - 217px);padding: 0px;margin: 0px;overflow: hidden;min-width: 316px;'>
                <ul class='neo' style='margin: 0;padding: 10;width: calc(100% - 20px);overflow: hidden;background: none repeat scroll 0% 0% rgb(243, 243, 243);'>
                    <li class='neoli' style='overflow: hidden;margin: 10px;float: left;width: 260px;height: 30px;background: white;'>
                        <p style='margin: 7px;'><strong>Compra:</strong> #" . $pago['idcompra'] . "</p>
                    </li>
                    <li class='neoli' style='overflow: hidden;margin: 10px;float: left;width: 260px;height: 30px;background: white;'>
                        <p style='margin: 7px;'><strong>Total:</strong> " . $pago['totalpago'] . "</p>
                    </li>
                    <li class='neoli' style='overflow: hidden;margin: 10px;float: left;width: 260px;height: 30px;background: white;'>
                        <p style='margin: 7px;'><strong>RIF/CI:</strong> " . $pago['rif'] . "</p>
                    </li>
                    <li class='neoli' style='overflow: hidden;margin: 10px;float: left;width: 260px;height: 30px;background: white;'>
                        <p style='margin: 7px;'><strong>NÂº Ref:</strong> " . $pago['numeroreferencia'] . "</p>
                    </li>
                    <li class='neoli' style='overflow: hidden;margin: 10px;float: left;width: 260px;height: 30px;background: white;'>
                        <p style='margin: 7px;'><strong>NÂº Conf:</strong> " . $pago['numeroconfirmacion'] . "</p>
                    </li>
                    <li class='neoli' style='overflow: hidden;margin: 10px;float: left;width: 260px;height: 30px;background: white;'>
                        <p style='margin: 7px;'><strong>Estatus:</strong> " . $pago['estatusoperacion'] . "</p>
                    </li>
                    <li class='neoli' style='overflow: hidden;margin: 10px;float: left;width: 260px;height: 30px;background: white;'>
                        <p style='margin: 7px;'><strong>Nombre:</strong> " . $pago['cliente'] . "</p>
                    </li>
                    <li class='neoli' style='overflow: hidden;margin: 10px;float: left;width: 260px;height: 30px;background: white;'>
                        <p style='margin: 7px;'><strong>Fecha:</strong> " . $hoy['mday'] . "/" . $hoy['mon'] . "/" . $hoy['year'] . "</p>
                    </li>
                    <li class='neoli' style='overflow: hidden;margin: 10px;float: left;width: 260px;height: 30px;background: white;'>
                        <p style='margin: 7px;'><strong>Hora:</strong> " . $hoy['hours'] . ":" . $hoy['minutes'] . ":" . $hoy['seconds'] . "</p>
                    </li>
                </ul>
            </div>";
                        $this->mailpedido($pago['idcompra'], 1, $mail, $cc_email);
                        $this->db = $this->load->database('default', true);
                        $update = $this->modelo_universal->update('carro_compra', array('estatus' => '1', 'estatus_neo' => $pago['estatusoperacion'], 'numeroreferencia' => $pago['numeroreferencia'], 'numeroconfirmacion' => $pago['numeroconfirmacion'], "date_neo" => $hoy['year'] . "-" . $hoy['mon'] . "-" . $hoy['mday']), array('id' => $pago['idcompra']));
                        if ($update) {
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($pago);
                        } else {
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($pago);
                        }
                    } else {
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($pago);
                    }
                } else {
                    $return = array('success' => false, "error" => "Neopago no esta disponible en estos momentos", "errornumber" => 8);
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "Esta empresa no permite pagos por Neopago", "errornumber" => 8);
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "Esta empresa no permite pagos por Neopago", "errornumber" => 8);
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function recibe_imagen($url_origen, $archivo_destino) {
        $mi_curl = curl_init($url_origen);
        $fs_archivo = fopen($archivo_destino, "w");
        curl_setopt($mi_curl, CURLOPT_FILE, $fs_archivo);
        curl_setopt($mi_curl, CURLOPT_HEADER, 0);
        $respuesta = curl_exec($mi_curl);
        $error = curl_error($mi_curl);
        curl_close($mi_curl);
        fclose($fs_archivo);
        $arhivo = basename($archivo_destino);
        $this->_create_thumbnailsmall($arhivo);
        if ($respuesta) {
            return true;
        } elseif ($error) {
            return false;
        }
    }

    private function _create_thumbnailsmall($filename) {
        $this->load->library('image_lib');
        $config['image_library'] = 'gd2';
        $config['source_image'] = './imagenescarrito/' . $filename;
        $config['new_image'] = './imagenescarrito/small/';
        $config['width'] = 200;
        $config['height'] = 200;
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
    }

    public function val_img($imagen) {
        $file = $imagen;
        $file_headers = @get_headers($file);
        if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {
            $exists = false;
        } else {
            $exists = true;
        }
        return $exists;
    }

    public function timelinecomentadd() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('tokkenuser') || isset($_GET['tokkenuser'])) {
                $user = $this->authorization();
                $datosuser = $this->datosuser($user);
                if ($user) {
                    if ($this->input->post('idpublicacion') || isset($_GET['idpublicacion'])) {
                        $idpublicacion = $this->input->post('idpublicacion');
                        if (isset($_GET['idpublicacion'])) {
                            $idpublicacion = $_GET['idpublicacion'];
                        }
                        $check = $this->modelo_universal->select('timeline', '*', array('id' => $idpublicacion));
                        if ($check) {
                            if ($this->input->post('comentario') || isset($_GET['comentario'])) {
                                $comentario = $this->input->post('comentario');
                                if (isset($_GET['comentario'])) {
                                    $comentario = $_GET['comentario'];
                                }
                                $ss = getdate();
                                $hora = $ss['year'] . '-' . $ss['mon'] . '-' . $ss['mday'] . ' ' . $ss['hours'] . ':' . $ss['minutes'] . ':' . $ss['seconds'];

                                $insert = $this->modelo_universal->insert('comentarios', array('id_publicacion' => $idpublicacion, 'comentario' => $comentario, 'user' => $user, 'name' => $datosuser['name'] . ' ' . $datosuser['last_name'], 'fecha' => $hora));
                                if ($insert) {
                                    $return = array('success' => TRUE, "idcomentario" => mysql_insert_id(), 'comentario' => $comentario, 'user' => $user, 'name' => $datosuser['name'] . ' ' . $datosuser['last_name'], 'picture' => $datosuser['picture'], 'fecha' => $hora);
                                    header('Content-type: text/json');
                                    header('Content-type: application/json');
                                    echo json_encode($return);
                                } else {
                                    $return = array('success' => false, "error" => "error al agregar comentario");
                                    header('Content-type: text/json');
                                    header('Content-type: application/json');
                                    echo json_encode($return);
                                }
                            } else {
                                $return = array('success' => false, "error" => "comentario no enviado");
                                header('Content-type: text/json');
                                header('Content-type: application/json');
                                echo json_encode($return);
                            }
                        } else {
                            $return = array('success' => false, "error" => "publidcacion no existe");
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => "idpublicacion no enviado");
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => "tokken de usuario invalido");
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "no se ha enviado tokken de usuario");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "no se han enviado datos");
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function timelinecomentdelet() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('tokkenuser') || isset($_GET['tokkenuser'])) {
                $user = $this->authorization();
                if ($user) {
                    $this->db = $this->load->database('default', true);
                    if ($this->input->post('idcomentario') || isset($_GET['idcomentario'])) {
                        $idcomentario = $this->input->post('idcomentario');
                        if (isset($_GET['idcomentario'])) {
                            $idcomentario = $_GET['idcomentario'];
                        }
                        $check = $this->modelo_universal->select('comentarios', '*', array('idcomentario' => $idcomentario));
                        if ($check) {
                            $delete = $this->modelo_universal->delete('comentarios', array('idcomentario' => $idcomentario, 'user' => $user));
                            if ($delete) {
                                $return = array('success' => TRUE);
                                header('Content-type: text/json');
                                header('Content-type: application/json');
                                echo json_encode($return);
                            } else {
                                $publicacion = $this->modelo_universal->select('timeline', '*', array('id' => $check[0]['id_publicacion']));
                                if ($publicacion) {
                                    $empresa = empresa($publicacion[0]['idempresa']);
                                    if ($empresa) {
                                        $admin = adminapp($empresa[0]['idempresa'], $user);
                                        if ($admin) {
                                            $delete = $this->modelo_universal->delete('comentarios', array('idcomentario' => $idcomentario));
                                            if ($delete) {
                                                $return = array('success' => TRUE);
                                                header('Content-type: text/json');
                                                header('Content-type: application/json');
                                                echo json_encode($return);
                                            } else {
                                                $return = array('success' => false, 'error' => 'error al eliminar comentario');
                                                header('Content-type: text/json');
                                                header('Content-type: application/json');
                                                echo json_encode($return);
                                            }
                                        } else {
                                            $return = array('success' => false, 'error' => 'el usuario no tiene permisos para eliminar este comentario');
                                            header('Content-type: text/json');
                                            header('Content-type: application/json');
                                            echo json_encode($return);
                                        }
                                    } else {
                                        $delete = $this->modelo_universal->delete('comentarios', array('idcomentario' => $idcomentario));
                                        if ($delete) {
                                            $return = array('success' => TRUE);
                                            header('Content-type: text/json');
                                            header('Content-type: application/json');
                                            echo json_encode($return);
                                        } else {
                                            $return = array('success' => false, 'error' => 'error al eliminar comentario');
                                            header('Content-type: text/json');
                                            header('Content-type: application/json');
                                            echo json_encode($return);
                                        }
                                    }
                                } else {
                                    $delete = $this->modelo_universal->delete('comentarios', array('idcomentario' => $idcomentario));
                                    if ($delete) {
                                        $return = array('success' => TRUE);
                                        header('Content-type: text/json');
                                        header('Content-type: application/json');
                                        echo json_encode($return);
                                    } else {
                                        $return = array('success' => false, 'error' => 'error al eliminar comentario');
                                        header('Content-type: text/json');
                                        header('Content-type: application/json');
                                        echo json_encode($return);
                                    }
                                }
                            }
                        } else {
                            $return = array('success' => false, "error" => "Comentario no existe");
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => "idcomentario no enviado");
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => "tokken de usuario invalido");
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "no se ha enviado tokken de usuario");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "no se han enviado datos");
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function timelinecoment() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('empresa') || isset($_GET['empresa'])) {
                $empresa = $this->input->post('empresa');
                if (isset($_GET['empresa'])) {
                    $empresa = $_GET['empresa'];
                }
                $empresa = $this->empresa($empresa);
                if ($empresa) {
                    if ($this->input->post('timeline') || isset($_GET['timeline'])) {
                        $timeline = $this->input->post('timeline');
                        if (isset($_GET['timeline'])) {
                            $timeline = $_GET['timeline'];
                        }
                        $timeline = $this->modelo_universal->select('timeline', '*', array('id' => $timeline, 'idempresa' => $empresa[0]['idempresa']));
                        if ($timeline) {
                            $comentarios = $this->modelo_universal->select('comentarios', '*', array('id_publicacion' => $timeline[0]['id']));
                            if ($comentarios) {
                                foreach ($comentarios as $pos2 => $data2) {
                                    $datosuser = $this->datosuser($data2['user']);
                                    if ($datosuser) {
                                        $comentarios[$pos2]['image'] = $datosuser['picture'];
                                        $comentarios[$pos2]['name'] = $datosuser['name'] . ' ' . $datosuser['last_name'];
                                    } else {
                                        $comentarios[$pos2]['image'] = 'Profile-256.png';
                                    }
                                }
                                $return = array('success' => false, "id_publicacion" => $timeline[0]['id'], 'comentarios' => $comentarios);
                                header('Content-type: text/json');
                                header('Content-type: application/json');
                                echo json_encode($return);
                            } else {
                                $return = array('success' => false, "error" => "Publicacion no tiene comentarios");
                                header('Content-type: text/json');
                                header('Content-type: application/json');
                                echo json_encode($return);
                            }
                        } else {
                            $return = array('success' => false, "error" => "Publicacion no encontrada en esta empresa");
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => "No se ha enviado id de publicacion");
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => "Empresa enviada no existe");
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "no se ha enviado empresa");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "no se han enviado datos");
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function timeline() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('empresa') || isset($_GET['empresa'])) {
                $empresa = $this->input->post('empresa');
                if (isset($_GET['empresa'])) {
                    $empresa = $_GET['empresa'];
                }
                $empresa = $this->empresa($empresa);
                if ($empresa) {

                    $limite = $this->input->post('limite');
                    if (isset($_GET['limite'])) {
                        $limite = $_GET['limite'];
                    }
                    $desde = $this->input->post('desde');
                    if (isset($_GET['desde'])) {
                        $desde = $_GET['desde'];
                    }
                    $check = $this->modelo_universal->count('timeline', array('idempresa' => $empresa[0]['idempresa']));
                    if ($check) {
                        if ($desde && $desde > ($check - 1)) {
                            $return = array('success' => false, "error" => "La empresa no tiene tantas publicaciones");
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                            return;
                        }
                        $timeline = $this->modelo_universal->select('timeline', '*', array('idempresa' => $empresa[0]['idempresa']), $limite, $desde, 'date', 'DESC');
                        if ($timeline) {
                            if ($this->input->post('coment') || isset($_GET['coment'])) {
                                foreach ($timeline as $pos => $data) {
                                    $timeline[$pos]['comentarios'] = $this->modelo_universal->select('comentarios', '*', array('id_publicacion' => $data['id']));
                                    if ($timeline[$pos]['comentarios']) {
                                        foreach ($timeline[$pos]['comentarios'] as $pos2 => $data2) {
                                            $datosuser = $this->datosuser($data2['user']);
                                            if ($datosuser) {
                                                $timeline[$pos]['comentarios'][$pos2]['image'] = $datosuser['picture'];
                                                $timeline[$pos]['comentarios'][$pos2]['name'] = $datosuser['name'] . ' ' . $datosuser['last_name'];
                                            } else {
                                                $timeline[$pos]['comentarios'][$pos2]['image'] = 'Profile-256.png';
                                            }
                                        }
                                    }
                                }
                            }
                            $return = array('success' => TRUE, 'cantidad' => $check, "timeline" => $timeline);
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        } else {
                            $return = array('success' => false, "error" => "La empresa no tiene publicaciones");
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => "La empresa no tiene publicaciones");
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => "La empresa no existe");
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "no se han enviado la empresa");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "no se han enviado datos");
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function timelinedetail() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('empresa') || isset($_GET['empresa'])) {
                $empresa = $this->input->post('empresa');
                if (isset($_GET['empresa'])) {
                    $empresa = $_GET['empresa'];
                }
                $empresa = $this->empresa($empresa);
                if ($empresa) {
                    if ($this->input->post('publicacion') || isset($_GET['publicacion'])) {
                        $publicacion = $this->input->post('publicacion');
                        if (isset($_GET['publicacion'])) {
                            $publicacion = $_GET['publicacion'];
                        }

                        $limite = $this->input->post('limite');
                        if (isset($_GET['limite'])) {
                            $limite = $_GET['limite'];
                        }
                        $desde = $this->input->post('desde');
                        if (isset($_GET['desde'])) {
                            $desde = $_GET['desde'];
                        }
                        $check = $this->modelo_universal->select('timeline', '*', array('id' => $publicacion, 'idempresa' => $empresa[0]['idempresa']));
                        if ($check) {
                            $check[0]['comentarios'] = $this->modelo_universal->select('comentarios', '*', array('id_publicacion' => $check[0]['id']));
                            if ($check[0]['comentarios']) {
                                foreach ($check[0]['comentarios'] as $pos2 => $data2) {
                                    $datosuser = $this->datosuser($data2['user']);
                                    if ($datosuser) {
                                        $check[0]['comentarios'][$pos2]['image'] = $datosuser['picture'];
                                        $check[0]['comentarios'][$pos2]['name'] = $datosuser['name'] . ' ' . $datosuser['last_name'];
                                    } else {
                                        $check[0]['comentarios'][$pos2]['image'] = 'Profile-256.png';
                                    }
                                }
                            }
                            $return = array('success' => TRUE, 'publicacion' => $check);
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        } else {
                            $return = array('success' => false, "error" => "La publicaciones no existe");
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => "No se ha enviado publicacion");
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => "La empresa no existe");
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "no se han enviado la empresa");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "no se han enviado datos");
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function timelinedelet() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('tokkenuser') || isset($_GET['tokkenuser'])) {
                $user = $this->authorization();
                if ($user) {
                    $this->db = $this->load->database('default', true);
                    if ($this->input->post('idpublicacion') || isset($_GET['idpublicacion'])) {
                        $idpublicacion = $this->input->post('idpublicacion');
                        if (isset($_GET['idpublicacion'])) {
                            $idpublicacion = $_GET['idpublicacion'];
                        }
                        $check = $this->modelo_universal->select('timeline', '*', array('id' => $idpublicacion));
                        if ($check) {
                            $check[0]['idempresa'];
                        } else {
                            $return = array('success' => false, "error" => "Publicacion no existe");
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => "idcomentario no enviado");
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => "tokken de usuario invalido");
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "no se ha enviado tokken de usuario");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "no se han enviado datos");
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function neochek() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('empresa') || isset($_GET['empresa'])) {
                $empresa = $this->input->post('empresa');
                if (isset($_GET['empresa'])) {
                    $empresa = $_GET['empresa'];
                }
                $empresa = $this->empresa($empresa);
                if ($empresa) {
                    $neopago = $this->modelo_universal->select('neopago','*', array('id_empresa' => $empresa[0]['idempresa']));
                    if ($neopago) {
                        $return = array('success' => TRUE);
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    } else {
                        $return = array('success' => false, "error" => "La empresa no tiene NeoPago Configurado");
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => "La empresa no es valida");
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "No se ha enviado empresa");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "No se han enviado datos");
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function neoreg() {
        if ($this->input->post()) {
            $hh = $this->modelo_universal->insert('neoreg', $_POST);
            if ($hh) {
                $return = array('success' => TRUE, "mensaje" => $this->db->last_query());
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            } else {
                $return = array('success' => false, "error" => $this->db->last_query());
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "No se han enviado datos");
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function analitycs() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('tokkenapp') || isset($_GET['tokkenapp'])) {
                $tokkenapp = $this->input->post('tokkenapp');
                if (isset($_GET['tokkenapp'])) {
                    $tokkenapp = $_GET['tokkenapp'];
                }
                $this->db = $this->load->database('pkaccount', true);
                $app = $this->modelo_universal->query('SELECT * FROM tokken WHERE tokken ="' . $tokkenapp . '" OR id ="' . $tokkenapp . '"');
                if ($app) {
                    if ($this->input->post('trackingid') || isset($_GET['trackingid'])) {
                        $trackingid = $this->input->post('trackingid');
                        if (isset($_GET['trackingid'])) {
                            $trackingid = $_GET['trackingid'];
                        }
                        $insert = $this->modelo_universal->update('tokken', array('analitycs' => $trackingid), array('id' => $app[0]['id']));
                        $this->db = $this->load->database('default', true);
                        if ($insert) {
                            $return = array('success' => true, "mensaje" => "Tracking ID Agregado Satisfactoriamente");
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        } else {
                            $return = array('success' => FALSE, "error" => "No se han modificado datos");
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => "No se ha enviado trackingid");
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => "Tokkenapp enviado es invalido");
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "No se han enviado tokkenapp");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "No se han enviado datos");
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function banner() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('empresa') || isset($_GET['empresa'])) {
                $empresa = $this->input->post('empresa');
                if (isset($_GET['empresa'])) {
                    $empresa = $_GET['empresa'];
                }
                $empresa = $this->empresa($empresa);
                if ($empresa) {
                    $banner = $this->modelo_universal->select('banner', '*', array('idempresa' => $empresa[0]['idempresa']));
                    if ($banner) {
                        $return = array('success' => true, "banners" => $banner);
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    } else {
                        $return = array('success' => false, "error" => "No se han agregados banner");
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
                } else {
                    $return = array('success' => false, "error" => "No se ha enviado empresa");
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "No se ha enviado empresa");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "No se han enviado datos");
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function prod_dest() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('empresa') || isset($_GET['empresa'])) {
                $empresa = $this->input->post('empresa');
                if (isset($_GET['empresa'])) {
                    $empresa = $_GET['empresa'];
                }
                $empresa = $this->empresa($empresa);
                if ($empresa) {
                    $destacados = $this->modelo_universal->select('productos_destacados', '*', array('idempresa' => $empresa[0]['idempresa']));
                    if ($destacados) {
                        if ($destacados[0]['idproducto1'] == -1) {
                            $producto1 = $this->modelo_universal->select('productos', '*', array('empresa_idempresa' => $empresa[0]['idempresa']), 0, 1, 'idproductos', 'random');
                        } else {
                            $producto1 = $this->modelo_universal->select('productos', '*', array('idproductos' => $destacados[0]['idproducto1']));
                            if (!$producto1) {
                                $producto1 = $this->modelo_universal->select('productos', '*', array('empresa_idempresa' => $empresa[0]['idempresa']), 0, 1, 'idproductos', 'random');
                            }
                        }
//                            $producto1 = $this->db->last_query();
                        if ($destacados[0]['idproducto2'] == -1) {
                            $producto2 = $this->modelo_universal->select('productos', '*', array('empresa_idempresa' => $empresa[0]['idempresa']), 0, 1, 'idproductos', 'random');
                        } else {
                            $producto2 = $this->modelo_universal->select('productos', '*', array('idproductos' => $destacados[0]['idproducto2']));
                            if (!$producto2) {
                                $producto2 = $this->modelo_universal->select('productos', '*', array('empresa_idempresa' => $empresa[0]['idempresa']), 0, 1, 'idproductos', 'random');
                            }
                        }
                        $producto1 = $producto1[0];
                        $producto2 = $producto2[0];

                        if ($producto1 && $producto2) {
                            $productos[0] = $producto1;
                            $productos[1] = $producto2;

                            $return = array('success' => TRUE, "destacados" => $productos);
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        } else {
                            $return = array('success' => false, "error" => "Error al Consultar Destacados");
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $productos = $this->modelo_universal->select('productos', '*', array('empresa_idempresa' => $empresa[0]['idempresa']), 0, 2, 'idproductos', 'random');
                        if ($productos) {
                            $return = array('success' => TRUE, "destacados" => $productos);
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        } else {
                            $return = array('success' => false, "error" => "Error al Consultar Destacados");
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    }
                } else {
                    $return = array('success' => false, "error" => "No se ha enviado empresa");
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "No se ha enviado empresa");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "No se han enviado datos");
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function pedidoadolar($id = NULL) {
        $monedas = $this->modelo_universal->select('moneda', '*');
//        debug($monedas);
        $mon = array();
        foreach ($monedas as $key => $value) {
//            echo $value['abreviado'];
            $ss = array($value['abreviado'] => $value['valor']);
            $mon = array_merge($mon, $ss);
        }
        if ($id) {
            $pedido = $this->modelo_universal->select('carro_compra', '*', array('id' => $id));
            if ($pedido) {
                $productos = $this->modelo_universal->query('SELECT productos.*, empresa.moneda as moneda, moneda.valor, carro_compra_pago.cantidad as pedido FROM carro_compra_pago, productos, empresa, moneda WHERE carro_compra_pago.id_pedido=' . $id . ' AND carro_compra_pago.id_producto=productos.idproductos AND productos.empresa_idempresa=empresa.idempresa AND empresa.moneda=moneda.abreviado');
                if ($productos) {
                    $total = 0;
                    $dolares = 0;
                    $euros = 0;
                    foreach ($productos as $key => $value) {
                        $bolivares = $value['precio'] * $mon[$value['moneda']];

                        $suma = $value['pedido'] * $bolivares;
                        $total = $total + $suma;

                        $suma = $value['pedido'] * ($bolivares / $mon['$']);
                        $dolares = $dolares + $suma;

                        $suma = $value['pedido'] * ($bolivares / $mon['â‚¬']);
                        $euros = $euros + $suma;
                    }
                    $return = array('success' => TRUE, "pedido" => $id, 'BS' => round($total, 2), 'USD' => round($dolares, 2), 'EUR' => round($euros, 2));
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                } else {
                    $return = array('success' => false, "error" => "El Pedido no tiene productos");
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "El Pedido no Existe");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "No se han enviado datos");
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function mpcuenta($id = null) {
        if ($id) {
            $empresa = $this->empresa($id);
            if ($empresa) {
                $paypal = $this->modelo_universal->select('mercadopago', '*', array('idempresa' => $empresa[0]['idempresa']));
                if ($paypal) {
                    $return = array('success' => true, "cuenta" => $paypal[0]);
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                } else {
                    $return = array('success' => false, "error" => "La empresa no tiene cuenta de paypal registrada");
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "La empresa no existe");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "No se han enviado datos");
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function paypalcuenta($id = null) {
        if ($id) {
            $empresa = $this->empresa($id);
            if ($empresa) {
                $paypal = $this->modelo_universal->select('paypal', '*', array('id_empresa' => $empresa[0]['idempresa']));
                if ($paypal) {
                    $return = array('success' => true, "cuenta" => $paypal[0]);
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                } else {
                    $return = array('success' => false, "error" => "La empresa no tiene cuenta de paypal registrada");
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "La empresa no existe");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "No se han enviado datos");
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
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

    private function curl($url = null, $datos = null, $post = null) {
        if ($url) {
            $var = "";
//            $h = $this->url_exists($url);
//            if ($h) {
            if ($post) {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, 1);
                if ($datos) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $datos);
                }
            } else {
                if ($datos) {
                    $var.="?";
                    $n = 0;
                    foreach ($datos as $key => $value) {
                        if ($n > 0) {
                            $var .= "&";
                        }
                        $var.=$key . "=" . urlencode($value);
                        $n = $n + 1;
                    }
                }
                $ch = curl_init($url . $var);
            }
//            } else {
//                $retr = array('success' => false, 'error' => 'La funcion indicada no existe');
//                return $retr;
//            }
//            debug($accion, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $respuesta = curl_exec($ch);
            $error = curl_error($ch);
            $decoded = json_decode($respuesta, true);
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
            $retr = array('success' => false, 'error' => 'no se ha enviado url');
            return $retr;
        }
    }

    public function ols() {
        echo"Post: <br>";
        debug($_POST, false);
        echo"Get: <br>";
        debug($_GET, false);
        echo"Request: <br>";
        debug($_REQUEST, false);
        echo"Server: <br>";
        debug($_SERVER, false);
    }

    public function mercadopagors() {
//        $url = "http://pkclick.com/pkapi/ols";
//        $url = "http://api.mercadolibre.com/sandbox/collections/notifications/1430296420";
//        $datos = array("access_token" => "APP_USR-2559493792267909-042904-0430fabc051f5904e72bf52976ff5447__H_G__-167480625");
//        foreach ($datos as $key => $value) {
//            echo "key = " . $key . "<br>";
//            echo "value = " . $value . "<br>";
//        }
//        $ss = $this->curl($url, $datos);
//        debug($ss);
//        echo $ss;
        if ($this->input->post()) {
            $insert = json_encode($this->input->post());
            $this->modelo_universal->insert('prueba', array('json' => $insert));
        }
    }

    public function url() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('url') || isset($_GET['url'])) {
                $url = $this->input->post('url');
                if (isset($_GET['url'])) {
                    $url = $_GET['url'];
                }
                if (($url == "http://localhost") || ($url == "http://usuario-p")) {
                    $url = "http://localhost/plantilla";
                }
                $this->db = $this->load->database('pkaccount', true);

                $tokken = $this->modelo_universal->query('SELECT id,tokken,idempresa,estatus FROM tokken  WHERE redirect LIKE "%' . $url . '"');
//                $tokken = $this->modelo_universal->select('tokken', 'id,tokken,idempresa,estatus', array('redirect' => $url));
                if ($tokken) {
                    $tokken = $tokken[0];
                    $color = $this->modelo_universal->select('color', '*', array('tokken' => $tokken['id']));
                    $tokken['config'] = $color;
                    $return = array('success' => TRUE, "datos" => $tokken);
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                } else {
                    $return = array('success' => false, "error" => "No se ha encontrado URL");
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "No se han enviado la url");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "No se han enviado datos");
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
        $this->db = $this->load->database('default', true);
    }

    public function urlcolor() {
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            $this->db = $this->load->database('pkaccount', true);
            $config = $this->modelo_universal->select('color', '*', array('tokken' => $this->input->post('tokken')));
            if ($this->input->post('urlimg')) {
                $s = $this->recibe_imagen($this->input->post('urlimg'), '/home/kamilahosting/public_html/imagenescarrito/urlfondo/' . $this->input->post('file'));
                unset($_POST['urlimg']);
            } else {
                $_POST['file'] = $config[0]['file'];
            }
            if ($config) {
                $this->modelo_universal->update('color', $this->input->post(), array('id' => $config[0]['id']));
                $return = array('success' => true, "action" => "update");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            } else {
                $this->modelo_universal->insert('color', $this->input->post());
                $return = array('success' => true, "action" => "insert");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "No se han enviado datos");
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public function prueba3($tokkenuser = null, $empresa = null) {
        $s = json_decode('{"success":false,"idcompra":"157","estatusoperacion":"DENIED","totalpago":"5.00","voucher":"__<br>_______________BANPLUS_<br>__________RIF_J-00042303-2_<br>__________RECIBO_DE_COMPRA_<br>_<br>NEOPAGO_SISTEMA_DE_MEDIOS_DE_PAGO,_C.A._<br>COLINAS_DE_BELLO_MONTE_<br>RIF:J-29760964-4_<br>AFILIADO:0076582947_<br>TERMINAL:00004002_LOTE:1_<br>CUENTA:540140******4362__<br>FECHA:06\/02\/2015_HORA:11:47:47_<br>TRACE:3094_NRO._REF:002885_<br>_<br>TRANSACCION_FALLIDA_<br>TARJETA_RESTRINGIDA__(62)_<br>_<br>_<br>","product_list":"","rif":"18465197","cliente":"WILFREDO A LINARES M","correo":"WILFREDO A LINARES M"}');
        debug($s);
    }

    public function prueba2($tokkenuser = null, $empresa = null) {
        $prueba = $this->modelo_universal->query('SELECT * FROM prueba WHERE id=18 OR id=22');
        foreach ($prueba as $key => $value) {
            debug(json_decode($value['json']), false);
        }
    }

    public function bancos($id_empresa, $email = false) {
        if ($id_empresa) {
            $bancos = $this->modelo_universal->select('bancos', '*', array('id_empresa' => $id_empresa));
            if ($bancos) {
                if ($email) {
                    $bank = '';
                    $bank .= '<div class="bancos"><table>
                <thead>
                    <tr style="height: 40px;">
                        <td colspan="5"><strong>Cuentas Bancarias</strong></td>
                    </tr>
                    <tr style="height: 40px;">
                        <td style="width: 150px;"><strong>Banco</strong></td>
                        <td style="width: 150px;"><strong>Beneficiario</strong></td>
                        <td style="width: 150px;"><strong>Identificaci&oacute;n</strong></td>
                        <td style="width: 165px;"><strong>Cuenta</strong></td>
                        <!--<td><strong>Opciones</strong></td>-->
                    </tr>
                </thead>
                <tbody>';
                    foreach ($bancos as $banco) {
                        $bank.='<tr style="height: 40px;">
                <td>' . $banco['banco'] . '</td>
                <td>' . $banco['beneficiario'] . '</td>
                <td>' . $banco['tipo'] . '-' . $banco['identificacion'] . '</td>
                <td>' . $banco['cuenta'] . '</td>
            </tr>';
                    }
                    $bank.='</tbody>
                <tfoot>
                    <tr style="height: 40px;">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <ul>
            </ul>
        </div>';
                    return $bank;
                } else {
                    return $bancos;
                }
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    private function correo_pedido($idpedido) {
        $this->db = $this->load->database('default', true);
        $pedido = $this->modelo_universal->select('carro_compra', '*', array('id' => $idpedido, 'estatus' => 0));
        if ($pedido) {
            $datos = $this->datosuser($pedido[0]['id_user']);
            $empresa = $this->datosempresa($pedido[0]['id_empresa']);
            $productos = $this->modelo_universal->query('SELECT productos.imagen, productos.nombre, carro_compra_pago.cantidad FROM productos, carro_compra_pago WHERE productos.idproductos=carro_compra_pago.id_producto AND carro_compra_pago.id_pedido=' . $idpedido);
            $bank = bancos($empresa['idempresa'], 1);

            $mail = "<html>
                        <head>
                            <title>Pedido #" . $idpedido . " PkClick</title>
                            <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
                            <meta content='width=device-width, initial-scale=1.0' name='viewport'>
                            <link type='text/css' rel='stylesheet' href='http://fonts.googleapis.com/css?family=Open+Sans'>
                        </head>
                        <body>
                            <div>
                                <p>Empresa: <strong>" . $empresa['nombre2'] . "</strong> ha recibido el siguiente pedido:</p>
                                <p> Usuario: " . $datos['user'] . " <img style='height: 25px; width: 25px;' src='http://www.pkclick.com/pknetmarketing.com/images/Profile-256.png'></p>
                                <p>Correo: " . $datos['email'] . "</p>
                                <p>Nombre: " . $datos['name'] . " " . $datos['last_name'] . "</p>
                                <p>Total: " . number_format($pedido[0]['total'], 2, ',', '.') . "</p>
                            </div>
                            <div>
                                <div style='overflow: hidden;' class='productos'>
                                    <p style='text-align: center;font-size: 30;margin: 10 0; '>
                                        Lista de Productos
                                    </p>
                                    <p style='display: inline-flex;width: 100%;text-align: center;font-size: 23px;margin: 0px; border-bottom: 1px solid;'>
                                        <a style='width: 50%; display: block;'>Producto</a>
                                        <a style='width: 50%; display: block;'>Cantidad</a>
                                    </p>                                                
                                                ";
            foreach ($productos as $producto) {
                $mail .="<p style='display: inline-flex;width: 100%;text-align: center;margin: 0px;font-size: 20px; border-bottom: 1px solid;'>
                                    <a style='width: 50%;display: block;'>
                                        <img style='height: 20px; width: 20px;' src='http://www.pkclick.com/imagenescarrito/small/" . $producto['imagen'] . "' alt=''>
                                        " . $producto['nombre'] . "
                                    </a>
                                    <a style='width: 50%;display: block;'>" . $producto['cantidad'] . "</a>
                                </p>";
            }
            $mail .="</div>
                                  " . $bank . "
                                    </body>
                                </html>";
//                                debug($mail);
            $para = $empresa['email'] . ', '; // atención a la coma
            $para .= $datos['email'];
            $titulo = "Pedido #" . $idpedido . " PkClick";
            $cabeceras = 'MIME-Version: 1.0' . "\r\n";
            $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $cabeceras .= 'From: PkClick (no-replay) <pkpclick@pkclick.com>' . "\r\n";
//                                $cabeceras .= "co: " . $datos['name'] . " " . $datos['last_name'] . " <" . $datos['email'] . ">" . "\r\n";
            $cabeceras .= 'Cc: ' . $datos['email'] . "\r\n";
            //        $cabeceras .= 'Bcc: birthdaycheck@example.com' . "\r\n";
            // Enviarlo
            $s = mail($para, $titulo, $mail, $cabeceras);
            if ($s) {
                $update = $this->modelo_universal->update('carro_compra', array('estatus' => 1), array('id' => $idpedido));
                if ($update) {
                    $return = array('success' => TRUE);
                } else {
                    $return = array('success' => false, "error" => "No se ha realizado el pedido");
                }
            } else {
                $return = array('success' => false, "error" => "No se ha enviado el correo");
            }
        } else {
            $return = array('success' => false, "error" => "El Pedido no Existe");
        }
    }

    public function pagos() {
//        if ($this->input->post()) {
//            $insert = json_encode($this->input->post());
//            $this->modelo_universal->insert('prueba', array('json' => $insert));
//        }
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            $this->db = $this->load->database('default', true);
            if ($this->input->post('idpedido') || isset($_GET['idpedido'])) {
                $idpedido = $this->input->post('idpedido');
                if (isset($_GET['idpedido'])) {
                    $idpedido = $_GET['idpedido'];
                }
                $pedido = $this->modelo_universal->select('carro_compra', '*', array('id' => $idpedido, 'estatus' => 0));
                if ($pedido) {
                    $datos = $this->datosuser($pedido[0]['id_user']);
                    $empresa = $this->datosempresa($pedido[0]['id_empresa']);
                    $productos = $this->modelo_universal->query('SELECT productos.imagen, productos.nombre, carro_compra_pago.cantidad FROM productos, carro_compra_pago WHERE productos.idproductos=carro_compra_pago.id_producto AND carro_compra_pago.id_pedido=' . $idpedido);
//                                debug($datos, false);
//                                debug($empresa, false);
                    $bancos = $this->modelo_universal->select('bancos', '*', array('id_empresa' => $empresa['idempresa']));
                    $bank = '';
                    if (isset($bancos) && ($bancos)) {
                        $bank .= '<div class="bancos">
            <table>
                <thead>
                    <tr style="height: 40px;">
                        <td colspan="5"><strong>Cuentas Bancarias</strong></td>
                    </tr>
                    <tr style="height: 40px;">
                        <td style="width: 150px;"><strong>Banco</strong></td>
                        <td style="width: 150px;"><strong>Beneficiario</strong></td>
                        <td style="width: 150px;"><strong>Identificaci&oacute;n</strong></td>
                        <td style="width: 165px;"><strong>Cuenta</strong></td>
                        <!--<td><strong>Opciones</strong></td>-->
                    </tr>
                </thead>
                <tbody>';
                        foreach ($bancos as $banco) {
                            $bank.='<tr style="height: 40px;">
                <td>' . $banco['banco'] . '</td>
                <td>' . $banco['beneficiario'] . '</td>
                <td>' . $banco['tipo'] . '-' . $banco['identificacion'] . '</td>
                <td>' . $banco['cuenta'] . '</td>
            </tr>';
                        }
                        $bank.='</tbody>
                <tfoot>
                    <tr style="height: 40px;">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <ul>
            </ul>
        </div>';
                    }
                    $mail = "<html>
                                            <head>
                                                <title>Pedido #" . $idpedido . " PkClick</title>
                                                <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
                                                <meta content='width=device-width, initial-scale=1.0' name='viewport'>
                                                <link type='text/css' rel='stylesheet' href='http://fonts.googleapis.com/css?family=Open+Sans'>
                                            </head>
                                            <body>
                                                <div>
                                                    <p>Empresa: <strong>" . $empresa['nombre2'] . "</strong> ha recibido el siguiente pedido:</p>
                                                    <p> Usuario: " . $datos['user'] . " <img style='height: 25px; width: 25px;' src='http://www.pkclick.com/pknetmarketing.com/images/Profile-256.png'></p>
                                                    <p>Correo: " . $datos['email'] . "</p>
                                                    <p>Nombre: " . $datos['name'] . " " . $datos['last_name'] . "</p>
                                                    <p>Total: " . number_format($pedido[0]['total'], 2, ',', '.') . "</p>
                                                </div>
                                                <div>
                                                    <div style='overflow: hidden;' class='productos'>
                                                        <p style='text-align: center;font-size: 30;margin: 10 0; '>
                                                            Lista de Productos
                                                        </p>
                                                        <p style='display: inline-flex;width: 100%;text-align: center;font-size: 23px;margin: 0px; border-bottom: 1px solid;'>
                                                            <a style='width: 50%; display: block;'>Producto</a>
                                                            <a style='width: 50%; display: block;'>Cantidad</a>
                                                        </p>                                                
                                                ";
                    foreach ($productos as $producto) {
                        $mail .="<p style='display: inline-flex;width: 100%;text-align: center;margin: 0px;font-size: 20px; border-bottom: 1px solid;'>
                                    <a style='width: 50%;display: block;'>
                                        <img style='height: 20px; width: 20px;' src='http://www.pkclick.com/imagenescarrito/small/" . $producto['imagen'] . "' alt=''>
                                        " . $producto['nombre'] . "
                                    </a>
                                    <a style='width: 50%;display: block;'>" . $producto['cantidad'] . "</a>
                                </p>";
                    }
                    $mail .="</div>
                                        </div>
                                    </body>
                                </html>";
//                                debug($mail);
                    $para = $empresa['email'] . ', '; // atención a la coma
                    $para .= $datos['email'];
                    $titulo = "Pedido #" . $idpedido . " PkClick";
                    $cabeceras = 'MIME-Version: 1.0' . "\r\n";
                    $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $cabeceras .= 'From: PkClick (no-replay) <pkpclick@pkclick.com>' . "\r\n";
//                                $cabeceras .= "co: " . $datos['name'] . " " . $datos['last_name'] . " <" . $datos['email'] . ">" . "\r\n";
                    $cabeceras .= 'Cc: ' . $datos['email'] . "\r\n";
                    //        $cabeceras .= 'Bcc: birthdaycheck@example.com' . "\r\n";
                    // Enviarlo
                    $s = mail($para, $titulo, $mail, $cabeceras);
                    if ($s) {
                        $update = $this->modelo_universal->update('carro_compra', array('estatus' => 1), array('id' => $idpedido));
                        if ($update) {
                            $return = array('success' => TRUE);
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        } else {
                            $return = array('success' => false, "error" => "No se ha realizado el pedido");
                            header('Content-type: text/json');
                            header('Content-type: application/json');
                            echo json_encode($return);
                        }
                    } else {
                        $return = array('success' => false, "error" => "No se ha enviado el correo");
                        header('Content-type: text/json');
                        header('Content-type: application/json');
                        echo json_encode($return);
                    }
//                                echo $mail;
                } else {
                    $return = array('success' => false, "error" => "El Pedido no Existe");
                    header('Content-type: text/json');
                    header('Content-type: application/json');
                    echo json_encode($return);
                }
            } else {
                $return = array('success' => false, "error" => "ID de pedido no enviado");
                header('Content-type: text/json');
                header('Content-type: application/json');
                echo json_encode($return);
            }
        } else {
            $return = array('success' => false, "error" => "no se han enviado datos", "errornumber" => 0);
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($return);
        }
    }

    public
            function prueba($tokkenuser = null, $empresa = null) {
//        $ar = file_exists($tokkenuser);
        $file = $tokkenuser;
//        debug($file, false);
        $file_headers = @get_headers($file);
//        debug($file_headers, false);
        if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {
            $exists = false;
        } else {
            $exists = true;
        }
//        debug($exists);
        return $exists;
//        debug($ar);
    }

}

?>