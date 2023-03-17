<?php

namespace scMVC\Controllers;

use Monolog\Logger;
use scMVC\Controllers\BaseController;
use scMVC\Models\Coupons;

class Coupon extends BaseController
{
    //
    public function my_coupons(){

        if(!check_Session()){
            header('Location: index.php');
        }

        $user_id = $_SESSION['user']->id;
        $model = new Coupons();
        $results = $model->get_my_coupons($user_id);

        $data['user'] = $_SESSION['user'];
        $data['my_coupons'] = $results['data'];

        $this->view('layouts/html_header');
        $this->view('navbar', $data);
        $this->view('list_my_coupons', $data);
        $this->view('footer');
        $this->view('layouts/html_footer');
    }

    //
    public function coupons_add_new_frm(){

        if(!check_Session()){
            header('Location: index.php');
        }

        $data['user'] = $_SESSION['user'];

        $this->view('layouts/html_header');
        $this->view('navbar', $data);
        $this->view('insert_coupon_frm', $data);
        $this->view('footer');
        $this->view('layouts/html_footer');
    }

    //
    public function new_coupon_submit() {

        if(!check_Session() || $_SERVER['REQUEST_METHOD'] != 'POST'){
            header('Location: index.php');
        }

        $data['user'] = $_SESSION['user'];
        $data['errors'] = [];
        $formData = $_POST;
        $user_id = $data['user']->id;
        $resultformValidation = coupon_form_validation($formData);

        if(!$resultformValidation['status'] || !empty($resultformValidation['errors'])){

            $data['errors'] =  $resultformValidation['errors'];

            $this->view('layouts/html_header');
            $this->view('navbar', $data);
            $this->view('insert_coupon_frm', $data);
            $this->view('footer');
            $this->view('layouts/html_footer');
        }

        if($resultformValidation['status'] AND empty($resultformValidation['errors'])){

            $model = new Coupons();
            $model->insert_coupon_to_database($formData);

            $resultValidCoupons = $model->get_user_valid_coupons($data['user']->id); 
            $totalActiveCouponValor = $resultValidCoupons->results[0]->total;

            if($totalActiveCouponValor >= 300){
               
                $numbers = floor($totalActiveCouponValor/300);
                $number= 0;

                for ($i=0; $i < $numbers; $i++) { 
                    $number=$number +1;
                    $myuuid = guidv4();
                    $model->create_luck_numbers($myuuid, $user_id);
                }
                   
                $model->deactive_coupons($user_id);
            }
           
            $data['status'] =  'success';
            $data['message'] = 'Cupom cadastrado com sucesso!';

            //SALVA NO LOG
            logger("O usuário de id: " . $user_id . " acaba de cadastrar o cupom: " . $formData['code'], 'info');

            $this->view('layouts/html_header');
            $this->view('navbar', $data);
            $this->view('insert_coupon_frm', $data);
            $this->view('footer');
            $this->view('layouts/html_footer');
        }
    }

    ///
    public function list_active_coupons(){

        if(!check_Session()){
            header('Location: index.php');
        }

        $user_id = $_SESSION['user']->id;
        $model = new Coupons();
        $results = $model->get_active_coupons();

        $data['user'] = $_SESSION['user'];
        $data['active_coupons'] = $results['data'];

        $this->view('layouts/html_header');
        $this->view('navbar', $data);
        $this->view('list_active_coupons', $data);
        $this->view('footer');
        $this->view('layouts/html_footer');

    }

    //
    public function client_coupons($id){

        if(!check_Session()){
            header('Location: index.php');
        }
        
        $model = new Coupons();
        $results = $model->get_client_coupons($id);

        $data['user'] = $_SESSION['user'];
        $data['coupons'] = $results['data'];

        $this->view('layouts/html_header');
        $this->view('navbar', $data);
        $this->view('list_client_coupons', $data);
        $this->view('footer');
        $this->view('layouts/html_footer');

    }

    public function export_csv($id=null)
    {
    
        if(!check_Session()){
            header('Location: index.php');
        }

        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            $this->my_coupons();
            return;
        }   

        $json_data = $_POST['data'];
        // Decoda o JSON DE VOLTA PRA ARRAY
        $data = json_decode($json_data, true);

        switch ($id) {
            case 'my_coupons':
                // CHAMA A FUNÇÂO PRA CONVERTER O ARRAY EM CSV E FAZ O DOWNLOAD
                export_csv($data, array('Código', 'Valor', 'Loja', 'Data da Criação', 'Status'), 'meus-cupons');
                $this->my_coupons();
                break;
            case 'active_coupons':
                export_csv($data, array('Código', 'Valor', 'Loja', 'Data da Criação', 'Status'), 'cupons_ativos');
                $this->list_active_coupons();
                break;        
            default:
                break;
        }

    }
    
}