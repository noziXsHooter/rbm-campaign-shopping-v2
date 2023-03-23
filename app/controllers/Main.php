<?php

namespace scMVC\Controllers;
use scMVC\Controllers\BaseController;
use scMVC\Models\Agents;
use scMVC\Models\Users;

class Main extends BaseController
{
    //View de retorno (Formulário de login)
    public function index()
    {

        if(!check_session())
        {
            $this->login_frm();
            return;
        }
        
        $data['user'] = $_SESSION['user'];

        $this->view('layouts/html_header');
        $this->view('navbar', $data);
        $this->view('homepage', $data);
        $this->view('footer');
        $this->view('layouts/html_footer');

    }
    //Envia formulário de login para cadastro
    public function login_submit()
    {

        if(check_session()){
            $this->index();
            return;
        }

        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            $this->index();
            return;
        }    
        
        $validation_errors = [];
        if(empty($_POST['text_username']) || empty($_POST['text_password'])){
            $validation_errors[] = "Usuário e senha são obrigatórios";
        }
        
        $cpf = $_POST['text_username'];
        $password = $_POST['text_password'];

        //Implementação
       /*  if(!filter_var($cpf, FILTER_VALIDATE_EMAIL)){
            $validation_errors[] = "O usuário tem que ser um email válido";
        }

        if(strlen($cpf) < 5 || strlen($cpf) > 50){
            $validation_errors[] = "O usuário deve ter entre 5 e 50 caracteres";
        }
        if(strlen($password) < 6 || strlen($password) > 12){
            var_dump(strlen($password));
            $validation_errors[] = "O senha deve ter entre 6 e 12 caracteres";
        } 
        */

        if(!empty($validation_errors)){
            $_SESSION['validation_errors'] = $validation_errors;
            $this->login_frm();
            return;
        }

        $model = new Users();
        $result = $model->check_login($cpf, $password);

        if(!$result['status']){
           

            logger("$cpf - login inválido", 'error');

            $_SESSION['server_error'] = 'Login inválido';
            $this->login_frm();
            return;
        }
 
        logger("$cpf - logou com successo");

        $results = $model->get_user_data($cpf);

        $_SESSION['user'] = $results['data'];

        //SETA O ULTIMO LOGIN DO USUARIO
        /* $results = $model->set_user_last_login($_SESSION['user']->id); */

        $this->index();
    }

    //View formulário de login 
    public function login_frm()
    {
        if(check_session())
        {
            $this->index();
            return;
        }

        $data = [];
        if(!empty($_SESSION['validation_errors'])){
            $data['validation_errors'] = $_SESSION['validation_errors'];
            unset($_SESSION['validation_errors']);
        }

        $this->view('layouts/html_header');
        $this->view('login_frm', $data);
        $this->view('layouts/html_footer');

    }

    // View registro de usuário
    public function register_frm()
    {
        if(check_session())
        {
            $this->index();
            return;
        }

        $data = [];
        if(!empty($_SESSION['validation_errors'])){
            $data['validation_errors'][] = $_SESSION['validation_errors'];
            unset($_SESSION['validation_errors']);
        }

        $this->view('layouts/html_header');
        $this->view('register_frm', $data);
        $this->view('layouts/html_footer');
    }

    //Envia formulário de registro
    public function register_submit()
    {

        if(check_session()){
            $this->index();
            return;
        }

        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            $this->index();
            return;
        }    
        
        $postParams = [
            'name' => $_POST['name'],
            'born_in' => $_POST['born_in'],
            'gender' => $_POST['gender'],
            'cpf' => $_POST['cpf'],
            'password' => $_POST['password'],
        ];

        //Implementação

/*         if(strlen($cpf) < 5 || strlen($cpf) > 50){
            $validation_errors[] = "O usuário deve ter entre 5 e 50 caracteres";
        }
        if(strlen($password) < 6 || strlen($password) > 12){
            var_dump(strlen($password));
            $validation_errors[] = "O senha deve ter entre 6 e 12 caracteres";
        }  
*/

        if(!empty($validation_errors)){
            
            $_SESSION['validation_errors'] = $validation_errors;
            $this->login_frm();
            return;
        }

        $model = new Users();
        $result = $model->check_register($_POST['cpf']); //Verifica se o cpf ja existe na base de dados

        if(!$result['status']){
           
            logger("Falha de registro de usuário. O CPF: " . $_POST['cpf'] . "já existe do banco", 'error');// Salva log
            $_SESSION['validation_errors'] = 'Registro inválido';
            $this->register_frm();
            return;
        }

        $result = $model->user_register($postParams);  //Registra usuário do banco

        if(!$result['status']){

            logger("Falha de registro de usuário - Query Error.", 'critical'); // Salva log
            $_SESSION['validation_errors'] = $result['message'];
            $this->register_frm();
            return;
        }

        logger("O cpf: " . $_POST['cpf'] . " - acabou de se registrar",); // Salva log

        $data['register_success'] = $result['message'];
        $this->view('layouts/html_header');
        $this->view('register_frm', $data);
        $this->view('layouts/html_footer');

    }

    //  Ação de deslogar usuário logado
    public function logout()
    {

        if(!check_session()){
            $this->index();
            return;
        }
        
        logger($_SESSION['user']->name . "- fez logout"); //Salva log
        unset($_SESSION['user']);
        $this->index();
    }

}