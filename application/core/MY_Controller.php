<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/*
 * Controller principal que possui functions comuns para todos os outros;
 * OBS> Nâo utilizar nos controllers do applications.
 */

class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        //pode difinir aqui css e js que serão padrão para todas as telas.
        $this->template->set('css_files', array());
        $this->template->set('js_files', array());

        $this->CI = & get_instance();
        $this->session = & $this->CI->session;
    }    

    /*
     * Retorna um js formatado.
     */

    public function get_js_formatado($js_name) {
        $js_name = JSPATH . $js_name . '.js';
        return base_url() . $js_name;
    }

    /*
     * Retorna um css formatado.
     */

    public function get_css_formatado($css_name) {
        $css_name = CSSPATH . $css_name . '.css';
        return base_url() . $css_name;
    }

    /*
     * Converte datas
     */

    function date_converter($_date = null) {
        $format = '/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/';
        if ($_date != null && preg_match($format, $_date, $partes)) {
            return $partes[3] . '-' . $partes[2] . '-' . $partes[1];
        }
        return false;
    }

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */