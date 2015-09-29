<?php

class Panel extends CI_Controller {

    public $data = null;
    public $tokkenapp = null;
    
    public function __construct() {
        parent::__construct();
        
        date_default_timezone_set("America/Caracas");

        $this->load->library('form_validation');
        $this->load->model('bcapi');
        $this->load->model('modelo_universal');
        error_reporting(-1);
        ini_set('display_errors', '1');
		
		$this->load->library('bc_api');
        if (isset($_COOKIE['tokkenapp']) && ($_COOKIE['tokkenapp'])) {
            $this->tokkenapp = $_COOKIE['tokkenapp'];
            if (isset($_COOKIE['cnf']) && ($_COOKIE['cnf'])) {
                $this->data['config'] = json_decode($_COOKIE['cnf']);
            }
            $config['tokken'] = $this->tokkenapp;
            $config['tag'] = true;
            $this->load->library('user', $config);
        } else {
            $url = $_SERVER['HTTP_HOST'];
            $ss = explode('www.', $url);
            if (isset($ss[1]) && ($ss[1])) {
                $url = $ss[1];
            }
            $ss = explode(':', $url);
            if (isset($ss[1]) && ($ss[1])) {
                $url = $ss[0];
            }
            $tokken = $this->bc_api->post('url', array('url' => $url));
            if ($tokken && $tokken['success']) {
                setcookie("tokkenapp", $tokken['datos']['tokken']);
                if (isset($tokken['datos']['config'][0])) {
                    $datos = $tokken['datos']['config'][0];
                } else {
                    $datos = array();
                }
                setcookie("cnf", json_encode($datos));
                redirect('./');
            } else {
                echo 'Configure su URL (' . $url . ') en <a href="http://www.pkclick.com" target="_blank">Pkclick.com</a>';
                exit();
            }
         }
         if (!$this->user->conect){
              redirect('http://pkaccount.com/p/?url=116');
         }
    }
    
    public function rif(){
        /*$nrif = 'V-18910941-1';
        $this->load->library('Rif');
        $mp = new Rif($nrif);*/
        
    }
    public function apiajax($function = null){
        
        if ($this->input->post()){
            $curl = $this->bc_api->post($function, $this->input->post());
        }else{
            
        }
        $curl = $this->bc_api->post('direccion_script',null);
        if (isset($curl) && ($curl['success'])){
            $this->data['script']=$curl['answer'];
        }else{
            $this->data['script']=$curl['error'];
        }
        $this->load->view('apiajax', $this->data);
        
    }
    
    
    public function api_(){
        
       
        
        $post = array(
            //'buscar' => 'innovacion',
            //'token'=> 'e06bc77f815d3afdc1fd49356afb4e87',
            'tokkenuser' => $_COOKIE['ip'],
            'company' => 'name company',
            'rif' => 'V-18910941-1',
            'currency' => 1,
            'country' => 1,
            //////////datos de contacto///////
            'first_name' => 'juan',
            'last_name' => 'figueroa',
            'posicion' => 'gerente',
            'di' => '18910941',
            'phone' => '04127396364',
            'email' => 'jfigueroaubv@gmail.com',
            ////////////
            'address'=> 'direccion',
                );
                //debug(md5('2015-09-23 11:44:33'));
        //debug($this->bc_api->post('consultingcompany', $post));
        //debug($this->bc_api->post('contactcheck', $post));
        //debug($this->bc_api->post('town', $post));
        //debug($this->bc_api->post('contactxyz', $post)); // busqueda de contact
        //debug($this->bc_api->post('companyxyz', $post)); // busqueda de company
        
        $company = $this->bc_api->post('addcompany', $post);
        debug($company,false);
        exit();
        $company = $this->bc_api->post('company', $post);
        debug($company,false);
        //debug($company);
        $post1 = array(
            'companyid' => $company['answer'][0]['id'],
            'tokkenuser' => $_COOKIE['ip']
            );
            //Se manda el tokken del usuario, no el id, de la company si se manda el id, porque no se ha guardado tokken en la cokkie
        $h =$this->bc_api->post('module', $post1);
        //echo ($h);
        debug($h);
        
    }
    
    public function signout(){
        session_destroy();
        $this->session->sess_destroy();
        $uri = 'http://pkaccount.com/p/close.php?url=116&ip=' . $_COOKIE['ip'];
        redirect($uri);
    }

