<?php

class Api extends CI_Controller {
    public $data = null;

    public function __construct() {
	
        parent::__construct();
        
        date_default_timezone_set("America/Caracas");

        error_reporting(-1);
        ini_set('display_errors', '1');
        $this->load->model('modelo_universal');
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
                } else {
                    $return = array('success' => false, "error" => "No se ha encontrado URL", 'errornumber'=> 7);
                }
            } else {
                $return = array('success' => false, "error" => "No se han enviado la url", 'errornumber'=> 8);
            }
        } else {
            $return = array('success' => false, "error" => "No se han enviado datos", 'errornumber'=> 9);
        }
        header('Content-type: text/json');
        header('Content-type: application/json');
        echo json_encode($return);
        $this->db->close();
       // $this->db = $this->load->database('default', true);
    }
    
    private function company_tokken($id = null){//validar el tokken de la empresa
        //al ser una funcion private, no responde json porque es para uso initerno del api (se usa desde las funciones que responden json para indicar el idcompany de el token enviado)
            //valido si me enviaron el token directo a la funcion
        $this->db = $this->load->database('default', true);
        if ($id){
            //si me lo enviaron verifico que existe
            $tokken = $this->modelo_universal->query("SELECT * FROM tokken WHERE company_id = ".$id);
            if ($tokken){
                //si existe retorno el identificador de la compañia
                return (int) $tokken[0]['tokken'];
            }else{
                //de no existir retorno null
                return null;
            }
        }else{
            //si no me enviaron el token directo a la funcion, verifico si me lo enviaron por post, o por get
             $tokkn = $this->input->post('token');
            if (isset($_GET['token'])) {
                $tokkn = $_GET['token'];
            }
            if ($tokkn){
                //si me lo enviaron verifico que existe
                $tokken = $this->modelo_universal->query("SELECT * FROM tokken WHERE tokken = '".$tokkn."'");
                if ($tokken){
                    //si existe retorno el identificador de la compañia
                    return (int) $tokken[0]['company_id'];
                }else{
                    //de no existir retorno null
                    return null;
                }
            }else{
                // de no haberme enviado el token por post o get, retorno null
                return null;
            }
        }
        $this->db->close();
    }
    private function tokken_id($tokkn = null){//validar el tokken de la empresa
        //al ser una funcion private, no responde json porque es para uso initerno del api (se usa desde las funciones que responden json para indicar el idcompany de el token enviado)
            //valido si me enviaron el token directo a la funcion
        $this->db = $this->load->database('default', true);
        if ($tokkn){
            //si me lo enviaron verifico que existe
            $tokken = $this->modelo_universal->query("SELECT * FROM tokken WHERE tokken = '".$tokkn."'");
            if ($tokken){
                //si existe retorno el identificador de la compañia
                return (int) $tokken[0]['company_id'];
            }else{
                //de no existir retorno null
                return null;
            }
        }else{
            //si no me enviaron el token directo a la funcion, verifico si me lo enviaron por post, o por get
             $tokkn = $this->input->post('token');
            if (isset($_GET['token'])) {
                $tokkn = $_GET['token'];
            }
            if ($tokkn){
                //si me lo enviaron verifico que existe
                $tokken = $this->modelo_universal->query("SELECT * FROM tokken WHERE tokken = '".$tokkn."'");
                if ($tokken){
                    //si existe retorno el identificador de la compañia
                    return (int) $tokken[0]['company_id'];
                }else{
                    //de no existir retorno null
                    return null;
                }
            }else{
                // de no haberme enviado el token por post o get, retorno null
                return null;
            }
        }
        $this->db->close();
    }
     private function user_id($ip = null){
        if($ip){
            $this->db = $this->load->database('pkaccount', true);
            $userid = $this->modelo_universal->select('session', 'user', array('ip' => $ip, 'flag' => 1));
            $this->db->close();
//            debug($this->db->last_query(), false);
            if ($userid) {
                return (int) $userid[0]['user'];
                //return $userid[0];
            } else {
                return false;
            }
        }else{
           //si no me enviaron el token directo a la funcion, verifico si me lo enviaron por post, o por get
            $ip = $this->input->post('tokkenuser');
            if (isset($_GET['tokkenuser'])) {
                $ip = $_GET['tokkenuser'];
            }
            if ($ip){
                //si me lo enviaron verifico que existe
                $this->db = $this->load->database('pkaccount', true);
                $userid = $this->modelo_universal->select('session', 'user', array('ip' => $ip, 'flag' => 1));
                $this->db->close();
    //            debug($this->db->last_query(), false);
                if ($userid) {
                    return (int) $userid[0]['user'];
                    //return $userid[0];
                } else {
                    return false;
                }
            }else{
                // de no haberme enviado el token por post o get, retorno null
                return null;
            
            }
        }
    }
    public function permit_user($userid,$companyid){
        if (isset($companyid) && isset($userid)){
            $this->db = $this->load->database('default', true);
            $permit= $this->modelo_universal->query('SELECT module.* FROM module, permits, company WHERE permits.user='.$userid.' AND permits.id_company=company.id AND module.id=permits.id_module AND company.id='.$companyid);
            $this->db->close();
            //debug($permit);
            if ($permit){
                return $permit;
            }else {
                return null;
            }    
        }else{
            return null;
        }
    }
    
    public function direccion_script() {
        $data= "<script>
             $(document).ready(function(){
                    $('#country').append('<option class=\"load\" value=\"\">CARGANDO..</option>');
                     $('#state').prop('disabled', true);
                     $('#town').prop('disabled', true);
                     $('#parish').prop('disabled', true);
                     $.ajax({
                            async: true,
                            type: 'POST',
                            dataType: 'html',
                            contentType: 'application/x-www-form-urlencoded',
                            url: '".base_url()."/api/direccion', 
                            data: 'country=1&',
                            success: function(data) {
                                //$('.respuesta').html(data);
                                var obj = jQuery.parseJSON(data);
                                if (obj.success){
                                    for (h in obj.answer){
                                        console.log(h);
                                        console.log(obj.answer[h]);
                                        $('.respuesta').html(obj.message);
                                        $('.load').html('SELECCIONE');
                                        $('#country').append('<option currency=\"'+ obj.answer[h].id_currency +'\" value=\"'+ obj.answer[h].id +'\">'+obj.answer[h].name.toUpperCase() +'</option>');
                                    }
                                }else{
                                    $('.respuesta').html(obj.error);
                                }
                                //alert('El nombre es: '+ obj.success);
                                console.log(obj);
                            }
                    });
                    
                    $('#country').change(function(){
                        
                        $('#state').html('');
                        $('#state').append('<option class=\"load\" value=\"\">CARGANDO..</option>');
                        $('#town').html('');
                        $('#parish').html('');
                        $('#state').prop('disabled', true);
                        $('#town').prop('disabled', true);
                        $('#parish').prop('disabled', true);
                         $.ajax({
                            async: true,
                            type: 'POST',
                            dataType: 'html',
                            contentType: 'application/x-www-form-urlencoded',
                            url: '"base_url()."/api/direccion', 
                            data: 'state=1&idparent='+ $('#country').val(),
                            success: function(data) {
                                //$('.respuesta').html(data);
                                var obj = jQuery.parseJSON(data);
                                if (obj.success){
                                    for (h in obj.answer){
                                        console.log(h);
                                        console.log(obj.answer[h]);
                                        $('.respuesta').html(obj.message);
                                        $('.load').html('SELECCIONE');
                                        $('#state').append('<option value=\"'+ obj.answer[h].id +'\">'+obj.answer[h].name.toUpperCase() +'</option>');
                                    }
                                    $('#state').prop('disabled', false);
                                }else{
                                    $('.respuesta').html(obj.error);
                                }
                                console.log(obj);
                            }
                        });
                    });
                    $('#state').change(function(){
                        $('#town').html('');
                        $('#town').append('<option class=\"load\" value=\"\">CARGANDO..</option>');
                        $('#parish').html('');
                        $('#town').prop('disabled', true);
                        $('#parish').prop('disabled', true);
                         $.ajax({
                            async: true,
                            type: 'POST',
                            dataType: 'html',
                            contentType: 'application/x-www-form-urlencoded',
                            url: '".base_url()."/api/direccion', 
                            data: 'town=1&idparent='+ $('#state').val(),
                            success: function(data) {
                                //$('.respuesta').html(data);
                                var obj = jQuery.parseJSON(data);
                                if (obj.success){
                                    for (h in obj.answer){
                                        console.log(h);
                                        console.log(obj.answer[h]);
                                        $('.respuesta').html(obj.message);
                                        $('.load').html('SELECCIONE');
                                        $('#town').append('<option value=\"'+ obj.answer[h].id +'\">'+obj.answer[h].name.toUpperCase() +'</option>');
                                    }
                                    $('#town').prop('disabled', false);
                                }else{
                                    $('.respuesta').html(obj.error);
                                }
                                console.log(obj);
                            }
                        });
                    });
                    $('#town').change(function(){
                        
                        $('#parish').html('');
                        $('#parish').append('<option class=\"load\" value=\"\">CARGANDO..</option>');
                        $('#parish').prop('disabled', true);
                         $.ajax({
                            async: true,
                            type: 'POST',
                            dataType: 'html',
                            contentType: 'application/x-www-form-urlencoded',
                            url: '".base_url()."/api/direccion', 
                            data: 'parish=1&idparent='+ $('#town').val(),
                            success: function(data) {
                                var obj = jQuery.parseJSON(data);
                                if (obj.success){
                                    for (h in obj.answer){
                                        console.log(h);
                                        console.log(obj.answer[h]);
                                        $('.respuesta').html(obj.message);
                                        $('.load').html('SELECCIONE');
                                        $('#parish').append('<option value=\"'+ obj.answer[h].id +'\">'+obj.answer[h].name.toUpperCase() +'</option>');
                                    }
                                    $('#parish').prop('disabled', false);
                                }else{
                                    $('.respuesta').html(obj.error);
                                }
                                console.log(obj);
                            }
                        });
                    });
                });
        </script>";
        $return = array('success' => true, "message" => 'Recuerde importar jquery, y crear los input Select con los siguientes ID: #country, #state, #town, #parish ',"answer" => $data);
        header('Content-type: text/json');
        header('Content-type: application/json');
        echo json_encode($return);
    }
    public function direccion() {
        $this->db = $this->load->database('default', true);
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if ($this->input->post('country') || isset($_GET['country'])){
                $data=$this->modelo_universal->select('country','*',null,null,null,'name','ASC');
                if ($data){
                    $return = array('success' => true, "message" => 'su consulta arrojo ('.count($data). ') resultados',"answer" => $data);
                }else{
                    $return = array('success' => false, "error" => 'Error al consultar', 'errornumber'=> 'falta numero');
                }
            }elseif ($this->input->post('idparent') || isset($_GET['idparent'])){
                $idparent= $this->input->post('idparent');
                if (isset($_GET['idparent'])){
                    $idparent=$_GET['idparent'];
                }
                
                if($this->input->post('state') || isset($_GET['state'])){
                    $data=$this->modelo_universal->select('state','*',array('country_id'=>$idparent),null,null,'name','ASC');
                    if ($data){
                        $return = array('success' => true, "message" => 'su consulta arrojo ('.count($data). ') resultados',"answer" => $data);
                    }else{
                        $return = array('success' => false, "error" => 'Error al consultar', 'errornumber'=> 'falta numero');
                    }
                }elseif($this->input->post('town') || isset($_GET['town'])){
                    $data=$this->modelo_universal->select('town','*',array('state_id'=>$idparent),null,null,'name','ASC');
                    if ($data){
                        $return = array('success' => true, "message" => 'su consulta arrojo ('.count($data). ') resultados',"answer" => $data);
                    }else{
                        $return = array('success' => false, "error" => 'Error al consultar', 'errornumber'=> 'falta numero');
                    }      
                } elseif($this->input->post('parish') || isset($_GET['parish'])){
                    $data=$this->modelo_universal->select('parish','*',array('town_id'=>$idparent),null,null,'name','ASC');
                    if ($data){
                        $return = array('success' => true, "message" => 'su consulta arrojo ('.count($data). ') resultados',"answer" => $data);
                    }else{
                        $return = array('success' => false, "error" => 'Error al consultar', 'errornumber'=> 'falta numero');
                    }   
                }else{
                    $return = array('success' => false, "error" => 'Datos enviados no validos', 'errornumber'=> 'falta numero');
                }
            }else{
                $return = array('success' => false, "error" => 'No se ha enviado datos correctamente', 'errornumber'=> 'falta numero');
            }
        }else{
            //aqui verifico si me estan enviando los datos de get y post 
            $return = array('success' => false, "error" => 'No se han enviado datos', 'errornumber'=> 1);
        }
        $this->db->close();
        //siempre se cambia la variable return, para simplificar codigo
        header('Content-type: text/json');
        header('Content-type: application/json');
        echo json_encode($return);
    }
    public function header() {
	
        $this->load->view('header', $this->data);
		
    }

    public function index($mensaje = null) {
            $this->url();
            
            $this->header();
            $this->load->view('index', $this->data);
			$this->footer();

    }

    public function footer() {
	
        $this->load->view('footer', $this->data);
		
    }
    
    
    
    
    
    public function rif($nrif = null) {
        require_once ('./application/libraries/Rif.php');
        if($nrif == null){
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if($_POST['rif']){
                $nrif = $_POST['rif'];
            }
            if($_GET['rif']){
                $nrif = $_POST['rif'];
            }
        }
        }
        
        //$nrif = 'V-18910941-1';
        $mp = new Rif($nrif);
        $rf = json_decode($mp->getInfo());
        
        switch ($rf->code_result) {
          case 1:
              //$this-data[''] = ;
            /*$texto  = "Razón social: {$rf->seniat->nombre}<br />"
                    . "Agente Retención: {$rf->seniat->agenteretencioniva}<br />"
                    . "Contribuyente IVA: {$rf->seniat->contribuyenteiva}<br />"
                    . "Tasa: {$rf->seniat->tasa}<br />"
                    . "Rif: ".$nrif;*/
                    //$texto = $rf->seniat;
                    $r = array(
                        'Razón social' => $rf->seniat->nombre,
                        'Agente Retención' => $rf->seniat->agenteretencioniva,
                        'Contribuyente IVA' => $rf->seniat->contribuyenteiva,
                        'Tasa' => $rf->seniat->tasa
                        );
                    $texto = array(
                        'success' => true,
                        "answer" => $r
                        
                        );
            break;
        
          default:
            $texto = array('success' => false);
          break;
        }
        //echo json_encode($texto);
        return $texto;
        //debug($rf);
    }
    public function company(){
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            $companyid=$this->tokken_id();
            //$return = $companyid;
            if ($companyid){
                $userid = $this->user_id();
                if($userid){
                    $data = $this->consult('company',array('id'=> $companyid));
                    if($data != null or $data != false){
                        $return = array('success' => true, "message" => 'su busqueda arrojo ('.count($data). ') resultados',"answer" => $data);
                    }
                }else{
                    
                    $return = array('success' => false, "error" => 'Tokenuser invalido', 'errornumber'=> 'falta numero');
                }
            }else{
                $return = array('success' => false, "error" => 'Token invalido', 'errornumber'=> 3);
            }
        }else{
            //no se te olvide poner este else, no lo tenias xD
             $return = array('success' => false, "error" => "No se han enviado datos", 'errornumber'=> 1);
        }
        
        header('Content-type: text/json');
        header('Content-type: application/json');
        echo json_encode($return);
        //echo json_encode($userid);
    }
    public function module(){
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            $userid= $this->user_id();
            if ($userid){
                $this->db = $this->load->database('default', true);
                $data = $this->permit_user($userid,$this->input->post('companyid'));
                if($data){
                    $return = array('success' => true, "message" => 'su busqueda arrojo ('.count($data). ') resultados',"answer" => $data);
                }else{
                    $return = array('success' => false, "error" => "no tienes permisos para esta empresa", 'errornumber'=> 10);
                }
            }else{
                $return = array('success' => false, "error" => "Tokkenuser Invalido", 'errornumber'=> 'falta numero');
            }
        }else{
            $return = array('success' => false, "error" => "No se han enviado datos", 'errornumber'=> 1);
        }
        header('Content-type: text/json');
        header('Content-type: application/json');
        echo json_encode($return);
    }
    
    
    
    /***************/

