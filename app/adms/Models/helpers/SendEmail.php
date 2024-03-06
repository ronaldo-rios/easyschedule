<?php

namespace App\adms\Models\helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class SendEmail
{
    private array $data;
    private bool $result;
    private array $mailInfo;
    private string $fromEmail;
    private int $optionConfigEmail;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function send(array $resultDb, array $data): void
    {
        $this->mailInfo['host'] = $resultDb['host'];
        $this->mailInfo['username'] = $resultDb['username'];
        $this->mailInfo['password'] = $resultDb['password'];
        $this->mailInfo['fromEmail'] = $resultDb['email'];
        $this->mailInfo['fromName'] = $resultDb['name'];
        $this->mailInfo['smtpsecure'] = $resultDb['smtp_secure'];
        $this->mailInfo['port'] = $resultDb['port'];
        $this->fromEmail = $this->mailInfo['fromEmail'];
        $this->optionConfigEmail = (int) $resultDb['id'];
        $this->data = $data;

        /*$this->data['toEmail'] = 'ronaldo@gmail.com';
        $this->data['toName'] = 'Fulaninho';
        $this->data['subject'] = 'Confirmação de cadastro';
        $this->data['contentHtml'] = '<p>Olá <Strong>Fulaninho</strong>! Clique no link para confirmar seu cadastro!</p>';
        $this->data['contentText'] = 'Olá Fulaninho! Clique no link para confirmar seu cadastro!';*/

        $this->sendWithPHPMailer();
    }

    private function sendWithPHPMailer(): void
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            //$mail->SMTPDebug  = SMTP::DEBUG_SERVER;   
            $mail->CharSet = 'UTF-8';                   
            $mail->isSMTP();                                            
            $mail->Host       = $this->mailInfo['host'];                   
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = $this->mailInfo['username'];                   
            $mail->Password   = $this->mailInfo['password'];                             
            $mail->SMTPSecure = $this->mailInfo['smtpsecure'];            
            $mail->Port       = $this->mailInfo['port'];

            //Recipients
            $mail->setFrom($this->mailInfo['fromEmail'], $this->mailInfo['fromName']);
            $mail->addAddress($this->data['toEmail'], $this->data['toName']);     

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $this->data['subject'];
            $mail->Body    = $this->data['contentHtml'];
            $mail->AltBody = $this->data['contentText'];

            $mail->send();
            $this->result = true;  
        } 
        catch (Exception $e) {
            $this->result = false;
        }
    }
}