    public function header() {
        if(isset($_COOKIE['ip']) || isset($_GET['ip'])){
            if (isset($_GET['ip']) && $_GET['ip'] == 1){
                redirect('http://pkaccount.com/p/?url=116');
            }elseif (isset($_GET['ip']) && $_GET['ip'] != 1){
                redirect('http://benjicode-pkadmin.c9.io/');
            }
        }else{
            redirect('http://pkaccount.com/p/?url=116');
        }
        $this->load->view('header', $this->data);
        
    }
    
    public function sidebar() {
	
        $this->load->view('sidebar', $this->data);
		
    }
    
    public function footer() {
	
        $this->load->view('footer', $this->data);
		
    }
    public function authorization_($tokkn, $ip){
        if($tokkn || $ip){
            $this->db = $this->load->database('pkaccount', true);
            $userid = $this->modelo_universal->select('session', 'user', array('ip' => $ip, 'flag' => 1, 'url'=> 116));
//            debug($this->db->last_query(), false);
            if ($userid) {
                return (int) $userid[0]['user'];
                //return $userid[0];
            } else {
                return false;
            }
        }
    }

    public function index($mensaje = null) {
            $this->header();
            $this->sidebar();
            $this->load->view('index', $this->data);
			$this->footer();
 
    }
    public function companyAdd($mensaje = null) {
        //debug( $this->input->post(),false );
        $this->load->library('form_validation');
        //debug($this->form_validation->run());
        if($this->input->post() == false){
            $this->header();
            $this->db = $this->load->database('default', true);
            $this->data['currency'] = $this->modelo_universal->select('currency', '*');
            $this->data['country'] = $this->modelo_universal->select('country', '*');
            //debug($this->data['country']);
            $datos = $this->bc_api->post('direccion_script',null);
            if (isset($datos) && ($datos['success'])){
                $this->data['mscript']=$datos['answer'];
            }
            $this->load->view('/company/add', $this->data);
			$this->footer();
        }else{
            if($this->validarpost($_SERVER['HTTP_ORIGIN'].'/') == true){
                //debug($this->input->post(),false);
                $us = $this->input->post('name'); 
                
                //aqui abajo: -->
                $this->load->view('/company/add', $this->data);
                //$categorias = $this->bcapi->post('addcompany', array('username' => $us));
                $categorias = $this->bc_api->post('addcompany', $this->input->post());
                //$data json_decode($categorias);
                //debug($data);
                // debug($categorias,false);
            }else{
                exit();
            }
            
        }
        
            
    }
    public function validarpost($url){
        if($url == base_url()){
            return true;
        }else{
            return false;
        }
    }
    public function companyAddPost(){
         debug($_POST,false);
           // $this->load->view('pr/pr');
        
       
        debug( $this->input->post(),false );
    }
     public function companyInviteUser($mensaje = null) {
            $this->header();
            $this->sidebar();
            $this->load->view('/company/inviteUser', $this->data);
			$this->footer();
    }
    public function terminosyCondiciones($mensaje = null) {
            $this->header();
            $this->sidebar();
            $this->load->view('pages/terminos-y-condiciones', $this->data);
			$this->footer();
    }
    
    public function email(){
        $this->load->view('mail/header');
        
        $this->load->view('mail/footer');
    }
    public function configurarFactura($mensaje = null) {
            $this->header();
            $this->sidebar();
            $this->load->view('settings/factura.php', $this->data);
			$this->footer();
    }
    
    public function ctapcobrar($mensaje = null) {
            $this->header();
            $this->sidebar();
            $this->load->view('company/ctapcobrar.php', $this->data);
			$this->footer();
			
    }
     
    public function companyInviteUser_ajax(){
        if($this->input->is_ajax_request()){
            //search
            $search = $this->input->post('search');
            //echo json_encode($search);
            $this->db = $this->load->database('pkaccount', true);
            //juan.figueroa@proyectokamila.com
            //$consult = $this->modelo_universal->select('user', "*", array('email'=>$search));
            //$consult = $this->modelo_universal->query("SELECT * FROM user email LIKE '%" . $search . "%'");
            $consult = $this->modelo_universal->query("SELECT id,user,name,last_name,email,picture FROM  `user` WHERE  `email` LIKE  '%" . $search . "%'");
            //$consult ="SELECT * FROM  `user` WHERE  `email` LIKE  '%" . $search . "%'";
            
            echo json_encode($consult);
            
            $this->db->close();
        }else{
            show_404();
        }
    }

}


?>
