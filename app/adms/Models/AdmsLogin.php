<?php

namespace App\adms\Models;

use PDO;
use App\adms\Models\helpers\Connection;
use App\adms\Enum\UserSituation;

class AdmsLogin
{
    private ?array $data;
    private object $conn;
    private bool $result = false;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function login(array $data = null): void
    {
        $this->data = $data;
        $this->conn = Connection::connect();

        $sqlUser = $this->conn->prepare($this->validateUser());
        $sqlUser->bindValue(':user', trim($this->data['user']), PDO::PARAM_STR);
        $sqlUser->execute();
        $resultUser = $sqlUser->fetch();

        if ($resultUser) {
            $this->validateIfEmailConfirm($resultUser);
        } 
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Usuário ou senha incorretos</div>";
            $this->result = false;
        }
    }

    private function validateUser(): string
    {
        return "SELECT 
                user.id, user.name, user.nickname,
                user.email, user.password, user.image,
                user.user_situation_id, user.access_level_id, 
                access.order_level
                FROM `users` AS user
                INNER JOIN `access_levels` AS access
                    ON user.access_level_id = access.id
                    WHERE UPPER(`user`) = UPPER(:user)
                    LIMIT 1";
    }

    private function validatePassword(?array $resultUser): void
    {
        if (password_verify($this->data['password'], $resultUser['password'])) {
            $_SESSION['user_id']            = $resultUser['id'];
            $_SESSION['user_name']          = $resultUser['name'];
            $_SESSION['user_nickname']      = $resultUser['nickname'];
            $_SESSION['user_email']         = $resultUser['email'];
            $_SESSION['user_image']         = $resultUser['image'];
            $_SESSION['user_situation_id']  = $resultUser['user_situation_id'];
            $_SESSION['access_level']       = $resultUser['access_level_id'];
            $_SESSION['order_level']        = $resultUser['order_level'];
            $this->result = true;
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Usuário ou senha incorretos</div>";
            $this->result = false;
        }
    }
    
    private function validateIfEmailConfirm(array $resultUser)
    {
        $userSituationId = $resultUser['user_situation_id'];
        $message = "";

        $message = match ($userSituationId) {
            UserSituation::CONFIRMED_EMAIL->value => $this->validatePassword($resultUser),
            UserSituation::WAITING_FOR_CONFIRMATION->value => "<div class='alert alert-danger'>Você precisa confirmar seu e-mail para acessar. 
            Clique <a href='" . URL . "new-confirm-email/index'> aqui </a> para reenviar o e-mail de confirmação.</div>",
            UserSituation::NOT_REGISTERED->value => "<div class='alert alert-danger'>Usuário não cadastrado. Entre em contato com a empresa</div>",
            default => "<div class='alert alert-danger'>Usuário inativo. Entre em contato com a empresa</div>",
        };
    
        $_SESSION['msg'] = $message;
    }
}