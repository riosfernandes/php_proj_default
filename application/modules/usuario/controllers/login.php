<?php

//@todo: salvar na base o id da session?
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends MY_Controller_Admin {

    var $error;

    function __construct() {
        parent::__construct();
    }

    function index() {
        //validation rules
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'username', 'required');
        $this->form_validation->set_rules('password', 'password', 'trim|required|md5');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        //m�todo utilizando o post via jquery
        $this->loadLogin();
    }

    private function loadLogin() {
        $data['titulo'] = 'Login';
        $data['css_files'] = array('login');
        $data['js_files'] = array('jquery-1.7.1.min', 'jquery.tools.min', 'login');

        $this->load->view('login', $data);
    }

    /* m�todo utilizando javascript para realizar
     * o post.
     */
    function entrar() {
        //obter o usu�rio e a senha e tentar efetuar o login
        $usuario = isset($_POST['username']) ? $_POST['username'] : '';
        $senha = isset($_POST['password']) ? $_POST['password'] : '';

        //validar
        if ($usuario == "" || $senha == "") {
            $var = array(
                'success' => false,
                'message' => htmlentities('Dados do usu�rio n�o informados')
            );
            echo json_encode($var);
            return false;
        }
        
         $this->load->model('usuario_model');

        $u = new Usuario_model();
        $u->where('login', $usuario);
//      @todo: permitir ao usu�rio trocar o hash padr�o
        $u->where('senha', md5($senha));
//      @todo: Recuperar da configura��o do sistema
//      $u->where('situacao_id', 2);
        $u->get(1);

        if ($u->result_count() != 1) {
            $var = array(
                'success' => false,
                'message' => htmlentities('Usu�rio n�o localizado')
            );
            echo json_encode($var);
            return false;
        }

//        @todo: recuperar do sistema
        switch ($u->situacao_id) {
            case 1: //Novo
            case 4: //Expirado
                $this->trocar_senha($u);
                break;
            case 2: //Ativo
                break;
            case 3: //Inativo
            default:
                //@todo: dar mensagem e voltar a tela de login
                return false;
                break;
        }

        // insere data e hora do ultimo login
        $u->update('ultimo_acesso', date("Y-m-d H:i:s"));

        /* salva na session o usu�rio, o cooke s� guarda 4KB, 
         * talvez seja necess�rio salvar apenas o id do usuario
         */
        $usuario = array(
            'id' => $u->id,
            'nome' => $u->nome,
            'login' => $u->login,
            'email' => $u->email,
            'tipo_usuario_id' => $u->tipo_usuario_id,
            'situacao_id' => $u->situacao_id,
            'senha' => $u->senha,
            'ultima_troca' => $u->ultima_troca,
            'ultimo_acesso' => $u->ultimo_acesso
        );

        $this->session->set_userdata('usuario_logado', $usuario);

        $var = array(
            'success' => true,
            'usuario' => array('login' => $usuario['login'], 'nome' => $usuario['nome'])
        );
        echo json_encode($var);
        return TRUE;
    }

    function trocar_senha() {
//        @todo: trocar senha
//        insere data e hora do ultima troca de login
//        $u->update('ultima_troca', date("Y-m-d H:i:s"));
    }

    function logout() {
        $this->session->unset_userdata('usuario_logado');
        $this->session->sess_destroy();
        redirect('login');
    }

    function recuperar_senha() {
//        @todo: fazer a recupera��o de senha enviando e-mail para o usu�rio
//        try {
//            $login = isset($_POST['login']) ? $_POST['login'] : '';
//            $email = isset($_POST['email']) ? $_POST['email'] : '';
//
//            $usuario = new Usuario_model();
//            $usuario->where('login', $login);
//            $usuario->where('email', $email);
//            $usuario->get(1);
//
//            if ($usuario->result_count() != 1) {
//                $var = array(
//                    'success' => false,
//                    'message' => htmlentities('Usu�rio informado n�o localizado')
//                );
//                echo json_encode($var);
//                return false;
//            }
//
//            $this->enviar_email(
//                    $usuario->email,
//                    $usuario->senha);
//
//            $var = array(
//                'success' => true
//            );
//            echo json_encode($var);
//            return true;
//        } catch (Exception $e) {
//            $var = array(
//                'success' => false,
//                'message' => htmlentities($e->getMessage())
//            );
//            echo json_encode($var);
//            return false;
//        }
    }
    
    private function enviar_email($email, $senha) {
        throw new Exception('testando excess�o');
        
        /* @todo: implementar o envio de email para o usu�rio
         * @todo: logar a solicita��o de senha por email informando
         * o usu�rio.
         */
        
        $mensagem = 'Senha para acesso solicitada: ' + senha;

        $this->load->library('email');

        $this->email->from('voce@seu-site.com', 'Seu Nome');
        $this->email->to(email);
        $this->email->cc('outro@outro-site.com');
        $this->email->bcc('fulano@qualquer-site.com');

        $this->email->subject('Recupera��o de senha');
        $this->email->message($mensagem);

        $this->email->send();

//        echo $this->email->print_debugger();
    }
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */