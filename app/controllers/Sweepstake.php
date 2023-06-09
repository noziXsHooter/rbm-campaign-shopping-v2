<?php

namespace scMVC\Controllers;

use Exception;
use scMVC\Controllers\BaseController;
use scMVC\Models\Sweepstakes;

class Sweepstake extends BaseController
{
    //Página de retorno (Página principal do sorteio)
    public function index()
    {

        if(!check_Session()){
            header('Location: index.php');
        }

        $model = new Sweepstakes();
    
        $data['user'] = $_SESSION['user']; // Pega os dados do usuário da sessão
        $sweepstakeStatus = $model->sweepstake_status();
        $data['sweepstake_status'] = $sweepstakeStatus['data'][0]->status; //Pega o status do Sorteio
        $data['sweepstakes_data'] = $model->get_sweepstakes()['data']; //Pega a lista dos sorteios realizados
        $data['sweepstake_winner'] = [];

        if($data['sweepstake_status']){
            $data['sweepstake_winner'] = $data['sweepstakes_data'][0];
        }

        $this->view('layouts/html_header');
        $this->view('navbar', $data);
        $this->view('sweepstakes', $data);
        $this->view('footer');
        $this->view('layouts/html_footer');

    }

    //View de sorteio do cliente
    public function clients_index()
    {
    
        if(!check_Session()){
            header('Location: index.php');
        }

        $model = new Sweepstakes();
    
        $data['user'] = $_SESSION['user']; // Pega os dados do usuário da sessão
        $sweepstakeStatus = $model->sweepstake_status();
        $data['sweepstake_status'] = $sweepstakeStatus['data'][0]->status; //Pega o status do Sorteio
        $data['sweepstakes_data'] = $model->get_sweepstakes()['data']; //Pega a lista dos sorteios realizados
        $data['sweepstake_winner'] = [];

        if($data['sweepstake_status']){
            $data['sweepstake_winner'] = $data['sweepstakes_data'][0];
        }elseif(!$data['sweepstake_status']){
            $data['sweepstake_winner'] = [];
        }

        $this->view('layouts/html_header');
        $this->view('navbar', $data);
        $this->view('sweepstake', $data);
        $this->view('footer');
        $this->view('layouts/html_footer');
    
    }
  
    /**
     * Lida com as ações dos botões de sorteio
     *
     * @param  string $id
     * @return void
     */
    public function sweepstake_handlers(string $id= null)
    {

        if(!check_Session() || $_SESSION['user']->profile != 'admin' ){
            header('Location: index.php');
        }

        $model = new Sweepstakes();
        $data['user'] = $_SESSION['user'];
        $sweepstakeStatus = $model->sweepstake_status();
        $data['sweepstake_status'] = $sweepstakeStatus['data'][0]->status; //Pega o status do Sorteio
        $data['sweepstakes_data'] = $model->get_sweepstakes()['data']; //Pega a lista dos sorteios realizados
        $data['sweepstake_winner'] = [];
        $data['sweepstake_message'] = [];

        switch ($id) {

            case 'raffle': //REALIZA O SORTEIO TEÓRICO

                if(!$data['sweepstake_status']){
                    $resultRaffle = raffle();

                    if(!$resultRaffle['status']){

                        $data['sweepstake_message'] =  $resultRaffle;

                        $this->view('layouts/html_header');
                        $this->view('navbar', $data);
                        $this->view('sweepstakes', $data);
                        $this->view('footer');
                        $this->view('layouts/html_footer');
                    }

                    $data['sweepstake_winner'][] = $resultRaffle['message'] ? $resultRaffle['message'] : '';

                    //Salva no log
                    logger("Um sorteio teórico acaba de ser realizado. Dados do ganhador: " . $data['sweepstake_winner'][0], 'info');

                    $this->view('layouts/html_header');
                    $this->view('navbar', $data);
                    $this->view('sweepstakes', $data);
                    $this->view('footer');
                    $this->view('layouts/html_footer');
                }

                $data['sweepstake_message'] = [
                            "status" => false,
                            "message" => 'O sorteio já foi realizado.'
                        ];
                        
                $data['sweepstake_winner'] = $data['sweepstakes_data'][0];

                $this->view('layouts/html_header');
                $this->view('navbar', $data);
                $this->view('sweepstakes', $data);
                $this->view('footer');
                $this->view('layouts/html_footer');

                break;


            case 'announce_sweepstake_winner':  //Finaliza o sorteio - limpa os números da sorte e muda o status do sorteio 

                $winnerNumber = $_SESSION['winnerNumber'];
                $resultWinnerData =  $model->search_for_hash_owner($winnerNumber); //Procura pelo cliente dono do número da sorte

                if(!$resultWinnerData->results){

                    $data['sweepstake_message'] = ['status'=> false, 'message' => 'Não há números a serem sorteados!'];
                     //Salva no log
                     logger("Houve uma tentativa de sorteio sem números da sorte. ", 'notice');

                    $this->view('layouts/html_header');
                    $this->view('navbar', $data);
                    $this->view('sweepstakes', $data);
                    $this->view('footer');
                    $this->view('layouts/html_footer');

                }

                $sweepstakeParams = [
                    'user_id'   => $resultWinnerData->results[0]->id,
                    'hash'      => $resultWinnerData->results[0]->hash,
                    'name'       => $resultWinnerData->results[0]->name
                ];

                if(!empty($resultWinnerData->results[0])){

                    $resultInsert = $model->insert_sweepstake_to_database($sweepstakeParams); //Insere sorteio no banco
                    $resultWinner = announce_sweepstake_winner(); // Anuncia ganhador do sorteio
                }

                $data['sweepstake_message'] = $resultWinner;
                $data['sweepstakes_data'] = $model->get_sweepstakes()['data']; //Pega a lista dos sorteios realizados

                $this->view('layouts/html_header');
                $this->view('navbar', $data);
                $this->view('sweepstakes', $data);
                $this->view('footer');
                $this->view('layouts/html_footer');
                break;


            case 'enableSweepstake':   //Reabilita para novo sorteio
                
                $resultEnable = enable_sweepstake();
                $data['sweepstake_status'] = $sweepstakeStatus['data'][0]->status; //Pega o status do Sorteio
                /* printData($data['sweepstake_status']); */
                $data['sweepstakes_data'] = $model->get_sweepstakes()['data']; //Pega a lista dos sorteios realizados
                $data['sweepstakes_message'] = $resultEnable;
                /* printData($resultRaffle); */

                $this->view('layouts/html_header');
                $this->view('navbar', $data);
                $this->view('sweepstakes', $data);
                $this->view('footer');
                $this->view('layouts/html_footer');
                break;
            default:
                throw new Exception("Essa ação não existe.");
                break;
        }

        $this->view('layouts/html_header');
        $this->view('navbar', $data);
        $this->view('sweepstakes', $data);
        $this->view('footer');
        $this->view('layouts/html_footer');
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
            $this->index();
            return;
        }   

        $json_data = $_POST['data'];
        // Decoda o json de volta em array
        $data = json_decode($json_data, true);

        // Chama a função pra converter o array em csv e faz o download
         export_csv($data, array('Nome', 'Número da Sorte', 'Data da Criação'), 'sorteio-realizados');

        $this->index();

    }

    
}