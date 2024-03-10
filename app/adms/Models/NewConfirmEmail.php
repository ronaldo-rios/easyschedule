<?php

namespace App\adms\Models;

use PDO;
use App\adms\Models\helpers\SendEmail;
use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\ValidateEmptyField;

class NewConfirmEmail
{
    private ?array $data = null;
    private string $firstName;
    private array $emailData;
    private bool $result = false;
    private string $confirmEmail;
    private object $conn;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function newConfirmEmail(?array $data): void
    {
        $this->data = $data;
        ValidateEmptyField::validateField($this->data);

        if(! ValidateEmptyField::getResult() || ValidateEmptyField::getResult() === false) {
            return;
        }
        
        $this->conn = Connection::connect();
        $resultUser = $this->queryUserInfo();
      
        if (! empty($resultUser)) {
            $this->validateNewConfirmEmail($resultUser);
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Email não cadastrado na base de dados!</div>";
            $this->result = false;
        }
    }

    private function queryUserInfo(): array
    {
        $sql = "SELECT `id`, `name`, `email`, `confirm_email` 
                FROM `users` 
                WHERE LOWER(`email`) = LOWER(:email)
                LIMIT 1";
        
        $resultUserInfo = $this->conn->prepare($sql);
        $resultUserInfo->bindValue(':email', $this->data['email'], \PDO::PARAM_STR);
        $resultUserInfo->execute();
        $finalResult = $resultUserInfo->fetch(PDO::FETCH_ASSOC);
        
        if ($resultUserInfo->rowCount() > 0) {
            return (array) $finalResult;
        }
        
        return [];
    }

    private function validateNewConfirmEmail(array $result): void
    {
        if (empty($result['confirm_email'] || $result['confirm_email'] === null)) {
            $this->registerNewHashKey($result);
            $this->result = true;
        }
        else {
            $this->sendNewConfirmEmail($result);
        }
    }

    private function registerNewHashKey(array $result): bool
    {
        $id = $result['id'];
        $newKey = password_hash($result['email'] . date('Y-m-d H:i:s'), PASSWORD_BCRYPT);

        $update = "UPDATE `users` 
                    SET `confirm_email` = :confirm_email, 
                       `updated_at` = NOW() 
                    WHERE `id` = :id";

        $updateHashKey = $this->conn->prepare($update);
        $updateHashKey->bindValue(':confirm_email', $this->confirmEmail, \PDO::PARAM_STR);
        $updateHashKey->bindValue(':id', $id, \PDO::PARAM_INT);
        $resultUpdate = $updateHashKey->execute();

        if ($resultUpdate) {
            $result['confirm_email'] = $newKey;
            $this->sendNewConfirmEmail($result);
            return true;
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao enviar novo link. Por favor, tente novamente.</div>";
            return false;
        }
    }

    private function sendNewConfirmEmail(array $result): void
    {
        $this->emailHtml($result);
        $this->emailText($result);

        AdmsEmailCredencials::readEmailCredencials($this->emailData);
        if(AdmsEmailCredencials::getResult()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>
            Novo link enviado para o e-mail {$result['email']}! Confira sua caixa de entrada.
            </div>";
            $this->result = true;
        } 
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>
            Erro ao enviar e-mail de confirmação! Entre em contato com " . ADM_EMAIL .
            "</div>";
            $this->result = false;
        }
        
    }

    private function emailHtml(array $result): void
    {
        $name = explode(" ", $result['name']);
        $this->firstName = $name[0];
        $this->emailData['toEmail'] = $result['email'];
        $this->emailData['toName'] = $result['name'];
        $this->emailData['subject'] = 'Confirmação de cadastro';

        $url = URL . 'confirm-email/index?key=' . $result['confirm_email'];
        $this->emailData['contentHtml'] = "<a><p>Olá <Strong>{$this->firstName}</strong>! Clique no link para confirmar seu cadastro!</p>";
        $this->emailData['contentHtml'] .= "<a href='$url'>{$url}</a><br><br>";
    }

    private function emailText(array $result): void
    {
        $url = URL . 'conf-email/index?key=' . $result['confirm_email'];
        $this->emailData['contentText'] = "Olá {$this->firstName}! Clique no link para confirmar seu cadastro!";
        $this->emailData['contentText'] .= $url . "\n\n";
    }
}