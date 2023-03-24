<?php

namespace scMVC\Models;

use Exception;
use scMVC\Models\BaseModel;

class LuckNumbers extends BaseModel
{
    //Retorna os números da sorte
    public function get_luck_numbers()
    {
            $params = [];
            
            $this->db_connect();
            $result = $this->query("SELECT " .
            "u.id, " . 
            "n.user_id, " .
            "u.name, " . 
            "n.hash, " . 
            "u.sex, " . 
            "n.created_at " .
            "FROM luck_numbers AS n " .
            "INNER JOIN users AS u " .
            "ON n.user_id = u.id " .
            "ORDER BY u.name ASC ",
             $params);

            return [
                'status' => 'success',
                'data' => $result->results
            ];
    }

    //Retorna os números da sorte do cliente
    public function get_client_luck_numbers($user_id)
    {

            $params = [
                ':user_id' => $user_id
            ];
            
            $this->db_connect();
            $result = $this->query("SELECT " .
            "u.id, " . 
            "n.user_id, " . 
            "u.name, " . 
            "n.hash, " .
            "u.sex, " . 
            "n.created_at " .
            "FROM luck_numbers AS n " .
            "INNER JOIN users AS u " .
            "ON n.user_id = :user_id " .
            "WHERE u.id = :user_id " .
            "ORDER BY u.name ASC ",
             $params);

                return [
                    'status' => 'success',
                    'data' => $result->results
                ];
    }

}