//tokken 116
    // tabla, condition(defaul(null) campos(defaul '*') 
    public function consultaccount($tabla, $condition = null, $select = '*'){
        $this->db = $this->load->database('pkaccount', true);
        $consult = $this->modelo_universal->select($tabla, $select, $condition);
        $this->db->close();
        return $consult;
        
        //debug($user_list);
    }
    public function consult($tabla, $condition = null, $select = '*'){
        $this->db = $this->load->database('default', true);
        $consult = $this->modelo_universal->select($tabla, $select, $condition);
        $this->db->close();
        return $consult;
        //debug($user_list);
    }
    
    public function pr(){
        //debug($this->consult('company'));
        //debug($this->consultaccount('user',array('id'=> 3)));
        //$this->modelo_universal->insert('currency', array('name' => 'Bolivares', 'symbol'=> 'Bs'));
        //$this->modelo_universal->insert('country', array('name' => 'Venezuela', 'id_currency'=> mysql_insert_id()));
        
    }
    
   
   
   
    
    public function addcompany(){
        
       
            //aqui valido que se mande el token de la empresa
                $userid = $this->user_id();
                if($userid){
                
                //si me mandan el token verifico que existe con esa funcion tokken_id que devuelve el identificador de la empresa a la que pertenece ese token
                
                if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)){
                    if($this->input->post('company')){
                        $name = $this->input->post('company');
                        if($this->input->post('di')){
                            //$di = $this->input->post('di');
                            $rift = $this->rif($this->input->post('di'));
                            if($rift['success']==true){
                                
                                if($this->input->post('country')){
                                    $country = $this->input->post('country');
                                        if($this->input->post('currency')){
                                        $currency = $this->input->post('currency');
                                        
                                        $rif = $this->input->post('rif');
                                        if($rift['answer']['Contribuyente IVA'] == 'SI'){
                                            $contributor = 1;
                                        }else{
                                            $contributor = 0;
                                        }
                                        
                                        //$return = $this->input->post('userfile');
                                        if($this->input->post('userfile') != false){
                                           
                                        }else{
                                            $userfile = null;
                                        }
                                         $userfile = $this->input->post('userfile');
                                            $datos = array(
                                                'name' => $name,
                                                'rif' => $this->input->post('rif'),
                                                'logo' => $userfile,
                                                'contributor' => $contributor,
                                                'user' => $userid,
                                                //'address' => $this->input->post('address'),
                                                'id_currency'=> $this->input->post('currency'),
                                                'id_country' => $country,
                                                // hasta aqui lo que necesito para company
                                                /*'name' => $this->input->post('first_name'),
                                                'lastname' => $this->input->post('last_name'),
                                                'phone' => $this->input->post('phone'),
                                                'emal' => $this->input->post('email'),
                                                'state' => $this->input->post('state'),
                                                'town' => $this->input->post('town'),
                                                'parish' => $this->input->post('parish'),
                                                //'date'=>
                                                */
                                            );
                                            $this->db = $this->load->database('default', true);
                                            $this->modelo_universal->insert('company_copy', $datos);
                                            $inderid = ($this->db->insert_id());
                                            if(is_numeric($inderid)) {
                                                //$return = $inderid;
                                                 if($this->input->post('first_name')){
                                                     $first_name = $this->input->post('first_name');
                                                     
                                                     if($this->input->post('last_name')){
                                                         $last_name = $this->input->post('last_name');
                                                         
                                                         if($this->input->post('posicion')){
                                                             $posicion = $this->input->post('posicion');
                                                             
                                                             if($this->input->post('di')){
                                                                 $di = $this->input->post('di');
                                                             }else{
                                                                 $di = null;
                                                             }
                                                                 if($this->input->post('phone')){
                                                                     $phone = $this->input->post('phone');
                                                             
                                                                     if($this->input->post('email')){
                                                                         $email = $this->input->post('email');
                                                                         ///////////////////////////////////////////////////////////////////////////
                                                                         $datos1=array(
                                                                             'first_name' => $first_name,
                                                                             'last_name'=> $last_name,
                                                                             'posicion' => $posicion,
                                                                             'di' => $di,
                                                                             'phone' => $phone,
                                                                             'email' => $email
                                                                             );
                                                                         $this->modelo_universal->insert('contact_copy', $datos);
                                                                         $inderidcontact = ($this->db->insert_id());
                                                                         $return = array('idcompany' => $inderid, 'idcontact' => $inderidcontact);
                                                                     }else{
                                                                         $return = array('success' => false, "error" => "No se han enviado [email]", 'errornumber'=> 1);
                                                                     }
                                                             
                                                         
                                                             
                                                                 }else{
                                                                     $return = array('success' => false, "error" => "No se han enviado [phone]", 'errornumber'=> 1);
                                                                 }
                                                             
                                                         
                                                             
                                                             
                                                         }else{
                                                             $return = array('success' => false, "error" => "No se han enviado [posicion]", 'errornumber'=> 1);
                                                         }
                                                         
                                                     }else{
                                                         $return = array('success' => false, "error" => "No se han enviado [last_name]", 'errornumber'=> 1);
                                                     }
                                                 
                                                 
                                                 }else{
                                                     $return = array('success' => false, "error" => "No se han enviado [first_name]", 'errornumber'=> 1);
                                                 }
                                                
                                                
                                                
                                            }else{
                                                $return = array('success' => false, "error" => "No se ha podido insertar la compa&tilde;ia", 'errornumber'=> '');
                                            }
                                            //$return = '';
                                        }else{
                                           $return = array('success' => false, "error" => "No se han enviado [currency]", 'errornumber'=> 1);
                                        }
                                }else{
                                   $return = array('success' => false, "error" => "No se han enviado [country]", 'errornumber'=> 1);
                                }
                            }else{
                                $return = array('success' => false, "error" => "Rif no registrado en el SENIAT", 'errornumber'=> '');
                            }
                        }else{
                            $return = array('success' => false, "error" => "No se han enviado [rif]", 'errornumber'=> 1);
                        }
                    }else{
                        $return = array('success' => false, "error" => "No se han enviado [company]", 'errornumber'=> 1);
                    }
                    
                        //echo json_encode($datos);
                //$r = $this->modelo_universal->insert('company_copy', $datos);
                //echo json_encode($this->db->insert_id());
                
        
            }
            }else{
                $return = array('success' => false, "error" => 'No se ha autentificado', 'errornumber'=> 'falta numero');
            }
        
        
            
        header('Content-type: text/json');
        header('Content-type: application/json');
        echo json_encode($return);
    }
    
    
    
    public function recibe_imagen($url_origen, $archivo_destino) {
        
        //echo $archivo_destino;
        //exit();
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
    
    private function _create_thumbnailsmall($filename) {
        //$this->load->library('image_lib');
        $config['image_library'] = 'gd2';
        $config['source_image'] = './imgempresa/' . $filename;
        //debug($config['source_image']);
        $config['new_image'] = './imgempresa/small/';
        $config['width'] = 200;
        $config['height'] = 200;
        $this->load->library('image_lib');
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
    }

    

    
    
    
    public function companyxyz(){
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            $token = $this->input->post('token');
            if (isset($_GET['token'])) {
                $token = $_GET['token'];
            }
            //aqui valido que se mande el token de la empresa
            if ($token){
                //si me mandan el token verifico que existe con esa funcion tokken_id que devuelve el identificador de la empresa a la que pertenece ese token
                $tokken=$this->tokken_id($token);
                //la funcion la puedes llamar como hice ahi o sin mandar el token () igual valida el que es por post, o get
                //debug($tokken);
                if ($tokken){
                    $buscar = $this->input->post('buscar');
                    //funcion this->input->post('variable') valida si existe la variable post, de no existir pone null
                    if (isset($_GET['buscar'])) {
                        $buscar = $_GET['buscar'];
                    }
                    //con este guet ahi le doy prioridad al GET para pode trabajar por las URL y ver las respuestas en jdson de una
                    if($buscar){
                        //aqui valido si me enviaron algo que buscar
                        $contact = $this->modelo_universal->query("SELECT * FROM company WHERE (name LIKE '%" . $buscar . "%' OR di LIKE '%" . $buscar . "%' OR contributor LIKE '%" . $buscar . "%' OR type LIKE '%" . $buscar . "%')");
                        //contact tine un query que busca tanto en first_name, last_name, phone, y email, asi no se tiene que hacer tanta validacion en php, y te arroja todos los resultados 
                        if ($contact){
                            //aqui valido si consiguio algo, coloco el sucess en true, y mando el array con los resultados
                            $return = array('success' => true, "message" => 'su busqueda arrojo ('.count($contact). ') resultados',"answer" => $contact);
                        }else{
                            //si no conigue nada, mando el mensaje de que no se mando
                            $return = array('success' => false, "error" => 'Contacto no encontrado', 'errornumber'=> 5);
                        }
                    }else{
                        //aqui si no me enviaron justamente la variable que necesito, que en este caso es buscar
                        $return = array('success' => false, "error" => 'No se ha establecido el elemento a buscar', 'errornumber'=> 2);
                    }
                } else{ 
                    // si da null, es porque el token no existe
                    $return = array('success' => false, "error" => 'Token invalido', 'errornumber'=> 3);
                }
            }else{
                // si no me madan el token de a empresa, no se puede hace nada
                $return = array('success' => false, "error" => 'No se ha ingresado el tokken de la empresa', 'errornumber'=> 4);
            }
        }else{
            //aqui verigico si me estan enviando los datos de enviar 
            $return = array('success' => false, "error" => 'No se han enviado datos', 'errornumber'=> 9);
        }
        //siempre se cambia la variable return, para simplificar codigo
        header('Content-type: text/json');
        header('Content-type: application/json');
        echo json_encode($return);
    }
    
    public function contactxyz(){//consulta el/los contactos de una empresa
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            $token = $this->input->post('token');
            if (isset($_GET['token'])) {
                $token = $_GET['token'];
            }
            //aqui valido que se mande el token de la empresa
            if ($token){
                //si me mandan el token verifico que existe con esa funcion tokken_id que devuelve el identificador de la empresa a la que pertenece ese token
                $tokken=$this->tokken_id($token);
                //la funcion la puedes llamar como hice ahi o sin mandar el token () igual valida el que es por post, o get
                //debug($tokken);
                if ($tokken){
                    $buscar = $this->input->post('buscar');
                    //funcion this->input->post('variable') valida si existe la variable post, de no existir pone null
                    if (isset($_GET['buscar'])) {
                        $buscar = $_GET['buscar'];
                    }
                    //con este guet ahi le doy prioridad al GET para pode trabajar por las URL y ver las respuestas en jdson de una
                    if($buscar){
                        //aqui valido si me enviaron algo que buscar
                        $contact = $this->modelo_universal->query("SELECT * FROM contact WHERE (di LIKE '%" . $buscar . "%' OR first_name LIKE '%" . $buscar . "%' OR last_name LIKE '%" . $buscar . "%' OR phone LIKE '%" . $buscar . "%' OR email LIKE '%" . $buscar . "%') AND id_company=".$tokken);
                        //contact tine un query que busca tanto en first_name, last_name, phone, y email, asi no se tiene que hacer tanta validacion en php, y te arroja todos los resultados 
                        if ($contact){
                            //aqui valido si consiguio algo, coloco el sucess en true, y mando el array con los resultados
                            $return = array('success' => true, "message" => 'Su búsqueda arrojo ('.count($contact). ') resultados',"answer" => $contact);
                        }else{
                            //si no conigue nada, mando el mensaje de que no se mando
                            $return = array('success' => false, "error" => 'Contacto no encontrado', 'errornumber'=> 4);
                        }
                    }else{
                        //aqui si no me enviaron justamente la variable que necesito, que en este caso es buscar
                        $return = array('success' => false, "error" => 'No se ha establecido el elemento a buscar', 'errornumber'=> 2);
                    }
                } else{ 
                    // si da null, es porque el token no existe
                    $return = array('success' => false, "error" => 'Token inválido', 'errornumber'=> 3);
                }
            }else{
                // si no me madan el token de a empresa, no se puede hace nada
                $return = array('success' => false, "error" => 'No se ha ingresado el tokke de la empresa', 'errornumber'=> 4);
            }
        }else{
            //aqui verifico si me estan enviando los datos de get y post 
            $return = array('success' => false, "error" => 'No se han enviado datos', 'errornumber'=> 1);
        }
        //siempre se cambia la variable return, para simplificar codigo
        header('Content-type: text/json');
        header('Content-type: application/json');
        echo json_encode($return);
     }        
        
    
    public function town(){
        if (isset($_POST) && ($_POST) || isset($_GET) && ($_GET)) {
            if(isset($_POST['name']) && ($_POST['name'] != null)){
                //$consult = $this->modelo_universal->select('town', '*', array('name'=>$_POST['name']));
                $name = $_POST['name'];
            }else{
                $name = null;
            }
            if(isset($_POST['state_id']) && ($_POST['state_id'] != null)){
                $state_id = $_POST['state_id'];
                //state_id
            }else{
                $state_id = null;
            }
            if(($name != null) or ($name != '')){
                if(($state_id != null) or ($state_id != '')){
                    $consult = $this->modelo_universal->query("SELECT * FROM town WHERE name = '$name' or state_id = '$state_id'");
                }else{
                    $consult = $this->modelo_universal->query("SELECT * FROM town WHERE name = '$name'");
                }
            }else{
                if(($state_id == null) or ($state_id != '')){
                    $consult = $this->modelo_universal->query("SELECT * FROM town WHERE state_id = '$state_id'");
                }
            }
            //$consult = $this->modelo_universal->select('town', '*', array('name'=>$name, 'state_id'=>$state_id),);
            //$consult = $this->modelo_universal->query("SELECT * FROM town WHERE name = '$name' or state_id = '$state_id'");
            
        }else{
            
        }
        
        if(!empty($consult)){
            //debug('no');
            $return = $consult;
        }else{
            //debug('si');
            $return = array('success' => false, "error" => "no se han encontrado resultados con los datos enviados", 'errornumber'=> 6 );
        }
            
        header('Content-type: text/json');
        header('Content-type: application/json');
        echo json_encode($return);
    }
    
    

}//fin clase api
