<?php

namespace scMVC\Models;

use Exception;
use scMVC\Models\BaseModel;

class Users extends BaseModel
{
    // Checa o login
    public function check_login($cpf, $password)
    {
        $params = [
            ':cpf' => $cpf
        ];

        $this->db_connect();
/*         $results = $this->query(
            "SELECT id, passwrd FROM users " .
            "WHERE AES_ENCRYPT(:username, '" . MYSQL_AES_KEY."') = name"
            , $params); 
*/

        $results = $this->query(
                    "SELECT cpf, password FROM users WHERE :cpf = cpf"
                    , $params);


        if($results->affected_rows == 0){
             return [
                    'status' => false,
                    
                ];
            }
        // Verifica o password encripitado com o digitado no campo
        if(!password_verify($password, $results->results[0]->password)){
            return [
             'status' => false

            ];
        }
        return [
            'status' => true
        ];
    }

    // Verifica se o cpf já está registrado no banco
    public function check_register(string $cpf):array
    {
        $params = [
            ':cpf' => $cpf
        ];

        $this->db_connect();
        $results = $this->query(
                    "SELECT cpf FROM users WHERE :cpf = cpf"
                    , $params);


        if($results->affected_rows > 0){
             return [
                    'status' => false
                ];
            }

        return [
            'status' => true
        ];
    }

    //Registra o usuário
    public function user_register(array $params)
    {
        $params = [
            ':name' => $params['name'],
            ':gender' => $params['gender'],
            ':born_in' => $params['born_in'],
            ':cpf' => $params['cpf'],
            ':password' => password_hash($params['password'], PASSWORD_DEFAULT)
        ];


            $this->db_connect();
                $results = $this->non_query(
                    "INSERT INTO users (name, sex, born_in, cpf, password, profile) 
                    VALUES (:name, :gender, :born_in, :cpf, :password, 'agent')"
                        , $params);

                if($results->status == 'error'){
                    return [
                        'status' => false,
                        'message' => 'Ocorreu algum erro.'
                   ];
                }

                return [
                    'status' => true,
                    'message' => 'Registrado com sucesso!'
                ];
    }

    //Retorna todos os clientes
    public function get_all_clients()
    {

        $params = [];

        $this->db_connect();
        $results = $this->query(
            "SELECT " .
            "id," . 
            "name, " . 
            "sex, " . 
            "born_in ". 
            "FROM users "
            , $params);

            return [
                'status' => 'success',
                'data' => $results->results
            ];
    }

    // Retorna os dados do cliente
    public function get_user_data(string $cpf)
    {
        $params = [
            ':cpf' => $cpf
        ];
        $this->db_connect();
        $results = $this->query(
            "SELECT " . 
            "id, " . 
            "name, " . 
            "profile ". 
            "FROM users " .
            "WHERE cpf = :cpf"
            , $params);

            return [
                'status' => 'success',
                'data' => $results->results[0]
            ];
        }
        
/*
    public function set_user_last_login($id){
            
        $params = [
                'id' => $id
        ];
        $this->db_connect();
        $results = $this->non_query(
            "UPDATE agents SET " . 
            "last_login = NOW() " .
            "WHERE id = :id"
            , $params);
            
        return $results;
    }
 
    public function delete_agent_client($id_client){
        
        $params = [
            ':id' => $id_client,
        ];
        $this->db_connect();
        return $this->non_query("DELETE FROM persons WHERE id = :id", $params);
    } 

 */   
    /*     public function get_results(){
            $params = [
                'profile' => 'admin'
            ];
            $this->db_connect();
            return $this->query("SELECT * FROM agents WHERE profile =:profile", $params);
        } */

}