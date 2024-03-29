<?php

namespace App\adms\Models;

use PDO;
use App\adms\Models\helpers\Connection;

class ViewUser
{
    private bool $result = false;
    private object $conn;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function viewInfo(int $id): ?array
    {
        $this->conn = Connection::connect();
        return $this->detailsUser($id);  
    }

    private function detailsUser(int $id): ?array
    {
        $queryUser = "SELECT 
                        users.id AS id,
                        users.name AS name,
                        users.email AS email,
                        users.user AS user,
                        users.image AS image,
                        users.nickname AS nickname,
                        users.created_at AS created_at,
                        users.updated_at AS updated_at,
                        us.situation_name AS situation,
                        c.color AS color,
                        al.access_level AS access_level
                      FROM users 
                      INNER JOIN users_situation AS us
                        ON users.user_situation_id = us.id
                      INNER JOIN colors AS c
                        ON us.color_id = c.id
                      INNER JOIN access_levels AS al
                        ON users.access_level_id = al.id
                      WHERE users.id = :id 
                      AND al.order_level > :order_level
                      LIMIT 1";
                      
        $stmt = $this->conn->prepare($queryUser);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':order_level', $_SESSION['order_level'], PDO::PARAM_INT);
        $stmt->execute();
        $dataResult = (array) $stmt->fetch(PDO::FETCH_ASSOC);
       
        if(! empty($dataResult)) {
            $this->result = true;
            return $dataResult;
        }

        $_SESSION['msg'] = "<div class='alert alert-danger'>Usuário não encontrado!</div>";
        $this->result = false;
        return [];
    }
}