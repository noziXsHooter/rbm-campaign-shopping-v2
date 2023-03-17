<?php

namespace scMVC\Controllers;

use Exception;
use scMVC\Controllers\BaseController;
use scMVC\Models\LuckNumbers;

class LuckNumber extends BaseController
{
    //
    public function luck_numbers(){
        /* printData("luck numbers"); */
        if(!check_Session()){
            header('Location: index.php');
        }

        $user_id = $_SESSION['user']->id;
        $model = new LuckNumbers();
        $results = $model->get_luck_numbers();

        $data['user'] = $_SESSION['user'];
        $data['luck_numbers'] = $results['data'];

        $this->view('layouts/html_header');
        $this->view('navbar', $data);
        $this->view('list_luck_numbers', $data);
        $this->view('footer');
        $this->view('layouts/html_footer');

    }

    //
    public function client_luck_numbers()
    {
        if(!check_Session()){
            header('Location: index.php');
        }

        $user_id = $_SESSION['user']->id;
        $model = new LuckNumbers();
        $results = $model->get_client_luck_numbers($user_id);

        $data['user'] = $_SESSION['user'];
        $data['luck_numbers'] = $results['data'];

        $this->view('layouts/html_header');
        $this->view('navbar', $data);
        $this->view('list_my_luck_numbers', $data);
        $this->view('footer');
        $this->view('layouts/html_footer');
    
    }
    //
    public function export_csv($id=null)
    {
    
        if(!check_Session()){
            header('Location: index.php');
        }

        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            $this->luck_numbers();
            return;
        }   

        $json_data = $_POST['data'];
        // Decoda o JSON DE VOLTA PRA ARRAY
        $data = json_decode($json_data, true);

        switch ($id) {
            case 'luck_numbers':

                //
                $data = array_map(function($subarray) {
                    unset($subarray['id'], $subarray['user_id']);
                    return $subarray;
                }, $data);

                // CHAMA A FUNÇÂO PRA CONVERTER O ARRAY EM CSV E FAZ O DOWNLOAD
                export_csv($data, array('Nome', 'Número da Sorte', 'Gênero', 'Data da Criação'), 'todos-nums-da-sorte');
                $this->luck_numbers();
                break;
            case 'client_luck_numbers':

                $data = array_map(function($subarray) {
                    unset($subarray['id'], $subarray['user_id']);
                    return $subarray;
                }, $data);

                export_csv($data, array('Nome', 'Número da Sorte', 'Gênero', 'Data da Criação'), 'meus-nums-da-sorte');
                $this->client_luck_numbers();
                break;        
            default:
                break;
        }

    }

}