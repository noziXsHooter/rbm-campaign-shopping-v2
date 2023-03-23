<?php

namespace scMVC\Models;

use Exception;
use scMVC\Models\BaseModel;

class Coupons extends BaseModel
{
    //Retorn os cupons do usuario
    public function get_my_coupons(int $user_id):array
    {
            $params = [
                ':user_id' => $user_id
            ];
            $this->db_connect();
            $results = $this->query(
            "SELECT " . 
            "code, " . 
            "valor, ". 
            "store, " .
            "date_time, " .
            "status " .
            "FROM coupons " .
            "WHERE :user_id = user_id " .
            "ORDER BY status DESC" 
            , $params);

            return [
                'status' => 'success',
                'data' => $results->results
            ];
    }

    //Retorna somentos os cupons ativos(Todos)
    public function get_active_coupons():array
    {
            $params = [];
            $this->db_connect();
            $results = $this->query(
                "SELECT " . 
                "code, " . 
                "valor, ". 
                "store, " .
                "date_time, " .
                "status " .
                "FROM coupons " .
                "WHERE status = 1 " .
                "ORDER BY date_time DESC"
                , $params);

                return [
                    'status' => 'success',
                    'data' => $results->results
                ];
    }

    //Retorna os cupons do cliente selecionado
    public function get_client_coupons(int $client_id):array
    {
            $params = [
                ':client_id' => $client_id
            ];

            $this->db_connect();
            $results = $this->query(
                "SELECT " . 
                "u.name, ". 
                "c.code, " . 
                "c.valor, ". 
                "c.store, " .
                "c.date_time, " .
                "c.status " .
                "FROM coupons AS c " .
                "RIGHT JOIN users AS u " .
                "ON c.user_id = u.id " .
                "WHERE c.user_id = :client_id " .
                "ORDER BY status ASC",
                 $params);

            return [
                'status' => 'success',
                'data' => $results->results
            ];
    }

    //Registra o cupom no banco
    function insert_coupon_to_database(array $params)
    {
        $formData = [
            ':code'      => $params['code'],
            ':user_id'   => $_SESSION['user']->id,
            ':cpf'       => $params['cpf'],
            ':valor'     => $params['valor'],
            ':store'     => $params['store'],
            ':date_time' => $params['date_time'],
            ':status'    => $params['status']
        ];
    
        $this->db_connect();
        $result = $this->non_query("INSERT INTO coupons (code, user_id, cpf, valor, store, date_time, status) 
        VALUES (:code, :user_id, :cpf, :valor, :store, :date_time, :status)", $formData);
    
        return $result;
    
    }

    //Cria os numeros da sorte
    public function create_luck_numbers(string $guid, int $id)
    {
    
        $params = [
            ':hash'      => $guid,
            ':user_id'   => $id
        ];
    
        try {
    
            $this->db_connect();
            $resultTotal = $this->non_query("INSERT INTO luck_numbers (hash, user_id) VALUES (:hash, :user_id)", $params);
    
            } catch (Exception $e) {
                return [
                    "status" => false,
                    "errors" => $e->getMessage()
            ];
        }
    
    }

    //Desativa os cupons que já foram processados
    public function deactive_coupons(int $id)
    {
        $params = [
            ':user_id' => $id,
        ];
    
        try {
            $this->db_connect();
            $resultTotal = $this->non_query("UPDATE coupons SET status = 0 WHERE user_id = :user_id", $params);
    
        } catch (Exception $e) {
            return [
                "status" => false,
                "errors" => $e->getMessage()
            ];
        }
    }

//-----------------------------------------------------------------
//----------------   QUERY VALIDATION      ------------------------
//-----------------------------------------------------------------

    //Verifica se o cupom já existe
    function couponCodeValidation(int $code)
    {
        $params = [
            ':code' => $code
        ];

        $this->db_connect();
        $result = $this->query("SELECT * FROM coupons WHERE code = :code",
             $params);

        if($result->affected_rows > 0){
            return [
                'status' => true,
            ];
        }else {
            return [
                'status' => false,
            ];
        }
    }


    //Verifica se o cupom existe e se o cpf é o mesmo do usuario logado
    function coupon_and_cpf_validation(array $values)
    {
        $codeParams = [':code' => $values['code']];
        $cpfParams =  [':user_id' => $values['user_id']];

        $couponFormCpf = $values['cpf'];
        $validationErrors = [];

        $this->db_connect();
        $resultCodeQuery = $this->query("SELECT * FROM coupons WHERE code = :code", $codeParams);

        if($resultCodeQuery->status == 'success' AND $resultCodeQuery->affected_rows > 0)
        {
            $validationErrors[] = 'Esse cupom já foi cadastrado.';
        }

        $resultCpfQuery = $this->query("SELECT cpf FROM users WHERE id = :user_id", $cpfParams);

        if($resultCpfQuery->status == 'success' AND $resultCpfQuery->results[0]->cpf != $couponFormCpf)
        {
            $validationErrors[] = 'O cpf deve ser o seu.';
        }

        if(!empty($validationErrors)){

            return [
                'status' => false,
                'errors' => $validationErrors
            ];

        }else {
            return [
                'status' => true,
            ];
        }
    }

    // Retorna os cupons válidos do usuário
    function get_user_valid_coupons(int $id)
    {
        $params = ['user_id' => $id,];

        $this->db_connect();
        $resultTotal = $this->query("SELECT SUM(valor) AS total FROM coupons WHERE user_id = :user_id and status = '1'", $params);

        return $resultTotal;

    }

/*  
    public function delete_agent_client($id_client){
        
        $params = [
            ':id' => $id_client,
        ];
        $this->db_connect();
        return $this->non_query("DELETE FROM persons WHERE id = :id", $params);
    }  */
    /*     public function get_results(){
            $params = [
                'profile' => 'admin'
            ];
            $this->db_connect();
            return $this->query("SELECT * FROM agents WHERE profile =:profile", $params);
    } 
*/

}