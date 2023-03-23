<?php

namespace scMVC\Controllers;

use Exception;
use scMVC\Controllers\BaseController;
use scMVC\Models\LuckNumbers;

class LuckNumber extends BaseController
{
    //View com todos os números da sorte
    public function luck_numbers()
    {
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

    //View com os números da sorte do usuário
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


    
    /**
     * Export csv file
     *
     * @param  string $id
     * @return void
     */
    public function export_csv(string $id=null)
    {
    
        if(!check_Session()){
            header('Location: index.php');
        }

        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            $this->luck_numbers();
            return;
        }   

        $json_data = $_POST['data'];
        // Decoda o json de volta em array
        $data = json_decode($json_data, true);

        switch ($id) {
            case 'luck_numbers':

                //Elimina os campos id e user_id antes de criar o arquivo csv
                $data = array_map(function($subarray) {
                    unset($subarray['id'], $subarray['user_id']);
                    return $subarray;
                }, $data);

                // Chama a função pra converter o array em csv e faz o download
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