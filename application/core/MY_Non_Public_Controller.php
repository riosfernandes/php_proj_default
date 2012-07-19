<?php

class MY_Non_Public_Controller extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->isLogado();
    }   

    function isLogado() {
        $u = TRUE;
        //@todo: definir logica para verificar se o usuário está logado $u = $this->session->userdata('usuario_logado');
        if (!$u) {
            redirect('login');
        }
    }   
    
}

/* End of file MY_Non_Public_Controller.php */
/* Location: ./application/core/MY_Non_Public_Controller.php */