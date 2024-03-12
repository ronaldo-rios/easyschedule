<?php

namespace App\adms\Models;

use App\adms\Enum\ConfigEmails;
use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\ValidateEmptyField;

class RecoverPassword
{
    private bool $result = false;
    private ?array $data;
    private object $conn;
    private array $emailData;
    private string $firstName;
    private int $optionConfigEmail = ConfigEmails::RECOVER_PASSWORD->value;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function recover(?array $data): void
    {
        $this->data = $data;
        ValidateEmptyField::validateField($this->data);

        if(! ValidateEmptyField::getResult()) {
            return;
        }

        $this->conn = Connection::connect();
        $resultUser = $this->validateUser();

        if(! empty($resultUser)) {
            $this->updateRecoverPassword($resultUser);
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Email não cadastrado na base de dados!</div>";
            $this->result = false;
        }

    }

    private function validateUser(): array
    {
        $query = "SELECT `id`, `name`, `email`, `recover_password` 
                  FROM `users` 
                  WHERE `email` = :email 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':email', $this->data['email'], \PDO::PARAM_STR);
        $stmt->execute();
        $resultUser = $stmt->fetch();

        if ($stmt->rowCount() > 0) {
            return (array) $resultUser;
        }
        
        return [];
    }

    private function updateRecoverPassword(array $resultUser): bool
    {
        $this->data['recover_password'] = password_hash(
            $this->data['email'] . date('Y-m-d H:i:s'), PASSWORD_BCRYPT
        );
        $id = $resultUser['id'];

        $update = "UPDATE `users` 
                  SET `recover_password` = :recover_password,
                      `updated_at` = NOW()
                  WHERE `id` = :id";

        $stmt = $this->conn->prepare($update);
        $stmt->bindValue(':recover_password', $this->data['recover_password'], \PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $this->sendEmail($resultUser);
            return $this->result = true;
        }
        $_SESSION['msg'] = "<div class='alert alert-danger'>
        Link para recuperação de senha não foi enviado. Por favor, tente novamente
        </div>";
        return $this->result = false;   
    }

    private function sendEmail(array $resultUser): void
    {
        $this->emailHtml($resultUser);
        $this->emailText($resultUser);

        AdmsEmailCredencials::readEmailCredencials($this->emailData, $this->optionConfigEmail);

        if (AdmsEmailCredencials::getResult()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>
            Novo link enviado para o e-mail {$resultUser['email']}! Confira sua caixa de entrada.
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
        $this->emailData['subject'] = 'Recuperar Senha';

        $url = URL . 'update-password/index?key=' . $this->data['recover_password'];
        $this->emailData['contentHtml'] = "<a><p>Olá <Strong>{$this->firstName}</strong>! Você solicitou a recuperação de seu acesso. 
        Clique no link para atualizar sua senha!</p>";
        $this->emailData['contentHtml'] .= "<a href='$url'>{$url}</a><br><br>";
    }

    private function emailText(array $result): void
    {
        $url = URL . 'update-password/index?key=' . $this->data['recover_password'];
        $this->emailData['contentText'] = "Olá {$this->firstName}! Você solicitou a recuperação de seu acesso. 
        Clique no link para atualizar sua senha!";
        $this->emailData['contentText'] .= $url . "\n\n";
    }
}