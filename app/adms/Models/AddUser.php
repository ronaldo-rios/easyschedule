<?php

namespace App\adms\Models;

use App\adms\Enum\AccessLevels;
use App\adms\Enum\ConfigEmails;
use App\adms\Enum\UserSituation;
use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\UploadImage;
use App\adms\Models\helpers\ValidatePassword;
use App\adms\Models\helpers\ValidateEmptyField;
use App\adms\Models\helpers\ConvertToCapitularString;

class AddUser
{
    private bool $result = false;
    private array $data = [];
    private object $conn;
    private string $confirmEmail;
    private array $emailData;
    private string $firstName;
    private int $waitingConfirm = UserSituation::WAITING_FOR_CONFIRMATION->value;
    private int $optionConfigEmail = ConfigEmails::REGISTER_CONFIRMATION->value;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function add(?array $data): void
    {
        $this->data = $data;
        $ignoreFields = ['nickname'];
        ValidateEmptyField::validateField($this->data, $ignoreFields);

        if(ValidateEmptyField::getResult()){
            $this->conn = Connection::connect();

            $sqlUser = $this->queryUser();

            if ($sqlUser) {
                $this->verifyIfEmailExists($sqlUser);
                $this->verifyIfUserExists($sqlUser);
            }

            if (! $sqlUser) {
                ValidatePassword::validate($this->data['password']);
                if (ValidatePassword::getResult() === false) {
                    $this->result = false;
                    return;
                }

                $this->data['nickname'] = !empty($this->data['nickname']) ? $this->data['nickname'] : null;
                $encriptPassword = password_hash($this->data['password'], PASSWORD_BCRYPT);
                $email = trim(filter_var($this->data['email'], FILTER_VALIDATE_EMAIL));
                $this->confirmEmail = password_hash($encriptPassword . date('Y-m-d H:i:s'), PASSWORD_BCRYPT);

                if (! $email) {
                    $_SESSION['msg'] = "<div class='alert alert-danger'>Email inválido!</div>";
                    $this->result = false;
                    return;
                }

                $insert = $this->insertUser($email, $encriptPassword, $this->confirmEmail, $this->waitingConfirm);

                if ($insert) {
                    $this->sendEmail();
                }
                else {
                    $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao cadastrar usuário!</div>";
                    $this->result = false;
                }
            }
        }
        else {
            $this->result = false;
        }
    }

    private function queryUser(): array
    {
        $sql = "SELECT `id`, `user`, `email` 
                FROM `users` 
                WHERE 
                (UPPER(`user`) = UPPER(:user)) OR (`email` = :email)
                LIMIT 1";

        $sqlUser = $this->conn->prepare($sql);
        $sqlUser->bindValue(':user', $this->data['user'], \PDO::PARAM_STR);
        $sqlUser->bindValue(':email', $this->data['email'], \PDO::PARAM_STR);
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

    private function insertUser($email,$encriptPassword, $confirmEmail, $situation): string
    {

        $insert = "INSERT INTO `users` 
            (   
                `name`, `nickname`, 
                `email`, `user`, `password`, 
                `confirm_email`, `access_level_id`, 
                `user_situation_id`, `created_at`
            ) 
            VALUES 
            (
                :name, UPPER(:nickname), 
                LOWER(:email), UPPER(:user), :password, 
                :confirm_email, :access_level, :user_situation, NOW()
            )";

        $sqlInsert = $this->conn->prepare($insert);
        $sqlInsert->bindValue(':name', ConvertToCapitularString::format($this->data['name']), \PDO::PARAM_STR);
        $sqlInsert->bindValue(':nickname', $this->data['nickname'], \PDO::PARAM_STR);
        $sqlInsert->bindValue(':email', $email, \PDO::PARAM_STR);
        $sqlInsert->bindValue(':user', trim($this->data['user']), \PDO::PARAM_STR);
        $sqlInsert->bindValue(':password', $encriptPassword, \PDO::PARAM_STR);
        $sqlInsert->bindValue(':confirm_email', $confirmEmail, \PDO::PARAM_STR);
        $sqlInsert->bindValue(':access_level', AccessLevels::USER_DEFAULT->value, \PDO::PARAM_INT);
        $sqlInsert->bindValue(':user_situation', $situation, \PDO::PARAM_INT);
        return $sqlInsert->execute();
    }

    private function sendEmail(): void
    {
        $this->contentEmailHtml();
        $this->contentEmailText();

        AdmsEmailCredencials::readEmailCredencials($this->emailData, $this->optionConfigEmail);
        if(AdmsEmailCredencials::getResult()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>
            Usuário cadastrado com sucesso! Um email de confirmação foi enviado para o email informado.
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

    private function contentEmailHtml(): void
    {
        $name = explode(" ", $this->data['name']);
        $this->firstName = $name[0];
        $this->emailData['toEmail'] = $this->data['email'];
        $this->emailData['toName'] = $this->data['name'];
        $this->emailData['subject'] = 'Confirmação de cadastro';

        $url = URL . 'confirm-email/index?key=' . $this->confirmEmail;
        $this->emailData['contentHtml'] = "<a><p>Olá <Strong>{$this->firstName}</strong>! Clique no link para confirmar seu cadastro!</p>";
        $this->emailData['contentHtml'] .= "<a href='$url'>{$url}</a><br><br>";
    }

    private function contentEmailText(): void
    {
        $url = URL . 'conf-email/index?key=' . $this->confirmEmail;
        $this->emailData['contentText'] = "Olá {$this->firstName}! Clique no link para confirmar seu cadastro!";
        $this->emailData['contentText'] .= $url . "\n\n";
    }
}