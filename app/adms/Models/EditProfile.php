<?php

namespace App\adms\Models;

use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\UploadImage;
use App\adms\Models\helpers\ValidatePassword;
use App\adms\Models\helpers\ValidateEmptyField;
use App\adms\Models\helpers\ConvertToCapitularString;

class EditProfile
{
    private object $conn;
    private bool $result = false;
    private ?array $data = [];
    private string $encriptPassword;

    public function getResult(): bool
    {
        return $this->result;
    }
  
    public function viewProfile(): array
    {
        $this->conn = Connection::connect();
        return $this->getInfoProfile();
    }

    private function getInfoProfile(): array
    {
        $query = "SELECT `name`, `nickname`, `email`, `image`, `password`, `user`
                  FROM `users` 
                  WHERE `id` = :id LIMIT 1";

        $statement = $this->conn->prepare($query);
        $statement->bindValue(':id', (int) $_SESSION['user_id'], \PDO::PARAM_INT);
        $statement->execute();
        $resultProfile = (array) $statement->fetch(\PDO::FETCH_ASSOC);

        if ($resultProfile) {
            $this->result = true;
            return $resultProfile;
        }
        else {
            return [];
        }
    }

    public function edit(?array $formData): void
    {
        $this->data = $formData;
        $ignoreFields = ['image', 'nickname'];
        ValidateEmptyField::validateField($this->data, $ignoreFields);

        if(ValidateEmptyField::getResult()){
            $this->conn = Connection::connect();
            $user = $this->queryUser();

            if ($user) {
                $this->verifyIfEmailExists($user);
                $this->verifyIfUserExists($user);
            }
        
            if (! $user) {

                ValidatePassword::validate($this->data['password']);
                if (ValidatePassword::getResult() === false) {
                    $this->result = false;
                    return;
                }

                $this->data['image'] = !empty($_FILES['image']['name']) ? $_FILES['image'] : null;
                $this->encriptPassword = password_hash($this->data['password'], PASSWORD_BCRYPT);
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

    private function updateUser(): void
    {
        
        if($this->data['image'] !== null) {
            // Get user details to delete old image if !empty
            $oldImage = $this->fetchCurrentUserImage((int) $this->data['id']);
           
            if ($oldImage) {
                UploadImage::deleteBeforeImage($this->data, $oldImage);
            } 

            $this->data['image'] = UploadImage::uploadUserImage($this->data);
        }
     
        $update = "UPDATE `users`
                        SET `name` = :name, `email` = :email, `password` = :password,
                            `user` = :user, `nickname` = :nickname,
                            `image` = :image, `updated_at` = NOW()
                        WHERE `id` = :id";

        $stmt = $this->conn->prepare($update);
        $stmt->bindValue(':name', ConvertToCapitularString::format($this->data['name']), \PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->data['email'], \PDO::PARAM_STR);
        $stmt->bindValue(':password', $this->encriptPassword, \PDO::PARAM_STR);
        $stmt->bindValue(':user', $this->data['user'], \PDO::PARAM_STR);
        $stmt->bindValue(':nickname', $this->data['nickname'], \PDO::PARAM_STR);
        $stmt->bindValue(':image', $this->data['image'], \PDO::PARAM_STR);
        $stmt->bindValue(':id', (int) $_SESSION['user_id'], \PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount()) {
            $_SESSION['user_name'] = $this->data['name'];
            $_SESSION['user_nickname'] = $this->data['nickname'];
            $_SESSION['user_email'] = $this->data['email'];
            $_SESSION['msg'] = "<p style='color: green;'>Perfil atualizado com sucesso!</p>";            $this->result = true;
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao atualizar usuário!</div>";
            $this->result = false;
        }
    }

    private function fetchCurrentUserImage(int $id): ?string
    {
        $queryImage = "SELECT `image` 
                       FROM `users` WHERE `id` = :id";

        $statement = $this->conn->prepare($queryImage);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetchColumn();
        return !empty($result) ? (string) $result : null;
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
        $sqlUser->bindValue(':id', (int) $_SESSION['user_id'], \PDO::PARAM_INT);
        $sqlUser->execute();
        $user = (array) $sqlUser->fetch(\PDO::FETCH_ASSOC);

        if ($sqlUser->rowCount() > 0) {
            return $user;
        }

        return [];
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