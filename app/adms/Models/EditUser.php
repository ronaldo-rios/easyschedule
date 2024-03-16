<?php

namespace App\adms\Models;

use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\ValidatePassword;
use App\adms\Models\helpers\ValidateEmptyField;

class EditUser
{
    private bool $result = false;
    private object $conn;
    private ?array $data;

    public function getResult() {
        return $this->result;
    }

    public function viewInfoUser(int $id): ?array
    {
        $this->conn = Connection::connect();
        return $this->detailsUser($id); 
    }

    public function edit(?array $formData): void
    {
        $this->data = $formData;
        $ignoreFields = ['image', 'nickname'];
        ValidateEmptyField::validateField($this->data, $ignoreFields);

        if(ValidateEmptyField::getResult()){
            $this->conn = Connection::connect();
            $user = $this->queryUser();
            var_dump($user);
            if ($user) {
                $this->verifyIfEmailExists($user);
                $this->verifyIfUserExists($user);
            }
        
            if (! $user) {

                $this->data['email'] = trim(filter_var($this->data['email'], FILTER_VALIDATE_EMAIL));

                if (! $this->data['email']) {
                    $_SESSION['msg'] = "<div class='alert alert-danger'>Email inválido!</div>";
                    $this->result = false;
                    return;
                }

                $this->updateUser();
            }
        }
    }

    private function detailsUser(int $id): ?array
    {
        $queryUser = "SELECT `id`, `name`, `email`, `password`,
                             `user`, `image`, `nickname`, `updated_at`
                      FROM `users`
                      WHERE `id` = :id
                      LIMIT 1";

        $stmt = $this->conn->prepare($queryUser);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $dataResult = (array) $stmt->fetch(\PDO::FETCH_ASSOC);

        if (! empty($dataResult)) {
            $this->result = true;
            return $dataResult;
        }

        $_SESSION['msg'] = "<div class='alert alert-danger'>Usuário não encontrado!</div>";
        $this->result = false;
        return [];
    }

    private function queryUser(): array
    {
        $sql = "SELECT `id`, `user`, `email` 
                FROM `users` 
                WHERE `id` <> :id
                AND (
                        (UPPER(`user`) = UPPER(:user)) 
                        OR 
                        (LOWER(`email`) = LOWER(:email))
                    )
                LIMIT 1";

        $sqlUser = $this->conn->prepare($sql);
        $sqlUser->bindValue(':user', $this->data['user'], \PDO::PARAM_STR);
        $sqlUser->bindValue(':email', $this->data['email'], \PDO::PARAM_STR);
        $sqlUser->bindValue(':id', $this->data['id'], \PDO::PARAM_INT);
        $sqlUser->execute();
        $user = (array) $sqlUser->fetch(\PDO::FETCH_ASSOC);

        if ($sqlUser->rowCount() > 0) {
            return $user;
        }

        return [];
    }

    private function updateUser(): void
    {
        $update = "UPDATE `users`
                        SET `name` = :name, `email` = :email,
                            `user` = :user, `nickname` = :nickname,
                            `image` = :image, `updated_at` = NOW()
                        WHERE `id` = :id";

        $stmt = $this->conn->prepare($update);
        $stmt->bindValue(':name', $this->data['name'], \PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->data['email'], \PDO::PARAM_STR);
        $stmt->bindValue(':user', $this->data['user'], \PDO::PARAM_STR);
        $stmt->bindValue(':nickname', $this->data['nickname'], \PDO::PARAM_STR);
        $stmt->bindValue(':image', $this->data['image'], \PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->data['id'], \PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>Usuário atualizado com sucesso!</div>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao atualizar usuário!</div>";
            $this->result = false;
        }
    }

    private function verifyIfEmailExists(?array $userExists): void
    {
        if($userExists['email'] && $userExists['email'] === $this->data['email']) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Email já existe. Tente outro e-mail.</div>";
            $this->result = false;
        } 
    }

    private function verifyIfUserExists(?array $userExists): void
    {
        if($userExists['user'] && $userExists['user'] === $this->data['user']) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Usuário já existe!</div>";
            $this->result = false;
        }
    }
}