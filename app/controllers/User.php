<?php

namespace scMVC\Controllers;
use scMVC\Controllers\BaseController;
use scMVC\Models\Agents;
use scMVC\Models\Users;
use TraitViews;

class User extends BaseController
{
    // Lista todos os clientes
    public function list_clients(){

        if(!check_Session() || $_SESSION['user']->profile != 'admin'){
            header('Location: index.php');
        }

        $user_id = $_SESSION['user']->id;
        $model = new Users();
        $results = $model->get_all_clients();
        
        $data['user'] = $_SESSION['user'];
        $data['clients'] = $results['data'];


        //Função de teste para retorno das views
        $traitViews = new TraitViews();
        $traitViews->trait_views('list_clients', $data);

    /*  $this->view('layouts/html_header');
        $this->view('navbar', $data);
        $this->view('list_clients', $data);
        $this->view('footer');
        $this->view('layouts/html_footer');
    */
    }
    
    /**
     * Export csv file
     *
     * @return void
     */
    public function export_csv()
    {
    
        if(!check_Session()){
            header('Location: index.php');
        }

        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            $this->list_clients();
            return;
        }   

        $json_data = $_POST['data'];
        // Decoda o json de volta em array
        $data = json_decode($json_data, true);

        // Chama a função pra converter o array em csv e faz o download
        export_csv($data, array('ID','Nome', 'Gênero', 'Nascimento'), 'clientes');

        $this->list_clients();
    }

}